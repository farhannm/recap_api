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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_role')->default('2');
            $table->string('nama', 100)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telp', 14)->unique();
            $table->longText('alamat');
            $table->string('jabatan');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();

            
            $table->foreign('id_role')->references('id')->on('roles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
