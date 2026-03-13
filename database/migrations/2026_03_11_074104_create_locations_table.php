<?php

use App\Models\Specimen;
use App\Models\VirtualUnit;
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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VirtualUnit::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('rack');
            $table->string('box', 3);
            $table->unsignedSmallInteger('position');
            $table->boolean('used')->default(0);
            $table->boolean('virgin')->default(1);
            $table->string('barcode', 30)->nullable();
            $table->foreignIdFor(Specimen::class)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
