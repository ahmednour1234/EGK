<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StorePackageRequest;
use App\Http\Resources\PackageResource;
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
     * @queryParam status string Filter by status (pending_review, approved, rejected, paid, in_transit, delivered, cancelled). Example: pending_review
     * @queryParam statuses array Filter by multiple statuses. Example: ["pending_review","approved"]
     * @queryParam package_type_id int Filter by package type ID. Example: 1
     * @queryParam search string Search in tracking number, receiver name, or description. Example: PKG-102
     * @queryParam pickup_date_from date Filter packages with pickup date from. Example: 2025-11-01
     * @queryParam pickup_date_to date Filter packages with pickup date to. Example: 2025-11-30
     * @queryParam delivery_date_from date Filter packages with delivery date from. Example: 2025-11-01
     * @queryParam delivery_date_to date Filter packages with delivery date to. Example: 2025-11-30
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Packages retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "tracking_number": "PKG-ABC123XYZ",
     *       "status": "pending_review",
     *       "status_label": "Pending Review"
     *     }
     *   ]
     * }
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

        // Handle statuses as array if provided as comma-separated string
        if (isset($filters['statuses']) && is_string($filters['statuses'])) {
            $filters['statuses'] = explode(',', $filters['statuses']);
        }

        $packages = $this->packageRepository->getAll($sender->id, array_filter($filters, fn($value) => $value !== null));

        return $this->success(PackageResource::collection($packages), 'Packages retrieved successfully');
    }

    /**
     * Get Single Package
     * 
     * Get a single package by ID with full details.
     * 
     * @urlParam id int required Package ID. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Package retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "tracking_number": "PKG-ABC123XYZ",
     *     "status": "pending_review"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Package not found",
     *   "data": null
     * }
     */
    public function show(int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $package = $this->packageRepository->getById($id, $sender->id);

        if (!$package) {
            return $this->error('Package not found', 404);
        }

        return $this->success(new PackageResource($package), 'Package retrieved successfully');
    }

    /**
     * Create Package
     * 
     * Create a new package request. The package will be created with "pending_review" status.
     * 
     * @bodyParam pickup_address_id int optional ID of saved pickup address. Example: 1
     * @bodyParam pickup_full_address string required Full pickup address. Example: Hamra Plaza, Bliss Street, 4th Floor
     * @bodyParam pickup_city string required Pickup city. Example: Beirut
     * @bodyParam pickup_date date required Pickup date. Example: 2025-11-03
     * @bodyParam pickup_time time required Pickup time. Example: 14:30
     * @bodyParam delivery_full_address string required Full delivery address. Example: Zahle Industrial Zone, Bldg 22, 3rd Floor
     * @bodyParam delivery_city string required Delivery city. Example: Zahle
     * @bodyParam delivery_date date required Delivery date. Example: 2025-11-04
     * @bodyParam delivery_time time required Delivery time. Example: 15:00
     * @bodyParam receiver_name string required Receiver full name. Example: Elie Haddad
     * @bodyParam receiver_mobile string required Receiver mobile number. Example: +96170234567
     * @bodyParam package_type_id int required Package type ID. Example: 1
     * @bodyParam description string required Package description. Example: Apple AirPods sealed box
     * @bodyParam weight float required Package weight in kg. Example: 0.5
     * @bodyParam compliance_confirmed boolean required Confirmation of compliance. Example: true
     * 
     * @response 201 {
     *   "success": true,
     *   "message": "Package created successfully",
     *   "data": {
     *     "id": 1,
     *     "tracking_number": "PKG-ABC123XYZ",
     *     "status": "pending_review"
     *   }
     * }
     */
    public function store(StorePackageRequest $request): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('packages', 'public');
            $data['image'] = $path;
        }
        
        // Ensure pickup_address_id belongs to sender
        if (isset($data['pickup_address_id'])) {
            $address = $sender->addresses()->find($data['pickup_address_id']);
            if (!$address) {
                return $this->error('Pickup address not found or does not belong to you', 404);
            }
        }
        
        $package = $this->packageRepository->create($sender->id, $data);

        return $this->created(new PackageResource($package->load(['packageType', 'pickupAddress', 'country'])), 'Package created successfully');
    }

    /**
     * Update Package
     * 
     * Update an existing package. Only packages in "pending_review" status can be updated.
     * 
     * @urlParam id int required Package ID. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Package updated successfully",
     *   "data": {
     *     "id": 1,
     *     "tracking_number": "PKG-ABC123XYZ"
     *   }
     * }
     */
    public function update(StorePackageRequest $request, int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        
        $package = $this->packageRepository->getById($id, $sender->id);
        
        if (!$package) {
            return $this->error('Package not found', 404);
        }
        
        // Only allow updates for pending_review packages
        if ($package->status !== 'pending_review') {
            return $this->error('Package can only be updated when status is pending_review', 422);
        }
        
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $image = $request->file('image');
            $path = $image->store('packages', 'public');
            $data['image'] = $path;
        }
        
        // Ensure pickup_address_id belongs to sender
        if (isset($data['pickup_address_id'])) {
            $address = $sender->addresses()->find($data['pickup_address_id']);
            if (!$address) {
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
     * Cancel Package
     * 
     * Cancel a package. Only packages in "pending_review", "approved", or "paid" status can be cancelled.
     * 
     * @urlParam id int required Package ID. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Package cancelled successfully",
     *   "data": {
     *     "id": 1,
     *     "status": "cancelled"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Package cannot be cancelled in its current status",
     *   "data": null
     * }
     */
    public function cancel(int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        
        try {
            $success = $this->packageRepository->cancel($id, $sender->id);
            
            if (!$success) {
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
     * 
     * Soft delete a package. Use cancel endpoint instead for cancelling packages.
     * 
     * @urlParam package int required Package ID. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Package deleted successfully",
     *   "data": null
     * }
     */
    public function destroy(int $package): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $packageModel = $this->packageRepository->getById($package, $sender->id);

        if (!$packageModel) {
            return $this->error('Package not found', 404);
        }

        $packageModel->delete();
        return $this->success(null, 'Package deleted successfully');
    }

    /**
     * Get Active Package
     * 
     * Get the active package for the authenticated sender. Active packages are those that are not delivered or cancelled (pending_review, approved, rejected, paid, in_transit).
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Active package retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "tracking_number": "PKG-ABC123XYZ",
     *     "status": "in_transit",
     *     "status_label": "In Transit",
     *     "pickup_city": "Beirut",
     *     "delivery_city": "Zahle",
     *     "receiver_name": "Elie Haddad",
     *     "receiver_mobile": "+96170234567"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "No active package found",
     *   "data": null
     * }
     */
    public function activePackage(): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $package = $this->packageRepository->getActivePackage($sender->id);

        if (!$package) {
            return $this->error('No active package found', 404);
        }

        return $this->success(new PackageResource($package), 'Active package retrieved successfully');
    }

    /**
     * Get Last Package
     * 
     * Get the most recently created package for the authenticated sender.
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Last package retrieved successfully",
     *   "data": {
     *     "id": 5,
     *     "tracking_number": "PKG-XYZ789ABC",
     *     "status": "delivered",
     *     "status_label": "Delivered",
     *     "pickup_city": "Beirut",
     *     "delivery_city": "Tripoli",
     *     "receiver_name": "John Doe",
     *     "receiver_mobile": "+96170123456"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "No package found",
     *   "data": null
     * }
     */
    public function lastPackage(): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $package = $this->packageRepository->getLastPackage($sender->id);

        if (!$package) {
            return $this->error('No package found', 404);
        }

        return $this->success(new PackageResource($package), 'Last package retrieved successfully');
    }
}

