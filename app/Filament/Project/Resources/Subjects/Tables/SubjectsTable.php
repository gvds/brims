<?php

namespace App\Filament\Project\Resources\Subjects\Tables;

use App\Enums\SubjectStatus;
use App\Filament\Project\Resources\Subjects\Schemas\SubjectForm;
use App\Models\Subject;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subjectID')
                    ->label('Subject ID')
                    ->searchable(),
                TextColumn::make('site.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.fullname')
                    ->label('Manager')
                    ->searchable(['firstname', 'lastname']),
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
                    ->attribute('fullname')
                    ->label('Manager')
                    ->searchable()
                    ->preload(),
            ])
            ->deferFilters(false)
            ->recordActions([
                Action::make('enrol')
                    ->visible(fn(Subject $record): bool => $record->status === SubjectStatus::Generated)
                    ->schema(SubjectForm::configure(new Schema)->columns(2)->getComponents())
                    ->action(function (array $data, Subject $record): void {
                        DB::beginTransaction();
                        try {
                            $record->enrol($data);
                            Notification::make()
                                ->title('Subject enrolled successfully')
                                ->success()
                                ->send();
                            DB::commit();
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            Notification::make()
                                ->title('Error enrolling subject: ' . $th->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
