<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement;

use Illuminate\Support\ServiceProvider;
use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\Contracts\SeoResolverInterface;
use Mahdijd\SeoManagement\Contracts\SeoRouteRepositoryInterface;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\Repositories\SeoMetadataRepository;
use Mahdijd\SeoManagement\Repositories\SeoRouteRepository;
use Mahdijd\SeoManagement\Repositories\SeoSettingsRepository;
use Mahdijd\SeoManagement\Services\SeoResolver;

/**
 * SeoServiceProvider
 *
 * Registers all services, configuration, migrations, views, and commands
 * for the Mahdijd SEO Management package.
 */
class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/seo.php',
            'seo'
        );

        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPublishing();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'seo');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'seo');
    }

    /**
     * Register repository singletons in the service container.
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(
            SeoMetadataRepositoryInterface::class,
            SeoMetadataRepository::class
        );

        $this->app->singleton(
            SeoRouteRepositoryInterface::class,
            SeoRouteRepository::class
        );

        $this->app->singleton(
            SeoSettingsRepositoryInterface::class,
            SeoSettingsRepository::class
        );
    }

    /**
     * Register core services in the service container.
     */
    private function registerServices(): void
    {
        $this->app->singleton(
            SeoResolverInterface::class,
            SeoResolver::class
        );
    }

    /**
     * Register all publishable package assets.
     */
    private function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/seo.php' => config_path('seo.php'),
        ], 'seo-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'seo-migrations');

        // Publish language files
        $this->publishes([
            __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/seo'),
        ], 'seo-lang');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/seo'),
        ], 'seo-views');
    }
}
