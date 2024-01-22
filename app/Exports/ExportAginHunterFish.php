<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportAginHunterFish implements FromCollection, WithHeadings
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
      $amount = $row->profit;
      if($amount < 0){
        $status = 'Lose';
      }elseif($amount >= 0){
        $status = 'Win';
      }else{
        $status = 'Pending';
      }
      $result[] = array(
        '0' => $row->id,
        '1' => $row->username,
        '2' => $row->totalbulletcost,
        '3' => $row->totalfishcost,
        '4' => $row->profit,
        '5' => $row->create_date,
        '6' => $status,
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID', 'User ID','TotalBulletCost','TotalFishCost','Profit', 'DateTime', 'Win/Loss'
    ];
  }
}
