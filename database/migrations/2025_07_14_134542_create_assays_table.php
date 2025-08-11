<?php

use App\Models\AssayDefinition;
use App\Models\Study;
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
        Schema::create('assays', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->foreignIdFor(Study::class)->constrained();
            $table->foreignIdFor(AssayDefinition::class, 'assaydefinition_id')->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('technologyPlatform', 50);
            $table->json('additional_fields')->nullable();
            $table->string('assayfile', 150)->nullable();
            $table->string('assayfilename')->nullable();
            $table->text('uri')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assays');
    }
};
