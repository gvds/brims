<?php

namespace App\Filament\Project\Resources\Subjects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('subjectID')
                    ->label('Subject ID')
                    ->size(TextSize::Large)
                    ->weight(FontWeight::Bold),
                TextEntry::make('status')
                    ->size(TextSize::Large)
                    ->weight(FontWeight::Bold),
                TextEntry::make('site.name')
                    ->label('Site Name')
                    ->size(TextSize::Large)
                    ->weight(FontWeight::Bold),
                TextEntry::make('arm.name')
                    ->label('Current Arm')
                    ->size(TextSize::Large)
                    ->weight(FontWeight::Bold),
                TextEntry::make('user.fullname')
                    ->label('Manager')
                    ->size(TextSize::Large)
                    ->weight(FontWeight::Bold),
                Grid::make()
                    ->columns(2)
                    ->components([
                        TextInput::make('firstname')
                            ->label('First Name')
                            ->default(null),
                        TextInput::make('lastname')
                            ->label('Last Name')
                            ->default(null),
                    ])
                    ->columnSpanFull(),
                DatePicker::make('enrolDate')
                    ->label('Enrolment Date')
                    ->default(now())
                    ->required(),
                Repeater::make('address')
                    ->simple(
                        TextInput::make('addressEntry'),
                    )
                    ->columnSpan(2)
                    ->hint('Enter one address component per line'),
            ])
            ->columns(2)
            ->extraAttributes([
                'class' => 'w-1/3',
            ]);
    }
}
