<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreSenderAddressRequest;
use App\Http\Resources\SenderAddressResource;
use App\Repositories\Contracts\SenderAddressRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Sender Addresses
 * 
 * APIs for managing sender addresses
 */
class SenderAddressController extends BaseApiController
{
    public function __construct(
        protected SenderAddressRepositoryInterface $addressRepository
    ) {}

    /**
     * Get All Addresses
     * 
     * Get a list of all addresses for the authenticated sender.
     * 
     * @queryParam type string Filter by address type (home, office, warehouse, other). Example: home
     * @queryParam is_default boolean Filter by default status. Example: true
     * @queryParam search string Search in title, address, city, or area. Example: Beirut
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Addresses retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Home",
     *       "type": "home",
     *       "is_default": true,
     *       "full_address": "Hamra Plaza, Bliss Street, 4th Floor",
     *       "city": "Beirut",
     *       "area": "Hamra"
     *     }
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $filters = [
            'type' => $request->input('type'),
            'is_default' => $request->input('is_default'),
            'search' => $request->input('search'),
        ];

        $addresses = $this->addressRepository->getAll($sender->id, array_filter($filters, fn($value) => $value !== null));

        return $this->success(SenderAddressResource::collection($addresses), 'Addresses retrieved successfully');
    }

    /**
     * Get Single Address
     * 
     * Get a single address by ID.
     * 
     * @urlParam address int required Address ID. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Address retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "title": "Home",
     *     "type": "home",
     *     "is_default": true,
     *     "full_address": "Hamra Plaza, Bliss Street, 4th Floor"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Address not found",
     *   "data": null
     * }
     */
    public function show(int $address): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $address = $this->addressRepository->getById($address, $sender->id);

        if (!$address) {
            return $this->error('Address not found', 404);
        }

        return $this->success(new SenderAddressResource($address), 'Address retrieved successfully');
    }

    /**
     * Create Address
     * 
     * Create a new address for the authenticated sender.
     * 
     * @bodyParam title string required Address title (e.g., Home, Office, Warehouse). Example: Home
     * @bodyParam type string required Address type (home, office, warehouse, other). Example: home
     * @bodyParam is_default boolean optional Set as default address. Example: true
     * @bodyParam full_address string required Full address (Street, building, floor). Example: Hamra Plaza, Bliss Street, 4th Floor
     * @bodyParam mobile_number string optional Mobile number. Example: +96170234567
     * @bodyParam country string optional Country. Example: Lebanon
     * @bodyParam city string required City. Example: Beirut
     * @bodyParam area string optional Area/District. Example: Hamra
     * @bodyParam landmark string optional Landmark. Example: Near AUB Main Gate
     * @bodyParam latitude float optional Latitude. Example: 33.8938
     * @bodyParam longitude float optional Longitude. Example: 35.5018
     * 
     * @response 201 {
     *   "success": true,
     *   "message": "Address created successfully",
     *   "data": {
     *     "id": 1,
     *     "title": "Home",
     *     "type": "home",
     *     "is_default": true
     *   }
     * }
     */
    public function store(StoreSenderAddressRequest $request): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $address = $this->addressRepository->create($sender->id, $request->validated());

        return $this->created(new SenderAddressResource($address), 'Address created successfully');
    }

    /**
     * Update Address
     * 
     * Update an existing address.
     * 
     * @urlParam address int required Address ID. Example: 1
     * @bodyParam title string optional Address title. Example: Home
     * @bodyParam type string optional Address type. Example: home
     * @bodyParam is_default boolean optional Set as default address. Example: true
     * @bodyParam full_address string optional Full address. Example: Updated address
     * @bodyParam mobile_number string optional Mobile number. Example: +96170234567
     * @bodyParam country string optional Country. Example: Lebanon
     * @bodyParam city string optional City. Example: Beirut
     * @bodyParam area string optional Area/District. Example: Hamra
     * @bodyParam landmark string optional Landmark. Example: Near AUB Main Gate
     * @bodyParam latitude float optional Latitude. Example: 33.8938
     * @bodyParam longitude float optional Longitude. Example: 35.5018
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Address updated successfully",
     *   "data": {
     *     "id": 1,
     *     "title": "Home",
     *     "type": "home"
     *   }
     * }
     */
    public function update(StoreSenderAddressRequest $request, int $address): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        
        try {
            $addressModel = $this->addressRepository->update($address, $sender->id, $request->validated());
            return $this->success(new SenderAddressResource($addressModel), 'Address updated successfully');
        } catch (\Exception $e) {
            return $this->error('Address not found', 404);
        }
    }

    /**
     * Delete Address
     * 
     * Soft delete an address.
     * 
     * @urlParam address int required Address ID. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Address deleted successfully",
     *   "data": null
     * }
     */
    public function destroy(int $address): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $deleted = $this->addressRepository->delete($address, $sender->id);

        if (!$deleted) {
            return $this->error('Address not found', 404);
        }

        return $this->success(null, 'Address deleted successfully');
    }

    /**
     * Set Address as Default
     * 
     * Set an address as the default address for the sender.
     * 
     * @urlParam id int required Address ID. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Address set as default successfully",
     *   "data": {
     *     "id": 1,
     *     "is_default": true
     *   }
     * }
     */
    public function setDefault(int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        $success = $this->addressRepository->setAsDefault($id, $sender->id);

        if (!$success) {
            return $this->error('Address not found', 404);
        }

        $address = $this->addressRepository->getById($id, $sender->id);
        return $this->success(new SenderAddressResource($address), 'Address set as default successfully');
    }
}

