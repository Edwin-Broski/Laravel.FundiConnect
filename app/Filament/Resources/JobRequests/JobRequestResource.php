<?php

namespace App\Filament\Resources\JobRequests;

use App\Filament\Resources\JobRequests\Pages\ListJobRequests;
use App\Filament\Resources\JobRequests\Pages\ViewJobRequest;
use App\Models\JobRequest;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;

class JobRequestResource extends Resource
{
    protected static ?string $model = JobRequest::class;
    protected static ?string $label = 'Job Request';

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-briefcase';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Operations';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('status')
                ->options([
                    'pending'     => 'Pending',
                    'accepted'    => 'Accepted',
                    'in_progress' => 'In Progress',
                    'completed'   => 'Completed',
                    'cancelled'   => 'Cancelled',
                    'declined'    => 'Declined',
                ]),
            Forms\Components\Textarea::make('description')
                ->disabled(),
            Forms\Components\TextInput::make('location_area')
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provider.user.name')
                    ->label('Provider')
                    ->searchable(),
                Tables\Columns\TextColumn::make('trade.name')
                    ->label('Trade')
                    ->badge(),
                Tables\Columns\TextColumn::make('location_area')
                    ->label('Area'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'pending'     => 'warning',
                        'accepted'    => 'info',
                        'in_progress' => 'primary',
                        'completed'   => 'success',
                        'cancelled'   => 'gray',
                        'declined'    => 'danger',
                        default       => 'gray',
                    }),
                Tables\Columns\IconColumn::make('customer_confirmed')
                    ->boolean()
                    ->label('Confirmed'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'     => 'Pending',
                        'accepted'    => 'Accepted',
                        'in_progress' => 'In Progress',
                        'completed'   => 'Completed',
                        'cancelled'   => 'Cancelled',
                        'declined'    => 'Declined',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobRequests::route('/'),
            'view'  => ViewJobRequest::route('/{record}'),
        ];
    }
}