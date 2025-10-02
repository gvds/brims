<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectMember extends Pivot
{
    protected $table = 'project_member';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
