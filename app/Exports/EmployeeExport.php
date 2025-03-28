<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $roles;
    
    protected $positions = [
        'waiter' => 'MESERO',
        'chef' => 'CHEF',
    ];
    
    public function __construct($roles = null)
    {
        $this->roles = $roles;
    }

    public function collection()
    {
        $query = Employee::with('position');

        if ($this->roles && !in_array('all', $this->roles)) {
            $query->whereHas('position', function ($query) {
                $query->whereIn('name', $this->roles);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Primer Apellido',
            'Segundo Apellido',
            'Puesto',
            'Salario',
            'Fecha de Creación',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->name,
            $employee->first_surname,
            $employee->second_surname,
            $this->positions[$employee->position->name] ?? 'N/A',
            $employee->salary,
            $employee->created_at->format('d-m-Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Aplicar negritas y color de fondo a las cabeceras
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12], 
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '780000'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
            
            // Centrar los valores de todas las columnas
            'A:Z' => [
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
