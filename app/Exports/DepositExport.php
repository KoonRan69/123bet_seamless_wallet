<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Model\Money;
use App\Model\Wallet;
use Maatwebsite\Excel\Facades\Excel;
use DB;
class DepositExport implements FromCollection, WithHeadings
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
      	$getAddress = Wallet::whereIn('Address_User', $user)->where('Address_IsUse', 1)->select('Address_Currency', 'Address_Address', 'Address_User')->get()->toArray();
        foreach ($money as $row) {
            if ($row['Money_MoneyStatus'] == 1) {
                $row['Money_Confirm'] = "Success";
            } else {
                $row['Money_Confirm'] = "Cancel";
            }
          	$addressDeposit = '';
          	foreach($getAddress as $k=>$add){
              	if($row['Money_User'] == $add['Address_User'] && $row['Money_CurrencyFrom'] == $add['Address_Currency']){
                  	$addressDeposit = $add['Address_Address'];
                  	//unset($getAddress[$k]);
                  	break;
                }
            }
            $result[] = array(
                $row['Money_ID'],
                $row['Money_User'],
                $level[$row['User_Level']],
                date('Y-m-d H:i:s', $row['Money_Time']),
                $row['Money_Currency'] == 8 && ($row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 19 || $row['Money_MoneyAction'] == 20) ? $row['Money_USDT'] : $row['Money_USDT']/$row['Money_Rate'],
                $row['Currency_To_Symbol'] ?? ($row['Currency_From_Symbol'] ?? $row['Currency_Symbol']),
                $row['Money_Rate'],
                $row['Money_Currency'] == 8 && ($row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 19 || $row['Money_MoneyAction'] == 20) ? $row['Money_USDT']*$row['Money_Rate'] : $row['Money_USDT'],
                $row['Money_Currency'] == 8 && ($row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 19 || $row['Money_MoneyAction'] == 20) ? $row['Money_USDTFee'] * $row['Money_Rate'] : $row['Money_USDTFee'],
                $row['Money_Currency'] == 8 && ($row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 19 || $row['Money_MoneyAction'] == 20) ? $row['Money_USDTFee'] : $row['Money_USDTFee'] / $row['Money_Rate'],
                $row['Money_Comment'],
                $row['Money_Confirm'],
                $row['Money_Address'],
              	$addressDeposit,
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
            'User Level',
            'DateTime',
            'Amount Coin',
            'Currency',
            'Rate',
            'Amount USD',
            'Fee USD',
            'Fee Coin',
            'Comment',
            'Status',
            'Hash',
          	'Address',
        ];

    }

}
