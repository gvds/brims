<?php

use App\Models\PhysicalUnit;
use App\Models\Project;
use App\Models\Specimentype;
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
        Schema::create('virtual_units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->foreignIdFor(PhysicalUnit::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            // $table->foreignIdFor(Specimentype::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('storageSpecimenType', 50);
            $table->string('rack_extent', 10);
            $table->unsignedTinyInteger('startRack');
            $table->unsignedTinyInteger('endRack');
            $table->string('startBox', 3);
            $table->string('endBox', 3);
            $table->unsignedSmallInteger('rackCapacity');
            $table->unsignedSmallInteger('boxCapacity');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_units');
    }
};
