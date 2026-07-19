<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained()->cascadeOnDelete();
            $table->string('mata_pelajaran');
            $table->float('skor', 5, 2);
            $table->timestamps();

            $table->unique(['siswa_id', 'mata_pelajaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
