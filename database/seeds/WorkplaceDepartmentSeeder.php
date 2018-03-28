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

        factory(Department::class)->create([
            'name' => 'Coumadin',
            'code' => 'AC',
            'description' => 'Gérer des INR',
            'branch_id' => '1',
            'workplace_id' => '2',
        ]);

        factory(Department::class)->create([
            'name' => 'Médecine interne',
            'code' => 'MI',
            'description' => 'Gérer des patients malades',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Onco',
            'code' => 'ONCO',
            'description' => 'Oncologie',
            'branch_id' => '1',
            'workplace_id' => '1',
        ]);

        factory(Department::class)->create([
            'name' => 'Soins intensifs médicaux',
            'code' => 'SIM',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Soins intensifs chirurgicaux',
            'code' => 'SIC',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Soins intensifs HD',
            'code' => 'SIHD',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'SAMI',
            'code' => 'VIH',
            'description' => 'Gérer les patients infectés',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);


        factory(Department::class)->create([
            'name' => 'SIPA',
            'code' => 'SIPA',
            'description' => 'Surveillance des antimicrobiens',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Psychiatrie',
            'code' => 'SM',
            'description' => 'Santé mentale',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Insuffisance cardiaque',
            'code' => 'IC',
            'description' => 'CLIC',
            'branch_id' => '1',
            'workplace_id' => '2',
        ]);

        factory(Department::class)->create([
            'name' => 'Soins palliatifs',
            'code' => 'SP',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Centre d\'informations sur le médicament',
            'code' => 'CIM',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '1',
        ]);

        factory(Department::class)->create([
            'name' => 'Ariane',
            'code' => 'AR',
            'description' => 'Développement Ariane',
            'branch_id' => '1',
            'workplace_id' => '1',
        ]);

        factory(Department::class)->create([
            'name' => 'Mère-enfant',
            'code' => 'ME',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Pédiatrie',
            'code' => 'PE',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Urgence HF',
            'code' => 'URHF',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Urgence HD',
            'code' => 'URHD',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        factory(Department::class)->create([
            'name' => 'Distribution',
            'code' => 'DIS',
            'description' => 'Distribution',
            'branch_id' => '1',
            'workplace_id' => '2',
        ]);
    }
}
