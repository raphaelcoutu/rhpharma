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
        factory(User::class, 1)->create([
            'firstname' => 'PHM',
            'lastname' => 'Admin',
            'email' => 'phm_admin@RHPharma.com',
            'password' => Hash::make('password')
            ]);

        factory(User::class, 1)->create([
            'firstname' => 'PHM',
            'lastname' => 'User',
            'email' => 'phm_user@RHPharma.com',
            'password' => Hash::make('password')
        ]);

        factory(User::class, 1)->create([
            'firstname' => 'ATP',
            'lastname' => 'Admin',
            'email' => 'atp_admin@RHPharma.com',
            'password' => Hash::make('password'),
            'branch_id' => 2
        ]);

        factory(User::class, 1)->create([
            'firstname' => 'ATP',
            'lastname' => 'User',
            'email' => 'atp_user@RHPharma.com',
            'password' => Hash::make('password'),
            'branch_id' => 2
        ]);
    }
}
