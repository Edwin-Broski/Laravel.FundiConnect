<?php

namespace App\Filament\Resources\JobRequests\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class JobRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('customer_id'),
                TextEntry::make('provider_id'),
                TextEntry::make('trade_id'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('location_address')
                    ->placeholder('-'),
                TextEntry::make('location_area')
                    ->placeholder('-'),
                TextEntry::make('status'),
                IconEntry::make('customer_confirmed')
                    ->boolean(),
                IconEntry::make('provider_confirmed')
                    ->boolean(),
                TextEntry::make('completion_photo')
                    ->placeholder('-'),
                IconEntry::make('customer_no_show_flag')
                    ->boolean(),
                TextEntry::make('scheduled_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
