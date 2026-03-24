<?php

namespace App\Http\Controllers;

use App\Models\StorageAllocation;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class StorageAllocationReportController extends Controller
{
    private $fpdf;

    /**
     * Handle the incoming request.
     */
    public function __invoke(StorageAllocation $storageAllocation, Request $request)
    {
        $layout = 'P';
        if ($layout == 'P') {
            $this->fpdf = new Fpdf('P');
        } else {
            $this->fpdf = new Fpdf('L');
        }

        $this->fpdf->AddFont('Calibri', 'B', 'calibrib.php');
        $this->fpdf->AddFont('Calibri', '', 'calibri.php');
        $this->fpdf->SetDisplayMode('fullpage');
        $this->fpdf->SetMargins(5, 5);
        $this->fpdf->AddPage();
        $this->fpdf->SetFont('Calibri', 'B', 16);
        $this->fpdf->Cell(0, 9, $storageAllocation->project->title . ': Specimen Storage', 0, 1, 'C');
        $this->fpdf->SetFont('Calibri', 'B', 14);
        $this->fpdf->Cell(0, 9, '(' . $storageAllocation->created_at . ' - ' . $storageAllocation->user->fullname . ')', 0, 1, 'C');
        $this->fpdf->SetFont('Calibri', 'B', 11);
        $this->fpdf->Cell(0, 0, '', 'T', 1, 'L');
        $this->fpdf->Cell(42, 7, 'Specimen Type', '', 0, 'L');
        $this->fpdf->Cell(24, 7, 'Subject', '', 0, 'L');
        $this->fpdf->Cell(20, 7, 'Event', '', 0, 'L');
        $this->fpdf->Cell(14, 7, 'Aliquot', '', 0, 'L');
        if ($layout == 'P') {
            $this->fpdf->Cell(23, 7, 'Barcode', '', 0, 'L');
        } else {
            $this->fpdf->Cell(40, 7, 'Barcode', '', 0, 'L');
        }
        $this->fpdf->Cell(18, 7, 'Unit', '', 0, 'L');
        $this->fpdf->SetFont('Calibri', 'B', 8);
        $this->fpdf->Cell(30, 7, ' (Virtual-Unit Rack:Box:Position)', '', 1, 'L');
        $this->fpdf->Cell(0, 0, '', 'T', 1, 'L');

        $this->fpdf->SetFont('Calibri', '', 9);

        foreach ($storageAllocation->storageLogs as $storageLog) {
            $this->fpdf->Cell(42, 7, $storageLog->specimentype->name, 0, 0, 'L');
            $this->fpdf->Cell(24, 7, $storageLog->specimen->subject->subjectID, 0, 0, 'L');
            $this->fpdf->Cell(20, 7, $storageLog->specimen->subjectEvent->event->name, 0, 0, 'L');
            $this->fpdf->Cell(14, 7, $storageLog->specimen->aliquot, 0, 0, 'C');
            if ($layout == 'P') {
                $this->fpdf->Cell(23, 7, $storageLog->specimen->barcode, 0, 0, 'L');
            } else {
                $this->fpdf->Cell(40, 7, $storageLog->specimen->barcode, 0, 0, 'L');
            }
            if (! empty($storageLog->location_id)) {
                $locstring = '[' . $storageLog->location->virtualUnit->physicalUnit->name . '] : ' . $storageLog->location->virtualUnit->virtualUnit . '   ' . $storageLog->location->rack . ' : ' . $storageLog->location->box . ' : ' . $storageLog->location->position;
            } else {
                $locstring = 'No Storage location allocated';
            }
            $this->fpdf->Cell(60, 7, $locstring, 0, 1, 'L');
        }
        $this->fpdf->Cell(0, 1, '', 'TB', 1, 'L');

        $storageLogs = $storageAllocation->storageLogs->whereNull('location_id');
        if ($storageLogs->count() > 0) {
            $this->fpdf->SetFont('Calibri', 'B', 12);
            $this->fpdf->Cell(0, 3, '', '', 1, 'L');
            $this->fpdf->Cell(0, 9, 'Specimens not allocated storage positions due to the lack of available space', 0, 1, 'L');
            $this->fpdf->SetFont('Calibri', 'B', 11);
            $this->fpdf->Cell(0, 0, '', 'T', 1, 'L');
            $this->fpdf->Cell(30, 7, 'Barcode', '', 0, 'L');
            $this->fpdf->Cell(55, 7, 'Specimen Type', '', 1, 'L');
            $this->fpdf->Cell(0, 0, '', 'T', 1, 'L');
            $this->fpdf->SetFont('Calibri', '', 9);

            foreach ($storageLogs as $storageLog) {
                $this->fpdf->Cell(30, 7, $storageLog->specimen->barcode, 0, 0, 'L');
                $this->fpdf->Cell(55, 7, $storageLog->specimentype->name, 0, 1, 'L');
            }
        }
        $this->fpdf->Cell(0, 1, '', 'TB', 1, 'L');

        $pdf = $this->fpdf->Output('', 'S');

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="storage_report_' . $storageAllocation->id . '.pdf"',
        ]);
    }
}
