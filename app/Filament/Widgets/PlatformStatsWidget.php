<?php

namespace App\Filament\Widgets;

use App\Models\Dispute;
use App\Models\JobRequest;
use App\Models\Provider;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total users', User::count())
                ->description('All registered accounts')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Providers pending', Provider::where('status', 'pending')->count())
                ->description('Awaiting your review')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Active providers', Provider::where('status', 'approved')->count())
                ->description('Live on the platform')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('success'),

            Stat::make('Total jobs', JobRequest::count())
                ->description('All time job requests')
                ->icon('heroicon-o-briefcase')
                ->color('info'),

            Stat::make('Completed jobs', JobRequest::where('status', 'completed')->count())
                ->description('Successfully finished')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Open disputes', Dispute::where('status', 'open')->count())
                ->description('Need your attention')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}