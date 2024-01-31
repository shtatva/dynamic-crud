<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Shtatva\DynamicCrud\Interfaces\ModuleRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;

class DeleteModule extends Command
{
    private TableRepositoryInterface $tableRepository;
    private ModuleRepositoryInterface $moduleRepository;

    public function __construct(TableRepositoryInterface $tableRepository, ModuleRepositoryInterface $moduleRepository)
    {
        parent::__construct();
        $this->tableRepository = $tableRepository;
        $this->moduleRepository = $moduleRepository;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-module {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('tableName');
        $table = $this->tableRepository->getTableFromName($tableName);

        if (!$table)
            return $this->error('Table not found');

        $module = $table->module;
        $model = $table->model;
        $controller = $table->controller;

        Artisan::call('app:delete-route', [
            'name' => $table->name
        ]);

        Artisan::call('app:delete-controller', [
            'name' => $controller->name
        ]);

        Artisan::call('app:delete-model', [
            'name' => $model->name
        ]);

        $this->moduleRepository->delete($module);
        $this->tableRepository->deleteController($table);
        $this->tableRepository->deleteModel($table);

        $this->info("Module deleted successfully");
    }
}
