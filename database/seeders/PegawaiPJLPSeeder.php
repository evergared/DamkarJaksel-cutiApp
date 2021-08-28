<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PegawaiPJLPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        
        for($i = 0; $i < 100; $i++)
        {
            DB::table('pegawai_pjlp')->insert([
                'nip' => $faker->nik,
                'no_pjlp' => $this->randomNrk(),
                'nama' => $faker->name,
                'golongan' => "golongan",
                'jabatan' => $this->randomJabatan(),
                'nip_atasan' => $faker->nik(),
                'pendidikan' => $this->randomPendidikan(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

    }

    function randomJabatan()
    {
        $id = random_int(0,3);

        switch($id)
        {
            case 0: return "Damkar";
            case 1: return "Kebersihan";
            case 2: return "Keamanan";
            case 3: return "Mekanikal Elektrik";
            default : return "Damkar";
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
