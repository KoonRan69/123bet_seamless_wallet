<?php

namespace App\Http\Controllers\APIProvide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Validator;
use App\Model\User;
use App\Model\ProUser;
use App\Model\Money;
use App\Model\ProMoney;
use App\Model\LogUser;
use App\Model\ProBetHistoryAgin;
use App\Model\ProBetHistoryAginSlot;
use App\Model\ProBetHistoryAginHunterFish;
//use App\Model\BetHistoryAgin;
use Carbon\Carbon;
use DB;

class AginProvideController extends Controller
{
  public $config;
  public $password_agin = 'KoonRan69';
  public function __construct()
  {
    //$this->middleware('auth:api', ['except' => ['saveHistoryBest']]);
    $this->config = config('urlAgin.agin_api');

  }

  public function listHistoryBestSlot(Request $request){
    $Provide_Key_API = $request->key_api;
    $user = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $betHistory = ProBetHistoryAginSlot::where('user_parent', $user->User_ID)->orderBy('create_date', 'DESC')->paginate(50);
    return $this->response(200,$betHistory);
  }

  public function listHistoryBestSport(Request $request){
    $Provide_Key_API = $request->key_api;
    $user = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $betHistory = ProBetHistoryAgin::where('user_parent', $user->User_ID)->orderBy('create_date', 'DESC')->paginate(50);
    return $this->response(200,$betHistory);
  }


  public function listHistoryBestHunter(Request $request){
    $Provide_Key_API = $request->key_api;
    $user = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $betHistory = ProBetHistoryAginHunterFish::where('user_parent', $user->User_ID)->orderBy('create_date', 'DESC')->paginate(50);
    return $this->response(200,$betHistory);
  }


  //lưu lịch sử spotbook
  public function saveHistoryBest(){
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
    $Provide_Key_API = $req->key_api;
    $user_pro = User::where('Provide_Key_API', $Provide_Key_API)->first();
    foreach($xml->row as $value){
      $checkUser = ProUser::where([
        ['User_Agin','=', $value['username'] ],
        ['User_Provide', '=', $user_pro->User_ID],
      ])->first();
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
  public function saveHistoryBestSlot(){
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
    $Provide_Key_API = $req->key_api;
    $user_pro = User::where('Provide_Key_API', $Provide_Key_API)->first();
    foreach($xml->row as $value){
      $checkUser = ProUser::where([
        ['User_Agin','=', $value['username'] ],
        ['User_Provide', '=', $user_pro->User_ID],
      ])->first();
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
  public function saveHistoryBestHunter(){
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
    $Provide_Key_API = $req->key_api;
    $user_pro = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $results = [];
    foreach($xml->row as $value){
      $checkUser = ProUser::where([
        ['User_Agin','=', $value['username'] ],
        ['User_Provide', '=', $user_pro->User_ID],
      ])->first();
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

  public function register(Request $request){
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = ProUser::where('User_Email', $request->email )->first();
    if($user){
      return $this->response(200, [], 'Email already exist', [], false);
    }
    $Provide_Key_API = $request->key_api;
    $user_pro = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $data= [
      'User_ID' => $this->RandomIDUser(),
      'User_Provide' => $user_pro->User_ID,
      'User_Email' => $request->email,
    ];

    ProUser::insert($data);
    return $this->response(200, ['User_ID'=>$data['User_ID'] ], 'Registed success', [], true);
  }

  public function login(Request $request){
    $validator = Validator::make($request->all(), [
      'user_agin' => 'required',
      'password' => 'required|min:6|max:12',
    ], [
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'password.required' =>  trans('notification.password_required'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = ProUser::where('User_Agin', $request->user_agin )->first();
    if(!$user){
      return $this->response(200, [], trans('notification.you_have_not_registered_an_account_yet'), [], false);
    }
    if($request->password != $user->User_Agin_Password){
      return $this->response(200, [], trans('notification.Incorrect_password'), [], false);
    }
    $sidag = $this->generateRandomString();
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }
    $gameType="TASSPTA";//"TASSPTA"
    if($request->gameType){
      $gameType = $request->gameType;
    }
    $params = array( 
      "cagent"    => $this->config['cagent'],
      "loginname" => $this->config['prefix'].$user->User_ID,
      "actype"   => "1",	
      "password"  =>  $this->password_agin,  // 
      "cur"       => $this->config['currency'],
      "dm"  => "123betnow.net",
      "sid"  => $sidag,
      "lang"  => "3",
      "gameType"  => $gameType,
      "oddtype"  => "A",
    );
    $paramStr =  $this->encrypt($this->config['des_key'],$params); 
    $key = md5($paramStr. $this->config['md5key']); 
    $paramStr = urlencode($paramStr); 
    $urllogin = "https://gci.123betnow.net/forwardGame.do?params=".$paramStr."&key=".$key;
    return $this->response(200, $urllogin, trans('notification.login_success'), [], true);
  }

  public function CreateMember(Request $request){
    $Provide_Key_API = $request->key_api;
    $user = User::where('Provide_Key_API', $Provide_Key_API)->first();
    //dd('createMember:'.$user_parent->User_ID);
    //$user = User::where()->first();
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|max:255',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'password_confirm' => 'required|same:password|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ], [
      'password.min' => 'password minimum 6 characters',
      'password.max' => 'password up to 12 characters ',
      'password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password_confirm.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password_confirm.min' => trans('notification.password_minimum_6_characters'),
      'password_confirm.max' => trans('notification.password_up_to_12_characters '),
      'password_confirm.same' => trans('notification.Confirm_password_is_not_the_same_as_password'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    //////////////////////////// CREAT AG ACCOUNT //////////////////////////////////
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }

    $user_pro = ProUser::where('User_Email', $request->email )->first();
    if($user_pro && ($user_pro->User_Agin_Password != NULL) ){
      return $this->response(200, [], [], 'Account already exists' , false);
    }

    //has user, not registed Agin
    if($user_pro && ($user_pro->User_Agin_Password == NULL) ){
      $params = array(
        'cagent' =>$this->config['cagent'],
        'loginname' =>$this->config['prefix']. $user_pro->User_ID,
        'password' => $this->password_agin,  //
        'actype' => '1', // 1 là tài khoản thực, 0 là tài khoản dùng thử
        'oddtype' => 'A',
        'cur' => $this->config['currency'],
        'method' => 'lg'
      );
      $paramStr =  $this->encrypt($this->config['des_key'], $params);
      $key = md5($paramStr . $this->config['md5key']);
      $paramStr = urlencode($paramStr);
      $url = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStr . '&key=' . $key;
      $xml = simplexml_load_file($url);
      $input = json_encode($xml);
      $data = json_decode($input);
      foreach($data as $value){
        $info_value = $value->{'info'};
        $info_msg = $value->{'msg'};
      }

      if($info_value == 0){
        $data = [
          'User_Agin' => $this->config['prefix'].$user_pro->User_ID,
          'User_Agin_Password' => $request->password,
        ];
        ProUser::where('User_Email', $request->email)->update($data);
        $list = [
          'User_Agin' => $this->config['prefix'].$user_pro->User_ID,
        ];
        return $this->response(200, $list, trans('notification.register_success'), [], true);
      }
    }

    //Is not registed
    $d['User_ID'] = $this->RandomIDUser();
    $params = array(
      'cagent' =>$this->config['cagent'],
      'loginname' =>$this->config['prefix']. $d['User_ID'],
      'password' => $this->password_agin,  //
      'actype' => '1', // 1 là tài khoản thực, 0 là tài khoản dùng thử
      'oddtype' => 'A',
      'cur' => $this->config['currency'],
      'method' => 'lg'
    );
    $paramStr =  $this->encrypt($this->config['des_key'], $params);
    $key = md5($paramStr . $this->config['md5key']);
    $paramStr = urlencode($paramStr);
    $url = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStr . '&key=' . $key;
    $xml = simplexml_load_file($url);
    $input = json_encode($xml);
    $data = json_decode($input);
    foreach($data as $value){
      $info_value = $value->{'info'};
      $info_msg = $value->{'msg'};
    }

    if($info_value == 0){
      //LogUser::addLogUser($user->User_ID, 'register success agin', $info_msg ?? 'Response data false', $request->ip());
      //$d['User_ID'] = $this->RandomIDUser();
      $d['User_Email'] = $request->email;
      $d['User_Provide'] = $user->User_ID;
      $d['User_Agin_Password'] = $request->password;
      $d['User_Agin'] = $this->config['prefix'].$d['User_ID'];
      $pro_user = ProUser::create($d);

      $list = [
        'User_Agin' => $pro_user->User_Agin,
      ];
      return $this->response(200, $list, trans('notification.register_success'), [], true);
    }
    //LogUser::addLogUser($user->User_ID, 'register failed agin', $info_msg ?? 'Response data false', $request->ip());
    return $this->response(200, [], trans('notification.register_failed'), [], false);
  }

  public function postChangePass(Request $request){
    // return $this->response(200, [], 'function under maintenance!', [], false);
    $user = ProUser::where('User_Agin', $request->user_agin)->first();
    $validator = Validator::make($request->all(), [
      //'username' => 'required|min:8|unique:users,User_Name',
      //'nickname' => 'nullable|min:6',
      'user_agin' => 'required',
      'password' => 'required|min:6',
      'new_password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'confirm_password' => 'required|same:new_password|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ], [
      //'new_password.regex' => 'Your password must be at least 6 digits and must be in uppercase and lowercase letters as well as have at least 1 number!',
      'new_password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'confirm_password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'new_password.required' => trans('notification.password_required'),
      'new_password.min' => trans('notification.password_minimum_6_characters'),
      'new_password.max' => trans('notification.password_up_to_12_characters '),
      'confirm_password.required' => trans('notification.password_required') , 
      'confirm_password.min' => trans('notification.password_minimum_6_characters'),
      'confirm_password.max' => trans('notification.password_up_to_12_characters '),
      'confirm_password.same' => trans('notification.confirm_password_must_be_the_same_as_the_old_password'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if (!$user) {
      return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
    }

    if ($user->User_Agin_Password !== $request->password) {
      return $this->response(200, [], trans('notification.old_password_is_incorrect'), [], false);
    }
    if ($request->password === $request->new_password) {
      return $this->response(200, [], trans('notification.New_password_and_old_password_cannot_be_the_same!'), [], false);
    }
    ProUser::where('User_Agin', $request->user_agin)->update(['User_Agin_Password'=> $request->new_password]);
    //$user->User_Agin_Password = $request->new_password;
    //$user->save();   
    //LogUser::addLogUser($user->User_ID, 'Change password agin', 'Response data true', $request->ip());
    return $this->response(200, [], trans('notification.change_password_agin_sportBook_successful'));
  }


  public function deposit(Request $request){
    $validator = Validator::make($request->all(), [
      'user_agin' => 'required',
      'amount' => 'required|numeric|min:50',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ],[
      'password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password.required' => trans('notification.Password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'amount.required' => trans('notification.amount_required'),
      'amount.min' => trans('notification.minimum_amount_50'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user_pro = ProUser::where('User_Agin', $request->user_agin)->first();
    $Provide_Key_API = $request->key_api;
    $user = User::where('Provide_Key_API', $Provide_Key_API)->first();
    //$user = User::find($request->user()->User_ID);
    //if ($user->User_Level != 1) return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);
    //dd($user);
    if (!$user_pro) {
      return $this->response(200, [], trans('notification.Please_register!'), [], false);
    }
    //$userBalance = User::getBalance($user->User_ID);
    //if ($userBalance < $request->amount) return $this->response(200, [], trans('notification.Your_balance_is_not_enough'), [], false);
    if($request->password !== $user_pro->User_Agin_Password){
      return $this->response(200, [], 'Incorrect password', [], false);
    }
    $arrayInsert = array(
      'Money_User' => $user_pro->User_ID,
      'Money_Parent_ID' => $user->User_ID,
      'Money_USDT' => $request->amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Deposit to agin ' . $request->amount . ' point',
      'Money_MoneyAction' => 84,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      //'Money_Currency' => 3,
      'Money_CurrentAmount' => $request->amount,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      //'Money_FromAPI' => 1,
    );

    $pro_money = ProMoney::insert($arrayInsert);
    //dd($pro_money);
    //$id = Money::insertGetId($arrayInsert);

    //////////////////// CHUYEN TIEN VAO AG /////////////////   

    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }


    $transferno = $this->generateRandomDepositString();
    $paramstc = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user_pro->User_ID,
      'method'    => 'tc',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
      'billno'    => $this->config['cagent'] . $transferno,
      'type' => 'IN',
      'credit' => $request->amount
    );
    $paramStrtc =  $this->encrypt($this->config['des_key'], $paramstc);
    $keytc = md5($paramStrtc . $this->config['md5key']);
    $paramStrtc = urlencode($paramStrtc);
    $urltc = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStrtc . '&key=' . $keytc;
    $xmltc = simplexml_load_file($urltc);
    $inputtc = json_encode($xmltc);
    $datatc = json_decode($inputtc);

    $paramstcc = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user_pro->User_ID,
      'method'    => 'tcc',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
      'billno'    => $this->config['cagent'] . $transferno,
      'type' => 'IN',
      'credit' => $request->amount,
      'flag' => '1'
    );
    $paramStrtcc =  $this->encrypt($this->config['des_key'], $paramstcc);
    $keytcc = md5($paramStrtcc . $this->config['md5key']);
    $paramStrtcc = urlencode($paramStrtcc);
    $urltcc = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStrtcc . '&key=' . $keytcc;
    $xmltcc = simplexml_load_file($urltcc);
    $inputtcc = json_encode($xmltcc);
    $datatcc = json_decode($inputtcc);
    foreach($datatc as $value){
      $info_valuetc = $value->{'info'};
      $info_msgtc = $value->{'msg'};
    }

    foreach($datatcc as $value){
      $info_valuetcc = $value->{'info'};
      $info_msgtcc = $value->{'msg'};
    }
    if($info_valuetcc == 0 || $info_valuetc == 0){
      //LogUser::addLogUser($user->User_ID, 'Deposit agin success', $info_msgtcc ?? 'Response data false', $request->ip());
      return $this->response(200, [], trans('notification.deposit_success'), [], true);
    }

    //$cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
    //LogUser::addLogUser($user->User_ID, 'Deposit failed agin', $info_msgtcc ?? 'Response data false', $request->ip());
    return $this->response(200, [], trans('notification.deposit_failed'), [], false);

  }

  public function withdraw(Request $request){
    $validator = Validator::make($request->all(), [
      'user_agin' => 'required',
      'amount' => 'required|numeric|min:50',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ],[
      'password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password.required' => trans('notification.Password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'amount.required' => trans('notification.amount_required'),
      'amount.min' => trans('notification.minimum_amount_50'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user_pro = ProUser::where('User_Agin', $request->user_agin)->first();
    $Provide_Key_API = $request->key_api;
    $user = User::where('Provide_Key_API', $Provide_Key_API)->first();
    //$user = User::find($request->user()->User_ID);
    //if ($user->User_Level != 1) return $this->response(200, [], 'The system is maintained!!!', [], false);
    if (!$user_pro) {
      return $this->response(200, [], trans('notification.please_register!'), [], false);
    }
    if($request->password != $user_pro->User_Agin_Password){
      return $this->response(200, [], trans('notification.Incorrect_password'), [], false);
    }
    //dd($this->aginBalance($user_pro->User_ID)*1);
    if($request->amount > $this->aginBalance($user_pro->User_ID)*1){
      return $this->response(200, [], trans('notification.Balance_agin_sporbook_is_not_enough'), [], false);
    }

    //////////////////// CHUYEN TIEN VAO AG /////////////////   
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }
    $transferno = $this->generateRandomDepositString();
    $paramstc = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user_pro->User_ID,
      'method'    => 'tc',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
      'billno'    => $this->config['cagent'] . $transferno,
      'type' => 'OUT',
      'credit' => $request->amount
    );
    $paramStrtc =  $this->encrypt($this->config['des_key'], $paramstc);
    $keytc = md5($paramStrtc . $this->config['md5key']);
    $paramStrtc = urlencode($paramStrtc);
    $urltc = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStrtc . '&key=' . $keytc;
    $xmltc = simplexml_load_file($urltc);
    $inputtc = json_encode($xmltc);
    $datatc = json_decode($inputtc);

    $paramstcc = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user_pro->User_ID,
      'method'    => 'tcc',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
      'billno'    => $this->config['cagent'] . $transferno,
      'type' => 'OUT',
      'credit' => $request->amount,
      'flag' => '1'
    );
    $paramStrtcc =  $this->encrypt($this->config['des_key'], $paramstcc);
    $keytcc = md5($paramStrtcc . $this->config['md5key']);
    $paramStrtcc = urlencode($paramStrtcc);
    $urltcc = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStrtcc . '&key=' . $keytcc;
    $xmltcc = simplexml_load_file($urltcc);
    $inputtcc = json_encode($xmltcc);
    $datatcc = json_decode($inputtcc);
    foreach($datatc as $value){
      $info_valuetc = $value->{'info'};
      $info_msgtc = $value->{'msg'};
    }
    foreach($datatcc as $value){
      $info_valuetcc = $value->{'info'};
      $info_msgtcc = $value->{'msg'};
    }
    if($info_valuetcc == 0 || $info_valuetc == 0){
      $arrayInsert = array(
        'Money_User' => $user_pro->User_ID,
        'Money_Parent_ID' => $user->User_ID,
        'Money_USDT' => - $request->amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw from agin with ' . $request->amount . ' point',
        'Money_MoneyAction' => 85,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        //'Money_Currency' => 3,
        'Money_CurrentAmount' => $request->amount,
        'Money_CurrencyFrom' => 0,
        'Money_CurrencyTo' => 0,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        //'Money_FromAPI' => 1,
      );

      //dd($arrayInsert);
      $checkWithdraw = ProMoney::insert($arrayInsert);

      //LogUser::addLogUser($user->User_ID, 'Withdraw agin success', $info_msgtcc ?? 'Response data false', $request->ip());
      return $this->response(200, [], trans('notification.withdraw_success'), [], true);
    }
    //LogUser::addLogUser($user->User_ID, 'Withdraw failed agin', $info_msgtcc ?? 'Response data false', $request->ip());
    return $this->response(200, [], trans('notification.withdraw_failed'), [], false);
  }
  public function aginBalance($user){
    $user = ProUser::where('User_ID', $user)->first();
    if ($user->User_Agin_Password == NULL) {
      $balance = 0;
      return $balance;
    }
    $params = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user->User_ID,
      'method'    => 'gb',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
    );
    $paramStr =  $this->encrypt($this->config['des_key'], $params);
    $key = md5($paramStr . $this->config['md5key']);
    $paramStr = urlencode($paramStr);
    $url = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStr . '&key=' . $key;
    $xml = simplexml_load_file($url);
    $input = json_encode($xml);
    $data = json_decode($input);
    foreach($data as $value){
      $info_value = $value->{'info'};
    }
    return $info_value;
  }
  public function generateRandomString($length = 16)
  {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  } 

  public function generateRandomDepositString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = 'deposit';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  function encrypt($des_key,$params) 
  { 
    $param = http_build_query($params); 
    $param = str_replace('&', '/\\\\\\\\/', $param); 
    $data = openssl_encrypt($param, 'DES-ECB', $des_key, OPENSSL_RAW_DATA); 
    return base64_encode($data); 
  } 

  public function RandomIDUser()
  {
    $id = rand(10000000, 99999999);
    //TẠO RA ID RANĐOM
    $user = ProUser::where('User_ID', $id)->first();

    //KIỂM TRA ID RANDOM ĐÃ CÓ TRONG USER CHƯA
    if (!$user) {
      return $id;
    }else{
      return $this->RandomIDUser();
    }
  }
}
