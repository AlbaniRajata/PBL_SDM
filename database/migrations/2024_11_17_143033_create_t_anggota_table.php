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
        Schema::create('t_anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->unsignedBigInteger('id_kegiatan')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_jabatan_kegiatan')->nullable();
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('id_kegiatan', 'fk_kegiatan')->references('id_kegiatan')->on('t_kegiatan');
            $table->foreign('id_user', 'fk_user')->references('id_user')->on('t_user');
            $table->foreign('id_jabatan_kegiatan', 'fk_jabatan')->references('id_jabatan_kegiatan')->on('t_jabatan_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_anggota');
    }
};