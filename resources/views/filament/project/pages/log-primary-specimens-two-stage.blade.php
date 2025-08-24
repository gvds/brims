<x-filament-panels::page>
    <div class="space-y-6">
        @if (!$stageOneCompleted)
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold tracking-tight">
                    Stage 1: Scan Project-Subject-Event Barcode
                </h2>
                <p class="mt-2 text-gray-500 dark:text-gray-400 mb-6">
                    Scan the barcode containing the project ID, subject ID, and subject event ID
                </p>

                {{ $this->form }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight">
                            Stage 2: Capture Primary Specimens
                        </h2>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">
                            Log specimens for Subject <span class="font-semibold">{{ $subject->subjectID }}</span>,
                            Event <span class="font-semibold">{{ $subjectEvent->event->name }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                {{ $this->form }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
