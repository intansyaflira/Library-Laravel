<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanBukuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman_buku', function (Blueprint $table) {
            $table->bigIncrements('id_peminjaman_buku');
            $table->unsignedBigInteger('id_siswa');
            $table->date('tanggal_pinjam');
            $table->string('tanggal_kembali');
            
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman_buku');
    }
}
