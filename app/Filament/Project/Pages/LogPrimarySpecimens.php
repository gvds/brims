<?php

namespace App\Filament\Project\Pages;

use App\Models\SpecimenType;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class LogPrimarySpecimens extends Page
{
    protected string $view = 'filament.project.pages.log-primary-specimens';

    public Collection $primaryTypes;
    public ?array $specimens = [];

    public function mount(): void
    {
        /** @var Collection $primaryTypes */
        $this->primaryTypes = SpecimenType::query()
            ->where('primary', true)
            ->get();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(
                [
                    Repeater::make('specimens')
                        ->label('Primary Specimens')
                        ->schema([
                            TextInput::make('barcode')
                                ->label('Barcode')
                                ->required(),
                            TextInput::make('volume')
                                ->label('Volume')
                                ->numeric(),
                        ])
                        ->addActionLabel('Add Specimen')
                        ->columns(2)
                        ->defaultItems(2),
                    // TextInput::make('barcode')
                    //     ->label('Barcode')
                    //     ->required(),
                    // TextInput::make('volume')
                    //     ->label('Volume')
                    //     ->numeric(),
                ]
            )
            ->columns(4)
            // ->schema($this->getFormSchema())
            ->statePath('specimens');
    }

    protected function getFormSchema(): array
    {
        $sections = [];

        foreach ($this->primaryTypes as $type) {
            // create a fresh fields array for each type/section
            $fields = [];

            $fields[] = Repeater::make("specimens.{$type->id}")
                ->label($type->name)
                ->schema([
                    TextInput::make('barcode')
                        ->label('Barcode')
                        ->required(),
                    TextInput::make('volume')
                        ->label('Volume')
                        ->numeric(),
                    // Add more fields as needed
                ])
                ->addActionLabel("Add {$type->name}")
                ->columns(1);

            $sections[] = Section::make($type->name)
                ->schema($fields)
                ->columns(5);
        }

        return $sections;
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        foreach ($data['specimens'] ?? [] as $typeId => $specimens) {
            foreach ($specimens as $specimenData) {
                // Save each specimen instance, relate to project as needed
                // Example: Specimen::create([...])
            }
        }

        $this->notify('success', 'Primary specimens logged successfully.');
        $this->redirect(route('filament.project.pages.log-primary-specimens'));
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Log Specimens')
                ->submit('submit'),
        ];
    }
}
