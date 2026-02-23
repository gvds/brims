<?php

namespace App\Filament\Project\Pages;

use App\Enums\SpecimenStatus;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\Subject;
use App\Models\SubjectEvent;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LogDerivativeSpecimens extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected string $view = 'filament.project.pages.log-derivative-specimens';

    public ?User $user = null;

    public ?int $userSiteId = null;

    public ?array $specimens = null;

    public Collection $specimenTypes;

    public ?Subject $subject = null;

    public ?SubjectEvent $subjectEvent = null;

    public int $stage = 0;

    public ?string $selection_route = null;

    public ?string $parent_barcode = null;

    public ?string $pse_barcode = null;

    public ?Specimen $parent_specimen = null;

    public Collection $potentialParentSpecimens;

    protected $listeners = ['updateform' => '$refresh'];

    public function mount(): void
    {
        $this->initializeUser();
        $this->initializeSpecimenTypes();
    }

    private function initializeUser(): void
    {
        $this->user = session('currentProject')->members()->where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        if (! $this->user) {
            Notification::make()
                ->title('Error')
                ->body('You are not a member of this project.')
                ->color('danger')
                ->send();
            $this->redirect(route('filament.app.resources.projects.index'));
        }

        // Store the site_id separately since pivot data gets lost during Livewire serialization
        $this->userSiteId = $this->user->pivot->site_id;
    }

    private function initializeSpecimenTypes(): void
    {
        $this->specimenTypes = Specimentype::query()
            ->where('project_id', session('currentProject')->id)
            ->where('primary', false)
            ->get();
    }

    protected function form(Schema $form): Schema
    {
        if ($this->stage === 0) {
            return $form
                ->components(
                    [
                        ToggleButtons::make('selection_route')
                            ->label('Select by')
                            ->options([
                                'parent_barcode' => 'Parent Specimen',
                                'pse_barcode' => 'Project Subject Event',
                            ])
                            ->autofocus()
                            ->inline()
                            ->live(),
                        TextInput::make('parent_barcode')
                            ->label('Parent Specimen Barcode')
                            ->helperText('Scan the parent specimen barcode')
                            ->statePath('parent_barcode')
                            ->visible(fn($get) => $get('selection_route') === 'parent_barcode')
                            ->scopedExists(Specimen::class, 'barcode')
                            ->extraAttributes([
                                'class' => 'w-full md:w-80',
                                'x-on:keydown.enter.prevent' => '$wire.loadSpecimenBarcodes()',
                            ]),
                        TextInput::make('pse_barcode')
                            ->label('Project Subject Event Barcode')
                            ->helperText('Scan the PSE barcode')
                            ->statePath('pse_barcode')
                            ->visible(fn($get) => $get('selection_route') === 'pse_barcode')
                            ->extraAttributes([
                                'class' => 'w-full md:w-80',
                                'x-on:keydown.enter.prevent' => '$wire.loadSubjectEventSpecimens()',
                            ]),
                    ]
                );
        } elseif ($this->stage === 1) {
            $sections = [];
            foreach ($this->potentialParentSpecimens as $group => $potentialParentSpecimens) {
                $sections[] = Section::make($group)
                    ->schema(function () use ($potentialParentSpecimens) {
                        $potentialParents = [];
                        foreach ($potentialParentSpecimens as $potentialParentSpecimen) {
                            $barcode = $potentialParentSpecimen['barcode'];
                            $potentialParents[] = TextEntry::make('barcode_' . $barcode)
                                ->getStateUsing(fn() => $barcode)
                                ->hiddenLabel()
                                ->grow(false)
                                ->action(Action::make('select_parent_' . $barcode)->action(fn() => $this->selectParentSpecimen($barcode)));
                        }

                        return [Flex::make($potentialParents)];
                    })
                    ->compact()
                    ->collapsible();
            }

            return $form
                ->components(
                    [...$sections]
                );
        } else {
            // Stage 2 - Specimen Barcodes and Volumes
            $sections = [];
            $groupedTypes = $this->specimenTypes->sortBy('specimenGroup')->groupBy('specimenGroup');
            foreach ($groupedTypes as $group => $types) {
                $specimenTypes = [];
                foreach ($types as $type) {
                    $aliquotFields = [];
                    for ($i = 0; $i < count($this->specimens[$type->id]); $i++) {
                        $aliquotFields[] = Grid::make()
                            ->schema([
                                TextInput::make("specimens.{$type->id}.{$i}.barcode")
                                    ->label('Aliquot ' . ($i + 1))
                                    ->regex('/' . $type->Labware->barcodeFormat . '/')
                                    ->disabled(isset($this->specimens[$type->id][$i]['barcode']))
                                    ->extraAttributes(['style' => 'height: 30px']),
                                TextInput::make("specimens.{$type->id}.{$i}.volume")
                                    ->hiddenLabel()
                                    ->numeric()
                                    ->inputMode('decimal')
                                    ->requiredWith("specimens.{$type->id}.{$i}.barcode")
                                    ->minValue(0)
                                    ->default($type->defaultVolume)
                                    ->suffix($type->volumeUnit)
                                    ->disabled(isset($this->specimens[$type->id][$i]['barcode']))
                                    ->extraAttributes(['style' => 'height: 30px']),
                            ])
                            ->columns(1)
                            ->extraAttributes(['class' => 'py-0 mb-2']);
                    }
                    $specimenTypes[] = Flex::make([
                        Grid::make(1)
                            ->schema([
                                Action::make('addAliquot_' . $type->id)
                                    ->hiddenLabel()
                                    ->action(fn() => $this->addAliquot($type->id))
                                    ->color('success')
                                    ->icon(Heroicon::Plus)
                                    ->outlined()
                                    ->extraAttributes(['id' => 'addAliquot_' . $type->id]),
                                Action::make('removeAliquot_' . $type->id)
                                    ->hiddenLabel()
                                    ->action(fn() => $this->removeAliquot($type->id))
                                    ->color('danger')
                                    ->icon(Heroicon::Minus)
                                    ->requiresConfirmation(fn(): bool => $this->logged($type->id))
                                    ->modalHeading(fn() => $this->logged($type->id) ? 'Delete ' . $type->name . ' Aliquot ' . ($i) : null)
                                    ->modalDescription(fn() => $this->logged($type->id) ? 'The aliquot with barcode ' . ($this->specimens[$type->id][count($this->specimens[$type->id]) - 1]['barcode'] ?? '') . ' will be deleted. Are you sure you want to do this?' : null)
                                    ->outlined()
                                    ->extraAttributes(['id' => 'removeAliquot_' . $type->id])
                                    ->modalSubmitAction(fn(Action $action): \Filament\Actions\Action => $action->label('Delete')),
                            ])
                            ->grow(false),
                        Fieldset::make($type->name)
                            ->schema($aliquotFields)
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 3,
                                'lg' => 4,
                                'xl' => 5,
                                '2xl' => 6,
                                '3xl' => 8,
                            ])
                            ->grow(true)
                            ->extraAttributes(['class' => 'py-0']),
                    ])
                        ->extraAttributes(['class' => 'items-center']);
                }
                $sections[] = Section::make($group)
                    ->schema($specimenTypes)
                    ->compact()
                    ->collapsible()
                    ->columns(1)
                    ->extraAttributes(['class' => 'py-0']);
            }

            return $form
                ->components(
                    [...$sections,]
                );
        }
    }

    public function selectParentSpecimen(string $barcode): void
    {
        $this->parent_barcode = $barcode;
        $this->loadSpecimenBarcodes();
    }
    public function loadSpecimenBarcodes(): void
    {
        $this->validate([
            'parent_barcode' => [
                'required',
                Rule::exists('specimens', 'barcode')->where('project_id', session('currentProject')->id),
                // 'exists:specimens,barcode',
            ],
        ]);

        $this->parent_specimen = Specimen::with('subjectEvent', 'subjectEvent.subject')
            ->where('barcode', $this->parent_barcode)->first();

        $this->subjectEvent = $this->parent_specimen->subjectEvent;
        $this->subject = $this->parent_specimen->subjectEvent->subject;

        $loggedSpecimenTypes = Specimen::where('parentSpecimen_id', $this->parent_specimen->id)
            ->get()
            ->groupBy('specimenType_id');
        $specimens = [];

        if ($loggedSpecimenTypes->count() !== 0) {
            foreach ($loggedSpecimenTypes as $specimenType_id => $loggedSpecimens) {
                foreach ($loggedSpecimens as $aliquot => $specimen) {
                    $specimens[$specimenType_id][$aliquot] = [
                        'barcode' => $specimen->barcode,
                        'volume' => $specimen->volume,
                        'logged' => true,
                    ];
                }
            }
        }

        foreach ($this->specimenTypes as $type) {
            if (count($specimens[$type->id] ?? []) > 0) {
                continue;
            }
            for ($i = 0; $i < (int) ($type->aliquots); $i++) {
                $specimens[$type->id][$i]['volume'] = $type->defaultVolume;
            }
        }
        $this->specimens = $specimens;

        $this->stage = 2;

        $this->dispatch('updateform');
    }

    public function loadSubjectEventSpecimens(): void
    {
        $this->validate([
            'pse_barcode' => [
                'required',
                new \App\Rules\ValidPSE,
            ],
        ]);
        [$project_id, $subject_id, $subject_event_id] = explode('_', (string) $this->pse_barcode);
        $this->subjectEvent = SubjectEvent::find($subject_event_id);
        $this->subject = Subject::find($subject_id);

        if (! $this->subjectEvent) {
            return;
        }

        $parentalSpecimenTypes = Specimentype::where('project_id', session('currentProject')->id)
            ->whereNotNull('parentSpecimenType_id')
            ->pluck('parentSpecimenType_id')
            ->unique();

        $this->potentialParentSpecimens = Specimen::with('specimenType')
            ->where('subject_event_id', $this->subjectEvent->id)
            ->whereIn('specimenType_id', $parentalSpecimenTypes)
            ->get()
            ->groupBy('specimenType.name')
            ->map(fn($group) => $group->values()->toArray());

        $this->stage = 1;

        $this->dispatch('updateform');
    }

    private function logged($specimenType_id): bool
    {
        return $this->specimens[$specimenType_id][count($this->specimens[$specimenType_id]) - 1]['logged'] ?? false;
    }

    private function addAliquot(int $typeId): void
    {
        array_push($this->specimens[$typeId], ['volume' => $this->specimenTypes->find($typeId)->defaultVolume]);
        $this->dispatch('updateform');
    }

    private function removeAliquot(int $typeId): void
    {
        array_pop($this->specimens[$typeId]);
        $this->dispatch('updateform');
    }

    public function submit(): void
    {
        // if ($this->stage === 0 || ! $this->subjectEvent) {
        //     Notification::make()
        //         ->title('Error')
        //         ->body('Please scan a valid PSE barcode first.')
        //         ->color('danger')
        //         ->send();

        //     return;
        // }

        $loggedCount = 0;
        try {
            DB::beginTransaction();

            foreach ($this->specimens ?? [] as $specimenType_id => $specimens) {
                $specimenType = $this->specimenTypes->find($specimenType_id);
                if (! $specimenType) {
                    throw new \Exception("Specimen Type ID {$specimenType_id} not found");
                }
                foreach ($specimens as $aliquot => $specimenData) {
                    if (isset($specimenData['barcode']) && ! empty($specimenData['barcode']) && ! isset($specimenData['logged'])) {
                        Specimen::create([
                            'barcode' => $specimenData['barcode'],
                            'volume' => $specimenData['volume'],
                            'volumeUnit' => $specimenType->volumeUnit,
                            'aliquot' => $aliquot + 1,
                            'specimenType_id' => $specimenType_id,
                            'subject_event_id' => $this->subjectEvent->id,
                            'site_id' => $this->userSiteId,
                            'project_id' => session('currentProject')->id,
                            'status' => SpecimenStatus::Logged,
                            'loggedBy_id' => $this->user->id,
                            'loggedAt' => now(),
                            'parentSpecimen_id' => $this->parent_specimen->id,
                        ]);
                        $loggedCount++;
                    }
                }
            }

            DB::commit();

            Notification::make()
                ->title('Specimens Logged')
                ->body($loggedCount . ' derivative specimens logged successfully.')
                ->color(fn(): string => $loggedCount > 0 ? 'success' : 'warning')
                ->send();

            // Reset form for new entry
            $this->reset(['parent_barcode', 'specimens', 'subjectEvent', 'subject', 'stage', 'parent_specimen']);
            $this->initializeSpecimenTypes();

            $this->dispatch('updateform');
        } catch (\Throwable $th) {
            DB::rollBack();

            Notification::make()
                ->title('Failed')
                ->body('Failed to log derivative specimens. ' . $th->getMessage())
                ->color('danger')
                ->send();
        }
    }

    public function resetForm(): void
    {
        $this->reset(['parent_barcode', 'specimens', 'subjectEvent', 'subject', 'stage']);
        $this->initializeSpecimenTypes();
        $this->dispatch('updateform');
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if ($this->stage !== 0) {
            $actions[] = Action::make('reset')
                ->label('Start Over')
                ->color('danger')
                ->action('resetForm');
        }

        if ($this->stage === 2) {
            $actions[] = Action::make('submit')
                ->label('Save Specimens')
                ->color('primary')
                ->action('submit');
        }

        return $actions;
    }
}
