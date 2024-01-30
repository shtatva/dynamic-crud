<?php

namespace Shtatva\DynamicCrud\Interfaces;

use Shtatva\DynamicCrud\Models\Table;
use Shtatva\DynamicCrud\Models\TableField;

interface TableRepositoryInterface
{
    public function getAllTables();
    public function getTableFromName($name);
    public function createTable($data);
    public function createTableFields(Table $table, $tableFields);
    public function deleteTable(Table $table);
    public function getAllMigration($tableName);
    public function deleteMigration($ids);
    public function getTableField($id);
    public function updateTableFields(TableField $table, $updateData);
    public function createModel(Table $table, $data);
    public function createController(Table $table, $data);
    public function deleteController(Table $table);
    public function deleteModel(Table $table);
}