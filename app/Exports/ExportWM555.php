<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportWM555 implements FromCollection, WithHeadings
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
        
                '0' =>$row->id,
                '1' =>$row->username,
                '2' =>$row->user_id,
                '3' =>$row->game_type,
                '4' =>$row->bet_id,
                '5' =>$row->bet_amount,
                '6' =>$row->rolling,
                '7' =>$row->result_amount,
                '8' =>$row->balance,
                '9' =>$row->bet_time,
				'10' =>$row->payout_time,
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID', 'UserName', 'UserID', 'Game Type', 'BetID', 'Bet Amount', 'Rolling', 'Result Amount', 'Balance', 'Bet Time', 'Payout Time'
    ];
  }
}
