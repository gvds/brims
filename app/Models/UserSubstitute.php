<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSubstitute extends Pivot
{
    protected $table = 'user_substitute';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function substitute_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'substitute_user_id', 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
