<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>

    <input wire:model="{{ $getStatePath() }}" class="border  border-neutral-300 dark:border-neutral-700 rounded-md shadow-xs py-1 px-2 my-0 bg-white dark:bg-neutral-800 min-w-40" />

    {{-- <div
        x-data="{ state: $wire.$entangle(@js($getStatePath())) }"
        {{ $getExtraAttributeBag() }}
    > --}}
        {{-- Interact with the `state` property in Alpine.js --}}
    {{-- </div> --}}
</x-dynamic-component>
