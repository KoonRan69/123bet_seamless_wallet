<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Money;
use App\Model\User;
use App\Model\Investment;
use App\Model\Stringsession;
use GuzzleHttp\Client;
use Session;
use DB;

class DashboardController extends Controller
{
	public $urlAPI = 'https://sonicxgame.com/api/v1/';
	public $ip = '206.189.46.10';
	public $key = 'YUJFbnZBpu6jDr0vQklf56Dfy7Q9MRIO';
	public $pwd = 'DsWabOvzAZ';
	public $acccount = 'DAFCOORG';
	
    public function getDashboard(){
	    
        $RandomToken = Money::RandomToken();
        $user = Session('user');
        
		$balance = User::getBalance(Session('user')->User_ID);
		// dd($balance);
        $total_interest = Money::where('Money_User', Session('user')->User_ID)->where('Money_MoneyAction', 4)->where('Money_Confirm', 0)->sum('Money_USDT');
        $history_invest = Investment::join('currency', 'Currency_ID' ,'investment_Currency')->where('investment_User', $user->User_ID )->where('investment_Status','<>', -1)->orderBy('investment_ID', 'DESC')->paginate(10);
		
		$total['Commission'] = Money::where('Money_User', $user->User_ID)->where('Money_MoneyStatus', 1)->whereIn('Money_MoneyAction', [5,6, 19,20])->sum(DB::raw('Money_USDT'));
		$total_invest = Investment::where('investment_User', Session('user')->User_ID)->sum(DB::raw('investment_Amount'));
		$percent_maxout = 3;
		$total['Maxout'] = $total_invest * $percent_maxout - $total['Commission'];
		$chartMaxout =[
			['value'=>$total['Commission'], 'name'=>'Commission'], 
			['value'=>$total['Maxout'], 'name'=>'Max Out'],
					  ];
					//   dd($chartMaxout[1]['value']);
		return view('System.Dashboard.Index', compact('total_interest','balance', 'history_invest', 'RandomToken','user', 'chartMaxout', 'total_invest'));
    }
    
    public static function getGame($id){
	    $game = DB::table('sox_game')->where('_id', $id)->first();
	    $user = Session('user');
	    $LOGINID = $user->User_Name;
	    $PASSWORD = $user->User_PasswordNotHash;
	    if($id == 'sportsbook'){
		    abort(404);
		    $SYSTEM = 'alt_sportsbook';
			$PAGE = 'Lobby';
	    }elseif($id == 'lottery'){
		    abort(404);
		    $SYSTEM = 'alt_lottery';
			$PAGE = 'Lobby';
	    }else{
		    $SYSTEM = $game->System;
			$PAGE = $game->ID;
	    }
	    $TID = uniqid();
	    $pwd = config('sonix.pwd');
	    $ip = config('sonix.ip');
	    $key = config('sonix.key');
		$urlAPI = config('sonix.urlAPI');
	    $hash = md5('Game/Auth/'.$ip.'/'.$TID.'/'.$key.'/'.$LOGINID.'/'.$PASSWORD.'/'.$SYSTEM.'/'.$pwd);
		
	    $api = $urlAPI.'game/auth/'.$key.'/?tid='.$TID.'&login='.$LOGINID.'&password='.$PASSWORD.'&system='.$SYSTEM.'&page='.$PAGE.'&ip='.$ip.'&hash='.$hash;
	    
	    $client = new \GuzzleHttp\Client();
		$response = $client->request('GET', $api);
		$link = str_replace('1,', null, $response->getBody(true)->getContents());




		return view('System.Game.Index', compact('link'));

    }
    
}
