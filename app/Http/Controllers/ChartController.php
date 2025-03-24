<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Traits\Loggable;
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

    public function getMostUsedTables(Request $request) {
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
    public function monthlyIncomeTrend() {
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
    public function servicesAttendedWaiter(Request $request) {
        try {
            $rowsPerPage = $request->get('rowsPerPage', 5);
            $page = $request->get('page', 1);

            $waiters = Order::select(
                'user_id',
                DB::raw('COUNT(user_id) as total_services')
            )
            ->with(['user:id,employee_id', 'user.employee:id,name'])
            ->where('order_status_id', OrderStatus::PAID)
            ->groupBy('user_id')
            ->orderBy('total_services','ASC')
            ->paginate($rowsPerPage, ['*'], 'page', $page);

            Log::info('waiters', ['waiters' => $waiters]);

            return ApiResponse::success(['waiters' => $waiters], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los meseros');
        }
    }     
}
