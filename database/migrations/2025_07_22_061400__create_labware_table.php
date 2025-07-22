<?php

use App\Models\Labware;
use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('labware', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15);
            $table->boolean('preregister')->default(0);
            $table->string('barcodeFormat', 50);
            $table->set('registration', ['range', 'single'])->default('range');
            $table->foreignIdFor(Project::class)->nullable();
            $table->timestamps();
        });

        Labware::create([
            'id' => 1,
            'name' => 'Adhesive',
            'preregister' => 1,
            'barcodeFormat' => '^(A\d{7}|\d{4}|[A-Z]\d{3}[A-Z]\d{4})$',
            'registration' => 'range'
        ]);
        Labware::create([
            'id' => 2,
            'name' => 'FluidX 260ul',
            'preregister' => 0,
            'barcodeFormat' => '^SU\d{8}$',
            'registration' => 'single'
        ]);
        Labware::create([
            'id' => 3,
            'name' => 'FluidX 500ul',
            'preregister' => 0,
            'barcodeFormat' => '^(FD|SU)\\d{8}$',
            'registration' => 'single'
        ]);
        Labware::create([
            'id' => 4,
            'name' => 'MGIT',
            'preregister' => 0,
            'barcodeFormat' => '^\d{12}$',
            'registration' => 'single'
        ]);
        Labware::create([
            'id' => 5,
            'name' => 'Pre 2ml',
            'preregister' => 1,
            'barcodeFormat' => '^(G\d{9}|E\d{7}|\d{8})$',
            'registration' => 'range'
        ]);
        Labware::create([
            'id' => 6,
            'name' => 'Pre 5ml',
            'preregister' => 0,
            'barcodeFormat' => '',
            'registration' => 'range'
        ]);
        Labware::create([
            'id' => 7,
            'name' => 'Xpert',
            'preregister' => 0,
            'barcodeFormat' => '',
            'registration' => 'single'
        ]);
        Labware::create([
            'id' => 8,
            'name' => 'FluidX 1ml',
            'preregister' => 0,
            'barcodeFormat' => '^SU\d{8}$',
            'registration' => 'range'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labware');
    }
};
