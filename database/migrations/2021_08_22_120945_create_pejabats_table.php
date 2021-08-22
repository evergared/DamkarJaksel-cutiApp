<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePejabatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO : imagine the possibility, kemungkinan tabel ini akan berubah

        Schema::create('pejabat', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->string('nip',30)->nullable(false);
            $table->string('nrk');
            $table->string('nama')->nullable(false);
            $table->string('golongan');
            $table->string('jabatan');
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
        Schema::dropIfExists('pejabat');
    }
}
