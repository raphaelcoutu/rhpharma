<?php

namespace App\Http\Controllers;


use PDO;
use App\Models\User;
use App\Models\Constraint;
use App\Models\ConstraintType;
use Illuminate\Http\Request;

class ConstraintImporterController extends Controller
{
    public function index()
    {
        return view('constraintImporter.index');
    }

    public function import(Request $request)
    {
        $rows = $this->queryConstraints($request['start'], $request['end']);

        $constraintsToAdd = [];

        $constraintTypes = ConstraintType::select('id', 'azure_id')->get();
        $missingConstraintTypesIds = [];

        $users = User::select('id', 'azure_id')->get();
        $missingUsersIds = [];

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
                    array_push($missingUsersIds, intval($row['User_id']));
                }
            } else {
                array_push($missingConstraintTypesIds, intval($row['ConstraintType_id']));
            }

        }

        // Si l'array de missingConstraintTypes n'est pas null, on redirige vers erreur
        if (!empty($missingConstraintTypesIds)) {

            $missingConstraintTypes = $this->getConstraintTypesByAzureIds($missingConstraintTypesIds);

            return redirect()->route('constraintImporter.index')
                    ->with('error', "ERREUR: Type(s) de contrainte non associée(s)")
                    ->with('missingConstraintTypes', $missingConstraintTypes);
        }

        // Si l'array de missingUsers n'est pas null, on redirige vers erreur
        if (!empty($missingUsersIds)) {

            $missingUsers = $this->getUsersByAzureIds($missingUsersIds);

            return redirect()->route('constraintImporter.index')
                    ->with('error', "ERREUR: Utilisateur(s) non associé(s)")
                    ->with('missingUsers', $missingUsers);
        }

        Constraint::getQuery()->delete();
        \DB::table('constraints')->insert($constraintsToAdd);

        return redirect()->route('constraintImporter.index')
            ->with('status', 'Contraintes importées! ('. count($constraintsToAdd) . ')');
    }

    private function queryConstraints($startDate, $endDate)
    {
        $time_start = microtime(true);

        $conn = new PDO('sqlsrv:server='.config('azure.host').';Database='.config('azure.database'),
            config('azure.username'), config('azure.password'));
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $tsql = "SELECT U.Id as User_id, U.FirstName, U.LastName,
            C.Id as Constraint_id, C.StartDate, C.EndDate, C.Weight, C.Comment, C.Status, C.NumberOfOccurrences, C.Disposition, C.IsDayOfWeek, C.Day, C.Day1, C.Discriminator,
            Ct.Id as ConstraintType_id, Ct.Name
            FROM Constraints As C
            JOIN ConstraintTypes AS Ct ON Ct.Id = C.TypeId
            JOIN Users As U ON U.Id = C.UserId
            WHERE ((StartDate >= ? AND EndDate <= ?) OR StartDate <= ? AND EndDate >= ?) AND TypeID <> 79
            ORDER BY U.Lastname";
        $getResults = $conn->prepare($tsql);
        $getResults->execute([$startDate, $endDate, $endDate, $startDate]);
        $results = $getResults->fetchAll(PDO::FETCH_ASSOC);

        $time_end = microtime(true);
        $execution_time = round((($time_end - $time_start)*1000),2);

        return $results;

    }

    private function getConstraintTypesByAzureIds($ids) {
        $conn = new PDO('sqlsrv:server='.config('azure.host').';Database='.config('azure.database'),
            config('azure.username'), config('azure.password'));
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $qMarks = str_repeat('?,', count($ids) -1) . '?';

        $tsql = "SELECT Id, Name, Description
            FROM ConstraintTypes As CT
            WHERE Id IN ($qMarks)";
        $getResult = $conn->prepare($tsql);
        $getResult->execute($ids);
        $result = $getResult->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    private function getUsersByAzureIds($ids) {
        $conn = new PDO('sqlsrv:server='.config('azure.host').';Database='.config('azure.database'),
            config('azure.username'), config('azure.password'));
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $qMarks = str_repeat('?,', count($ids) -1) . '?';

        $tsql = "SELECT Id, FirstName, LastName
            FROM Users As U
            WHERE Id IN ($qMarks)";
        $getResult = $conn->prepare($tsql);
        $getResult->execute($ids);
        $result = $getResult->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
