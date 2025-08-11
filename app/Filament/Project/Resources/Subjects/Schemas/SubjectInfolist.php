<?php

namespace App\Filament\Project\Resources\Subjects\Schemas;

use App\Enums\SubjectStatus;
use App\Models\Arm;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\HtmlString;

class SubjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $record = $schema->getRecord();
        return $schema
            ->components([
                Fieldset::make('Subject Details')
                    ->columns([
                        'sm' => 1,
                        'lg' => 2,
                        '2xl' => 3,
                    ])
                    ->components([
                        TextEntry::make('subjectID')
                            ->label('Subject ID'),
                        TextEntry::make('fullname')
                            ->label('Full Name'),
                        TextEntry::make('address')
                            ->listWithLineBreaks(),
                        TextEntry::make('site.name')
                            ->label('Site Name'),
                        TextEntry::make('enrolDate')
                            ->date('Y-m-d'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->size(TextSize::Medium)
                            ->extraAttributes(fn(): array => [
                                'class' => match ($record->status) {
                                    SubjectStatus::Generated => 'border rounded-lg px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                    SubjectStatus::Enrolled => 'border rounded-lg px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/40 bg- dark:text-green-300',
                                    SubjectStatus::Dropped => 'border rounded-lg px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
                                },
                            ]),
                    ]),
                Grid::make()
                    ->columns(1)
                    ->components([
                        Fieldset::make('Current Arm')
                            ->columns(2)
                            ->components([
                                TextEntry::make('arm.name')
                                    ->label('Name'),
                                TextEntry::make('armBaselineDate')
                                    ->label('Baseline Date')
                                    ->date('Y-m-d'),
                            ]),
                        Fieldset::make('Previous Arm')
                            ->columns(2)
                            ->components([
                                TextEntry::make('previousArm.name')
                                    ->label('Name')
                                    ->live(),
                                TextEntry::make('previousArmBaselineDate')
                                    ->label('Baseline Date')
                                    ->date('Y-m-d'),
                            ])
                            ->hidden(fn(): bool => $record->previous_arm_id === null),
                    ]),
                TextEntry::make('user.fullname')
                    ->label('Manager'),
                Actions::make([
                    Action::make('Drop')
                        ->label('Drop Subject')
                        ->color('danger')
                        ->action(fn() => $record->update(['status' => SubjectStatus::Dropped->value]))
                        ->after(fn($livewire) => $livewire->dispatch('refreshSubjectViewData'))
                        ->visible(fn(): bool => $record->status->value === SubjectStatus::Enrolled->value),
                    Action::make('Re-Instate')
                        ->label('Re-Instate Subject')
                        ->color('success')
                        ->action(fn() => $record->update(['status' => SubjectStatus::Enrolled->value]))
                        ->after(fn($livewire) => $livewire->dispatch('refreshSubjectViewData'))
                        ->visible(fn(): bool => $record->status->value === SubjectStatus::Dropped->value),
                    Action::make('switch_arm')
                        ->label('Switch Arm')
                        ->color('info')
                        ->schema([
                            Select::make('arm_id')
                                ->label('Arm')
                                ->options(Arm::query()->whereIn('id', $record->arm->switcharms ?? [])->pluck('name', 'id'))
                                ->required()
                                ->in(fn() => Arm::query()->whereIn('id', $record->arm->switcharms ?? [])->pluck('id')),
                            DatePicker::make('armBaselineDate')
                                ->label('New Arm Baseline Date')
                                ->default(Date::now())
                                ->required()
                                ->beforeOrEqual('today')
                                ->afterOrEqual($record->armBaselineDate)
                                ->visible(fn(): bool => $record->status->value === SubjectStatus::Enrolled->value && $record->arm->switcharms !== null),
                        ])
                        ->action(fn($data) => $record->switchArm($data['arm_id'], $data['armBaselineDate']))
                        ->after(fn($livewire) => $livewire->dispatch('refreshSubjectViewData'))
                        ->requiresConfirmation()
                        ->modalDescription(new HtmlString('<div class="text-md font-bold">Are you sure you want to switch arms?</div><div class="text-lg text-red-500 font-bold">All currently pending events will be cancelled.</div>'))
                        ->visible(fn(): bool => $record->status->value === SubjectStatus::Enrolled->value && $record->arm->switcharms !== null),
                    Action::make('revert_arm_switch')
                        ->label('Revert Arm Switch')
                        ->color('warning')
                        ->action(fn() => $record->revertArmSwitch())
                        ->after(fn($livewire) => $livewire->dispatch('refreshSubjectViewData'))
                        ->requiresConfirmation()
                        ->modalDescription(new HtmlString('<div class="text-md font-bold">Are you sure you want to revert the previous arm switch?</div><div class="text-lg text-red-500 font-bold">All currently events in the current arm will be deleted.</div>'))
                        ->visible(fn(): bool => $record->status->value === SubjectStatus::Enrolled->value && $record->previous_arm_id !== null),
                ])
            ])
            ->columns([
                'sm' => 1,
                'md' => 2,
                'lg' => 3,
            ])
            ->extraAttributes(['class' => 'border border-gray-200 rounded-lg py-4 px-5 bg-gray-50 dark:bg-zinc-900 dark:border-zinc-800']);
    }
}
