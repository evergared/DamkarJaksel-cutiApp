<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiASNSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Pegawai_ASN', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->string('nip',30)->nullable(false);
            $table->string('nrk')->nullable(false);
            $table->string('nama')->nullable(false);
            $table->string('golongan');
            $table->string('jabatan');
            $table->string('nip_atasan',30); // foreign key
            $table->string('pendidikan');

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
        Schema::dropIfExists('Pegawai_ASN');
    }
}
