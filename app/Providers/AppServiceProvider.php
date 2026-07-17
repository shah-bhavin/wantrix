<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Vendor;
use App\Observers\CampaignObserver;
use App\Observers\VendorObserver;
use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Vendor::observe(VendorObserver::class);
        Campaign::observe(CampaignObserver::class);
        View::composer(
            [
                'components.vendor.sidebar',
                'components.vendor.navbar',
                'layouts.vendor',
            ],
            NavigationComposer::class
        );
    }
}
