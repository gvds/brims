<?php

namespace App\Http\Controllers;

use App\Actions\PrintLabels;
use App\Enums\LabelStatus;
use App\Enums\SubjectStatus;
use App\Library\PDF_Label;
use App\Models\SubjectEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    private $fpdf;

    /**
     * Print labels for either: (a) the queued labels for the current user/project or
     * (b) a list of subject-event ids passed via the `ids[]` query parameter.
     *
     * Returns an application/pdf download response.
     */
    public function __invoke(Request $request)
    {
        $userIds = Auth::user()->substitutees->pluck('id')->push(Auth::id())->all();

        $subjectEvents = SubjectEvent::join('subjects', 'subject_id', 'subjects.id')
            ->join('events', 'event_id', 'events.id')
            ->join('arms', 'events.arm_id', 'arms.id')
            ->when($request->has('ids'), fn($query) => $query->whereIn('subject_event.id', $request->input('ids', [])))
            ->whereHas('subject', fn(Builder $q) => $q
                ->where('project_id', session('currentProject')->id)
                ->where('status', SubjectStatus::Enrolled)
                ->whereIn('user_id', $userIds))
            ->where('labelstatus', LabelStatus::Queued)

            ->select([
                'subject_event.id',
                'subjects.project_id',
                'arms.name AS armname',
                'events.name AS eventname',
                'events.id AS event_id',
                'subjectID',
                'firstname',
                'lastname',
                'subject_event_labels',
                'name_labels',
                'study_id_labels',
                'iteration',
            ])
            ->get();
        $this->fpdf = new PDF_Label('L7651_mod');

        $this->fpdf->AddPage();
        $this->fpdf->AddFont('Calibri', '', 'calibri.php');
        $this->fpdf->SetFont('Calibri', '', 8);

        foreach ($subjectEvents as $event) {
            // Generate Name labels
            $PSE = $event->project_id . '_' . $event->subjectID . '_' . $event->id;
            for ($i = 0; $i < $event->name_labels; $i++) {
                $text = sprintf("%s %s\n%s\n%s [%s]\nArm: %s", $event->firstname, $event->lastname, $PSE, $event->eventname, $event->iteration, $event->armname);
                $this->fpdf->Add_BarLabel($text, $PSE);
            }
            // Generate Study ID labels
            for ($i = 0; $i < $event->study_id_labels; $i++) {
                $text = sprintf('%s', $event->subjectID);
                $this->fpdf->Add_BarLabel($text, $event->subjectID);
            }
            // Generate PSE labels
            for ($i = 0; $i < $event->subject_event_labels; $i++) {
                $text = sprintf("%s\n%s [%s]\nArm: %s", $PSE, $event->eventname, $event->iteration, $event->armname);
                $this->fpdf->Add_BarLabel($text, $PSE);
            }
        }

        // Return PDF as a string so caller (controller) can emit a proper response.
        $pdf = $this->fpdf->Output('', 'S');

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="labels.pdf"',
        ]);
    }
}
