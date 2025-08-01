<?php

use App\Models\Team;
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
        Schema::create('studydesigns', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('type', 100)->unique();
            $table->string('type_term_accession_number')->nullable();
            $table->string('type_term_reference', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studydesigns');
    }
};
