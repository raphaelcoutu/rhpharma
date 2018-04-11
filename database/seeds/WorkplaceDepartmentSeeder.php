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
            'branch_id' => 1,
            'workplace_id' => $department['workplace_id'] ?? 1,
            'department_type_id' => $department['department_type_id'] ?? null,
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

        Workplace::create([
            'name' => 'Gestion'
        ]);

        $this->create([
            'name' => 'Coumadin',
            'description' => 'Gérer des INR',
            'branch_id' => 1,
            'workplace_id' => 2,
        ]);

        $this->create([
            'name' => 'Médecine interne',
            'description' => 'Gérer des patients malades',
            'branch_id' => 1,
            'workplace_id' => 2,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Onco',
            'description' => 'Oncologie',
            'branch_id' => 1,
            'workplace_id' => 1,
        ]);

        $this->create([
            'name' => 'Soins intensifs médicaux',
            'description' => 'Patients malades ++',
            'branch_id' => 1,
            'workplace_id' => 1,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Soins intensifs chirurgicaux',
            'description' => 'Patients malades ++',
            'branch_id' => 1,
            'workplace_id' => 1,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Soins intensifs HD',
            'description' => 'Patients malades ++',
            'branch_id' => 1,
            'workplace_id' => 2,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'SAMI',
            'description' => 'Gérer les patients infectés',
            'branch_id' => 1,
            'workplace_id' => 2,
            'department_type_id' => 1,
        ]);


        $this->create([
            'name' => 'SIPA',
            'description' => 'Surveillance des antimicrobiens',
            'branch_id' => 1,
            'workplace_id' => 1,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Psychiatrie',
            'description' => 'Santé mentale',
            'branch_id' => 1,
            'workplace_id' => 2,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Insuffisance cardiaque',
            'description' => 'CLIC',
            'branch_id' => 1,
            'workplace_id' => 2,
        ]);

        $this->create([
            'name' => 'Soins palliatifs',
            'description' => '',
            'branch_id' => 1,
            'workplace_id' => 2,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Centre d\'informations sur le médicament',
            'description' => '',
            'branch_id' => 1,
            'workplace_id' => 1,
        ]);

        $this->create([
            'name' => 'Ariane',
            'description' => 'Développement Ariane',
            'branch_id' => 1,
            'workplace_id' => 1,
        ]);

        $this->create([
            'name' => 'Mère-enfant',
            'description' => '',
            'branch_id' => 1,
            'workplace_id' => 1,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Pédiatrie',
            'description' => '',
            'branch_id' => 1,
            'workplace_id' => 1,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Urgence HF',
            'description' => '',
            'branch_id' => 1,
            'workplace_id' => 1,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Urgence HD',
            'description' => '',
            'branch_id' => 1,
            'workplace_id' => 2,
            'department_type_id' => 1,
        ]);

        $this->create([
            'name' => 'Distribution HF',
            'description' => 'Distribution',
            'branch_id' => 1,
            'workplace_id' => 1,
        ]);

        $this->create([
            'name' => 'Distribution HD',
            'description' => 'Distribution',
            'branch_id' => 1,
            'workplace_id' => 2,
        ]);

        $this->create([
            'name' => 'Absences',
            'description' => 'Gestion des absences',
            'branch_id' => 1,
            'workplace_id' => 3,
            'department_type_id' => 2
        ]);
    }
}
