<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLoginAt
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $event->user->update([
            'last_login' => now(),
            'active' => true,
        ]);
    }
}
