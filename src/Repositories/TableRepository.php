<?php

namespace Shtatva\DynamicCrud\Repositories;

use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;
use Shtatva\DynamicCrud\Models\Migration;
use Shtatva\DynamicCrud\Models\Table;
use Shtatva\DynamicCrud\Models\TableField;

class TableRepository implements TableRepositoryInterface
{
    public function getTable($id)
    {
        return Table::find($id);
    }

    public function getAllTables()
    {
        return Table::all();
    }

    public function getTableFromName($name)
    {
        return Table::where('name', $name)->first();
    }

    public function createTable($data)
    {
        return Table::create($data);
    }

    public function createTableFields(Table $table, $data)
    {
        return $table->tablefields()->createMany($data);
    }

    public function deleteTable(Table $table){
        return $table->delete();
    }

    public function getAllMigration($tableName)
    {
        return Migration::where('migration', 'LIKE', "%{$tableName}%")->get();
    }

    public function deleteMigration($ids)
    {
        return Migration::destroy($ids);
    }

    public function getTableField($id)
    {
        return TableField::find($id);
    }

    public function updateTableFields(TableField $tableField, $updateData)
    {
        return $tableField->update($updateData);
    }

    public function createModel(Table $table, $data)
    {
        return $table->model()->create($data);
    }

    public function createController(Table $table, $data)
    {
        return $table->controller()->create($data);
    }

    public function deleteController(Table $table)
    {
        return $table->controller()->delete();
    }

    public function deleteModel(Table $table)
    {
        return $table->model()->delete();
    }
}