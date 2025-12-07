<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $ceo = Employee::updateOrCreate(
            ['employee_id' => 'EMP-0001'],
            [
                'name' => 'Ariana Putri',
                'gender' => 'Female',
                'job_title' => 'Chief Executive Officer',
                'department' => 'Executive',
                'work_email' => 'ariana.putri@example.com',
                'work_phone' => '+62 812 1111 1111',
                'employment_type' => 'Full-time',
                'hire_date' => now()->subYears(5),
                'status' => 'Active',
            ]
        );

        $opsManager = Employee::updateOrCreate(
            ['employee_id' => 'EMP-0002'],
            [
                'name' => 'Budi Santoso',
                'gender' => 'Male',
                'job_title' => 'Operations Manager',
                'department' => 'Operations',
                'manager_id' => $ceo->id,
                'work_email' => 'budi.santoso@example.com',
                'work_phone' => '+62 812 2222 2222',
                'employment_type' => 'Full-time',
                'hire_date' => now()->subYears(3),
                'status' => 'Active',
            ]
        );

        Employee::updateOrCreate(
            ['employee_id' => 'EMP-0003'],
            [
                'name' => 'Citra Lestari',
                'gender' => 'Female',
                'job_title' => 'HR Specialist',
                'department' => 'Human Resources',
                'manager_id' => $opsManager->id,
                'work_email' => 'citra.lestari@example.com',
                'work_phone' => '+62 812 3333 3333',
                'employment_type' => 'Full-time',
                'hire_date' => now()->subYear(),
                'status' => 'Active',
            ]
        );
    }
}
