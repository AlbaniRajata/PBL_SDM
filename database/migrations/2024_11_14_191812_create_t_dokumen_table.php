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
        Schema::create('t_dokumen', function (Blueprint $table) {
            $table->id('id_dokumen');
            $table->string('surat_tugas');
            $table->string('proposal');
            $table->string('bukti_pencairan');
            $table->string('dokumentasi');
            $table->string('dokumen_lpj');
            $table->string('riwayat');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_dokumen', function (Blueprint $table) {
            //
        });
    }
};
