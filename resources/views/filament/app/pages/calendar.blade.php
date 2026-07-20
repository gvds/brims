<x-filament-panels::page>
    <!-- 1. Enforce a rigid outer viewport container that intercepts Filament's flow -->
    <div class="h-[calc(100vh-14rem)] flex flex-col w-full">

        <div>
            <div class='font-semibold mb-1 ml-2'>Projects</div>
            <div class="flex gap-5 px-5 py-2 rounded-md max-w-max bg-white border-zinc-200 dark:bg-zinc-900 border dark:border-zinc-800 shadow-sm font-semibold">
                @foreach ($projects as $index => $project)
                    <div style="color: {{ $this->colours[$project->id] }}">{{ $project->title }}</div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between">
            <div class='font-semibold text-xl mt-5 mb-2 ml-2'>{{ $month }}</div>
            <div>
                <span class="border rounded-md bg-zinc-800 px-3 py-1 cursor-pointer" wire:click='previousmonth'>Previous</span>
                <span class="border rounded-md bg-zinc-800 px-3 py-1 cursor-pointer" wire:click='nextmonth'>Next</span>
            </div>
        </div>

        <div class="border rounded-md border-zinc-300 dark:border-zinc-700! bg-zinc-100 dark:bg-zinc-900! shadow-md min-w-3/4 max-w-max flex flex-col grow h-full overflow-hidden">

            <div class="grid grid-cols-7 border-b border-zinc-300 dark:border-zinc-700! min-h-10 text-sm">
                <div class="m-auto">Sunday</div>
                <div class="m-auto">Monday</div>
                <div class="m-auto">Tuesday</div>
                <div class="m-auto">Wednesday</div>
                <div class="m-auto">Thursday</div>
                <div class="m-auto">Friday</div>
                <div class="m-auto">Saturday</div>
            </div>

            <div class="grid grid-cols-7 grid-rows-5 border-t border-l border-zinc-200 dark:border-zinc-700! text-xs grow h-full auto-rows:1fr">
                @foreach ($weeks as $week)
                    @for($day = 0; $day < 7; $day++)
                        <div class="px-3 py-2 border-b border-r border-zinc-300 dark:border-zinc-700! flex gap-x-3 overflow-y-visible">
                            <div>
                                {{ $week['start']->copy()->addDays($day)->format('j') }}
                            </div>
                            <div class="flex-col space-y-2 w-full">
                                @php
                                    $date = $week['start']->copy()->addDays($day)
                                @endphp
                                @if (array_key_exists($date->format('Y-m-d'), $subjectEvents))
                                    @foreach ($subjectEvents[$date->format('Y-m-d')] as $event)
                                        <div class="group relative inline-block">
                                            <div style="color: {{ $this->colours[$event['subject']['project']['id']] }}" class="border py-1 px-2 rounded-md font-semibold cursor-pointer">
                                            {{ $event['subject']['firstname'] . ' ' . $event['subject']['lastname'] }}
                                            </div>
                                            <div class="pointer-events-none absolute mb-2 bottom-full left-1/2 z-50 -translate-x-1/2 whitespace-nowrap rounded shadow-lg bg-zinc-200 text-zinc-900 px-3 py-1.5 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                                <div>Arm: {{ $event['event']['arm']['name'] }}</div>
                                                <div>Event: {{ $event['event']['name'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endfor
                @endforeach
            </div>

        </div>

    </div>
</x-filament-panels::page>
