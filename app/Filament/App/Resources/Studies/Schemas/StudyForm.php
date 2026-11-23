<?php

namespace App\Filament\App\Resources\Studies\Schemas;

use App\Models\Study;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class StudyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->autocomplete(false)
                    ->required()
                    ->unique(ignorable: fn (?Study $record): ?Study => $record),
                TextInput::make('identifier')
                    ->autocomplete(false)
                    ->required(),
                // Toggle::make('project_linked')
                //     ->label('Project-linked Study')
                //     ->live()
                //     ->disabled(fn (Get $get) => $get('projects') !== [])
                //     ->dehydrated()
                //     ->helperText('If checked, this study is linked to one or more projects.'),
                Select::make('projects')
                    ->label('Projects')
                    ->relationship('projects', 'title')
                    ->multiple()
                    ->preload()
                    // ->live()
                    ->searchable()
                    ->helperText('A project cannot be removed while its specimens are attached to this study, or while that would leave a study member who is neither a team admin nor a member of another linked project.')
                    ->rules([
                        fn (?Study $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($record): void {
                            $projectIds = $value ?? [];

                            $message = $record
                                ? self::projectRemovalViolation($record, $projectIds)
                                : self::creatorEligibilityViolation($projectIds);

                            if ($message) {
                                $fail($message);
                            }
                        },
                    ])
                    // ->disabled(fn (Get $get) => $get('project_linked') === false)
                    // ->saved()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('submission_date'),
                DatePicker::make('public_release_date'),
                Toggle::make('locked')
                    ->helperText('When locked, specimens cannot be added or removed.')
                    ->onColor('danger'),
            ])
            ->columns([
                'xs' => 1,
                'sm' => 2,
            ])
            ->extraAttributes(['class' => 'w-full lg:w-2/3 xl:w-1/2']);
    }

    /**
     * @param  array<int, int|string>  $projectIds
     */
    private static function projectRemovalViolation(Study $study, array $projectIds): ?string
    {
        $removedProjectIds = $study->projects()
            ->pluck('projects.id')
            ->diff($projectIds)
            ->all();

        $blockedTitles = $study->projects()
            ->whereKey($study->projectIdsWithSpecimens($removedProjectIds))
            ->pluck('projects.title');

        if ($blockedTitles->isNotEmpty()) {
            return 'Cannot remove '.$blockedTitles->join(', ').': specimens from '.($blockedTitles->count() > 1 ? 'these projects are' : 'this project is').' attached to the study.';
        }

        $ineligibleMembers = $study->membersIneligibleWithProjects($projectIds);

        if ($ineligibleMembers->isNotEmpty()) {
            return 'Cannot remove these projects: '.$ineligibleMembers->pluck('fullname')->join(', ').' would no longer be eligible to be study members. Remove them from the study first.';
        }

        return null;
    }

    /**
     * @param  array<int, int|string>  $projectIds
     */
    private static function creatorEligibilityViolation(array $projectIds): ?string
    {
        $creator = Auth::user();

        $isEligible = Study::eligibleMembersQuery($creator->team_id, $projectIds)
            ->whereKey($creator->getKey())
            ->exists();

        return $isEligible
            ? null
            : 'You become the lead of the study, so you must be a team admin or a member of one of the selected projects.';
    }
}
