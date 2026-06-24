<?php

namespace App\Models;

use Database\Factories\StudydesignFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(StudydesignFactory::class)]
class StudyDesign extends Model
{
    /** @use HasFactory<\Database\Factories\StudyDesignFactory> */
    use HasFactory;

    protected $table = 'studydesigns';

    /**
     * Get the projects associated with the study design.
     */

    protected $guarded = ['id'];
}
