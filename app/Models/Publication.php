<?php

namespace App\Models;

use App\Enums\PublicationStatus;
use Database\Factories\PublicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    /** @use HasFactory<PublicationFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'authors' => 'array',
            'publication_status' => PublicationStatus::class,
        ];
    }
}
