<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Invoices;
use App\Models\Payment;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; // Add this concern
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\DB;

class InvoicesExport implements
    FromCollection,
    WithColumnFormatting,
    WithHeadings,
    WithEvents,
    WithStartRow
{
    protected $startDate;
    protected $endDate;
    protected $type;

    public function __construct($startDate, $endDate, $type)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type = $type;
    }

    protected $customerCategories = [];

    protected function getCustomerCategories()
    {
        return $this->customerCategories;
    }

    public function collection(): Collection
    {
        // Single query to get all invoice and payment data
        $results = DB::table('invoices as i')
            ->join('customer_packages as cp', 'i.customer_package_id', '=', 'cp.id')
            ->join('customers as c', 'cp.customer_id', '=', 'c.id')
            ->leftJoin('payment_allocations as pa', 'pa.invoice_id', '=', 'i.id')
            ->leftJoin('payments as p', function ($join) {
                $join->on('pa.payment_id', '=', 'p.id')
                    ->whereBetween('p.payment_datetime', [$this->startDate, $this->endDate]);
            })
            ->whereBetween('i.invoice_date', [$this->startDate, $this->endDate])
            ->select(
                'c.id as customer_id',
                'c.full_name',
                'c.username',
                DB::raw("CASE WHEN isolir_at is not null THEN 'yes' ELSE null END AS isolir"),
                'i.invoice_date',
                'i.total_amount',
                'p.payment_datetime',
                'pa.amount as payment_amount',
                'c.customer_category'
            )
            ->get()
            ->groupBy('customer_id');

        $data = new Collection;
        $period = $this->generateDatePeriod();

        foreach ($results as $customerId => $records) {
            $firstRecord = $records->first();
            $this->customerCategories[] = $firstRecord->customer_category; // Store category

            $rowData = [$firstRecord->username ?? $firstRecord->full_name, $firstRecord->isolir];

            foreach ($period as $date) {
                // Calculate invoice total (avoid duplicates by using unique invoice dates)
                $invoiceTotal = $records
                    ->unique(function ($record) {
                        return $record->invoice_date . '-' . $record->total_amount;
                    })
                    ->filter(function ($record) use ($date) {
                        return $this->matchesDate(Date::parse($record->invoice_date), $date);
                    })
                    ->sum('total_amount');

                // Calculate payment total
                $paymentTotal = $records
                    ->filter(function ($record) use ($date) {
                        return $record->payment_datetime &&
                            $this->matchesDate(Date::parse($record->payment_datetime), $date);
                    })
                    ->sum('payment_amount');

                $rowData[] = $invoiceTotal;
                $rowData[] = $paymentTotal;
            }

            $data->push($rowData);
        }

        return $data;
    }

    public function headings(): array
    {
        $period = $this->generateDatePeriod();

        // First row: Month-Year headers (will be merged)
        $firstRow = ['Customer', 'Isolir'];
        foreach ($period as $date) {
            $firstRow[] = $this->formatDateForHeading($date);
            $firstRow[] = ''; // Empty cell for merge
        }

        // Second row: Invoice and Payment sub-headers
        $secondRow = ['', ''];
        foreach ($period as $date) {
            $secondRow[] = 'Invoice';
            $secondRow[] = 'Payment';
        }

        return [$firstRow, $secondRow];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $period = $this->generateDatePeriod();

                // Merge cells for month-year headers
                $columnIndex = 3;
                foreach ($period as $date) {
                    $startColumn = Coordinate::stringFromColumnIndex($columnIndex);
                    $endColumn = Coordinate::stringFromColumnIndex($columnIndex + 1);

                    $sheet->mergeCells("{$startColumn}1:{$endColumn}1");

                    $sheet->getStyle("{$startColumn}1:{$endColumn}1")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $columnIndex += 2;
                }

                $mergedCells = [
                    'A1:A2',
                    'B1:B2'
                ];
                foreach ($mergedCells as $cell) {
                    $sheet->mergeCells($cell);
                    $sheet->getStyle($cell)
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Color rows for "free_forever" customers
                $rowNumber = 3; // Data starts from row 3
                foreach ($this->getCustomerCategories() as $category) {
                    if ($category === 'free_forever') {
                        $sheet->getStyle("A{$rowNumber}:" . $sheet->getHighestColumn() . $rowNumber)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('90EE90'); // Light green color
                    }


                    $rowNumber++;
                }

                // Set column widths (only need to set once per column)
                $sheet->getColumnDimension('A')->setWidth(25); // Customer column
                $columnIndex = 3;
                foreach ($period as $date) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex))->setWidth(12); // Invoice
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex + 1))->setWidth(12); // Payment
                    $columnIndex += 2;
                }

                // Set border for entire data range in one call
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);
            },
        ];
    }

    public function map($row): array
    {
        return array_values($row);
    }

    public function columnFormats(): array
    {
        $formats = [];
        $period = $this->generateDatePeriod();
        $columnIndex = 2;

        foreach ($period as $date) {
            $formats[Coordinate::stringFromColumnIndex($columnIndex)] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1;
            $formats[Coordinate::stringFromColumnIndex($columnIndex + 1)] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1;
            $columnIndex += 2;
        }

        return $formats;
    }

    public function startRow(): int
    {
        return 3; // Data starts from row 3 (after 2 header rows)
    }

    protected function matchesDate($datetime, $periodDate): bool
    {
        if ($this->type === Invoices::REPORT_MONTHLY) {
            return $datetime->format('Y-m') === $periodDate->format('Y-m');
        } elseif ($this->type === Invoices::REPORT_ANNUALY) {
            return $datetime->format('Y') === $periodDate->format('Y');
        } else { // REPORT_DATE_RANGE
            return $datetime->format('Y-m-d') === $periodDate->format('Y-m-d');
        }
    }

    protected function generateDatePeriod(): CarbonPeriod
    {
        if ($this->type === Invoices::REPORT_MONTHLY) {
            return CarbonPeriod::create($this->startDate, '1 month', $this->endDate);
        } elseif ($this->type === Invoices::REPORT_ANNUALY) {
            return CarbonPeriod::create($this->startDate, '1 year', $this->endDate);
        } else { // REPORT_DATE_RANGE
            return CarbonPeriod::create($this->startDate, '1 day', $this->endDate);
        }
    }

    protected function formatDateForHeading($date): string
    {
        if ($this->type === Invoices::REPORT_MONTHLY) {
            return $date->format('M Y');
        } elseif ($this->type === Invoices::REPORT_ANNUALY) {
            return $date->format('Y');
        } else { // REPORT_DATE_RANGE
            return $date->format('d-m-Y');
        }
    }
}
