<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {file} {--s|separator=;}';

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
        ];
        $this->constraintsIds = [
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
        if (($handle = fopen($this->argument('file'), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, null, $this->option('separator'))) !== FALSE) {
                // On enlève le header
                if($row == 1) {
                    $row++; continue;
                }

                $typeId = $data[15];

                if(array_key_exists($typeId, $this->constraintsIds)) {
                    $weight = ($data[6] === "TRUE") ? 1 : (($data[6] == "FALSE") ? 0 : $data[6]);

                    // On doit regarder dans la variable "day" ou "day1"...

                    if($data[12] !== "") {
                        $day = $data[12];

                    } else if($data[13] !== "") {
                        $day = $data[13];
                    } else {
                        $day = null;
                    }

                    $constraint = [
                        'user_id' => $this->userIds[$data[0]],
                        'start_datetime' => $data[4],
                        'end_datetime' => $data[5],
                        'constraint_type_id' => $this->constraintsIds[$typeId],
                        'weight' => $weight,
                        'status' => $data[8],
                        'comment' => $data[7],
                        'validated_by' => 1,
                        'number_of_occurrences' => ($data[9] !== "") ? $data[9] : null,
                        'day' => $day,
                        'disposition' => ($data[10] !== "") ? $data[10] : null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    array_push($constraints, $constraint);
                } else {
                    throw new \Exception("Constrainte manquante : {$typeId}");
                }

                $row++;
            }
            fclose($handle);
        }
        ini_set('auto_detect_line_endings', FALSE);

        \DB::table('constraints')->insert($constraints);

        $this->info('Done. ' . count($constraints) . ' contraintes importées.');
    }
}
