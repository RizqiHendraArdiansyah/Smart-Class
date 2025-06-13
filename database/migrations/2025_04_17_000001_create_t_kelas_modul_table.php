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
        Schema::create('t_kelas_modul', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')
                ->constrained('m_kelas')
                ->onDelete('cascade');
            $table->foreignId('module_id')
                ->constrained('m_modul')
                ->onDelete('cascade');
            $table->date('start_date')->nullable(); // Tanggal mulai modul untuk kelas ini
            $table->date('end_date')->nullable(); // Tanggal selesai modul untuk kelas ini
            $table->boolean('is_active')->default(true); // Status aktif modul untuk kelas ini
            $table->timestamps();
            $table->softDeletes();

            // Memastikan satu modul hanya bisa diassign sekali ke satu kelas
            $table->unique(['class_id', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kelas_modul');
    }
};
