<?php

use App\AssignedShift;
use App\Constraint;
use App\Holiday;
use App\Schedule;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BranchSeeder::class);
        $this->call(DepartmentTypeSeeder::class);
        $this->call(WorkplaceDepartmentSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ConstraintTypeSeeder::class);
        $this->call(ShiftTypeSeeder::class);
        $this->call(ShiftSeeder::class);
//        $this->call(SettingSeeder::class);
        $this->call(TripletSeeder::class);

//        $departmentsSettings = Setting::where('key', 'departments_order')->get()->first();
//        $departmentsSettings->value = '[{"id":4,"active":true,"order":0},{"id":7,"active":true,"order":1},{"id":5,"active":true,"order":2},{"id":8,"active":true,"order":3},{"id":2,"active":true,"order":4},{"id":11,"active":true,"order":5},{"id":6,"active":true,"order":6},{"id":9,"active":true,"order":7},{"id":14,"active":true,"order":8},{"id":15,"active":true,"order":9},{"id":16,"active":true,"order":10},{"id":17,"active":true,"order":11},{"id":10,"active":true,"order":12},{"id":12,"active":true,"order":13},{"id":3,"active":true,"order":14},{"id":21,"active":true,"order":15},{"id":24,"active":true,"order":16},{"id":25,"active":true,"order":17},{"id":26,"active":true,"order":18},{"id":27,"active":false,"order":19}]';
//        $departmentsSettings->save();

        Holiday::create(['description' => 'St-Jean-Baptiste 2018', 'date' => '2018-06-25']);
        Holiday::create(['description' => 'Fête Canada 2018', 'date' => '2018-07-02']);
        Holiday::create(['description' => 'Fête du travail 2018', 'date' => '2018-09-03']);


        Schedule::create([
            'name' => '2018-05-13 au 2018-06-23',
            'branch_id' => 1,
            'limit_date_weekends' => now(),
            'limit_date' => Carbon::parse('2018-04-15'),
            'start_date' => Carbon::parse('2018-05-13'),
            'end_date' => Carbon::parse('2018-06-23')
        ]);

        Schedule::create([
            'name' => '2018-06-24 au 2018-09-15',
            'branch_id' => 1,
            'limit_date_weekends' => now(),
            'limit_date' => Carbon::parse('2018-05-20'),
            'start_date' => Carbon::parse('2018-06-24'),
            'end_date' => Carbon::parse('2018-09-15')
        ]);

        Schedule::create([
            'name' => '2018-09-16 au 2018-10-27',
            'branch_id' => 1,
            'limit_date_weekends' => now(),
            'limit_date' => now()->addDay(1),
            'start_date' => Carbon::parse('2018-09-16'),
            'end_date' => Carbon::parse('2018-10-27')
        ]);

        Schedule::create([
            'name' => '2018-10-28 au 2018-12-08',
            'branch_id' => 1,
            'limit_date_weekends' => now(),
            'limit_date' => now()->addDay(1),
            'start_date' => Carbon::parse('2018-10-28'),
            'end_date' => Carbon::parse('2018-12-08')
        ]);

        Schedule::create([
            'name' => '2018-12-09 au 2019-02-02',
            'branch_id' => 1,
            'limit_date_weekends' => now(),
            'limit_date' => now()->addDay(1),
            'start_date' => Carbon::parse('2018-12-09'),
            'end_date' => Carbon::parse('2019-02-02')
        ]);

        Schedule::create([
            'name' => '2019-02-03 au 2019-03-16',
            'branch_id' => 1,
            'limit_date_weekends' => now(),
            'limit_date' => now()->addDay(1),
            'start_date' => Carbon::parse('2019-02-03'),
            'end_date' => Carbon::parse('2019-03-16')
        ]);
    }

    private function createDayOff(string $date, array $userIds)
    {
        $parsedDate = Carbon::parse($date);

        foreach ($userIds as $userId) {
            $assigned[] = [
                'user_id' => $userId,
                'shift_id' => 1,
                'is_generated' => 0,
                'is_published' => 0,
                'date' => $parsedDate
            ];
        }

        AssignedShift::insert($assigned);
    }

    private function createShift(string $date, int $shiftId, array $userIds)
    {
        $parsedDate = Carbon::parse($date);

        foreach ($userIds as $userId) {
            $assigned[] = [
                'user_id' => $userId,
                'shift_id' => $shiftId,
                'is_generated' => 0,
                'is_published' => 0,
                'date' => $parsedDate
            ];
        }

        AssignedShift::insert($assigned);
    }

    private function createAssignedShift(string $date, array $userIds)
    {
        $shifts = [2,3,4,5,8];
        $assigned = [];
        $constraints = [];
        $parsedDate = Carbon::parse($date);

        foreach($userIds as $index => $userId) {
            $assigned[] = [
                'user_id' => $userId,
                'shift_id' => $shifts[$index],
                'is_generated' => 0,
                'is_published' => 0,
                'date' => $parsedDate
            ];
        }

        if($parsedDate->dayOfWeek === 6) {

            foreach($userIds as $index => $userId) {
                $constraints[] = [
                    'user_id' => $userId,
                    'start_datetime' => $parsedDate->copy()->addDays(-1)->setTime(0,0),
                    'end_datetime' => $parsedDate->copy()->addDays(-1)->setTime(23,59),
                    'constraint_type_id' => 48,
                    'weight' => 1,
                    'comment' => 'AUTO-GENERATED',
                    'status' => 1,
                    'validated_by' => 1,
                ];
            }

            Constraint::insert($constraints);
        }

        AssignedShift::insert($assigned);
    }
}
