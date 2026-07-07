<?php

namespace App\Filament\Resources\Providers;

use App\Filament\Resources\Providers\Pages\EditProvider;
use App\Filament\Resources\Providers\Pages\ListProviders;
use App\Filament\Resources\Providers\Pages\CreateProvider;
use App\Models\Provider;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;

class ProviderResource extends Resource
{
    protected static ?string $model = Provider::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-wrench-screwdriver';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'People';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('status')
                ->options([
                    'pending'   => 'Pending',
                    'approved'  => 'Approved',
                    'rejected'  => 'Rejected',
                    'suspended' => 'Suspended',
                ])
                ->required(),
            Forms\Components\Toggle::make('is_available')
                ->label('Available'),
            Forms\Components\Toggle::make('is_verified')
                ->label('Verified'),
            Forms\Components\Textarea::make('bio')
                ->nullable(),
            Forms\Components\TextInput::make('location_area')
                ->nullable(),
            Forms\Components\TextInput::make('location_district')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('trades.name')
                    ->label('Trades')
                    ->badge(),
                Tables\Columns\TextColumn::make('location_area')
                    ->label('Area'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'pending'   => 'warning',
                        'approved'  => 'success',
                        'rejected'  => 'danger',
                        'suspended' => 'gray',
                        default     => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified'),
                Tables\Columns\TextColumn::make('avg_rating')
                    ->label('Rating')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobs_completed')
                    ->label('Jobs')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'approved'  => 'Approved',
                        'rejected'  => 'Rejected',
                        'suspended' => 'Suspended',
                    ]),
            ])
            ->actions([
                ViewAction::make(),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Provider $record) => $record->status === 'pending')
                    ->action(fn(Provider $record) => $record->update([
                        'status'      => 'approved',
                        'is_verified' => true,
                    ]))
                    ->requiresConfirmation(),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Provider $record) => $record->status === 'pending')
                    ->action(fn(Provider $record) => $record->update([
                        'status' => 'rejected',
                    ]))
                    ->requiresConfirmation(),

                Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-o-no-symbol')
                    ->color('warning')
                    ->visible(fn(Provider $record) => $record->status === 'approved')
                    ->action(function (Provider $record) {
                        $record->update(['status' => 'suspended']);
                        $record->user->update(['is_active' => false]);
                    })
                    ->requiresConfirmation(),
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
        'index'  => ListProviders::route('/'),
        'create' => CreateProvider::route('/create'),
        'edit'   => EditProvider::route('/{record}/edit'),
    ];
}
}