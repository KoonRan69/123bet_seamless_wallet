<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportAginSportBook implements FromCollection, WithHeadings
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
      $checkWin = strpos($row->simplified_result, 'Win');
      $checkLose = strpos($row->simplified_result, 'Lose');
      if($checkLose !== false){
        $status = 'Lose';
      }elseif($checkWin !== false){
        $status = 'Win';
      }else{
        $status = 'Pending';
      }

      $result[] = array(
        '0' => $row->id,
        '1' => $row->username,
        '2' => $row->account,
        '3' => $row->cus_account,
        '4' => $row->create_date,
        '5' => $status,
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID', 'User ID','Amount','Profit', 'DateTime', 'Win/Loss'
    ];
  }
}
