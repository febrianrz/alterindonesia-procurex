<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            'A000'  => 'PT Pupuk Indonesia',
            'B000'  => 'PT Petrokimia Gresik',
            'C000'  => 'PT Pupuk Kujang',
            'D000'  => 'PT Pupuk Kalimantan Timur',
            'E000'  => 'PT Pupuk Iskandar Muda',
            'F000'  => 'PT Pupuk Sriwidjaja',
            'G000'  => 'PT Rekayasa Industri',
            'H000'  => 'PT Mega Eltra',
            'I000'  => 'PT PI Logistik',
            'J000'  => 'PT PI Utilitas',
            'JA00'  => 'PT Kaltim Daya Mandiri',
        ];

        foreach ($companies as $key => $value) {
            \App\Models\Company::firstOrCreate([
                'code'  => $key,
            ],[
                'name'  => $value,
                'description'   => '-',
                'is_active' => true,
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
