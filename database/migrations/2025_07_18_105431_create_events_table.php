<?php

use App\Models\Arm;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->foreignIdFor(Arm::class)->constrained();
            $table->unsignedBigInteger('redcap_event_id')->nullable();
            $table->boolean('autolog')->default(0);
            $table->unsignedMediumInteger('offset')->unsigned()->nullable();
            $table->unsignedMediumInteger('offset_ante_window')->unsigned()->nullable();
            $table->unsignedMediumInteger('offset_post_window')->unsigned()->nullable();
            $table->unsignedTinyInteger('name_labels')->unsigned()->default(0);
            $table->unsignedTinyInteger('subject_event_labels')->unsigned()->default(0);
            $table->unsignedTinyInteger('study_id_labels')->unsigned()->default(0);
            $table->unsignedTinyInteger('event_order')->unsigned()->default(0);
            $table->boolean('repeatable')->default(0);
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
