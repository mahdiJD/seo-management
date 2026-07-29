<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource;

class ListSeoRoutes extends ListRecords
{
    protected static string $resource = SeoRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
