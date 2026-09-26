<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $adminPermissions = [
            'view admin dashboard',

            'view users',
            'add users',
            'edit users',
            'delete users',

            'view farmers',
            'edit farmers',
            'delete farmers',

            'view customers',
            'view customer',
            'delete customers',

            'view markets',
            'add markets',
            'edit markets',
            'delete markets',

            'view categories',
            'add categories',
            'edit categories',
            'delete categories',

            'view products',
            'add products',
            'edit products',
            'delete products',

            'view reviews',
            'flag reviews',
            'delete reviews',

            'view reports',
            'generate reports',
            'download reports',

            'view announcements',
            'add announcements',
            'edit announcements',
            'delete announcements',

            'view roles',
            'add roles',
            'edit roles',
            'delete roles',

            'view permissions',
            'add permissions',
            'edit permissions',
            'delete permissions',
        ];

        $farmerPermissions = [
            'view dashboard',

            'view profile',
            'edit profile',

            'view markets',
            'join markets',
            'leave markets',

            'view products',
            'add products',
            'edit products',
            'delete products',

            'view weekly stock',
            'add weekly stock',
            'edit weekly stock',
            'delete weekly stock',

            'view orders',
            'view order',
            'update order status',

            'view pickup slots',
            'add pickup slots',
            'edit pickup slots',
            'delete pickup slots',

            'view reviews',
        ];

        $customerPermissions = [];

        foreach (array_merge(
            $adminPermissions,
            $farmerPermissions,
            $customerPermissions
        ) as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $farmer = Role::firstOrCreate([
            'name' => 'farmer',
            'guard_name' => 'web',
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions($adminPermissions);

        $farmer->syncPermissions($farmerPermissions);

        $customer->syncPermissions($customerPermissions);

        User::whereNotNull('role')->get()->each(function ($user) {
            $user->syncRoles([$user->role]);
        });
    }
}