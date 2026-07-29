<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Mahdijd\SeoManagement\Filament\Resources\SeoRouteResource;

class EditSeoRoute extends EditRecord
{
    protected static string $resource = SeoRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
