<?php

namespace App\Models;

use App\Models\Scopes\AssayDefinitionScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([AssayDefinitionScope::class])]
class AssayDefinition extends Model
{
    /** @use HasFactory<\Database\Factories\AssayDefinitionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'additional_fields' => 'json',
        ];
    }
}
