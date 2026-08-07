<?php

declare(strict_types=1);

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Filament\Pages\SeoSettingsPage;
use Mahdijd\SeoManagement\Models\SeoSettings;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $user = new User;
    $user->name = 'Test Admin';
    $user->email = 'admin@example.com';

    $this->actingAs($user);
});

describe('SeoSettingsPage', function (): void {
    it('loads the settings page on fresh install', function (): void {
        expect(SeoSettings::count())->toBe(0);

        Livewire::test(SeoSettingsPage::class)
            ->assertOk();
    });

    it('auto-creates settings record on mount via firstOrCreate', function (): void {
        expect(SeoSettings::count())->toBe(0);

        Livewire::test(SeoSettingsPage::class);

        expect(SeoSettings::count())->toBe(1);
    });

    it('loads existing settings data into form fields on mount', function (): void {
        $settings = SeoSettings::create([
            'site_name' => 'My Site',
            'default_title' => 'Default Title',
            'default_description' => 'Site description.',
            'default_robots' => 'index,follow',
        ]);

        Livewire::test(SeoSettingsPage::class)
            ->assertFormSet([
                'site_name' => 'My Site',
                'default_title' => 'Default Title',
                'default_description' => 'Site description.',
                'default_robots' => 'index,follow',
            ]);
    });

    it('can update global seo settings and persists to database', function (): void {
        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'site_name' => 'New Site Name',
                'default_title' => 'New Default Title',
                'default_description' => 'New default description.',
                'default_robots' => 'noindex,follow',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('seo_settings', [
            'site_name' => 'New Site Name',
            'default_title' => 'New Default Title',
            'default_description' => 'New default description.',
            'default_robots' => 'noindex,follow',
        ]);
    });

    it('updates existing settings without creating duplicate records', function (): void {
        SeoSettings::create([
            'site_name' => 'Original Site',
            'default_title' => 'Original Title',
        ]);

        expect(SeoSettings::count())->toBe(1);

        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'site_name' => 'Updated Site',
                'default_title' => 'Updated Title',
            ])
            ->call('save');

        expect(SeoSettings::count())->toBe(1);

        $this->assertDatabaseHas('seo_settings', [
            'site_name' => 'Updated Site',
            'default_title' => 'Updated Title',
        ]);
    });

    it('shows success notification after saving settings', function (): void {
        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'site_name' => 'Updated Site',
            ])
            ->call('save')
            ->assertNotified();
    });

    it('flushes all seo cache when settings are saved via observer', function (): void {
        $cacheManager = Mockery::mock(SeoCacheManagerInterface::class);
        $cacheManager->shouldReceive('flush')->times(2);

        $this->app->instance(SeoCacheManagerInterface::class, $cacheManager);

        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'site_name' => 'Cache Flushing Site',
            ])
            ->call('save');

        Mockery::close();
    });

    it('supports saving open graph default settings', function (): void {
        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'default_og_type' => 'website',
                'default_og_site_name' => 'OG Site Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('seo_settings', [
            'default_og_type' => 'website',
            'default_og_site_name' => 'OG Site Name',
        ]);
    });

    it('supports saving twitter card default settings', function (): void {
        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'default_twitter_card' => 'summary_large_image',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('seo_settings', [
            'default_twitter_card' => 'summary_large_image',
        ]);
    });

    it('supports saving structured data json-ld default settings', function (): void {
        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'default_json_ld' => '{"@context":"https://schema.org","@type":"WebSite","name":"Test"}',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $settings = SeoSettings::first();
        expect($settings->default_json_ld)->toBeString();

        $decoded = json_decode($settings->default_json_ld, true);
        expect($decoded)->toBe([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Test',
        ]);
    });

    it('supports setting default robots directive', function (): void {
        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'default_robots' => 'noindex,nofollow',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('seo_settings', [
            'default_robots' => 'noindex,nofollow',
        ]);
    });

    it('supports setting default canonical url', function (): void {
        Livewire::test(SeoSettingsPage::class)
            ->fillForm([
                'default_canonical' => 'https://example.com',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('seo_settings', [
            'default_canonical' => 'https://example.com',
        ]);
    });
});
