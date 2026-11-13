<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReporteExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function __construct(private array $columnas, private array $filas)
    {
    }

    public function array(): array
    {
        return $this->filas;
    }

    public function headings(): array
    {
        return $this->columnas;
    }
}
