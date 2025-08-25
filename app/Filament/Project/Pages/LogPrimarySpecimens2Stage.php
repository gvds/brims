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
use Filament\Forms\Components\Placeholder;
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

use function PHPUnit\Framework\isNull;

class LogPrimarySpecimens2Stage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = "Log Primary Specimens (2-Stage)";

    protected static ?string $title = "Log Primary Specimens";

    protected static ?int $navigationSort = 101;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected string $view = "filament.project.pages.log-primary-specimens-two-stage";

    // Properties
    public Collection $specimenTypes;
    public Collection $groups;
    public array $aliquotCounts = [];
    public ?array $specimens = null;
    public ?User $user = null;
    public ?int $userSiteId = null;
    public ?string $pse_barcode = '5_37_73';
    // public ?string $pse_barcode = null;
    public ?SubjectEvent $subjectEvent = null;
    public ?Subject $subject = null;
    // public bool $stageOneCompleted = true;
    public bool $stageOneCompleted = false;

    protected $listeners = ['updateform' => '$refresh'];

    public function mount(): void
    {
        $this->initializeUser();
        $this->initializeSpecimenTypes();

        // $this->subject = Subject::find(37);
        // $this->subjectEvent = SubjectEvent::find(73);
    }

    private function initializeUser(): void
    {
        $this->user = session("currentProject")->members()->where("user_id", \Illuminate\Support\Facades\Auth::id())->first();
        if (!$this->user) {
            Notification::make()
                ->title("Error")
                ->body("You are not a member of this project.")
                ->color("danger")
                ->send();
            $this->redirect(route("filament.app.resources.projects.index"));
        }

        // Store the site_id separately since pivot data gets lost during Livewire serialization
        $this->userSiteId = $this->user->pivot->site_id;
    }

    private function initializeSpecimenTypes(): void
    {
        $this->specimenTypes = Specimentype::query()
            ->where("project_id", session("currentProject")->id)
            ->where("primary", true)
            ->get();

        // Initialize aliquotCounts for each type
        foreach ($this->specimenTypes as $type) {
            $this->aliquotCounts[$type->id] = (int) ($type->aliquots ?? 1);
        }

        $this->groups = $this->specimenTypes->pluck("specimenGroup")
            ->unique()
            ->sortBy("name");
    }

    protected function getFormSchema(): array
    {
        if (!$this->stageOneCompleted) {
            // Stage 1 - PSE Barcode scanning
            return [
                TextInput::make("pse_barcode")
                    ->label("Project Subject Event Barcode")
                    ->helperText("Scan the barcode")
                    ->required()
                    ->regex("/^" . session("currentProject")->id . "_\d+_\d+$/")
                    ->statePath("pse_barcode")
                    ->autofocus()
                    ->extraAttributes(["class" => "w-full md:w-80"])
            ];
        } else {
            // Stage 2 - Specimen Barcodes and Volumes
            $sections = [];
            $groupedTypes = $this->specimenTypes->groupBy("specimenGroup");
            foreach ($groupedTypes as $group => $types) {
                $specimenTypes = [];
                foreach ($types as $type) {
                    $aliquots = $this->aliquotCounts[$type->id];
                    $aliquotFields = [];
                    for ($i = 0; $i < $aliquots; $i++) {
                        $aliquotFields[] = Grid::make()
                            ->schema([
                                TextInput::make("specimens.{$type->id}.{$i}.barcode")
                                    ->label("Aliquot " . ($i + 1))
                                    ->regex("/" . $type->Labware->barcodeFormat . "/")
                                    ->disabled(isset($this->specimens[$type->id][$i]["barcode"]))
                                    ->extraAttributes(["style" => "height: 30px"]),
                                TextInput::make("specimens.{$type->id}.{$i}.volume")
                                    ->hiddenLabel()
                                    ->numeric()
                                    ->inputMode("decimal")
                                    ->requiredWith("specimens.{$type->id}.{$i}.barcode")
                                    ->minValue(0)
                                    ->default($type->defaultVolume)
                                    ->suffix($type->volumeUnit)
                                    ->disabled(isset($this->specimens[$type->id][$i]["barcode"]))
                                    ->extraAttributes(["style" => "height: 30px"]),
                            ])
                            ->columns(1)
                            ->extraAttributes(["class" => "py-0 mb-2"]);
                    }
                    $specimenTypes[] = Flex::make([
                        Grid::make(1)
                            ->schema([
                                Action::make("addAliquot_" . $type->id)
                                    ->hiddenLabel()
                                    ->action(fn() => $this->addAliquot($type->id))
                                    ->color("primary")
                                    ->icon(Heroicon::Plus)
                                    ->outlined(),
                                Action::make("removeAliquot_" . $type->id)
                                    ->hiddenLabel()
                                    ->action(fn() => $this->removeAliquot($type->id))
                                    ->color("primary")
                                    ->icon(Heroicon::Minus)
                                    ->outlined(),
                            ])
                            ->grow(false),
                        Fieldset::make($type->name)
                            ->schema($aliquotFields)
                            ->columns([
                                "default" => 1,
                                "sm" => 2,
                                "md" => 3,
                                "lg" => 4,
                                "xl" => 5,
                                "2xl" => 6,
                                "3xl" => 8,
                            ])
                            ->grow(true)
                            ->extraAttributes(["class" => "py-0"]),
                    ])
                        ->extraAttributes(["class" => "items-center"]);
                }
                $sections[] = Section::make($group)
                    ->schema($specimenTypes)
                    ->compact()
                    ->collapsible()
                    ->columns(1)
                    ->extraAttributes(["class" => "py-0"]);
            }

            return $sections;
        }
    }

    public function addAliquot(int $typeId): void
    {
        $this->aliquotCounts[$typeId] = ($this->aliquotCounts[$typeId] ?? 0) + 1;
        $this->dispatch("updateform");
    }

    public function removeAliquot(int $typeId): void
    {
        $this->aliquotCounts[$typeId] = max(($this->aliquotCounts[$typeId] ?? 0) - 1, 0);
        $this->dispatch("updateform");
    }

    // Validate PSE barcode and move to stage 2
    public function validatePseBarcode(): void
    {
        $this->validate([
            "pse_barcode" => ["required", "regex:/^" . session("currentProject")->id . "_\d+_\d+$/"],
        ]);

        [$project_id, $subject_id, $subject_event_id] = explode("_", $this->pse_barcode);
        $this->subjectEvent = SubjectEvent::find($subject_event_id);
        $this->subject = Subject::find($subject_id);

        if (!$this->subjectEvent || !$this->subject) {
            Notification::make()
                ->title("Validation Error")
                ->body("The Project-Subject-Event Barcode is invalid.")
                ->color("danger")
                ->send()
                ->persistent();
            return;
        }

        if ($this->subjectEvent->subject_id != $subject_id) {
            Notification::make()
                ->title("Validation Error")
                ->body("The Subject ID in the barcode does not match the Subject Event record.")
                ->color("danger")
                ->send()
                ->persistent();
            return;
        }

        $loggedSpecimenTypes = Specimen::where("subject_event_id", $this->subjectEvent->id)->whereRelation("specimentype", "primary", true)->get()->groupBy("specimenType_id");
        // dd($loggedSpecimenTypes);
        $specimens = [];

        if ($loggedSpecimenTypes->count() !== 0) {
            foreach ($loggedSpecimenTypes as $specimenType_id => $loggedSpecimens) {
                foreach ($loggedSpecimens as $aliquot => $specimen) {
                    $specimens[$specimenType_id][$aliquot] = [
                        "barcode" => $specimen->barcode,
                        "volume" => $specimen->volume,
                        'logged' => true,
                    ];
                }
            }
        }

        foreach ($this->specimenTypes as $type) {
            $aliquots = (int) ($type->aliquots ?? 1);
            for ($i = 0; $i < $aliquots; $i++) {
                if (!isset($specimens[$type->id][$i]["barcode"])) {
                    $specimens[$type->id][$i]["volume"] = $type->defaultVolume;
                }
            }
        }
        $this->specimens = $specimens;

        $this->stageOneCompleted = true;

        $this->dispatch("updateform");

        Notification::make()
            ->title("Subject Event Found")
            ->body("Ready to log specimens for Subject {$this->subject->subjectID}, Event {$this->subjectEvent->event->name}")
            ->color("success")
            ->send();
    }

    // Submit the specimens for the validated PSE
    public function submit(): void
    {
        if (!$this->stageOneCompleted || !$this->subjectEvent) {
            Notification::make()
                ->title("Error")
                ->body("Please scan a valid PSE barcode first.")
                ->color("danger")
                ->send();
            return;
        }

        $loggedCount = 0;
        try {
            DB::beginTransaction();

            foreach ($this->specimens ?? [] as $specimenType_id => $specimens) {
                $specimenType = $this->specimenTypes->find($specimenType_id);
                if (!$specimenType) throw new \Exception("Specimen Type ID {$specimenType_id} not found");

                foreach ($specimens as $aliquot => $specimenData) {
                    if (isset($specimenData["barcode"]) && !empty($specimenData["barcode"]) && !isset($specimenData['logged'])) {
                        Specimen::create([
                            "barcode" => $specimenData["barcode"],
                            "volume" => $specimenData["volume"],
                            "volumeUnit" => $specimenType->volumeUnit,
                            "aliquot" => $aliquot,
                            "specimenType_id" => $specimenType_id,
                            "subject_event_id" => $this->subjectEvent->id,
                            "site_id" => $this->userSiteId,
                            "status" => SpecimenStatus::Logged,
                            "loggedBy_id" => $this->user->id,
                            "loggedAt" => now(),
                        ]);
                        $loggedCount++;
                    }
                }
            }

            DB::commit();

            Notification::make()
                ->title("Specimens Logged")
                ->body($loggedCount . " primary specimens logged successfully.")
                ->color(fn() => $loggedCount > 0 ? "success" : "warning")
                ->send();

            // Reset form for new entry
            $this->reset(["pse_barcode", "specimens", "subjectEvent", "subject", "stageOneCompleted"]);
            $this->initializeSpecimenTypes();

            $this->dispatch("updateform");
        } catch (\Throwable $th) {
            DB::rollBack();

            Notification::make()
                ->title("Failed")
                ->body("Failed to log primary specimens. " . $th->getMessage())
                ->color("danger")
                ->send();
        }
    }

    // Reset form and start over
    public function resetForm(): void
    {
        $this->reset(["pse_barcode", "specimens", "subjectEvent", "subject", "stageOneCompleted"]);
        $this->initializeSpecimenTypes();

        Notification::make()
            ->title("Form Reset")
            ->body("You can start over with a new PSE barcode.")
            ->color("info")
            ->send();
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if ($this->stageOneCompleted) {
            $actions[] = Action::make("reset")
                ->label("Start Over")
                ->color("danger")
                ->action("resetForm");

            $actions[] = Action::make("submit")
                ->label("Save Specimens")
                ->color("primary")
                ->action("submit");
        } else {
            $actions[] = Action::make("proceed")
                ->label("Validate Barcode")
                ->color("success")
                ->action("validatePseBarcode");
        }

        return $actions;
    }
}
