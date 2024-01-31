<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;

class GenerateModule extends Command
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
    protected $signature = 'app:generate-module {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('tableName');

        $table = $this->tableRepository->getTableFromName($tableName);
        if(!$table)
            return $this->error("Table not found: " . $tableName);

        $modelName = ucfirst($table->name);
        
        Artisan::call('app:generate-model', [
            'name'      => $modelName,
            'tableName' => $table->name
        ]);

        $controllerName = $modelName . 'Controller';
        
        Artisan::call('app:generate-controller', [
            'name'      => $controllerName,
            'modelName' => $modelName
        ]);

        Artisan::call('app:add-route', [
            'name' => $table->name
        ]);

        $this->info("Module Created Successfully");
    }
}
