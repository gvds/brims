<?php

use App\Models\Team;
use App\Models\User;
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
        Schema::create('assay_definitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('name', 50);
            $table->text('description');
            $table->boolean('active')->default(true);
            $table->string('measurementType', 50);
            $table->string('measurementTypeTermAccessionNumber', 50)->nullable();
            $table->string('measurementTypeTermSourceReference', 50)->nullable();
            $table->string('technologyType', 50);
            $table->string('technologyTypeTermAccessionNumber', 50)->nullable();
            $table->string('technologyTypeTermSourceReference', 50)->nullable();
            $table->json('additional_fields')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assay_definitions');
    }
};
