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
        Schema::create('t_kegiatan', function (Blueprint $table) {
            $table->id('id_kegiatan');
            $table->string('nama_kegiatan');
            $table->text('deskripsi_kegiatan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->date('tanggal_acara')->nullable();
            $table->string('tempat_kegiatan')->nullable();
            $table->enum('jenis_kegiatan', ['Kegiatan JTI', 'Kegiatan Non-JTI']);
            $table->unsignedBigInteger('id_user');

            // Add foreign key constraint
            $table->foreign('id_user')
                ->references('id_user')
                ->on('t_user')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kegiatan');
    }
};