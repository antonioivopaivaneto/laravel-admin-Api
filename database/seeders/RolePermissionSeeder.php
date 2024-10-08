<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();
        $admin = Role::whereName('Admin')->first();

        foreach ($permissions as $permission) {
            DB::table('role_permission')->insert([
                'role_id' => $admin->id,
                'permission_id' => $permission->id
            ]);
        }

        $editor = Role::whereName('Editor')->first();

        foreach ($permissions as $permission) {
            if (!in_array($permission->name, ['edit_roles'])) {
                DB::table('role_permission')->insert([
                    'role_id' => $editor->id,
                    'permission_id' => $permission->id
                ]);
            }
        }
        $viewer = Role::whereName('Viewer')->first();
        $viewerRoles = [
            'view_users',
            'view_roles',
            'view_products',
            'view_orders',
        ];

        foreach ($permissions as $permission) {
            if (in_array($permission->name,$viewerRoles)) {
                DB::table('role_permission')->insert([
                    'role_id' => $viewer->id,
                    'permission_id' => $permission->id
                ]);
            }
        }
    }
}
