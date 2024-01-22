<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class AginSlot implements ToCollection, WithHeadingRow
{
  /**
    * @param Collection $collection
    */
  public function collection (Collection $rows)
  {
    date_default_timezone_set("America/Anguilla");
    $dataInsert = array();
    $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $deleteOld = DB::table('bet_history_agin_slot')->where('billtime', '>=', $mondayThisWeek)->delete();
    //$timeInsert = date('Y-m-d H:i:s');
    foreach ($rows as $key=>$value) 
    {
      if($value['member']){
        $dataInsert[] = [
          'userid' => str_replace('now_', '', $value['member']),
          'username' => $value['member'],
          'billno' => $value['bill_no'],
          'productid' => 'JT9',
          'billtime' => $value['bet_timeuseast'],
          'reckontime' => $value['bet_timeuseast'],
          'slottype' => $value['slot_type'],
          'currency' => $value['currency_type'],
          'gametype' => $value['game_type'],
          'betIP' => $value['beting_ip'],
          'account' => $value['bet_amt'],
          'cus_account' => $value['gross_win'],
          'valid_account' => $value['effebet'],
          'account_base' => $value['effebet'],
          'account_bonus' => 0,
          'cus_account_base' => $value['gross_win'],
          'cus_account_bonus' => 0,
          'flag' => 1,
          'platformtype' => 'SLOT',
          'devicetype' => $value['beting_side'],
          'mainbillno' => $value['main_bill_no'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['bet_timeuseast']))),
        ];
      }

    }
    DB::table('bet_history_agin_slot')->insert($dataInsert);
  }
}
