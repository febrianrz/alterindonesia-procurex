<?php

namespace Database\Seeders;

use App\Models\AuthClient;
use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gateway = [
            'enable'    => true,
            'host'      => 'http://8.215.70.78:7000',
            'public_key'=> ''
        ];
        Config::updateOrCreate([
            'code'  => Config::$GATEWAY_CODE
        ],[
            'data'  => $gateway
        ]);
        $smtp = [
            'host'      => '',
            'port'      => '',
            'username'  => '',
            'password'  => '',
            'encryption'=> '',
            'email_from'=> '',
            'name_from'=> ''
        ];
        Config::updateOrCreate([
            'code'  => Config::$SMTP_CODE
        ],[
            'data'  => $smtp
        ]);
        $sap = [
            'host'      => '',
            'username'      => '',
            'password'  => ''
        ];
        Config::updateOrCreate([
            'code'  => Config::$SAP_CODE
        ],[
            'data'  => $sap
        ]);

        AuthClient::firstOrCreate([
            'id'    => 1,
        ],[
            'name'  => 'App',
            'is_active' => true,
            'secret'    => 'TiT6vruAOngLDu29PBX2opRSYUdF1TbG8GAoRONoeJLwkVSAwAr8MmLzleTL'
//            'secret'    => Str::random(60)
        ]);
    }
}
