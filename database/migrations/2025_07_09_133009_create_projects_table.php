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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained();
            $table->foreignIdFor(User::class, 'leader_id')->constrained();
            $table->string('identifier', 100)->unique();
            $table->string('title', 100)->unique();
            $table->text('description')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('public_release_date')->nullable();
            $table->string('subjectID_prefix', 10);
            $table->unsignedTinyInteger('subjectID_digits');
            $table->string('storageProjectName', 40)->nullable();
            // $table->foreignIdFor(Label::class)->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
