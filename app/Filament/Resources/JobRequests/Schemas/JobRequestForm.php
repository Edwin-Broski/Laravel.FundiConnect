<?php

namespace App\Filament\Resources\JobRequests\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class JobRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->required(),
                TextInput::make('provider_id')
                    ->required(),
                TextInput::make('trade_id')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('location_address'),
                TextInput::make('location_area'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Toggle::make('customer_confirmed')
                    ->required(),
                Toggle::make('provider_confirmed')
                    ->required(),
                TextInput::make('completion_photo'),
                Toggle::make('customer_no_show_flag')
                    ->required(),
                DateTimePicker::make('scheduled_at'),
            ]);
    }
}
