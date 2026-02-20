<?php

namespace App\Filament\Project\Resources\Subjects\Pages;

use App\Filament\Project\Resources\Subjects\SubjectResource;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Subject;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class ListSubjects extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate_subjects')
                ->schema([
                    Select::make('arm')
                        ->options(fn() => session('currentProject')
                            ->arms
                            ->where('manual_enrol', true)
                            ->pluck('name', 'id')
                            ->toArray())
                        ->required(),
                    TextInput::make('subjects')
                        ->label('Number of Subjects to Generate')
                        ->hint('Enter a number between 1 and 20')
                        ->integer()
                        ->minValue(1)
                        ->maxValue(20)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $currentProject = Project::find(session('currentProject')->id);
                    $lastSubjectNumber = $currentProject->last_subject_number;
                    try {
                        DB::beginTransaction();
                        for ($i = 1; $i <= $data['subjects']; $i++) {
                            Subject::create([
                                'subjectID' => $currentProject->subjectID_prefix . str_pad(++$lastSubjectNumber, $currentProject->subjectID_digits, '0', STR_PAD_LEFT),
                                'project_id' => $currentProject->id,
                                'arm_id' => $data['arm'],
                                'user_id' => Auth::id(),
                                'site_id' => ProjectMember::where('project_id', $currentProject->id)
                                    ->where('user_id', Auth::id())
                                    ->value('site_id'),
                            ]);
                        }
                        $currentProject->last_subject_number = $lastSubjectNumber;
                        $currentProject->save();
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        Notification::make()
                            ->title('Error generating subjects: ' . $th->getMessage())
                            ->danger()
                            ->send()
                            ->persistent();
                    }
                })
                ->modalWidth('lg'),
        ];
    }
}
