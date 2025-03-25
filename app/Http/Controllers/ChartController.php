<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Traits\Loggable;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
    use Loggable;

    public function getMostUsedTables(Request $request)
    {
        try {
            $rowsPerPage = $request->get('rowsPerPage', 5);
            $page = $request->get('page', 1);

            $tables = Order::select(
                'table_id',
                DB::raw('COUNT(table_id) as total_uses')
            )
                ->with('table:id,name')
                ->where('order_status_id', OrderStatus::PAID)
                ->groupBy('table_id')
                ->orderByDesc('total_uses')
                ->paginate($rowsPerPage, ['*'], 'page', $page);

            return ApiResponse::success(['tables' => $tables], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener las mesas');
        }
    }
    public function monthlyIncomeTrend()
    {
        try {
            $currentMonth = now()->month;
            $currentYear = now()->year;
            $previousYear = now()->subYear()->year;

            $months = range(1, $currentMonth);

            $currentMonthincome = DB::table('orders')
                ->select(
                    DB::raw("EXTRACT(MONTH FROM created_at) as mes"),
                    DB::raw("SUM(total_amount) as total")
                )
                ->where('order_status_id', OrderStatus::PAID)
                ->whereYear('created_at', $currentYear)
                ->whereIn(DB::raw("EXTRACT(MONTH FROM created_at)"), $months)
                ->groupBy('mes')
                ->orderBy('mes')
                ->pluck('total', 'mes');

            $incomeLastMonth = DB::table('orders')
                ->select(
                    DB::raw("EXTRACT(MONTH FROM created_at) as mes"),
                    DB::raw("SUM(total_amount) as total")
                )
                ->where('order_status_id', OrderStatus::PAID)
                ->whereYear('created_at', $previousYear)
                ->whereIn(DB::raw("EXTRACT(MONTH FROM created_at)"), $months)
                ->groupBy('mes')
                ->orderBy('mes')
                ->pluck('total', 'mes');

            $result = [];

            foreach ($months as $month) {
                $result[] = [
                    'mes' => Carbon::createFromFormat('!m', $month)->locale('es')->translatedFormat('F'),
                    'ingresos_mes_actual' => $currentMonthincome[$month] ?? 0,
                    'ingresos_mes_pasado' => $incomeLastMonth[$month] ?? 0
                ];
            }

            return ApiResponse::success(['result' => $result], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener la tendencia de ingresos');
        }
    }
    public function servicesAttendedWaiter(Request $request)
    {
        try {
            $rowsPerPage = $request->get('rowsPerPage', 5);
            $page = $request->get('page', 1);

            $waiters = Order::select(
                'user_id',
                DB::raw('COUNT(user_id) as total_services')
            )
                ->where('order_status_id', OrderStatus::PAID)
                ->groupBy('user_id')
                ->orderByDesc('total_services')
                ->with(['user:id,employee_id', 'user.employee:id,name'])
                ->paginate($rowsPerPage, ['*'], 'page', $page);

            return ApiResponse::success(['waiters' => $waiters], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los meseros');
        }
    }
    public function trendsMainCourseSales(Request $request)
    {
        try {

            $totalSold = OrderItem::whereHas('order', function ($query) {
                $query->where('order_status_id', OrderStatus::PAID);
            })->where('dish_type', 'Plato Fuerte')->sum('quantity');

            // Obtener el top 5 de los platillos más vendidos
            $topDishes = OrderItem::select('dish_name as name', DB::raw('SUM(quantity) as total_sold'))
                ->whereHas('order', function ($query) {
                    $query->where('order_status_id', OrderStatus::PAID);
                })
                ->where('dish_type', 'Plato Fuerte')
                ->groupBy('dish_name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();

            // Obtener la cantidad total de los demás platillos
            $otherSold = OrderItem::whereHas('order', function ($query) {
                $query->where('order_status_id', OrderStatus::PAID);
            })->where('dish_type', 'Plato Fuerte')
                ->whereNotIn('dish_name', $topDishes->pluck('name'))
                ->sum('quantity');

            // Agregar el porcentaje a cada platillo
            $topDishes = $topDishes->map(function ($dish) use ($totalSold) {
                $dish->value = round(($dish->total_sold / $totalSold) * 100, 2);
                return $dish;
            });

            // Agregar "Otros" con su porcentaje
            $otherDishes = (object) [
                'name' => 'Otros',
                'total_sold' => $otherSold,
                'value' => round(($otherSold / $totalSold) * 100, 2),
            ];

            // Unimos los resultados
            $result = $topDishes->push($otherDishes);

            return ApiResponse::success(['result' => $result], 'Operación exitosa');
            
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los meseros');
        }
    }
}
