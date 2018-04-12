<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:rhpharma-prod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importer les contraintes de RHPharma (Azure)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $conn = $this->connectSQL();
        if($conn === false) {
            echo "Could not connect.\n";
            die( print_r( sqlsrv_errors(), true));
        }
    }

    private function connectSQL()
    {
        $host = env('AZURE_HOST');
        $connectionOptions = [
            'Database' => env('AZURE_DATABASE'),
            'Uid' => env('AZURE_USERNAME'),
            'PWD' => env('AZURE_PASSWORD')
        ];

        $conn = sqlsrv_connect($host, $connectionOptions);

        return $conn;
    }
}
