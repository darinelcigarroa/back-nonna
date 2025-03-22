<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Table;
use App\Traits\Loggable;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TableController extends Controller
{
    use Loggable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $rowsPerPage = $request->get('rowsPerPage', 10);
            $page = $request->get('page', 1);
            $filter = $request->get('filter');

            $tables = Table::select(
                'id',
                'name',
                'capacity',
                'status'
            )->when(!empty($filter), function($query) use ($filter) {
                $query->search($filter);

            })->orderBy('id', 'DESC')->paginate($rowsPerPage, ['*'], 'page', $page);

            return ApiResponse::success(['tables' => $tables], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener las mesas');
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $table = new Table();
            $table->fill($request->all());
            $table->save();

            return ApiResponse::success(['table' => $table], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al guardar la mesa');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        try {
            $table->fill($request->all());
            $table->save();
 
             return ApiResponse::success(['table' => $table], 'Operación exitosa');
         } catch (Exception $e) {
             $this->logError($e);
             return ApiResponse::error('Error interno al actualizar la mesa');
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        try {
            $table->delete();
 
             return ApiResponse::success(['table' => $table], 'Operación exitosa');
         } catch (Exception $e) {
             $this->logError($e);
             return ApiResponse::error('Error interno al elimnar la mesa');
         }
    }
    public function getTables()
    {
        try {
            $tables = Table::select(
                'id',
                'name',
                'capacity',
                'status'
            )->orderBy('id', 'ASC')->get();

            return ApiResponse::success(['tables' => $tables], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener las mesas');
        }
    }
}
