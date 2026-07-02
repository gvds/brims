<?php

namespace App\Models;

use App\Enums\ManifestStatus;
use App\Models\Scopes\SpecimenScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Manifest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
            $specimen->logTransferred();
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

        $specimens = $this->specimens()->withoutGlobalScope(SpecimenScope::class)->get();

        foreach ($specimens as $specimen) {
            $specimen->logReceived($this->destinationSite_id);
            $this->specimens()->updateExistingPivot($specimen->id, [
                'received' => true,
                'receivedTime' => now(),
            ]);
        }
    }

    public function export(): StreamedResponse
    {
        $items = $this->specimens()
            ->with('specimenType', 'subjectEvent.event.arm', 'subjectEvent.subject')
            ->orderBy('id')
            ->get();

        $filename = "manifest-{$this->id}.csv";

        return response()->streamDownload(function () use ($items): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Subject', 'Barcode', 'Arm', 'Event', 'Sample Type', 'Aliquot', 'Volume'], escape: '\\');

            foreach ($items as $item) {
                fputcsv(
                    $handle,
                    [
                        $item->subjectEvent->subject->subjectID,
                        $item->barcode,
                        $item->subjectEvent->event->arm->name,
                        $item->subjectEvent->event->name,
                        $item->specimenType->name,
                        $item->aliquot,
                        $item->volume . $item->specimenType->volumeUnit,
                    ],
                    escape: '\\'
                );
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
    #[\Override]
    protected function casts(): array
    {
        return [
            'shippedDate' => 'date',
            'receivedDate' => 'date',
            'status' => ManifestStatus::class,
            'specimenTypes' => 'array',
        ];
    }
}
