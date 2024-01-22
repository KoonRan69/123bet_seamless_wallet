<?php

namespace App\Http\Controllers\Provide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use Session;
use App\Exports\ExportAeSexy;
use App\Model\ProBetHistoryAeSexy;
use Carbon\Carbon;
use DB;
use DateTime;

class AWCController extends Controller
{
  public $config;
  //public $is_maintain = 0;

  public function __construct()
  {
    //$this->middleware('auth:api');
    $this->config = config('urlAWC.ae_sexy');
  }

  public function getHistoryAeSexy(Request $request){
    $user = Session::get('user');
    $gameWallet = DB::table('pro_bet_history_ae_sexy')->where('user_parent', $user->User_ID);
    if($request->user_id){
      $gameWallet = $gameWallet->where('pro_bet_history_ae_sexy.userId',$request->user_id);
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('updateTime', '>=', ($request->datefrom.' 00:00:00'))
        ->where('updateTime', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('updateTime', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('updateTime', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {

      $gameWallet = $gameWallet->orderByDesc('id')->get();
      return Excel::download(new ExportAeSexy($gameWallet), 'history-awc-aesexy.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('pro_bet_history_ae_sexy');
    return view('Provide.aesexy', compact('gameWallet', 'columnTable'));
  }
  
  public function saveHistoryAeSexy(Request $request){
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    $now = new \DateTime();
    if($request->time >= 60 || $request->time < 1){
      dd("Time is illegal");
    }
    if($request->time){
      $start  =date(DATE_ISO8601, mktime(date("H") , date("i") - $request->time, date("s"), date("m")  , date("d"), date("Y")));
    }else{
      $start  =date(DATE_ISO8601, mktime(date("H") - 1 , date("i"), date("s"), date("m")  , date("d"), date("Y")));
    }
    $before = new \DateTime($start);

    //dd($now, $before);

    $dateEnd = urlencode($now->format(DateTime::ATOM));
    $dateStart = urlencode($before->format(DateTime::ATOM));
    //dd($dateEnd, $dateStart);
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $this->config['url']."/fetch/gzip/getTransactionByTxTime",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "cert=".$this->config['cert']."&agentId=".$this->config['agentId']."&startTime=".$dateStart."&endTime=".$dateEnd."&platform=SEXYBCRT&currency=CNY",
      CURLOPT_HTTPHEADER => [
        "content-type: application/x-www-form-urlencoded"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    $results = [];
    if ($err) {
      //echo "cURL Error #:" . $err;
      dd('faild');
    }else{
      $data = json_decode($response);
      //dd($data);
      if(empty($data->transactions)){
        dd('empty');
      }
      if($data->status == 1028){
        dd('Unable to proceed. please try again later');
        //return $this->response(200, [], 'Unable to proceed. please try again later', [], false);
      }
      foreach($data->transactions as $item){
        $check = ProUser::where('User_ID',$item->userId)->first();
        $checkExist = ProBetHistoryAeSexy::where('platformTxId', $item->platformTxId)->first();
        //dd($check, $checkExist);
        if($check && !$checkExist){
          //dd($item);
          $timeAE = strtotime($item->updateTime);
          $updateTime = date('Y-m-d H:i:s' , $timeAE);
          $time123bet = date( 'Y-m-d H:i:s' ,strtotime ( '-7 hours' ,  $timeAE  ));
          //dd($updateTime , $time123bet);
          $datas = [
            'gameType' => $item->gameType,
            'winAmount' =>$item->winAmount,
            'settleStatus' => $item->settleStatus,
            'realBetAmount'=> $item->realBetAmount,
            'realWinAmount'=> $item->realWinAmount,
            'txTime'=> $item->txTime,
            'updateTime'=> $updateTime,
            'userId'=> $item->userId,
            'betType'=> $item->betType,
            'platform'=> $item->platform,
            'txStatus'=> $item->txStatus,
            'betAmount'=> $item->betAmount,
            'gameName'=> $item->gameName,
            'platformTxId'=> $item->platformTxId,
            'betTime'=> $item->betTime,
            'gameCode'=> $item->gameCode,
            'currency'=> $item->currency,
            'jackpotBetAmount'=> $item->jackpotBetAmount,
            'jackpotWinAmount'=> $item->jackpotWinAmount,
            'turnover'=> $item->turnover,
            'roundId'=> $item->roundId,
            'gameInfo'=> $item->gameInfo,
            'time123bet' => $time123bet,
          ];
          ProBetHistoryAeSexy::insert($datas);
          //array_push($results, $datas);
        }
      }
      if(empty($results)){
        dd('empty');
        //return $this->response(200, [], 'empty', [], false);
      }
      dd('success');
      //return $this->response(200, [], 'success', [], false);
      //dd('Save History Best startdate: '.$dateStart.' - enddate: '.$now.' Success');
    }
  }

}
