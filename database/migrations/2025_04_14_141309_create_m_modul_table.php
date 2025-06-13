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
        Schema::create('m_modul', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Modul (e.g., "Aljabar Dasar")
            $table->text('description')->nullable(); // Deskripsi singkat modul
            $table->integer('waktu')->nullable(); // Deskripsi singkat modul
            $table->unsignedInteger('order')->default(0); // Urutan tampil modul
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_modul');
    }
};
