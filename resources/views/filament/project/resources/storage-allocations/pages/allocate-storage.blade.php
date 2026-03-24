<x-filament-panels::page>
    <form wire:submit="allocate" class="space-y-6 max-w-max min-w-100 bg-zinc-50 dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg p-6 shadow-md">
        {{ $this->form }}

        <x-filament::button type="submit" class='w-full'>
            Allocate
        </x-filament::button>
    </form>
</x-filament-panels::page>
