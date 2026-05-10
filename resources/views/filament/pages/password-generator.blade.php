<x-filament-panels::page>
    <form class="flex flex-col gap-4">
        {{ $this->form }}

        <x-filament::button wire:click="generate" class="mb-2">
            {{ __('password-generators.generate_btn') }}
        </x-filament::button>

        {{ $this->password }}
    </form>
</x-filament-panels::page>
