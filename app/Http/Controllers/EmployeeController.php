<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeExport;
use Exception;
use App\Models\User;
use App\Models\Employee;
use App\Traits\Loggable;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\search;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
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
            $filterPosition = $request->get('filterPosition');

            $employee = Employee::with('position:id,name')->select(
                'id',
                'name',
                'first_surname',
                'second_surname',
                'position_id',
                'salary'
            )->when(!empty($search), function ($query) use ($search) {
                return $query->search($search);
            })->when(!empty($filterPosition), function ($query) use ($filterPosition) {
                return $query->whereHas('position', function ($query) use ($filterPosition) {
                    $query->whereIn('name', $filterPosition);
                });
            })
                ->orderBy('id', 'DESC')->paginate($rowsPerPage, ['*'], 'page', $page);

            return ApiResponse::success(['employees' => $employee], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los empleados');
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
        try {
            $employee = new Employee();
            $employee->fill($request->all());
            $employee->save();

            $userName = $this->generateUserName($employee);

            User::create([
                'user_name' => $userName,
                'email' => $employee->email,
                'password' => bcrypt($userName),
                'employee_id' => $employee->id,
            ]);

            $employee->load('position');

            $role = Role::where('name', $employee->position->name)->first();

            $employee->user->assignRole($role);

            return ApiResponse::success(['employee' => $employee], 'Empleado creado exitosamente');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los empleados');
        }
    }

    public function generateUserName($employee)
    {
        $firstName = explode(' ', $employee->name)[0];
        return strtolower($firstName) . $employee->id;
    }
    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        try {
            $employee->fill($request->all());
            $employee->save();
            $employee->load('position');

            $role = Role::where('name', $employee->position->name)->first();

            $employee->user->assignRole($role);

            return ApiResponse::success(['employee' => $employee], 'Empleado actualizado exitosamente');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los empleados');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return ApiResponse::success(['employee' => $employee], 'Empleado eliminado exitosamente');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al eliminar el empleado');
        }
    }
    public function exportEmployeeExcel(Request $request) {
        try {
            return Excel::download(new EmployeeExport($request->all()), 'users.xlsx');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al exportar los empleados');
        }
    }
}
