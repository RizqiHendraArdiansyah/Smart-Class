<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_kuis', function (Blueprint $table) {
            $table->boolean('is_passcode_enabled')->default(false)->after('time_limit_minutes');
            $table->string('passcode')->nullable()->after('is_passcode_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('t_kuis', function (Blueprint $table) {
            $table->dropColumn(['is_passcode_enabled', 'passcode']);
        });
    }
};
