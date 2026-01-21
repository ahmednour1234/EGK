<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreTravelerTicketRequest;
use App\Http\Resources\TravelerTicketResource;
use App\Repositories\Contracts\TravelerTicketRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Traveler Tickets
 *
 * APIs for managing traveler tickets (only accessible to travelers)
 */
class TravelerTicketController extends BaseApiController
{
    public function __construct(
        protected TravelerTicketRepositoryInterface $ticketRepository
    ) {}

    /**
     * Get All Tickets
     *
     * Get a list of all tickets for the authenticated traveler with advanced filtering.
     * Only travelers (type='traveler') can access this endpoint.
     *
     * @queryParam status string Filter by status (draft, active, matched, completed, cancelled). Example: active
     * @queryParam statuses array Filter by multiple statuses. Example: ["draft","active"]
     * @queryParam trip_type string Filter by trip type (one-way, round-trip). Example: one-way
     * @queryParam transport_type string Filter by transport type. Example: Car
     *
     * @queryParam from_country_id int Filter by from country id. Example: 1
     * @queryParam to_country_id int Filter by to country id. Example: 2
     *
     * @queryParam from_city string Filter by from city. Example: Beirut
     * @queryParam to_city string Filter by to city. Example: Tripoli
     * @queryParam departure_date_from date Filter tickets with departure date from. Example: 2025-11-01
     * @queryParam departure_date_to date Filter tickets with departure date to. Example: 2025-11-30
     * @queryParam search string Search in cities, transport type, or notes. Example: Beirut
     * @queryParam page int Page number for pagination. Example: 1
     * @queryParam per_page int Items per page (default: 15, max: 100). Example: 15
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Tickets retrieved successfully",
     *   "data": [...],
     *   "meta": {...}
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $traveler = Auth::guard('sender')->user();

        if ($traveler->type !== 'traveler') {
            return $this->error('Only travelers can access tickets', 403);
        }

        $filters = [
            'status' => $request->input('status'),
            'statuses' => $request->input('statuses'),
            'trip_type' => $request->input('trip_type'),
            'transport_type' => $request->input('transport_type'),

            'from_country_id' => $request->input('from_country_id'),
            'to_country_id' => $request->input('to_country_id'),

            'from_city' => $request->input('from_city'),
            'to_city' => $request->input('to_city'),
            'departure_date_from' => $request->input('departure_date_from'),
            'departure_date_to' => $request->input('departure_date_to'),
            'search' => $request->input('search'),
        ];

        // Handle statuses as array if provided as comma-separated string
        if (isset($filters['statuses']) && is_string($filters['statuses'])) {
            $filters['statuses'] = array_values(array_filter(array_map('trim', explode(',', $filters['statuses']))));
        }

        // normalize country ids if string
        foreach (['from_country_id', 'to_country_id'] as $key) {
            if (isset($filters[$key]) && $filters[$key] !== null && $filters[$key] !== '') {
                $filters[$key] = (int) $filters[$key];
            }
        }

        $perPage = min((int) $request->input('per_page', 15), 100);

        $tickets = $this->ticketRepository->getAll(
            $traveler->id,
            array_filter($filters, fn ($value) => $value !== null && $value !== ''),
            $perPage
        );

        return $this->paginated(TravelerTicketResource::collection($tickets), 'Tickets retrieved successfully');
    }

    /**
     * Get Active Trips
     *
     * Get all active tickets (status='active') for the authenticated traveler with package counts.
     * Only travelers (type='traveler') can access this endpoint.
     *
     * @queryParam trip_type string Filter by trip type (one-way, round-trip). Example: one-way
     * @queryParam transport_type string Filter by transport type. Example: Car
     *
     * @queryParam from_country_id int Filter by from country id. Example: 1
     * @queryParam to_country_id int Filter by to country id. Example: 2
     *
     * @queryParam from_city string Filter by from city. Example: Beirut
     * @queryParam to_city string Filter by to city. Example: Tripoli
     * @queryParam departure_date_from date Filter tickets with departure date from. Example: 2025-11-01
     * @queryParam departure_date_to date Filter tickets with departure date to. Example: 2025-11-30
     * @queryParam search string Search in cities, transport type, or notes. Example: Beirut
     * @queryParam page int Page number for pagination. Example: 1
     * @queryParam per_page int Items per page (default: 15, max: 100). Example: 15
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Active trips retrieved successfully",
     *   "data": [...],
     *   "meta": {...}
     * }
     */

public function activeTrips(Request $request): JsonResponse
{
    $traveler = Auth::guard('sender')->user();

    if (!$traveler || $traveler->type !== 'traveler') {
        return $this->error('Only travelers can access active trips', 403);
    }

    $filters = [
        'status' => 'active',
        'trip_type' => $request->input('trip_type'),
        'transport_type' => $request->input('transport_type'),
        'from_country_id' => $request->input('from_country_id'),
        'to_country_id' => $request->input('to_country_id'),
        'from_city' => $request->input('from_city'),
        'to_city' => $request->input('to_city'),
        'departure_date_from' => $request->input('departure_date_from'),
        'departure_date_to' => $request->input('departure_date_to'),
        'search' => $request->input('search'),
    ];

    foreach (['from_country_id', 'to_country_id'] as $key) {
        if (isset($filters[$key]) && $filters[$key] !== null && $filters[$key] !== '') {
            $filters[$key] = (int) $filters[$key];
        }
    }

    $filters = array_filter($filters, fn ($v) => $v !== null && $v !== '');

    $perPage = (int) $request->input('per_page', 15);
    $perPage = $perPage > 0 ? min($perPage, 100) : 15;

    $tickets = $this->ticketRepository->getAll(
        travelerId: (int) $traveler->id,
        filters: $filters,
        perPage: $perPage,
        withTrashed: false,
        withCounts: ['packages'],
    );

    return $this->paginated(
        TravelerTicketResource::collection($tickets),
        'Active trips retrieved successfully'
    );
}
    /**
     * Create Ticket
     *
     * Create a new travel ticket. Only travelers (type='traveler') can create tickets.
     *
     * @bodyParam from_country_id int required From country id. Example: 1
     * @bodyParam to_country_id int required To country id. Example: 2
     *
     * @bodyParam from_city string required From city. Example: Beirut
     * @bodyParam to_city string required To city. Example: Tripoli
     * @bodyParam full_address string required Full address. Example: Main Street, Building 5
     * @bodyParam landmark string optional Landmark. Example: Near AUB Main Gate
     * @bodyParam latitude number optional Latitude coordinate. Example: 33.893791
     * @bodyParam longitude number optional Longitude coordinate. Example: 35.472163
     * @bodyParam trip_type string required Trip type (one-way or round-trip). Example: one-way
     * @bodyParam departure_date date required Departure date. Example: 2025-11-26
     * @bodyParam departure_time time required Departure time. Example: 11:33
     * @bodyParam arrival_date date optional Arrival date. Example: 2025-11-26
     * @bodyParam arrival_time time optional Arrival time. Example: 14:00
     * @bodyParam return_date date optional Return date (required for round-trip). Example: 2025-11-27
     * @bodyParam return_time time optional Return time (required for round-trip). Example: 14:00
     * @bodyParam transport_type string required Transport type. Example: Car
     * @bodyParam total_weight_limit number optional Total weight limit in kg. Example: 10
     * @bodyParam max_package_count int optional Maximum package count. Example: 5
     * @bodyParam acceptable_package_types array optional Array of package type IDs. Example: [1, 2, 3]
     * @bodyParam preferred_pickup_area string optional Preferred pickup area. Example: City Center
     * @bodyParam preferred_delivery_area string optional Preferred delivery area. Example: Downtown
     * @bodyParam notes_for_senders string optional Notes for senders. Example: No liquids please
     * @bodyParam allow_urgent_packages boolean optional Allow urgent packages. Example: false
     * @bodyParam accept_only_verified_senders boolean optional Accept only verified senders. Example: true
     * @bodyParam status string optional Status (draft or active). Default: draft. Example: active
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Ticket created successfully",
     *   "data": {...}
     * }
     */
    public function store(StoreTravelerTicketRequest $request): JsonResponse
    {
        $traveler = Auth::guard('sender')->user();

        if ($traveler->type !== 'traveler') {
            return $this->error('Only travelers can create tickets', 403);
        }

        $data = $request->validated();

        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        $ticket = $this->ticketRepository->create($traveler->id, $data);

        return $this->created(TravelerTicketResource::make($ticket), 'Ticket created successfully');
    }

    /**
     * Get Single Ticket
     *
     * Get a single ticket by ID with full details.
     *
     * @urlParam id int required Ticket ID. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Ticket retrieved successfully",
     *   "data": {...}
     * }
     */
    public function show(string $id): JsonResponse
    {
        $traveler = Auth::guard('sender')->user();

        if ($traveler->type !== 'traveler') {
            return $this->error('Only travelers can access tickets', 403);
        }

        $ticket = $this->ticketRepository->getById($traveler->id, (int) $id);

        if (!$ticket) {
            return $this->error('Ticket not found', 404);
        }

        return $this->success(TravelerTicketResource::make($ticket), 'Ticket retrieved successfully');
    }

    /**
     * Update Ticket
     *
     * Update an existing ticket. Only draft or active tickets can be updated.
     *
     * @urlParam id int required Ticket ID. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Ticket updated successfully",
     *   "data": {...}
     * }
     */
    public function update(StoreTravelerTicketRequest $request, string $id): JsonResponse
    {
        $traveler = Auth::guard('sender')->user();

        if ($traveler->type !== 'traveler') {
            return $this->error('Only travelers can update tickets', 403);
        }

        $ticket = $this->ticketRepository->getById($traveler->id, (int) $id);

        if (!$ticket) {
            return $this->error('Ticket not found', 404);
        }

        if (!in_array($ticket->status, ['draft', 'active'], true)) {
            return $this->error('Ticket can only be updated if status is draft or active', 422);
        }

        $data = $request->validated();

        // Don't allow status change to matched, completed, or cancelled through update
        if (isset($data['status']) && in_array($data['status'], ['matched', 'completed', 'cancelled'], true)) {
            unset($data['status']);
        }

        $ticket = $this->ticketRepository->update($traveler->id, (int) $id, $data);

        return $this->success(TravelerTicketResource::make($ticket), 'Ticket updated successfully');
    }

    /**
     * Delete Ticket
     *
     * Soft delete a ticket.
     *
     * @urlParam id int required Ticket ID. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Ticket deleted successfully",
     *   "data": null
     * }
     */
    public function destroy(string $id): JsonResponse
    {
        $traveler = Auth::guard('sender')->user();

        if ($traveler->type !== 'traveler') {
            return $this->error('Only travelers can delete tickets', 403);
        }

        $deleted = $this->ticketRepository->delete($traveler->id, (int) $id);

        if (!$deleted) {
            return $this->error('Ticket not found', 404);
        }

        return $this->success(null, 'Ticket deleted successfully');
    }
}
