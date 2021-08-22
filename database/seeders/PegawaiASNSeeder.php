<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PegawaiASNSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        
        for($i = 0; $i < 10; $i++)
        {
            DB::table('pegawai_asn')->insert([
                'nip' => $faker->nik,
                'nrk' => $this->randomNrk(),
                'nama' => $faker->name,
                'golongan' => "golongan",
                'jabatan' => $this->randomJabatan(),
                'nip_atasan' => $faker->nik(),
                'pendidikan' => $this->randomPendidikan()
            ]);
        }

    }

    function randomJabatan()
    {
        $id = random_int(0,2);

        switch($id)
        {
            case 0: return "Anggota";
            case 1: return "Kepala Regu";
            case 2: return "Kepala Pleton";
            case 3: return "Kepala Sektor";
            case 4: return "Kepala Sudin";
            default : return "Anggota";
        }
    }

    function randomPendidikan()
    {
        $id = random_int(0,3);

        switch($id)
        {
            case 0: return "SMA/SMK";
            case 1: return "D3";
            case 2: return "S1";
            case 3: return "S2";
            default : return "SMA/SMK";
        }
    }

    function randomNrk()
    {
        
        $id = '';

        for($i = 0;$i < 6;$i++)
        {
            $id .= (string)random_int(0,9);
        }

        return $id;
    }
}
