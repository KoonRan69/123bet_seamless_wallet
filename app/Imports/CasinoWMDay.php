<?php

namespace App\Imports;

use App\Model\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class CasinoWMDay implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection (Collection $rows)
    {
        $dataInsert = [];
        foreach ($rows as $row)
        {
//            dd($row);
            $dateInsertExcel = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']);
            $timeInsert = date_format($dateInsertExcel, 'Y-m-d H:i:s');
            $username = substr($row['direct_membername'], 3, 6);
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
        DB::table('history_wm')->insert($dataInsert);
//        $listMission = DB::table('mission')->where('status', 1)->select('id', 'name', 'step', 'status', 'description', 'icon', 'unit', 'expired')->get();
        
        //file_get_contents('https://apiv2.123betnow.net/cron/statistical-sa?key=123123123&wm555=1&week=this');
    }
}
