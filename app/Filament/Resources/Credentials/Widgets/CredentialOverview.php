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
                Credential::query()
                    ->where('user_id', auth()->user()?->id)
                    ->count()
            ),
            Stat::make(
                __('credentials.stats.credentials_trash'),
                Credential::onlyTrashed()
                    ->where('user_id', auth()->user()?->id)
                    ->count()
            ),
            Stat::make(
                __('credentials.stats.credentials_wo_category'),
                Credential::query()
                    ->where('category_id', null)
                    ->where('user_id', auth()->user()?->id)
                    ->count()
            ),
        ];
    }
}
