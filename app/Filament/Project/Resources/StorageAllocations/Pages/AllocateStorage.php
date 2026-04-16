<?php

namespace App\Filament\Project\Resources\StorageAllocations\Pages;

use App\Enums\SpecimenStatus;
use App\Enums\StorageDestinations;
use App\Filament\Project\Resources\StorageAllocations\StorageAllocationResource;
use App\Models\Location;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\StorageAllocation;
use App\Models\VirtualUnit;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllocateStorage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StorageAllocationResource::class;

    protected static ?string $title = 'Allocate Specimen Storage';

    protected string $view = 'filament.project.resources.storage-allocations.pages.allocate-storage';

    public ?array $data = [];

    public $project;

    public Collection $specimenTypes;

    public $locationsCounts;

    public ?int $userSiteId = null;

    public function mount(): void
    {
        $this->project = session('currentProject');

        $this->userSiteId = $this->project->members()
            ->where('user_id', Auth::id())
            ->first()
            ->pivot
            ->site_id;

        $this->specimenTypes = Specimentype::query()
            ->where('project_id', $this->project->id)
            ->whereNotNull('storageSpecimenType')
            ->withCount([
                'specimens' => fn($query) => $query
                    ->where('status', SpecimenStatus::Logged)
                    ->where('site_id', $this->userSiteId),
            ])
            ->having('specimens_count', '>', 0)
            ->get();

        $this->locationsCounts = VirtualUnit::query()
            ->where('project_id', $this->project->id)
            ->where('active', true)
            ->withCount('freeLocations')
            ->get()
            ->groupBy('storageSpecimenType')
            ->map(fn(Collection $units): int => $units->sum('free_locations_count'));

        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        $insufficientStorage = $this->specimenTypes->mapWithKeys(function (Specimentype $type): array {
            $available = $this->locationsCounts->get($type->storageSpecimenType, 0);

            return [$type->id => $type->specimens_count > $available];
        });

        return $form
            ->schema([
                Toggle::make('reuse_locations')
                    ->label('Allow allocation to previously used locations')
                    ->required()
                    ->onColor('success')
                    ->offColor('danger'),
                CheckboxList::make('specimenTypeIds')
                    ->label('Select specimen types to allocate to storage')
                    ->options($this->specimenTypes->pluck('name', 'id')->all())
                    ->descriptions(
                        $this->specimenTypes->mapWithKeys(function (Specimentype $type) use ($insufficientStorage): array {
                            $description = "{$type->specimens_count} " . str('specimen')->plural($type->specimens_count);

                            if ($insufficientStorage->get($type->id)) {
                                $available = $this->locationsCounts->get($type->storageSpecimenType, 0);
                                $description .= " — Insufficient storage: {$available} " . str('location')->plural($available) . ' available';
                            }

                            return [$type->id => $description];
                        })->all()
                    )
                    ->disableOptionWhen(fn(string $value): bool => $insufficientStorage->get((int) $value, false))
                    ->required(),
            ])
            ->statePath('data');
    }

    public function allocate(): void
    {
        $data = $this->form->getState();

        $reuse_locations = $data['reuse_locations'];

        $selectedTypeIds = $data['specimenTypeIds'];

        $specimenTypes = Specimentype::query()
            ->whereIn('id', $selectedTypeIds)
            ->get();

        try {
            DB::beginTransaction();

            $storageAllocation = StorageAllocation::create([
                'project_id' => $this->project->id,
                'user_id' => Auth::id(),
                'storageDestination' => StorageDestinations::Internal->value,
            ]);

            $totalAllocated = 0;

            foreach ($specimenTypes as $specimenType) {

                $specimens = Specimen::query()
                    ->where('specimenType_id', $specimenType->id)
                    ->where('site_id', $this->userSiteId)
                    ->where('status', SpecimenStatus::Logged)
                    ->get();

                $locations = Location::query()
                    ->whereRelation('virtualUnit', 'project_id', $this->project->id)
                    ->whereRelation('virtualUnit', 'storageSpecimenType', $specimenType->storageSpecimenType)
                    ->when(! $reuse_locations, function ($query) {
                        $query->where('used', false);
                    })
                    ->limit($specimens->count())
                    ->lockForUpdate()
                    ->get();

                if ($locations->count() < $specimens->count()) {
                    throw new \Exception("Insufficient storage space for specimen type: {$specimenType->name}. {$specimens->count()} locations required, only {$locations->count()} available.");
                }

                foreach ($specimens as $index => $specimen) {
                    $location = $locations[$index];

                    $location->update([
                        'used' => true,
                        'specimen_id' => $specimen->id,
                        'barcode' => $specimen->barcode,
                    ]);

                    $specimen->logStored();

                    $storageAllocation->storageLogs()->create([
                        'specimentype_id' => $specimenType->id,
                        'specimen_id' => $specimen->id,
                        'location_id' => $location->id,
                    ]);

                    $totalAllocated++;
                }
            }

            DB::commit();

            Notification::make()
                ->title("Successfully allocated {$totalAllocated} specimens to storage.")
                ->success()
                ->send();
        } catch (\Throwable $th) {
            DB::rollBack();
            Notification::make()
                ->title('Failed to allocate specimens to storage.')
                ->body($th->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }

        $this->redirect(static::getResource()::getUrl('index'));
    }
}
