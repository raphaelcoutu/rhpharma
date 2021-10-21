<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function create(array $user)
    {
        static $password;

        return User::create([
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'email' => $user['email'] ?? Str::random(10) . '@RHPharma.com',
            'password' => $password ?: $password = Hash::make('password'),
            'remember_token' => Str::random(10),
            'workdays_per_week' => $user['workdays_per_week'] ?? 5,
            'seniority' => null,
            'is_active' => $user['is_active'] ?? 1,
            'is_manual' => $user['is_manual'] ?? 0,
            'branch_id' => $user['branch_id'] ?? 1,
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phm_admin = $this->create([
            'firstname' => 'SuperUser',
            'lastname' => 'SuperUser',
            'email' => 'phm_admin@RHPharma.com',
            'is_active' => 0
            ]);

        /*$phm_user = $this->create([
            'firstname' => 'PHM',
            'lastname' => 'User',
            'email' => 'phm_user@RHPharma.com',
            'is_active' => 0
        ]);

        $this->create([
            'firstname' => 'ATP',
            'lastname' => 'Admin',
            'email' => 'atp_admin@RHPharma.com',
            'branch_id' => 2
        ]);

        $this->create([
            'firstname' => 'ATP',
            'lastname' => 'User',
            'email' => 'atp_user@RHPharma.com',
            'branch_id' => 2
        ]);*/

        // Pharmaciens

        $aalongpre = $this->create([
            'firstname' => 'Audrey-Anne',
            'lastname' => 'Longpré',
            'email' => 'aalongpre@RHPharma.com',
        ]);

        $acoulombe = $this->create([
            'firstname' => 'Alexandrine',
            'lastname' => 'Coulombe',
            'email' => 'acoulombe@RHPharma.com',
            'workdays_per_week' => 3,
        ]);

        $adubuc = $this->create([
            'firstname' => 'Annie',
            'lastname' => 'Dubuc',
            'email' => 'adubuc@RHPharma.com',
            'workdays_per_week' => 3
        ]);

        $ameunier = $this->create([
            'firstname' => 'Annie',
            'lastname' => 'Meunier',
            'workdays_per_week' => 3
        ]);

        $bbeloin = $this->create([
            'firstname' => 'Bianca',
            'lastname' => 'Beloin-Jubinville',
            'workdays_per_week' => 3
        ]);

        $bboilard = $this->create([
            'firstname' => 'Brigitte',
            'lastname' => 'Boilard',
        ]);

        $cdesaulniers = $this->create([
            'firstname' => 'Cathy',
            'lastname' => 'Desaulniers',
            'workdays_per_week' => 3
        ]);

        $cleclair = $this->create([
            'firstname' => 'Christian',
            'lastname' => 'Leclair',
            'email' => 'cleclair@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $cpelletier = $this->create([
            'firstname' => 'Claudia',
            'lastname' => 'Pelletier-Mayette',
            'email' => 'cpelletier@RHPharma.com',
        ]);

        $eboisvert = $this->create([
            'firstname' => 'Émilie',
            'lastname' => 'Boisvert',
            'email' => 'eboivert@RHPharma.com',
        ]);

        $ebouchard = $this->create([
            'firstname' => 'Émile',
            'lastname' => 'Bouchard',
            'email' => 'ebouchard@RHPharma.com',
        ]);

        $edubuc = $this->create([
            'firstname' => 'Émilie',
            'lastname' => 'Dubuc',
            'email' => 'edubuc@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $edufort = $this->create([
            'firstname' => 'Émilie',
            'lastname' => 'Dufort-Rouleau',
            'email' => 'edufort@RHPharma.com',
        ]);

        $gberard = $this->create([
            'firstname' => 'Ghislain',
            'lastname' => 'Bérard',
            'email' => 'gberard@RHPharma.com',
        ]);

        $gdorais = $this->create([
            'firstname' => 'Gabriel',
            'lastname' => 'Dorais',
            'email' => 'gdorais@RHPharma.com',
        ]);

        $gduplain = $this->create([
            'firstname' => 'Geneviève',
            'lastname' => 'Duplain-Cyr',
            'email' => 'gduplain@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $gferland = $this->create([
            'firstname' => 'Gabrielle',
            'lastname' => 'Ferland',
            'email' => 'gferland@RHPharma.com',
        ]);

        $glanglois = $this->create([
            'firstname' => 'Geneviève',
            'lastname' => 'Langlois',
            'email' => 'glanglois@RHPharma.com',
        ]);


        $hblain = $this->create([
            'firstname' => 'Hugues',
            'lastname' => 'Blain',
            'email' => 'hblain@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $jcouture = $this->create([
            'firstname' => 'Jodianne',
            'lastname' => 'Couture',
            'email' => 'jcouture@RHPharma.com',
            'workdays_per_week' => 3
        ]);

        $jdesbiens = $this->create([
            'firstname' => 'Joëlle',
            'lastname' => 'Desbiens',
            'email' => 'jdesbiens@RHPharma.com',
            'workdays_per_week' => 3
        ]);

        $jdion = $this->create([
            'firstname' => 'Jocelyn',
            'lastname' => 'Dion',
            'email' => 'jdion@RHPharma.com',
        ]);

        $jduchesneau = $this->create([
            'firstname' => 'Josée',
            'lastname' => 'Duchesneau',
            'email' => 'jduchesneau@RHPharma.com',
        ]);

        $jproulx = $this->create([
            'firstname' => 'Josée',
            'lastname' => 'Proulx',
            'email' => 'jproulx@RHPharma.com',
            'workdays_per_week' => 3
        ]);

        $jleblond = $this->create([
            'firstname' => 'Julie',
            'lastname' => 'Leblond',
            'email' => 'jleblond@RHPharma.com',
            'workdays_per_week' => 3
        ]);

        $jquenneville = $this->create([
            'firstname' => 'Julie',
            'lastname' => 'Quenneville',
            'email' => 'jquenneville@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $llegeleux = $this->create([
            'firstname' => 'Lorraine',
            'lastname' => 'Legeleux',
            'email' => 'llegeleux@RHPharma.com',
        ]);

        $mberteau = $this->create([
            'firstname' => 'Mathieu',
            'lastname' => 'Berteau',
            'email' => 'mberteau@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $mfbeauchesne = $this->create([
            'firstname' => 'Marie-France',
            'lastname' => 'Beauchesne',
            'email' => 'mfbeauchesne@RHPharma.com',
            'is_manual' => 1
        ]);

        $mgilbert = $this->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Gilbert',
            'email' => 'mgilbert@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $mguay = $this->create([
            'firstname' => 'Marianne',
            'lastname' => 'Guay',
            'email' => 'mguay@RHPharma.com',
        ]);

        $mjlachance = $this->create([
            'firstname' => 'Marie-Josée',
            'lastname' => 'Lachance',
            'email' => 'mjlachance@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $mlacerte = $this->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Lacerte',
            'email' => 'mlacerte@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $mprousseau = $this->create([
            'firstname' => 'Marie-Pierre',
            'lastname' => 'Rousseau',
            'email' => 'mprousseau@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $mturgeon = $this->create([
            'firstname' => 'Martin',
            'lastname' => 'Turgeon',
            'email' => 'mturgeon@RHPharma.com',
        ]);

        $ndaviau = $this->create([
            'firstname' => 'Nathalie',
            'lastname' => 'Daviau',
            'email' => 'ndaviau@RHPharma.com',
            'workdays_per_week' => 4
        ]);

        $ngoettel = $this->create([
            'firstname' => 'Nicolas',
            'lastname' => 'Goettel',
            'email' => 'ngoettel@RHPharma.com',
        ]);

        $rbournival = $this->create([
            'firstname' => 'Roxanne',
            'lastname' => 'Bournival',
            'email' => 'rbournival@RHPharma.com',
        ]);

        $rcoutu = $this->create([
            'firstname' => 'Raphaël',
            'lastname' => 'Coutu',
            'email' => 'rcoutu@RHPharma.com',
        ]);

        $sbergeron = $this->create([
            'firstname' => 'Sabrina',
            'lastname' => 'Bergeron-Wolff',
            'email' => 'sbergeron@RHPharma.com',
        ]);


        $scloutier = $this->create([
            'firstname' => 'Sylvie',
            'lastname' => 'Cloutier',
            'email' => 'scloutier@RHPharma.com',
        ]);

        $slamontagne = $this->create([
            'firstname' => 'Sophie',
            'lastname' => 'Lamontagne',
            'email' => 'slamontagne@RHPharma.com',
        ]);

        $sletendre = $this->create([
            'firstname' => 'Sara',
            'lastname' => 'Letendre',
            'email' => 'sletendre@RHPharma.com',
        ]);

        $tjoly = $this->create([
            'firstname' => 'Thomas',
            'lastname' => 'Joly-Mischlich',
            'email' => 'tjoly@RHPharma.com',
        ]);

        $vchiasson = $this->create([
            'firstname' => 'Valérie',
            'lastname' => 'Chiasson-Roussel',
            'email' => 'vchiasson@RHPharma.com',
            'workdays_per_week' => 3
        ]);

        $vclement = $this->create([
            'firstname' => 'Valérie',
            'lastname' => 'Clément',
            'email' => 'vclement@RHPharma.com',
        ]);

        $vlord = $this->create([
            'firstname' => 'Vicky',
            'lastname' => 'Lord',
            'email' => 'vlord@RHPharma.com',
        ]);

        $mbd = $this->create([
            'firstname' => 'Marie-Ève',
            'lastname' => 'Bédard-Dufresne',
            'email' => 'mebedarddufresne@RHPharma.com',
            'workdays_per_week' => 5,
        ]);

        $cdr = $this->create([
            'firstname' => 'Camille',
            'lastname' => 'Dufort-Rouleau',
            'email' => 'cdufort@RHPharma.com',
            'workdays_per_week' => 5,
        ]);

        $plr = $this->create([
            'firstname' => 'Pierre-Luc',
            'lastname' => 'Ratté',
            'email' => 'plratte@RHPharma.com',
            'workdays_per_week' => 5,
        ]);

        $ia = $this->create([
            'firstname' => 'Isabelle',
            'lastname' => 'Audet',
            'email' => 'iaudet@RHPharma.com',
            'workdays_per_week' => 5,
            'is_active' => 0,
        ]);
    }
}
