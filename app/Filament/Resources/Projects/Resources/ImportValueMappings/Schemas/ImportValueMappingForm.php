<?php

namespace App\Filament\Resources\Projects\Resources\ImportValueMappings\Schemas;

use App\Enums\EventStatus;
use App\Enums\SpecimenStatus;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
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
        $sections = [];
        foreach ($models as $model => $fields) {
            $sections[] = Section::make($model)
                ->schema(
                    function () use ($fields, $projectId) {
                        $fieldsets = [];
                        foreach ($fields as $fieldname => $relation) {
                            switch (TRUE) {
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
                            $inputs = [];
                            foreach ($options as $sitekey => $option) {
                                $inputs[] = TextInput::make($option);
                            }
                            $fieldsets[$fieldname] =
                                Fieldset::make(Str::of($fieldname)->plural()->title())
                                ->schema($inputs)
                                ->columns([
                                    'default' => 1,
                                    'sm' => 3,
                                    'lg' => 4,
                                    'xl' => 6,
                                    '2xl' => 8,
                                ]);
                        }
                        return $fieldsets;
                    }
                )
                ->columns(1);
        }
        return $schema
            ->components($sections)
            ->columns(1);
    }
}
