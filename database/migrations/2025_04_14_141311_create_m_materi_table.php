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
        Schema::create('m_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')
                ->constrained('m_modul') // merujuk ke tabel 'modules'
                ->onDelete('cascade'); // Jika modul dihapus, materi terkait ikut terhapus
            $table->string('title'); // Judul Materi (e.g., "Pengenalan Variabel")
            $table->string('type'); // Tipe materi: 'text', 'video', 'pdf', 'link'
            $table->longText('content'); // Isi materi (bisa teks, URL video, path file PDF)
            $table->unsignedInteger('order')->default(0); // Urutan tampil materi dalam modul
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_materi');
    }
};
