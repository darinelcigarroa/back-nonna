<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\order;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::all();

            ApiResponse::success([
                'orders' => $orders
            ]);

        } catch (Exception $e) {
            return ApiResponse::error('Error interno al obtener las ordenes', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            dd($request->all());
            $folio = Order::generateUniqueFolio();
        
            $order = Order::create([
                'folio' => $folio,
                'table_number' => $request->table->id,
                'num_dinners' => $request->num_dinners,
                'user_id' => Auth::id(),
                'status' => Order::STATUS_PENDING,
            ]);


        
            ApiResponse::success([
                'order' => $order
            ], 'Orden creada correctamente', 201);

        } catch (Exception $e) {
            return ApiResponse::error('Error interno al obtener las ordenes', 500);
        }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }
}
