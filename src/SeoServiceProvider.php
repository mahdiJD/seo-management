<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Mahdijd\SeoManagement\Console\Commands\ClearSeoCache;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\Contracts\SeoRendererInterface;
use Mahdijd\SeoManagement\Contracts\SeoResolverInterface;
use Mahdijd\SeoManagement\Contracts\SeoRouteRepositoryInterface;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Models\SeoSettings;
use Mahdijd\SeoManagement\Observers\SeoMetadataObserver;
use Mahdijd\SeoManagement\Observers\SeoRouteObserver;
use Mahdijd\SeoManagement\Observers\SeoSettingsObserver;
use Mahdijd\SeoManagement\Repositories\SeoMetadataRepository;
use Mahdijd\SeoManagement\Repositories\SeoRouteRepository;
use Mahdijd\SeoManagement\Repositories\SeoSettingsRepository;
use Mahdijd\SeoManagement\Services\SeoCacheManager;
use Mahdijd\SeoManagement\Services\SeoManager;
use Mahdijd\SeoManagement\Services\SeoRenderer;
use Mahdijd\SeoManagement\Services\SeoResolver;
use Mahdijd\SeoManagement\View\Components\TagsComponent;

/**
 * SeoServiceProvider
 *
 * Registers all services, configuration, migrations, views, commands, and components
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
        $this->registerObservers();
        $this->registerCommands();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'seo');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'seo');

        Blade::component('seo::tags', TagsComponent::class);
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

        $this->app->singleton(
            SeoRendererInterface::class,
            SeoRenderer::class
        );

        $this->app->singleton(
            SeoCacheManagerInterface::class,
            SeoCacheManager::class
        );

        $this->app->singleton(
            SeoManager::class,
            fn ($app) => new SeoManager(
                $app->make(SeoResolverInterface::class),
                $app->make(SeoRendererInterface::class),
                $app->make(SeoCacheManagerInterface::class)
            )
        );

        $this->app->alias(SeoManager::class, 'seo');
    }

    /**
     * Register model observers for automatic cache invalidation.
     */
    private function registerObservers(): void
    {
        SeoMetadata::observe(SeoMetadataObserver::class);
        SeoRoute::observe(SeoRouteObserver::class);
        SeoSettings::observe(SeoSettingsObserver::class);
    }

    /**
     * Register package console commands.
     */
    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearSeoCache::class,
            ]);
        }
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
