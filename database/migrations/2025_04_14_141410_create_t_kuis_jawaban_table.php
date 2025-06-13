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
        Schema::create('t_kuis_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')
                ->constrained('t_kuis_coba')
                ->onDelete('cascade');
            // Relasi ke Pertanyaan
            $table->foreignId('question_id')
                ->constrained('m_pertanyaan')
                ->onDelete('cascade'); // Atau restrict

            $table->text('answer'); // Jawaban yang diberikan user
            $table->boolean('is_correct')->nullable(); // Ditentukan saat validasi jawaban
            $table->unsignedInteger('points_awarded')->default(0); // Poin yang didapat untuk jawaban ini
            $table->softDeletes();

            // User hanya bisa menjawab 1 kali per pertanyaan per attempt
            $table->unique(['quiz_attempt_id', 'question_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kuis_jawaban');
    }
};
