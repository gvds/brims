<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Assay extends Model
{
    /** @use HasFactory<\Database\Factories\AssayFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(
            Project::class,
            Study::class,
            'id',        // Foreign key on Study table
            'id',        // Foreign key on Project table
            'study_id',  // Local key on Assay table
            'project_id' // Local key on Study table
        );
    }

    public function assaydefinition(): BelongsTo
    {
        return $this->belongsTo(AssayDefinition::class);
    }

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected function casts(): array
    {
        return [
            'additional_fields' => 'json',
            'assayfiles' => 'array',
        ];
    }
}
