<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function create(array $user)
    {
        static $password;

        return User::create([
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'email' => $user['email'] ?? Str::random(10) . '@rhpharma.com',
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
            'email' => 'phm_admin@rhpharma.com',
            'is_active' => 0
            ]);

        /*$phm_user = $this->create([
            'firstname' => 'PHM',
            'lastname' => 'User',
            'email' => 'phm_user@rhpharma.com',
            'is_active' => 0
        ]);

        $this->create([
            'firstname' => 'ATP',
            'lastname' => 'Admin',
            'email' => 'atp_admin@rhpharma.com',
            'branch_id' => 2
        ]);

        $this->create([
            'firstname' => 'ATP',
            'lastname' => 'User',
            'email' => 'atp_user@rhpharma.com',
            'branch_id' => 2
        ]);*/

        // Pharmaciens

        $aalongpre = $this->create([
            'firstname' => 'Audrey-Anne',
            'lastname' => 'Longpré',
            'email' => 'aalongpre@rhpharma.com',
        ]);

        $acoulombe = $this->create([
            'firstname' => 'Alexandrine',
            'lastname' => 'Coulombe',
            'email' => 'acoulombe@rhpharma.com',
            'workdays_per_week' => 3,
        ]);

        $adubuc = $this->create([
            'firstname' => 'Annie',
            'lastname' => 'Dubuc',
            'email' => 'adubuc@rhpharma.com',
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
            'email' => 'cleclair@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $cpelletier = $this->create([
            'firstname' => 'Claudia',
            'lastname' => 'Pelletier-Mayette',
            'email' => 'cpelletier@rhpharma.com',
        ]);

        $eboisvert = $this->create([
            'firstname' => 'Émilie',
            'lastname' => 'Boisvert',
            'email' => 'eboivert@rhpharma.com',
        ]);

        $ebouchard = $this->create([
            'firstname' => 'Émile',
            'lastname' => 'Bouchard',
            'email' => 'ebouchard@rhpharma.com',
        ]);

        $edubuc = $this->create([
            'firstname' => 'Émilie',
            'lastname' => 'Dubuc',
            'email' => 'edubuc@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $edufort = $this->create([
            'firstname' => 'Émilie',
            'lastname' => 'Dufort-Rouleau',
            'email' => 'edufort@rhpharma.com',
        ]);

        $gberard = $this->create([
            'firstname' => 'Ghislain',
            'lastname' => 'Bérard',
            'email' => 'gberard@rhpharma.com',
        ]);

        $gdorais = $this->create([
            'firstname' => 'Gabriel',
            'lastname' => 'Dorais',
            'email' => 'gdorais@rhpharma.com',
        ]);

        $gduplain = $this->create([
            'firstname' => 'Geneviève',
            'lastname' => 'Duplain-Cyr',
            'email' => 'gduplain@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $gferland = $this->create([
            'firstname' => 'Gabrielle',
            'lastname' => 'Ferland',
            'email' => 'gferland@rhpharma.com',
        ]);

        $glanglois = $this->create([
            'firstname' => 'Geneviève',
            'lastname' => 'Langlois',
            'email' => 'glanglois@rhpharma.com',
        ]);


        $hblain = $this->create([
            'firstname' => 'Hugues',
            'lastname' => 'Blain',
            'email' => 'hblain@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $jcouture = $this->create([
            'firstname' => 'Jodianne',
            'lastname' => 'Couture',
            'email' => 'jcouture@rhpharma.com',
            'workdays_per_week' => 3
        ]);

        $jdesbiens = $this->create([
            'firstname' => 'Joëlle',
            'lastname' => 'Desbiens',
            'email' => 'jdesbiens@rhpharma.com',
            'workdays_per_week' => 3
        ]);

        $jdion = $this->create([
            'firstname' => 'Jocelyn',
            'lastname' => 'Dion',
            'email' => 'jdion@rhpharma.com',
        ]);

        $jduchesneau = $this->create([
            'firstname' => 'Josée',
            'lastname' => 'Duchesneau',
            'email' => 'jduchesneau@rhpharma.com',
        ]);

        $jproulx = $this->create([
            'firstname' => 'Josée',
            'lastname' => 'Proulx',
            'email' => 'jproulx@rhpharma.com',
            'workdays_per_week' => 3
        ]);

        $jleblond = $this->create([
            'firstname' => 'Julie',
            'lastname' => 'Leblond',
            'email' => 'jleblond@rhpharma.com',
            'workdays_per_week' => 3
        ]);

        $jquenneville = $this->create([
            'firstname' => 'Julie',
            'lastname' => 'Quenneville',
            'email' => 'jquenneville@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $llegeleux = $this->create([
            'firstname' => 'Lorraine',
            'lastname' => 'Legeleux',
            'email' => 'llegeleux@rhpharma.com',
        ]);

        $mberteau = $this->create([
            'firstname' => 'Mathieu',
            'lastname' => 'Berteau',
            'email' => 'mberteau@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $mfbeauchesne = $this->create([
            'firstname' => 'Marie-France',
            'lastname' => 'Beauchesne',
            'email' => 'mfbeauchesne@rhpharma.com',
            'is_manual' => 1
        ]);

        $mgilbert = $this->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Gilbert',
            'email' => 'mgilbert@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $mguay = $this->create([
            'firstname' => 'Marianne',
            'lastname' => 'Guay',
            'email' => 'mguay@rhpharma.com',
        ]);

        $mjlachance = $this->create([
            'firstname' => 'Marie-Josée',
            'lastname' => 'Lachance',
            'email' => 'mjlachance@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $mlacerte = $this->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Lacerte',
            'email' => 'mlacerte@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $mprousseau = $this->create([
            'firstname' => 'Marie-Pierre',
            'lastname' => 'Rousseau',
            'email' => 'mprousseau@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $mturgeon = $this->create([
            'firstname' => 'Martin',
            'lastname' => 'Turgeon',
            'email' => 'mturgeon@rhpharma.com',
        ]);

        $ndaviau = $this->create([
            'firstname' => 'Nathalie',
            'lastname' => 'Daviau',
            'email' => 'ndaviau@rhpharma.com',
            'workdays_per_week' => 4
        ]);

        $ngoettel = $this->create([
            'firstname' => 'Nicolas',
            'lastname' => 'Goettel',
            'email' => 'ngoettel@rhpharma.com',
        ]);

        $rbournival = $this->create([
            'firstname' => 'Roxanne',
            'lastname' => 'Bournival',
            'email' => 'rbournival@rhpharma.com',
        ]);

        $rcoutu = $this->create([
            'firstname' => 'Raphaël',
            'lastname' => 'Coutu',
            'email' => 'rcoutu@rhpharma.com',
        ]);

        $sbergeron = $this->create([
            'firstname' => 'Sabrina',
            'lastname' => 'Bergeron-Wolff',
            'email' => 'sbergeron@rhpharma.com',
        ]);


        $scloutier = $this->create([
            'firstname' => 'Sylvie',
            'lastname' => 'Cloutier',
            'email' => 'scloutier@rhpharma.com',
        ]);

        $slamontagne = $this->create([
            'firstname' => 'Sophie',
            'lastname' => 'Lamontagne',
            'email' => 'slamontagne@rhpharma.com',
        ]);

        $sletendre = $this->create([
            'firstname' => 'Sara',
            'lastname' => 'Letendre',
            'email' => 'sletendre@rhpharma.com',
        ]);

        $tjoly = $this->create([
            'firstname' => 'Thomas',
            'lastname' => 'Joly-Mischlich',
            'email' => 'tjoly@rhpharma.com',
        ]);

        $vchiasson = $this->create([
            'firstname' => 'Valérie',
            'lastname' => 'Chiasson-Roussel',
            'email' => 'vchiasson@rhpharma.com',
            'workdays_per_week' => 3
        ]);

        $vclement = $this->create([
            'firstname' => 'Valérie',
            'lastname' => 'Clément',
            'email' => 'vclement@rhpharma.com',
        ]);

        $vlord = $this->create([
            'firstname' => 'Vicky',
            'lastname' => 'Lord',
            'email' => 'vlord@rhpharma.com',
        ]);

        $mbd = $this->create([
            'firstname' => 'Marie-Ève',
            'lastname' => 'Bédard-Dufresne',
            'email' => 'mebedarddufresne@rhpharma.com',
            'workdays_per_week' => 5,
        ]);

        $cdr = $this->create([
            'firstname' => 'Camille',
            'lastname' => 'Dufort-Rouleau',
            'email' => 'cdufort@rhpharma.com',
            'workdays_per_week' => 5,
        ]);

        $plr = $this->create([
            'firstname' => 'Pierre-Luc',
            'lastname' => 'Ratté',
            'email' => 'plratte@rhpharma.com',
            'workdays_per_week' => 5,
        ]);

        $ia = $this->create([
            'firstname' => 'Isabelle',
            'lastname' => 'Audet',
            'email' => 'iaudet@rhpharma.com',
            'workdays_per_week' => 5,
            'is_active' => 0,
        ]);
    }
}
