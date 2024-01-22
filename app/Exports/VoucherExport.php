<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Model\Money;
use Maatwebsite\Excel\Facades\Excel;
class VoucherExport implements FromCollection, WithHeadings
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


    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $money = $this->temp;
    $result = [];
    foreach ($money as $row) {
      $row = (array)$row;
      if ($row['status'] == 1) {
        $row['status'] = "Used";
      } elseif($row['status'] == 0) {
        $row['status'] = "NoUse";
      }else{
        $row['status'] = "Canceled";
      }

      $result[] = array(
        $row['id'],
        $row['User_ID'],
        $level[$row['User_Level']],
        $row['type'],
        $row['status'],
        $row['datetime'],
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID',
      'User',
      'Level',
      'Type',
      'Status',
      'DateTime',
    ];

  }

}
