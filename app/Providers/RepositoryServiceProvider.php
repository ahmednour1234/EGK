<?php

namespace App\Providers;

use App\Repositories\CityRepository;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Repositories\Contracts\FaqRepositoryInterface;
use App\Repositories\Contracts\PackageRepositoryInterface;
use App\Repositories\Contracts\PackageTypeRepositoryInterface;
use App\Repositories\Contracts\PageRepositoryInterface;
use App\Repositories\Contracts\SenderDeviceRepositoryInterface;
use App\Repositories\Contracts\SenderRepositoryInterface;
use App\Repositories\Contracts\SenderVerificationCodeRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\FaqRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PackageTypeRepository;
use App\Repositories\PageRepository;
use App\Repositories\SenderAddressRepository;
use App\Repositories\Contracts\SenderAddressRepositoryInterface;
use App\Repositories\SenderDeviceRepository;
use App\Repositories\SenderRepository;
use App\Repositories\SenderVerificationCodeRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TravelerTicketRepository;
use App\Repositories\Contracts\TravelerTicketRepositoryInterface;
use App\Repositories\TravelerPackageRepository;
use App\Repositories\Contracts\TravelerPackageRepositoryInterface;
use App\Repositories\StatisticsRepository;
use App\Repositories\Contracts\StatisticsRepositoryInterface;
use App\Repositories\RecentUpdatesRepository;
use App\Repositories\Contracts\RecentUpdatesRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(PackageTypeRepositoryInterface::class, PackageTypeRepository::class);
        $this->app->bind(SenderRepositoryInterface::class, SenderRepository::class);
        $this->app->bind(SenderVerificationCodeRepositoryInterface::class, SenderVerificationCodeRepository::class);
        $this->app->bind(SenderDeviceRepositoryInterface::class, SenderDeviceRepository::class);
        $this->app->bind(SenderAddressRepositoryInterface::class, SenderAddressRepository::class);
        $this->app->bind(TravelerTicketRepositoryInterface::class, TravelerTicketRepository::class);
        $this->app->bind(TravelerPackageRepositoryInterface::class, TravelerPackageRepository::class);
        $this->app->bind(StatisticsRepositoryInterface::class, StatisticsRepository::class);
        $this->app->bind(RecentUpdatesRepositoryInterface::class, RecentUpdatesRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

