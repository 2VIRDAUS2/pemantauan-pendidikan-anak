<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('kelas', 10)->default('1')->after('nama_siswa');
        });

        Schema::table('pengajuan_izins', function (Blueprint $table) {
            $table->longText('file_bukti_data')->after('alasan');
            $table->string('file_bukti_nama')->after('file_bukti_data');
            $table->string('file_bukti_mime')->after('file_bukti_nama');
            $table->dropColumn('file_bukti_path');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('kelas');
        });

        Schema::table('pengajuan_izins', function (Blueprint $table) {
            $table->string('file_bukti_path')->after('alasan');
            $table->dropColumn(['file_bukti_data', 'file_bukti_nama', 'file_bukti_mime']);
        });
    }
};
