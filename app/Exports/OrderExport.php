<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{
    protected $filters;
    protected $totalAmount = 0; // Variable para almacenar el total

    public function __construct($filters = null)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $orders = Order::with('orderStatus', 'paymentType')
            ->when(!empty($this->filters['date']), function ($query) {
                $query->whereDate('created_at', $this->filters['date']);
            })
            ->when(!empty($this->filters['status']), function ($query) {
                $query->whereHas('orderStatus', function ($query) {
                    $query->whereIn('name', $this->filters['status']);
                });
            })
            ->when(empty($this->filters['status']), function ($query) {
                $query->whereHas('orderStatus', function ($query) {
                    $query->whereIn('id', [OrderStatus::CANCELED, OrderStatus::PAID]);
                });
            })
            ->get();

        // Calcular la suma total de la columna 'total_amount'
        $this->totalAmount = $orders->sum('total_amount');

        return $orders;
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Mesa',
            'Estado',
            'Número de comensales',
            'Fecha de pago',
            'Fecha de cancelación',
            'Tipo de pago',
            'Total',
        ];
    }

    public function map($order): array
    {
        return [
            $order->folio,
            $order->table->name ?? 'SIN ASIGNAR',
            strtoupper($order->orderStatus->name ?? 'SIN ESTADO'),
            $order->num_dinners,
            $order->payment_date ?? 'SIN FECHA',
            $order->cancellation_date ?? 'SIN FECHA',
            mb_strtoupper($order->paymentType->name ?? 'NO ESPECIFICADO', 'UTF-8'),
            number_format($order->total_amount, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Agregar la fila de "TOTAL GENERAL"
        $lastRow = $sheet->getHighestRow() + 2; // Obtiene la última fila y suma 1
        $sheet->setCellValue('G' . $lastRow, 'TOTAL GENERAL');
        $sheet->setCellValue('H' . $lastRow, $this->totalAmount); // Coloca la suma total

        // Aplicar formato de moneda a la celda del total
        $sheet->getStyle("H{$lastRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00'); // Formato de moneda

        // Aplicar formato de moneda a la columna Total
        $sheet->getStyle('H2:H' . $lastRow)->getNumberFormat()->setFormatCode('"$"#,##0.00'); // Formato de moneda

        // Aplicar estilos a la fila de total
        $sheet->getStyle("G{$lastRow}:H{$lastRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '000']],
            'alignment' => ['horizontal' => 'center'],
        ]);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '780000'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
            'A:Z' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
