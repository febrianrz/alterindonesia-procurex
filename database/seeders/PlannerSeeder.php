<?php

namespace Database\Seeders;

use App\Models\GeneralPlanner;
use App\Models\SpecificPlanner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $generalPlanner = [
            'Divisi IT',
            'Divisi Pemasaran',
            'Divisi Kayasa',
            'Divisi Pengadaan',
            'Divisi Industri'
        ];
        foreach ($generalPlanner as $planner){
            GeneralPlanner::updateOrCreate([
                'name'  => $planner
            ],[
                'is_active' => true
            ]);
        }

        $specificPlanner = [
            'Application'
        ];

        foreach ($specificPlanner as $planner){
            SpecificPlanner::updateOrCreate([
                'name'  => $planner
            ],[
                'is_active' => true
            ]);
        }
    }
}
