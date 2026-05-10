<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

/**
 * @property-read Schema $form
 * @property-read Schema $password
 */
class PasswordGenerator extends Page
{
    protected string $view = 'filament.pages.password-generator';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?int $navigationSort = 2;

    /** @var array<int, string> | null */
    public ?array $passwordContaining = [];

    public int $passwordLength = 16;

    public string $generatedPassword = '';

    public static function getNavigationLabel(): string
    {
        return __('Password Generator');
    }

    public function getTitle(): string | Htmlable
    {
        return __('Password Generator');
    }

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::ExtraLarge;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('password-generators.heading'))
                    ->schema([
                        Fieldset::make(__('password-generators.password_containing'))
                            ->schema([
                                CheckboxList::make('containing')
                                    ->hiddenLabel()
                                    ->options([
                                        'letters' => __('password-generators.letters'),
                                        'numbers' => __('password-generators.numbers'),
                                        'symbols' => __('password-generators.symbols'),
                                        'spaces' => __('password-generators.spaces'),
                                    ])
                                    ->required()
                                    ->columns()
                                    ->columnSpanFull()
                                    ->validationMessages([
                                        'required' => __('password-generators.at_least_one_selected'),
                                    ])
                                    ->statePath('passwordContaining'),
                            ]),

                        TextInput::make('length')
                            ->label(Str::ucfirst(__('length')))
                            ->inlineLabel()
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->statePath('passwordLength'),
                    ]),
            ]);
    }

    public function password(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('generatedPassword')
                    ->label(__('password-generators.generated_password'))
                    ->copyable()
                    ->readOnly()
                    ->hidden(fn () => empty($this->generatedPassword)),
            ]);
    }

    public function generate(): void
    {
        /** @var array<string, array<int, string> | int> $state */
        $state = $this->form->getState();

        /** @var array<int, string> $passwordContaining */
        $passwordContaining = $state['passwordContaining'];

        /** @var int $passwordLength */
        $passwordLength = $state['passwordLength'] ?? 16;

        $this->generatedPassword = Str::password(
            length: $passwordLength,
            letters: in_array('letters', $passwordContaining),
            numbers: in_array('numbers', $passwordContaining),
            symbols: in_array('symbols', $passwordContaining),
            spaces: in_array('spaces', $passwordContaining),
        );
    }
}
