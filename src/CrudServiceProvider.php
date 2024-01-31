<?php

namespace Shtatva\DynamicCrud;

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
use Shtatva\DynamicCrud\Interfaces\DatabaseRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\ModelRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\ModuleRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;
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
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        if($this->app->runningInConsole()){
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
                GenerateModule::class
            ]);
        }

        include __DIR__.'/routes.php';
    }
}
