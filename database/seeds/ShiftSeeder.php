<?php

use App\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function create(string $code, int $departmentId, int $shiftTypeId, bool $default = false)
    {
        Shift::create([
            'code' => $code,
            'description' => '',
            'shift_type_id' => $shiftTypeId,
            'department_id' => $departmentId,
            'is_default' => $default
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Distribution HF = 18, Distribution HD = 19

        $this->create('C', 20, 10);

        $this->create('8HF', 18, 1);
        $this->create('9HF', 18, 2);
        $this->create('10HF', 18, 9);

        $this->create('8HD', 19, 1);
        $this->create('8HDam', 19, 4);
        $this->create('8HDpm', 19, 5);
        $this->create('10HD', 19, 9);

        $this->create('AC', 1, 1);
        $this->create('ACpm', 1, 7);

        $this->create('MI', 2, 1);

        $this->create('ON', 3, 1);
        $this->create('ON', 24, 1);
        $this->create('ON', 25, 1);
        $this->create('ON', 26, 1);
        $this->create('ON', 27, 1);

        $this->create('SIM', 4, 1);
        $this->create('SIC', 5, 1);
        $this->create('SIHD', 6, 1);

        $this->create('VIH', 7, 1);

        $this->create('SIPA', 8, 2);

        $this->create('L', 9, 1);

        $this->create('IC', 10, 1);

        $this->create('SP', 11, 1);

        $this->create('CIM', 12, 1);

        $this->create('AR', 13, 1);

        $this->create('ME', 14, 1);

        $this->create('P', 15, 1);

        $this->create('URHF', 16, 1);

        $this->create('URHD', 17, 1);

        $this->create('IR', 21,1);

        $this->create('DP', 23, 1);
    }
}
