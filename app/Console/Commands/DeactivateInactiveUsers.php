<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:deactivate-inactive-users')]
#[Description('Command description')]
class DeactivateInactiveUsers extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = now()->subMonths(3);

        $deactivatedCount = User::query()
            ->where('active', true)
            ->where('last_login', '<', $threshold)
            ->update(['active' => false]);

        $this->info("Deactivated {$deactivatedCount} users.");
    }
}
