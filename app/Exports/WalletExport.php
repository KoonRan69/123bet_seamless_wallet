<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Model\Money;
use Maatwebsite\Excel\Facades\Excel;
class WalletExport implements FromCollection, WithHeadings
{
  public $temp = '';
  /**
    * @return \Illuminate\Support\Collection
    */
  use Exportable;
  public function __construct($query = null){
    $this->temp = $query->toArray();

  }
  public function collection()
  {
    //Affiliate Commission

    //$percentArr = [1=>0.01, 2=>0.02, 3=>0.03];

    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $money = $this->temp;
    // dd($money);
    $result = [];
    $arr_coin = Money::getSymbol();
    foreach ($money as $row) {
      if ($row['Money_MoneyStatus'] == 1) {
        if (($row['Money_MoneyAction'] == 2 || $row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 18) && $row['Money_Confirm'] == 0) {
          $row['Money_Confirm'] = "Pending";
        } else {
          $row['Money_Confirm'] = "Success";
        }
      } else {
        $row['Money_Confirm'] = "Cancel";
      }

      $result[] = array(
        '0' => $row['Money_ID'],
        '1' => $row['Money_User'],
        '2' => $level[$row['User_Level']],
        '3' => $row['MoneyAction_Name'],
        '4' => $row['Money_Comment'],
        '5' => date('Y-m-d H:i:s', $row['Money_Time']),
        '6' => $row['Money_Currency'] == 8 && ($row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 19 || $row['Money_MoneyAction'] == 20) ? $row['Money_USDT'] : $row['Money_USDT']/$row['Money_Rate'],
        '7' => $row['Money_MoneyAction'] == 1 ? $arr_coin[$row['Money_CurrencyFrom']] : ($row['Money_MoneyAction'] == 2 ? $arr_coin[$row['Money_CurrencyTo']]: $arr_coin[$row['Money_Currency']]),
        '8' => $row['Money_Rate'],
        '9' => $row['Money_Currency'] == 8 && ($row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 19 || $row['Money_MoneyAction'] == 20) ? $row['Money_USDT']*$row['Money_Rate'] : $row['Money_USDT'],
        '10' => $row['Money_Currency'] == 8 && ($row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 19 || $row['Money_MoneyAction'] == 20) ? $row['Money_USDTFee'] * $row['Money_Rate'] : $row['Money_USDTFee'],
        '11' => $row['Money_Currency'] == 8 && ($row['Money_MoneyAction'] == 21 || $row['Money_MoneyAction'] == 19 || $row['Money_MoneyAction'] == 20) ? $row['Money_USDTFee'] : $row['Money_USDTFee'] / $row['Money_Rate'],
        '12' => $row['Money_Confirm'],
        '13' => $row['Money_Address'],
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID',
      'User ID',
      'User Level',
      'Action',
      'Comment',
      'DateTime',
      'Amount Coin',
      'Currency',
      'Rate',
      'Amount USD',
      'Fee USD',
      'Fee Coin',
      'Status',
      'Address/Hash'
    ];

  }

}
