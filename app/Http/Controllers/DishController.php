<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Dish;
use App\Models\DishType;
use App\Traits\Loggable;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DishController extends Controller
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
            $search = $request->get('filter');

            $tables = Dish::with('dishType')->select(
                'id',
                'name',
                'price',
                'description',
                'status',
                'dish_type_id'
            )->when(!empty($search), function ($query) use ($search) {
                $query->search($search);
            })->orderBy('id', 'DESC')->paginate($rowsPerPage, ['*'], 'page', $page);

            return ApiResponse::success(['dishes' => $tables], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los platillos');
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $dish = new Dish();
            $dish->fill($request->all());
            $dish->save();

            return ApiResponse::success(['dish' => $dish], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al guardar el platillo');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Dish $dish)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dish $dish)
    {
        try {
            $dish->fill($request->all());
            $dish->save();
            $dish->load('dishType');

            return ApiResponse::success(['dish' => $dish], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al actualizar el platillo');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish)
    {
        try {
            $dish->delete();

            return ApiResponse::success(['dish' => $dish], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al eliminar el platillo');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function getDishes(Request $request)
    {
        try {
            $dishes = Dish::when($request->has('typeDish'), function ($query) use ($request) {
                return $query->where('dish_type_id', $request->typeDish);
            })->where('status', true)->select('id', 'name', 'price', 'description', 'dish_type_id')->get();

            return ApiResponse::success([
                'dishes' => $dishes
            ]);
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los platillos', 500);
        }
    }
    public function getMenuDishes(Request $request)
    {
        try {
            $search = $request->search;
    
            $dishes = DishType::with(['dishes' => function ($query) use ($search) {
                $query->when(!empty($search), function ($query) use ($search) {
                    $query->where('name', 'ILIKE', "%{$search}%");
                })->orderBy('id');
            }])
            ->when(!empty($search), function ($query) use ($search) {
                $query->whereHas('dishes', function ($query) use ($search) {
                    $query->where('name', 'ILIKE', "%{$search}%");
                });
            })
            ->orderBy('id', 'ASC')
            ->get();        
    
            return ApiResponse::success(['typesDishes' => $dishes]);
        } catch (\Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los platillos', 500);
        }
    }
    
    public function toggleDishStatus(Dish $dish)
    {
        try {
            $dish = Dish::find($dish->id);
            $dish->update(['status' => !$dish->status]);

            $statusText = $dish->status ? 'activado' : 'desactivado';

            return ApiResponse::success(['dish' => $dish], "$dish->name $statusText correctamente");
        } catch (\Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al cambiar el estado del platillo');
        }
    }
}
