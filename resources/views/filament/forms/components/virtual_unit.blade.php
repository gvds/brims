<div class='py-2 px-3'>

    {{-- <div class="flex gap-9 max-w-fit" x-bind:class="{{$this->unitDefinition->sectionLayout == 'Vertical'}} ? 'flex-col' : 'flex-row'">
        --}}
        <div class="flex">
            <div class="flex flex-1 gap-9 flex-col">
                @php
                $rack = 0;
                @endphp
                @foreach ($this->unitDefinition->sections as $section)
                <div>
                    <div class='font-semibold text-slate-800 dark:text-slate-300 mb-1'>Section {{$section->section_number}}</div>
                    <div class='border border-slate-300 rounded-md p-1 mb-3 max-w-max'>
                        <table class='text-slate-100 bg-slate-300 rounded-md'>
                            @foreach (range(1,$section->rows) as $row)
                            <tr>
                                @foreach (range(1,$section->columns) as $column)
                                @php
                                $rack++;
                                @endphp
                                @if ($this->racks[$rack] === 'u')
                                <td>
                                    <div
                                        class="w-12 h-12 border border-slate-500 rounded-md p-2 cursor-pointer bg-radial  flex items-center justify-center from-rose-950 via-rose-600 to-rose-300">
                                        {{$rack}}</div>
                                </td>
                                @else
                                <td wire:click="toggleRack({{$rack}},{{$section->section_number}})">
                                    @php
                                    $appearance = match ($this->racks[$rack]) {
                                    'a' => 'from-slate-950 via-slate-700 to-slate-300 hover:from-slate-300',
                                    's' => 'from-emerald-950 via-emerald-700 to-emerald-300 hover:from-emerald-300',
                                    'p' => 'from-sky-950 via-sky-700 to-sky-300 hover:from-sky-300',
                                    }
                                    @endphp
                                    <div
                                        class="w-12 h-12 border border-slate-500 rounded-md p-2 cursor-pointer bg-radial flex items-center justify-center {{$appearance}}">
                                        {{$rack}}
                                    </div>
                                </td>
                                @endif
                                @endforeach
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Legend --}}
            <div>
                <div class='border border-slate-300 rounded-md p-1 mb-3 max-w-max'>
                    <table class='text-slate-100'>
                        <tr>
                            <th class='text-slate-800 dark:text-slate-300 pr-2'>Allocated</th>
                            <td>
                                <div
                                    class="w-10 h-10 border border-slate-500 rounded-md p-2 text-center bg-radial from-rose-950 via-rose-600 to-rose-300">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class='text-slate-800 dark:text-slate-300 pr-2'>Partial</th>
                            <td>
                                <div
                                    class="w-10 h-10 border border-slate-500 rounded-md p-2 text-center bg-radial from-sky-950 via-sky-700 to-sky-300">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class='text-slate-800 dark:text-slate-300 pr-2'>Available</th>
                            <td>
                                <div
                                    class="w-10 h-10 border border-slate-500 rounded-md p-2 text-center bg-radial from-slate-950 via-slate-700 to-slate-300">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class='text-slate-800 dark:text-slate-300 pr-2'>Selected</th>
                            <td>
                                <div
                                    class="w-10 h-10 border border-slate-500 rounded-md p-2 text-center bg-radial from-emerald-950 via-emerald-700 to-emerald-300">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    {{-- </div> --}}
</div>
