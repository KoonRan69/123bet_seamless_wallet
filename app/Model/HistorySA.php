<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class HistorySA extends Model{
    protected $table = "historysa";

    public $timestamps = true;


    public static function insertHistory($data){
	    $result = new HistorySA;
	    $result->BetTime = $data->BetTime;
	    $result->PayoutTime = $data->PayoutTime;
	    $result->Username = $data->Username;
        $result->HostID = $data->HostID;
        $result->GameID = $data->GameID;
	    $result->Round = $data->Round;
        $result->Set = $data->Set;
        $result->BetID = $data->BetID;
	    $result->BetAmount = $data->BetAmount;
        $result->Rolling = $data->Rolling;
        $result->ResultAmount = $data->ResultAmount;
	    $result->Balance = $data->Balance;
        $result->GameType = $data->GameType;
        $result->BetType = $data->BetType;
	    $result->BetSource = $data->BetSource;
	    $result->Detail = $data->Detail;
        $result->TransactionID = $data->TransactionID;
        $result->GameResult = $data->GameResult;
        $result->State = $data->State;
        $result->statistical = $data->statistical;
	    $result->save();
        return $result;
    }
    
   
}
