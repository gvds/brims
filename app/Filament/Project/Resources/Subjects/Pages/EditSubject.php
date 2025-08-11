<?php

namespace App\Filament\Project\Resources\Subjects\Pages;

use App\Enums\SubjectStatus;
use App\Filament\Project\Resources\Subjects\SubjectResource;
use App\Models\Event;
use Carbon\CarbonImmutable;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditSubject extends EditRecord
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->visible(fn($record): bool => $record->status !== SubjectStatus::Generated),
            DeleteAction::make(),
        ];
    }

    #[\Override]
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->status === SubjectStatus::Generated) {
            $data['status'] = SubjectStatus::Enrolled->value;
            $data['armBaselineDate'] = $data['enrolDate'];
        }

        return $data;
    }

    #[\Override]
    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        DB::beginTransaction();
        try {
            if ($record->status === SubjectStatus::Generated) {
                $armBaselineDate = new CarbonImmutable($data['enrolDate']);
                $newevents = Event::where('arm_id', $record->arm_id)->get();
                $newevents->each(fn($event) => $record->events()->attach(
                    $event,
                    [
                        'eventDate' => $armBaselineDate,
                        'minDate' => isset($event->offset_ante_window) ? $armBaselineDate->addDays($event->offset - $event->offset_ante_window) : null,
                        'maxDate' => isset($event->offset_post_window) ? $armBaselineDate->addDays($event->offset + $event->offset_post_window) : null,
                        'iteration' => 1,
                        'status' => 0,
                    ]
                ));
            }
            $record->update($data);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $record;
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
