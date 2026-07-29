<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Mahdijd\SeoManagement\Filament\Pages\SeoSettingsPage;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource;

/**
 * SeoPlugin
 *
 * Filament panel plugin for registering SEO Management resources and settings page
 * with a single call in the host application's PanelProvider:
 *
 * ```php
 * $panel->plugins([
 *     SeoPlugin::make(),
 * ]);
 * ```
 */
class SeoPlugin implements Plugin
{
    /**
     * Get the unique plugin identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return 'seo-management';
    }

    /**
     * Create a new instance of the plugin.
     *
     * @return static
     */
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Register the plugin resources and pages with the Filament panel.
     *
     * @param  Panel  $panel
     * @return void
     */
    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                SeoRouteResource::class,
            ])
            ->pages([
                SeoSettingsPage::class,
            ]);
    }

    /**
     * Boot the plugin.
     *
     * @param  Panel  $panel
     * @return void
     */
    public function boot(Panel $panel): void
    {
    }
}
