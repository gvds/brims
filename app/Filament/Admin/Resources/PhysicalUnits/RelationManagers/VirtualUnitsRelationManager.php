<?php

namespace App\Filament\Admin\Resources\PhysicalUnits\RelationManagers;

use App\Models\Location;
use App\Models\Specimentype;
use App\Models\VirtualUnit;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Unique;

class VirtualUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'virtualUnits';

    public $physicalUnit;

    public $unitDefinition;

    public $data;

    public $racks = [];

    public $selectedRacks = [];

    public $selectedSection = null;

    public $selectionIsPartial = false;

    public Collection $virtualUnitsInPartialRack;

    public $startRack = null;

    public $endRack = null;

    public function isReadOnly(): bool
    {
        return false;
    }

    public function initialise(): void
    {
        $this->physicalUnit = $this->getOwnerRecord()->load('virtualUnits', 'unitDefinition');
        $this->unitDefinition = $this->physicalUnit->unitDefinition;
    }

    private function getVirtualUnitsinPartialRack(int $rack): void
    {
        $this->virtualUnitsInPartialRack = VirtualUnit::where('physical_unit_id', $this->physicalUnit->id)->where('startRack', $rack)->get();
    }

    public function toggleRack(int $rack, int $section): void
    {
        if ($this->isInvalidSelectableSection($section)) {
            return;
        }

        // if no racks are selected
        if (empty($this->selectedRacks)) {
            $this->selectedSection = $section;
            $this->virtualUnitsInPartialRack = collect();
            $this->selectedRacks[] = $rack;
            if ($this->racks[$rack] === 'p') {
                $this->selectionIsPartial = true;
                $this->data['rack_extent'] = 'Partial';
                $this->getVirtualUnitsinPartialRack($rack);
                $this->data['rackCapacity'] = $this->virtualUnitsInPartialRack->first()->rackCapacity;
                $this->data['boxCapacity'] = $this->virtualUnitsInPartialRack->first()->boxCapacity;

                return;
            }

            if ($this->data['rack_extent'] === 'Partial') {
                $this->racks[$rack] = 'p';
                $this->getVirtualUnitsinPartialRack($rack);
            } else {
                $this->racks[$rack] = 's';
            }
            $this->data['rackCapacity'] = $this->unitDefinition->sections[$section - 1]->boxes;
            $this->data['boxCapacity'] = $this->unitDefinition->sections[$section - 1]->positions;

            return;
        }

        // if a partial rack is selected
        if ($this->data['rack_extent'] === 'Partial') {
            if ($this->selectedRacks[0] === $rack) {
                $this->racks[$rack] = $this->selectionIsPartial ? 'p' : 'a';
                $this->selectionIsPartial = false;
                $this->virtualUnitsInPartialRack = collect([]);
                $this->selectedRacks = [];
                $this->data['rack_extent'] = 'Full';
                $this->selectionIsPartial = false;
                $this->selectedSection = null;
                $this->data['rackCapacity'] = null;
                $this->data['boxCapacity'] = null;
            }

            return;
        }

        // if we're working with full racks and we clicked on a partial rack
        if ($this->racks[$rack] === 'p') {
            return;
        }

        // Extend or reduce the selected full racks
        switch ($rack) {
            case $this->selectedRacks[0]:
                array_shift($this->selectedRacks);
                break;
            case $this->selectedRacks[0] - 1:
                array_unshift($this->selectedRacks, $rack);
                break;
            case $this->selectedRacks[array_key_last($this->selectedRacks)]:
                array_pop($this->selectedRacks);
                break;
            case $this->selectedRacks[array_key_last($this->selectedRacks)] + 1:
                $this->selectedRacks[] = $rack;
                break;
            default:
                return;
        }

        // if we're left with no selected racks, reset some variables
        if (empty($this->selectedRacks)) {
            $this->selectedSection = null;
            $this->virtualUnitsInPartialRack = collect();
            $this->data['rackCapacity'] = null;
            $this->data['boxCapacity'] = null;
        }

        // toggle the rack's selected state
        $this->racks[$rack] = $this->racks[$rack] === 'a' ? 's' : 'a';
    }

    private function isInvalidSelectableSection($section): bool
    {
        return $section !== $this->selectedSection and ! is_null($this->selectedSection);
    }

    /**
     * @param  Form  $form
     * @return Form
     *
     * @throws BindingResolutionException
     */
    public function form(Schema $schema): Schema
    {
        if (! isset($this->physicalUnit)) {
            $this->initialise();
        }

        return $schema
            ->schema([
                Grid::make()
                    ->columns(3)
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->required()
                                    ->unique(modifyRuleUsing: function (Unique $rule, Get $get) {
                                        return $rule->where('study_id', $get('study_id'));
                                    }),
                                Select::make('study_id')
                                    ->relationship(name: 'study', titleAttribute: 'name')
                                    ->preload()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(fn (Set $set) => $set('specimentype_id', null)),
                                Select::make('specimentype_id')
                                    ->options(
                                        fn (Get $get): Collection => Specimentype::query()
                                            ->where('study_id', $get('study_id'))
                                            ->pluck('name', 'id')
                                            ->unique()
                                    )
                                    ->required(),
                                Radio::make('rack_extent')
                                    ->options([
                                        'Full' => 'Full',
                                        'Partial' => 'Partial',
                                    ])
                                    ->default('Full')
                                    ->inline()
                                    ->disabled(fn (): bool => count($this->selectedRacks) > 0 or $this->selectionIsPartial)
                                    ->live()
                                    ->dehydrated()
                                    ->afterStateUpdated(
                                        function (Set $set) {
                                            $set('startBox', null);
                                            $set('endBox', null);
                                        }
                                    ),
                                Select::make('startBox')
                                    ->options(fn () => $this->getBoxOptions())
                                    ->live()
                                    ->requiredIf('rack_extent', 'Partial')
                                    ->disabled(fn (): bool => $this->data['rack_extent'] === 'Full')
                                    ->disableOptionWhen(fn ($value): bool => $this->selectionIsPartial and $this->boxIsUsed($value))
                                    ->afterStateUpdated(function (?string $state, Get $get, Set $set) {
                                        foreach ($this->virtualUnitsInPartialRack as $key => $virtualUnit) {
                                            if ($state < $virtualUnit->startBox and $get('endBox') > $virtualUnit->endBox) {
                                                $set('endBox', $state);
                                            }
                                        }
                                    })
                                    ->lte('endBox'),
                                Select::make('endBox')
                                    ->options(fn () => $this->getBoxOptions())
                                    ->live()
                                    ->requiredIf('rack_extent', 'Partial')
                                    ->disabled(fn (): bool => $this->data['rack_extent'] === 'Full')
                                    ->disableOptionWhen(fn ($value): bool => $this->selectionIsPartial and $this->boxIsUsed($value))
                                    ->afterStateUpdated(function (?string $state, Get $get, Set $set) {
                                        foreach ($this->virtualUnitsInPartialRack as $key => $virtualUnit) {
                                            if ($state > $virtualUnit->endBox and $get('startBox') < $virtualUnit->startBox) {
                                                $set('startBox', $state);
                                            }
                                        }
                                    })
                                    ->gte('startBox'),
                                TextInput::make('rackCapacity')
                                    ->numeric()
                                    ->disabled(fn (): bool => $this->selectionIsPartial)
                                    ->dehydrated()
                                    ->gt('0'),
                                TextInput::make('boxCapacity')
                                    ->numeric()
                                    ->disabled(fn (): bool => $this->selectionIsPartial)
                                    ->dehydrated()
                                    ->gt('0'),
                                // Forms\Components\Hidden::make('unitDefinition')
                                //     ->default($this->unitDefinition),
                            ])
                            ->columns(2)
                            ->columnSpan(['lg' => 1]),
                        Section::make($this->physicalUnit->name.' Layout')
                            ->schema([
                                View::make('filament.forms.components.virtual_unit'),
                            ])
                            ->columnSpan(['lg' => 2]),
                    ]),
            ])
            ->columns(1)
            ->statePath('data');
    }

    private function boxIsUsed($value): bool
    {
        $partialVirtualUnits = VirtualUnit::where('physical_unit_id', $this->physicalUnit->id)->where('startRack', $this->selectedRacks[0])->get();
        foreach ($partialVirtualUnits as $key => $virtualunit) {
            if ($virtualunit->startBox <= $value and $virtualunit->endBox >= $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  Get  $get
     * @param  UnitDefinition  $unitDefinition
     *
     * @property Section $section
     *
     * @return array <string|int>
     */
    private function getBoxOptions(): array
    {
        if (is_null($this->selectedSection)) {
            return [];
        }

        $section = $this->unitDefinition->sections[$this->selectedSection - 1];

        if ($this->selectionIsPartial) {
            $rackCapacity = $this->virtualUnitsInPartialRack->first()->rackCapacity;
        } else {
            $rackCapacity = $section->boxes;
        }

        return $this->unitDefinition->boxDesignation === 'Alpha' ?
            array_combine(range('A', chr(64 + $rackCapacity)), range('A', chr(64 + $rackCapacity))) :
            array_combine(range(1, $rackCapacity), range(1, $rackCapacity));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('study.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('specimentype.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rack_extent'),
                TextColumn::make('startRack')
                    ->sortable(),
                TextColumn::make('endRack'),
                TextColumn::make('startBox'),
                TextColumn::make('endBox'),
                TextColumn::make('rackCapacity'),
                TextColumn::make('boxCapacity'),
                TextColumn::make('locations_count')
                    ->label('Locations')
                    ->counts('locations'),
                TextColumn::make('free_locations_count')
                    ->label('Free Locations')
                    ->counts('freeLocations'),
                ToggleColumn::make('active'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->authorize(true)
                    ->visible(fn () => $this->getOwnerRecord()->available)
                    ->createAnother(false)
                    ->beforeFormValidated(function (CreateAction $action) {
                        if (empty($this->selectedRacks)) {
                            Notification::make()
                                ->title('Rack Selection Error')
                                ->body('One or more racks must be selected')
                                ->danger()
                                ->duration(5000)
                                ->send();
                            $action->halt();
                        }
                    })
                    ->mutateDataUsing(function (array $data): array {
                        if ($data['rack_extent'] === 'Full') {
                            $unitDefinition = $this->unitDefinition->load('sections');
                            $data['startBox'] = '1';
                            $data['endBox'] = $unitDefinition->sections[$this->selectedSection - 1]->boxes;
                            if ($unitDefinition->boxDesignation === 'Alpha') {
                                $data['startBox'] = 'A';
                                $data['endBox'] = chr($data['endBox'] + 64);
                            }
                        }
                        $data['startRack'] = $this->selectedRacks[0];
                        $data['endRack'] = $data['startRack'] + count($this->selectedRacks) - 1;
                        unset($data['unitDefinition']);

                        return $data;
                    })
                    ->beforeFormFilled(function () {
                        $this->selectedRacks = [];
                        $this->selectedSection = null;
                        $this->selectionIsPartial = false;
                        $rack_count = 0;
                        foreach ($this->unitDefinition->sections as $key => $section) {
                            $rack_count += $section->rows * $section->columns;
                        }
                        $this->racks = $boxcount = array_fill(1, $rack_count, 0);
                        foreach ($this->physicalUnit->virtualUnits as $virtualunit) {
                            if ($virtualunit->rack_extent === 'Full') {
                                for ($rack = $virtualunit->startRack; $rack <= $virtualunit->endRack; $rack++) {
                                    $this->racks[$rack] = $virtualunit->rackCapacity;
                                    $boxcount[$rack] = $virtualunit->rackCapacity;
                                }
                            } else {
                                $this->racks[$virtualunit->startRack] = $virtualunit->rackCapacity;
                                $boxcount[$virtualunit->startRack] += (ord($virtualunit->endBox) - ord($virtualunit->startBox) + 1);
                            }
                        }
                        for ($i = 1; $i <= count($boxcount); $i++) {
                            $this->racks[$i] = match (true) {
                                $boxcount[$i] === 0 => 'a',
                                $boxcount[$i] === $this->racks[$i] => 'u',
                                default => 'p'
                            };
                        }
                    })
                    ->after(function (VirtualUnit $record) {
                        for ($rack = $record->startRack; $record->startRack <= $record->endRack; $record->startRack++) {
                            for ($box = $record->startBox; $box <= $record->endBox; $box++) {
                                for ($position = 1; $position <= $record->boxCapacity; $position++) {
                                    Location::create([
                                        'virtual_unit_id' => $record->id,
                                        'rack' => $rack,
                                        'box' => $box,
                                        'position' => $position,
                                    ]);
                                }
                            }
                        }
                    })
                    ->modalWidth('full'),
            ])
            ->recordActions([
                Action::make('consolidate')
                    ->color(Color::Indigo)
                    ->button()
                    ->size('xs')
                    ->requiresConfirmation()
                    ->action(function (VirtualUnit $record) {
                        try {
                            DB::beginTransaction();
                            DB::table('locations')->where('virtual_unit_id', $record->id)->lockForUpdate()->get();
                            $record->consolidate();
                            DB::commit();
                            Notification::make()
                                ->title('Consolidation complete')
                                ->body('All specimens in Virtual Unit '.$record->virtualUnit.' have been relocated to the beginning of the unit.')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            Notification::make()
                                ->title('Consolidation failed')
                                ->body($th->getMessage())
                                ->danger()
                                ->duration(0)
                                ->send();
                        }
                    }),
                Action::make('shrink')
                    ->color(Color::Yellow)
                    ->button()
                    ->size('xs')
                    ->disabled(fn (VirtualUnit $record) => $record->free_extents() === 0)
                    ->requiresConfirmation()
                    ->schema([
                        Select::make('itemsToRemove')
                            ->label(fn (VirtualUnit $record) => $record->rack_extent === 'Full' ? 'Number of racks to remove' : 'Number of boxes to remove')
                            ->required()
                            ->options(fn (VirtualUnit $record) => array_combine(range(1, $record->free_extents()), range(1, $record->free_extents())))
                            ->default(1),
                    ])
                    ->action(function (VirtualUnit $record, array $data) {
                        try {
                            DB::beginTransaction();
                            DB::table('locations')->where('virtual_unit_id', $record->id)->lockForUpdate()->get();
                            if ($record->rack_extent === 'Full') {
                                $record->removeRacks($data['itemsToRemove']);
                            } else {
                                $record->removeBoxes($data['itemsToRemove']);
                            }
                            DB::commit();
                            Notification::make()
                                ->title('Shrinkage complete')
                                ->body('Virtual Unit '.$record->virtualUnit.' has been reduced by '.$data['itemsToRemove'].' '.($record->rack_extent === 'Full' ? 'racks' : 'boxes').'.')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            Notification::make()
                                ->title('Shrinkage failed')
                                ->body($th->getMessage())
                                ->danger()
                                ->duration(0)
                                ->send();
                        }
                    }),
                DeleteAction::make()
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->disabled(fn (VirtualUnit $record) => $record->usedLocations()->count() > 0),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
