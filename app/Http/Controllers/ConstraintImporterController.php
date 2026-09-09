<?php

namespace App\Http\Controllers;

use App\Models\Constraint;
use App\Models\ConstraintType;
use App\Models\User;
use App\Services\AzureRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ConstraintImporterController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('constraintImporter/index', [
            'status' => session('status'),
            'newUsers' => session('newUsers', []),
            'newConstraintTypes' => session('newConstraintTypes', []),
            'missingUsers' => session('missingUsers', []),
        ]);
    }

    public function import(Request $request, AzureRepository $azureRepository): RedirectResponse
    {
        $rows = $azureRepository->constraints($request['start'], $request['end']);

        $constraintsToAdd = [];

        $constraintTypes = ConstraintType::select('id', 'azure_id')->get();
        $missingConstraintTypesIds = [];

        $users = User::select('id', 'azure_id')->get();
        $missingUsers = [];

        foreach ($rows as $row) {
            $constraintType = $constraintTypes->firstWhere('azure_id', $row['ConstraintType_id']);
            if ($constraintType) {
                $weight = ($row['Weight'] === 'TRUE') ? 1 : (($row['Weight'] == 'FALSE') ? 0 : $row['Weight']);

                // On doit regarder dans la variable "day" ou "day1"...

                if ($row['Day'] !== null) {
                    $day = $row['Day'];

                } elseif ($row['Day1'] !== null) {
                    $day = $row['Day1'];
                } else {
                    $day = null;
                }

                $user = $users->firstWhere('azure_id', $row['User_id']);
                if ($user) {

                    $row = [
                        'user_id' => $user->id,
                        'start_datetime' => $row['StartDate'],
                        'end_datetime' => $row['EndDate'],
                        'constraint_type_id' => $constraintType->id,
                        'weight' => $row['Weight'],
                        'status' => $row['Status'],
                        'comment' => $row['Comment'],
                        'validated_by' => 1,
                        'number_of_occurrences' => ($row['NumberOfOccurrences'] !== '') ? $row['NumberOfOccurrences'] : null,
                        'day' => $day,
                        'disposition' => ($row['Disposition'] !== '') ? $row['Disposition'] : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $constraintsToAdd[] = $row;
                } else {
                    $missingUsers[] = [
                        'Id' => intval($row['User_id']),
                        'FirstName' => $row['FirstName'],
                        'LastName' => $row['LastName'],
                    ];
                }
            } else {
                $missingConstraintTypesIds[] = intval($row['ConstraintType_id']);
            }

        }

        // Si l'array de missingConstraintTypes n'est pas null, on redirige vers erreur
        $newConstraintTypes = [];
        if (! empty($missingConstraintTypesIds)) {

            $newConstraintTypes = $azureRepository->constraintTypesByIds($missingConstraintTypesIds);

            foreach ($newConstraintTypes as $constraintType) {
                ConstraintType::updateOrCreate([
                    'branch_id' => $constraintType['BranchId'],
                    'azure_id' => $constraintType['Id'],
                    'name' => $constraintType['Name'],
                    'description' => $constraintType['Description'],
                    'code' => $constraintType['Code'],
                    'is_work' => $constraintType['IsWork'],
                    'is_single_day' => $constraintType['IsSingleDay'],
                    'is_group_constraint' => $constraintType['IsGroupConstraint'],
                    'is_day_in_schedule' => $constraintType['IsDayInSchedule'],
                ]);
            }
        }

        // Si l'array de missingUsers n'est pas null, on redirige vers erreur
        $newUsers = [];
        if (! empty($missingUsers)) {

            $unique_array = [];
            foreach ($missingUsers as $element) {
                $hash = $element['Id'];
                $unique_array[$hash] = $element;
            }

            $missingUsers = array_values($unique_array);
            $uniqueMissingUserIds = collect($missingUsers)->pluck('Id')->toArray();
            $newUsers = $azureRepository->usersByIds($uniqueMissingUserIds);

            foreach ($newUsers as $user) {
                User::updateOrCreate([
                    'azure_id' => $user['Id'],
                    'branch_id' => $user['BranchId'],
                    'lastname' => $user['LastName'],
                    'firstname' => $user['FirstName'],
                    'password' => Hash::make(Str::random(12)),
                    'email' => $user['Email'],
                    'workdays_per_week' => $user['WorkdaysPerWeek'],
                ]);
            }
        }

        Constraint::getQuery()->delete();
        \DB::table('constraints')->insert($constraintsToAdd);

        return redirect()->route('constraintImporter.index')
            ->with('status', 'Contraintes importées! ('.count($constraintsToAdd).')')
            ->with('newUsers', $newUsers)
            ->with('newConstraintTypes', $newConstraintTypes)
            ->with('missingUsers', $missingUsers);
    }
}
