<?php

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
        Schema::create('unit_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('orientation', 10);
            $table->string('sectionLayout', 10);
            $table->string('boxDesignation', 10);
            $table->string('storageType', 20)->index();
            $table->string('rackOrder', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_definitions');
    }
};
