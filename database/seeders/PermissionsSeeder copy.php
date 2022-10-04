<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'group_name' => 'report',
                'permissions' => [
                    'report.transaction',
                    'report.disburse',
                    'report.loan',
                    'report.loan.repayment',
                    'report.customer',
                    'report.performance',
                    'report.loan.arrears',
                ],
                // 'report.loan.arrears.loan',
                // 'report.loan.arrears.customer',
                // 'report.loan.arrears.branch',
                // 'report.loan.arrears.staff',

            ],


                // 'report.transaction.all',
                // 'report.transaction.user',
                // 'report.transaction.date',
                // 'report.transaction.date.all',
                // 'report.transaction.date.user',

                // [
                //     'name' => 'report.transaction',
                //     'display_name' => 'Transaction Report',
                //     'description' => 'Transaction Report',
                // ],
                // [
                //     'name' => 'report.users',
                //     'display_name' => 'Users Report',
                //     'description' => 'Users Report',
                // ],
                // [
                //     'name' => 'report.users.search',
                //     'display_name' => 'Users Report Search',
                //     'description' => 'Users Report Search',
                // ],

        ];
        // $permissions = [
        //     [
        //         'group_name' => 'loan_management',
        //         'permissions' => [
        //             //reject loan
        //             'loan_management.reject_loan',
        //             //approve loan
        //             'loan_management.approve_loan',                    
        //         ]
        //     ],
        //     [
        //         'group_name' => 'customer',
        //         'permissions' => [
        //             //create customer
        //             'customer.create',
        //             //edit customer
        //             'customer.edit',
        //             //delete customer
        //             'customer.delete',
        //             //view customer
        //             'customer.view',
        //             //update customer
        //             'customer.update',
        //         ]
        //     ],
        //     //disbursement
        //     [
        //         'group_name' => 'disbursement',
        //         'permissions' => [
        //             //create disbursement
        //             'disbursement.create',
        //             //edit disbursement
        //             'disbursement.edit',
        //             //delete disbursement
        //             'disbursement.delete',
        //             //view disbursement
        //             'disbursement.view',
        //             //update disbursement
        //             'disbursement.update',
        //         ]
        //     ],


                

        // ];

        //update permissions of admin
        $admin = \App\Models\User::where('email', 'admin@admin.com')->first();
        //get the name of his role
        $role = $admin->getRoleNames()->first();
        //get the role
        $role = \Spatie\Permission\Models\Role::findByName($role);
        //update this new permissions to the role
        foreach ($permissions as $key => $value) {
            foreach ($value['permissions'] as $key => $per) {
                //create permission
                $permission = \Spatie\Permission\Models\Permission::create(['name' => $per, 'group_name' => $value['group_name']]);
                //assign this new permission to the role
                $role->givePermissionTo($permission);
                $permission->assignRole($role);
                // $admin->assignRole($role);
            }
        }
        // $role->syncPermissions($permissions);

        // foreach ($permissions as $permission) {



            // [
            //     'name' => 'branch-list',
            //     'display_name' => 'Display Branch Listing',
            //     'description' => 'See only Listing Of Branch',
            // ],
            // [
            //     'name' => 'branch-create',
            //     'display_name' => 'Create Branch',
            //     'description' => 'Create New Branch',
            // ],
            // [
            //     'name' => 'branch-edit',
            //     'display_name' => 'Edit Branch',
            //     'description' => 'Edit Branch',
            // ],
            // [
            //     'name' => 'branch-delete',
            //     'display_name' => 'Delete Branch',
            //     'description' => 'Delete Branch',
            // ],
            // [
            //     'name' => 'user-list',
            //     'display_name' => 'Display User Listing',
            //     'description' => 'See only Listing Of User',
            // ],
            // [
            //     'name' => 'user-create',
            //     'display_name' => 'Create User',
            //     'description' => 'Create New User',
            // ],
            // [
            //     'name' => 'user-edit',
            //     'display_name' => 'Edit User',
            //     'description' => 'Edit User',
            // ],
            // [
            //     'name' => 'user-delete',
            //     'display_name' => 'Delete User',
            //     'description' => 'Delete User',
            // ],
            // [
            //     'name' => 'role-list',
            //     'display_name' => 'Display Role Listing',
            //     'description' => 'See only Listing Of Role',
            // ],
            // [
            //     'name' => 'role-create',
            //     'display_name' => 'Create Role',
            //     'description' => 'Create New Role',
            // ],
            // [
            //     'name' => 'role-edit',
            //     'display_name' => 'Edit Role',
            //     'description' => 'Edit Role',
            // ],
            // [
            //     'name' => 'role-delete',
            //     'display_name' => 'Delete Role',
            //     'description' => 'Delete Role',
            // ],
            // [
            //     'name' => 'permission-list',
            //     'display_name' => 'Display Permission Listing',
            //     'description' => 'See only Listing Of Permission',
            // ],
            // [
            //     'name' => 'permission-create',
            //     'display_name' => 'Create Permission',
            //     'description' => 'Create New Permission',
            // ],
            // [
            //     'name' => 'permission-edit',
            //     'display_name' => 'Edit Permission',
            //     'description' =>    'Edit Permission',
            // ],
        
        //
    }
}
