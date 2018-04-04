<?php

use App\Department;
use App\Workplace;
use Illuminate\Database\Seeder;

class WorkplaceDepartmentSeeder extends Seeder
{
    public function create(array $department)
    {
        return Department::create([
            'name' => $department['name'],
            'description' => $department['description'] ?? '...',
            'branch_id' => '1',
            'workplace_id' => $department['workplace_id'] ?? 1,
            'bonus_weeks' => $department['bonus_weeks'] ?? 2,
            'bonus_pts' => $department['bonus_pts'] ?? 4,
            'malus_weeks' => $department['malus_weeks'] ?? 3,
            'malus_pts' => $department['malus_pts'] ?? 8,
            'monday_am' => $department['monday_am'] ?? 2,
            'monday_pm' => $department['monday_pm'] ?? 2,
            'tuesday_am' => $department['tuesday_am'] ?? 2,
            'tuesday_pm' => $department['tuesday_pm'] ?? 2,
            'wednesday_am' => $department['wednesday_am'] ?? 2,
            'wednesday_pm' => $department['wednesday_pm'] ?? 2,
            'thursday_am' => $department['thursday_am'] ?? 2,
            'thursday_pm' => $department['thursday_pm'] ?? 2,
            'friday_am' => $department['friday_am'] ?? 2,
            'friday_pm' => $department['friday_pm'] ?? 2
        ]);
    }
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

        $this->create([
            'name' => 'Coumadin',
            'code' => 'AC',
            'description' => 'Gérer des INR',
            'branch_id' => '1',
            'workplace_id' => '2',
        ]);

        $this->create([
            'name' => 'Médecine interne',
            'code' => 'MI',
            'description' => 'Gérer des patients malades',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Onco',
            'code' => 'ONCO',
            'description' => 'Oncologie',
            'branch_id' => '1',
            'workplace_id' => '1',
        ]);

        $this->create([
            'name' => 'Soins intensifs médicaux',
            'code' => 'SIM',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Soins intensifs chirurgicaux',
            'code' => 'SIC',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Soins intensifs HD',
            'code' => 'SIHD',
            'description' => 'Patients malades ++',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'SAMI',
            'code' => 'VIH',
            'description' => 'Gérer les patients infectés',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);


        $this->create([
            'name' => 'SIPA',
            'code' => 'SIPA',
            'description' => 'Surveillance des antimicrobiens',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Psychiatrie',
            'code' => 'SM',
            'description' => 'Santé mentale',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Insuffisance cardiaque',
            'code' => 'IC',
            'description' => 'CLIC',
            'branch_id' => '1',
            'workplace_id' => '2',
        ]);

        $this->create([
            'name' => 'Soins palliatifs',
            'code' => 'SP',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Centre d\'informations sur le médicament',
            'code' => 'CIM',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '1',
        ]);

        $this->create([
            'name' => 'Ariane',
            'code' => 'AR',
            'description' => 'Développement Ariane',
            'branch_id' => '1',
            'workplace_id' => '1',
        ]);

        $this->create([
            'name' => 'Mère-enfant',
            'code' => 'ME',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Pédiatrie',
            'code' => 'PE',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Urgence HF',
            'code' => 'URHF',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '1',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Urgence HD',
            'code' => 'URHD',
            'description' => '',
            'branch_id' => '1',
            'workplace_id' => '2',
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Distribution',
            'code' => 'DIS',
            'description' => 'Distribution',
            'branch_id' => '1',
            'workplace_id' => '2',
        ]);
    }
}
