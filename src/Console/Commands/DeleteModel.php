<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->argument('name');
        $modelPath = app_path('Models');
        $fullPath = $modelPath . DIRECTORY_SEPARATOR . $modelName . '.php';
        
        if (File::exists($fullPath)) {
            File::delete($fullPath);
            $this->info('Model file deleted successfully: ' . $modelName);
        } else {
            $this->error('Model file not found: ' . $modelName);
        }
    }
}
