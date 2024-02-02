<?php

namespace Shtatva\DynamicCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PublishAllFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-all-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes all the required files for dynamic crud';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vendorPublish = 'vendor:publish';

        $commandTags = [
            'app_laravel_file',
            'app_react_file',
            'constants',
            'custom_stubs',
            'pages'
        ];

        foreach ($commandTags as $tag) {
            Artisan::call($vendorPublish . ' --tag=' . $tag);    
        }

        $this->info('All files published successfully');
    }
}
