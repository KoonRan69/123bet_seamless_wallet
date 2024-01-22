<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportAeSexy implements FromCollection, WithHeadings
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
      $winAmount = $row->realWinAmount;
      if($winAmount == 0){
        $status = 'Lose';
      }elseif($winAmount > 0){
        $status = 'Win';
      }else{
        $status = 'Pending';
      }
      $result[] = array(
        '0' => $row->id,
        '1' => $row->userId,
        '2' => $row->realBetAmount,
        '3' => $row->realWinAmount,
        '4' => $row->turnover,
        '5' => $row->updateTime,
        '6' => $row->roundId,
        '7' => $status,
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID', 'User ID','Bet Amount','Win Amount','Turnover', 'DateTime', 'Round Id', 'Win/Loss'
    ];
  }
}
