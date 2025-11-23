<?php

namespace App\Providers;

use App\Services\Sms\AtipaySms;
use App\Services\Sms\SmsInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->app->bind(SmsInterface::class, AtipaySms::class);
        Paginator::useBootstrapFour();
    }
}
