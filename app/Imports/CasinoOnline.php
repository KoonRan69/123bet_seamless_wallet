<?php

namespace App\Imports;

use App\Model\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class CasinoOnline implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection (Collection $rows)
    {
        $dataInsert = [];
        $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $deleteOld = DB::table('totalhistorysa')->where('CreatedAt', '>=', $mondayThisWeek)->delete();
        $getListImported = DB::table('totalhistorysa')->where('CreatedAt', '>=', $mondayThisWeek)->pluck('Username')->toArray();
      	//$getListImported = [];
        foreach ($rows as $row) 
        {
          	//dd($rows, $row);
            $username = str_replace('@ebp', '', $row['username']);
            $username = str_replace('NOW', '', $username);
            if(array_search($username, $getListImported) !== false){
              	//continue;
            }
            $dataInsert[] = [
              'Level' => $row['level'],
              'Username' => $username,
              'Currency' => $row['currency'],
              'NumberOfTransactions' => $row['number_of_transactions'],
              'BetAmount' => $row['bet_amount'],
              'WinLoss' => $row['winloss'],
              'RollingAmount' => $row['rolling_amount'],
              'RollingRate' => $row['rolling_rate'],
              'RollingCommission' => $row['rolling_commission'],
              'MemberTotal' => $row['member_total'],
              'CreatedAt' => date('Y-m-d H:i:s'),
              'UpdatedAt' => date('Y-m-d H:i:s'),
            ];
        }
        DB::table('totalhistorysa')->insert($dataInsert);
      
      	file_get_contents('https://apiv2.123betnow.net/cron/statistical-sa?key=123123123&lastweek=1');
    }
}