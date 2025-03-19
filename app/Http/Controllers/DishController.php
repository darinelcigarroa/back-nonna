<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Dish;
use App\Traits\Loggable;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

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

            $tables = Dish::with('dishType')->select(
                'id',
                'name',
                'price',
                'description',
                'status',
                'dish_type_id'
            )->orderBy('id', 'DESC')->paginate($rowsPerPage, ['*'], 'page', $page);

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
    public function dishes(Request $request)
    {
        try {
            $dishes = Dish::when($request->has('typeDish'), function ($query) use ($request) {
                return $query->where('dish_type_id', $request->typeDish);
            })->select('id', 'name', 'price', 'description', 'dish_type_id')->get();

            return ApiResponse::success([
                'dishes' => $dishes
            ]);
            
        } catch (\Throwable $th) {
            return ApiResponse::error('Error interno al obtener los platillos', 500);
        }
    }

}
