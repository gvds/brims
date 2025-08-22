<?php

namespace App\Filament\Project\Pages;

use App\Enums\SpecimenStatus;
use App\Filament\Forms\Components\SpecimenBarcode;
use App\Models\Specimen;
use App\Models\SpecimenType;
use App\Models\SubjectEvent;
use App\Models\User;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Enums\Size;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LogPrimarySpecimens extends Page implements HasForms
{
    use InteractsWithForms;

    protected $listeners = ['aliquotChanged' => '$refresh'];
    public function addAliquot(int $typeId): void
    {
        $this->aliquotCounts[$typeId] = ($this->aliquotCounts[$typeId] ?? 0) + 1;
        $this->dispatch('aliquotChanged');
    }

    public function removeAliquot(int $typeId): void
    {
        $this->aliquotCounts[$typeId] = max(($this->aliquotCounts[$typeId] ?? 0) - 1, 0);
        $this->dispatch('aliquotChanged');
    }

    protected string $view = 'filament.project.pages.log-primary-specimens';

    public Collection $primaryTypes;
    public Collection $groups;
    public array $aliquotCounts = [];
    public ?array $specimens = null;
    private ?User $user = null;
    public ?string $pse_barcode = null;

    public function mount(): void
    {
        $this->primaryTypes = SpecimenType::query()
            ->where('project_id', session('currentProject')->id)
            ->where('primary', true)
            ->get();

        // Initialize aliquotCounts for each type
        foreach ($this->primaryTypes as $type) {
            $this->aliquotCounts[$type->id] = (int) ($type->aliquots ?? 1);
        }

        $this->groups = $this->primaryTypes->pluck('specimenGroup')
            ->unique()
            ->sortBy('name');

        if (is_null($this->specimens)) {
            $specimens = [];
            foreach ($this->primaryTypes as $type) {
                $aliquots = (int) ($type->aliquots ?? 1);
                for ($i = 0; $i < $aliquots; $i++) {
                    $specimens[$type->id][$i]['volume'] = $type->defaultVolume;
                }
            }
            $this->specimens = $specimens;
        }
    }

    protected function getFormSchema(): array
    {
        $sections = [];
        $groupedTypes = $this->primaryTypes->groupBy('specimenGroup');

        foreach ($groupedTypes as $group => $types) {
            $specimenTypes = [];
            foreach ($types as $type) {
                $aliquots = $this->aliquotCounts[$type->id] ?? (int) ($type->aliquots ?? 1);
                $aliquotFields = [];
                for ($i = 0; $i < $aliquots; $i++) {
                    $aliquotFields[] = Grid::make()
                        ->schema([
                            // SpecimenBarcode::make("specimens.{$type->id}.{$i}.barcode")
                            //     ->label("Aliquot " . ($i + 1))
                            //     ->regex('/' . $type->Labware->barcodeFormat . '/'),
                            TextInput::make("specimens.{$type->id}.{$i}.barcode")
                                ->label("Aliquot " . ($i + 1))
                                ->regex('/' . $type->Labware->barcodeFormat . '/')
                                ->extraAttributes(['style' => 'height: 30px']),
                            TextInput::make("specimens.{$type->id}.{$i}.volume")
                                ->hiddenLabel()
                                ->numeric()
                                ->inputMode('decimal')
                                ->requiredWith("specimens.{$type->id}.{$i}.barcode")
                                ->minValue(0)
                                ->default($type->defaultVolume)
                                ->suffix($type->volumeUnit)
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
                                ->color('primary')
                                ->icon('heroicon-m-plus')
                                ->outlined(),
                            Action::make('removeAliquot_' . $type->id)
                                ->hiddenLabel()
                                ->action(fn() => $this->removeAliquot($type->id))
                                ->color('primary')
                                ->icon('heroicon-m-minus')
                                ->outlined(),
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

        return [Wizard::make([
            Step::make('PSE')
                ->schema([
                    TextInput::make('pse_barcode')
                        ->label('Project Subject Event Barcode')
                        ->required()
                        ->regex('/^' . session('currentProject')->id . '_\d+_\d+$/')
                        ->statePath('pse_barcode')
                        ->extraAttributes(['class' => 'w-80']),
                ]),
            Step::make('Specimen Information')
                ->schema($sections)
        ])];
    }
    public function submit(): void
    {
        $data = $this->form->getState();
        [$project_id, $subject_id, $subject_event_id] = explode('_', $data['pse_barcode'] ?? null);
        $subjectEvent = SubjectEvent::find($subject_event_id);

        if ($subjectEvent->id !== $subject_id) {
            Notification::make()
                ->title('Validation Error')
                ->body('The Project-Subject-Event Barcode ' . $data['pse_barcode'] . ' is invalid.')
                ->color('danger')
                ->send()
                ->persistent();
            // $this->redirect(route('filament.project.pages.log-primary-specimens'));
            return;
        }
        $this->user = session('currentProject')->members()->where('user_id', auth()->id())->first();
        $this->specimens = $data;
        $loggedCount = 0;
        try {
            DB::beginTransaction();

            foreach ($data['specimens'] ?? [] as $specimenType_id => $specimens) {
                $specimenType = SpecimenType::find($specimenType_id);
                foreach ($specimens as $aliquot => $specimenData) {
                    if (isset($specimenData['barcode'])) {
                        Specimen::create([
                            'barcode' => $specimenData['barcode'],
                            'volume' => $specimenData['volume'],
                            'volumeUnit' => $specimenType->volumeUnit,
                            'aliquot' => $aliquot,
                            'subject_event_id' => $subjectEvent->id,
                            'site_id' => $this->user->pivot->site_id,
                            'status' => SpecimenStatus::Logged,
                            'logged_by' => $this->user->id,
                            'logged_at' => now(),
                        ]);
                        $loggedCount++;
                    }
                }
            }

            DB::commit();

            Notification::make()
                ->title('Specimens Logged')
                ->body($loggedCount . ' primary specimens logged successfully.')
                ->color(fn() => $loggedCount > 0 ? 'success' : 'warning')
                ->send();
        } catch (\Throwable $th) {

            DB::rollBack();

            Notification::make()
                ->title('Failed')
                ->body('Failed to log primary specimens. ' . $th->getMessage())
                ->color('danger')
                ->send();
        }
        $this->redirect(route('filament.project.pages.log-primary-specimens'));
    }

    protected function getActions(): array
    {
        return [
            Action::make('submit')
                ->label('Save')
                ->action('submit'),
        ];
    }
}
