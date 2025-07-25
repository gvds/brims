<?php

namespace App\Filament\Project\Resources\Subjects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('subjectID'),
                TextEntry::make('project_id')
                    ->numeric(),
                TextEntry::make('site.name')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('firstname'),
                TextEntry::make('lastname'),
                TextEntry::make('enrolDate')
                    ->date(),
                TextEntry::make('arm.name')
                    ->numeric(),
                TextEntry::make('armBaselineDate')
                    ->date(),
                TextEntry::make('previous_arm_id')
                    ->numeric(),
                TextEntry::make('previousArmBaselineDate')
                    ->date(),
                TextEntry::make('subject_status')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
