<?php

namespace App\Filament\App\Pages;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    #[\Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent()
                    ->helperText(str('New users can set their password by clicking **"Forgot your password?"**')
                        ->inlineMarkdown()
                        ->toHtmlString()),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[\Override]
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
            // 'active' => true, // This check has been replaced by the override of the authenticate method below --- IGNORE ---
        ];
    }

    #[\Override]
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        $credentials = $this->getCredentialsFromFormData($data);

        $authProvider = Filament::auth()->getProvider();
        $user = $authProvider->retrieveByCredentials($credentials);

        if ($user && $authProvider->validateCredentials($user, $credentials) && ! $user->active) {
            throw ValidationException::withMessages([
                'data.username' => __('Your account is currently inactive.'),
            ]);
        }

        return parent::authenticate();
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
