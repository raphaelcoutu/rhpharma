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
        $this->call(SettingSeeder::class);
        $this->call(TripletSeeder::class);

        $departmentsSettings = Setting::where('key', 'departments_order')->get()->first();
        $departmentsSettings->value = '[{"id":4,"active":true,"order":0},{"id":7,"active":true,"order":1},{"id":5,"active":true,"order":2},{"id":8,"active":true,"order":3},{"id":2,"active":true,"order":4},{"id":11,"active":true,"order":5},{"id":6,"active":true,"order":6},{"id":9,"active":true,"order":7},{"id":14,"active":true,"order":8},{"id":15,"active":true,"order":9},{"id":16,"active":true,"order":10},{"id":17,"active":true,"order":11},{"id":10,"active":true,"order":12},{"id":12,"active":true,"order":13},{"id":3,"active":true,"order":14},{"id":21,"active":true,"order":15},{"id":24,"active":true,"order":16},{"id":25,"active":true,"order":17},{"id":26,"active":true,"order":18},{"id":27,"active":false,"order":19}]';
        $departmentsSettings->save();

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

        // Weekends
        $this->createAssignedShift('2018-06-24', [46,40,31,21,50]);
        $this->createAssignedShift('2018-06-25', [32,31,46,21,50]);
        $this->createShift('2018-06-25', 12,[40,7]);
        $this->createDayOff('2018-06-26', [40,50]);
        $this->createDayOff('2018-06-27', [40,50]);
        $this->createDayOff('2018-06-28', [46,31,21]);
        $this->createDayOff('2018-06-29', [46,31,21]);

        $this->createAssignedShift('2018-06-30', [23,27,41,12,28]);
        $this->createAssignedShift('2018-07-01', [27,41,23,12,28]);
        $this->createAssignedShift('2018-07-02', [42,23,27,16,12]);
        $this->createShift('2018-07-02', 12, [41,28]);
        $this->createDayOff('2018-07-03', [27,28]);
        $this->createDayOff('2018-07-04', [27,28]);
        $this->createDayOff('2018-07-05', [23,41,12]);
        $this->createDayOff('2018-07-06', [23,41,12]);

        $this->createAssignedShift('2018-07-07', [20,32,7,33,4]);
        $this->createAssignedShift('2018-07-08', [32,7,20,4,33]);
        $this->createDayOff('2018-07-09', [32,4]);
        $this->createDayOff('2018-07-10', [32,4]);
        $this->createDayOff('2018-07-12', [20,7,33]);
        $this->createDayOff('2018-07-13', [20,7,33]);

        $this->createAssignedShift('2018-07-14', [5,15,8,24,49]);
        $this->createAssignedShift('2018-07-15', [15,8,5,49,24]);
        $this->createDayOff('2018-07-16', [15,49]);
        $this->createDayOff('2018-07-17', [15,49]);
        $this->createDayOff('2018-07-19', [5,8,24,18]);
        $this->createDayOff('2018-07-20', [5,8,24]);

        $this->createAssignedShift('2018-07-21', [23,42,37,18,39]);
        $this->createAssignedShift('2018-07-22', [42,37,23,39,18]);
        $this->createDayOff('2018-07-23', [42,39]);
        $this->createDayOff('2018-07-24', [42,39,18]);
        $this->createDayOff('2018-07-26', [23,37,13]);
        $this->createDayOff('2018-07-27', [23,37]);

        $this->createAssignedShift('2018-07-28', [16,11,13,9,35]);
        $this->createAssignedShift('2018-07-29', [11,13,16,35,9]);
        $this->createDayOff('2018-07-30', [11,35]);
        $this->createDayOff('2018-07-31', [11,35,13]);
        $this->createDayOff('2018-08-02', [16,9]);
        $this->createDayOff('2018-08-03', [16,9]);

        $this->createAssignedShift('2018-08-04', [47,31,22,38,51]);
        $this->createAssignedShift('2018-08-05', [31,22,47,51,38]);
        $this->createDayOff('2018-08-06', [31,51]);
        $this->createDayOff('2018-08-07', [31,51]);
        $this->createDayOff('2018-08-09', [47,22,38]);
        $this->createDayOff('2018-08-10', [47,22,38]);

        $this->createAssignedShift('2018-08-11', [45,50,5,21,25]);
        $this->createAssignedShift('2018-08-12', [50,5,45,25,21]);
        $this->createDayOff('2018-08-13', [50,25]);
        $this->createDayOff('2018-08-14', [50,25]);
        $this->createDayOff('2018-08-16', [45,5,21]);
        $this->createDayOff('2018-08-17', [45,5,21]);

        $this->createAssignedShift('2018-08-18', [2,27,28,40,29]);
        $this->createAssignedShift('2018-08-19', [27,28,2,29,40]);
        $this->createDayOff('2018-08-20', [27,29]);
        $this->createDayOff('2018-08-21', [27,29]);
        $this->createDayOff('2018-08-23', [2,28,40]);
        $this->createDayOff('2018-08-24', [2,28,40]);

        $this->createAssignedShift('2018-08-25', [46,4,33,41,12]);
        $this->createAssignedShift('2018-08-26', [4,33,46,12,41]);
        $this->createDayOff('2018-08-27', [4,12]);
        $this->createDayOff('2018-08-28', [4,12]);
        $this->createDayOff('2018-08-30', [46,33,41]);
        $this->createDayOff('2018-08-31', [46,33,41]);

        $this->createAssignedShift('2018-09-01', [34,14,15,24,35]);
        $this->createAssignedShift('2018-09-02', [14,15,34,35,24]);
        $this->createAssignedShift('2018-09-03', [8,34,14,39,24]);
        $this->createShift('2018-09-03', 12,[15,35]);
        $this->createDayOff('2018-09-04', [14,35]);
        $this->createDayOff('2018-09-05', [14,35]);
        $this->createDayOff('2018-09-06', [34,15,24]);
        $this->createDayOff('2018-09-07', [34,15,24]);

        $this->createAssignedShift('2018-09-08', [8,7,20,49,9]);
        $this->createAssignedShift('2018-09-09', [7,20,8,9,49]);
        $this->createDayOff('2018-09-10', [7,9]);
        $this->createDayOff('2018-09-11', [7,9]);
        $this->createDayOff('2018-09-13', [8,20,49]);
        $this->createDayOff('2018-09-14', [8,20,49]);

        $this->createAssignedShift('2018-09-15', [42,47,39,32,38]);

        //Donner la première semaine de l'horaire à Émile car il commence le vendredi
        $this->createShift('2018-06-26', 11, [12]);
        $this->createShift('2018-06-27', 11, [12]);
        $this->createShift('2018-06-28', 11, [12]);

        $this->createShift('2018-08-13', 11, [30]);
        $this->createShift('2018-08-14', 11, [30]);
        $this->createShift('2018-08-15', 11, [30]);
        $this->createShift('2018-08-17', 11, [30]);
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
