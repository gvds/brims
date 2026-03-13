<?php

use App\Models\Location;
use App\Models\StorageConsolidation;
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
        Schema::create('relocations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StorageConsolidation::class)->constrained()->onDelete('cascade');
            $table->string('barcode', 30);
            $table->foreignIdFor(Location::class, 'source_location_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Location::class, 'destination_location_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relocations');
    }
};
