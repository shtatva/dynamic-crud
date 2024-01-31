<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteMigrationFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:delete-migration-file {fileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the migration files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fileName = $this->argument('fileName');
        $migrationPath = database_path('migrations');
        $fullPath = $migrationPath . DIRECTORY_SEPARATOR . $fileName . '.php';

        if (File::exists($fullPath)) {
            File::delete($fullPath);
            $this->info('Migration file deleted successfully: ' . $fileName);
        } else {
            $this->error('Migration file not found: ' . $fileName);
        }
    }
}
