<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:delete-migration {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('name');
        $stubFilePath = base_path('stubs/migration.delete.stub');
        $stubContent = File::get($stubFilePath);
        $stubContent = str_replace('{{ table }}', $tableName, $stubContent);

        $this->createMigrationFile($tableName, $stubContent);  
    }

    public function createMigrationFile($tableName, $content)
    {
        $migrationsDirectory = database_path('migrations');
        $fileName = date('Y_m_d_His') . '_delete_' . strtolower($tableName) . '_table.php';
        $filePath = $migrationsDirectory . '/' . $fileName;
        File::put($filePath, $content);

        $this->info("Migration file created: $fileName");
    }
}
