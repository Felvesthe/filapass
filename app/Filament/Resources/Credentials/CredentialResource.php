<?php

declare(strict_types=1);

namespace App\Filament\Resources\Credentials;

use App\Filament\Resources\Credentials\Pages\ManageCredentials;
use App\Models\Credential;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Rawilk\FilamentPasswordInput\Password;

class CredentialResource extends Resource
{
    protected static ?string $model = Credential::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedLockClosed;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('Credentials');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Credentials');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('credentials.service_name')),

                TextInput::make('login')
                    ->label(Str::ucfirst(__('validation.attributes.username')))
                    ->copyable(),

                TextInput::make('email')
                    ->label(Str::ucfirst(__('validation.attributes.email')))
                    ->copyable()
                    ->email(),

                Password::make('password')
                    ->label(Str::ucfirst(__('validation.attributes.password')))
                    ->copyable()
                    ->regeneratePassword(notify: false)
                    ->maxLength(16)
                    ->required(),

                TextInput::make('url')
                    ->label(__('credentials.url_address'))
                    ->url(),

                Select::make('category_id')
                    ->label(trans_choice('Category', 1))
                    ->placeholder(__('credentials.no_category'))
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->limit(5)
                    )
                    ->native(false)
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->groups([
                Group::make('category.name')
                    ->label(trans_choice('Category', 1)),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label(__('credentials.service_name'))
                    ->sortable()
                    ->searchable()
                    ->limit(24)
                    ->default(__('No Content')),

                TextColumn::make('login')
                    ->label(Str::ucfirst(__('validation.attributes.username')))
                    ->copyable()
                    ->sortable()
                    ->searchable()
                    ->limit(24)
                    ->default(__('No Content')),

                TextColumn::make('email')
                    ->label(Str::ucfirst(__('validation.attributes.email')))
                    ->copyable()
                    ->sortable()
                    ->searchable()
                    ->limit(42)
                    ->default(__('No Content')),

                TextColumn::make('password')
                    ->label(Str::ucfirst(__('validation.attributes.password')))
                    ->formatStateUsing(fn (): string => '••••••••••••')
                    ->copyable(),

                TextColumn::make('url')
                    ->label(__('credentials.url_address'))
                    ->url(fn (Credential $credential) => filter_var($credential->url, FILTER_SANITIZE_URL))
                    ->openUrlInNewTab()
                    ->sortable()
                    ->searchable()
                    ->limit(24)
                    ->toggleable()
                    ->default(__('No Content')),

                TextColumn::make('category.name')
                    ->label(trans_choice('Category', 1))
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(trans_choice('Category', 1))
                    ->relationship('category', 'name'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->stackedOnMobile();
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCredentials::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
