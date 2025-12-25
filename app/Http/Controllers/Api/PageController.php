<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageResource;
use App\Repositories\Contracts\PageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Pages
 * 
 * APIs for retrieving static pages
 */
class PageController extends BaseApiController
{
    public function __construct(
        protected PageRepositoryInterface $pageRepository
    ) {}

    /**
     * Get All Pages
     * 
     * Get a list of all pages. Can be filtered by slug(s) and searched.
     * 
     * @queryParam slug string Filter by page slug. Example: about-us
     * @queryParam slugs string Comma-separated list of slugs to filter. Example: about-us,contact-us,privacy-policy
     * @queryParam active boolean Filter by active status (default: true). Example: true
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * @queryParam search string Search in title or content. Example: company
     * @queryParam order_by string Order by field (default: id). Example: id
     * @queryParam order_direction string Order direction (asc, desc). Example: asc
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Pages retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "slug": "about-us",
     *       "title": "About Us",
     *       "content": "Welcome to our company...",
     *       "meta_title": "About Us - Company Name",
     *       "meta_description": "Learn more about our company",
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
            'slug' => $request->input('slug'),
            'slugs' => $request->input('slugs') ? explode(',', $request->input('slugs')) : null,
            'active' => $request->input('active', true),
            'search' => $request->input('search'),
            'order_by' => $request->input('order_by', 'id'),
            'order_direction' => $request->input('order_direction', 'asc'),
        ];

        $pages = $this->pageRepository->getAll(array_filter($filters, fn($value) => $value !== null));

        return $this->success(PageResource::collection($pages), 'Pages retrieved successfully');
    }

    /**
     * Get Page by Slug
     * 
     * Get a single page by its slug.
     * 
     * @urlParam slug string required Page slug. Example: about-us
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Page retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "slug": "about-us",
     *     "title": "About Us",
     *     "content": "Welcome to our company...",
     *     "meta_title": "About Us - Company Name",
     *     "meta_description": "Learn more about our company",
     *     "is_active": true
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Page not found",
     *   "data": null
     * }
     */
    public function show(string $slug, Request $request): JsonResponse
    {
        $locale = $request->input('locale', app()->getLocale());

        // Set locale for the request
        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        }

        $page = $this->pageRepository->getBySlug($slug);

        if (!$page) {
            return $this->error('Page not found', 404);
        }

        return $this->success(new PageResource($page), 'Page retrieved successfully');
    }
}

