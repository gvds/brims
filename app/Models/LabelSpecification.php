<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelSpecification extends Model
{
    // protected $table = 'label_specifications';

    protected $primaryKey = 'format';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'format',
        'paper-size',
        'metric',
        'marginLeft',
        'marginTop',
        'NX',
        'NY',
        'SpaceX',
        'SpaceY',
        'width',
        'height',
        'font-size',
        'padding'
    ];
}
