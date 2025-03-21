<?php

namespace App\Http\Controllers;

use Exception;
use App\Traits\Loggable;
use App\Models\PaymentType;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentTypeRequest;


class PaymentTypeController extends Controller
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

            $paymentTypes = PaymentType::with('dishType')->select(
                'id',
                'name',
            )->orderBy('id', 'DESC')->paginate($rowsPerPage, ['*'], 'page', $page);

            return ApiResponse::success(['paymentTypes' => $paymentTypes], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los platillos');
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
    public function store(StorePaymentTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentType $paymentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentType $paymentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentType $paymentType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentType $paymentType)
    {
        //
    }
    public function getPaymentTypes(Request $request)
    {
        try {
            $paymentTypes = PaymentType::select(
                'id',
                'name',
            )->orderBy('id', 'DESC')->get();

            return ApiResponse::success(['paymentTypes' => $paymentTypes], 'Operación exitosa');
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al obtener los platillos');
        }
    }
}
