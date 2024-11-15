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
        Schema::create('t_sdm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_kegiatan');
            $table->unsignedBigInteger('id_dokumen');

            $table->foreign('id_kegiatan')
            ->references('id_kegiatan')
            ->on('t_kegiatan')
            ->onDelete('cascade');

            $table->foreign('id_user')
            ->references('id_user')
            ->on('t_user')
            ->onDelete('cascade');

            $table->foreign('id_dokumen')
            ->references('id_dokumen')
            ->on('t_dokumen')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_sdm', function (Blueprint $table) {
            //
        });
    }
};
