<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Exports\InvoicesExport;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoices;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            Action::make('report')
                ->icon(Heroicon::ClipboardDocumentCheck)
                ->schema([
                    Select::make('type')
                        ->options(Invoices::getInvoiceReportLabel())
                        ->live()
                        ->required()
                        ->default(Invoices::REPORT_DATE_RANGE)
                        ->afterStateUpdated(function (callable $set, $state) {
                            if ($state === Invoices::REPORT_MONTHLY) {
                                $set('start_date_monthly', now()->startOfMonth()->format('Y-m'));
                                $set('end_date_monthly', now()->endOfMonth()->format('Y-m'));
                            } elseif ($state === Invoices::REPORT_ANNUALY) {
                                $set('start_date_annually', now()->year);
                                $set('end_date_annually', now()->year);
                            } else {
                                $set('start_date_date_range', now()->startOfMonth()->format('Y-m-d'));
                                $set('end_date_date_range', now()->endOfMonth()->format('Y-m-d'));
                            }
                        }),

                    DatePicker::make('start_date_date_range')
                        ->label('Start Date')
                        ->visible(fn (Get $get) => $get('type') === Invoices::REPORT_DATE_RANGE)
                        ->required(fn (Get $get) => $get('type') === Invoices::REPORT_DATE_RANGE)
                        ->default(now()->startOfMonth()),

                    DatePicker::make('end_date_date_range')
                        ->label('End Date')
                        ->visible(fn (Get $get) => $get('type') === Invoices::REPORT_DATE_RANGE)
                        ->required(fn (Get $get) => $get('type') === Invoices::REPORT_DATE_RANGE)
                        ->default(now()->endOfMonth()),

                    TextInput::make('start_date_monthly')
                        ->label('Start Month')
                        ->type('month')
                        ->visible(fn (Get $get) => $get('type') === Invoices::REPORT_MONTHLY)
                        ->required(fn (Get $get) => $get('type') === Invoices::REPORT_MONTHLY)
                        ->default(now()->startOfMonth()->format('Y-m')),

                    TextInput::make('end_date_monthly')
                        ->label('End Month')
                        ->type('month')
                        ->visible(fn (Get $get) => $get('type') === Invoices::REPORT_MONTHLY)
                        ->required(fn (Get $get) => $get('type') === Invoices::REPORT_MONTHLY)
                        ->default(now()->endOfMonth()->format('Y-m')),

                    Select::make('start_date_annually')
                        ->label('Start Year')
                        ->options(function () {
                            $startYear = now()->subYears(3)->year;
                            $endYear = now()->addYears(3)->year;

                            return collect(range($startYear, $endYear))
                                ->mapWithKeys(fn ($year) => [$year => $year])
                                ->toArray();
                        })
                        ->visible(fn (Get $get) => $get('type') === Invoices::REPORT_ANNUALY)
                        ->required(fn (Get $get) => $get('type') === Invoices::REPORT_ANNUALY)
                        ->default(now()->year),

                    Select::make('end_date_annually')
                        ->label('End Year')
                        ->options(function () {
                            $startYear = now()->subYears(3)->year;
                            $endYear = now()->addYears(3)->year;

                            return collect(range($startYear, $endYear))
                                ->mapWithKeys(fn ($year) => [$year => $year])
                                ->toArray();
                        })
                        ->visible(fn (Get $get) => $get('type') === Invoices::REPORT_ANNUALY)
                        ->required(fn (Get $get) => $get('type') === Invoices::REPORT_ANNUALY)
                        ->default(now()->year),

                ])
                ->action(function (array $data) {
                    $startDate = null;
                    $endDate = null;
                    $fileName = 'invoices-report.xlsx';

                    if ($data['type'] === Invoices::REPORT_MONTHLY) {
                        $startDate = Carbon::parse($data['start_date_monthly'])->startOfMonth();
                        $endDate = Carbon::parse($data['end_date_monthly'])->endOfMonth();
                        $fileName = 'invoices-monthly-report-'.$startDate->format('Y-m').'-'.$endDate->format('Y-m').'.xlsx';
                    } elseif ($data['type'] === Invoices::REPORT_ANNUALY) {
                        $startDate = Carbon::parse($data['start_date_annually'].'-01-01')->startOfYear();
                        $endDate = Carbon::parse($data['end_date_annually'].'-12-31')->endOfYear();
                        $fileName = 'invoices-annual-report-'.$startDate->format('Y').'-'.$endDate->format('Y').'.xlsx';
                    } else { // REPORT_DATE_RANGE
                        $startDate = Carbon::parse($data['start_date_date_range'])->startOfDay();
                        $endDate = Carbon::parse($data['end_date_date_range'])->endOfDay();
                        $fileName = 'invoices-date-range-report-'.$startDate->format('Y-m-d').'-'.$endDate->format('Y-m-d').'.xlsx';
                    }

                    return Excel::download(new InvoicesExport($startDate, $endDate, $data['type']), $fileName);
                }),
        ];
    }
}
