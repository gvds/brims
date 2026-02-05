<?php

namespace App\Models;

use App\Enums\ManifestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Manifest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'shippedDate' => 'date',
        'receivedDate' => 'date',
        'status' => ManifestStatus::class,
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
}
