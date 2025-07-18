<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    // protected string $view = 'filament.pages.profile';
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('firstname')
                    ->required()
                    ->autocomplete()
                    ->autofocus(),
                TextInput::make('lastname')
                    ->required()
                    ->autocomplete(),
                TextInput::make('telephone')
                    ->tel()
                    ->mask('+99 (99) 999-9999')
                    ->maxLength(20),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
