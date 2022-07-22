<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IncidentsExport implements FromView, ShouldAutoSize, WithColumnFormatting, WithMapping, WithStyles
{
    protected $incidents;

    public function __construct(array $incidents)
    {
        $this->incidents = $incidents;
    }

    public function map($incidents): array
    {
        return [
            Date::dateTimeToExcel($incidents->created_at),
            Date::dateTimeToExcel($incidents->resolution)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => 'dd-mm-yy hh:mm',
            'J' => 'dd-mm-yy hh:mm',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]]
        ];
    }

    public function view(): View
    {
        return view('exports.incidents', [
            'incidents' => $this->incidents
        ]);
    }

}
