<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteOldExports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-exports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete exported directories older than 24 hours from filament_exports';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        if (! Storage::disk('exports')->exists('filament_exports')) {
            Log::warning('Export directory does not exist.');

            return self::SUCCESS;
        }

        $directories = Storage::disk('exports')->directories('filament_exports');
        $deletedCount = 0;
        $cutoffTime = now()->subHours(24)->timestamp;

        foreach ($directories as $directory) {
            $directoryTime = filectime(Storage::disk('exports')->path($directory));

            if ($directoryTime < $cutoffTime) {
                Storage::disk('exports')->deleteDirectory($directory);
                $deletedCount++;
                Log::info("Deleted: {$directory}");
            }
        }

        Log::info("Deleted {$deletedCount} old export directories.");

        return self::SUCCESS;
    }
}
