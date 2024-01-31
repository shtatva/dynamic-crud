<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the Controller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controllerName = $this->argument('name');
        $controllerPath = app_path('Http/Controllers');
        $fullPath = $controllerPath . DIRECTORY_SEPARATOR . $controllerName . '.php';

        if (File::exists($fullPath)) {
            File::delete($fullPath);
            $this->info('Controller file deleted successfully: ' . $controllerName);
        } else {
            $this->error('Controller file not found: ' . $controllerName);
        }
    }
}
