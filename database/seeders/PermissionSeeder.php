<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resoucePermissions = [
            'company',
            'role',
            'user',
            'general::planner',
            'specific::planner'
        ];
        $pagePermissions = [
            'Logs',
            'FilamentDebugger',
            'GatewaySettingPage'
        ];

      $resourcePrefix = [
          'view',
          'view_any',
          'create',
          'update',
          'delete',
          'delete_any',
          'force_delete',
          'force_delete_any',
          'publish',
          'replicate',
          'restore',
          'restore_any',
          'reorder',
      ];

      foreach($resoucePermissions as $p) {
        foreach ($resourcePrefix as $prefix) {
            Permission::updateOrCreate([
                'name'  => "{$prefix}_{$p}",
                'guard_name' => 'web'
            ],[
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);

        }
      }

      foreach ($pagePermissions as $item) {
          Permission::updateOrCreate([
              'name'  => "page_{$item}",
              'guard_name' => 'web'
          ],[
              'created_at'    => Carbon::now(),
              'updated_at'    => Carbon::now()
          ]);
      }

      $role = Role::find(1);
      $role->givePermissionTo(Permission::all());
    }
}
