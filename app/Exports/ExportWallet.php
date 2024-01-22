<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportWallet implements FromCollection, WithHeadings
{
  public $temp = '';
  /**
     * @return \Illuminate\Support\Collection
     */
  use Exportable;
  public function __construct($query = null)
  {
    $this->temp = $query->toArray();
  }
  public function collection()
  {
    $money = $this->temp;
    $result = [];
    foreach ($money as $row) {
      $result[] = array(
        '0' => $row->Money_ID  ,
        '1' => abs($row->Money_USDT),
        '2' => $row->Money_USDTFee,
        '3' => $row->Money_Rate,
        '4' => $row->MoneyAction_Name,
        '5' => $row->Money_Comment,
        '6' => date( 'Y-m-d H:i:s', $row->Money_Time ),
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID', 'Amount','Fee','Rate', 'Action', 'Comment', 'Time'
    ];
  }
}
