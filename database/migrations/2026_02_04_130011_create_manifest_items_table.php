<?php

use App\Models\Manifest;
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
        Schema::create('manifest_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Manifest::class)->constrained('manifest')->onDelete('cascade');
            $table->foreignIdFor(Specimen::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->unsignedTinyInteger('priorSampleStatus');
            $table->boolean('received')->default(false);
            $table->datetime('receivedTime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifest_items');
    }
};
