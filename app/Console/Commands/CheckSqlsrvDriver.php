<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSqlsrvDriver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:sqlsrv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the SQLSRV driver is installed and enabled';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!extension_loaded('sqlsrv')) {
            $this->error('❌ ERROR: The SQLSRV driver is not installed.');
            $this->line('➡️  Please install and enable it in your PHP configuration.');
            $this->line('   For installation instructions, visit:');
            $this->line('   https://learn.microsoft.com/en-us/sql/connect/php/installation-tutorial-linux-mac');
            return self::FAILURE;
        }

        // Run the shell command to query ODBC drivers
        $output = shell_exec('odbcinst -q -d');

        if (!$output || !str_contains($output, 'ODBC Driver 18 for SQL Server')) {
            $this->error('❌ msodbcsql18 is not installed or not configured.');
            $this->line('➡️  Please install it using the instructions at:');
            $this->line('   https://learn.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server');
        }

        $this->info('✅ sqlsrv driver is installed and enabled.');
        $this->info('✅ msodbcsql18 is installed and available.');
        return self::SUCCESS;
    }
}
