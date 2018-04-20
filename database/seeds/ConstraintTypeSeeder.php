<?php

use App\ConstraintType;
use Illuminate\Database\Seeder;

class ConstraintTypeSeeder extends Seeder
{
    public function create(array $type)
    {
        return ConstraintType::create([
            'name' => $type['name'],
            'description' => $type['description'],
            'code' => $type['code'],
            'is_work' => $type['is_work'],
            'is_single_day' => $type['is_single_day'],
            'is_group_constraint' => $type['is_group_constraint'] ?? 0,
            'is_day_in_schedule' => $type['is_day_in_schedule'] ?? 0,
            'branch_id' => $type['branch_id'] ?? 1
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create([
            'name' => 'Formation fixe jour',
            'description' => 'Formation pour la/les journée(s) complète(s). Doit être approuvée par coordo à l\'enseignement',
            'code' => 'FOR',
            'is_work' => 1,
            'is_single_day' => 0,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Formation fixe en AM',
            'description' => 'Formation pour la matinée seulement. Doit être approuvée par coordo à l\'enseignement',
            'code' => 'FOR',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Formation fixe en PM',
            'description' => 'Formation pour l\'après-midi seulement. Doit être approuvée par coordo à l\'enseignement',
            'code' => 'FOR',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Congé sans solde souhait jour fixe',
            'description' => 'Aimerait être en congé sans solde à une date précide',
            'code' => 'CSS',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Congé de FDS souhait jour fixe',
            'description' => 'Journée préférée pour congé de fds à une date précise (sélectionner le samedi ou le dimanche correspondant : le congé est valable pour la fds entière)',
            'code' => 'C',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Reprise de temps souhait fixe jour',
            'description' => 'Aimerait prendre une RT à une date précise et pour la journée complète',
            'code' => 'RT',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Reprise de temps souhait AM',
            'description' => 'Aimerait prendre une RT à une date précise et pour la matinée seulement',
            'code' => 'RTAM',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Reprise de temps souhait PM',
            'description' => 'Aimerait prendre une RT à une date précise et pour l\'après-midi seulement',
            'code' => 'RTPM',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Férié à reprendre fixe jour',
            'description' => 'Aimerait reprendre un férié à une date précise',
            'code' => 'F',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Rayonnement fixe jour',
            'description' => 'Présentation ou cours lors de séminaire, congrès, université. Journée complète (doit être acceptée au préàlable)',
            'code' => 'RAY',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Rayonnement fixe en AM',
            'description' => 'Présentation ou cours lors de séminaire, congrès, université. Matinée seulement (doit être acceptée au préàlable)',
            'code' => 'RAYAM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Rayonnement fixe en PM',
            'description' => 'Présentation ou cours lors de séminaire, congrès, université. Après-midi seulement (doit être acceptée au préàlable)',
            'code' => 'RAYPM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Libération fixe jour',
            'description' => 'Aimerait une libération pour une date déterminée et la journée complète (RPE par exemple)',
            'code' => 'XND',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Libération fixe en AM',
            'description' => 'Aimerait une libération pour une date déterminée et la matinée seulement (comité par exemple)',
            'code' => 'XNDAM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Libération fixe en PM',
            'description' => 'Aimerait une libération pour une date déterminée et l\'après-midi seulement (comité par exemple)',
            'code' => 'XNDPM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Comité de pharmaco réunion midi',
            'description' => 'Aimerait une libération pour le comité de pharmaco et pour une date déterminée et la journée complète (RPE par exemple)',
            'code' => 'CPR',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Comité de pharmaco libération fixe AM',
            'description' => 'Aimerait une libération pour le comité de pharmaco et pour une date déterminée et la matinée seulement (comité par exemple)',
            'code' => 'CPAM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Comité de pharmaco libération fixe PM',
            'description' => 'Aimerait une libération pour le comité de pharmaco et pour une date déterminée et l\'après-midi seulement (comité par exemple)',
            'code' => 'CPPM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'PGTM fixe jour',
            'description' => 'Réunion PGTM prévue pour toute la journée',
            'code' => 'PG',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'PGTM fixe en AM',
            'description' => 'Réunion PGTM prévue pour la matinée seulement',
            'code' => 'PGAM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'PGTM fixe en PM',
            'description' => 'Réunion PGTM prévue pour l\'après-midi',
            'code' => 'PGPM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Vacances perlées fixe jour',
            'description' => 'Date souhaitée pour vacances perlées mais si date fixe seulement. Si plusieurs choix de dates, veuillez l\'inscrire dans la section "selon disponibilité"',
            'code' => 'VP',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Ariane fixe jour',
            'description' => 'Demande de temps Ariane pour une journée complète',
            'code' => 'AR',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Ariane fixe en AM',
            'description' => 'Demande de temps Ariane pour une matinée seulement',
            'code' => 'ARAM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Ariane fixe en PM',
            'description' => 'Demande de temps Ariane pour une après-midi seulement',
            'code' => 'ARPM',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'URE fixe jour',
            'description' => 'Demande de libération à date fixe pour projet URE déjà approuvé',
            'code' => 'URE',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Visite grossesse en AM',
            'description' => 'Visite de grossesse pour une matinée',
            'code' => 'VG',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Visite grossesse en PM',
            'description' => 'Visite de grossesse pour une après-midi',
            'code' => 'VG',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Travailler de jour (avant 16h30)',
            'description' => 'Doit terminer max à 16h30. Pas de SCAS, ni de Coumadin, ni ON3',
            'code' => 'J',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Travailler de jour (avant 17h)',
            'description' => 'Doit terminer max à 17h. Pas de Coumadin.',
            'code' => 'JS',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Travailler de jour (avant 17h30)',
            'description' => 'Doit terminer max à 17h30. Coumadin et SCAS OK.',
            'code' => 'JSA',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Vacances',
            'description' => 'Période de vacances',
            'code' => 'V',
            'is_work' => 0,
            'is_single_day' => 0,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Contrainte de FDS',
            'description' => 'Ne peut pas travailler cette fin de semaine',
            'code' => 'NFDS',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Férié travaillé',
            'description' => 'Travaille le férié',
            'code' => 'T',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Congé parental',
            'description' => '',
            'code' => 'PAREN',
            'is_work' => 0,
            'is_single_day' => 0,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Congé de maternité',
            'description' => '',
            'code' => 'MAT',
            'is_work' => 0,
            'is_single_day' => 0,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Congé paternité 5 semaines',
            'description' => '',
            'code' => 'PAT',
            'is_work' => 0,
            'is_single_day' => 0,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Congé différé',
            'description' => '',
            'code' => 'CTD',
            'is_work' => 0,
            'is_single_day' => 0,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Contrainte de garde',
            'description' => '',
            'code' => 'PDG',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Travailler de soir (14 seul)',
            'description' => '',
            'code' => '14',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
        ]);

        $this->create([
            'name' => 'Comité de coordination',
            'description' => '',
            'code' => '/G',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Journée supplémentaire proposée',
            'description' => '',
            'code' => 'JP',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Soirée supplémentaire proposée',
            'description' => '',
            'code' => 'S+',
            'is_work' => 1,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Comité de gestion CIUSSS am',
            'description' => '',
            'code' => 'G/',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Comité de gestion CIUSSS pm',
            'description' => '',
            'code' => '/G',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);

        $this->create([
            'name' => 'Comité de gestion jour fixe',
            'description' => '',
            'code' => 'G',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 1
        ]);
    }
}
