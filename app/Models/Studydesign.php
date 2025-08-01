<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Studydesign extends Model
{
    /** @use HasFactory<\Database\Factories\StudydesignFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
