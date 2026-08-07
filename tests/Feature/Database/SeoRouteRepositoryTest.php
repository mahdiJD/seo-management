<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoRouteRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Repositories\SeoRouteRepository;

uses(RefreshDatabase::class);

describe('SeoRouteRepository', function (): void {
    beforeEach(function (): void {
        $this->repository = new SeoRouteRepository;
    });

    it('implements SeoRouteRepositoryInterface', function (): void {
        expect($this->repository)->toBeInstanceOf(SeoRouteRepositoryInterface::class);
    });

    it('returns null when no route record exists for a route name', function (): void {
        $result = $this->repository->findByRouteName('non-existent');

        expect($result)->toBeNull();
    });

    it('finds existing route record by route_name', function (): void {
        SeoRoute::create([
            'route_name' => 'home',
            'title' => 'Home Page Title',
        ]);

        $result = $this->repository->findByRouteName('home');

        expect($result)->not->toBeNull()
            ->and($result)->toBeInstanceOf(SeoRoute::class)
            ->and($result->title)->toBe('Home Page Title');
    });

    it('creates a new route record when saving for an unrecorded route name', function (): void {
        $saved = $this->repository->save('about', [
            'title' => 'About Us',
            'description' => 'About Us Description',
        ]);

        expect($saved)->toBeInstanceOf(SeoRoute::class)
            ->and($saved->route_name)->toBe('about')
            ->and($saved->title)->toBe('About Us');

        expect(SeoRoute::count())->toBe(1);
    });

    it('updates existing route record when saving for a recorded route name', function (): void {
        $this->repository->save('contact', ['title' => 'Contact Us']);

        $updated = $this->repository->save('contact', ['title' => 'Contact Support']);

        expect($updated->title)->toBe('Contact Support')
            ->and(SeoRoute::count())->toBe(1);
    });

    it('deletes an existing route record', function (): void {
        $this->repository->save('pricing', ['title' => 'Pricing']);

        $deleted = $this->repository->delete('pricing');

        expect($deleted)->toBeTrue()
            ->and(SeoRoute::count())->toBe(0);
    });

    it('returns false when deleting a route record that does not exist', function (): void {
        $deleted = $this->repository->delete('non-existent');

        expect($deleted)->toBeFalse();
    });

    it('enforces unique constraint on route_name', function (): void {
        $this->repository->save('unique-route', ['title' => 'First']);

        expect(fn () => SeoRoute::create([
            'route_name' => 'unique-route',
            'title' => 'Duplicate',
        ]))->toThrow(QueryException::class);
    });
});
