<?php

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
        Schema::create('manifests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Site::class, 'sourceSite_id')->constrained();
            $table->foreignIdFor(Site::class, 'destinationSite_id')->constrained();
            $table->date('shippedDate')->nullable();
            $table->foreignIdFor(User::class, 'receivedBy_id')->nullable()->constrained();
            $table->date('receivedDate')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifests');
    }
};
