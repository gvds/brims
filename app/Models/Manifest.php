<?php

namespace App\Models;

use App\Enums\ManifestStatus;
use App\Enums\SpecimenStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Manifest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'shippedDate' => 'date',
        'receivedDate' => 'date',
        'status' => ManifestStatus::class,
        'specimenTypes' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sourceSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'sourceSite_id');
    }

    public function destinationSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'destinationSite_id');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receivedBy_id');
    }

    public function specimens(): BelongsToMany
    {
        return $this->belongsToMany(Specimen::class, 'manifest_items')
            ->using(ManifestItem::class)
            ->withPivot(['id', 'priorSpecimenStatus', 'received', 'receivedTime'])
            ->withTimestamps();
    }

    public function ship(): void
    {
        if ($this->status !== ManifestStatus::Open) {
            throw new \Exception('Only manifests with status "Open" can be shipped.');
        }

        $this->status = ManifestStatus::Shipped;
        $this->shippedDate = now();
        $this->save();

        foreach ($this->specimens as $specimen) {
            $specimen->status = SpecimenStatus::Transferred;
            $specimen->save();
        }
    }

    public function receive(): void
    {
        if ($this->status !== ManifestStatus::Shipped) {
            throw new \Exception('Only manifests with status "Shipped" can be received.');
        }

        $this->status = ManifestStatus::Received;
        $this->receivedDate = now();
        $this->receivedBy_id = Auth::id();
        $this->save();

        foreach ($this->specimens as $specimen) {
            $specimen->status = SpecimenStatus::Logged;
            $specimen->site_id = $this->destinationSite_id;
            $specimen->save();
            $this->specimens()->updateExistingPivot($specimen->id, [
                'received' => true,
                'receivedTime' => now(),
            ]);
        }
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $items = $this->specimens()
            ->with('specimenType', 'subjectEvent.event.arm', 'subjectEvent.subject')
            ->orderBy('id')
            ->get();

        $filename = "manifest-{$this->id}.csv";

        return response()->streamDownload(function () use ($items) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Subject', 'Barcode', 'Arm', 'Event', 'Sample Type', 'Aliquot', 'Volume']);

            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->subjectEvent->subject->subjectID,
                    $item->barcode,
                    $item->subjectEvent->event->arm->name,
                    $item->subjectEvent->event->name,
                    $item->specimenType->name,
                    $item->aliquot,
                    $item->volume . $item->specimenType->volumeUnit,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
