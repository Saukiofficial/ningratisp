<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Invoices;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InvoicesExport implements
    FromCollection,
    WithColumnFormatting,
    WithHeadings,
    WithMapping
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

    public function collection(): Collection
    {
        // Fetch all invoices within the date range
        $invoices = Invoices::query()
            ->with('customerPackage')
            ->whereBetween('invoice_date', [$this->startDate, $this->endDate])
            ->get();

        // Get all unique customers who have invoices in this period
        $customerIds = $invoices->pluck('customerPackage.customer_id')->unique();
        $customers = Customer::whereIn('id', $customerIds)->get();

        $data = new Collection;

        // Generate date periods based on type
        $period = $this->generateDatePeriod();

        foreach ($customers as $customer) {
            $rowData = ['Customer' => $customer->full_name ?? $customer->user_name]; // First column is customer name
            foreach ($period as $date) {
                $totalAmount = $invoices->filter(function ($invoice) use ($customer, $date) {
                    // Filter by customer and date
                    if ($this->type === Invoices::REPORT_MONTHLY) {
                        return $invoice->customerPackage->customer_id === $customer->id &&
                            $invoice->invoice_date->format('Y-m') === $date->format('Y-m');
                    } elseif ($this->type === Invoices::REPORT_ANNUALY) {
                        return $invoice->customerPackage->customer_id === $customer->id &&
                            $invoice->invoice_date->format('Y') === $date->format('Y');
                    } else { // REPORT_DATE_RANGE
                        return $invoice->customerPackage->customer_id === $customer->id &&
                            $invoice->invoice_date->format('Y-m-d') === $date->format('Y-m-d');
                    }
                })->sum('total_amount'); // Sum the total_amount for filtered invoices

                $rowData[$this->formatDateForHeading($date)] = $totalAmount;
            }
            $data->push($rowData);
        }

        return $data;
    }

    public function headings(): array
    {
        $headings = ['Customer'];
        $period = $this->generateDatePeriod();
        foreach ($period as $date) {
            $headings[] = $this->formatDateForHeading($date);
        }

        return $headings;
    }

    public function map($row): array
    {
        return array_values($row);
    }

    public function columnFormats(): array
    {
        $formats = [];
        $period = $this->generateDatePeriod();
        $columnIndex = 1; // Start from the second column (index 1) for dates

        foreach ($period as $date) {
            $formats[Coordinate::stringFromColumnIndex($columnIndex)] = NumberFormat::FORMAT_TEXT;
            $columnIndex++;
        }

        return $formats;
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
