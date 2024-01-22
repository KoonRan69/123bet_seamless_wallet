<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class AginSlotLastWeek implements ToCollection, WithHeadingRow
{
  /**
    * @param Collection $collection
    */
  public function collection (Collection $rows)
  {
    date_default_timezone_set("America/Anguilla");
    $dataInsert = [];
    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $deleteOld = DB::table('bet_history_agin_hunterfish')->where('billtime', '>=', $mondayLastWeek)->where('billtime', '<', $mondayThisWeek)->delete();
    foreach ($rows as $key=>$value) 
    {
      if($value['member']){
        $dataInsert[] = [
          'userid' => str_replace('now_', '', $value['member']),
          'username' => $value['member'],
          'productid' => 'JT9',
          'roomid' => $value['roomid'],
          'sceneid' => $value['sceneid'],
          'starttime' => strtotime($value['begin_timeuseast']),
          'endtime' => strtotime($value['end_timeuseast']),
          'billtime' => strtotime($value['end_timeuseast']),
          'gametype' => $value['game_type'],
          'currency' => $value['currency_type'],
          'totalbulletcost' => $value['bullet_cost'],
          'totalfishcost' => $value['prey_earn'],
          'profit' => $value['profit'],
          'totaljpcontribute' => $value['jackpot_comm'],
          'totaljackpot' => $value['jackpot_win'],
          'totalfirstprize' => $value['weapon_winner'],
          'remark' => $value['remark'],
          'devicetype' => $value['beting_side'],
          'totalweaponHit' => $value['collection_prize'],
          'totalcollection' => $value['anchorfish_prize'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['end_timeuseast']))),
        ];
      }
    }
    DB::table('bet_history_agin_hunterfish')->insert($dataInsert);
  }
}
