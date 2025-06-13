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
        Schema::create('t_kuis_coba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Atau set null jika user dihapus tapi riwayat ingin disimpan
            // Relasi ke Kuis
            $table->foreignId('quiz_id')
                ->constrained('t_kuis')
                ->onDelete('cascade'); // Atau restrict jika kuis tidak boleh dihapus jika ada attempt

            $table->unsignedInteger('score')->nullable(); // Skor total (diisi setelah selesai)
            $table->timestamp('started_at')->useCurrent(); // Waktu mulai
            $table->timestamp('completed_at')->nullable(); // Waktu selesai
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kuis_coba');
    }
};
