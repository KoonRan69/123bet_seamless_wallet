<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class AginSportBook implements ToCollection, WithHeadingRow
{
  /**
    * @param Collection $collection
    */
  public function collection (Collection $rows)
  {
    date_default_timezone_set("America/Anguilla");
    $dataInsert = array();
    $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $deleteOld = DB::table('bet_history_agin')->where('billtime', '>=', $mondayThisWeek)->delete();
    //$timeInsert = date('Y-m-d H:i:s');
    foreach ($rows as $key=>$value) 
    {
      if($value['status'] === 'unsettled'){
        $flag = 0;
      }else if($value['status'] === 'settled'){
        $flag = 1;
      }else if($value['status'] === 'CancelOrder'){
        $flag = -8;
      }
      if($value['member']){
        $dataInsert[] = [
          'userid' => str_replace('now_', '', $value['member']),
          'username' => $value['member'],
          'productid' => $value['agent'],
          'billno' => $value['bill_no'],
          'extbillno' => $value['extbill_no'],
          'thirdbillno' => $value['thirdbill_no'],
          'billtime' => $value['bet_time'],
          'currency' => $value['currency'],
          'betIP' => $value['beting_ip'],
          'account' => $value['bet_amt'],
          'cus_account' => $value['gross_win'],
          'valid_account' => $value['effebet'],
          'flag' => $flag,
          'odds' => $value['odds'],
          'bettype' => $value['bet_type'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['bet_time']))),
          'reckontime' => $value['reckontime'],
          'simplified_result' => $value['event'],
        ];
      }

    }
    DB::table('bet_history_agin')->insert($dataInsert);
  }
}
