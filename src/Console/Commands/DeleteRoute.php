<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;

class DeleteRoute extends Command
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
    protected $signature = 'app:delete-route {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the route';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $table = $this->tableRepository->getTableFromName($name);

        if(!$table)
            return $this->error('Table not found: ' . $name);

        $controller = $table->controller;

        if(!$controller)
            return $this->error('Controller not found: ' . $name);

        $controller = $controller->name;

        $webRoutePath = base_path('routes/web.php');
        $webRouteContent = File::get($webRoutePath);
        $routeStr = "Route::resource('/". $name ."', '". $controller ."');\n";

        $newWebRouteContent = str_replace($routeStr, '', $webRouteContent);
        File::put($webRoutePath, $newWebRouteContent);
        
        $this->info("Remove route successfully");
    }
}
