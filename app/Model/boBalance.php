<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use App\Model\GameBet;


class boBalance extends Model
{
    protected $table = "boBalance";
    
    protected $fillable = ['id','sub', 'balance', 'oldBalance', 'datetime'];

	public $timestamps = false;
	
	
	public static function getBalance($id){
		$balanceSub = 0;

		$balanceCheck = boBalance::where('sub', $id)->orderBy('id', 'DESC')->first();
		

		if(!$balanceCheck){
			$balanceSub += 0;
			
		}else{
			$bet = GameBet::where('GameBet_SubAccountUser', (int)$id)
							->whereIn('GameBet_Status', [0,1,2,3])
							->where('GameBet_datetime', '>=', $balanceCheck->time)
							->sum('GameBet_AmountWin');

			$balanceSub = $balanceCheck->balance+$bet;
            

		}
		return $balanceSub;
	}
	
	
}
