<?php

declare(strict_types=1);

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource\Pages\CreateSeoRoute;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource\Pages\EditSeoRoute;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource\Pages\ListSeoRoutes;
use Mahdijd\SeoManagement\Models\SeoRoute;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $user = new User;
    $user->name = 'Test Admin';
    $user->email = 'admin@example.com';

    $this->actingAs($user);
});

describe('SeoRouteResource', function (): void {
    it('can load the list page', function (): void {
        Route::get('/test-route', fn () => 'test')->name('test.route')->middleware('web');

        $record = SeoRoute::create(['route_name' => 'test.route', 'title' => 'Test Route']);

        Livewire::test(ListSeoRoutes::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$record]);
    });

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

    it('can load the create page', function (): void {
        Route::get('/test-route', fn () => 'test')->name('test.route')->middleware('web');

        Livewire::test(CreateSeoRoute::class)
            ->assertOk();
    });

    it('can create a SeoRoute with SEO metadata', function (): void {
        Route::get('/test-route', fn () => 'test')->name('test.route')->middleware('web');

        Livewire::test(CreateSeoRoute::class)
            ->fillForm([
                'route_name' => 'test.route',
                'title' => 'Test Route Title',
                'description' => 'Test route description.',
                'robots' => 'index,follow',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('seo_routes', [
            'route_name' => 'test.route',
            'title' => 'Test Route Title',
            'description' => 'Test route description.',
            'robots' => 'index,follow',
        ]);
    });

    it('rejects a duplicate route_name on create', function (): void {
        Route::get('/existing-route', fn () => 'existing')->name('existing.route')->middleware('web');

        SeoRoute::create(['route_name' => 'existing.route', 'title' => 'Existing']);

        Livewire::test(CreateSeoRoute::class)
            ->fillForm([
                'route_name' => 'existing.route',
                'title' => 'Duplicate',
            ])
            ->call('create')
            ->assertHasFormErrors(['route_name'])
            ->assertNotNotified();
    });

    it('can load the edit page with existing SEO data', function (): void {
        Route::get('/existing-route', fn () => 'existing')->name('existing.route')->middleware('web');

        $record = SeoRoute::create([
            'route_name' => 'existing.route',
            'title' => 'Existing Title',
        ]);

        Livewire::test(EditSeoRoute::class, ['record' => $record->getRouteKey()])
            ->assertOk()
            ->assertFormSet([
                'route_name' => 'existing.route',
                'title' => 'Existing Title',
            ]);
    });

    it('does not allow editing route_name after creation', function (): void {
        Route::get('/existing-route', fn () => 'existing')->name('existing.route')->middleware('web');

        $record = SeoRoute::create(['route_name' => 'existing.route', 'title' => 'Existing']);

        Livewire::test(EditSeoRoute::class, ['record' => $record->getRouteKey()])
            ->assertFormFieldIsDisabled('route_name');
    });

    it('can update SEO metadata via the edit page', function (): void {
        Route::get('/existing-route', fn () => 'existing')->name('existing.route')->middleware('web');

        $record = SeoRoute::create(['route_name' => 'existing.route', 'title' => 'Old Title']);

        Livewire::test(EditSeoRoute::class, ['record' => $record->getRouteKey()])
            ->fillForm([
                'title' => 'Updated Title',
                'description' => 'Updated description.',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('seo_routes', [
            'id' => $record->id,
            'title' => 'Updated Title',
            'description' => 'Updated description.',
        ]);
    });
});
