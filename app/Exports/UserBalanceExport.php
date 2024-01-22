<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Model\User;
use DB;
class UserBalanceExport implements FromCollection, WithHeadings
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
    $user = $this->temp;
    $result = [];
    foreach ($user['data'] as $row) {
      $status = 'No';
      if($row['User_Block'] == 1)  $status = 'Block';
      $balance = User::getBalance($row['User_ID'], 3);
      $result[] = array(
        '0' => $row['User_ID'],
        '1' => 'Member',
        '2' => $balance,
        '3' =>  $status,
      );
    }
    return (collect($result));

  }
  public function headings(): array
  {

    return [
      'UserID','Level', 'Balance', 'Status'
    ];

  }
}
