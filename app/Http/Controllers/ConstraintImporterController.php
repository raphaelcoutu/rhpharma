<?php

namespace App\Http\Controllers;


use App\Models\Constraint;
use App\Models\ConstraintType;
use App\Models\User;
use App\Services\AzureRepository;
use Illuminate\Http\Request;

class ConstraintImporterController extends Controller
{
    public function __construct(AzureRepository $azureRepository)
    {
        $this->azureRepository = $azureRepository;
    }

    public function index()
    {
        return view('constraintImporter.index');
    }

    public function import(Request $request)
    {
        $rows = $this->azureRepository->constraints($request['start'], $request['end']);

        $constraintsToAdd = [];

        $constraintTypes = ConstraintType::select('id', 'azure_id')->get();
        $missingConstraintTypesIds = [];

        $users = User::select('id', 'azure_id')->get();
        $missingUsers = [];

        foreach($rows as $row) {
            $constraintType = $constraintTypes->firstWhere('azure_id', $row['ConstraintType_id']);
            if($constraintType) {
                $weight = ($row['Weight'] === "TRUE") ? 1 : (($row['Weight'] == "FALSE") ? 0 : $row['Weight']);

                // On doit regarder dans la variable "day" ou "day1"...

                if($row['Day'] !== null) {
                    $day = $row['Day'];

                } else if($row['Day1'] !== null) {
                    $day = $row['Day1'];
                } else {
                    $day = null;
                }

                $user = $users->firstWhere('azure_id', $row['User_id']);
                if($user) {

                    $row = [
                        'user_id' => $user->id,
                        'start_datetime' => $row['StartDate'],
                        'end_datetime' => $row['EndDate'],
                        'constraint_type_id' => $constraintType->id,
                        'weight' => $row['Weight'],
                        'status' => $row['Status'],
                        'comment' => $row['Comment'],
                        'validated_by' => 1,
                        'number_of_occurrences' => ($row['NumberOfOccurrences'] !== "") ? $row['NumberOfOccurrences'] : null,
                        'day' => $day,
                        'disposition' => ($row['Disposition'] !== "") ? $row['Disposition'] : null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    array_push($constraintsToAdd, $row);
                } else {
                    array_push($missingUsers, [
                        'Id' => intval($row['User_id']),
                        'FirstName' => $row['FirstName'],
                        'LastName' => $row['LastName']
                    ]);
                }
            } else {
                array_push($missingConstraintTypesIds, intval($row['ConstraintType_id']));
            }

        }

        // Si l'array de missingConstraintTypes n'est pas null, on redirige vers erreur
        if (!empty($missingConstraintTypesIds)) {

            $missingConstraintTypes = $this->azureRepository->constraintTypesByIds($missingConstraintTypesIds);

            return redirect()->route('constraintImporter.index')
                    ->with('error', "ERREUR: Type(s) de contrainte non associée(s)")
                    ->with('missingConstraintTypes', $missingConstraintTypes);
        }

        // Si l'array de missingUsers n'est pas null, on redirige vers erreur
        if (!empty($missingUsers)) {

            $unique_array = [];
            foreach($missingUsers as $element) {
                $hash = $element['Id'];
                $unique_array[$hash] = $element;
            }
            $uniqueMissingUsers = array_values($unique_array);

            return redirect()->route('constraintImporter.index')
                    ->with('error', "ERREUR: Utilisateur(s) non associé(s)")
                    ->with('missingUsers', $uniqueMissingUsers);
        }

        Constraint::getQuery()->delete();
        \DB::table('constraints')->insert($constraintsToAdd);

        return redirect()->route('constraintImporter.index')
            ->with('status', 'Contraintes importées! ('. count($constraintsToAdd) . ')');
    }
}
