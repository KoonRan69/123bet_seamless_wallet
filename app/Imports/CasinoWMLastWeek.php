<?php

namespace App\Imports;

use App\Model\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class CasinoWMLastWeek implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection (Collection $rows)
    {
        $dataInsert = [];
        $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday last week'));
        $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $deleteOld = DB::table('total_history_wm')->where('DateTime', '>=', $mondayLastWeek)->where('DateTime', '<', $mondayThisWeek)->delete();
      	//dd($deleteOld);
        $getListImported = DB::table('total_history_wm')->where('DateTime', '>=', $mondayThisWeek)->pluck('Username')->toArray();
      	$timeInsert = date('Y-m-d 00:00:00', strtotime('friday last week'));
      	//$getListImported = [];
        foreach ($rows as $row) 
        {
            $username = substr($row['direct_membername'], 3, 6);
          	if($username == 110670){
          	//dd($username, $row['direct_membername'], $row, str_replace(',','',$row['bet_amount']), str_replace(',','',$row['vaild_betvirtual']), str_replace(',','',$row['vaild_betreal']));
            }
            if(array_search($username, $getListImported) !== false){
              	//continue;
            }
            $dataInsert[] = [
              'MemberName' => $row['direct_membername'],
              'Username' => $username,
              'Currency' => $row['currency'],
              'Per' => $row['per'],
              'Amount' => str_replace(',','',$row['bet_amount']),
              'ValidBetVirtual' => str_replace(',','',$row['vaild_betvirtual']),
              'ValidBetReal' => str_replace(',','',$row['vaild_betreal']),
              'WinLoss' => str_replace(',','',$row['win_lose_amount']),
              'RolloverCommission' => str_replace(',','',$row['rollover_commission']),
              'Result' => str_replace(',','',$row['result']),
              'LowerPay' => str_replace(',','',$row['lower_pay']),
              'Upload' => str_replace(',','',$row['upload']),
              'Profit' => str_replace(',','',$row['profit']),
              'DateTime' => $timeInsert,
              'CreatedAt' => date('Y-m-d H:i:s'),
            ];
        }
        DB::table('total_history_wm')->insert($dataInsert);
      
      	//file_get_contents('https://apiv2.123betnow.net/cron/statistical-sa?key=123123123&lastweek=1');
    }
}