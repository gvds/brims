<?php

namespace App\Filament\project\Resources\Projects\Resources\ImportValueMappings\Schemas;

use App\Enums\EventStatus;
use App\Enums\SpecimenStatus;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportValueMappingForm
{

    public static function configure(Schema $schema): Schema
    {

        $projectId = request()->route('project');

        $models = [
            'Subject' => [
                'site' => '',
                'arm' => '',
            ],
            'SubjectEvent' => [
                'event' => 'arm',
                'status' => EventStatus::class,
            ],
            'Specimen' => [
                'specimentype' => '',
                'status' => SpecimenStatus::class,
            ],
        ];

        $modelformsets = [];

        foreach ($models as $modelname => $fields) {

            $keyvalues = [];

            foreach ($fields as $fieldname => $relation) {

                $table = Str::of($fieldname)->plural();

                $options = [];
                switch (true) {
                    case strstr($relation, 'App\Enums'):
                        foreach ($relation::cases() as $case) {
                            $options[] = $case->name;
                        }
                        break;
                    case $relation === '':
                        $options = DB::table($table)
                            ->where('project_id', $projectId)
                            ->pluck('name');
                        break;
                    default:
                        $relationtable = Str::of($relation)->plural();
                        $options = DB::table($table)
                            ->join($relationtable, $relation . '_id', '=', $relationtable . '.id')
                            ->where($relationtable . '.project_id', $projectId)
                            ->pluck($table . '.name');
                }

                $optionset = [];

                foreach ($options as $option) {
                    $optionset[$option] = '';
                }

                $keyvalues[] = KeyValue::make(Str::of($fieldname)->plural()->title())
                    ->keyLabel('Database Value')
                    ->valueLabel('Import Value')
                    ->default($optionset)
                    ->addable(false)
                    ->deletable(false)
                    ->editableKeys(false);
            }

            $modelformsets[$modelname] = $keyvalues;
        }

        return $schema
            ->components([
                Select::make('model')
                    ->options(array_combine(array_keys($models), array_keys($models)))
                    ->default('Subject')
                    ->live()
                    ->required()
                    ->extraAttributes(['class' => 'max-w-200']),
                Fieldset::make('Value Mappings')
                    ->schema(fn(Get $get): array => match ($get('model')) {
                        'Subject' => $modelformsets['Subject'],
                        'SubjectEvent' => $modelformsets['SubjectEvent'],
                        'Specimen' => $modelformsets['Specimen'],
                        default => []
                    })
                    ->columns(1),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'max-w-max min-w-1/3']);
    }
}
