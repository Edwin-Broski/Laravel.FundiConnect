<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PlatformStatsWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-home';
    }

    public function getWidgets(): array
    {
        return [
            PlatformStatsWidget::class,
        ];
    }
}