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
        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->string('identifier', 100)->unique();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('public_release_date')->nullable();
            $table->string('studyfile', 150)->nullable();
            $table->string('studyfilename')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studies');
    }
};
