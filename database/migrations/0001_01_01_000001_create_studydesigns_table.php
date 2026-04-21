<?php

use App\Models\StudyDesign;
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
        Schema::create('studydesigns', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 100)->unique();
            $table->string('type_term_accession_number')->nullable();
            $table->string('type_term_reference', 50)->nullable();
            $table->timestamps();
        });

        StudyDesign::factory()
            ->makeMany([
                ['type' => 'Randomized Controlled Trial', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C46079', 'type_term_reference' => 'NCIT'],
                ['type' => 'Cohort Study', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C15208', 'type_term_reference' => 'NCIT'],
                ['type' => 'Case-Control Study', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C15197', 'type_term_reference' => 'NCIT'],
                ['type' => 'Cross-Sectional Study', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C53310', 'type_term_reference' => 'NCIT'],
                ['type' => 'Case Report', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C15362', 'type_term_reference' => 'NCIT'],
                ['type' => 'Case Series', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C16229', 'type_term_reference' => 'NCIT'],
                ['type' => 'Crossover Study', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C82637', 'type_term_reference' => 'NCIT'],
                ['type' => 'Systematic Review', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/OBI_0001958', 'type_term_reference' => 'OBI'],
                ['type' => 'Observational', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C215504', 'type_term_reference' => 'NCIT'],
                ['type' => 'Interventional', 'type_term_accession_number' => 'http://purl.obolibrary.org/obo/NCIT_C98388', 'type_term_reference' => 'NCIT'],
                ['type' => 'Other'],
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studydesigns');
    }
};
