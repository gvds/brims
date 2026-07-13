<?php

namespace App\Models;

use CountryEnums\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    /** @use HasFactory<\Database\Factories\InstitutionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function physicalUnits(): HasMany
    {
        return $this->hasMany(PhysicalUnit::class);
    }

    protected function casts(): array
    {
        return [
            'country' => Country::class,
        ];
    }
}
