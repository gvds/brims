<?php

namespace App\Filament\Resources\Projects\Resources\Publications\Schemas;

use App\Enums\PublicationStatus;
use Closure;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
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
                            ->regex('/^\d{7,8}$/', 'PubMed ID must be a 7 or 8 digit number.'),
                        TextInput::make('doi')
                            ->requiredIf('publication_status', PublicationStatus::Published->value)
                            ->regex('/^10\.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i', 'DOI must start with "10." followed by a valid DOI format.'),
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
