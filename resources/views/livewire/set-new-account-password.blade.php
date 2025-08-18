<div class="flex items-center justify-center h-screen">
    <div class='mx-10 max-w-max bg-slate-100 border border-gray-400 rounded-xl p-10'>
        <h1 class='font-bold text-4xl mb-5 text-center'>
            {{ config('app.name')}}
        </h1>
        <h1 class='font-bold text-2xl mb-5'>
            Set New Account Password
        </h1>
        <form wire:submit="submit">
            {{ $this->form }}

            <button type="submit" class='bg-blue-600 text-white px-4 py-2 rounded-md mt-10'>
                Submit
            </button>
        </form>

        <x-filament-actions::modals />
    </div>
</div>
