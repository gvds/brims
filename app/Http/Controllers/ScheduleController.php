<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectEvent;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class ScheduleController extends Controller
{
    private $fpdf;

    public function generate(Request $request, $week)
    {
        try {
            $currentProject = session('currentProject');

            if (! in_array($week, ['thisweek', 'nextweek'])) {
                return back()->with('error', 'Invalid schedule week specified');
            }

            if ($week == 'nextweek') { // Next week's schedule
                $startdate = Date::parse('next monday');
            } else { // This week's schedule
                $startdate = Date::parse('monday this week');
            }

            $hstartdate = $startdate->format('d/m/Y'); // formatted for header
            $enddate = $startdate->add(6, 'days');
            $henddate = $enddate->format('d/m/Y'); // formatted for header
            $header = "($hstartdate - $henddate)";

            $this->fpdf = new Fpdf('L');
            $this->fpdf->AddFont('Calibri', 'B', 'calibrib.php');
            $this->fpdf->SetDisplayMode('fullpage');
            $this->fpdf->SetMargins(5, 5);
            $this->fpdf->AddPage();
            $this->fpdf->SetFont('Calibri', 'B', 16);
            $this->fpdf->Cell(0, 9, $currentProject->title." project Followup Schedule - $header", 0, 1, 'C');
            $this->fpdf->SetFont('Calibri', 'B', 11);
            $this->fpdf->Cell(0, 0, '', 'T', 1, 'L');
            $this->fpdf->Cell(26, 7, 'Subject', '', 0, 'C');
            $this->fpdf->Cell(50, 7, 'Name', '', 0, 'C');
            $this->fpdf->Cell(35, 7, 'Event', '', 0, 'C');
            $this->fpdf->Cell(25, 7, 'Due Date', '', 0, 'C');
            $this->fpdf->Cell(25, 7, 'Start Date', '', 0, 'C');
            $this->fpdf->Cell(25, 7, 'End Date', '', 0, 'C');
            $this->fpdf->Cell(25, 7, 'Address', '', 0, 'C');

            $this->fpdf->Cell(0, 7, '', '', 1, 'L');
            $this->fpdf->Cell(0, 0, '', 'T', 1, 'L');

            $this->fpdf->SetFillColor(220, 220, 220);

            // Get ids of current user and users for whom this user is substituting
            $userIDList = Auth::user()->substitutees()
                ->pluck('users.id')
                ->push(Auth::id());

            // Schedule events
            SubjectEvent::whereHas(
                'subject',
                fn ($query) => $query->where('project_id', session('currentProject')->id)
                    ->whereIn('user_id', $userIDList)
                    ->where('status', 1)
            )
                ->whereHas('event', fn ($query) => $query->where('active', true))
                ->where('minDate', '<=', $enddate)
                ->where('status', '<', 2)
                ->update(['status' => 2]);

            $subjects = Subject::with([
                'events' => fn ($query) => $query->where('status', 2)
                    ->where('active', true)
                    ->orderBy('eventDate'),
            ])
                ->where('project_id', session('currentProject')->id)
                ->where('user_id', auth()->user()->id)
                ->where('status', 1)
                ->orderBy('subjectID')
                ->get();
            $fill = 1;
            foreach ($subjects as $subject) {
                foreach ($subject->events as $event) {
                    $fill = $fill ? 0 : 1;
                    $this->fpdf->SetFont('Arial', '', 10);
                    $this->fpdf->Cell(26, 9, $subject->subjectID, 0, 0, 'C', $fill);
                    $this->fpdf->Cell(50, 9, $subject->fullname, 0, 0, 'C', $fill);
                    $this->fpdf->Cell(35, 9, $event->name, 0, 0, 'C', $fill);
                    $this->fpdf->SetFont('Arial', 'B', 10);
                    $this->fpdf->Cell(25, 9, $event->pivot->eventDate, 0, 0, 'C', $fill);
                    $this->fpdf->SetFont('Arial', '', 10);
                    $this->fpdf->Cell(25, 9, $event->pivot->minDate, 0, 0, 'C', $fill);
                    $this->fpdf->Cell(25, 9, $event->pivot->maxDate, 0, 0, 'C', $fill);
                    $this->fpdf->SetFont('Arial', '', 8);
                    $address = implode(', ', $subject->address);
                    $this->fpdf->Cell(0, 9, $address, 0, 1, 'L', $fill);
                }
            }

            $this->fpdf->Output('schedule.pdf', 'I');
        } catch (\Throwable $th) {
            return redirect('/')->with('error', $th->getMessage());
        }
    }
}
