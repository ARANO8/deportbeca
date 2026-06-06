<?php

namespace App\Providers;

use App\Interfaces\FixtureGenerationServiceInterface;
use App\Services\FixtureGenerationService;
use Illuminate\Support\ServiceProvider;

class FixtureGenerationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FixtureGenerationServiceInterface::class, FixtureGenerationService::class);
    }

    public function boot(): void
    {
        //
    }
}
