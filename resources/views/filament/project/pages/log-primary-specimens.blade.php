<x-filament-panels::page>
    <div class="space-y-6">
        @if (!$stageOneCompleted)
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <h2 class="text-xl font-bold tracking-tight mb-5">
                    Scan Project-Subject-Event Barcode
                </h2>
                {{ $this->form }}
            </div>
        @else
            <div class="text-xl bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                    Log specimens for Subject <span class="font-bold">{{ $subject->subjectID }}</span>,
                    Event <span class="font-bold">{{ $subjectEvent->event->name }}</span>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                {{ $this->form }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
