<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class SbobetThisWeek implements ToCollection, WithHeadingRow
{
  /**
    * @param Collection $collection
    */
  public function collection (Collection $rows)
  {
    $dataInsert = array();
    $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $deleteOld = DB::table('bet_history_sbobet_ib')->where('time_123betnow', '>=', $mondayThisWeek)->delete();
    //$timeInsert = date('Y-m-d H:i:s');
    $time = time();
    foreach ($rows as $key=>$value) 
    {
      if($value['ten_nguoi_dung']){
        $dataInsert[] = [
          'username' => $value['ten_nguoi_dung'],
          'userid' => str_replace('now_', '', $value['ten_nguoi_dung']),
          'turnover_by_stake' => $value['turnover_by_stake'],
          'net_turnover_by_stake' => $value['net_turnover_by_stake'],
          'turnover_by_actual_stake' => $value['turnover_by_actual_stake'],
          'net_turnover_by_actual_stake' => $value['net_turnover_by_actual_stake'],
          'currency' => $value['tien_te'],
          'number_of_bets' => $value['so_cuoc'],
          'member_wins' => $value['thanh_vien_thang'],
          'company' => $value['cong_ty'],
          'sgd_company' => $value['sgd_cong_ty'],
          'time_123betnow' => date('Y-m-d H:i:s',$time),
          'statistical' => 0,
        ];
      }

    }
    DB::table('bet_history_sbobet_ib')->insert($dataInsert);
  }
}
