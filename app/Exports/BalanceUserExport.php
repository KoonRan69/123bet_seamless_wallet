<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Model\User;
use DB;
class BalanceUserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $temp = '';
    use Exportable;
    public function __construct($query = null){
        $this->temp = $query->toArray();
        
    }
    public function collection()
    {
        $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'BOT', 10=>'Admin View');
        $user = $this->temp;
        $result = [];
        $currency = DB::table('currency')->pluck('Currency_Symbol', 'Currency_ID')->toArray();
        // dd($currency);
        foreach ($user as $row) {
            // dd($listAddress);
            $result[] = array(
                '0' => $row->id,
                '1' => $row->user,
                '2' => $row->main,
                '3' => $row->casino,
                '4' => $row->sportbook,
                '5' => $row->datetime,
            );
        }
        return (collect($result));

    }
    public function headings(): array
    {
        
        return [
            'ID', 'UserID', 'Balance Main', 'Balance Casino', 'Balance Sportbook', 'DateTime' 
        ];
        
    }
}
