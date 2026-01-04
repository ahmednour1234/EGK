<?php

namespace App\Repositories\Contracts;

interface RecentUpdatesRepositoryInterface
{
    /**
     * Get recent updates for a traveler.
     */
    public function getTravelerUpdates(int $travelerId, string $type = 'all', int $limit = 20): array;

    /**
     * Get recent updates for a sender.
     */
    public function getSenderUpdates(int $senderId, int $limit = 20): array;
}

