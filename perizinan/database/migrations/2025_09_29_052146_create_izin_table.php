<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIzinTable extends Migration
{
    public function up()
    {
        Schema::create('izin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_izin', ['Sakit', 'Kegiatan', 'Lainnya']);
            $table->text('alasan')->nullable();
            $table->enum('status', [
                'pending_wali_kelas',
                'ditolak_wali_kelas',
                'disetujui_wali_kelas',
                'pending_keamanan',
                'disetujui_keamanan',
                'ditolak_keamanan'
            ])->default('pending_wali_kelas');
            $table->text('catatan')->nullable();
            // Tanggal resmi yang ditetapkan Keamanan
            $table->date('tgl_mulai_disetujui')->nullable();
            $table->date('tgl_selesai_disetujui')->nullable();
            $table->enum('status_lapor', ['belum_lapor', 'sudah_lapor'])->default('belum_lapor');
            // Denda otomatis
            $table->integer('denda')->default(0);
            $table->enum('status_denda', ['belum_dibayar', 'dibayar'])->default('belum_dibayar');
            $table->timestamp('tanggal_dibayar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('izin');
    }
}
