<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phm_admin = factory(User::class)->create([
            'firstname' => 'PHM',
            'lastname' => 'Admin',
            'email' => 'phm_admin@RHPharma.com',
            'password' => Hash::make('password'),
            'is_active' => 0
            ]);

        $phm_user = factory(User::class)->create([
            'firstname' => 'PHM',
            'lastname' => 'User',
            'email' => 'phm_user@RHPharma.com',
            'password' => Hash::make('password'),
            'is_active' => 0
        ]);

        factory(User::class)->create([
            'firstname' => 'ATP',
            'lastname' => 'Admin',
            'email' => 'atp_admin@RHPharma.com',
            'password' => Hash::make('password'),
            'branch_id' => 2
        ]);

        factory(User::class)->create([
            'firstname' => 'ATP',
            'lastname' => 'User',
            'email' => 'atp_user@RHPharma.com',
            'password' => Hash::make('password'),
            'branch_id' => 2
        ]);

        // Pharmaciens

        $aalongpre = factory(User::class)->create([
            'firstname' => 'Audrey-Anne',
            'lastname' => 'Longpré',
            'email' => 'aalongpre@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $aalongpre->departments()->attach([1,8,15]);

        $acoulombe = factory(User::class)->create([
            'firstname' => 'Alexandrine',
            'lastname' => 'Coulombe',
            'email' => 'acoulombe@RHPharma.com',
            'password' => Hash::make('password'),
            'workdays_per_week' => 3,
            'is_active' => 0,
        ]);
        $acoulombe->departments()->attach([1,9,12]);

        $adubuc = factory(User::class)->create([
            'firstname' => 'Annie',
            'lastname' => 'Dubuc',
            'email' => 'adubuc@RHPharma.com',
            'password' => Hash::make('password'),
            'workdays_per_week' => 3
        ]);
        $adubuc->departments()->attach([1,4,5,6]);

        $ameunier = factory(User::class)->create([
            'firstname' => 'Annie',
            'lastname' => 'Meunier',
            'email' => 'ameunier@RHPharma.com',
            'password' => Hash::make('password'),
            'workdays_per_week' => 5
        ]);
        $ameunier->departments()->attach([1,11]);

        $bbeloin = factory(User::class)->create([
            'firstname' => 'Bianca',
            'lastname' => 'Beloin-Jubinville',
            'email' => 'bbeloin@RHPharma.com',
            'password' => Hash::make('password'),
            'is_active' => 0
        ]);
        $bbeloin->departments()->attach([2,8]);

        $bboilard = factory(User::class)->create([
            'firstname' => 'Brigitte',
            'lastname' => 'Boilard',
            'email' => 'bboilard@RHPharma.com',
            'password' => Hash::make('password'),
        ]);

        $cdesaulniers = factory(User::class)->create([
            'firstname' => 'Cathy',
            'lastname' => 'Desaulniers',
            'email' => 'cdesaulniers@RHPharma.com',
            'password' => Hash::make('password'),
            'workdays_per_week' => 3
        ]);
        $cdesaulniers->departments()->attach([14,15]);

        $cleclair = factory(User::class)->create([
            'firstname' => 'Christian',
            'lastname' => 'Leclair',
            'email' => 'cleclair@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $cleclair->departments()->attach([1,2,4,5,6]);

        $cpelletier = factory(User::class)->create([
            'firstname' => 'Claudia',
            'lastname' => 'Pelletier',
            'email' => 'cpelletier@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $cpelletier->departments()->attach([14]);

        $eboisvert = factory(User::class)->create([
            'firstname' => 'Émilie',
            'lastname' => 'Boisvert',
            'email' => 'eboivert@RHPharma.com',
            'password' => Hash::make('password')
        ]);

        $ebouchard = factory(User::class)->create([
            'firstname' => 'Émile',
            'lastname' => 'Bouchard',
            'email' => 'ebouchard@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $ebouchard->departments()->attach([2,4,5,6]);

        $edubuc = factory(User::class)->create([
            'firstname' => 'Émilie',
            'lastname' => 'Dubuc',
            'email' => 'edubuc@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $edubuc->departments()->attach([1,7]);

        $edufort = factory(User::class)->create([
            'firstname' => 'Émilie',
            'lastname' => 'Dufort-Rouleau',
            'email' => 'edufort@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $edufort->departments()->attach([1,11]);

        $gberard = factory(User::class)->create([
            'firstname' => 'Ghislain',
            'lastname' => 'Bérard',
            'email' => 'gberard@RHPharma.com',
            'password' => Hash::make('password')
        ]);

        $gdorais = factory(User::class)->create([
            'firstname' => 'Gabriel',
            'lastname' => 'Dorais',
            'email' => 'gdorais@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $gdorais->departments()->attach([1,10,16,17]);

        $gduplain = factory(User::class)->create([
            'firstname' => 'Geneviève',
            'lastname' => 'Duplain-Cyr',
            'email' => 'gduplain@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $gduplain->departments()->attach([7]);

        $gferland = factory(User::class)->create([
            'firstname' => 'Gabrielle',
            'lastname' => 'Ferland',
            'email' => 'gferland@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $gferland->departments()->attach([15]);

        $glanglois = factory(User::class)->create([
            'firstname' => 'Gabrielle',
            'lastname' => 'Langlois',
            'email' => 'glanglois@RHPharma.com',
            'password' => Hash::make('password')
        ]);


        $hblain = factory(User::class)->create([
            'firstname' => 'Hugues',
            'lastname' => 'Blain',
            'email' => 'hblain@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $hblain->departments()->attach([1,4,5,6]);

        $jcouture = factory(User::class)->create([
            'firstname' => 'Jodianne',
            'lastname' => 'Couture',
            'email' => 'jcouture@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $jcouture->departments()->attach([1,10]);

        $jdesbiens = factory(User::class)->create([
            'firstname' => 'Joëlle',
            'lastname' => 'Desbiens',
            'email' => 'jdesbiens@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $jdesbiens->departments()->attach([16,17]);

        $jdion = factory(User::class)->create([
            'firstname' => 'Jocelyn',
            'lastname' => 'Dion',
            'email' => 'jdion@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $jdion->departments()->attach([1,4,5,6,8]);

        $jduchesneau = factory(User::class)->create([
            'firstname' => 'Josée',
            'lastname' => 'Duchesneau',
            'email' => 'jduchesneau@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $jduchesneau->departments()->attach([1,9]);

        $jproulx = factory(User::class)->create([
            'firstname' => 'Josée',
            'lastname' => 'Proulx',
            'email' => 'jproulx@RHPharma.com',
            'password' => Hash::make('password')
        ]);

        $jleblond = factory(User::class)->create([
            'firstname' => 'Julie',
            'lastname' => 'Leblond',
            'email' => 'jleblond@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $jleblond->departments()->attach([1]);

        $jquenneville = factory(User::class)->create([
            'firstname' => 'Julie',
            'lastname' => 'Quenneville',
            'email' => 'jquenneville@RHPharma.com',
            'password' => Hash::make('password')
        ]);

        $llegeleux = factory(User::class)->create([
            'firstname' => 'Lorraine',
            'lastname' => 'Legeleux',
            'email' => 'llegeleux@RHPharma.com',
            'password' => Hash::make('password'),
            'is_active' => 0
        ]);
        $llegeleux->departments()->attach([8]);

        $mberteau = factory(User::class)->create([
            'firstname' => 'Mathieu',
            'lastname' => 'Berteau',
            'email' => 'mberteau@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mberteau->departments()->attach([2,11]);

        $mfbeauchesne = factory(User::class)->create([
            'firstname' => 'Marie-France',
            'lastname' => 'Beauchesne',
            'email' => 'mfbeauchesne@RHPharma.com',
            'password' => Hash::make('password'),
            'is_active' => 1,
            'is_manual' => 1
        ]);
        $mfbeauchesne->departments()->attach([2]);

        $mgilbert = factory(User::class)->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Gilbert',
            'email' => 'mgilbert@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mgilbert->departments()->attach([4,5,6,8]);

        $mguay = factory(User::class)->create([
            'firstname' => 'Marianne',
            'lastname' => 'Guay',
            'email' => 'mguay@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mguay->departments()->attach([1,9,10]);

        $mjlachance = factory(User::class)->create([
            'firstname' => 'Marie-Josée',
            'lastname' => 'Lachance',
            'email' => 'mjlachance@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mjlachance->departments()->attach([1,12]);

        $mlacerte = factory(User::class)->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Lacerte',
            'email' => 'mlacerte@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mlacerte->departments()->attach([4,5,6]);

        $mprousseau = factory(User::class)->create([
            'firstname' => 'Marie-Pierre',
            'lastname' => 'Rousseau',
            'email' => 'mprousseau@RHPharma.com',
            'password' => Hash::make('password')
        ]);

        $mturgeon = factory(User::class)->create([
            'firstname' => 'Martin',
            'lastname' => 'Turgeon',
            'email' => 'mturgeon@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mturgeon->departments()->attach([4,5,6]);

        $ndaviau = factory(User::class)->create([
            'firstname' => 'Nathalie',
            'lastname' => 'Daviau',
            'email' => 'ndaviau@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $ndaviau->departments()->attach([16,17]);

        $ngoettel = factory(User::class)->create([
            'firstname' => 'Nicolas',
            'lastname' => 'Goettel',
            'email' => 'ngoettel@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $ngoettel->departments()->attach([1,4,5,6,16,17]);

        $rbournival = factory(User::class)->create([
            'firstname' => 'Roxanne',
            'lastname' => 'Bournival',
            'email' => 'rbournival@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $rbournival->departments()->attach([7]);

        $rcoutu = factory(User::class)->create([
            'firstname' => 'Raphaël',
            'lastname' => 'Coutu',
            'email' => 'rcoutu@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $rcoutu->departments()->attach([2]);

        $sbergeron = factory(User::class)->create([
            'firstname' => 'Sabrina',
            'lastname' => 'Bergeron-Wolff',
            'email' => 'sbergeron@RHPharma.com',
            'password' => Hash::make('password'),
            'is_active' => 0
        ]);
        $sbergeron->departments()->attach([7]);


        $scloutier = factory(User::class)->create([
            'firstname' => 'Sylvie',
            'lastname' => 'Cloutier',
            'email' => 'scloutier@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $scloutier->departments()->attach([14]);

        $slamontagne = factory(User::class)->create([
            'firstname' => 'Sophie',
            'lastname' => 'Lamontagne',
            'email' => 'slamontagne@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $slamontagne->departments()->attach([16,17]);

        $sletendre = factory(User::class)->create([
            'firstname' => 'Sara',
            'lastname' => 'Letendre',
            'email' => 'sletendre@RHPharma.com',
            'password' => Hash::make('password')
        ]);

        $tjoly = factory(User::class)->create([
            'firstname' => 'Thomas',
            'lastname' => 'Joly-Mischlich',
            'email' => 'tjoly@RHPharma.com',
            'password' => Hash::make('password')
        ]);

        $vchiasson = factory(User::class)->create([
            'firstname' => 'Valérie',
            'lastname' => 'Chiasson-Roussel',
            'email' => 'vchiasson@RHPharma.com',
            'password' => Hash::make('password'),
            'workdays_per_week' => 3
        ]);
        $vchiasson->departments()->attach([1,14,16,17]);

        $vclement = factory(User::class)->create([
            'firstname' => 'Valérie',
            'lastname' => 'Clément',
            'email' => 'vclement@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $vclement->departments()->attach([1,10]);

        $vlord = factory(User::class)->create([
            'firstname' => 'Vicky',
            'lastname' => 'Lord',
            'email' => 'vlord@RHPharma.com',
            'password' => Hash::make('password'),
            'workdays_per_week' => 2
        ]);
        $vlord->departments()->attach([1,4,5,6,12]);
    }
}
