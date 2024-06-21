<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Master;
use App\Models\Setting;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $user=User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@123')
        ]);

        $permission=array(
            'Role Add',
            'Role Edit',
            'Role Delete',
            'Role View',
            'Department Add',
            'Department Edit',
            'Department Delete',
            'Department View',
            'Designation Add',
            'Designation Edit',
            'Designation Delete',
            'Designation View',
            'Section Add',
            'Section Edit',
            'Section Delete',
            'Section View',
            'Job Function Add',
            'Job Function Edit',
            'Job Function Delete',
            'Job Function View',
            'Certificate Add',
            'Certificate Edit',
            'Certificate Delete',
            'Certificate View',
            'Setting Edit',
            'Staff Add',
            'Staff Edit',
            'Staff Delete',
            'Staff View',
            'ADT Staff View',
            'ADT Add',
            'ADT Edit',
            'ADT Delete',
            'ADT View',
            'ADT Report Download',
            'ADT Report Upload'
        );
        foreach($permission as $value){
            Permission::create(['name' => $value]);
        }

        $role=Role::create(['name' => 'admin']);
       
        $role->givePermissionTo(Permission::all());


        $user->assignRole($role);

        Master::create([
            'name' => 'Pilot',
            'type' => 'designation',
        ]);
        Setting::create([
            'app_name' => 'Airport Management System',
            'app_logo' => 'logo.png',
            'app_favicon' => 'favicon.png',
            'app_address'=>'',
            'app_email'=>'',
            'app_phone'=>'',
            'app_timezone'=>'UTC',
            'app_copyright'=>''
        ]);
    }
}
