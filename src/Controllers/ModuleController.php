<?php

namespace Shtatva\DynamicCrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Shtatva\DynamicCrud\Interfaces\ModuleRepositoryInterface;
use Shtatva\DynamicCrud\Interfaces\TableRepositoryInterface;
use Shtatva\DynamicCrud\Models\Table;

class ModuleController extends Controller
{
    private ModuleRepositoryInterface $moduleRepository;
    private TableRepositoryInterface $tableRepository;

    public function __construct(ModuleRepositoryInterface $moduleRepository, TableRepositoryInterface $tableRepository)
    {
        $this->moduleRepository = $moduleRepository;
        $this->tableRepository = $tableRepository;
    }

    public function listingModules()
    {
        try {
            $modules = $this->moduleRepository->getAllModule();

            return Inertia::render('Module/ListingAllModule', [
                'modules' => $modules
            ]);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function store($tableId)
    {

        try {
            $table = $this->tableRepository->getTable($tableId);
            $this->moduleRepository->create($table, [
                'name' => $table->name
            ]);

            Artisan::call('app:generate-module', [
                'tableName' => $table->name
            ]);

            return Inertia::location('/module');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function delete($tableId)
    {
        try {
            $table = $this->tableRepository->getTable($tableId);
            $module = $table->module;

            if ($module) {
                Artisan::call('app:delete-module', [
                    'tableName' => $table->name
                ]);
            }

            return Inertia::location('/module');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
