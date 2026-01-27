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
        Schema::table('subject_event', function (Blueprint $table): void {
            $table->index(['project_id', 'labelstatus', 'eventDate'], 'subject_event_label_project_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_event', function (Blueprint $table): void {
            $table->dropIndex('subject_event_label_project_date_idx');
        });
    }
};
