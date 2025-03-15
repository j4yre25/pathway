<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminRole = Role::Where('name', 'peso')->first();

        // Create the admin user
        $admin = User::create([
            'email' => 'admin@examplee.com',
            'password' => bcrypt('password'), // Use a secure password
            'is_approved' => true, // Assuming you have an approved field
            'role' => 'peso'
        ]);

        // Assign the "peso admin" role to the user
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }
    }
}
