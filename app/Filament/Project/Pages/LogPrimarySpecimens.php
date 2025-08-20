<?php

namespace App\Filament\Project\Pages;

use App\Filament\Forms\Components\SpecimenBarcode;
use App\Models\SpecimenType;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Collection;

class LogPrimarySpecimens extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.project.pages.log-primary-specimens';

    public Collection $primaryTypes;
    public Collection $groups;
    public ?array $specimens = null;

    public function mount(): void
    {
        $this->primaryTypes = SpecimenType::query()
            ->where('project_id', session('currentProject')->id)
            ->where('primary', true)
            ->get();
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
            $fields = [];
            foreach ($types as $type) {
                $aliquots = (int) ($type->aliquots ?? 1);
                $aliquotFields = [];
                for ($i = 0; $i < $aliquots; $i++) {
                    $aliquotFields[] = \Filament\Schemas\Components\Grid::make()
                        ->schema([
                            SpecimenBarcode::make("specimens.{$type->id}.{$i}.barcode")
                                ->label("Aliquot " . ($i + 1)),
                            TextInput::make("specimens.{$type->id}.{$i}.volume")
                                ->hiddenLabel()
                                ->numeric()
                                ->inputMode('decimal')
                                ->default($type->defaultVolume)
                                ->suffix($type->volumeUnit)
                                ->extraAttributes(['style' => 'height: 30px']),
                        ])
                        ->columns(1)
                        ->extraAttributes(['class' => 'gap-y-1 py-0 mb-2']);
                }
                $fields[] = \Filament\Schemas\Components\Fieldset::make($type->name)
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
                    ->extraAttributes(['class' => 'gap-y-0 py-0 my-0']);
            }
            $sections[] = Section::make($group)
                ->schema($fields)
                ->compact()
                ->collapsible()
                ->columns(1)
                ->extraAttributes(['class' => 'gap-y-1 py-0']);
        }

        return $sections;
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        foreach ($data['specimens'] ?? [] as $typeId => $specimens) {
            foreach ($specimens as $specimenData) {
                // Save each specimen instance, relate to project as needed
            }
        }

        Notification::make()
            ->title('Success')
            ->body('Primary specimens logged successfully.')
            ->color('success')
            ->send();

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
