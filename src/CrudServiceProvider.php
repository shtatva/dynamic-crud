<?php

namespace Shtatva\DynamicCrud;

use Illuminate\Support\ServiceProvider;
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
        include __DIR__.'/routes.php';
    }
}
