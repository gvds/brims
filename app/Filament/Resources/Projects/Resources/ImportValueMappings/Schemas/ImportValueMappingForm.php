<?php

namespace App\Filament\Resources\Projects\Resources\ImportValueMappings\Schemas;

use App\Enums\EventStatus;
use App\Enums\SpecimenStatus;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
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

        $elements = [
            Select::make('model')
                ->options(array_combine(array_keys($models), array_keys($models)))
                ->default('Subject')
                ->live()
                ->required()
                ->extraAttributes(['class' => 'max-w-200'])
        ];

        $elements[] = Fieldset::make('Value Mappings')
            ->schema(function (Get $get) use ($models, $projectId) {
                $model = $get('model');
                if (!$model || !isset($models[$model])) {
                    return [];
                }

                $keyvalues = [];
                foreach ($models[$model] as $fieldname => $relation) {
                    switch (true) {
                        case strstr($relation, 'App\Enums'):
                            $options = [];
                            foreach ($relation::cases() as $case) {
                                $options[] = $case->name;
                            }
                            break;
                        case $relation === '':
                            $options = DB::table($fieldname . 's')
                                ->where('project_id', $projectId)
                                ->pluck('name');
                            break;
                        default:
                            $options = DB::table($fieldname . 's')
                                ->join($relation . 's', $relation . '_id', '=', $relation . 's.id')
                                ->where($relation . 's.project_id', $projectId)
                                ->pluck($fieldname . 's.name');
                    }
                    $default = [];
                    foreach ($options as $option) {
                        $default[$option] = '';
                    }
                    $keyvalues[] = KeyValue::make(Str::of($fieldname)->plural()->title())
                        ->keyLabel('Database Value')
                        ->valueLabel('Import Value')
                        ->default($default)
                        ->addable(false)
                        ->deletable(false)
                        ->editableKeys(false);
                }

                return $keyvalues;
            })
            ->columns(1);

        return $schema
            ->components(
                Grid::make(1)
                    ->schema($elements)
            )
            ->columns(1)
            ->extraAttributes(['class' => 'max-w-max min-w-1/3']);
    }
}
