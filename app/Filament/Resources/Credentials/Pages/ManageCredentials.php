<?php

declare(strict_types=1);

namespace App\Filament\Resources\Credentials\Pages;

use App\Filament\Resources\Credentials\CredentialResource;
use App\Filament\Resources\Credentials\Widgets\CredentialOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Auth\AuthenticationException;

class ManageCredentials extends ManageRecords
{
    protected static string $resource = CredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateDataUsing(function (array $data): array {
                    $user = auth()->user();

                    if ($user === null) {
                        throw new AuthenticationException;
                    }

                    $data['user_id'] = $user->id;

                    return $data;
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CredentialOverview::class,
        ];
    }
}
