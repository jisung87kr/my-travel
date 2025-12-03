<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        foreach (UserRole::cases() as $role) {
            Role::create(['name' => $role->value]);
        }

        // Create permissions
        $permissions = [
            // Product permissions
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'products.manage',

            // Booking permissions
            'bookings.view',
            'bookings.create',
            'bookings.update',
            'bookings.cancel',
            'bookings.manage',

            // Review permissions
            'reviews.create',
            'reviews.update',
            'reviews.delete',
            'reviews.reply',
            'reviews.manage',

            // Message permissions
            'messages.send',
            'messages.view',

            // Admin permissions
            'users.manage',
            'vendors.manage',
            'reports.manage',
            'dashboard.admin',
            'dashboard.vendor',
            'dashboard.guide',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName(UserRole::ADMIN->value);
        $adminRole->givePermissionTo(Permission::all());

        $vendorRole = Role::findByName(UserRole::VENDOR->value);
        $vendorRole->givePermissionTo([
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'bookings.view',
            'bookings.update',
            'reviews.reply',
            'messages.send',
            'messages.view',
            'dashboard.vendor',
        ]);

        $guideRole = Role::findByName(UserRole::GUIDE->value);
        $guideRole->givePermissionTo([
            'bookings.view',
            'bookings.update',
            'messages.view',
            'dashboard.guide',
        ]);

        $travelerRole = Role::findByName(UserRole::TRAVELER->value);
        $travelerRole->givePermissionTo([
            'products.view',
            'bookings.view',
            'bookings.create',
            'bookings.cancel',
            'reviews.create',
            'reviews.update',
            'reviews.delete',
            'messages.send',
            'messages.view',
        ]);
    }
}
