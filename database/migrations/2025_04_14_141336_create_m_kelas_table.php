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
        Schema::create('m_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Kelas (e.g., "Kelas 10A")
            $table->integer('semester')->default(1); // Nama Kelas (e.g., "Kelas 10A")
            $table->year('batch_year'); // Tahun Angkatan (e.g., 2024)
            $table->string('offering')->nullable(); // Offering/Rombel (e.g., "A", "B", atau bisa digabung di name)
            $table->text('description')->nullable();
            $table->boolean('is_aktif')->default(1);
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kelas');
    }
};
