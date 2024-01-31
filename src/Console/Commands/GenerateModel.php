<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;

class GenerateModel extends Command
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
    protected $signature = 'app:generate-model {name} {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the dynamic model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('tableName');
        $name = $this->argument('name');
        $table = $this->tableRepository->getTableFromName($tableName);

        if(!$table)
            return $this->error("Table not found: " . $tableName);

        $stubFilePath = app_path('CustomStubs/model.stub');
        $stubContent = File::get($stubFilePath);
        $stubContent = str_replace('{{ table }}', $tableName, $stubContent);
        $stubContent = str_replace('{{ class }}', $name, $stubContent);

        $this->tableRepository->createModel($table, [
            'name' => $name
        ]);
        
        $this->createModelFile($name, $stubContent);  
    }

    public function createModelFile($name, $content)
    {
        
        $modelsDirectory = app_path('Models');
        $fileName = $name . '.php';
        $filePath = $modelsDirectory . '/' . $fileName;
        File::put($filePath, $content);

        $this->info("Model file created: $fileName");
    }
}
