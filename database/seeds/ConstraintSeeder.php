<?php

use App\ConstraintNote;
use App\User;
use Illuminate\Database\Seeder;

class ConstraintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $note = ConstraintNote::create([
            'content' => 'Allo bobo',
            'user_id' => 1,
            'constraint_id' => 1,
            'is_secret_note' => 0
        ]);

        $note->users()->save(User::find(1));
    }
}
