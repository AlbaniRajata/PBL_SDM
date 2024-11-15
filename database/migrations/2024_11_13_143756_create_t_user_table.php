<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('username')->index();
            $table->string('nama');
            $table->string('email')->index();
            $table->string('password');
            $table->string('NIP')->nullable();
            $table->enum('level', ['admin', 'pimpinan', 'dosen', 'dosenAnggota', 'dosenPIC']);
            $table->decimal('poin', 3, 1)->nullable();
            $table->timestamps();
        });

        DB::table('t_user')->where('level', 'dosenAnggota')->update(['poin' => 0.5]);
        DB::table('t_user')->where('level', 'dosenPIC')->update(['poin' => 2.0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_user');
    }
};