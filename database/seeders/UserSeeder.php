<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::find('A000');
        $user = \App\Models\User::firstOrCreate([
            'username'  => 'superadmin',
        ],
            [
                'company_code'=> $company->code,
                'name'      => 'Superadmin',
                'email'     => 'superadmin@app.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password'  => bcrypt('superadmin'),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);

        $role = Role::updateOrCreate([
            'id'    => '1'
        ],[
            'name'  => 'superadmin',
            'guard_name'=> 'web'
        ]);

        Role::updateOrCreate([
            'id'    => '2'
        ],[
            'name'  => 'staff',
            'guard_name'=> 'web'
        ]);

        $user->assignRole('superadmin');
    }
}
