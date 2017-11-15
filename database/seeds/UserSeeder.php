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
            'password' => Hash::make('password')
            ]);

        $phm_user = factory(User::class)->create([
            'firstname' => 'PHM',
            'lastname' => 'User',
            'email' => 'phm_user@RHPharma.com',
            'password' => Hash::make('password')
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
        $aalongpre->departments()->attach([8]);

        $adubuc = factory(User::class)->create([
            'firstname' => 'Annie',
            'lastname' => 'Dubuc',
            'email' => 'adubuc@RHPharma.com',
            'password' => Hash::make('password'),
            'workdays_per_week' => 3
        ]);
        $adubuc->departments()->attach([4,5,6]);

        $bbeloin = factory(User::class)->create([
            'firstname' => 'Bianca',
            'lastname' => 'Beloin-Jubinville',
            'email' => 'bbeloin@RHPharma.com',
            'password' => Hash::make('password'),
        ]);
        $bbeloin->departments()->attach([2,8]);

        $cleclair = factory(User::class)->create([
            'firstname' => 'Christian',
            'lastname' => 'Leclair',
            'email' => 'cleclair@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $cleclair->departments()->attach([2,4,5,6]);

        $ebouchard = factory(User::class)->create([
            'firstname' => 'Émile',
            'lastname' => 'Bouchard',
            'email' => 'ebouchard@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $ebouchard->departments()->attach([2,4,5,6]);

        $jdion = factory(User::class)->create([
            'firstname' => 'Jocelyn',
            'lastname' => 'Dion',
            'email' => 'jdion@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $jdion->departments()->attach([4,5,6,8]);

        $hblain = factory(User::class)->create([
            'firstname' => 'Hugues',
            'lastname' => 'Blain',
            'email' => 'hblain@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $hblain->departments()->attach([4,5,6]);

        $llegeleux = factory(User::class)->create([
            'firstname' => 'Lorraine',
            'lastname' => 'Legeleux',
            'email' => 'llegeleux@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $llegeleux->departments()->attach([8]);

        $mberteau = factory(User::class)->create([
            'firstname' => 'Mathieu',
            'lastname' => 'Berteau',
            'email' => 'mberteau@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mberteau->departments()->attach([2]);

        $mfbeauchesne = factory(User::class)->create([
            'firstname' => 'Marie-France',
            'lastname' => 'Beauchesne',
            'email' => 'mfbeauchesne@RHPharma.com',
            'password' => Hash::make('password'),
            'is_active' => 0
        ]);
        $mfbeauchesne->departments()->attach([2]);

        $mgilbert = factory(User::class)->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Gilbert',
            'email' => 'mgilbert@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mgilbert->departments()->attach([4,5,6,8]);

        $mlacerte = factory(User::class)->create([
            'firstname' => 'Mélanie',
            'lastname' => 'Lacerte',
            'email' => 'mlacerte@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mlacerte->departments()->attach([4,5,6]);

        $mturgeon = factory(User::class)->create([
            'firstname' => 'Martin',
            'lastname' => 'Turgeon',
            'email' => 'mturgeon@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $mturgeon->departments()->attach([4,5,6]);

        $ngoettel = factory(User::class)->create([
            'firstname' => 'Nicolas',
            'lastname' => 'Goettel',
            'email' => 'ngoettel@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $ngoettel->departments()->attach([4,5,6]);

        $rcoutu = factory(User::class)->create([
            'firstname' => 'Raphaël',
            'lastname' => 'Coutu',
            'email' => 'rcoutu@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $rcoutu->departments()->attach([2]);

        $vlord = factory(User::class)->create([
            'firstname' => 'Vicky',
            'lastname' => 'Lord',
            'email' => 'vlord@RHPharma.com',
            'password' => Hash::make('password')
        ]);
        $vlord->departments()->attach([4,5,6]);
    }
}
