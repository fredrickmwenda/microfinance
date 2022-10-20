<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

		
		// $users = array(
		// 	array(
        //         'id' => '2',
        //         'role_id' => '2',
        //         'name' => 'David Jhon',
        //         'email' => 'user@user.com',
        //         'email_verified_at' => '2021-02-16 11:17:05',
        //         'password' => '$2y$10$82pSafb3mxrR9qP6sbGq.O49fA/G2MFIVS19/s7HBatSzAmBGvRzK',
        //         'phone' => '+8801303587195',
        //         'phone_verified_at' => '2021-02-16 17:19:08',
        //         'balance' => NULL,'amount' => NULL,
        //         'account_number' => '1962687945718489',
        //         'status' => '1',
        //         'two_step_auth' => '0',
        //         'remember_token' => NULL,
        //         'created_at' => '2021-02-16 11:17:06',
        //         'updated_at' => '2021-02-16 11:17:06')
		// );

		// User::insert($users);
    	
    	    	
    	$roleSuperAdmin = Role::create(['name' => 'superadmin']);

		$super = User::create([
			'first_name' => 'Super',
			'last_name' => 'Admin',
			'email' => 'admin@admin.com',
			'branch_id'=> '1',
			'phone' => '0713723353',
			'national_id' => '33555213',
			'password' => Hash::make('123456'),
			'role_id' => $roleSuperAdmin->id,
			'status' => 'active',
			
		]);
        //create permission
    	$permissions = [
    		[
    			'group_name' => 'dashboard',
    			'permissions' => [
    				'dashboard.index',
    			]
    		],
    		
            //  Transaction
			[
                'group_name' => 'transaction',
                'permissions' => [
                    'transaction.index',
					'transaction.create',
					'transaction.store',
					'transaction.show',
					'transaction.edit',
					'transaction.update',
					'transaction.destroy',
				    'transaction.list',
					'transactions.module',
                ]
			],

			// loan Management
			[
                'group_name' => 'loan_management',
                'permissions' => [
                    'loan.management.create',
                    'loan.management.edit',
                    'loan.management.index',
					'loan.management.delete',
					'loan.management.update',
					'loan.management.list',
					'loan.management.show',			
					'loan.rejected.index',
					'loan.approved.index',
					'loan.request.index',
					'loan.pending.index',
					'loan.active.index',
					'loan.closed.index',
					'loan.overdue.index',	
					'loan.payment.show',
					'loan.attachment.show',	
					'loan.payment.schedule',
					'loan.approve',
					'loan.reject',
					'loan.disburse',		
                ]
            ],
			// Disburse
			[
				'group_name' => 'disburse',
				'permissions' => [
					'disburse.create',
					'disburse.index',
					'disburse.update',
					'disburse.edit',
					'disburse.delete',
					'disburse.list',
				]
			],
           // Branches 
			[
				'group_name' => 'branch',
				'permissions' => [
					'branch.edit',
					'branch.create',
					'branch.index',
					'branch.delete',
					'branch.list',
					'branch.update',	
				]
			],
            // Payment Gateway
			
			[
				'group_name' => 'paymentgateway',
				'permissions' => [
					'payment.gateway.edit',
					'payment.gateway.index',
					'payment.gateway.store',
					'payment.gateway.delete',
					'payment.gateway.create',
					'payment.gateway.update',
					'payment.gateway.list',
				]
			],


			// customer

			[
				'group_name' => 'customer',
				'permissions' => [
					'customer.create',
					'customer.edit',
					'customer.update',
					'customer.delete',
					'customer.list',
					'customer.index',
					'customer.active',
					'customer.inactive',
				
				]
			],

			// Reports
			[
				'group_name' => 'report',
				'permissions' => [
					'report.index',
					'report.show',
					'report.list',
					'report.customer',
					'report.loan',
					'report.disburse',
					'report.transaction',
					'report.performance',
				]
			],

			[
				'group_name' => 'administrator',
				'permissions' => [
					'administrator.module'
				]
			],

            
			// Users
			[
				'group_name' => 'users',
				'permissions' => [
					'user.create',
					'user.index',
					'user.delete',
					'user.edit',
					'user.update',
					'user.verified',
					'user.show',
					'user.banned',
					'user.unverified',
					'user.list',
					
				]
			],

			// Roles
    		[
    			'group_name' => 'role',
    			'permissions' => [
    				'role.create',
    				'role.edit',
    				'role.update',
    				'role.delete',
    				'role.list',

    			]
    		],

			// Support

			[
                'group_name' => 'support',
                'permissions' => [
                    'support.index',
                    'support.delete',
                    'support.create',
					'support.edit',
					'support.update',
                ]
			],


			// Settings
			[
				'group_name' => 'settings',
				'permissions' => [
					'system.settings',
					'seo.settings',
					'menu',
					'theme.settings',
					'phone.settings',
				]
			],
	




    	];

        //assign permission

    	foreach ($permissions as $key => $row) {
    		foreach ($row['permissions'] as $per) {
    			$permission = Permission::create(['name' => $per, 'group_name' => $row['group_name']]);
    			$roleSuperAdmin->givePermissionTo($permission);
    			$permission->assignRole($roleSuperAdmin);
    			$super->assignRole($roleSuperAdmin);
    		}
    	}

    	
    }
}
