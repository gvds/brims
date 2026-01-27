<?php

namespace App\Actions;

use App\Models\SubjectEvent;
use Carbon\Carbon;

class QueueEventLabelsAction
{
    public function handle($thresholdDate = null)
    {
        $thresholdDate = $thresholdDate ?? Carbon::parse('next friday');
        $records = SubjectEvent::whereIn('eventstatus_id', [0, 1, 2])
            ->where('labelStatus', '0')
            ->join('events', 'event_id', 'events.id')
            ->join('arms', 'arm_id', 'arms.id')
            ->where('project_id', session('currentProject')->id)
            ->whereNotNull('eventDate')
            ->where('minDate', "<=", $thresholdDate)
            ->where('active', true)
            ->update(['labelStatus' => 1]);
        return $records;
    }
}
