<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dish $dishe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dish $dishe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dish $dishe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dishe)
    {
        //
    }
}
