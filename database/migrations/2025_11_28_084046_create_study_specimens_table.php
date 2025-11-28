<?php

use App\Models\Specimen;
use App\Models\Study;
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
        Schema::create('study_specimens', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Study::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Specimen::class)->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['study_id', 'specimen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_specimens');
    }
};
