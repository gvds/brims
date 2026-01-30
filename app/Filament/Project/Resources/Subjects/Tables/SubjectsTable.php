<?php

namespace App\Filament\Project\Resources\Subjects\Tables;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Enums\SubjectStatus;
use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use App\Filament\Project\Resources\Subjects\Schemas\SubjectForm;
use App\Models\Event;
use App\Models\Subject;
use App\Models\User;
use App\Services\REDCap;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        $substitutees = Auth::user()->substitutees()
            ->where('project_id', session('currentProject')->id)
            ->pluck('users.id');

        return $table
            ->modifyQueryUsing(function ($query) use ($substitutees) {
                if (Auth::user()->team_role !== TeamRoles::Admin && !in_array(Auth::user()->system_role, [SystemRoles::SysAdmin, SystemRoles::SuperAdmin])) {
                    $query->where('user_id', Auth::id())
                        ->orWhereIn('user_id', $substitutees);
                }
            })
            ->columns([
                TextColumn::make('subjectID')
                    ->label('Subject ID')
                    ->searchable(),
                TextColumn::make('site.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.fullname')
                    ->label('Manager')
                    ->searchable(),
                TextColumn::make('firstname')
                    ->searchable(),
                TextColumn::make('lastname')
                    ->searchable(),
                TextColumn::make('enrolDate')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('arm.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('armBaselineDate')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('previousArm.name'),
                TextColumn::make('previousArmBaselineDate')
                    ->label('Previous Arm Baseline Date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(SubjectStatus::class),
                SelectFilter::make('site_id')
                    ->relationship('site', 'name')
                    ->label('Site')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('user_id')
                    ->options(fn(): array => User::all()->pluck('fullname', 'id')->toArray())
                    // ->relationship(
                    //     name: 'user',
                    //     titleAttribute: fn($record) => dd($record)
                    //     // titleAttribute: fn($record) => $record->user->firstname . ' ' . $record->user->lastname
                    // )
                    // ->attribute('fullname')
                    ->label('Manager')
                    ->searchable()
                    ->preload(),
            ])
            ->deferFilters(false)
            ->recordUrl(fn($record) => $record->status !== SubjectStatus::Generated ? route('filament.project.resources.subjects.view', ['tenant' => session('currentProject'), 'record' => $record]) : null)
            ->recordActions([
                ViewAction::make()
                    ->visible(fn($record): bool => $record->status !== SubjectStatus::Generated),
                Action::make('enrol')
                    ->visible(fn($record): bool => $record->status === SubjectStatus::Generated)
                    ->schema(SubjectForm::configure(new Schema())->columns(2)->getComponents())
                    ->action(function (array $data, Subject $record) {
                        DB::beginTransaction();
                        try {
                            $record->enrol($data);
                            DB::commit();
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            Notification::make()
                                ->title('Error enrolling subject: ' . $th->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                        Notification::make()
                            ->title('Subject enrolled successfully')
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->visible(fn($record): bool => $record->status === SubjectStatus::Enrolled)
                    ->successNotification(
                        Notification::make()
                            ->title('Subject updated successfully')
                            ->success()
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
