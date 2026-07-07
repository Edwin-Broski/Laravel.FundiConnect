<?php

namespace App\Filament\Resources\JobRequests\Pages;

use App\Filament\Resources\JobRequests\JobRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobRequest extends CreateRecord
{
    protected static string $resource = JobRequestResource::class;
}
