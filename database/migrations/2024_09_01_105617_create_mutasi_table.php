<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMutasiTable extends Migration
{
    public function up()
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('jenis_mutasi')->default('Masuk'); // Set default value
            $table->integer('jumlah')->nullable();
            $table->unsignedBigInteger('id_barang')->nullable();
            $table->unsignedBigInteger('id_pengguna')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mutasi');
    }
}
