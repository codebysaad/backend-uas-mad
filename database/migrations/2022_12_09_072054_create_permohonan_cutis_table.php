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
        Schema::create('permohonan_cutis', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('alasan');
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->boolean('status');
            $table->date('tgl_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permohonan_cutis');
    }
};
