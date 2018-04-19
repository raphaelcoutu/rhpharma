<?php

namespace App\Console\Commands;

use App\Constraint;
use Illuminate\Console\Command;

class ImportCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importer les contraintes de RHPharma (Azure)';

    protected $userIds;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->userIds = [
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
            1069 => 49,
            1070 => 4,
            1071 => 13,
            1072 => 19,
            1073 => 5,
            1074 => 27,
            1078 => 50,
            1080 => 52,
            1081 => 2,
            1082 => 49,
            1083 => 51
        ];
        $this->constraintsIds = [
            61 => 4,
            67 => 32,
            53 => 33
        ];

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $constraints = [];

        ini_set('auto_detect_line_endings',TRUE);
        $row = 1;
        if (($handle = fopen("public/Constraint_csv_20180416.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                // On enlève le header
                if($row == 1) {
                    $row++; continue;
                }

                $typeId = $data[14];

                if(array_key_exists($typeId, $this->constraintsIds)) {
                    $constraint = [
                        'user_id' => $this->userIds[$data[0]],
                        'start_datetime' => $data[4],
                        'end_datetime' => $data[5],
                        'constraint_type_id' => $this->constraintsIds[$typeId],
                        'weight' => $data[6],
                        'status' => $data[8],
                        'comment' => $data[7],
                        'validated_by' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    array_push($constraints, $constraint);
                }

                $row++;
            }
            fclose($handle);
        }
        ini_set('auto_detect_line_endings', FALSE);

        \DB::table('constraints')->insert($constraints);
    }
}
