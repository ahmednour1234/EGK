<?php

namespace App\Repositories\Contracts;

interface StatisticsRepositoryInterface
{
    /**
     * Get statistics for a traveler.
     */
    public function getTravelerStatistics(int $travelerId): array;

    /**
     * Get statistics for a sender.
     */
    public function getSenderStatistics(int $senderId): array;
}

