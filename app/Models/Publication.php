<?php

namespace App\Models;

use App\Enums\PublicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    /** @use HasFactory<\Database\Factories\PublicationFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'authors' => 'array',
        'publication_status' => PublicationStatus::class,
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
