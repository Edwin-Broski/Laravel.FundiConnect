<?php

namespace App\Filament\Resources\Disputes;

use App\Filament\Resources\Disputes\Pages\EditDispute;
use App\Filament\Resources\Disputes\Pages\ListDisputes;
use App\Filament\Resources\Disputes\Pages\ViewDispute;
use App\Models\Dispute;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;

class DisputeResource extends Resource
{
    protected static ?string $model = Dispute::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-exclamation-triangle';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Operations';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'open')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Textarea::make('reason')
                ->disabled(),
            Forms\Components\Select::make('status')
                ->options([
                    'open'         => 'Open',
                    'under_review' => 'Under Review',
                    'resolved'     => 'Resolved',
                ]),
            Forms\Components\Textarea::make('admin_notes')
                ->label('Admin notes')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('raisedBy.name')
                    ->label('Raised by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jobRequest.trade.name')
                    ->label('Trade'),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(40),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'open'         => 'danger',
                        'under_review' => 'warning',
                        'resolved'     => 'success',
                        default        => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Raised')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open'         => 'Open',
                        'under_review' => 'Under Review',
                        'resolved'     => 'Resolved',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('resolve')
                    ->label('Mark resolved')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Dispute $record) => $record->status !== 'resolved')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Resolution notes')
                            ->required(),
                    ])
                    ->action(fn(Dispute $record, array $data) => $record->update([
                        'status'      => 'resolved',
                        'admin_notes' => $data['admin_notes'],
                    ]))
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
            'index' => ListDisputes::route('/'),
            'view'  => ViewDispute::route('/{record}'),
            'edit'  => EditDispute::route('/{record}/edit'),
        ];
    }
}