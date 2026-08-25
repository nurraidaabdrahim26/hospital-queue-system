<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Administration'],
            ['name' => 'Emergency'],
            ['name' => 'Pharmacy'],
            ['name' => 'Orthopedics'],
            ['name' => 'Cardiology'],
            ['name' => 'Dermatology'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
