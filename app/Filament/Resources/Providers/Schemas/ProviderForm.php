<?php

namespace App\Filament\Resources\Providers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProviderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required(),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('location_area'),
                TextInput::make('location_district'),
                Toggle::make('is_available')
                    ->required(),
                Toggle::make('is_verified')
                    ->required(),
                TextInput::make('avg_rating')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('jobs_completed')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('id_photo'),
                TextInput::make('certificate_photo'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
