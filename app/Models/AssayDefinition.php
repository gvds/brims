<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssayDefinition extends Model
{
    /** @use HasFactory<\Database\Factories\AssayDefinitionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'additional_fields' => 'json',
        ];
    }
}
