<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class StarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //roles
        $admin = Role::create(['name' => 'admin']);
        $staff = Role::create(['name' => 'staff']);

        //permissions
        $viewProduct = Permission::create(['name' => 'view product']);
        $createProduct = Permission::create(['name' => 'create product']);
        $editProduct = Permission::create(['name' => 'edit product']);
        $deleteProduct = Permission::create(['name' => 'delete product']);

        //assign
        $admin->givePermissionTo($viewProduct);
        $admin->givePermissionTo($createProduct);
        $admin->givePermissionTo($editProduct);
        $admin->givePermissionTo($deleteProduct);

        $staff->givePermissionTo($viewProduct);
        $staff->givePermissionTo($createProduct);
        $staff->givePermissionTo($editProduct);

        $userAdmin = User::create([
            'name' => 'admin',
            'email'=> 'admin@demo.com',
            'password' => bcrypt('demo1234'),
        ]);

        $userAdmin->assignRole('admin');

        $userStaff = User::create([
            'name' => 'staff',
            'email'=> 'staff@demo.com',
            'password' => bcrypt('demo1234'),
        ]);

        $userStaff->assignRole('staff');
    }
}
