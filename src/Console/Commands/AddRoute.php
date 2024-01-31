<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;

class AddRoute extends Command
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
    protected $signature = 'app:add-route {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the route';

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
        $strCompare = 'Add more routes within the same namespace here';

        $position = strpos($webRouteContent, $strCompare);

        if ($position !== false) {
            $newRoute = "\n\tRoute::resource('/". $name ."', '". $controller ."');";
            $modifiedContent = substr_replace($webRouteContent, $newRoute, $position + strlen($strCompare), 0);

            File::put($webRoutePath, $modifiedContent);

            return $this->info("Route Add successfully");
        } else {
            return $this->error('Some error occured while adding the route');
        }
    }
}
