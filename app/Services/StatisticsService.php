<?php

namespace App\Services;

use App\Repositories\Contracts\StatisticsRepositoryInterface;

class StatisticsService
{
    public function __construct(
        protected StatisticsRepositoryInterface $statisticsRepository
    ) {}

    /**
     * Get statistics for a user based on their type.
     */
    public function getStatistics(int $userId, string $userType): array
    {
        if ($userType === 'traveler') {
            return $this->statisticsRepository->getTravelerStatistics($userId);
        }

        return $this->statisticsRepository->getSenderStatistics($userId);
    }
}

