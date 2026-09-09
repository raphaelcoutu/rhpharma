<?php

use App\Events\UpdateBuildStatus;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ConflictController;
use App\Http\Controllers\ConstraintController;
use App\Http\Controllers\ConstraintImporterController;
use App\Http\Controllers\ConstraintTypeController;
use App\Http\Controllers\ConstraintValidatorController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentUserController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleStatDepartmentController;
use App\Http\Controllers\SettingConstraintTypeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkplaceController;
use App\Jobs\BuildClinicalDepartments;
use App\Models\AssignedShift;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

Route::get('/scheduler', function () {
    $users = User::query()
        ->select('id', 'firstname', 'lastname')
        ->where('branch_id', 1)
        ->where('is_active', true)
        ->orderBy('lastname')
        ->get();

    $schedule = Schedule::find(10);

    $shifts = AssignedShift::with('shift')
        ->inDateInterval($schedule->start_date, $schedule->end_date)
        ->get();

    return Inertia::render('scheduler', [
        'users' => $users,
        'schedule' => $schedule,
        'shifts' => $shifts,
    ]);
});

Route::group(['middleware' => 'auth'], function () {

    // Branches
    Route::get('branches', [BranchController::class, 'index'])->name('branches.index');
    Route::post('branches/store', [BranchController::class, 'store'])->name('branches.store');
    Route::put('branches/{branch}', [BranchController::class, 'update'])->name('branches.update');

    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

    // Roles
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');

    // Workplaces
    Route::get('workplaces', [WorkplaceController::class, 'index'])->name('workplaces.index');
    Route::get('workplaces/create', [WorkplaceController::class, 'create'])->name('workplaces.create');
    Route::post('workplaces', [WorkplaceController::class, 'store'])->name('workplaces.store');
    Route::get('workplaces/{workplace}/edit', [WorkplaceController::class, 'edit'])->name('workplaces.edit');
    Route::put('workplaces/{workplace}', [WorkplaceController::class, 'update'])->name('workplaces.update');
    Route::get('workplaces/{workplace}', [WorkplaceController::class, 'show'])->name('workplaces.show');

    // Departments
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');

    // Schedules
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::get('schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::get('schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');

    // Holidays
    Route::get('holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::post('holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::patch('holidays/{holiday}', [HolidayController::class, 'update'])->name('holidays.update');

    // ConstraintImporter
    Route::get('constraintImporter', [ConstraintImporterController::class, 'index'])->name('constraintImporter.index');
    Route::get('constraintImporter/import{start?}{end?}', [ConstraintImporterController::class, 'import'])->name('constraintImporter.import');

    // ConstraintTypes
    Route::get('constraintTypes', [ConstraintTypeController::class, 'index'])->name('constraintTypes.index');
    Route::get('constraintTypes/create', [ConstraintTypeController::class, 'create'])->name('constraintTypes.create');
    Route::get('constraintTypes/{constraintType}/edit', [ConstraintTypeController::class, 'edit'])->name('constraintTypes.edit');
    Route::post('constraintTypes', [ConstraintTypeController::class, 'store'])->name('constraintTypes.store');
    Route::put('constraintTypes/{constraintType}', [ConstraintTypeController::class, 'update'])->name('constraintTypes.update');

    Route::get('constraints', [ConstraintController::class, 'index'])->name('constraints.index');

    // ConstraintsValidator
    Route::get('constraintsValidator', [ConstraintValidatorController::class, 'index'])->name('constraintsValidator.index');
    Route::get('constraintsValidator/history', [ConstraintValidatorController::class, 'history'])->name('constraintsValidator.history');

    // Calendar
    Route::get('calendar/{schedule}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::get('calendar/{schedule}/byDepartment/{department}', [CalendarController::class, 'showByDepartment'])->name('calendar.showByDepartment');

    // Shifts
    Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('shifts/{shift}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
    Route::post('shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');

    // ShiftTypes
    Route::get('shiftTypes', [ShiftTypeController::class, 'index'])->name('shiftTypes.index');
    Route::get('shiftTypes/create', [ShiftTypeController::class, 'create'])->name('shiftTypes.create');
    Route::post('shiftTypes', [ShiftTypeController::class, 'store'])->name('shiftTypes.store');
    Route::get('shiftTypes/{shiftType}/edit', [ShiftTypeController::class, 'edit'])->name('shiftTypes.edit');
    Route::post('shiftTypes/{shiftType}', [ShiftTypeController::class, 'update'])->name('shiftTypes.update');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('settings/constraintTypes', [SettingConstraintTypeController::class, 'index'])->name('settings.constraintTypes');
    Route::get('settings/departments', [SettingController::class, 'departments'])->name('settings.departments');

    // Exports
    Route::get('export/{schedule}', [ExportController::class, 'export'])->name('export');

    if (App::environment('local')) {
        Route::get('build/{scheduleId}', function ($scheduleId) {
            $event = new UpdateBuildStatus($scheduleId, 3, 3);
            dispatch(new BuildClinicalDepartments($event));
        });
    }
});

require __DIR__.'/auth.php';

// Other browser-session endpoints are used by legacy client-side components.
Route::prefix('api')->middleware('auth')->group(function () {
    // Calendar
    Route::get('calendar/getShifts', [CalendarController::class, 'getShifts']);
    Route::get('calendar/getUserData', [CalendarController::class, 'getUserData']);
    Route::post('calendar/setUserData', [CalendarController::class, 'setUserData']);
    Route::post('calendar/setSelectedData', [CalendarController::class, 'setSelectedData']);

    // Conflicts
    Route::get('conflicts/{scheduleId}', [ConflictController::class, 'fetch']);

    // Constraints
    Route::get('constraints/fixed', [ConstraintController::class, 'fetchFixed']);
    Route::get('constraints/availability', [ConstraintController::class, 'fetchAvailability']);
    Route::get('constraints/{id}/edit', [ConstraintController::class, 'edit']);
    Route::post('constraints/store', [ConstraintController::class, 'store']);
    Route::put('constraints/{id}/update', [ConstraintController::class, 'update']);

    // ConstraintTypes
    Route::get('constraintTypes', [ConstraintTypeController::class, 'fetch']);

    // ConstraintValidator
    Route::put('constraintsValidator/{id}', [ConstraintValidatorController::class, 'update']);

    // Departments-Users
    Route::get('departmentUsers/{id}', [DepartmentUserController::class, 'fetch']);
    Route::post('departmentUsers/{id}/store', [DepartmentUserController::class, 'store']);
    Route::delete('departmentUsers/{id}', [DepartmentUserController::class, 'destroy']);

    // Schedule
    Route::post('schedules/updateStatus', [BuildController::class, 'updateStatus']);
    Route::put('schedules/{id}/updateNotes', [ScheduleController::class, 'updateNotes']);

    // Schedule-Stats-Departments
    Route::get('scheduleStatDepartment/{scheduleId}', [ScheduleStatDepartmentController::class, 'show']);
    Route::get('scheduleStatDepartment/{scheduleId}/create', [ScheduleStatDepartmentController::class, 'create']);

    // Settings
    Route::patch('settings/departments', [SettingController::class, 'updateDepartments']);
    Route::patch('settings/triplets', [SettingController::class, 'updateTriplets']);

    // Settings-ConstraintTypes
    Route::patch('settings/constraintTypes', [SettingConstraintTypeController::class, 'update']);

    // Settings-Department-User
    Route::patch('settings/departmentUser', [SettingController::class, 'updateDepartmentUser']);
});
