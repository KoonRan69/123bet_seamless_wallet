<?php

namespace App\Imports;

use App\Model\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class SportBook  implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection (Collection $rows)
    {
      	$rows = $rows->toArray();
        $dataInsert = [];
        $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
        //$getListImported = DB::table('sportbook_history')->where('created_at', '>=', $mondayThisWeek)->pluck('bet')->toArray();
      	//dd($getListImported);
      	$upline = 'now';
        foreach ($rows as $row) 
        {
          	$checkAccount = strpos($row['account'], $upline);
          	if($checkAccount === false){
              	continue;
            }
          	$bet = $row['bet'];
            //if(array_search($bet, $getListImported) !== false){
            //  	continue;
            //}
          	$row['account'] = str_replace($upline, '', $row['account']);
          	$row['created_at'] = date('Y-m-d H:i:s');
          	$row['updated_at'] = date('Y-m-d H:i:s');
          	$row['statistical'] = 0;
            $dataInsert[] = $row;
        }
      	//dd($dataInsert);
        DB::table('sportbook_history')->insert($dataInsert);
    }
}