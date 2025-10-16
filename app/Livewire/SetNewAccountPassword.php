<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SetNewAccountPassword extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];
    public User $user;

    public function mount(): void
    {
        $this->user = User::find(basename(request()->getPathInfo()));
        if (!is_null($this->user->email_verified_at)) {
            to_route('filament.app.auth.login');
        }
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->autocomplete('new-password')
                    ->required()
                    ->confirmed()
                    ->minLength(12),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->autocomplete('new-password')
                    ->required()
                    ->minLength(12),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $this->user->update([
            'password' => bcrypt($this->data['password']),
            'email_verified_at' => now()->format('Y-m-d H:i:s'),
        ]);

        Auth::login($this->user);
        to_route('filament.app.pages.dashboard');
    }

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.set-new-account-password');
    }
}
