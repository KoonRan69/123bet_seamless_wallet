<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class EvolutionThisDay implements ToCollection, WithHeadingRow
{
  /**
    * @param Collection $collection
    */
  public function collection (Collection $rows)
  {
    $dataInsert = array();
    foreach ($rows as $key=>$value) 
    {
      if($value['username']){
        $dataInsert[] = [
          'evo_username' => $value['username'],
          'userId' => str_replace('now_', '', $value['username']),
          'evo_agent' => $value['agent'],
          'evo_id' => $value['id'],
          'evo_currency' => $value['currency'],
          'evo_game' => $value['game'],
          'evo_game_id' => $value['game_id'],
          'evo_betcode' => $value['bet_code'],
          'evo_bet' => $value['bet'],
          'evo_payout' => $value['payout'],
          'evo_win' => $value['win'],
          'evo_datetime' => date('Y-m-d',strtotime($value['datetime_utc'])),
          'evo_status' => $value['status'],
          'evo_result' => $value['result'],
          'time_123betnow' => date('Y-m-d',time()),
          'statistical' => 0,
        ];
      }

    }
    //DB::table('bet_history_evolution')->insert($dataInsert);
    DB::table('show_history_evolution')->insert($dataInsert);
  }
}
