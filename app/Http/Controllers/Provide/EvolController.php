<?php

namespace App\Http\Controllers\Provide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Excel;
use App\Exports\ExportEvolution;
use App\Model\LogUser;
use App\Model\ProBetHistoryEvo;
use App\Model\ProUser;

class EvolController extends Controller
{
  public $config;
  public $password_agin = 'KoonRan69';
  public $api_host;
  public function __construct()
  {
    //$this->middleware('auth:api', ['except' => ['saveHistoryBest']]);
    $this->config = config('urlAgin.agin_api');
    $this->api_host = "https://api.luckylivegames.com";
    $this->casinokey = "1gvsw90kwuok5zqs";
    $this->apitoken = "15a59174850db01115f28c0bd1705230";
    $this->currency = "CNY";
  }
  public function getHistoryEvol(Request $request){
    $user = Session::get('user');
    $gameWallet = DB::table('pro_bet_history_evo')->where('user_parent', $user->User_ID);
    if($request->user_id){
      $gameWallet = $gameWallet->where('pro_bet_history_evo.user_id',$request->user_id);
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'))
        ->where('created_at', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {

      $gameWallet = $gameWallet->orderByDesc('id')->get();
      return Excel::download(new ExportEvolution($gameWallet), 'history-evolution.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('pro_bet_history_evo');
    //dd($gameWallet,$columnTable);
    return view('Provide.evol', compact('gameWallet', 'columnTable'));
  }

  public function saveHistoryEvol(Request $req){
    //$Provide_Key_API = $req->key_api;
    //$user_pro = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $list_data = []; 
    date_default_timezone_set('UTC');
    $startDate = strtotime('-5 minutes');
    $endDate = strtotime('now');

    if($req->runtime == 10){
      $startDate = strtotime('-10 minutes');
    }
    if($req->runtime == 20){
      $startDate = strtotime('-20 minutes');
      $endDate = strtotime('-9 minutes');
    }
    if($req->runtime == 30){
      $startDate = strtotime('-30 minutes');
      $endDate = strtotime('-19 minutes');
    }
    if($req->runtime == 60){
      $startDate = strtotime('-60 minutes');
      $endDate = strtotime('-29 minutes');
    }

    $startDate = date("Y-m-d H:i:s", $startDate);
    $endDate = date("Y-m-d H:i:s", $endDate);

    $login = $this->casinokey;
    $password = $this->apitoken;

    //$url = "https://admin.luckylivegames.com/api/gamehistory/v1/casino/games?startDate=2022-06-08 04:9:05&endDate=2022-06-08 05:15:05";
    $url = "https://admin.luckylivegames.com/api/gamehistory/v1/casino/games?startDate=" . $startDate . "&endDate=" . $endDate . "";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
    $data = curl_exec($ch);
    //print_r($data);
    $data = json_decode($data,TRUE);
    if(count($data['data']) > 0){
      for($i=0;$i < count($data['data'][0]['games']);$i++)
      {
        for($z=0;$z < count($data['data'][0]['games'][$i]['participants']);$z++)
        {
          for($y=0;$y < count($data['data'][0]['games'][$i]['participants'][$z]['bets']);$y++)
          {
            $username=$data['data'][0]['games'][$i]['participants'][$z]['playerId'];
            $suboper=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['code'];
            $betmoney=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['stake'];
            $awardmoney=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['payout'];
            $bettime=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['placedOn'];
            $roundid=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['transactionId'];
            $game_name=$data['data'][0]['games'][$i]['gameType'];
            $orderid=$data['data'][0]['games'][$i]['id'];
            $betresult=$data['data'][0]['games'][$i]['status'];
            $timestring = strtotime($bettime);
            /*echo "username : $username\n";
          echo "suboper : $suboper\n";
          echo "betmoney : $betmoney\n";
          echo "awardmoney : $awardmoney\n";
          echo "bettime : $bettime\n";
          echo "roundid : $roundid\n";
          echo "game_name : $game_name\n";
          echo "orderid : $orderid\n";
          echo "betresult : $betresult\n";
          echo "timestring : $timestring\n";
          echo "-------------------------------------------\n";*/



            $checkid = substr($username, 0, 3) ;
            if($checkid == 'NOW' || $checkid == 'now'){
              $checkhistory = ProBetHistoryEvo::where('orderid',$orderid)->first() ; 
              $id = explode("_", $username);
              $checkuser = ProUser::where('User_Evo',$username)->first();

              if(!$checkhistory && $checkuser){
                $bethistory = new ProBetHistoryEvo() ; 
                $bethistory->username = $username;
                $bethistory->user_id = $id[1];
                $bethistory->suboper = $suboper;
                $bethistory->betmoney = $betmoney;
                $bethistory->awardmoney	= $awardmoney;
                $bethistory->roundid = $roundid ; 
                $bethistory->orderid = $orderid ; 
                $bethistory->betresult = $betresult ;
                $bethistory->bettime = $bettime ;
                $bethistory->timestring = $timestring ;
                $bethistory->game_name = $game_name ;
                $bethistory->user_parent = $user_pro->User_ID;
                //array_push( $list_data, $bethistory);
                $bethistory->save() ; 

              }
            }

            //BetHistoryEvo
          }
        }
      }
    }
    //return $this->response(200,$list_data , "'Save History Best startdate: '.$startDate.' - enddate: today Success'", [], false);
    //echo $list_data;
    dd('Save History Best startdate: '.$startDate.' - enddate: today Success');
  }

}
