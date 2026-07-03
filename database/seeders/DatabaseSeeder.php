<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $operationalAdmin = Role::create(['name' => 'Operational Admin']);
        $sponsorRole = Role::create(['name' => 'Sponsor']);

        // Create Users
        $farhan = User::create([
            'name' => 'Farhan',
            'email' => 'farhan@admin.com',
            'password' => Hash::make('password'),
            'account_status' => 'approved',
        ]);
        $farhan->assignRole($superAdmin);

        $ibu = User::create([
            'name' => 'Ibu',
            'email' => 'ibu@admin.com',
            'password' => Hash::make('password'),
            'account_status' => 'approved',
        ]);
        $ibu->assignRole($operationalAdmin);

        $tsurayya = User::create([
            'name' => 'Tsurayya',
            'email' => 'tsurayya@admin.com',
            'password' => Hash::make('password'),
            'account_status' => 'approved',
        ]);
        $tsurayya->assignRole($operationalAdmin);

        $sponsorAlpha = User::create([
            'name' => 'Sponsor Alpha',
            'email' => 'alpha@sponsor.com',
            'password' => Hash::make('password'),
            'account_status' => 'approved',
        ]);
        $sponsorAlpha->assignRole($sponsorRole);
    }
}
