<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiPJLPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Pegawai_PJLP', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->string('nip',30)->nullable(false);
            $table->string('no_pjlp');
            $table->string('nama')->nullable(false);
            $table->string('golongan');
            $table->string('jabatan');
            $table->string('nip_atasan',30); //->nullable(false);// foreign
            $table->string('pendidikan');
            $table->timestamps();

            $table->primary('nip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Pegawai_PJLP');
    }
}
