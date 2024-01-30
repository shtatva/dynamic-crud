<?php

namespace Shtatva\DynamicCrud\Repositories;

use Shtatva\DynamicCrud\Interfaces\ModuleRepositoryInterface;
use Shtatva\DynamicCrud\Models\Module;
use Shtatva\DynamicCrud\Models\Table;

class ModuleRepository implements ModuleRepositoryInterface
{
    public function getAllModule()
    {
        return Module::all();
    }

    public function create(Table $table, $data)
    {
        return $table->module()->create($data);
    }

    public function getModule($id)
    {
        return Module::find($id);
    }

    public function delete(Module $module)
    {
        return $module->delete();
    }
}