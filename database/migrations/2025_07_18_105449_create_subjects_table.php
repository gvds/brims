<?php

use App\Models\Arm;
use App\Models\Project;
use App\Models\Site;
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
        Schema::create('subjects', function (Blueprint $table): void {
            $table->id();
            $table->string('subjectID', 10)->unique();
            $table->foreignIdFor(Project::class)->constrained();
            $table->foreignIdFor(Site::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('firstname', 20)->nullable();
            $table->string('lastname', 30)->nullable();
            $table->json('address')->nullable();
            $table->date('enrolDate')->nullable();
            $table->foreignIdFor(Arm::class)->nullable()->constrained();
            $table->date('armBaselineDate')->nullable();
            $table->foreignIdFor(Arm::class, 'previous_arm_id')->nullable()->constrained();
            $table->date('previousArmBaselineDate')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
