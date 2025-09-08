<?php

namespace App\Rules;

use App\Models\Subject;
use App\Models\SubjectEvent;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPSE implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!preg_match("/^\d+_\d+_\d+$/", (string) $value)) {
            $fail('The barcode format is invalid.');
            return;
        }

        [$project_id, $subject_id, $subject_event_id] = explode("_", (string) $value);

        if ((int) $project_id !== session("currentProject")->id) {
            $fail('The Project ID in the barcode does not match the current project.');
            return;
        }

        $subject = Subject::find($subject_id);

        $subjectEvent = SubjectEvent::find($subject_event_id);

        if (!$subject) {
            $fail('The Subject ID in the barcode does not match any Subject record in the current project.');
            return;
        }

        if ($subjectEvent->subject_id != $subject_id) {
            $fail('The Subject ID in the barcode does not match the Subject Event record.');
            return;
        }
    }
}
