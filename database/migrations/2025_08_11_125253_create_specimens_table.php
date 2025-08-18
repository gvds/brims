<?php

use App\Models\Site;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\SubjectEvent;
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
        Schema::create('specimens', function (Blueprint $table) {
            $table->id();
            $table->string('barcode', 20);
            $table->foreignIdFor(SubjectEvent::class, 'subject_event_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Specimentype::class, 'specimenType_id')->constrained();
            $table->foreignIdFor(Site::class, 'site_id')->constrained();
            $table->unsignedTinyInteger('status')->default(0)->constrained();
            // $table->foreignIdFor(Location::class)->constrained();
            $table->unsignedTinyInteger('aliquot');
            $table->double('volume', 8, 2)->nullable();
            $table->string('volumeUnit')->nullable();
            $table->unsignedTinyInteger('thawcount')->default(0);
            $table->foreignIdFor(User::class, 'loggedBy_id')->constrained();
            $table->dateTime('loggedAt');
            $table->foreignIdFor(User::class, 'loggedOutBy_id')->nullable()->constrained();
            $table->foreignIdFor(User::class, 'usedBy_id')->nullable()->constrained();
            $table->dateTime('usedAt')->nullable()->nullable();
            $table->foreignIdFor(Specimen::class, 'parentSpecimen_id')->nullable()->constrained('specimens');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimens');
    }
};
