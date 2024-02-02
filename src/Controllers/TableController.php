<?php

namespace Shtatva\DynamicCrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;
use Shtatva\DynamicCrud\Models\Table;
use Shtatva\DynamicCrud\Requests\TableRequest;
use Throwable;

class TableController extends Controller
{
    private TableRepositoryInterface $tableRepository;

    public function __construct(TableRepositoryInterface $tableRepository)
    {
        $this->tableRepository = $tableRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = $this->tableRepository->getAllTables();

        return Inertia::render('Table/Listing', [
            'tables' => $tables
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Table/CreateMigration', [
            'formOption' => config('constants.selectOption')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TableRequest $request)
    {
        try {
            $tableName = $request->input('name');
            $tableData = [
                'name' => $tableName
            ];
            $tableFieldsData = $request->input('table_fields', []);
            $tableFieldsData = $this->addTableFields($tableFieldsData);
            $table = $this->tableRepository->createTable($tableData);
            $this->tableRepository->createTableFields($table, $tableFieldsData);

            Artisan::call('make:custom-migration', [
                'name' => $tableName
            ]);

            Artisan::call('migrate');

            return to_route('table.index');
        } catch (Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function addTableFields($tableFieldsData)
    {
        foreach ($tableFieldsData as $key => $tableField) {
            $tableFieldsData[$key]['form_type'] = config('constants.selectOption.form_type')[$tableField['type']];

            if (config('constants.selectOption.form_input_type')[$tableField['type']])
                $tableFieldsData[$key]['input_type'] = config('constants.selectOption.form_input_type')[$tableField['type']];
        }

        return $tableFieldsData;
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($tableId)
    {
        try {
            $table = $this->tableRepository->getTable($tableId);
            $isModuleCreated = $table->module ? true : false;

            $initialFormData = [
                'name'              => $table->name,
                'formfields'        => $table->tablefields,
                'isModuleCreated'   => $isModuleCreated
            ];

            return Inertia::render('Table/CreateMigration', [
                'formOption' => config('constants.selectOption'),
                'isEdit' => true,
                'initialFormData' => $initialFormData,
                'tableId' => $table->id
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $tableId)
    {
        try {
            $fields = $request->input('table_fields');
            $table = $this->tableRepository->getTable($tableId);
            $currentTablefields = $table->tablefields;

            $createNewFields = [];

            foreach ($fields as $field) {

                if (isset($field['id'])) {
                    $tableField = $this->tableRepository->getTableField($field['id']);
                    $this->tableRepository->updateTableFields($tableField, $field);

                    if ($tableField->wasChanged()) {
                        $updateDirtyData = [];

                        if ($tableField->wasChanged('name')) {
                            $updateDirtyData['is_dirty_rename_old'] = $currentTablefields->where('id', $tableField->id)->first()->name;
                        }

                        $remainingTypeFields = ['type', 'length_value', 'default', 'default_value', 'attributes', 'isNull', 'index'];

                        if ($tableField->wasChanged($remainingTypeFields)) {
                            $updateDirtyData['is_dirty'] = true;
                        }

                        $this->tableRepository->updateTableFields($tableField, $updateDirtyData);
                    }
                } else {
                    $field['is_dirty_new_created'] = true;
                    array_push($createNewFields, $field);
                }
            }
            $createNewFields = $this->tableRepository->createTableFields($table, $createNewFields);

            Artisan::call('app:edit-migration', [
                'name' => $table->name,
            ]);

            Artisan::call('migrate');

            return to_route('table.index');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tableId)
    {
        try {
            $table = $this->tableRepository->getTable($tableId);

            if ($table->module) {
                Artisan::call('app:delete-module', [
                    'tableName' => $table->name
                ]);
            }
            
            $hasdeleteTable = $this->tableRepository->deleteTable($table);

            if ($hasdeleteTable) {
                Artisan::call('make:delete-migration', [
                    'name' => $table->name
                ]);

                Artisan::call('migrate');
            }
            $this->deleteAllMigrationFiles($table->name);

            return to_route('table.index');
        } catch (Throwable $th) {
            return response()->json($th->getMessage());
        }
    }


    /**
     * Remove the migrations files
     */
    public function deleteAllMigrationFiles($tableName)
    {
        $migrations = $this->tableRepository->getAllMigration($tableName);

        foreach ($migrations as $migration) {
            Artisan::call('make:delete-migration-file', [
                'fileName' => $migration->migration
            ]);
        }

        $this->tableRepository->deleteMigration($migrations->pluck('id'));
    }
}
