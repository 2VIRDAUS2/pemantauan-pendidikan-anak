<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('kelas', 10);
            $table->string('hari', 20);
            $table->string('jam_mulai', 5);
            $table->string('jam_selesai', 5);
            $table->string('mata_pelajaran');
            $table->foreignId('guru_id')->constrained('users');
            $table->timestamps();

            $table->index(['kelas', 'hari']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajarans');
    }
};
