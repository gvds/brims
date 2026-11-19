<?php

namespace App\Models;

use App\Enums\StudyRoles;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StudyMembers extends Pivot
{
    protected $table = 'study_member';

    protected $guarded = ['id'];

    protected $casts = [
        'role' => StudyRoles::class,
    ];
}
