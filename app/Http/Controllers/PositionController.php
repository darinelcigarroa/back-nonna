<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Position;
use App\Helpers\ApiResponse;

class PositionController extends Controller
{
    public function index()
    {
        try {
           return ApiResponse::success([
                'positions' => Position::select('id', 'name')->get(),
            ]);
        } catch (Exception $e) {
            return ApiResponse::error('Error interno al obtener las posiciones', 500);
        }
    }
}
