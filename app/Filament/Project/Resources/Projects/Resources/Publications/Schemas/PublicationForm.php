<?php

namespace App\Filament\Project\Resources\Projects\Resources\Publications\Schemas;

use App\Enums\PublicationStatus;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PublicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                MarkdownEditor::make('title')
                    ->required()
                    ->columnSpanFull(),
                Grid::make(1)
                    ->schema([
                        TextInput::make('pubmed_id')
                            ->requiredIf('publication_status', PublicationStatus::Published->value)
                            ->regex('/^\d{7,8}$/'),
                        TextInput::make('doi')
                            ->requiredIf('publication_status', PublicationStatus::Published->value)
                            ->regex('/^10\.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i'),
                        TextInput::make('publication_date')
                            ->requiredIf('publication_status', PublicationStatus::Published->value),
                        Select::make('publication_status')
                            ->required()
                            ->options(PublicationStatus::class),
                    ]),
                Repeater::make('authors')
                    ->simple(
                        TextInput::make('name')
                            ->required()
                            ->label('Author Name'),
                    )
                    ->required(),

            ]);
    }
}
