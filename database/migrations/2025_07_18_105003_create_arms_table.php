<?php

use App\Models\Project;
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
        Schema::create('arms', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 50);
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('redcap_arm_id')->nullable();
            $table->unsignedTinyInteger('arm_num');
            $table->boolean('manual_enrol')->default(0);
            $table->string('switcharms', 100)->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'name']);
            $table->unique(['project_id', 'arm_num']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arms');
    }
};
