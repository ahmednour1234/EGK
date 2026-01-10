<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\PackageTypeController;
use App\Http\Controllers\Api\SenderAuthController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\RecentUpdatesController;
use App\Http\Controllers\Api\TravelerPackageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Settings routes
Route::get('settings', [SettingController::class, 'index']);
Route::get('settings/{key}', [SettingController::class, 'show']);

// FAQ routes
Route::get('faqs', [FaqController::class, 'index']);
Route::get('faqs/{id}', [FaqController::class, 'show']);

// Page routes
Route::get('pages', [PageController::class, 'index']);
Route::get('pages/slug/{slug}', [PageController::class, 'show']);

// City routes
Route::get('cities', [CityController::class, 'index']);
Route::get('cities/{id}', [CityController::class, 'show']);

// Country routes
Route::get('countries', [CountryController::class, 'index']);
Route::get('countries/{country}', [CountryController::class, 'show']);

// Package Type routes
Route::get('package-types', [PackageTypeController::class, 'index']);
Route::get('package-types/{id}', [PackageTypeController::class, 'show']);
Route::get('package-types/slug/{slug}', [PackageTypeController::class, 'showBySlug']);

// Sender Authentication routes (public)
Route::prefix('sender')->group(function () {
    Route::post('register', [SenderAuthController::class, 'register']);
    Route::post('verify-code', [SenderAuthController::class, 'verifyCode']);
    Route::post('login', [SenderAuthController::class, 'login']);
    Route::post('forget-password', [SenderAuthController::class, 'forgetPassword']);
    Route::post('reset-password', [SenderAuthController::class, 'resetPassword']);
});

// Sender Authentication routes (protected)
Route::prefix('sender')->middleware('auth:sender')->group(function () {
    Route::get('me', [SenderAuthController::class, 'me']);
    Route::put('update', [SenderAuthController::class, 'update']);
    Route::post('upload-avatar', [SenderAuthController::class, 'uploadAvatar']);
    Route::post('switch-type', [SenderAuthController::class, 'switchType']);
    Route::post('logout', [SenderAuthController::class, 'logout']);
    Route::post('refresh', [SenderAuthController::class, 'refresh']);
    
    // Sender Addresses routes
    Route::apiResource('addresses', \App\Http\Controllers\Api\SenderAddressController::class);
    Route::post('addresses/{id}/set-default', [\App\Http\Controllers\Api\SenderAddressController::class, 'setDefault']);
    
    // Sender Packages routes
    Route::apiResource('packages', \App\Http\Controllers\Api\PackageController::class);
    Route::post('packages/{id}/cancel', [\App\Http\Controllers\Api\PackageController::class, 'cancel']);
    Route::get('packages/active', [\App\Http\Controllers\Api\PackageController::class, 'activePackage']);
    Route::get('packages/last', [\App\Http\Controllers\Api\PackageController::class, 'lastPackage']);
    
    // Statistics route (accessible to both travelers and senders)
    Route::get('statistics', [StatisticsController::class, 'index']);
    
    // Recent Updates route (accessible to both travelers and senders)
    Route::get('recent-updates', [RecentUpdatesController::class, 'index']);
    
    // Traveler Tickets routes (only for travelers)
    Route::apiResource('tickets', \App\Http\Controllers\Api\TravelerTicketController::class);
    Route::get('traveler/active-trips', [\App\Http\Controllers\Api\TravelerTicketController::class, 'activeTrips']);
    
    // Traveler Package routes (only for travelers)
    Route::prefix('traveler')->group(function () {
        Route::get('packages-with-me', [TravelerPackageController::class, 'packagesWithMe']);
        Route::get('active-packages-now', [TravelerPackageController::class, 'activePackagesNow']);
    });
});


