<?php

namespace App\Filament\Project\Resources\Subjects\Pages;

use App\Filament\Project\Resources\Subjects\SubjectResource;
use App\Imports\MySubjecttImport;
use App\Imports\SubjectImporter;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ListSubjects extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('Import Subjects')
                ->color("gray")
                ->schema([
                    FileUpload::make('subjects_file')
                        ->required()
                        ->acceptedFileTypes(['text/csv']),
                ])
                ->action(
                    function (array $data, $livewire) {
                        $livewire->validate();
                        $uploaded_file = $data['subjects_file'];
                        // $trakcare_file_name = $uploaded_file->getClientOriginalName();
                        $subjectimport = new SubjectImporter(session('currentProject')->id);
                        try {
                            Excel::import($subjectimport, $uploaded_file);
                        } catch (ValidationException $e) {
                            $failures = $e->failures();
                            $error_messages = [];
                            foreach ($failures as $failure) {
                                $error_messages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
                            }
                            Notification::make('danger', 'Import Failed', implode(' ; ', $error_messages));
                        }
                    }
                ),
            ExcelImportAction::make()
                ->use(MySubjecttImport::class)
                ->label('Import Subjects 89')
                ->color('gray')
                ->beforeImport(function (array $data, $livewire, $excelImportAction) {
                    $excelImportAction->additionalData([
                        'project_id' => session('currentProject')->id
                    ]);
                    $excelImportAction->customImportData([
                        'project_id' => session('currentProject')->id,
                    ]);
                })
                ->validateUsing([
                    // 'subjectid' => [
                    //     'required',
                    //     'unique:subjects,subjectID',
                    //     // 'unique:subjects,subjectID,NULL,id,project_id,' . $this->project_id
                    // ],
                    // 'site_id' => [
                    //     'required',
                    //     Rule::exists('sites', 'id')->where('project_id',  session('currentProject')->id)
                    // ],
                ]),
        ];
    }
}
