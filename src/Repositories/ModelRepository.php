<?php

namespace Shtatva\DynamicCrud\Repositories;

use Shtatva\DynamicCrud\Interfaces\ModelRepositoryInterface;
use Shtatva\DynamicCrud\Models\DynamicModel;

class ModelRepository implements ModelRepositoryInterface
{
    public function create($data)
    {
        return DynamicModel::create($data);
    }

    public function fetchFromName($name)
    {
        return DynamicModel::where('name', $name)->first();
    }
}