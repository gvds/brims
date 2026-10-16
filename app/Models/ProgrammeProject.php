<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProgrammeProject extends Pivot
{
    protected $table = 'programme_project';

    protected $fillable = [
        'programme_id',
        'project_id',
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
