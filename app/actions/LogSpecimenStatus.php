<?php

namespace App\actions;

use App\Enums\SpecimenStatus;
use App\Models\Specimen;
use Illuminate\Support\Facades\Auth;

class LogSpecimenStatus
{
    public function __invoke(SpecimenStatus $status, string $barcodesInput, bool $thawed = false): int
    {
        $barcodes = array_map('trim', preg_split('/,|\r\n|\r|\n/', $barcodesInput));
        $barcodes = array_filter($barcodes); // Remove empty lines
        $barcodes = array_unique($barcodes); // Remove duplicate barcodes

        $allSpecimens = Specimen::whereIn('barcode', $barcodes)
            ->where('project_id', session('currentProject')->id)
            ->get();

        $validStatuses = match ($status) {
            SpecimenStatus::Used => [SpecimenStatus::Logged, SpecimenStatus::InStorage, SpecimenStatus::LoggedOut],
            SpecimenStatus::InStorage => [SpecimenStatus::LoggedOut],
            SpecimenStatus::LoggedOut => [SpecimenStatus::InStorage],
            default => throw new \Exception('Invalid target status provided.'),
        };

        $specimens = $allSpecimens
            ->whereIn('status', $validStatuses)
            ->where('site_id', session('currentProject')->members()->firstWhere('user_id', Auth::id())->site_id);

        if (count($barcodes) !== $specimens->count()) {
            $errorMessage = '';

            $invalidStatusSpecimens = $allSpecimens->whereNotIn('status', $validStatuses);

            if ($invalidStatusSpecimens->count()) {
                match ($validStatuses) {
                    [SpecimenStatus::InStorage] => $errorMessage .= 'The following barcodes are not listed as in storage: ' . implode(', ', $invalidStatusSpecimens->pluck('barcode')->toArray()) . '.<br><br>',
                    [SpecimenStatus::LoggedOut] => $errorMessage .= 'The following barcodes are not listed as logged out: ' . implode(', ', $invalidStatusSpecimens->pluck('barcode')->toArray()) . '.<br><br>',
                    default => $errorMessage .= 'The following barcodes are not eligible to be logged as used: ' . implode(', ', $invalidStatusSpecimens->pluck('barcode')->toArray()) . '.<br><br>',
                };
            }

            $invalidSiteSpecimens = $allSpecimens->whereNotIn('site_id', session('currentProject')->members()->firstWhere('user_id', auth()->id())->site_id);
            if ($invalidSiteSpecimens->count()) {
                $errorMessage .= 'The following barcodes are not located at your site: ' . implode(', ', $invalidSiteSpecimens->pluck('barcode')->toArray()) . '.<br><br>';
            }

            $notFoundBarcodes = array_diff($barcodes, $allSpecimens->pluck('barcode')->toArray());
            if ($notFoundBarcodes) {
                $errorMessage .= 'The following barcodes were not found in this project: ' . implode(', ', $notFoundBarcodes) . '.';
            }

            throw new \Exception($errorMessage);
        }

        switch ($status) {
            case SpecimenStatus::Used:
                $specimens->each(function ($specimen) {
                    $specimen->logUsed();
                });
                break;
            case SpecimenStatus::InStorage:
                $specimens->each(function ($specimen) use ($thawed) {
                    $specimen->logReturn($thawed);
                });
                break;
            case SpecimenStatus::LoggedOut:
                $specimens->each(function ($specimen) {
                    $specimen->logOut();
                });
                break;
        }
        $specimens->each(function ($specimen) use ($status) {
            $specimen->status = $status;
            $specimen->usedBy_id = Auth::id();
            $specimen->usedAt = now();
            $specimen->save();
        });

        return $specimens->count();
    }
}
