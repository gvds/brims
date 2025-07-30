<?php

namespace App\Filament\Project\Resources\Subjects\Schemas;

use App\Enums\SubjectStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

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
                        TextEntry::make('address'),
                        TextEntry::make('site.name')
                            ->label('Site Name'),
                        TextEntry::make('enrolDate')
                            ->date('Y-m-d'),
                        TextEntry::make('subject_status')
                            ->label('Status'),
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
                                TextEntry::make('previous_arm_id')
                                    ->label('Name'),
                                TextEntry::make('previousArmBaselineDate')
                                    ->label('Baseline Date')
                                    ->date('Y-m-d'),
                            ]),
                    ]),
                TextEntry::make('user.fullname')
                    ->label('Manager'),
            ])
            ->columns([
                'sm' => 1,
                'md' => 2,
                'lg' => 3,
            ])
            ->extraAttributes(['class' => 'border border-gray-200 rounded-lg py-4 px-5 bg-gray-50 dark:bg-zinc-900 dark:border-zinc-800']);
    }
}
