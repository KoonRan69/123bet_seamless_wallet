<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class AeSexyDay implements ToCollection, WithHeadingRow
{
  /**
    * @param Collection $collection
    */
  public function collection (Collection $rows)
  {
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    $dataInsert = [];
    foreach ($rows as $key=>$value) 
    {
      if($value['userId']){
        $dataInsert[] = [
          'gameType' => $value['gameType'],
          'winAmount' => $value['winAmount'],
          'settleStatus'=> $value['settleStatus'],
          'realBetAmount'=> $value['realBetAmount'],
          'realWinAmount'=> $value['realWinAmount'],
          'txTime'=> $value['txTime'],
          'updateTime'=> $value['updateTime'],
          'userId'=> $value['userId'],
          'betType'=> $value['betType'],
          'platform'=> $value['platform'],
          'txStatus'=> $value['txStatus'],
          'betAmount'=> $value['betAmount'],
          'gameName'=> $value['gameName'],
          'platformTxId'=> $value['platformTxId'],
          'betTime'=> $value['betTime'],
          'gameCode'=> $value['gameCode'],
          'currency'=> $value['currency'],
          'jackpotBetAmount'=> $value['jackpotBetAmount'],
          'jackpotWinAmount'=> $value['jackpotWinAmount'],
          'turnover'=> $value['turnover'],
          'roundId'=> $value['roundId'],
          'gameInfo'=> $value['gameInfo'],
          'time123bet' => date('Y-m-d H:i:s', strtotime('-7 hours',strtotime($value['time123bet']))),

        ];
      }
    }
    DB::table('bet_history_ae_sexy')->insert($dataInsert);
  }
}
