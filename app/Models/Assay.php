<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assay extends Model
{
    /** @use HasFactory<\Database\Factories\AssayFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    public function assaydefinition(): BelongsTo
    {
        return $this->belongsTo(Assaydefinition::class);
    }

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected function casts(): array
    {
        return [
            'additional_fields' => 'json',
        ];
    }
}
