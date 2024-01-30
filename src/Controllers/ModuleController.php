<?php

namespace Shtatva\DynamicCrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Shtatva\DynamicCrud\Interfaces\ModuleRepositoryInterface;
use Shtatva\DynamicCrud\Models\Table;

class ModuleController extends Controller
{
    private ModuleRepositoryInterface $moduleRepository;

    public function __construct(ModuleRepositoryInterface $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
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

    public function store(Table $table)
    {

        try {
            $this->moduleRepository->create($table, [
                'name' => $table->name
            ]);

            Artisan::call('app:generate-module', [
                'tableName' => $table->name
            ]);

            return to_route('table.index');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function delete(Table $table)
    {
        try {
            $module = $table->module;

            if ($module) {
                Artisan::call('app:delete-module', [
                    'tableName' => $table->name
                ]);
            }

            return to_route('table.index');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
