<?php

namespace App\Http\Controllers\Api;

use App\Events\PackageCompleted;
use App\Http\Requests\Api\StorePackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Repositories\Contracts\PackageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * @group Sender Packages
 *
 * APIs for managing sender packages
 */
class PackageController extends BaseApiController
{
    public function __construct(
        protected PackageRepositoryInterface $packageRepository
    ) {}

    /**
     * Get All Packages
     *
     * Get a list of all packages for the authenticated sender with advanced filtering.
     *
     * @queryParam status string Filter by status (pending_review, approved, rejected, paid, in_transit, delivered, cancelled, completed). Example: pending_review
     * @queryParam statuses array Filter by multiple statuses. Example: ["pending_review","approved"]
     * @queryParam package_type_id int Filter by package type ID. Example: 1
     * @queryParam search string Search in tracking number, receiver name, or description. Example: PKG-102
     * @queryParam pickup_date_from date Filter packages with pickup date from. Example: 2025-11-01
     * @queryParam pickup_date_to date Filter packages with pickup date to. Example: 2025-11-30
     * @queryParam delivery_date_from date Filter packages with delivery date from. Example: 2025-11-01
     * @queryParam delivery_date_to date Filter packages with delivery date to. Example: 2025-11-30
     */
    public function index(Request $request): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $filters = [
            'status' => $request->input('status'),
            'statuses' => $request->input('statuses'),
            'package_type_id' => $request->input('package_type_id'),
            'search' => $request->input('search'),
            'pickup_date_from' => $request->input('pickup_date_from'),
            'pickup_date_to' => $request->input('pickup_date_to'),
            'delivery_date_from' => $request->input('delivery_date_from'),
            'delivery_date_to' => $request->input('delivery_date_to'),
        ];

        if (isset($filters['statuses']) && is_string($filters['statuses'])) {
            $filters['statuses'] = array_values(array_filter(array_map('trim', explode(',', $filters['statuses']))));
        }

        $packages = $this->packageRepository->getAll(
            $sender->id,
            array_filter($filters, fn ($value) => $value !== null && $value !== '')
        );

        return $this->success(PackageResource::collection($packages), 'Packages retrieved successfully');
    }

    /**
     * Get Single Package
     */
    public function show(int $id): JsonResponse
    {
        $user = Auth::guard('sender')->user();
        $package = Package::with(['packageType', 'pickupAddress', 'country', 'sender', 'ticket'])->find($id);

        if (! $package) {
            return $this->error('Package not found', 404);
        }

        // Check if user is sender and package belongs to them
        $isSender = $user->type === 'sender' && $package->sender_id === $user->id;

        // Check if user is traveler and package is linked to their ticket
        $isTraveler = $user->type === 'traveler' && $package->ticket_id && $package->ticket && $package->ticket->traveler_id === $user->id;

        if (! $isSender && ! $isTraveler) {
            return $this->error('Package not found', 404);
        }

        return $this->success(new PackageResource($package), 'Package retrieved successfully');
    }

    /**
     * Create Package
     */
    public function store(StorePackageRequest $request): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $data = $request->validated();

        // Handle image upload (package image)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('packages', 'public');
            $data['image'] = $path;
        }

        // Ensure pickup_address_id belongs to sender
        if (isset($data['pickup_address_id'])) {
            $address = $sender->addresses()->find($data['pickup_address_id']);
            if (! $address) {
                return $this->error('Pickup address not found or does not belong to you', 404);
            }
        }

        $package = $this->packageRepository->create($sender->id, $data);

        return $this->created(
            new PackageResource($package->load(['packageType', 'pickupAddress', 'country'])),
            'Package created successfully'
        );
    }

    /**
     * Update Package
     *
     * Only packages in "pending_review" status can be updated.
     */
    public function update(StorePackageRequest $request, int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $package = $this->packageRepository->getById($id, $sender->id);

        if (! $package) {
            return $this->error('Package not found', 404);
        }

        if ($package->status !== 'pending_review') {
            return $this->error('Package can only be updated when status is pending_review', 422);
        }

        $data = $request->validated();

        // Handle image upload (package image)
        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        // Ensure pickup_address_id belongs to sender
        if (isset($data['pickup_address_id'])) {
            $address = $sender->addresses()->find($data['pickup_address_id']);
            if (! $address) {
                return $this->error('Pickup address not found or does not belong to you', 404);
            }
        }

        try {
            $package = $this->packageRepository->update($id, $sender->id, $data);
            return $this->success(new PackageResource($package), 'Package updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Complete Package (Upload Fees Image)
     *
     * Change package status to "completed" and upload fees proof image (image_fees).
     *
     * @urlParam id int required Package ID. Example: 1
     * @bodyParam image_fees file required Fees proof image. Example: (binary)
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Package completed successfully",
     *  "data": {...}
     * }
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $package = $this->packageRepository->getById($id, $sender->id);

        if (! $package) {
            return $this->error('Package not found', 404);
        }

        // تقدر تغيّر الشرط ده حسب منطقك (مثلاً لازم paid أو in_transit)
        if (in_array($package->status, ['cancelled', 'rejected'], true)) {
            return $this->error('Package cannot be completed in its current status', 422);
        }

        $request->validate([
            'image_fees' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
        ]);

        $updateData = [
            'status' => 'delivered',
            'delivered_at' => now(),
        ];

        // upload fees image if provided
        if ($request->hasFile('image_fees')) {
            $path = $request->file('image_fees')->store('package_fees', 'public');

            // delete old if exists
            if (!empty($package->image_fees)) {
                Storage::disk('public')->delete($package->image_fees);
            }

            $updateData['image_fees'] = $path;
        }

        $package->update($updateData);

        PackageCompleted::dispatch($package->fresh());

        return $this->success(new PackageResource($package), 'Package completed successfully');
    }

    /**
     * Cancel Package
     */
    public function cancel(int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        try {
            $success = $this->packageRepository->cancel($id, $sender->id);

            if (! $success) {
                return $this->error('Package not found', 404);
            }

            $package = $this->packageRepository->getById($id, $sender->id);
            return $this->success(new PackageResource($package), 'Package cancelled successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Delete Package
     */
    public function destroy(int $package): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $packageModel = $this->packageRepository->getById($package, $sender->id);

        if (! $packageModel) {
            return $this->error('Package not found', 404);
        }

        $packageModel->delete();
        return $this->success(null, 'Package deleted successfully');
    }

    /**
     * Get Active Package
     */
    public function activePackage(): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $package = $this->packageRepository->getActivePackage($sender->id);

        if (! $package) {
            return $this->error('No active package found', 404);
        }

        return $this->success(new PackageResource($package), 'Active package retrieved successfully');
    }

    /**
     * Get Last Package
     */
    public function lastPackage(): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $package = $this->packageRepository->getLastPackage($sender->id);

        if (! $package) {
            return $this->error('No package found', 404);
        }

        return $this->success(new PackageResource($package), 'Last package retrieved successfully');
    }
}
