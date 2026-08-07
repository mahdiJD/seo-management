<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\Enums\OpenGraphType;
use Mahdijd\SeoManagement\Enums\RobotsDirective;
use Mahdijd\SeoManagement\Enums\TwitterCardType;

/**
 * SeoSettingsPage
 *
 * Single-record Filament Page for configuring global default fallback SEO settings.
 * Auto-creates the single database record on mount via SeoSettingsRepositoryInterface.
 */
class SeoSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'seo::pages.seo-settings';

    protected static ?string $slug = 'seo-settings';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return config('seo.filament.navigation_group', 'SEO');
    }

    public static function getNavigationLabel(): string
    {
        return __('SEO Settings');
    }

    public function getTitle(): string
    {
        return __('Global SEO Settings');
    }

    public function mount(): void
    {
        /** @var SeoSettingsRepositoryInterface $repository */
        $repository = app(SeoSettingsRepositoryInterface::class);

        $settings = $repository->get();

        $this->form->fill($settings->toArray());
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make(__('Search Engine Defaults'))
                    ->schema([
                        TextInput::make('site_name')
                            ->label(__('seo::fields.site_name'))
                            ->maxLength(255)
                            ->nullable(),

                        TextInput::make('default_title')
                            ->label(__('seo::fields.default_title'))
                            ->maxLength(255)
                            ->nullable(),

                        Textarea::make('default_description')
                            ->label(__('seo::fields.default_description'))
                            ->rows(3)
                            ->nullable(),

                        TextInput::make('default_canonical')
                            ->label(__('seo::fields.default_canonical'))
                            ->url()
                            ->maxLength(500)
                            ->nullable(),

                        Select::make('default_robots')
                            ->label(__('seo::fields.default_robots'))
                            ->options(RobotsDirective::options())
                            ->nullable(),
                    ]),

                Section::make(__('Open Graph Defaults'))
                    ->schema([
                        FileUpload::make('default_og_image')
                            ->label(__('seo::fields.default_og_image'))
                            ->image()
                            ->directory('seo-images')
                            ->nullable(),

                        Select::make('default_og_type')
                            ->label(__('seo::fields.default_og_type'))
                            ->options(OpenGraphType::options())
                            ->nullable(),

                        TextInput::make('default_og_site_name')
                            ->label(__('seo::fields.default_og_site_name'))
                            ->maxLength(255)
                            ->nullable(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make(__('Twitter Card Defaults'))
                    ->schema([
                        Select::make('default_twitter_card')
                            ->label(__('seo::fields.default_twitter_card'))
                            ->options(TwitterCardType::options())
                            ->nullable(),

                        FileUpload::make('default_twitter_image')
                            ->label(__('seo::fields.default_twitter_image'))
                            ->image()
                            ->directory('seo-images')
                            ->nullable(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make(__('Structured Data Defaults'))
                    ->schema([
                        Textarea::make('default_json_ld')
                            ->label(__('seo::fields.default_json_ld'))
                            ->rows(5)
                            ->nullable(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        /** @var SeoSettingsRepositoryInterface $repository */
        $repository = app(SeoSettingsRepositoryInterface::class);

        $state = $this->form->getState();

        $repository->update($state);

        Notification::make()
            ->title(__('Global SEO settings updated successfully.'))
            ->success()
            ->send();
    }
}
