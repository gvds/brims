<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
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
        $exportPath = storage_path('app/private/filament_exports');

        if (! File::isDirectory($exportPath)) {
            $this->info('Export directory does not exist.');

            return self::SUCCESS;
        }

        $directories = File::directories($exportPath);
        $deletedCount = 0;
        $cutoffTime = now()->subHours(24)->timestamp;

        foreach ($directories as $directory) {
            $directoryTime = File::lastModified($directory);

            if ($directoryTime < $cutoffTime) {
                File::deleteDirectory($directory);
                $deletedCount++;
                $this->line("Deleted: {$directory}");
            }
        }

        $this->info("Deleted {$deletedCount} old export directories.");

        return self::SUCCESS;
    }
}
