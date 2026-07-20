<x-filament-panels::page>
    <div>
        <div class='font-semibold mb-1'>Projects</div>
        <div class="flex gap-5 px-5 py-2 rounded-md max-w-max bg-white border-zinc-200 dark:bg-zinc-900 border dark:border-zinc-800 shadow-sm font-semibold">
            @foreach ($projects as $index => $project)
                <div style="color: {{ $this->colours[$project->id] }}">{{ $project->title }}</div>
            @endforeach
        </div>
    </div>
    {{-- {{  $this->table }} --}}

    {{-- <div class="flex flex-col space-y-4"> --}}
    <div class="grid grid-cols-2 gap-4">
    @foreach ($subjectEvents as $date => $eventsForDate)
        <div>
            <div class="font-semibold border-b pb-2 mb-2">
                {{-- {{ Carbon::parse($date)->format('l, d M y') }} --}}
                {{ $date }}
            </div>

            <div class="flex flex-wrap gap-4 pl-4">
                @foreach ($eventsForDate as $subjectEvent)
                    <div class='text-sm border-3 rounded px-3 py-1' style='border-color: {{ $colours[$subjectEvent['subject']['project']['id']] }}'>
                        <div>{{ $subjectEvent['subject']['firstname'] }} {{ $subjectEvent['subject']['lastname'] }}</div>
                        <div>{{ $subjectEvent['event']['arm']['name'] }}</div>
                        <div>{{ $subjectEvent['event']['name'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    </div>


    {{-- <div class="flex">
        @foreach ($subjectEvents as $subjectEvent)
        <div class='text-sm border rounded px-3 py-1'>
            <div>{{ $subjectEvent->eventDate }}</div>
            <div>{{ $subjectEvent->subject->fullname }}</div>
            <div>{{ $subjectEvent->event->arm->name }}</div>
            <div>{{ $subjectEvent->event->name }}</div>
        </div>
        @endforeach
    </div> --}}
</x-filament-panels::page>
