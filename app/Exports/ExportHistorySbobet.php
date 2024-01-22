<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportHistorySbobet implements FromCollection, WithHeadings
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
        '0' => $row->id,
        '1' => $row->username,
        '2' => $row->userId,
        '3' => $row->turnover_by_stake,
        '4' => $row->net_turnover_by_stake,
        '5' => $row->turnover_by_actual_stake,
        '6' => $row->net_turnover_by_actual_stake,
        '7' => $row->currency,
        '8' => $row->number_of_bets,
        '9' => $row->member_wins,
        '10' => $row->company,
        '11' => $row->sgd_company,
        '12' => $row->time_123betnow,
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID', 'User Name','User ID','Turnover By Stake','Net Turnover By Stake','Turnover By Actual Stake',	'Net Turnover By Actual Stake',	'Tiền tệ',	'Số cược','Thành viên thắng','Công ty','SGD công ty'
    ];
  }
}
