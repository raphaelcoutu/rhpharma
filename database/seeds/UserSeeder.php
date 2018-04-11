<?php

use App\User;
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
            'email' => $user['email'] ?? str_random(10) . '@RHPharma.com',
            'password' => $password ?: $password = Hash::make('password'),
            'remember_token' => str_random(10),
            'workdays_per_week' => $user['workdays_per_week'] ?? 5,
            'seniority' => 0,
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
        $aalongpre->departments()->attach([1,8,15]);

        $acoulombe = $this->create([
            'firstname' => 'Alexandrine',
            'lastname' => 'Coulombe',
            'email' => 'acoulombe@RHPharma.com',
            'workdays_per_week' => 3,
            'is_active' => 0,
        ]);
        $acoulombe->departments()->attach([1,9,12]);

        $adubuc = $this->create([
            'firstname' => 'Annie',
            'lastname' => 'Dubuc',
            'email' => 'adubuc@RHPharma.com',
            'workdays_per_week' => 3
        ]);
        $adubuc->departments()->attach([1,4,5,6]);

        $ameunier = $this->create([
            'firstname' => 'Annie',
            'lastname' => 'Meunier',
            'workdays_per_week' => 5
        ]);
        $ameunier->departments()->attach([1,11]);

        $bbeloin = $this->create([
            'firstname' => 'Bianca',
            'lastname' => 'Beloin-Jubinville',
            'is_active' => 0
        ]);
        $bbeloin->departments()->attach([2,8]);

        $bboilard = $this->create([
            'firstname' => 'Brigitte',
            'lastname' => 'Boilard',
        ]);

        $cdesaulniers = $this->create([
            'firstname' => 'Cathy',
            'lastname' => 'Desaulniers',
            'workdays_per_week' => 3
        ]);
        $cdesaulniers->departments()->attach([14,15]);

        $cleclair = $this->create([
            'firstname' => 'Christian',
            'lastname' => 'Leclair',
            'email' => 'cleclair@RHPharma.com',
        ]);
        $cleclair->departments()->attach([1,2,4,5,6]);

        $cpelletier = $this->create([
            'firstname' => 'Claudia',
            'lastname' => 'Pelletier',
            'email' => 'cpelletier@RHPharma.com',
        ]);
        $cpelletier->departments()->attach([14]);

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
        $ebouchard->departments()->attach([2,4,5,6]);

        $edubuc = $this->create([
            'firstname' => 'Émilie',
            'lastname' => 'Dubuc',
            'email' => 'edubuc@RHPharma.com',
        ]);
        $edubuc->departments()->attach([1,7]);

        $edufort = $this->create([
            'firstname' => 'Émilie',
            'lastname' => 'Dufort-Rouleau',
            'email' => 'edufort@RHPharma.com',
        ]);
        $edufort->departments()->attach([1,11]);

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
        $gdorais->departments()->attach([1,10,16,17]);

        $gduplain = $this->create([
            'firstname' => 'Geneviève',
            'lastname' => 'Duplain-Cyr',
            'email' => 'gduplain@RHPharma.com',
        ]);
        $gduplain->departments()->attach([7]);

        $gferland = $this->create([
            'firstname' => 'Gabrielle',
            'lastname' => 'Ferland',
            'email' => 'gferland@RHPharma.com',
        ]);
        $gferland->departments()->attach([15]);

        $glanglois = $this->create([
            'firstname' => 'Geneviève',
            'lastname' => 'Langlois',
            'email' => 'glanglois@RHPharma.com',
        ]);


        $hblain = $this->create([
            'firstname' => 'Hugues',
            'lastname' => 'Blain',
            'email' => 'hblain@RHPharma.com',
        ]);
        $hblain->departments()->attach([1,4,5,6]);

        $jcouture = $this->create([
            'firstname' => 'Jodianne',
            'lastname' => 'Couture',
            'email' => 'jcouture@RHPharma.com',
        ]);
        $jcouture->departments()->attach([1,10]);

        $jdesbiens = $this->create([
            'firstname' => 'Joëlle',
            'lastname' => 'Desbiens',
            'email' => 'jdesbiens@RHPharma.com',
        ]);
        $jdesbiens->departments()->attach([16,17]);

        $jdion = $this->create([
            'firstname' => 'Jocelyn',
            'lastname' => 'Dion',
            'email' => 'jdion@RHPharma.com',
        ]);
        $jdion->departments()->attach([1,4,5,6,8]);

        $jduchesneau = $this->create([
            'firstname' => 'Josée',
            'lastname' => 'Duchesneau',
            'email' => 'jduchesneau@RHPharma.com',
        ]);
        $jduchesneau->departments()->attach([1,9]);

        $jproulx = $this->create([
            'firstname' => 'Josée',
            'lastname' => 'Proulx',
            'email' => 'jproulx@RHPharma.com',
        ]);

        $jleblond = $this->create([
            'firstname' => 'Julie',
            'lastname' => 'Leblond',
            'email' => 'jleblond@RHPharma.com',
        ]);
        $jleblond->departments()->attach([1]);

        $jquenneville = $this->create([
            'firstname' => 'Julie',
            'lastname' => 'Quenneville',
            'email' => 'jquenneville@RHPharma.com',
        ]);

        $llegeleux = $this->create([
            'firstname' => 'Lorraine',
            'lastname' => 'Legeleux',
            'email' => 'llegeleux@RHPharma.com',
            'is_active' => 0
        ]);
        $llegeleux->departments()->attach([8]);

        $mberteau = $this->create([
            'firstname' => 'Mathieu',
            'lastname' => 'Berteau',
            'email' => 'mberteau@RHPharma.com',
        ]);
        $mberteau->departments()->attach([2,11]);

        $mfbeauchesne = $this->create([
            'firstname' => 'Marie-France',
            'lastname' => 'Beauchesne',
            'email' => 'mfbeauchesne@RHPharma.com',
            'is_active' => 1,
            'is_manual' => 1
        ]);
        $mfbeauchesne->departments()->attach([2]);

        $mgilbert = $this->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Gilbert',
            'email' => 'mgilbert@RHPharma.com',
        ]);
        $mgilbert->departments()->attach([4,5,6,8]);

        $mguay = $this->create([
            'firstname' => 'Marianne',
            'lastname' => 'Guay',
            'email' => 'mguay@RHPharma.com',
        ]);
        $mguay->departments()->attach([1,9,10]);

        $mjlachance = $this->create([
            'firstname' => 'Marie-Josée',
            'lastname' => 'Lachance',
            'email' => 'mjlachance@RHPharma.com',
        ]);
        $mjlachance->departments()->attach([1,12]);

        $mlacerte = $this->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Lacerte',
            'email' => 'mlacerte@RHPharma.com',
        ]);
        $mlacerte->departments()->attach([4,5,6]);

        $mprousseau = $this->create([
            'firstname' => 'Marie-Pierre',
            'lastname' => 'Rousseau',
            'email' => 'mprousseau@RHPharma.com',
        ]);

        $mturgeon = $this->create([
            'firstname' => 'Martin',
            'lastname' => 'Turgeon',
            'email' => 'mturgeon@RHPharma.com',
        ]);
        $mturgeon->departments()->attach([4,5,6]);

        $ndaviau = $this->create([
            'firstname' => 'Nathalie',
            'lastname' => 'Daviau',
            'email' => 'ndaviau@RHPharma.com',
        ]);
        $ndaviau->departments()->attach([16,17]);

        $ngoettel = $this->create([
            'firstname' => 'Nicolas',
            'lastname' => 'Goettel',
            'email' => 'ngoettel@RHPharma.com',
        ]);
        $ngoettel->departments()->attach([1,4,5,6,16,17]);

        $rbournival = $this->create([
            'firstname' => 'Roxanne',
            'lastname' => 'Bournival',
            'email' => 'rbournival@RHPharma.com',
        ]);
        $rbournival->departments()->attach([7]);

        $rcoutu = $this->create([
            'firstname' => 'Raphaël',
            'lastname' => 'Coutu',
            'email' => 'rcoutu@RHPharma.com',
        ]);
        $rcoutu->departments()->attach([2]);

        $sbergeron = $this->create([
            'firstname' => 'Sabrina',
            'lastname' => 'Bergeron-Wolff',
            'email' => 'sbergeron@RHPharma.com',
            'is_active' => 0
        ]);
        $sbergeron->departments()->attach([7]);


        $scloutier = $this->create([
            'firstname' => 'Sylvie',
            'lastname' => 'Cloutier',
            'email' => 'scloutier@RHPharma.com',
        ]);
        $scloutier->departments()->attach([14]);

        $slamontagne = $this->create([
            'firstname' => 'Sophie',
            'lastname' => 'Lamontagne',
            'email' => 'slamontagne@RHPharma.com',
        ]);
        $slamontagne->departments()->attach([16,17]);

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
        $vchiasson->departments()->attach([1,14,16,17]);

        $vclement = $this->create([
            'firstname' => 'Valérie',
            'lastname' => 'Clément',
            'email' => 'vclement@RHPharma.com',
        ]);
        $vclement->departments()->attach([1,10]);

        $vlord = $this->create([
            'firstname' => 'Vicky',
            'lastname' => 'Lord',
            'email' => 'vlord@RHPharma.com',
            'workdays_per_week' => 2
        ]);
        $vlord->departments()->attach([1,4,5,6,12]);

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
        $cdr->departments()->attach(['14']);

        $plr = $this->create([
            'firstname' => 'Pierre-Luc',
            'lastname' => 'Ratté',
            'email' => 'plratte@RHPharma.com',
            'workdays_per_week' => 5,
        ]);
        $plr->departments()->attach(['16','17']);
    }
}
