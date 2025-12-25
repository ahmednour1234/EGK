<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FaqResource;
use App\Repositories\Contracts\FaqRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group FAQs
 * 
 * APIs for retrieving frequently asked questions
 */
class FaqController extends BaseApiController
{
    public function __construct(
        protected FaqRepositoryInterface $faqRepository
    ) {}

    /**
     * Get All FAQs
     * 
     * Get a list of all FAQs ordered by their display order.
     * 
     * @queryParam active boolean Filter by active status (default: true). Example: true
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * @queryParam order_by string Order by field (default: order). Example: order
     * @queryParam order_direction string Order direction (asc, desc). Example: asc
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "FAQs retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "question": "What is your return policy?",
     *       "answer": "We offer a 30-day return policy...",
     *       "order": 1,
     *       "is_active": true
     *     }
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $locale = $request->input('locale', app()->getLocale());

        // Set locale for the request
        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        }

        $filters = [
            'active' => $request->input('active', true),
            'order_by' => $request->input('order_by', 'order'),
            'order_direction' => $request->input('order_direction', 'asc'),
        ];

        $faqs = $this->faqRepository->getAll($filters);

        return $this->success(FaqResource::collection($faqs), 'FAQs retrieved successfully');
    }

    /**
     * Get Single FAQ
     * 
     * Get a single FAQ by ID.
     * 
     * @urlParam id int required FAQ ID. Example: 1
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "FAQ retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "question": "What is your return policy?",
     *     "answer": "We offer a 30-day return policy...",
     *     "order": 1,
     *     "is_active": true
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "FAQ not found",
     *   "data": null
     * }
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $locale = $request->input('locale', app()->getLocale());

        // Set locale for the request
        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        }

        $faq = $this->faqRepository->getById($id);

        if (!$faq) {
            return $this->error('FAQ not found', 404);
        }

        return $this->success(new FaqResource($faq), 'FAQ retrieved successfully');
    }
}

