<?php

use App\Models\UnitDefinition;
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
        Schema::create('physical_units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->foreignIdFor(UnitDefinition::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('serial', 30)->nullable();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean('available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_units');
    }
};
