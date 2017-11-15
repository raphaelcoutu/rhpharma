<?php

use App\Department;
use App\Workplace;
use Illuminate\Database\Seeder;

class WorkplaceDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Workplace::create([
            'name' => 'CHUS HF',
            'code' => 'HF',
            'address' => '3001, 12e Avenue Nord',
            'city' => 'Sherbrooke',
            'province' => 'Québec',
            'country' => 'Canada',
            'postal_code' => 'J1H 5N4'
        ]);

        Workplace::create([
            'name' => 'CHUS HD',
            'code' => 'HD',
            'address' => '580, rue Bowen S',
            'city' => 'Sherbrooke',
            'province' => 'Québec',
            'country' => 'Canada',
            'postal_code' => 'J1G 2E8'
        ]);

        Department::create([
            'name' => 'Coumadin',
            'code' => 'AC',
            'description' => 'Gérer des INR',
            'branch_id' => '1',
            'workplace_id' => '2'
        ]);

        Department::create([
            'name' => 'Médecine interne',
            'code' => 'MI',
            'description' => 'Gérer des patients malades',
            'branch_id' => '1',
            'workplace_id' => '2'
        ]);

        Department::create([
            'name' => 'Onco',
            'code' => 'ONCO',
            'description' => 'Oncologie',
            'branch_id' => '1',
            'workplace_id' => '1'
        ]);

        Department::create([
            'name' => 'Soins intensifs médicaux',
            'code' => 'SIM',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '1'
        ]);

        Department::create([
            'name' => 'Soins intensifs chirurgicaux',
            'code' => 'SIC',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '1'
        ]);

        Department::create([
            'name' => 'Soins intensifs HD',
            'code' => 'SIHD',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '2'
        ]);

        Department::create([
            'name' => 'Clinique SAMI',
            'code' => 'VIH',
            'description' => 'Gérer les patients infectés',
            'branch_id' => '1',
            'workplace_id' => '2'
        ]);

        Department::create([
            'name' => 'SIPA',
            'code' => 'SIPA',
            'description' => 'Surveillance des antimicrobiens',
            'branch_id' => '1',
            'workplace_id' => '1'
        ]);
    }
}
