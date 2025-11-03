<?php

namespace App\Filament\Project\Resources\Subjects\Pages;

use App\Filament\Project\Resources\Subjects\SubjectResource;
use App\Imports\SubjectImporter;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

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
                        Excel::import($subjectimport, $uploaded_file);
                    }
                ),
        ];
    }
}
