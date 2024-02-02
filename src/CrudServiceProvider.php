<?php

namespace Shtatva\DynamicCrud;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Shtatva\DynamicCrud\Console\Commands\AddRoute;
use Shtatva\DynamicCrud\Console\Commands\CustomMigration;
use Shtatva\DynamicCrud\Console\Commands\DeleteController;
use Shtatva\DynamicCrud\Console\Commands\DeleteMigration;
use Shtatva\DynamicCrud\Console\Commands\DeleteMigrationFile;
use Shtatva\DynamicCrud\Console\Commands\DeleteModel;
use Shtatva\DynamicCrud\Console\Commands\DeleteModule;
use Shtatva\DynamicCrud\Console\Commands\DeleteRoute;
use Shtatva\DynamicCrud\Console\Commands\EditMigration;
use Shtatva\DynamicCrud\Console\Commands\GenerateController;
use Shtatva\DynamicCrud\Console\Commands\GenerateModel;
use Shtatva\DynamicCrud\Console\Commands\GenerateModule;
use Shtatva\DynamicCrud\Console\Commands\PublishAllFiles;
use Shtatva\DynamicCrud\Interfaces\DatabaseRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\ModelRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\ModuleRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;
use Shtatva\DynamicCrud\Models\Table;
use Shtatva\DynamicCrud\Repositories\DatabaseRepository;
use Shtatva\DynamicCrud\Repositories\ModelRepository;
use Shtatva\DynamicCrud\Repositories\ModuleRepository;
use Shtatva\DynamicCrud\Repositories\TableRepository;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TableRepositoryInterface::class, TableRepository::class);
        $this->app->bind(DatabaseRepositoryInterface::class, DatabaseRepository::class);
        $this->app->bind(ModelRepositoryInterface::class, ModelRepository::class);
        $this->app->bind(ModuleRepositoryInterface::class, ModuleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        Route::model('table', Table::class);

        $this->publishes([
            __DIR__ . '/config/constants.php' => config_path('constants.php')
        ], 'constants');

        $this->publishes([
            __DIR__ . '/CustomStubs' => app_path('CustomStubs')
        ], 'custom_stubs');

        $this->publishes([
            __DIR__ . '/resources/js/Pages' => resource_path('js/Pages')
        ], 'pages');

        $this->publishes([
            __DIR__ . '/resources/js/app.jsx' => resource_path('js/app.jsx')
        ], 'app_react_file');

        $this->publishes([
            __DIR__ . '/resources/views/app.blade.php' => resource_path('views/app.blade.php')
        ], 'app_laravel_file');

        $this->commands([
            AddRoute::class,
            CustomMigration::class,
            DeleteController::class,
            DeleteMigration::class,
            DeleteMigrationFile::class,
            DeleteModel::class,
            DeleteModule::class,
            DeleteRoute::class,
            EditMigration::class,
            GenerateController::class,
            GenerateModel::class,
            GenerateModule::class,
            PublishAllFiles::class
        ]);
    }
}
