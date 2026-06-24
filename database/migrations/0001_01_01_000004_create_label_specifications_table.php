<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('label_specifications', function (Blueprint $table) {
            $table->string('format', 30)->primary();
            $table->string('paper-size', 20);
            $table->string('metric', 10);
            $table->decimal('marginLeft', 8, 3);
            $table->decimal('marginTop', 8, 3);
            $table->unsignedTinyInteger('NX');
            $table->unsignedTinyInteger('NY');
            $table->decimal('SpaceX', 8, 3);
            $table->decimal('SpaceY', 8, 3);
            $table->decimal('width', 8, 3);
            $table->decimal('height', 8, 3);
            $table->unsignedTinyInteger('font-size');
            $table->unsignedTinyInteger('padding')->nullable();
        });

        $specifications = [
            ['format' => '5160',       'paper-size' => 'letter',    'metric' => 'mm',    'marginLeft' => 1.762,    'marginTop' => 10.7,    'NX' => 3,    'NY' => 10,    'SpaceX' => 3.175,    'SpaceY' => 0,        'width' => 66.675,    'height' => 25.4,    'font-size' => 8],
            ['format' => '5161',       'paper-size' => 'letter',    'metric' => 'mm',    'marginLeft' => 0.967,    'marginTop' => 10.7,    'NX' => 2,    'NY' => 10,    'SpaceX' => 3.967,    'SpaceY' => 0,        'width' => 101.6,    'height' => 25.4,    'font-size' => 8],
            ['format' => '5162',       'paper-size' => 'letter',    'metric' => 'mm',    'marginLeft' => 0.97,    'marginTop' => 20.224,    'NX' => 2,    'NY' => 7,    'SpaceX' => 4.762,    'SpaceY' => 0,        'width' => 100.807,    'height' => 35.72,    'font-size' => 8],
            ['format' => '5163',       'paper-size' => 'letter',    'metric' => 'mm',    'marginLeft' => 1.762,    'marginTop' => 10.7,     'NX' => 2,    'NY' => 5,    'SpaceX' => 3.175,    'SpaceY' => 0,        'width' => 101.6,    'height' => 50.8,    'font-size' => 8],
            ['format' => '5164',       'paper-size' => 'letter',    'metric' => 'in',    'marginLeft' => 0.148,    'marginTop' => 0.5,     'NX' => 2,    'NY' => 3,    'SpaceX' => 0.2031,    'SpaceY' => 0,        'width' => 4.0,        'height' => 3.33,    'font-size' => 12],
            ['format' => '8600',       'paper-size' => 'letter',    'metric' => 'mm',    'marginLeft' => 7.1,     'marginTop' => 19,         'NX' => 3,     'NY' => 10, 'SpaceX' => 9.5,    'SpaceY' => 3.1,     'width' => 66.6,     'height' => 25.4,    'font-size' => 8],
            ['format' => 'L7163',      'paper-size' => 'A4',        'metric' => 'mm',    'marginLeft' => 5,        'marginTop' => 15,         'NX' => 2,    'NY' => 7,    'SpaceX' => 25,        'SpaceY' => 0,        'width' => 99.1,    'height' => 38.1,    'font-size' => 9],
            ['format' => '3422',       'paper-size' => 'A4',        'metric' => 'mm',    'marginLeft' => 0,        'marginTop' => 8.5,     'NX' => 3,    'NY' => 8,    'SpaceX' => 0,        'SpaceY' => 0,        'width' => 70,        'height' => 35,        'font-size' => 9],
            ['format' => 'L7651',      'paper-size' => 'A4',        'metric' => 'mm',    'marginLeft' => 5,        'marginTop' => 11,        'NX' => 5,    'NY' => 13, 'SpaceX' => 2,        'SpaceY' => 0,        'width' => 38.1,    'height' => 21.1,    'font-size' => 9],
        ];

        DB::table('label_specifications')->insert($specifications);

        $specifications_mod = [
            ['format' => 'L7651_mod',  'paper-size' => 'A4',        'metric' => 'mm',    'marginLeft' => 5,        'marginTop' => 11,         'NX' => 5,    'NY' => 13,    'SpaceX' => 3.2,    'SpaceY' => 0.1,    'width' => 38.1,    'height' => 21.1,    'font-size' => 8,    'padding' => 1],
        ];

        DB::table('label_specifications')->insert($specifications_mod);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_specifications');
    }
};
