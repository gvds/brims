<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Mail\UserAccountCreated;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    #[\Override]
    protected function handleRecordCreation(array $data): Model
    {
        $data['password'] = bcrypt(Str::random(25));
        return static::getModel()::create($data);
    }

    protected function afterCreate(): void
    {
        Mail::to($this->record->email)->send(new UserAccountCreated($this->record, Auth::user()));
    }
}
