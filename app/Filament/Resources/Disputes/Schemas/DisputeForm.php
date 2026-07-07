<?php

namespace App\Filament\Resources\Disputes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DisputeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('job_request_id')
                    ->required(),
                TextInput::make('raised_by')
                    ->required(),
                Textarea::make('reason')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                Textarea::make('admin_notes')
                    ->columnSpanFull(),
            ]);
    }
}
