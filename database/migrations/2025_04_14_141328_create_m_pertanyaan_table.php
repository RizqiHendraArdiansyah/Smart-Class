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
        Schema::create('m_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')
                ->constrained('t_kuis')
                ->onDelete('cascade');

            $table->text('text'); // Teks pertanyaan
            $table->string('type')->default('multiple_choice'); // Tipe soal: 'multiple_choice', 'true_false', 'short_answer'
            $table->json('options')->nullable(); // Pilihan jawaban (untuk multiple_choice), format JSON: {"A": "Jawaban A", "B": "Jawaban B"}
            $table->text('correct_answer'); // Kunci jawaban (bisa 'A', 'B', 'true', atau teks jawaban singkat)
            $table->string('difficulty_level'); // Tingkat kesulitan: 'easy', 'medium', 'hard'
            $table->unsignedInteger('points'); // Nilai/poin untuk soal ini (berdasarkan difficulty_level)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_pertanyaan');
    }
};
