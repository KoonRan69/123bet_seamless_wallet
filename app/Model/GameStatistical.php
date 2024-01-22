<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class GameStatistical extends Model{
    protected $table = "statistical";

    public $timestamps = true;

    const CREATED_AT = 'statistical_Time';
	const UPDATED_AT = 'statistical_UpdateTime';

    protected $primaryKey = 'statistical_ID';
	
	public static function getMaster($userID, $from, $to){
		$package = 0;
		$getInfo = DB::table('statistical')
					->join('users', 'User_ID', 'statistical_User')
					->where('statistical_User', "$userID")
					->where('statistical_Time', '>=', $from)
					->where('statistical_Time', '<', $to)
					->selectRaw('(statistical_TotalBet) as totalBet, User_ID, User_Tree')
					->groupBy('statistical_User')
					->first();
		$sumChildrenBet = 0;
		if(isset($getInfo->totalBet) && $getInfo->totalBet >= 500){
			$sumChildrenBet = DB::table('statistical')
								->join('users', 'User_ID', 'statistical_User')
								->whereRaw("User_Tree Like '%$getInfo->User_ID%'")
								->whereRaw("User_ID != '$getInfo->User_ID'")
								->where('statistical_Time', '>=', $from)
								->where('statistical_Time', '<', $to)
// 								->selectRaw('(statistical_TotalBet) as totalBet, User_ID , User_Email , User_Name, User_Tree, User_Balance')
								->sum('statistical_TotalBet');
			if($sumChildrenBet >= 300000){
				$package = 1;
			}
			if($sumChildrenBet >= 500000){
				$package = 2;
			}
			if($sumChildrenBet >= 1000000){
				$package = 3;
			}
		}
		$data = [
			'master' => $package,
			'sales' => $sumChildrenBet,
		];
		return $package;
	}
}
