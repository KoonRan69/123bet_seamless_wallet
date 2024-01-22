<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Model\Money;
use App\Model\Wallet;
use Maatwebsite\Excel\Facades\Excel;
use DB;
class MinusBalanceUserExport implements FromCollection, WithHeadings
{
    public $temp = '';
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function __construct($query = null){
        $this->temp = $query;

    }
    public function collection()
    {
        //Affiliate Commission

        //$percentArr = [1=>0.01, 2=>0.02, 3=>0.03];

        $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
        $money = $this->temp;
      	//dd($money);
      	$user = $money->pluck('Money_User')->toArray();
      	$money = $money->toArray();
        //dd($money,$user);
        $result = [];
        foreach ($money as $row) {
            $result[] = array(
                $row['Money_ID'],
                $row['Money_User'],
                $row['Money_USDT'] ,
                $row['Money_Currency'],
                $row['Money_Rate'],
                $row['Money_USDTFee'] ,
                $row['Money_Comment'],
                $row['Money_MoneyAction'],
            );
        }
      	//dd($result);
        return (collect($result));
    }
    public function headings(): array
    {

        return [
            'ID',
            'User ID',
            'Amount',
            'Currency',
            'Rate',
            'Fee',
            'Comment',
            'Action',
        ];

    }

}
