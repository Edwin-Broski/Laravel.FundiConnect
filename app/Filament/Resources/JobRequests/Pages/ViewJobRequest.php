<?php

namespace App\Filament\Resources\JobRequests\Pages;

use App\Filament\Resources\JobRequests\JobRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJobRequest extends ViewRecord
{
    protected static string $resource = JobRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
