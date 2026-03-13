<?php

namespace App\Models;

use App\Enums\StorageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitDefinition extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'storageType' => StorageType::class,
        ];
    }

    /**
     * @return HasMany <Section>
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('section_number');
    }

    /**
     * @return HasMany <PhysicalUnit>
     */
    public function physicalunits(): HasMany
    {
        return $this->hasMany(PhysicalUnit::class);
    }
}
