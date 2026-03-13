<?php

use App\Models\UnitDefinition;
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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UnitDefinition::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('section_number');
            $table->unsignedTinyInteger('rows');
            $table->unsignedTinyInteger('columns');
            $table->unsignedTinyInteger('boxes');
            $table->unsignedTinyInteger('positions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
