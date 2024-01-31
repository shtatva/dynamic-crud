<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shtatva\DynamicCrud\Models\Table;

class CustomMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-migration {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating dynamic migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('name');
        $stubFilePath = app_path('CustomStubs/migration.create.stub');
        $stubContent = File::get($stubFilePath);
        $stubContent = str_replace('{{ table }}', $tableName, $stubContent);
        $columns = $this->generateColumns($tableName);
        $stubContent = str_replace('{{ column }}', $columns, $stubContent);

        $this->createMigrationFile($tableName, $stubContent);
    }

    public function createMigrationFile($tableName, $content)
    {
        $migrationsDirectory = database_path('migrations');
        $fileName = date('Y_m_d_His') . '_create_' . strtolower($tableName) . '_table.php';
        $filePath = $migrationsDirectory . '/' . $fileName;
        File::put($filePath, $content);

        $this->info("Migration file created: $fileName");
    }

    public function generateColumns($tableName)
    {
        $table = Table::where('name', $tableName)->first();
        $tableFields = $table->tablefields;

        $columns = '';
        foreach ($tableFields as $tableField) {
            $columns .= $this->columnMigrationSyntax($tableField);
        }

        return $columns;
    }

    public function columnMigrationSyntax($data)
    {
        $str = config('constants.migration.fieldConversion')[$data->type];
        $str = str_replace('column_name', $data->name, $str);

        if ($data->length_value && strpos($str, 'length'))
            $str = str_replace('length', $data->length_value, $str);

        if ($data->default) {
            $str .= config('constants.migration.default')[$data->default];

            if ($data->default == 'As Defined:') {
                $str = str_replace('value', $data->default_value, $str);
            }
        }

        if ($data->isNull)
            $str .= '->nullable()';
 
        if($data->index)
            $str .= config('constants.migration.index')[$data->index];
 
        $str .= ';' . "\n\t\t\t";

        return $str;
    }
}
