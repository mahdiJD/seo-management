<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Filament\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\Enums\OpenGraphType;
use Mahdijd\SeoManagement\Enums\RobotsDirective;
use Mahdijd\SeoManagement\Enums\TwitterCardType;

/**
 * SeoManagerGroup
 *
 * Reusable Filament form group component for managing Eloquent model SEO metadata.
 */
class SeoManagerGroup
{
    /**
     * Create the Filament form schema for SEO metadata.
     *
     * @return Group
     */
    public static function make(): Group
    {
        return Group::make([
            Section::make(__('Search Engine Optimization'))
                ->schema([
                    TextInput::make('seo.title')
                        ->label(__('seo::fields.title'))
                        ->maxLength(255)
                        ->helperText(__('Recommended: max 60 characters'))
                        ->nullable(),

                    Textarea::make('seo.description')
                        ->label(__('seo::fields.description'))
                        ->rows(3)
                        ->helperText(__('Recommended: max 160 characters'))
                        ->nullable(),

                    TextInput::make('seo.canonical')
                        ->label(__('seo::fields.canonical'))
                        ->url()
                        ->maxLength(500)
                        ->nullable(),

                    Select::make('seo.robots')
                        ->label(__('seo::fields.robots'))
                        ->options(RobotsDirective::options())
                        ->searchable()
                        ->nullable(),

                    TextInput::make('seo.keywords')
                        ->label(__('seo::fields.keywords'))
                        ->maxLength(500)
                        ->nullable(),
                ])
                ->collapsible(),

            Section::make(__('Social Sharing'))
                ->schema([
                    TextInput::make('seo.og_title')
                        ->label(__('seo::fields.og_title'))
                        ->maxLength(255)
                        ->nullable(),

                    Textarea::make('seo.og_description')
                        ->label(__('seo::fields.og_description'))
                        ->rows(2)
                        ->nullable(),

                    FileUpload::make('seo.og_image')
                        ->label(__('seo::fields.og_image'))
                        ->image()
                        ->directory('seo-images')
                        ->nullable(),

                    Select::make('seo.og_type')
                        ->label(__('seo::fields.og_type'))
                        ->options(OpenGraphType::options())
                        ->searchable()
                        ->nullable(),

                    TextInput::make('seo.og_url')
                        ->label(__('seo::fields.og_url'))
                        ->url()
                        ->maxLength(500)
                        ->nullable(),

                    TextInput::make('seo.og_site_name')
                        ->label(__('seo::fields.og_site_name'))
                        ->maxLength(255)
                        ->nullable(),

                    Select::make('seo.twitter_card')
                        ->label(__('seo::fields.twitter_card'))
                        ->options(TwitterCardType::options())
                        ->searchable()
                        ->nullable(),

                    TextInput::make('seo.twitter_title')
                        ->label(__('seo::fields.twitter_title'))
                        ->maxLength(255)
                        ->nullable(),

                    Textarea::make('seo.twitter_description')
                        ->label(__('seo::fields.twitter_description'))
                        ->rows(2)
                        ->nullable(),

                    FileUpload::make('seo.twitter_image')
                        ->label(__('seo::fields.twitter_image'))
                        ->image()
                        ->directory('seo-images')
                        ->nullable(),
                ])
                ->collapsible()
                ->collapsed(),

            Section::make(__('Structured Data'))
                ->schema([
                    Textarea::make('seo.json_ld')
                        ->label(__('seo::fields.json_ld'))
                        ->rows(5)
                        ->helperText(__('Valid JSON-LD schema markup array or string'))
                        ->nullable(),
                ])
                ->collapsible()
                ->collapsed(),
        ]);
    }

    /**
     * Save or delete SEO metadata record for the model based on state.
     *
     * @param  Model  $record
     * @param  array<string, mixed>  $data
     * @return void
     */
    public static function save(Model $record, array $data): void
    {
        /** @var SeoMetadataRepositoryInterface $repository */
        $repository = app(SeoMetadataRepositoryInterface::class);

        // Filter out null/empty values
        $cleanData = array_filter($data, fn ($val) => $val !== null && $val !== '');

        $deleteEmpty = (bool) config('seo.filament.delete_empty_records', false);

        if ($deleteEmpty && empty($cleanData)) {
            $repository->delete($record);
        } else {
            $repository->save($record, $data);
        }
    }
}
