<?php

namespace App\Models;

use App\Enums\SpecimenStatus;
use Illuminate\Database\Eloquent\Model;

class SpecimenLog extends Model
{

    protected $guarded = ['id'];

    public function specimen()
    {
        return $this->belongsTo(Specimen::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    protected function casts(): array
    {
        return [
            'previous_status' => SpecimenStatus::class,
            'new_status' => SpecimenStatus::class,
        ];
    }
}
