<?php

namespace Shtatva\DynamicCrud\Interfaces;

interface DatabaseRepositoryInterface
{
    public function setModel($model);
    public function create($data);
    public function fetchAll();
    public function fetch($id);
    public function update($model, $updatedData);
    public function delete($model);
    public function getTableName();
}