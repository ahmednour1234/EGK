<?php

namespace App\Repositories;

use App\Models\TravelerTicket;
use App\Repositories\Contracts\TravelerTicketRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TravelerTicketRepository implements TravelerTicketRepositoryInterface
{

public function getAll(
    int $travelerId,
    array $filters = [],
    int $perPage = 15,
    bool $withTrashed = false,
    array $withCounts = []
): LengthAwarePaginator {
    $query = TravelerTicket::query()->where(function ($q) use ($travelerId) {
        $q->where('traveler_id', $travelerId)
          ->orWhere('sender_id', $travelerId);
    });

    if ($withTrashed) {
        $query->withTrashed();
    }

    if (!empty($withCounts)) {
        $query->withCount($withCounts);
    }

    if (isset($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    if (isset($filters['statuses']) && is_array($filters['statuses']) && !empty($filters['statuses'])) {
        $query->whereIn('status', $filters['statuses']);
    }

    if (isset($filters['trip_type'])) {
        $query->where('trip_type', $filters['trip_type']);
    }

    if (isset($filters['transport_type'])) {
        $query->where('transport_type', $filters['transport_type']);
    }

    if (isset($filters['from_country_id'])) {
        $query->where('from_country_id', (int) $filters['from_country_id']);
    }

    if (isset($filters['to_country_id'])) {
        $query->where('to_country_id', (int) $filters['to_country_id']);
    }

    if (isset($filters['from_city'])) {
        $query->where('from_city', 'like', '%' . $filters['from_city'] . '%');
    }

    if (isset($filters['to_city'])) {
        $query->where('to_city', 'like', '%' . $filters['to_city'] . '%');
    }

    if (isset($filters['departure_date_from'])) {
        $query->whereDate('departure_date', '>=', $filters['departure_date_from']);
    }

    if (isset($filters['departure_date_to'])) {
        $query->whereDate('departure_date', '<=', $filters['departure_date_to']);
    }

    if (isset($filters['search']) && $filters['search'] !== '') {
        $search = $filters['search'];
        $query->where(function (Builder $q) use ($search) {
            $q->where('from_city', 'like', '%' . $search . '%')
              ->orWhere('to_city', 'like', '%' . $search . '%')
              ->orWhere('transport_type', 'like', '%' . $search . '%')
              ->orWhere('notes_for_senders', 'like', '%' . $search . '%');
        });
    }

    return $query->latest()->paginate($perPage);
}
    public function getById(int $travelerId, int $id, bool $withTrashed = false): ?TravelerTicket
    {
        $query = TravelerTicket::where(function ($q) use ($travelerId) {
            $q->where('traveler_id', $travelerId)
              ->orWhere('sender_id', $travelerId);
        })->where('id', $id);

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->first();
    }

    public function create(int $travelerId, array $data): TravelerTicket
    {
        return DB::transaction(function () use ($travelerId, $data) {
            $data['traveler_id'] = $travelerId;

            // Ensure status is set
            if (!isset($data['status'])) {
                $data['status'] = 'draft';
            }

            return TravelerTicket::create($data);
        });
    }

    public function update(int $travelerId, int $id, array $data): ?TravelerTicket
    {
        $ticket = $this->getById($travelerId, $id);

        if (!$ticket) {
            return null;
        }

        if ($ticket->traveler_id !== $travelerId && $ticket->sender_id !== $travelerId) {
            return null;
        }

        return DB::transaction(function () use ($ticket, $data) {
            $ticket->update($data);
            return $ticket->fresh();
        });
    }

    public function delete(int $travelerId, int $id): bool
    {
        $ticket = $this->getById($travelerId, $id);

        if (!$ticket) {
            return false;
        }

        if ($ticket->traveler_id !== $travelerId && $ticket->sender_id !== $travelerId) {
            return false;
        }

        return $ticket->delete(); // Soft delete
    }

    public function restore(int $travelerId, int $id): bool
    {
        $ticket = TravelerTicket::onlyTrashed()
            ->where(function ($q) use ($travelerId) {
                $q->where('traveler_id', $travelerId)
                  ->orWhere('sender_id', $travelerId);
            })
            ->where('id', $id)
            ->first();

        if (!$ticket) {
            return false;
        }

        return $ticket->restore();
    }

    public function forceDelete(int $travelerId, int $id): bool
    {
        $ticket = TravelerTicket::onlyTrashed()
            ->where(function ($q) use ($travelerId) {
                $q->where('traveler_id', $travelerId)
                  ->orWhere('sender_id', $travelerId);
            })
            ->where('id', $id)
            ->first();

        if (!$ticket) {
            return false;
        }

        return $ticket->forceDelete();
    }
}

