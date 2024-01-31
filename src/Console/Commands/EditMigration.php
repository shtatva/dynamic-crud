<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;

class EditMigration extends Command
{

    private TableRepositoryInterface $tableRepository;

    public function __construct(TableRepositoryInterface $tableRepository)
    {
        parent::__construct();
        $this->tableRepository = $tableRepository;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:edit-migration {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit the migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('name');
        $table = $this->tableRepository->getTableFromName($tableName);
        $allTableFields = $table->tablefields;

        $stubFilePath = base_path('stubs/migration.update-rename.stub');
        $stubContent = File::get($stubFilePath);
        $stubContent = str_replace('{{ table }}', $tableName, $stubContent);

        $changeTableFields = $allTableFields->where('is_dirty', 1);
        $newTableFields = $allTableFields->where('is_dirty_new_created', 1);
        $renameTableFields = $allTableFields->whereNotNull('is_dirty_rename_old');
        $deletedTableFields = $allTableFields->where('is_dirty_deleted', 1);

        $renameColumnContent = $this->generateRenameColumnsTableFields($renameTableFields, $tableName);
        $stubContent = str_replace('{{ renameColumn }}', $renameColumnContent, $stubContent);

        $columns = $this->generateColumnsTableFields($changeTableFields, true);
        $columns .= $this->generateColumnsTableFields($newTableFields);
        $stubContent = str_replace('{{ column }}', $columns, $stubContent);

        $this->createMigrationFile($tableName, $stubContent);
        $this->updateIsDirtyFields($changeTableFields, ['is_dirty' => 0]);
        $this->updateIsDirtyFields($newTableFields, ['is_dirty_new_created' => 0]);
        $this->updateIsDirtyFields($renameTableFields, ['is_dirty_rename_old' => null]);
    }

    public function createMigrationFile($tableName, $content)
    {
        $migrationsDirectory = database_path('migrations');
        $fileName = date('Y_m_d_His') . '_edit_' . strtolower($tableName) . '_table.php';
        $filePath = $migrationsDirectory . '/' . $fileName;
        File::put($filePath, $content);

        $this->info("Migration file created: $fileName");
    }


    public function generateColumnsTableFields($tableFields, $isChanged = false)
    {
        $columns = '';
        foreach ($tableFields as $tableField) {
            $columns .= $this->columnMigrationSyntax($tableField, $isChanged);
        }
        return $columns;
    }

    public function generateRenameColumnsTableFields($tableFields, $tableName)
    {
        if ($tableFields->count() == 0)
            return '';

        $columns = '';
        foreach ($tableFields as $tableField) {
            $columns .= $this->renameColumnMigrationSyntax($tableField, $tableName);
        }

        return $columns;
    }

    public function renameColumnMigrationSyntax($tableField, $tableName)
    {
        $stubFilePath = base_path('stubs/rename.stub');
        $stubContent = File::get($stubFilePath);
        $stubContent = str_replace('{{ table }}', $tableName, $stubContent);
        $stubContent = str_replace('{{ current_value }}', $tableField->is_dirty_rename_old, $stubContent);
        $stubContent = str_replace('{{ new_value }}', $tableField->name, $stubContent);

        $newTableSynatax = $this->columnMigrationSyntax($tableField);
        $stringArr = explode(";", $newTableSynatax);
        array_push($stringArr, '->after(\'' . $tableField->is_dirty_rename_old .'\');');
        $newTableSynatax = implode("", $stringArr);

        $stubContent = str_replace('{{ new_table_syntax }}', $newTableSynatax, $stubContent);

        return $stubContent;
    }

    public function columnMigrationSyntax($data, $isChanged = false)
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

        if ($data->index)
            $str .= config('constants.migration.index')[$data->index];

        if ($isChanged)
            $str .= '->change()';

        $str .= ';' . "\n\t\t\t";

        return $str;
    }

    public function updateIsDirtyFields($tableFields, $data)
    {
        foreach ($tableFields as $tableField) {
            $this->tableRepository->updateTableFields($tableField, $data);
        }
    }
}
