<?php

namespace App\Filament\Resources\JobRequests\Pages;

use App\Filament\Resources\JobRequests\JobRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditJobRequest extends EditRecord
{
    protected static string $resource = JobRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
