<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id('id_file');
            $table->unsignedBigInteger('id_kegiatan');
            $table->string('nama_file');
            $table->string('kategori');
            $table->bigInteger('ukuran_file');
            $table->string('tipe_file');
            $table->string('file_path');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_kegiatan')->references('id_kegiatan')->on('t_kegiatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}