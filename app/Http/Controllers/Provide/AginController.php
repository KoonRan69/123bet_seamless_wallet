<?php

namespace App\Http\Controllers\Provide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use Session;
use DB;
use App\Exports\ExportAginSportBook;
use App\Exports\ExportAginSlot;
use App\Exports\ExportAginHunterFish;
use App\Model\LogUser;
use App\Model\ProUser;
use App\Model\ProBetHistoryAgin;
use App\Model\ProBetHistoryAginSlot;
use App\Model\ProBetHistoryAginHunterFish;

class AginController extends Controller
{
  public $config;
  public $password_agin = 'KoonRan69';
  public function __construct()
  {
    //$this->middleware('auth:api', ['except' => ['saveHistoryBest']]);
    $this->config = config('urlAgin.agin_api');

  }
  //lưu lịch sử spotbook
  public function saveHistoryAginSport(){
    //////////////////////////// GET WIN LOST //////////////////////////////////
    date_default_timezone_set("America/Anguilla");
    $cagent = 'JT9'; 
    $t =time(); //2022-02-14 05:55:08
    //dd(date('Y-m-d H:i:s'));
    $t1 = strtotime('-1 minutes',$t); 
    $t2 = strtotime('-10 minutes',$t1); 
    $startdate = date('Y-m-d H:i:s',$t2); 
    $enddate = date('Y-m-d H:i:s',$t1); 

    $key = md5($cagent.$startdate.$enddate.'6377A2D3DC3F79BFA3684DC886F28365');
    $url = 'http://jde6t9.gdcapi.com:3333/getagsportorders_ex.xml?startdate='.$startdate.'&enddate='.$enddate.'&cagent='.$cagent.'&key='.$key;
    //$url = 'http://jde6t9.gdcapi.com:3333/getorders.xml?cagent='.$cagent.'&startdate='.$startdate.'&enddate='.$enddate.'&key='.$key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    $xml = simplexml_load_string($data);
    $betHistory = ProBetHistoryAgin::where('create_date', '>=', strtotime('-1 days'))->pluck('billno')->toArray();
    $results = [];
    foreach($xml->row as $value){
      $checkUser = ProUser::where('User_Agin','=', $value['username'])->first();
      if ( !in_array($value['billno'], $betHistory) && $checkUser) {
        $results[] = [
          'userid' => str_replace('now_', '', $value['username']),
          'user_parent' => $user_pro->User_ID,
          'username' => $value['username'],
          'billno' => $value['billno'],
          'productid' => $value['productid'],
          'billtime' => $value['billtime'],
          'currency' => $value['currency'],
          'gametype' => $value['gametype'],
          'betIP' => $value['betIP'],
          'account' => $value['account'],
          'cus_account' => $value['cus_account'],
          'valid_account' => $value['valid_account'],
          'flag' => $value['flag'],
          'platformtype' => $value['platformtype'],
          'odds' => $value['odds'],
          'sport' => $value['sport'],
          'category' => $value['category'],
          'extbillno' => $value['extbillno'],
          'thirdbillno' => $value['thirdbillno'],
          'bettype' => $value['bettype'],
          'system' => $value['system'],
          'live' => $value['live'],
          'current_score' => $value['current_score'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['billtime']))),
          'reckontime' => $value['reckontime'],
          'competition' => $value['competition'],
          'market' => $value['market'],
          'selection' => $value['selection'],
          'simplified_result' => $value['simplified_result'],
        ];

        $checkUser = NULL;
      }
      /* else{
        $betUpdateStatus = BetHistoryAgin::where('billno',$value['billno'])->first();
        BetHistoryAgin::where('billno',$value['billno'])->update([
          'productid' => $value['productid'],
          'billtime' => $value['billtime'],
          'currency' => $value['currency'],
          'gametype' => $value['gametype'],
          'betIP' => $value['betIP'],
          'account' => $value['account'],
          'cus_account' => $value['cus_account'],
          'valid_account' => $value['valid_account'],
          'flag' => $value['flag'],
          'platformtype' => $value['platformtype'],
          'odds' => $value['odds'],
          'sport' => $value['sport'],
          'category' => $value['category'],
          'extbillno' => $value['extbillno'],
          'thirdbillno' => $value['thirdbillno'],
          'bettype' => $value['bettype'],
          'system' => $value['system'],
          'live' => $value['live'],
          'current_score' => $value['current_score'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['billtime']))),
          'reckontime' => $value['reckontime'],
          'competition' => $value['competition'],
          'market' => $value['market'],
          'selection' => $value['selection'],
          'simplified_result' => $value['simplified_result'],
        ]);
        /*if($betUpdateStatus->simplified_result == null){
          BetHistoryAgin::where('billno',$value['billno'])->update(['reckontime' => $value['reckontime'],'simplified_result'=>$value['simplified_result']]);
        }
      }*/
    }
    if (count($results) > 0) ProBetHistoryAgin::insert($results);
    return $this->response(200,$results , "'Save History Best startdate: '.$startdate.' - enddate: '.$enddate.' Success'", [], true);    
    //dd('Save History Best startdate: '.$startdate.' - enddate: '.$enddate.' Success');

  }
  //lưu lịch sử game slot
  public function saveHistoryAginSlot(){
    //////////////////////////// GET WIN LOST //////////////////////////////////
    date_default_timezone_set("America/Anguilla");
    $cagent = 'JT9'; 
    $t =time(); //strtotime('2022-04-18 03:05:55'); 
    //dd(date('Y-m-d H:i:s'));
    $t1 = strtotime('-1 minutes',$t); 
    $t2 = strtotime('-10 minutes',$t1); 
    $startdate = date('Y-m-d H:i:s',$t2); 
    $enddate = date('Y-m-d H:i:s',$t1); 

    $key = md5($cagent.$startdate.$enddate.'6377A2D3DC3F79BFA3684DC886F28365');
    $url = 'http://jde6t9.gdcapi.com:3333/getslotorders_ex.xml?startdate='.$startdate.'&enddate='.$enddate.'&cagent='.$cagent.'&key='.$key;
    //$url = 'http://jde6t9.gdcapi.com:3333/getorders.xml?cagent='.$cagent.'&startdate='.$startdate.'&enddate='.$enddate.'&key='.$key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    $xml = simplexml_load_string($data);
    $betHistory = ProBetHistoryAginSlot::where('create_date', '>=', strtotime('-1 days'))->pluck('billno')->toArray();
    $results = [];
   
    foreach($xml->row as $value){
      $checkUser = ProUser::where('User_Agin','=', $value['username'])->first();
      if ( !in_array($value['billno'], $betHistory) && $checkUser) {
        $results[] = [
          'userid' => str_replace('now_', '', $value['username']),
          'user_parent' => $user_pro->User_ID,
          'username' => $value['username'],
          'billno' => $value['billno'],
          'productid' => $value['productid'],
          'billtime' => $value['billtime'],
          'reckontime' => $value['reckontime'],
          'slottype' => $value['slottype'],
          'currency' => $value['currency'],
          'gametype' => $value['gametype'],
          'betIP' => $value['betIP'],
          'account' => $value['account'],
          'cus_account' => $value['cus_account'],
          'valid_account' => $value['valid_account'],
          'account_base' => $value['account_base'],
          'account_bonus' => $value['account_bonus'],
          'cus_account_base' => $value['cus_account_base'],
          'cus_account_bonus' => $value['cus_account_bonus'],
          'src_amount' => $value['src_amount'],
          'dst_amount' => $value['dst_amount'],
          'flag' => $value['flag'],
          'platformtype' => $value['platformtype'],
          'devicetype' => $value['devicetype'],
          'exttxid' => $value['exttxid'],
          'mainbillno' => $value['mainbillno'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['billtime']))),
        ];
        $checkUser = NULL;
      }
      /*else{
        $betUpdateStatus = BetHistoryAginSlot::where('billno',$value['billno'])->first();
        BetHistoryAginSlot::where('billno',$value['billno'])->update([
          'productid' => $value['productid'],
          'billtime' => $value['billtime'],
          'reckontime' => $value['reckontime'],
          'slottype' => $value['slottype'],
          'currency' => $value['currency'],
          'gametype' => $value['gametype'],
          'betIP' => $value['betIP'],
          'account' => $value['account'],
          'cus_account' => $value['cus_account'],
          'valid_account' => $value['valid_account'],
          'account_base' => $value['account_base'],
          'account_bonus' => $value['account_bonus'],
          'cus_account_base' => $value['cus_account_base'],
          'cus_account_bonus' => $value['cus_account_bonus'],
          'src_amount' => $value['src_amount'],
          'dst_amount' => $value['dst_amount'],
          'flag' => $value['flag'],
          'platformtype' => $value['platformtype'],
          'devicetype' => $value['devicetype'],
          'exttxid' => $value['exttxid'],
          'mainbillno' => $value['mainbillno'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['billtime']))),
        ]);
      }*/
    }
    if (count($results) > 0) ProBetHistoryAginSlot::insert($results);
    return $this->response(200,$results , "'Save History Best startdate: '.$startdate.' - enddate: '.$enddate.' Success'", [], true);    
  }
  //lưu lịch sử game bắn cá (hunter)
  public function saveHistoryAginFish(){
    //////////////////////////// GET WIN LOST //////////////////////////////////
    date_default_timezone_set("America/Anguilla");
    $cagent = 'JT9'; 
    $t = time(); //strtotime('2022-05-03 04:20:00'); 2022-05-03 21:54:44
    //dd(date('Y-m-d H:i:s'));
    $t1 = strtotime('-1 minutes',$t); 
    $t2 = strtotime('-10 minutes',$t1); 
    $startdate = strtotime(date('Y-m-d H:i:s',$t2)); 
    $enddate = strtotime(date('Y-m-d H:i:s',$t1)); 

    $key = md5($cagent.$startdate.$enddate.'6377A2D3DC3F79BFA3684DC886F28365');
    $url = 'http://jde6t9.gdcapi.com:3333/gethunterscene.xml?startdate='.$startdate.'&enddate='.$enddate.'&cagent='.$cagent.'&key='.$key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    $xml = simplexml_load_string($data);
    $betHistory = ProBetHistoryAginHunterFish::where('create_date', '>=', strtotime('-1 days'))->pluck('sceneid')->toArray();
    
    $results = [];
    foreach($xml->row as $value){
      $checkUser = ProUser::where('User_Agin','=', $value['username'])->first();
      if ( !in_array($value['sceneid'], $betHistory) && $checkUser) {
        $results[] = [
          'userid' => str_replace('now_', '', $value['username']),
          'user_parent' => $user_pro->User_ID,
          'username' => $value['username'],
          'productid' => $value['productid'],
          'roomid' => $value['roomid'],
          'betx' => $value['betx'],
          'sceneid' => $value['sceneid'],
          'starttime' => $value['starttime'],
          'endtime' => $value['endtime'],
          'billtime' => $value['billtime'],
          'gametype' => $value['gametype'],
          'currency' => $value['currency'],
          'totalbulletcost' => $value['totalbulletcost'],
          'totalfishcost' => $value['totalfishcost'],
          'profit' => $value['profit'],
          'totaljpcontribute' => $value['totaljpcontribute'],
          'totaljackpot' => $value['totaljackpot'],
          'totalfirstprize' => $value['totalfirstprize'],
          'remark' => $value['remark'],
          'devicetype' => $value['devicetype'],
          'totalweaponHit' => $value['totalweaponHit'],
          'totalcollection' => $value['totalcollection'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',$value['billtime']*1)),
        ];
        $checkUser = NULL;
      }
      /*else{
        $betUpdateStatus = BetHistoryAginHunterFish::where('sceneid',$value['sceneid'])->first();
        BetHistoryAginHunterFish::where('sceneid',$value['sceneid'])->update([
          'productid' => $value['productid'],
          'roomid' => $value['roomid'],
          'betx' => $value['betx'],
          'starttime' => $value['starttime'],
          'endtime' => $value['endtime'],
          'billtime' => $value['billtime'],
          'gametype' => $value['gametype'],
          'currency' => $value['currency'],
          'totalbulletcost' => $value['totalbulletcost'],
          'totalfishcost' => $value['totalfishcost'],
          'profit' => $value['profit'],
          'totaljpcontribute' => $value['totaljpcontribute'],
          'totaljackpot' => $value['totaljackpot'],
          'totalfirstprize' => $value['totalfirstprize'],
          'remark' => $value['remark'],
          'devicetype' => $value['devicetype'],
          'totalweaponHit' => $value['totalweaponHit'],
          'totalcollection' => $value['totalcollection'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',$value['billtime']*1)),
        ]);
      }*/
    }
    if (count($results) > 0) ProBetHistoryAginHunterFish::insert($results);
    return $this->response(200,$results , "'Save History Best startdate: '.$startdate.' - enddate: '.$enddate.' Success'", [], true);    
    //dd('Save History Best Hunter Fish startdate: '.$startdate.' - enddate: '.$enddate.' Success');
  }

  public function getHistoryAginSport(Request $request){
    $user = Session::get('user');
    $gameWallet = DB::table('pro_bet_history_agin')->where('user_parent', $user->User_ID);
    if($request->user_id){
      $gameWallet = $gameWallet->where('pro_bet_history_agin.userid',$request->user_id);
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'))
        ->where('create_date', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {

      $gameWallet = $gameWallet->orderByDesc('id')->get();
      return Excel::download(new ExportAginSportBook($gameWallet), 'history-agin-sport-book.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('pro_bet_history_agin');
    //dd($gameWallet,$columnTable);
    return view('Provide.aginsport', compact('gameWallet', 'columnTable'));
  }

  public function getHistoryAginFish(Request $request){
    $user = Session::get('user');
    $gameWallet = DB::table('pro_bet_history_agin_hunterfish')->where('user_parent', $user->User_ID);
    if($request->user_id){
      $gameWallet = $gameWallet->where('pro_bet_history_agin_hunterfish.userid',$request->user_id);
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'))
        ->where('create_date', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {

      $gameWallet = $gameWallet->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportAginHunterFish($gameWallet), 'history-agin-hunterfish.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('pro_bet_history_agin_hunterfish');
    return view('Provide.aginfish', compact('gameWallet', 'columnTable'));
  }

  public function getHistoryAginSlot(Request $request){
    $user = Session::get('user');
    $gameWallet = DB::table('pro_bet_history_agin_slot')->where('user_parent', $user->User_ID);
    if($request->user_id){
      $gameWallet = $gameWallet->where('pro_bet_history_agin_slot.userid',$request->user_id);
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'))
        ->where('create_date', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {

      $gameWallet = $gameWallet->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportAginSlot($gameWallet), 'history-agin-slot.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('pro_bet_history_agin_slot');
    return view('Provide.aginslot', compact('gameWallet', 'columnTable'));
  }



}
