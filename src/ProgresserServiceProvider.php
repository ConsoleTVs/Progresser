<?php

declare(strict_types=1);

namespace ConsoleTVs\Progresser;

use Illuminate\Support\ServiceProvider;

class ProgresserServiceProvider extends ServiceProvider
{
    /**
     * Register any progresser services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/progresser.php',
            'progresser'
        );
    }

    /**
     * Boot the progresser services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/progresser.php' => config_path('progresser.php'),
        ]);
    }
}
