<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssayDefinition extends Model
{
    /** @use HasFactory<\Database\Factories\AssayDefinitionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'additional_fields' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
