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
        Schema::create('publications', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->onDelete('cascade');
            $table->string('pubmed_id')->nullable()->unique();
            $table->string('doi')->nullable()->unique();
            $table->text('title');
            $table->json('authors');
            $table->year('publication_date')->nullable();
            $table->string('publication_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
