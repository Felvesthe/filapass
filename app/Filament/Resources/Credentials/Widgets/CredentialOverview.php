<?php

declare(strict_types=1);

namespace App\Filament\Resources\Credentials\Widgets;

use App\Models\Credential;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CredentialOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                __('credentials.stats.active_credentials'),
                Credential::count()
            ),
            Stat::make(
                __('credentials.stats.credentials_trash'),
                Credential::onlyTrashed()
                    ->count()
            ),
            Stat::make(
                __('credentials.stats.credentials_wo_category'),
                Credential::where('category_id', null)
                    ->count()
            ),
        ];
    }
}
