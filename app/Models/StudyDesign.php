<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyDesign extends Model
{
    /** @use HasFactory<\Database\Factories\StudyDesignFactory> */
    use HasFactory;

    protected $table = 'studydesigns';

    protected $guarded = ['id'];
}
