<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'key' => 'departments_order',
            'value' => '[{"id":4,"active":true,"order":0},{"id":5,"active":true,"order":1},{"id":8,"active":true,"order":2},{"id":6,"active":true,"order":3},{"id":2,"active":true,"order":4},{"id":7,"active":false,"order":5},{"id":9,"active":false,"order":6},{"id":11,"active":false,"order":7},{"id":14,"active":false,"order":8},{"id":15,"active":false,"order":9},{"id":16,"active":false,"order":10},{"id":17,"active":false,"order":11}]'
        ]);

        Setting::create([
            'key' => 'triplets_order',
            'value' => '[]'
        ]);
    }
}
