<?php

namespace App\Filament\Resources\Projects\Resources\ImportValueMappings\Schemas;

use App\Models\Arm;
use App\Models\Site;
use Dom\Text;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

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
                // 'status' => '',
            ],
            // 'Specimen' => 'Specimen',
        ];
        $sections = [];
        foreach ($models as $model => $fields) {
            $sections[] = Section::make($model)
                ->schema(
                    function () use ($fields, $projectId) {
                        $fieldsets = [];
                        foreach ($fields as $fieldname => $relation) {
                            if ($relation === '') {
                                // $options = Site::where('project_id', $projectId)->pluck('name');
                                $options = DB::table($fieldname . 's')
                                    ->where('project_id', $projectId)
                                    ->pluck('name');
                            } else {
                                $options = DB::table($fieldname . 's')
                                    ->join($relation . 's', $relation . '_id', '=', $relation . 's.id')
                                    ->where($relation . 's.project_id', $projectId)
                                    ->pluck($fieldname . 's.name');
                            }
                            //
                            // $options = Site::where('project_id', $projectId)->pluck('name');
                            $inputs = [];
                            foreach ($options as $sitekey => $option) {
                                $inputs[] = TextInput::make($option);
                            }
                            $fieldsets[$fieldname] =
                                Fieldset::make($fieldname)
                                ->schema($inputs)
                                ->columns(5);
                        }
                        return $fieldsets;
                    }
                )
                ->columns(1);
        }
        return $schema
            ->components($sections);
    }
}
