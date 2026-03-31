<?php

use App\Models\Specimen;
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
        Schema::create('specimen_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Specimen::class)->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('previous_status');
            $table->unsignedTinyInteger('new_status');
            $table->foreignIdFor(User::class, 'changed_by')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimen_logs');
    }
};
