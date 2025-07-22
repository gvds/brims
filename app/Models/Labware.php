<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labware extends Model
{
    /** @use HasFactory<\Database\Factories\LabwareFactory> */
    use HasFactory;

    protected $guarded = ['id'];
}
