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
        Schema::create('t_poin', function (Blueprint $table) {
            $table->id('id_poin');
            $table->foreignId('id_kegiatan')->constrained('t_kegiatan', 'id_kegiatan');
            $table->foreignId('id_user')->constrained('t_user', 'id_user');
            $table->decimal('poin', 2, 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_t_poin');
    }
};
