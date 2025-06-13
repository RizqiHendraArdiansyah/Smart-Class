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
        Schema::create('t_kelas_det', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')
                ->constrained('m_kelas')
                ->onDelete('cascade');
            // Relasi ke User
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Tambahkan kolom lain jika perlu (e.g., role dalam kelas)
            // $table->string('role')->default('student');

            $table->timestamps(); // Waktu user join kelas
            $table->softDeletes();
            // Pastikan user hanya bisa join satu kelas sekali
            $table->unique(['class_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kelas_det');
    }
};
