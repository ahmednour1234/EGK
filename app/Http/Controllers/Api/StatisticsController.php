<?php

namespace App\Http\Controllers\Api;

use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @group Statistics
 * 
 * APIs for getting statistics dashboard data
 */
class StatisticsController extends BaseApiController
{
    public function __construct(
        protected StatisticsService $statisticsService
    ) {}

    /**
     * Get Statistics
     * 
     * Get statistics dashboard data. Returns different statistics based on user type.
     * For travelers: total/active tickets, total/delivered packages linked to tickets, assigned drivers count
     * For senders: total/delivered packages
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Statistics retrieved successfully",
     *   "data": {
     *     "tickets": {
     *       "total": 10,
     *       "active": 5
     *     },
     *     "packages": {
     *       "total": 25,
     *       "delivered": 15
     *     },
     *     "drivers": {
     *       "assigned": 3
     *     }
     *   }
     * }
     */
    public function index(): JsonResponse
    {
        $user = Auth::guard('sender')->user();

        $statistics = $this->statisticsService->getStatistics($user->id, $user->type);

        return $this->success($statistics, 'Statistics retrieved successfully');
    }
}
