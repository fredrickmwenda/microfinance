<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [
            [
                'group_name' => 'RO Dashboard',
                'permissions' => [
                    'agent.dashboard',
                ]
            ]
        ];
        //get role called ro and assign permissions to it
        $role = Role::findByName('RO');
        // dd($role);
        foreach ($permissions as $key => $permission) {
            foreach ($permission['permissions'] as $key => $perm) {
                // dd($perm, $permission['group_name']);
                $permission = Permission::create(['name' => $perm, 'group_name' => $permission['group_name']]);
                $role->givePermissionTo($permission);
                $permission->assignRole($role);
                // assign all users with role RO to this permission
                $role->users()->sync($role->users()->pluck('id')->toArray(), false);
            }
        }



        








        













  



    // $permissions = [
    //     [
        //         'group_name' => 'report',
        //         'permissions' => [
        //             'report.transaction',
        //             'report.disburse',
        //             'report.loan',
        //             'report.loan.repayment',
        //             'report.customer',
        //             'report.performance',
        //             'report.loan.arrears',
        //         ],

        //     ],
        // ];


        //update permissions of admin
        // $admin = \App\Models\User::where('email', 'admin@admin.com')->first();
        // //get the name of his role
        // $role = $admin->getRoleNames()->first();
        // //get the role
        // $role = \Spatie\Permission\Models\Role::findByName($role);
        // //update this new permissions to the role
        // foreach ($permissions as $key => $value) {
        //     foreach ($value['permissions'] as $key => $per) {
        //         //create permission
        //         $permission = \Spatie\Permission\Models\Permission::create(['name' => $per, 'group_name' => $value['group_name']]);
        //         //assign this new permission to the role
        //         $role->givePermissionTo($permission);
        //         $permission->assignRole($role);
        //         // $admin->assignRole($role);
        //     }
        // }
 
    }
}
