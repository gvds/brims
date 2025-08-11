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
        Schema::create('protocols', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->foreignIdFor(Team::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('type_term_accession_number')->nullable();
            $table->string('type_term_reference')->nullable();
            $table->string('description');
            $table->string('uri');
            $table->string('version');
            $table->string('parameters_names');
            $table->string('parameters_term_accession_number');
            $table->string('parameters_term_reference');
            $table->string('components_names');
            $table->string('components_type');
            $table->string('components_type_term_accession_number');
            $table->string('components_type_term_reference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocols');
    }
};
