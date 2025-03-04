<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\DishType;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class DishTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
           return ApiResponse::success([
                'dishTypes' => DishType::select('id', 'name')->get(),
            ]);
        } catch (Exception $e) {
            return ApiResponse::error('Error interno al obtener los tipos de platillos', 500);
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
    public function show(DishType $dishType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DishType $dishType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DishType $dishType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DishType $dishType)
    {
        //
    }
}
