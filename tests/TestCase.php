<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Mahdijd\SeoManagement\SeoServiceProvider;
use Mahdijd\SeoManagement\Tests\Support\Filament\AdminPanelProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Base TestCase for the SEO Management package.
 *
 * Provides a minimal Laravel application environment backed by an
 * in-memory SQLite database. All package tests should extend this class.
 */
abstract class TestCase extends OrchestraTestCase
{
    /**
     * Register the package service providers.
     *
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            SeoServiceProvider::class,
            AdminPanelProvider::class,
        ];
    }

    public function ignorePackageDiscoveriesFrom(): array
    {
        return [];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        // Use in-memory SQLite for all tests
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
    }

    /**
     * Define database migrations for test fixtures.
     */
    protected function defineDatabaseMigrations(): void
    {
        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->timestamps();
        });
    }
}
