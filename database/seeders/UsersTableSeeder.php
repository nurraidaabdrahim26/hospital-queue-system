<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1, // 1 = Admin
            'department_id' => 1, // 1 = Administration

        ]);

        // Create Staff User
        User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 2, // 2 = Staff
            'department_id' => 2, // 2 = Emergency

        ]);
    }
}
