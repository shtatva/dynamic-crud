<?php

namespace Shtatva\DynamicCrud\Repositories;

use Shtatva\DynamicCrud\Interfaces\DatabaseRepositoryInterface;

class DatabaseRepository implements DatabaseRepositoryInterface
{
    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function fetchAll()
    {
       return $this->model->all(); 
    }

    public function fetch($id)
    {
        return $this->model->find($id);
    }

    public function update($model, $updatedData)
    {
        return $model->update($updatedData);
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function getTableName()
    {
        return $this->model->getTable();
    }
}