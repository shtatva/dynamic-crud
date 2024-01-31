<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shtatva\DynamicCrud\Interfaces\ModelRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;

class GenerateController extends Command
{
    private ModelRepositoryInterface $modelRepository;
    private TableRepositoryInterface $tableRepository;

    public function __construct(ModelRepositoryInterface $modelRepository, TableRepositoryInterface $tableRepository)
    {
        parent::__construct();
        $this->modelRepository = $modelRepository;
        $this->tableRepository = $tableRepository;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-controller {name} {modelName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the dynamic controller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $modelName = $this->argument('modelName');
        $model = $this->modelRepository->fetchFromName($modelName);
        $table = $model->table;

        if(!$model)
            return $this->error("Model not found: " . $model);

        $stubFilePath = app_path('CustomStubs/controller.resource.stub');
        $stubContent = File::get($stubFilePath);
        $stubContent = str_replace('{{ model }}', $model->name, $stubContent);
        $stubContent = str_replace('{{ class }}', $name, $stubContent);
        $stubContent = str_replace('{{ tableName }}', $table->name, $stubContent);

        $this->tableRepository->createController($table, [
            'name' => $name
        ]);

        $this->createControllerFile($name, $stubContent);  
    }

    public function createControllerFile($name, $content)
    {
        $controllersDirectory = app_path('Http/Controllers');
        $fileName = $name . '.php';
        $filePath = $controllersDirectory . '/' . $fileName;
        File::put($filePath, $content);

        $this->info("Controller file created: $fileName");
    }
}
