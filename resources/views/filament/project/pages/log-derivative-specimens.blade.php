<x-filament-panels::page>
    <div class="space-y-6">
        @if (!$stageOneCompleted)
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <h2 class="text-xl font-bold tracking-tight mb-5">
                    Scan Parent Specimen Barcode
                </h2>
                {{ $this->form }}
            </div>
        @else
        <div class='flex justify-between text-xl bg-white dark:bg-gray-800 shadow rounded-xl p-6'>
            <div>
                Log specimens for Parent Specimen: <span class="font-bold">{{ $parent_specimen->barcode }}</span>
                </div>
                    <div class="italic">Subject: <span class="font-bold">{{ $subject->subjectID }}</span> |
                    Event: <span class="font-bold">{{ $subjectEvent->event->name }}</span></div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                {{ $this->form }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
