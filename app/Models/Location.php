<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function virtualUnit()
    {
        return $this->belongsTo(VirtualUnit::class, 'virtual_unit_id');
    }

    public function free(): bool
    {
        return $this->update([
            'used' => false,
            'specimen_id' => null,
            'barcode' => null,
            'virgin' => false,
        ]);
    }
}
