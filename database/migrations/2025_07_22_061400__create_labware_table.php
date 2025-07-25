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
            $table->string('name', 30);
            $table->string('barcodeFormat', 50);
            $table->foreignIdFor(Project::class)->nullable();
            $table->timestamps();
        });

        Labware::create([
            'id' => 4,
            'name' => 'MGIT',
            'barcodeFormat' => '^\d{12}$',
        ]);
        Labware::create([
            'id' => 7,
            'name' => 'Xpert',
            'barcodeFormat' => '',
        ]);
        // Labware::create([
        //     'id' => 1,
        //     'name' => 'Adhesive',
        //     'barcodeFormat' => '^(A\d{7}|\d{4}|[A-Z]\d{3}[A-Z]\d{4})$',
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labware');
    }
};
