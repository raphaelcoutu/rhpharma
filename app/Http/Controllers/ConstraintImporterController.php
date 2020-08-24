<?php

namespace App\Http\Controllers;


use App\Constraint;
use Illuminate\Http\Request;
use PDO;

class ConstraintImporterController extends Controller
{
    private $userIds = [
        4 => 45,
        5 => 34,
        1025 => 15,
        1026 => 41,
        1027 => 46,
        1028 => 47,
        1029 => 42,
        1030 => 3,
        1031 => 21,
        1032 => 37,
        1033 => 22,
        1034 => 17,
        1035 => 31,
        1036 => 9,
        1037 => 26,
        1038 => 28,
        1039 => 10,
        1040 => 35,
        1042 => 14,
        1043 => 44,
        1044 => 29,
        1045 => 33,
        1046 => 7,
        1047 => 11,
        1048 => 6,
        1049 => 23,
        1050 => 32,
        1051 => 36,
        1053 => 8,
        1054 => 18,
        1055 => 20,
        1056 => 12,
        1057 => 38,
        1058 => 40,
        1059 => 16,
        1060 => 30,
        1063 => 24,
        1064 => 43,
        1066 => 39,
        1067 => 25,
        1069 => 48,
        1070 => 4,
        1071 => 13,
        1072 => 19,
        1073 => 5,
        1074 => 27,
        1078 => 50,
        1080 => 52,
        1081 => 2,
        1082 => 49,
        1083 => 51,
        1084 => 53,
        1085 => 54,
        1086 => 55,
        1087 => 56
    ];
    private $constraintsIds = [
        67 => 32,
        53 => 33,
        //79 (Travailler FDS)
        62 => 5,
        61 => 4,
        68 => 29,
        97 => 22,
        130 => 34,
        59 => 35,
        96 => 38,
        66 => 9,
        92 => 39,
        58 => 36,
        69 => 30,
        72 => 40,
        76 => 13,
        136 => 41,
        118 => 42,
        121 => 43,
        137 => 44,
        138 => 45,
        139 => 46,
        77 => 14,
        82 => 47,
        71 => 48,
        55 => 49,
        56 => 50,
        57 => 51,
        117 => 52,
        80 => 53,
        70 => 31,
        93 => 19,
        94 => 20,
        88 => 16,
        78 => 15,
        63 => 6,
        100 => 54,
        113 => 55,
        86 => 56,
        116 => 57,
        91 => 18,
        143 => 58,
        144 => 59,
        145 => 60,
        146 => 61,
        54 => 1,
        73 => 10,
        74 => 11,
        75 => 12,
        84 => 62,
        103 => 26,
        123 => 63,
        127 => 21,
        124 => 64,
        64 => 7,
        141 => 65,
        142 => 66,
        102 => 24,
        126 => 25,
        104 => 27,
        105 => 28,
        147 => 67,
        148 => 68,
        149 => 69,
        114 => 70,
        85 => 71,
        65 => 8,
        131 => 2,
        122 => 73,
        115 => 74,
        60 => 75,
        101 => 76,
        150 => 77
    ];

    public function index()
    {
        return view('constraintImporter.index');
    }

    public function import(Request $request)
    {
        $rows = $this->query($request['start'], $request['end']);

        // TEST
//        array_push($rows,
//            ["User_id" => "1048",
//                "FirstName" => "TEST*TEST*TEST",
//                "LastName" => "TEST*TEST*TEST",
//                "Constraint_id" => "4436",
//                "StartDate" => "2019-04-28 08:00:00.000",
//                "EndDate" => "2019-06-08 16:30:00.000",
//                "Weight" => "1",
//                "Comment" => "Cours soir udes",
//                "Status" => "1",
//                "NumberOfOccurrences" => null,
//                "Disposition" => null,
//                "IsDayOfWeek" => null,
//                "Day" => null,
//                "Day1" => "1",
//                "Discriminator" => "RecurrentConstraint",
//                "ConstraintType_id" => "9999", //Test
//                "Name" => "Travailler de jour (avant 16h30)"
//            ]);

        $constraints = [];

        foreach($rows as $row) {
            if(array_key_exists($row['ConstraintType_id'], $this->constraintsIds)) {
                $weight = ($row['Weight'] === "TRUE") ? 1 : (($row['Weight'] == "FALSE") ? 0 : $row['Weight']);

                // On doit regarder dans la variable "day" ou "day1"...

                if($row['Day'] !== null) {
                    $day = $row['Day'];

                } else if($row['Day1'] !== null) {
                    $day = $row['Day1'];
                } else {
                    $day = null;
                }

                $row = [
                    'user_id' => $this->userIds[$row['User_id']],
                    'start_datetime' => $row['StartDate'],
                    'end_datetime' => $row['EndDate'],
                    'constraint_type_id' => $this->constraintsIds[$row['ConstraintType_id']],
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

                array_push($constraints, $row);
            } else {
//                throw new \Exception("Constrainte manquante : {$row['ConstraintType_id']}");
                return redirect()->route('constraintImporter.index')
                    ->with('error', "Type de Constrainte manquant : id #{$row['ConstraintType_id']}");
            }

        }

        Constraint::getQuery()->delete();
        \DB::table('constraints')->insert($constraints);

        return redirect()->route('constraintImporter.index')
            ->with('status', 'Contraintes importées! ('. count($constraints) . ')');
    }

    private function query($startDate, $endDate)
    {
        $time_start = microtime(true);

        $conn = new PDO('sqlsrv:server='.env('AZURE_HOST').';Database='.env('AZURE_DATABASE'),
            env('AZURE_USERNAME'), env('AZURE_PASSWORD'));
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
}
