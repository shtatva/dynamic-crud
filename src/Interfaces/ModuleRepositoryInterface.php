<?php

namespace Shtatva\DynamicCrud\Interfaces;

use Shtatva\DynamicCrud\Models\Module;
use Shtatva\DynamicCrud\Models\Table;

interface ModuleRepositoryInterface
{
    public function getAllModule();
    public function create(Table $table, $data);
    public function getModule($id);
    public function delete(Module $module);
}