<?php

namespace App\Exports;

use App\Models\IkhtisarHarian;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class IkhtisarHarianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithDrawings, WithCustomStartCell, WithColumnWidths
{
    protected $unitId;
    protected $startDate;
    protected $endDate;

    public function __construct($unitId = null, $startDate = null, $endDate = null)
    {
        $this->unitId = $unitId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 15, // Unit
            'C' => 10, // Terpasang
            'D' => 10, // DMN
            'E' => 10, // Mampu
            'F' => 10, // Siang
            'G' => 10, // Malam
            'H' => 10, // Ratio
            'I' => 12, // Bruto
            'J' => 12, // Netto
            'K' => 10, // kWh
            'L' => 8,  // %
            'M' => 8,  // Periode
            'N' => 8,  // Operasi
            'O' => 8,  // Trip
            'P' => 10, // Derating
            'Q' => 8,  // EAF
            'R' => 8,  // EFOR
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('PLN Logo');
        $drawing->setPath(public_path('images/logo-pln.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function headings(): array
    {
        return [
            ['PT PLN NUSANTARA POWER'],
            ['UNIT PEMBANGKITAN KENDARI'],
            ['UNIT : PLTD WUAWUA'],
            ['IKHTISAR HARIAN'],
            [
                'No.',
                'Unit',
                'Daya',
                '',
                '',
                'Produksi',
                '',
                'Ratio',
                'Pemakaian Sendiri',
                '',
                'Jam Indikator',
                '',
                '',
                'Trip Non OMC',
                '',
                '',
                'Derating',
                '',
                '',
                'Kinerja Pembangkit',
                '',
                '',
                '',
                '',
                'Capability',
                '',
                'Pemakaian Bahan Bakar/Baku',
                '',
                '',
                '',
                '',
                'JSI'
            ],
            [
                '',
                '',
                'Terpasang',
                'DMN',
                'Mampu',
                'Bruto',
                'Netto',
                '%',
                'kWh',
                '%',
                'PO',
                'MO',
                'FO',
                'EPDH',
                'EFOR',
                'EUOF',
                'ESDH',
                'EAF',
                'SdOF',
                'EFOR',
                'SdOF (CAP)',
                'NOF',
                'HSD',
                'B.35',
                'MFO',
                'TOTAL BBM'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Background color untuk header tabel (biru muda)
        $tableHeaderBackground = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'B8E2FC' // Warna biru muda
                ]
            ]
        ];

        // Style untuk header utama
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        // Style untuk header tabel
        $tableHeaderStyle = [
            'font' => [
                'bold' => true,
                'size' => 10
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'B8E2FC' // Warna biru muda
                ]
            ]
        ];

        // Style untuk data
        $dataStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        // Merge cells untuk header
        $sheet->mergeCells('A1:AN1'); // PT PLN NUSANTARA POWER
        $sheet->mergeCells('A2:AN2'); // UNIT PEMBANGKITAN KENDARI
        $sheet->mergeCells('A3:AN3'); // UNIT : PLTD WUAWUA
        $sheet->mergeCells('A4:AN4'); // IKHTISAR HARIAN

        // Merge cells untuk kolom header
        $sheet->mergeCells('C5:E5');  // Daya (MW)
        $sheet->mergeCells('F5:G5');  // Beban Puncak
        $sheet->mergeCells('I5:J5');  // Produksi
        $sheet->mergeCells('K5:L5');  // Pemakaian Sendiri
        $sheet->mergeCells('M5:O5');  // Jam Indikator
        $sheet->mergeCells('P5:R5');  // Trip Non OMC
        $sheet->mergeCells('S5:U5');  // Derating
        $sheet->mergeCells('V5:Z5');  // Kinerja Pembangkit
        $sheet->mergeCells('AA5:AB5'); // Capability
        $sheet->mergeCells('AC5:AG5'); // Pemakaian Bahan Bakar/Baku

        // Terapkan style
        $sheet->getStyle('A1:A4')->applyFromArray($headerStyle);
        $sheet->getStyle('A5:AN6')->applyFromArray($tableHeaderStyle);
        $sheet->getStyle('A7:AN' . ($sheet->getHighestRow()))->applyFromArray($dataStyle);

        // Set row height
        $sheet->getRowDimension(5)->setRowHeight(30);
        $sheet->getRowDimension(6)->setRowHeight(30);

        // Perbaikan untuk set column width
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 
                    'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
                    'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK'];
        
        // Set default width untuk semua kolom
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setWidth(12);
        }

        // Set specific width untuk beberapa kolom
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(20);  // Unit
        
        return $sheet;
    }

    public function collection()
    {
        $query = IkhtisarHarian::with(['unit', 'machine']);

        if ($this->unitId) {
            $query->where('unit_id', $this->unitId);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        return $query->latest()->get();
    }

    public function map($ikhtisarHarian): array
    {
        static $no = 1;
        return [
            $no++,
            $ikhtisarHarian->machine->name,
            number_format($ikhtisarHarian->installed_power, 3),
            number_format($ikhtisarHarian->dmn_power, 3),
            number_format($ikhtisarHarian->capable_power, 3),
            number_format($ikhtisarHarian->peak_load_day, 3),
            number_format($ikhtisarHarian->peak_load_night, 3),
            number_format($ikhtisarHarian->power_ratio, 2),
            number_format($ikhtisarHarian->gross_production, 3),
            number_format($ikhtisarHarian->net_production, 3),
            number_format($ikhtisarHarian->self_usage_kwh, 3),
            number_format($ikhtisarHarian->self_usage_percent, 2),
            number_format($ikhtisarHarian->period_hours, 1),
            number_format($ikhtisarHarian->operating_hours, 1),
            $ikhtisarHarian->trip_non_omc,
            number_format($ikhtisarHarian->derating, 2),
            number_format($ikhtisarHarian->eaf, 2),
            number_format($ikhtisarHarian->efor, 2)
        ];
    }
} 