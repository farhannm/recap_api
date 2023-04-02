<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_pekerjaan')->nullable();
            $table->string('nama_pekerjaan');
            $table->string('desc_pekerjaan');
            $table->string('bukti_pekerjaan')->nullable();
            $table->integer('jam_kerja');
            $table->date('tgl_kerja');
            $table->enum('tipe',['Progress Weekday', 'Lembur Weekday', 'Lembur Weekend']);
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_pekerjaan')->references('id')->on('pekerjaan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pekerjaan');
    }
};
