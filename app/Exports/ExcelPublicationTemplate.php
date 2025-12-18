<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;


class ExcelPublicationTemplate implements FromView  ,WithEvents,ShouldAutoSize
{
    use Exportable;

    public function __construct(string $pca_no)
    {
        $this->pca_no = $pca_no;
    }
    public function view(): View
    {
        $issue_date='';
        if(str_contains(base64_decode($this->pca_no),'dr-')){

            $id=str_replace("dr-", "",base64_decode($this->pca_no));
            $details=DB::table('waybill.tblpublication_added_transaction')
            ->leftJoin('waybill.tblpca_accounts','tblpublication_added_transaction.pca_account_no','=','tblpca_accounts.pca_account_no')
            ->where('tblpublication_added_transaction.publication_added_transaction_id',$id)
            ->select('tblpca_accounts.full_name','tblpca_accounts.pca_account_no','tblpublication_added_transaction.issue_date')
            ->first();
            $issue_date=$details->issue_date;
            $data=DB::table('waybill.tblpublication_added_transaction_details as details')
            ->where('details.publication_added_transaction_id',$id)
            ->leftJoin('waybill.tblpublication_agent',function($join){
                $join->on('details.publication_agent_id', '=', 'tblpublication_agent.publication_agent_id')
                ->whereNotNull('details.publication_agent_id');
            })
            ->orderByRaw('-IFNULL(details.client_sequence,tblpublication_agent.agent_sequence) DESC')
            ->selectRaw("
            details.agent_name as publication_agent_name,
            details.agent_contact_person as contact_person,
            details.agent_contact_no as contact_no,
            details.agent_address_street as street,
            details.agent_address_barangay as barangay,
            details.agent_address_city as cities_name,
            details.agent_address_province as province_name,
            details.main_qty,
            details.tabloid_qty
            ")
            ->get();

        }else{

            $details=DB::table('waybill.tblpca_accounts')
            ->where('tblpca_accounts.pca_account_no',base64_decode($this->pca_no))
            ->select('full_name','pca_account_no')
            ->first();

            $data=DB::table('waybill.tblpublication_agent')
            ->leftJoin('waybill.tblsectorate2','tblpublication_agent.brgy','=','tblsectorate2.sectorate_no')
            ->leftJoin('waybill.tblcitiesminicipalities','tblsectorate2.city_id','=','tblcitiesminicipalities.cities_id')
            ->leftJoin('waybill.tblprovinces','tblcitiesminicipalities.province_id','=','tblprovinces.province_id')
            ->where('tblpublication_agent.pca_account_no',base64_decode($this->pca_no))
            ->orderByRaw('-tblpublication_agent.agent_sequence DESC')
            ->selectRaw("
            tblpublication_agent.publication_agent_name,
            UPPER(tblpublication_agent.street) as street,
            tblpublication_agent.contact_person,
            tblpublication_agent.contact_no,
            UPPER(tblsectorate2.barangay) as barangay,
            UPPER(tblcitiesminicipalities.cities_name) as cities_name,
            UPPER(tblprovinces.province_name) as province_name,
            '' as main_qty,
            '' as tabloid_qty
            ")
            ->get();
        }

        return view('exports.publication_template', [
            'details' => $details,
            'data' => $data,
            'issue_date' => $issue_date
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getProtection()->setSheet(true);
                $sheet->protectCells('A1:B1', 'doff^pub_template%');
                $sheet->protectCells('A2', 'doff^pub_template%');
                $sheet->protectCells('A3:I4', 'doff^pub_template%');
                $sheet->getStyle('B2')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
                $sheet->getStyle('A5:I100')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
                $sheet->getStyle('H5:H100')->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('I5:I100')->getNumberFormat()->setFormatCode('#,##0.00');

                $b2=$sheet->getCell('B2')->getValue();
                if($b2==''){ $b2=date('Y-m-d'); }
                $sheet->setCellValue('B2', \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($b2));
                $sheet->getStyle('B2')->getNumberFormat()->setFormatCode('mmmm dd, YYYY');
                $sheet->getStyle('B2')->getAlignment()->setHorizontal('left');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A3:I3')->getFont()->setBold(true);
                $sheet->getStyle('A4:I4')->getFont()->setBold(true);
                //$sheet->getStyle('C1:C100')->setQuotePrefix(true);

                $event->sheet->getStyle('B1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                ]);
                $event->sheet->getStyle('B2')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                ]);

                // $sheet->getColumnDimension('A')->setWidth(50);
                // $sheet->getColumnDimension('B')->setWidth(50);
                // $sheet->getColumnDimension('C')->setWidth(25);
                // $sheet->getColumnDimension('D')->setWidth(50);
                // $sheet->getColumnDimension('E')->setWidth(50);
                // $sheet->getColumnDimension('F')->setWidth(50);
                // $sheet->getColumnDimension('G')->setWidth(50);
                // $sheet->getColumnDimension('H')->setWidth(15);
                // $sheet->getColumnDimension('I')->setWidth(15);
            },
        ];
    }

}
