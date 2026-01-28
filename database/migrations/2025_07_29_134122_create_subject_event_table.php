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
        Schema::create('subject_event', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Event::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('iteration')->default(1);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('labelstatus')->default(0);
            $table->date('eventDate')->nullable();
            $table->date('minDate')->nullable();
            $table->date('maxDate')->nullable();
            $table->date('logDate')->nullable();
            $table->timestamps();

            $table->unique(['subject_id', 'event_id', 'iteration'], 'subject_event_iteration_unique');
            $table->index(['project_id', 'labelstatus', 'eventDate'], 'subject_event_label_project_date_idx');
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
