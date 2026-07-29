<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mahdijd\SeoManagement\SeoServiceProvider;
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
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            SeoServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function defineEnvironment($app): void
    {
        // Use in-memory SQLite for all tests
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
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
