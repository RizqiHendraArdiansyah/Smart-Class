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
        Schema::create('t_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')
                ->constrained('m_modul')
                ->onDelete('cascade');
            $table->date('deadline');
            $table->enum('is_aktif', ['aktif', 'tutup', 'datang']);
            $table->string('title'); // Judul Kuis (e.g., "Kuis Aljabar Bab 1")
            $table->text('description')->nullable(); // Deskripsi atau instruksi kuis
            $table->unsignedInteger('time_limit_minutes')->nullable(); // Batas waktu pengerjaan (opsional)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kuis');
    }
};
