<?php

namespace App\Filament\Resources\JobRequests\Pages;

use App\Filament\Resources\JobRequests\JobRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobRequests extends ListRecords
{
    protected static string $resource = JobRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
