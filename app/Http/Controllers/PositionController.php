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
            $positions = Position::where('visible', true)->select('id', 'name')->get();

           return ApiResponse::success([
                'positions' => $positions,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error('Error interno al obtener las posiciones', 500);
        }
    }
}
