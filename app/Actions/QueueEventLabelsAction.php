<?php

namespace App\Actions;

use App\Models\SubjectEvent;
use Carbon\Carbon;

class QueueEventLabelsAction
{
    public function handle(?\Carbon\CarbonInterface $thresholdDate = null, ?int $projectId = null, ?array $userIds = null): int
    {
        $thresholdDate = $thresholdDate ?? Carbon::parse('next friday');
        $projectId = $projectId ?? session('currentProject')->id;

        $query = SubjectEvent::query()
            ->whereIn('eventstatus_id', [0, 1, 2])
            ->where('labelstatus', \App\Enums\LabelStatus::Pending->value)
            ->whereNotNull('eventDate')
            ->where('minDate', '<=', $thresholdDate)
            ->where('active', true)
            ->whereHas('subject', fn ($q) => $q->where('project_id', $projectId));

        if (! empty($userIds)) {
            $query->whereHas('subject', fn ($q) => $q->whereIn('user_id', $userIds));
        }

        $affected = 0;

        $query->chunkById(500, function ($rows) use (&$affected) {
            $ids = $rows->pluck('id')->all();

            $updated = SubjectEvent::whereIn('id', $ids)
                ->update(['labelstatus' => \App\Enums\LabelStatus::Queued->value]);

            $affected += $updated;
        });

        return $affected;
    }
}
