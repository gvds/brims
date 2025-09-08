<?php

use App\Models\Labware;
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
        Schema::create('specimentypes', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->string('name', 50);
            $table->boolean('primary')->default(0);
            $table->tinyInteger('aliquots')->unsigned();
            $table->boolean('pooled')->default(0);
            $table->decimal('defaultVolume', 8, 2)->nullable();
            $table->string('volumeUnit', 10)->nullable();
            $table->boolean('store')->default(0);
            $table->json('transferDestinations')->nullable();
            $table->string('specimenGroup', 50)->nullable();
            $table->foreignIdFor(Labware::class)->nullable()->constrained();
            $table->string('storageDestination', 15)->nullable();
            $table->string('storageSpecimenType', 50)->nullable()->index();
            $table->foreignIdFor(Specimentype::class, 'parentSpecimenType_id')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimentypes');
    }
};
