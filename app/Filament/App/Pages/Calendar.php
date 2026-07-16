<?php

namespace App\Filament\App\Pages;

use App\Enums\EventStatus;
use App\Models\SubjectEvent;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Calendar extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.app.pages.calendar';

    public static function canAccess(): bool
    {
        return Auth::user()->can('Manage:Subject');
    }

    public static function table(Table $table): Table
    {
        $substitutees = Auth::user()->substitutees()->pluck('users.id')->toArray();
        $user_ids = array_merge([$user_id = Auth::id()], $substitutees);

        return $table
            ->query(
                SubjectEvent::query()
                    ->with(['subject', 'event'])
                    ->whereHas('user', function ($query) use ($user_ids) {
                        $query->whereIn('users.id', $user_ids);
                    })
                    ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                    ->where('maxDate', '>=', now())
                    ->where('eventDate', '<=', now()->addDays(30))
                    ->orderBy('eventDate', 'asc')
            )
            ->columns([
                Stack::make([
                    TextColumn::make('subject.project.title')
                        ->label('Project')
                        ->limit(20),
                    Split::make([
                        TextColumn::make('subject.fullname')
                            ->label('Subject'),
                        Stack::make([
                            TextColumn::make('event.arm.name')
                                ->label('Arm'),
                            TextColumn::make('event.name')
                                ->label('Event'),
                        ]),
                    ])->extraAttributes(['class' => '!items-start']),
                ])
            ])
            ->defaultGroup(
                Group::make('eventDate')
                    ->label('Event Date')
                    ->getTitleFromRecordUsing(fn($record): string => Carbon::parse($record->eventDate)->format('l, d M Y'))
                    ->titlePrefixedWithLabel(false)
            )
            ->contentGrid([
                'sm' => 2,
                'lg' => 3,
                'xl' => 4,
                '2xl' => 5,
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
