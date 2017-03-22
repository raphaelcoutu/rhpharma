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
            'name' => 'Onco 1',
            'code' => 'ON1',
            'description' => 'Gérer les conseils pharmacie d\'onco',
            'branch_id' => '1',
            'workplace_id' => '1'
        ]);

        Department::create([
            'name' => 'Onco 2',
            'code' => 'ON2',
            'description' => 'Gérer les conseils aux patients d\'onco',
            'branch_id' => '1',
            'workplace_id' => '1'
        ]);

        Department::create([
            'name' => 'Onco 3',
            'code' => 'ON3',
            'description' => 'Vérif d\'onco',
            'branch_id' => '1',
            'workplace_id' => '1'
        ]);

        Department::create([
            'name' => 'Clinique SAMI',
            'code' => 'VIH',
            'description' => 'Gérer les patients infectés',
            'branch_id' => '1',
            'workplace_id' => '2'
        ]);

        Department::create([
            'name' => 'Distrib',
            'code' => '8HF',
            'description' => 'Gérer les patients infectés',
            'branch_id' => '1',
            'workplace_id' => '1'
        ]);

        Department::create([
            'name' => 'Pacmed HF',
            'code' => 'HF1',
            'description' => 'ATP style',
            'branch_id' => '2',
            'workplace_id' => '1'
        ]);

        Department::create([
            'name' => 'Pacmed HD',
            'code' => 'HD1',
            'description' => 'ATP style',
            'branch_id' => '2',
            'workplace_id' => '2'
        ]);

        Department::create([
            'name' => 'Saisie HD',
            'code' => 'HD2',
            'description' => 'ATP style',
            'branch_id' => '2',
            'workplace_id' => '2'
        ]);
    }
}
