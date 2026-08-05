<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Filament\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route as RouteFacade;
use Mahdijd\SeoManagement\Enums\OpenGraphType;
use Mahdijd\SeoManagement\Enums\RobotsDirective;
use Mahdijd\SeoManagement\Enums\TwitterCardType;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource\Pages;
use Mahdijd\SeoManagement\Models\SeoRoute;

/**
 * SeoRouteResource
 *
 * Filament Resource for managing named web route SEO metadata.
 */
class SeoRouteResource extends Resource
{
    protected static ?string $model = SeoRoute::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $slug = 'seo-routes';

    public static function getNavigationGroup(): ?string
    {
        return config('seo.filament.navigation_group', 'SEO');
    }

    public static function getNavigationLabel(): string
    {
        return __('SEO Routes');
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make(__('Route Information'))
                    ->schema([
                        Select::make('route_name')
                            ->label(__('Route Name'))
                            ->options(fn () => static::getAvailableRouteOptions())
                            ->searchable()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn (string $operation) => $operation === 'edit'),
                    ]),

                Section::make(__('Search Engine Optimization'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('seo::fields.title'))
                            ->maxLength(255)
                            ->nullable(),

                        Textarea::make('description')
                            ->label(__('seo::fields.description'))
                            ->rows(3)
                            ->nullable(),

                        TextInput::make('canonical')
                            ->label(__('seo::fields.canonical'))
                            ->url()
                            ->maxLength(500)
                            ->nullable(),

                        Select::make('robots')
                            ->label(__('seo::fields.robots'))
                            ->options(RobotsDirective::options())
                            ->nullable(),

                        TextInput::make('keywords')
                            ->label(__('seo::fields.keywords'))
                            ->maxLength(500)
                            ->nullable(),
                    ]),

                Section::make(__('Social Sharing'))
                    ->schema([
                        TextInput::make('og_title')
                            ->label(__('seo::fields.og_title'))
                            ->maxLength(255)
                            ->nullable(),

                        Textarea::make('og_description')
                            ->label(__('seo::fields.og_description'))
                            ->rows(2)
                            ->nullable(),

                        FileUpload::make('og_image')
                            ->label(__('seo::fields.og_image'))
                            ->image()
                            ->directory('seo-images')
                            ->nullable(),

                        Select::make('og_type')
                            ->label(__('seo::fields.og_type'))
                            ->options(OpenGraphType::options())
                            ->nullable(),

                        TextInput::make('og_url')
                            ->label(__('seo::fields.og_url'))
                            ->url()
                            ->maxLength(500)
                            ->nullable(),

                        TextInput::make('og_site_name')
                            ->label(__('seo::fields.og_site_name'))
                            ->maxLength(255)
                            ->nullable(),

                        Select::make('twitter_card')
                            ->label(__('seo::fields.twitter_card'))
                            ->options(TwitterCardType::options())
                            ->nullable(),

                        TextInput::make('twitter_title')
                            ->label(__('seo::fields.twitter_title'))
                            ->maxLength(255)
                            ->nullable(),

                        Textarea::make('twitter_description')
                            ->label(__('seo::fields.twitter_description'))
                            ->rows(2)
                            ->nullable(),

                        FileUpload::make('twitter_image')
                            ->label(__('seo::fields.twitter_image'))
                            ->image()
                            ->directory('seo-images')
                            ->nullable(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make(__('Structured Data'))
                    ->schema([
                        Textarea::make('json_ld')
                            ->label(__('seo::fields.json_ld'))
                            ->rows(5)
                            ->nullable(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('route_name')
                    ->label(__('Route Name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label(__('SEO Title'))
                    ->searchable()
                    ->limit(50),

                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSeoRoutes::route('/'),
            'create' => Pages\CreateSeoRoute::route('/create'),
            'edit'   => Pages\EditSeoRoute::route('/{record}/edit'),
        ];
    }

    /**
     * Get available named GET web routes for select dropdown.
     *
     * @return array<string, string>
     */
    public static function getAvailableRouteOptions(): array
    {
        $existing = SeoRoute::pluck('route_name')->toArray();
        $options  = [];

        foreach (RouteFacade::getRoutes()->getRoutes() as $route) {
            $name = $route->getName();

            if (! is_string($name) || $name === '' || str_starts_with($name, '_') || str_starts_with($name, 'filament.')) {
                continue;
            }

            if (! in_array('GET', $route->methods(), true)) {
                continue;
            }

            if (in_array($name, $existing, true)) {
                continue;
            }

            $options[$name] = sprintf('%s (%s)', $name, $route->uri());
        }

        ksort($options);

        return $options;
    }
}
