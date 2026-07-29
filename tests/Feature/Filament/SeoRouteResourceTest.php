<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource;
use Mahdijd\SeoManagement\Models\SeoRoute;

uses(RefreshDatabase::class);

describe('SeoRouteResource', function (): void {
    it('discovers named GET web routes for select options', function (): void {
        Route::get('/test-route', fn () => 'test')->name('test.route')->middleware('web');

        $options = SeoRouteResource::getAvailableRouteOptions();

        expect($options)->toHaveKey('test.route');
    });

    it('excludes routes that already have a SeoRoute record from available options', function (): void {
        Route::get('/existing-route', fn () => 'existing')->name('existing.route')->middleware('web');

        SeoRoute::create(['route_name' => 'existing.route', 'title' => 'Existing']);

        $options = SeoRouteResource::getAvailableRouteOptions();

        expect($options)->not->toHaveKey('existing.route');
    });
});
