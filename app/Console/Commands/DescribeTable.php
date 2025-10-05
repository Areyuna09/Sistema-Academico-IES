<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DescribeTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:describe {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Describe table structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        $columns = \DB::select("DESCRIBE {$table}");

        $this->info("Structure of table: {$table}");
        $this->table(
            ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'],
            array_map(fn($col) => (array) $col, $columns)
        );
    }
}
