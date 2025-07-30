<?php

use App\Models\Event;
use App\Models\Subject;
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
        Schema::create('subject_event', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Event::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('iteration')->default(1);
            $table->unsignedTinyInteger('eventstatus')->default(0);
            $table->unsignedTinyInteger('labelstatus')->default(0);
            $table->date('eventDate')->nullable();
            $table->date('minDate')->nullable();
            $table->date('maxDate')->nullable();
            $table->date('logDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_event');
    }
};
