<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportEvolution implements FromCollection, WithHeadings
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
        '1' =>$row->evo_id,
        '2' =>$row->evo_agent,
        '3' =>$row->evo_username,
        '4' =>$row->userId,
        '5' =>$row->evo_currency,
        '6' =>$row->evo_game,
        '7' =>$row->evo_game_id,
        '8' =>$row->evo_betcode,
        '9' =>$row->evo_bet,
        '10' =>$row->evo_payout,
        '11' =>$row->evo_win,
        '12' =>$row->evo_status,
        '13' =>$row->evo_result,
        '14' =>$row->time_123betnow,
        '15' =>$row->statistical,
      );
    }
    return (collect($result));
  }
  public function headings(): array
  {

    return [
      'ID', 'ID Evo', 'Agen Evo', 'User Name',  'User ID', 'Currency Evo' ,'Type Game' ,'Game ID','Bet Code','Bet Amount', 'Payout Amount', 'Win Amount','Status','Result','Time 123Betnow', 'Status statictical'
    ];
  }
}
