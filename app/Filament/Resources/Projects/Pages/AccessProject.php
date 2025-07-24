<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class AccessProject extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ProjectResource::class;

    protected string $view = 'filament.resources.projects.pages.access-project';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        // self::$title = 'Project: ' . $this->record->title;
        if (session('currentProject')?->id != $this->record->id) {
            session(['currentProject' => $this->record]);
            Notification::make('projectselection')
                ->title('Project Selected')
                ->body(Str::markdown("The current project has changed to <br> **" . $this->record->title . "**"))
                ->status('success')
                ->color('info')
                ->send();
        }
    }

    public function getTitle(): string | Htmlable
    {
        return ''; // Or return null;
    }
}
