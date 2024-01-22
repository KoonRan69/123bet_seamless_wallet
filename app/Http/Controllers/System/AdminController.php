<?php

namespace App\Http\Controllers\System;

use App\Model\Investment;
use App\Model\Log;
use App\Model\Money;
use App\Model\logMoney;
use App\Model\Eggs;
use App\Model\Foods;
use App\Model\Pools;
use App\Model\Markets;
use App\Model\Profile;
use App\Model\GoogleAuth;
use App\Model\ItemHistory;
use App\Model\Fishs;
use App\Model\EggFailed;
use App\Model\GameBet;
use App\Model\Statistical;
use App\Model\Complaints;

use App\Model\LogUser;
use Cookie;
use Redirect;
use App\Model\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Excel;
use App\Jobs\SendMailJobs;
use Illuminate\Support\Facades\Hash;
use Validator;

use Coinbase\Wallet\Client as Client_CB;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money as CB_Money;

use App\Exports\WalletExport;
use App\Exports\FishsExport;
use App\Exports\EggsExport;
use App\Exports\WalletTempExport;
use App\Exports\InvesmentExport;
use App\Exports\UserExport;
use App\Exports\MarketExport;
use App\Exports\EggTransferExport;
use App\Exports\GameExport;
use App\Exports\EggsFailExport;
use App\Exports\StatisticalGame;
use App\Exports\BalanceUserExport;

class AdminController extends Controller
{

  public $feeInsur = [30 => 0.08, 7 => 0.02];
  public $fee_sell_egg_system = 0.05;
  public $rate_ebp = 10;

  public static function coinbase()
  {
    $apiKey = 'RHWAOsGp4FLv8WVJ';
    $apiSecret = 'OE6lRcSCdsRjrY1jzDjymFdfrrWU0hmd';

    $configuration = Configuration::apiKey($apiKey, $apiSecret);
    $client = Client::create($configuration);

    return $client;
  }

  public $config;

  public function __construct()
  {
    $this->config = config('utils.wm555');
  }

  public function generateRandomString($length = 10)
  {
    $characters = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    $checkCode = DB::table('code_bonus')->where('code', $randomString)->first();
    if (!$checkCode) {
      return $randomString;
    } else {
      return $this->generateRandomString();
    }
  }
  public function createCodePromotion(Request $req){
    $this->validate($req,[
      'quantity' => 'required|numeric|min:1',
      'price' => 'required|numeric|min:1',
      'count_day' => 'required|numeric|min:1',
      'description' => 'required',
    ]);
    $code = $this->generateRandomString();
    $expiration_date = date('Y-m-d H:i:s', strtotime("+" .$req->count_day . " days", time()));

    $data = array();
    $data['code'] =  $code;
    $data['price_bonus'] =  $req->price;
    $data['quantity'] = $req->quantity;
    $data['expiration_date'] =  $expiration_date;
    $data['description'] =  $req->description;
    DB::table('code_bonus')->insert($data);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Create code " . $code . " Successfully!"]);
  }
  public function getPromotionCode(Request $req){
    $listCode = DB::table('code_bonus');
    if($req->code){
      $listCode = $listCode->where('code',$req->code);
    }
    $listCode = $listCode->paginate();
    return view('System.Admin.Promotion-Code', compact('listCode')); 
  }
  public function getDetailLicense($id){
    $detail = Complaints::leftjoin('currency','Currency_ID','currency')
      ->where('id',$id)->orderbyDesc('created_at')->first();
    return view('System.Admin.Detail-License', compact('detail'));
  }
  public function getStatusLicense($id){
    $getLicense = Complaints::where('id',$id)->where('status',0)->update(['status'=>1]);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Replied success"]);
  }
  public function getLicense(Request $req){
    $getLicense = Complaints::leftjoin('currency','Currency_ID','currency')
      ->orderbyDesc('created_at')->paginate(20);
    return view('System.Admin.License', compact('getLicense'));
  }
  public function getSetAgencyUser($id, $level)
  {
    if (Session('user')->User_Level == 1) {
      $levelArr = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 16 => 'ABC');
      $info = User::find($id);
      if ($info) {
        $setLevel = GameBet::setAgencyUser($id, $level);

        $cmt_log = "Set Agency Level: " . $level . " ID User: " . $id;
        Log::insertLog(Session('user')->User_ID, "Set Agency User", 0, $cmt_log);
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Set Agency Level: " . $level . " ID User: " . $id . " Successfully!"]);
      }
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'User Not Found!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
  }

  public function getResetPasswordWm555(Request $request, $id)
  {
    $user = User::find($id);
    //dd($user);
    if ($user->User_WM555 != 1) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Account have not registered!"]);
    }
    $new_pass = 'AB123456';
    $username = 'now' . $user->User_ID;
    $dataRaw = [
      'username' => 'now' . $user->User_ID,
      'password' => $new_pass,
    ];
    //dd($dataRaw);
    $client = new Client();
    $apiKey = $this->config['key'];
    $res = $client->request('POST', $this->config['url'] . 'changepassword?apikey=' . $apiKey, [
      'body' => json_encode($dataRaw)
    ]);
    $data = $res->getBody()->getContents();
    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data));
    //dd($data);
    //$data = json_decode($res->getBody()->getContents());
    //dd($data,$dataRaw,$res->getBody()->getContents());
    //dd( $this->config['url'].'addmember?apikey='.$apiKey , $data, json_encode($dataRaw));
    if (!$data || $data->error_code != 0 || $data->data->status == false) {

      LogUser::addLogUser($user->User_ID, 'Rest password wm555 by Admin ID: ' . session('user')->User_ID, $data->data->message ?? 'Response data false', $request->ip());
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Reset password fail! Please try again!"]);
    }

    $user->User_WM_Password = $new_pass;
    $user->save();
    LogUser::addLogUser($user->User_ID, 'Change password wm555 by Admin ID: ' . session('user')->User_ID, $data->data->message ?? 'Response data true', $request->ip());
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Reset Password WM555 Successful!"]);
  }

  public function postAdminInsuranceMin(Request $req)
  {
    $this->validate($req,
                    [
                      'min' => 'required',
                    ]
                   );
    $data = [
      'Min' => $req->min,
      'Status' => 1,
    ];
    DB::table('promotion_min')->insert($data);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Add min insurance success!"]);
  }


  public function postAdminInsuranDate(Request $req)
  {
    $this->validate($req,
                    [
                      'date' => 'required',
                      'fee' => 'required',
                    ]
                   );
    $data = [
      'Date' => $req->date,
      'Fee' => $req->fee / 100,
      'Status' => 1,
    ];
    DB::table('promotion_date')->insert($data);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Add date insurance success!"]);
  }

  public function postAdminEditInsurDate(Request $req)
  {
    $this->validate($req,
                    [
                      'id' => 'required',
                      'date' => 'required',
                      'fee' => 'required',
                    ]
                   );
    $check = DB::table('promotion_date')->where('ID', $req->id)->first();
    if ($check) {
      DB::table('promotion_date')->where('ID', $check->ID)->update([
        'Date' => $req->date,
        'Fee' => $req->fee / 100,
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Edit date Success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'ID date not exit!']);
  }

  public function getAdminInsuranceDeleDate($id)
  {
    $check = DB::table('promotion_date')->where('ID', $id)->first();
    if ($check) {
      DB::table('promotion_date')->where('ID', $check->ID)->update([
        'Status' => -1
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Delete date Success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'ID date not exit!']);
  }

  public function getAdminInsuranceDeleCountries($id)
  {
    $check = DB::table('promotion_countries')->where('ID', $id)->first();
    if ($check) {
      DB::table('promotion_countries')->where('ID', $check->ID)->update([
        'Status' => -1
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Delete countries Success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'ID countries not exit!']);
  }

  public function getAdminInsuranceEditCountries($id, $countries)
  {
    if (Session('user')->User_Level == 1) {
      $check = DB::table('promotion_countries')->where('ID', $id)->first();
      if (!$check) {
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Countries Not Found!']);
      }
      DB::table('promotion_countries')->where('ID', $check->ID)->update([
        'Countries_id' => $countries,
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Updata success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
  }

  public function postAdminInsuranCountries(Request $req)
  {
    $this->validate($req,
                    [
                      'countries' => 'required',
                    ]
                   );
    $data = [
      'Countries_id' => $req->countries,
      'Status' => 1,
    ];
    DB::table('promotion_countries')->insert($data);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Add countries insurance success!"]);
  }

  public function postAdminEditInsurTime(Request $req)
  {
    $this->validate($req,
                    [
                      'id' => 'required',
                      'time_start' => 'required',
                    ]
                   );
    $check = DB::table('promotion_time_zoom')->where('id', $req->id)->first();
    if ($check) {
      DB::table('promotion_time_zoom')->where('id', $check->id)->update([
        'game_id' => $req->game_id,
        'time' => $req->time_start,
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Edit time zoom Success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'ID time zoom not exit!']);
  }

  public function postAdminEditInsurGame(Request $req)
  {
    $this->validate($req,
                    [
                      'name' => 'required',
                    ]
                   );
    $check = DB::table('promotion_game')->where('Promotion_Game_ID', $req->id)->first();
    if ($check) {
      DB::table('promotion_game')->where('Promotion_Game_ID', $check->Promotion_Game_ID)->update([
        'Promotion_Game_Name' => $req->name
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Edit game Success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'ID game not exit!']);
  }

  public function getAdminInsuranceDeleGame($id)
  {
    $check = DB::table('promotion_game')->where('Promotion_Game_ID', $id)->first();
    if ($check) {
      DB::table('promotion_game')->where('Promotion_Game_ID', $check->Promotion_Game_ID)->update([
        'Promotion_Game_Status' => -1
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Delete game Success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'ID game not exit!']);
  }

  public function getAdminInsuranceDeleTime($id)
  {
    $check = DB::table('promotion_time_zoom')->where('id', $id)->first();
    if ($check) {
      DB::table('promotion_time_zoom')->where('id', $check->id)->update([
        'status' => -1
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Delete time zoom Success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'ID time zoom not exit!']);
  }

  public function postAdminInsuranceTime(Request $req)
  {
    $this->validate($req,
                    [
                      'time' => 'required',
                    ]
                   );
    $data = [
      'time' => $req->time,
      'status' => 1,
    ];
    DB::table('promotion_time_zoom')->insert($data);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Add time zoom insurance success!"]);
  }

  public function postAdminInsuranceGame(Request $req)
  {
    $this->validate($req,
                    [
                      'game_name' => 'required',
                    ]
                   );
    $data = [
      'Promotion_Game_Name' => $req->game_name,
      'Promotion_Game_Status' => 1,
    ];
    DB::table('promotion_game')->insert($data);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Add game insurance success!"]);
  }

  public function getAdminSettingInsurance(Request $req)
  {

    $list_time = DB::table('promotion_time_zoom')->where('status', 1)->get();
    $list_countries = DB::table('promotion_countries')->where('Status', 1)->get();
    $min = DB::table('promotion_min')->where('Status', 1)->orderBy('ID', 'DESC')->first();
    $list_date = DB::table('promotion_date')->where('Status', 1)->get();
    $countries = DB::table('countries')->get();
    $list_game = DB::table('promotion_game')->where('Promotion_Game_Status', 1)->get();
    return view('System.Admin.SettingInsurance', compact('list_game', 'list_time', 'countries', 'list_countries', 'list_date', 'min'));

  }

  public function getAdminInsurance(Request $req)
  {
    $getData = DB::table('promotion_sub');
    if ($req->user_id) {
      $getData = $getData->where('user_id', $req->user_id);
    }
    if ($req->id) {
      $getData = $getData->where('id', $req->id);
    }
    if ($req->status && $req->status !== '') {
      $getData = $getData->where('status', $req->status);
    }
    if ($req->datefrom) {
      $getData = $getData->where('created_time', '>=', date('Y-m-d 00:00:00', strtotime($req->datefrom)));
    }
    if ($req->dateto) {
      $getData = $getData->where('created_time', '<', date('Y-m-d 00:00:00', (strtotime($req->dateto) + 86400)));
    }
    $getData = $getData->orderByDesc('id')->paginate(50);
    $feeInsur = $this->feeInsur;
    return view('System.Admin.Insurance', compact('getData', 'feeInsur'));
  }

  public function postDepositAdmin(Request $req)
  {
    $user = User::find(session('user')->User_ID);
    /*if ($user->User_Level != 1) {
          abort(404);
        }*/
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy();
    $arrCoin = [1 => 'BTC', 2 => 'ETH', 4 => 'DP-NFT', 5 => 'USDT', 7 => 'HBG', 8 => 'EBP', 12 => 'SOL', 13 => 'C98', 14 => 'ADA', 15 => 'TRX', 16 => 'BNB'];
    $getInfo = User::where('User_ID', $req->user)->first();
    $amount = $req->amount;
    $coin = $req->coin;

    if (!$getInfo) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error! User is not exist!']);
    }
    if (!$amount || $amount <= 0) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error! Enter amount > 0!']);
    }
    $symbol = $arrCoin[$coin];
    $priceCoin = $rate[$symbol];
    $AmountCoin = $amount / $priceCoin;

    $coinBalance = 3;
    if ($coin == 8) {
      $coinBalance = $coin;
      $AmountCoin = $amount;
    }
    $comment = "Deposit $AmountCoin $symbol";
    if($req->action != 1 ){
      if(!$req->comment || $req->comment == null){
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error! Comment not null!']);
      }
      $comment = $req->comment;
    }

    $feeDeposit = 0;
    if($coin == 4 || $coin == 12 || $coin == 13 || $coin == 14 || $coin == 15 || $coin == 16){
      $feeDeposit = 0.1;
    }elseif($coin == 7){
      $feeDeposit = 0;
    }
    $amountUSDTFee = $amount * $feeDeposit;
    //deposit
    if($req->action == 10 ) $coinBalance = 10;
    //dd(time(),$amount,$AmountCoin,$priceCoin,$req->hash);
    $money = new Money();
    $money->Money_User = $getInfo->User_ID;
    $money->Money_USDT = $amount;
    $money->Money_USDTFee = -$amountUSDTFee;
    $money->Money_Time = time();
    $money->Money_Comment = $comment;
    $money->Money_Currency = $coinBalance;
    $money->Money_CurrencyFrom = $coin;
    $money->Money_MoneyAction = $req->action;
    $money->Money_Address = $req->hash;
    $money->Money_CurrentAmount = $AmountCoin;
    $money->Money_Rate = $priceCoin;
    $money->Money_MoneyStatus = 1;
    $money->save();
    $current_date = date('Y-m-d H:i:s');
    //if(strtotime($current_date) > strtotime('2021-5-17 00:00:00') && strtotime($current_date) < strtotime('2021-5-30 00:00:00')){
    if($req->action == 1){
      $bonus = logMoney::getBonusDepositBirthday($getInfo->User_ID, $amount, $coin);
      $bonusFirstDay = logMoney::getBonusDailyRecharge($getInfo->User_ID, $amount, $coin);
    }


    //$updatebalance = User::updateBalance($user->Address_User, 5, $value - $amountFee);
    if($getInfo->User_Level == 1){
      //Money::commissionDepositNew($getInfo, $AmountCoin, $coin, $rate[$symbol]);
    }
    //updateBalanceDeposit
    Log::insertLog($user->User_ID, "Deposit Admin", 0, "$user->User_ID Deposit $amount $symbol To $getInfo->User_ID");
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Deposit $getInfo->User_ID $AmountCoin $symbol Success!"]);
  }

  public function getEggsTransfer(Request $request)
  {
    // dd(123);
    $user = Session('user');
    $eggs = Log::where('Log_Action', 'Transfer Eggs');
    if (Input::get('user_give')) {
      $eggs = $eggs->where('Log_Comment', 'like', "%" . Input::get('user_give') . "%");
    }
    if (Input::get('eggs_id')) {
      $eggs = $eggs->where('Log_Comment', 'like', "%" . Input::get('eggs_id') . "%");
    }
    if (Input::get('from') && !Input::get('to')) {
      $from = strtotime(Input::get('from'));
      $eggs->where('Log_CreatedAt', '>=', date('Y-m-d H:i:s', $from));
    }
    if (Input::get('to') && !Input::get('from')) {
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $eggs->where('Log_CreatedAt', '<', date('Y-m-d H:i:s', $to));
    }
    if (Input::get('to') && Input::get('from')) {
      $from = strtotime(Input::get('from'));
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $eggs->whereBetween('Log_CreatedAt', [date('Y-m-d H:i:s', $from), date('Y-m-d H:i:s', $to)]);
    }

    if ($request->export) {
      ob_end_clean();
      ob_start();
      return Excel::download(new EggTransferExport($eggs->get()), 'EggTransferExport.xlsx');
    }

    $eggs = $eggs->paginate(25);
    // dd($eggs);
    $balance['Egg'] = Eggs::where('Owner', (string)$user->User_ID)->where('Status', 1)->where('ActiveTime', 0)->select("ID")->count();
    return view('System.Admin.EggsTransfer', compact('eggs', 'balance'));
  }

  public function postTransferEgg(Request $req)
  {
    $this->validate($req, [
      'user' => 'required|nullable|string',
      'otp' => 'required|nullable|string',
    ], []);
    $user = Session('user');
    if ($user->User_Level != 4) {
      // abort(404);
    }
    $userGive = User::where('User_ID', $req->user)->orWhere('User_Email', $req->user)->first();
    if (!$userGive) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'User give egg is not found!']);
    }
    $google2fa = app('pragmarx.google2fa');
    $AuthUser = GoogleAuth::select('google2fa_Secret')->where('google2fa_User', $user->User_ID)->first();
    if (!$AuthUser) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Please enalble Authentication Code!']);
    }
    $valid = $google2fa->verifyKey($AuthUser->google2fa_Secret, $req->otp);
    if (!$valid) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Authentication code is wrong!']);
    }
    if ($req->quantity && $req->quantity > 1) {
      //transfer nhiều trứng 1 lần
      $quantity = (int)$req->quantity;
      $listEggs = Eggs::where('Owner', (string)$user->User_ID)->where('Status', 1)->where('ActiveTime', 0)->limit($quantity)->get();
      if ($listEggs->count() < $quantity) {
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Your egg is not enough!']);
      }
      $eggID = [];
      foreach ($listEggs as $egg) {
        $egg->Owner = $userGive->User_ID;
        $egg->BuyFrom = "Give From " . $user->User_ID;
        $egg->Pool = "0";
        $egg->save();
        ItemHistory::addHistory($userGive->User_ID, $egg->ID, 'Give Egg :' . $egg->ID . ' From ' . $user->User_ID, time());
        $eggID[] = $egg->ID;
      }
      $listEggsID = implode(", ", $eggID);
      Log::insertLog($user->User_ID, 'Transfer Eggs', 0, "Transfer List Egg: " . $listEggsID . " To User ID: " . $userGive->User_ID);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Transfer Eggs: ' . $listEggsID . ' Success!']);
    } else {
      //transfer 1 trứng 1 lần
      if ($req->egg) {
        $checkEggs = Eggs::where('Owner', (string)$user->User_ID)->where('ID', $req->egg)->where('Status', 1)->where('ActiveTime', 0)->first();
      } else {
        $checkEggs = Eggs::where('Owner', (string)$user->User_ID)->where('ID', $req->egg)->where('Status', 1)->where('ActiveTime', 0)->first();
      }
      $checkEggs = Eggs::where('Owner', (string)$user->User_ID)->where('Status', 1)->where('ActiveTime', 0)->first();
      if (!$checkEggs) {
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Your egg is not found!']);
      }
      $checkEggs->Owner = $userGive->User_ID;
      $checkEggs->BuyFrom = "Give From " . $user->User_ID;
      $checkEggs->Pool = "0";
      $checkEggs->save();
      ItemHistory::addHistory($userGive->User_ID, $checkEggs->ID, 'Give Egg :' . $checkEggs->ID . ' From ' . $user->User_ID, time());
      Log::insertLog($user->User_ID, 'Transfer Eggs', 0, "Transfer Egg: " . $checkEggs->ID . " To User ID: " . $userGive->User_ID);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Transfer Eggs: ' . $checkEggs->ID . ' Success!']);
    }
  }

  public function getSetLevelUser($id, $level)
  {
    if (Session('user')->User_Level == 1) {
      $levelArr = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
      $info = User::find($id);
      if ($info) {
        $info->User_Level = $level;
        $info->save();

        $cmt_log = "Set Level: " . $levelArr[$level] . " ID User: " . $id;
        Log::insertLog(Session('user')->User_ID, "Set Level User", 0, $cmt_log);
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Set Level: " . $levelArr[$level] . " ID User: " . $id . " Successfully!"]);
      }
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'User Not Found!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
  }

  public function getResetPassword($id)
  {
    $user = session('user');
    if ($user->User_Level == 1) {
      $userInfo = User::find($id);
      if ($userInfo) {
        $userInfo->User_Password = bcrypt('123456');
        $userInfo->save();
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Reset Password Success!']);
      }
    } else {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
    }
  }

  public function getBlogEvent()
  {
    $listBlog = DB::table('blog')->select('id', 'title', 'content', 'description', 'banner')->where('status', 1)->orderByDesc('id')->paginate(15);
    // dd($listBlog);
    return view('System.Admin.BlogEvent', compact('listBlog'));
  }

  public function getEditBlog(Request $req, $id)
  {
    $checkBlog = DB::table('blog')->where('id', $id)->first();
    // dd($checkBlog);
    return view('System.Admin.EditBlog', compact('checkBlog'));
  }

  public function getDeleteBlog(Request $req, $id)
  {
    $checkBlog = DB::table('blog')->where('id', $id)->first();
    if ($checkBlog) {
      $delete = DB::table('blog')->where('id', $id)->update([
        'status' => -1
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Delete blog success!']);
    }
    // dd($checkBlog);
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'error!']);
  }

  public function getPercent()
  {
    if (Session('user')->User_Level != 1) {
      return redirect()->back();
    }
    $percent[1] = DB::table('profit')->join('package', 'package_ID', 'Percent_PackageID')->where('Percent_Time', '>=', date('Y-m-d'))->where('Percent_PackageID', 1)->orderBy('Percent_Time')->get();
    $percent[2] = DB::table('profit')->join('package', 'package_ID', 'Percent_PackageID')->where('Percent_Time', '>=', date('Y-m-d'))->where('Percent_PackageID', 2)->orderBy('Percent_Time')->get();
    $percent[3] = DB::table('profit')->join('package', 'package_ID', 'Percent_PackageID')->where('Percent_Time', '>=', date('Y-m-d'))->where('Percent_PackageID', 3)->orderBy('Percent_Time')->get();
    $percent[4] = DB::table('profit')->join('package', 'package_ID', 'Percent_PackageID')->where('Percent_Time', '>=', date('Y-m-d'))->where('Percent_PackageID', 4)->orderBy('Percent_Time')->get();
    $percent[5] = DB::table('profit')->join('package', 'package_ID', 'Percent_PackageID')->where('Percent_Time', '>=', date('Y-m-d'))->where('Percent_PackageID', 5)->orderBy('Percent_Time')->get();
    $arrMinMax = [1 => ['min' => 99, 'max' => 999], 2 => ['min' => 1000, 'max' => 4999], 3 => ['min' => 5000, 'max' => 19999], 4 => ['min' => 20000, 'max' => 99999], 5 => ['min' => 100000, 'max' => 'Infinity']];

    return view('System.Admin.Percent', compact('percent', 'arrMinMax'));
  }

  public function postChangePercent(Request $req)
  {
    if (Session('user')->User_Level != 1) {
      dd('stop');
    }
    $percent = DB::table('profit')->where('Percent_ID', $req->ID)->update(['Percent_Percent' => ($req->Percent / 100)]);

    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Change % Success!']);

  }

  public function getLot()
  {
    if (Session('user')->User_Level != 1) {
      return redirect()->back();
    }
    $percent[1] = DB::table('lot')->join('package', 'package_ID', 'lot_Package')->where('lot_Date', '>=', date('Y-m-d'))->where('lot_Package', 1)->orderBy('lot_Date')->get();
    $percent[2] = DB::table('lot')->join('package', 'package_ID', 'lot_Package')->where('lot_Date', '>=', date('Y-m-d'))->where('lot_Package', 2)->orderBy('lot_Date')->get();
    $percent[3] = DB::table('lot')->join('package', 'package_ID', 'lot_Package')->where('lot_Date', '>=', date('Y-m-d'))->where('lot_Package', 3)->orderBy('lot_Date')->get();
    $percent[4] = DB::table('lot')->join('package', 'package_ID', 'lot_Package')->where('lot_Date', '>=', date('Y-m-d'))->where('lot_Package', 4)->orderBy('lot_Date')->get();
    $percent[5] = DB::table('lot')->join('package', 'package_ID', 'lot_Package')->where('lot_Date', '>=', date('Y-m-d'))->where('lot_Package', 5)->orderBy('lot_Date')->get();
    $arrMinMax = [1 => ['min' => 99, 'max' => 999], 2 => ['min' => 1000, 'max' => 4999], 3 => ['min' => 5000, 'max' => 19999], 4 => ['min' => 20000, 'max' => 99999], 5 => ['min' => 100000, 'max' => 'Infinity']];
    $lotSales = DB::table('lot_sales')->where('lot_Date', '>=', date('Y-m-d'))->orderBy('lot_Date')->get();
    return view('System.Admin.Lot', compact('percent', 'arrMinMax', 'lotSales'));
  }

  public function postChangeLotMember(Request $req)
  {
    if (Session('user')->User_Level != 1) {
      dd('stop');
    }
    $percent = DB::table('lot')->where('lot_ID', $req->ID)->update(['lot_Member' => $req->member]);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Change Member LOT Success!']);
  }

  public function postChangeLotSales(Request $req)
  {
    if (Session('user')->User_Level != 1) {
      dd('stop');
    }
    $percent = DB::table('lot_sales')->where('lot_ID', $req->ID)->update(['lot_Sales' => $req->sales]);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Change Sales LOT Success!']);
  }

  public function getMemberListAdmin(Request $req)
  {
    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $user = Session::get('user');
    if ($user->User_Level != 1 && $user->User_Level != 2 && $user->User_Level != 3) {
      dd('Stop');
    }
    $where = null;
    if ($req->UserID) {
      $where .= ' AND User_ID=' . $req->UserID;
    }

    if ($req->AddressRegister) {
      $where .= ' AND User_WalletAddress = "' . $req->AddressRegister . '"';
    }
    if ($req->Username) {
      $where .= ' AND User_Name LIKE "' . $req->Username . '"';
    }
    if ($req->Email) {
      $where .= ' AND User_Email LIKE "%' . $req->Email . '%"';
    }
    if ($req->sponsor) {
      $where .= ' AND User_Parent = ' . $req->sponsor;
    }
    if ($req->agency_level) {
      $where .= ' AND User_Agency_Level = ' . $req->agency_level;
    }
    if ($req->datetime) {
      $where .= ' AND date(User_RegisteredDatetime) = "' . date('Y-m-d', strtotime($req->datetime)) . '"';
    }
    if ($req->status_email != null) {
      $where .= ' AND User_EmailActive = ' . $req->status_email;
    }
    if ($req->user_level != null) {
      $where .= ' AND User_Level = ' . $req->user_level;
    }
    if ($req->tree != '') {

      $where .= ' AND User_Tree LIKE "%' . str_replace(', ', ',', $req->tree) . '%"';
    }
    if ($req->suntree != '') {

      $where .= ' AND User_SunTree LIKE "%' . str_replace(', ', ',', $req->suntree) . '%"';
    }
    if ($req->export == 1) {
      if ($user->User_Level != 1 && $user->User_Level != 2 && $user->User_Level != 3) {
        //dd('Stop');
      }
      $Member = User::with('AddressDeposit')
        ->leftJoin('google2fa', 'google2fa.google2fa_User', 'users.User_ID')
        ->leftJoin('profile', 'Profile_User', 'User_ID')
        ->whereRaw('1 ' . $where)
        ->orderBy('User_RegisteredDatetime', 'DESC')->get();

      ob_end_clean();
      ob_start();
      return Excel::download(new UserExport($Member), 'UserExport.xlsx');
      // $member = array();
      // foreach ($Member as $h) {
      //     if ($h->User_EmailActive == 1) {
      //         $h->User_EmailActive = "Active";
      //     } else {
      //         $h->User_EmailActive = "Not Active";
      //     }
      //     $member[] = $h;
      // }
      // //xuất excel
      // $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer');
      // // $listMemberExcel[] = array('ID','Email', 'ID Parent','Registred DateTime','Level','Status') ;
      // $listMemberExcel[] = array('ID', 'Email', 'Registred DateTime', 'ID Parent', 'Tree', 'Level', 'Status', 'Auth');
      // $i = 1;
      // foreach ($member as $d) {
      //     $listMemberExcel[$i][0] = $d->User_ID;
      //     $listMemberExcel[$i][1] = $d->User_Email;
      //     $listMemberExcel[$i][2] = $d->User_RegisteredDatetime;
      //     $listMemberExcel[$i][3] = $d->User_Parent;
      //     $listMemberExcel[$i][4] = $d->User_Tree;
      //     $listMemberExcel[$i][5] = $level[$d->User_Level];
      //     $listMemberExcel[$i][6] = $d->User_EmailActive;
      //     if ($d->google2fa_User) {
      //         $listMemberExcel[$i][7] = "Enable";
      //     } else {
      //         $listMemberExcel[$i][7] = "Disable";
      //     }
      //     $i++;
      // }

      // Excel::create('Member', function ($excel) use ($listMemberExcel) {
      //     $excel->setTitle('Member');
      //     $excel->setCreator('Member')->setCompany('SMT');
      //     $excel->setDescription('Member');
      //     $excel->sheet('sheet1', function ($sheet) use ($listMemberExcel) {
      //         $sheet->fromArray($listMemberExcel, null, 'A1', false, false);
      //     });
      // })->download('xls');
    }

    $user_list = User::leftJoin('google2fa', 'google2fa.google2fa_User', 'users.User_ID')
      ->join('user_level', 'User_Level_ID', 'User_Level')
      ->whereRaw('1 ' . $where)
      ->orderBy('User_RegisteredDatetime', 'DESC');

    $user_list = $user_list->paginate(50);
    //dd($user_list);
    $user_level = DB::table('user_level')->orderBy('User_Level_ID')->get();
    $user_agency_level = DB::table('user_agency_level')->orderBy('user_agency_level_ID')->get();
    $listSetAgency = DB::table('set_agency')->where('status', 1)->pluck('level', 'user')->toArray();
    return view('System.Admin.User', compact('user_list', 'user_level', 'user_agency_level', 'level', 'listSetAgency', 'user'));
  }

  public function getDisableAuth($id)
  {
    $user = Session('user');
    if ($user->User_Level == 1 || $user->User_Level == 3) {
      $check_auth = GoogleAuth::where('google2fa_User', $id)->delete();
      if ($check_auth) {
        //User to delete auth
        $checkUser = User::where('User_ID', $id)->first();
        $cmt_log = "Disable Auth ID User: " . $id;
        Log::insertLog(Session('user')->User_ID, "Disable Auth", 0, $cmt_log);

        //Send email
        try {
          // gửi mail thông báo
          $data = [
            'User_ID' => $checkUser->User_ID,
            'User_Email' => $checkUser->User_Email
          ];
          //Job
          //dispatch(new SendMailJobs('forgot_authenticator_success', $data, 'WE RECOVERED YOUR ACCOUNT SUCCESSFULLY ', $checkUser->User_ID));

          return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Successfully Deleted Auth!']);
        } catch (Exception $e) {
          echo $e;
          return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Successfully Deleted Auth! But Send mail fail']);
        }
      }
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Auth Delete Failed!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
  }

  public function getProfile(Request $request)
  {
    $user = Session('user');
    $profileList = Profile::join('users', 'Profile_User', 'User_ID');
    if ($request->Email) {
      $searchUserID = User::where('User_Email', $request->Email)->value('User_ID');
      $profileList = Profile::where('Profile_User', $searchUserID);
    }
    if ($request->UserID) {
      $profileList = $profileList->where('Profile_User', $request->UserID);
    }

    if ($request->status != null) {
      $profileList = $profileList->where('Profile_Status', $request->status);
    }
    if ($request->datefrom and $request->dateto) {
      $profileList = $profileList->whereRaw("DATE_FORMAT(Profile_Time, '%Y/%m/%d') >= '$request->datefrom' AND DATE_FORMAT(Profile_Time, '%Y/%m/%d') <= '$request->dateto' ");
    }
    if ($request->datefrom and !$request->dateto) {
      $profileList = $profileList->whereRaw("DATE_FORMAT(Profile_Time, '%Y/%m/%d') >= '$request->datefrom'");
    }
    if (!$request->datefrom and $request->dateto) {
      $profileList = $profileList->whereRaw("DATE_FORMAT(Profile_Time, '%Y/%m/%d') <= '$request->dateto'");
    }
    $profileList = $profileList->orderByDesc('Profile_ID', 'Profile_Status')->paginate(15);
    // dd($profileList);
    return view('System.Admin.Confirm-Profile', compact('profileList', 'user'));
  }

  public function confirmProfile(Request $request)
  {
    if (Session('user')->User_Level != 1 && Session('user')->User_Level != 3 && Session('user')->User_Level != 4) {
      return response()->json(['status' => 'error', 'message' => 'Error, please contact admin!'], 200);
    }
    if ($request->action == 1) {
      $updateProfileStatus = Profile::where('Profile_ID', $request->id)->update(['Profile_Status' => 1]);
      if ($updateProfileStatus) {
        $profileInfo = Profile::find($request->id);
        $user = User::find($profileInfo->Profile_User);
        $bonus = Money::bonusKYC($user);
        $data = [];
        $user = Profile::join('users', 'Profile_User', 'User_ID')
          ->where('Profile_ID', $request->id)
          ->first();
        //Send mail job
        $data = array('User_ID' => $user->User_ID, 'User_Name' => $user->User_Name, 'User_Email' => $user->User_Email, 'token' => 'hihi');
        //Job
        dispatch(new SendMailJobs('KYC_SUCCESS', $data, 'KYC Notification!', $user->User_ID));

        return response()->json(['status' => 'success', 'message' => 'confirmed!'], 200);
      }
      return response()->json(['status' => 'error', 'message' => 'Error, please contact admin!'], 200);
    }
    if ($request->action == -1) {
      $removeKYC = Profile::join('users', 'Profile_User', 'User_ID')->where('Profile_ID', $request->id)->first();

      $deleteImage_Server = Storage::disk('ftp')->delete([$removeKYC->Profile_Passport_Image, $removeKYC->Profile_Passport_Image_Selfie]);
      // $deleteImage_Server = true;
      if ($deleteImage_Server) {
        $data = [];
        $removeRecord = Profile::where('Profile_ID', $request->id)->delete();
        //Send mail job
        $data = array('User_ID' => $removeKYC->User_ID, 'User_Name' => $removeKYC->User_Name, 'User_Email' => $removeKYC->User_Email, 'token' => 'hihi');
        //Job
        dispatch(new SendMailJobs('KYC_ERROR', $data, 'KYC Notification!', $removeKYC->User_ID));

        return response()->json(['status' => 'success', 'message' => 'Disagreed!'], 200);
      }
      return response()->json(['status' => 'error', 'message' => 'Error, please contact admin!'], 200);
    }
  }

  public function getAdminInvestmentList(Request $request)
  {
    $investmentList = Investment::join('currency', 'investment_Currency', '=', 'currency.Currency_ID')
      ->join('users', 'investment_User', 'User_ID')
      ->orderBy('investment_ID', 'DESC');

    if ($request->user_id) {
      $investmentList = $investmentList->where('investment_User', $request->user_id);
    }
    if ($request->email) {
      $searchUserID = User::where('User_Email', $request->email)->value('User_ID');
      $investmentList = $investmentList->where('investment_User', $searchUserID);
    }
    if ($request->status != "") {

      $investmentList = $investmentList->where('investment_Status', $request->status);
    }
    if ($request->datefrom and $request->dateto) {
      $investmentList = $investmentList->where('investment_Time', '>=', strtotime($request->datefrom))
        ->where('investment_Time', '<', strtotime($request->dateto) + 86400);
    }
    if ($request->datefrom and !$request->dateto) {
      $investmentList = $investmentList->where('investment_Time', '>=', strtotime($request->datefrom));
    }
    if (!$request->datefrom and $request->dateto) {

      $investmentList = $investmentList->where('investment_Time', '<', strtotime($request->dateto) + 86400);
    }

    if ($request->export == 1) {
      if (Session('user')->User_Level != 1 && Session('user')->User_Level != 2) {
        dd('Stop');
      }
      ob_end_clean();
      ob_start();
      return Excel::download(new InvesmentExport($investmentList->get()), 'InvesmentExport.xlsx');
    }
    $investmentList = $investmentList->paginate(15);
    return view('System.Admin.Investment', compact('investmentList'));
  }

  public function getWallet(Request $request)
  {
    $user = Session('user');
    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $walletList = Money::leftjoin('currency', 'Money_Currency', '=', 'currency.Currency_ID')
      ->leftjoin('currency as currency_to', 'Money_CurrencyTo', '=', 'currency_to.Currency_ID')
      ->leftjoin('currency as currency_from', 'Money_CurrencyFrom', '=', 'currency_from.Currency_ID')
      ->leftjoin('moneyaction', 'Money_MoneyAction', '=', 'moneyaction.MoneyAction_ID')
      ->join('users', 'Money_User', 'users.User_ID')
      ->select('Money_ID', 'Money_User', 'users.User_Level', 'Money_MoneyAction', 'Money_USDT', 'Money_Currency', 'Money_USDTFee', 'Money_Time', 'currency.Currency_Name as Currency_Symbol', 'currency_from.Currency_Name as Currency_From_Symbol', 'currency_to.Currency_Name as Currency_To_Symbol', 'moneyaction.MoneyAction_Name', 'Money_Comment', 'Money_MoneyStatus', 'Money_Confirm', 'Money_Rate', 'Money_CurrentAmount', 'Money_Address','Money_CurrencyFrom', 'Money_CurrencyTo');

    $arr_coin = Money::getSymbol();
    if ($request->id) {
      $walletList = $walletList->where('Money_ID', intval($request->id));
    }
    if ($request->user_id) {
      $walletList = $walletList->where('Money_User', $request->user_id);
    }
    if ($request->tree) {
      $walletList = $walletList->where('User_Tree', 'LIKE', "%$request->tree%");
    }

    if (isset($request->User_Level) && $request->User_Level != null) {
      $walletList = $walletList->where('users.User_Level', $request->User_Level);
    }
    if ($request->action) {
      $walletList = $walletList->whereIn('Money_MoneyAction', $request->action);
    }
    if ($request->comment) {
      $walletList = $walletList->where('Money_Comment', 'like', "%$request->comment%");
    }
    if ($request->status != '') {
      //$walletList = $walletList->where('Money_MoneyStatus', $request->status);
      if ($request->status == 0) {
        $walletList = $walletList->where('Money_MoneyAction', 2)->where('Money_Confirm', 0);
      } else {
        $walletList = $walletList->where('Money_MoneyStatus', (int)$request->status);
      }
    }
    if ($request->datefrom and $request->dateto) {
      $walletList = $walletList->where('Money_Time', '>=', strtotime($request->datefrom))
        ->where('Money_Time', '<', strtotime($request->dateto));// + 86400
    }
    if ($request->datefrom and !$request->dateto) {
      $walletList = $walletList->where('Money_Time', '>=', strtotime($request->datefrom));
    }
    if (!$request->datefrom and $request->dateto) {
      $walletList = $walletList->where('Money_Time', '<', strtotime($request->dateto));// + 86400
    }

    if ($request->export) {
      set_time_limit(300);
      ob_end_clean();
      ob_start();
      return Excel::download(new WalletExport($walletList->orderByDesc('Money_ID')->get()), 'WalletExport.xlsx');

      // Excel::create('History-Wallet' . date('YmdHis'), function ($excel) use ($walletList, $level) {
      //     $excel->sheet('report', function ($sheet) use ($walletList, $level) {
      //         $sheet->appendRow(array(
      //             'ID',
      //             'User ID',
      //             'User Level',
      //             'Action',
      //             'Comment',
      //             'DateTime',
      //             'Amount Coin',
      //             'Currency',
      //             'Rate',
      //             'USD',
      //             'Fee Coin',
      //             'Fee USD',
      //             'Status'
      //         ));
      //         $walletList->chunk(2000, function ($rows) use ($sheet, $level) {
      //             foreach ($rows as $row) {
      //                 if ($row->Money_MoneyStatus == 1) {
      //                     if ($row->Money_MoneyAction == 2 && $row->Money_Confirm == 0) {
      //                         $row->Money_Confirm = "Pending";
      //                     } else {
      //                         $row->Money_Confirm = "Success";
      //                     }
      //                 } else {
      //                     $row->Money_Confirm = "Cancel";
      //                 }
      //                 $sheet->appendRow(array(

      //                     $row->Money_ID,
      //                     $row->Money_User,
      //                     $level[$row->User_Level],
      //                     $row->MoneyAction_Name,
      //                     $row->Money_Comment,
      //                     date('Y-m-d H:i:s', $row->Money_Time),
      //                     $row->Money_USDT,
      //                     $row->Currency_Symbol,
      //                     $row->Money_Rate,
      //                     $row->Money_USDT * $row->Money_Rate,
      //                     $row->Money_USDTFee,
      //                     $row->Money_USDTFee * $row->Money_Rate,
      //                     $row->Money_Confirm
      //                 ));
      //             }
      //         });
      //     });
      // })->export('xlsx');
    }
    // dd(1);
    $walletList = $walletList->orderByDesc('Money_ID')->paginate(50);
    $action = DB::table('moneyaction')->get();
    return view('System.Admin.Wallet', compact('walletList', 'action', 'level', 'user','arr_coin'));
  }

  public function getWalletGame(Request $request)
  {
    echo 'Dang lam';
  }

  public function getInterest(Request $request)
  {
    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $walletList = Money::where('Money_MoneyAction', 4)
      ->join('currency', 'Money_Currency', '=', 'currency.Currency_ID')
      ->join('moneyaction', 'Money_MoneyAction', '=', 'moneyaction.MoneyAction_ID')
      ->join('users', 'Money_User', 'users.User_ID')
      ->select('Money_ID', 'Money_User', 'users.User_Level', 'Money_MoneyAction', 'Money_USDT', 'Money_Currency', 'Money_USDTFee', 'Money_Time', 'currency.Currency_Name', 'Currency_Symbol', 'moneyaction.MoneyAction_Name', 'Money_Comment', 'Money_MoneyStatus', 'Money_Confirm', 'Money_Rate', 'Money_CurrentAmount');

    if ($request->id) {
      $walletList = $walletList->where('Money_ID', intval($request->id));
    }
    if ($request->user_id) {
      $walletList = $walletList->where('Money_User', $request->user_id);
    }
    if ($request->action) {
      $walletList = $walletList->where('Money_MoneyAction', $request->action);
    }
    if ($request->status) {
      //             $walletList = $walletList->where('Money_Confirm', $request->status);
      if ($request->status == 2) {
        $walletList = $walletList->where('Money_MoneyAction', 2)->where('Money_Confirm', 0);
      } else {
        $walletList = $walletList->where('Money_MoneyStatus', (int)$request->status);
      }
    }
    if ($request->datefrom and $request->dateto) {
      $walletList = $walletList->where('Money_Time', '>=', strtotime($request->datefrom))
        ->where('Money_Time', '<', strtotime($request->dateto) + 86400);
    }
    if ($request->datefrom and !$request->dateto) {
      $walletList = $walletList->where('Money_Time', '>=', strtotime($request->datefrom));
    }
    if (!$request->datefrom and $request->dateto) {
      $walletList = $walletList->where('Money_Time', '<', strtotime($request->dateto) + 86400);
    }
    if ($request->export) {
      ob_end_clean();
      ob_start();
      return Excel::download(new WalletExport($walletList->orderByDesc('Money_ID')->get()), 'WalletInterestExport.xlsx');

    }
    $walletList = $walletList->orderByDesc('Money_ID')->paginate(15);
    // dd($walletList);
    $action = DB::table('moneyaction')->get();
    return view('System.Admin.Interest', compact('walletList', 'action'));
  }

  public function getWithdraw(Request $request)
  {
    $withdrawCofirm = Money::join('users', 'Money_User', 'users.User_ID')
      ->where('Money_MoneyAction', 2)
      ->select('Money_ID', 'Money_User', 'Money_USDT', 'Money_Time', 'Money_Rate', 'Money_Confirm', 'users.User_Level');
    if ($request->email) {
      $searchuserID = User::where('User_Email', $request->email)->value('User_ID');
      $withdrawCofirm = $withdrawCofirm->where('Money_User', $searchuserID);
    }
    if ($request->id) {
      $withdrawCofirm = $withdrawCofirm->where('Money_ID', intval($request->id));
    }
    if ($request->user_id) {
      $withdrawCofirm = $withdrawCofirm->where('Money_User', $request->user_id);
    }
    if (isset($request->status)) {
      if ($request->status != 2) {
        $withdrawCofirm = $withdrawCofirm->where('Money_Confirm', $request->status);
      }
    }
    if ($request->datefrom and $request->dateto) {
      $withdrawCofirm = $withdrawCofirm->where('Money_Time', '>=', strtotime($request->datefrom))
        ->where('Money_Time', '<', strtotime($request->dateto) + 86400);
    }
    if ($request->datefrom and !$request->dateto) {
      $withdrawCofirm = $withdrawCofirm->where('Money_Time', '>=', strtotime($request->datefrom));
    }
    if (!$request->datefrom and $request->dateto) {
      $withdrawCofirm = $withdrawCofirm->where('Money_Time', '<', strtotime($request->dateto) + 86400);
    }
    $withdrawCofirm = $withdrawCofirm->orderByDesc('Money_ID')->paginate(15);
    return view('System.Admin.Withdraw', compact('withdrawCofirm'));
  }


  protected function getHttp($url)
  {
    $client = new Client();
    $response = $client->get($url);
    return json_decode($response->getBody());
  }

  public function getProfit()
  {
    return view('System.Admin.Confirm-Profit');
  }

  //get Pay daily INterest
  public function getPayDailyInterest(Request $request)
  {
    $level = array(1 => 'Admin', 0 => 'User', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $profitCofirm = Money::join('users', 'Money_User', 'users.User_ID')
      ->where('Money_MoneyAction', 4)
      ->select('Money_ID', 'Money_User', 'Money_USDT', 'Money_Time', 'Money_Rate', 'Money_Confirm', 'users.User_Level', 'users.User_WalletGTC', 'Money_Confirm_Time');

    if ($request->email) {
      $searchuserID = User::where('User_Email', $request->email)->value('User_ID');
      $profitCofirm = $profitCofirm->where('Money_User', $searchuserID);
    }
    if ($request->id) {
      $profitCofirm = $profitCofirm->where('Money_ID', $request->id);
    }

    if ($request->wallet_status != null) {
      if ($request->wallet_status == 1) {
        $profitCofirm = $profitCofirm->where('users.User_WalletGTC', '!=', null);
      }
      if ($request->wallet_status == 0) {
        $profitCofirm = $profitCofirm->where('users.User_WalletGTC', null);
      }
    }
    if ($request->user_id) {
      $profitCofirm = $profitCofirm->where('Money_User', $request->user_id);
    }

    if (isset($request->status)) {
      $profitCofirm = $profitCofirm->where('Money_Confirm', $request->status);
    }

    if ($request->datefrom and $request->dateto) {
      $profitCofirm = $profitCofirm->where('Money_Time', '>=', strtotime($request->datefrom))
        ->where('Money_Time', '<', strtotime($request->dateto) + 86400);
    }
    if ($request->datefrom and !$request->dateto) {
      $profitCofirm = $profitCofirm->where('Money_Time', '>=', strtotime($request->datefrom));
    }
    if (!$request->datefrom and $request->dateto) {
      $profitCofirm = $profitCofirm->where('Money_Time', '<', strtotime($request->dateto) + 86400);
    }

    if ($request->export) {
      Excel::create('Admin-Pay-Interest-' . date('YmdHis'), function ($excel) use ($profitCofirm, $level) {
        $excel->sheet('report', function ($sheet) use ($profitCofirm, $level) {
          $sheet->appendRow(array(
            'Interest ID', 'Interest ID', 'User Level', 'Interest Amount', 'Money Rate', 'Interest Time', 'Confirm Time', 'Update Wallet', 'Status'
          ));

          $profitCofirm->chunk(2000, function ($rows) use ($sheet, $level) {
            foreach ($rows as $row) {
              if ($row->Money_Confirm == 1) {
                $row->Money_Confirm = "Confirmed";
              } elseif ($row->Money_Confirm == 0) {
                $row->Money_Confirm = "Pending";
              } else {
                $row->Money_Confirm = "Cancel";
              }
              if ($row->User_WalletGTC) {
                $row->User_WalletGTC = 'Updated';
              } else {
                $row->User_WalletGTC = 'None';
              }
              $sheet->appendRow(array(
                $row->Money_ID, $row->Money_User, $level[$row->User_Level], $row->Money_USDT + 0, $row->Money_Rate, date('Y-m-d H:i:s', $row->Money_Time), $row->Money_Confirm_Time, $row->User_WalletGTC, $row->Money_Confirm
              ));
            }
          });
        });
      })->download('xlsx');
    }
    $profitCofirm = $profitCofirm->orderByDesc('Money_ID')->paginate(25);
    $walletBalance = $this->getHttp("http://trustexc.com/api/get_balance");
    return view('System.Admin.Confirm-Profit', compact('profitCofirm', 'walletBalance'));
  }

  //Log Mail List
  public function getLogMail(Request $request)
  {
    $logMails = Log::join('users', 'Log_User', 'users.User_ID')
      ->select('User_Email', 'Log_User', 'Log_Comment', 'Log_CreatedAt', 'Log_User', 'Log_Action', 'Log_ID');
    if ($request->UserID) {
      $logMails = $logMails->where('Log_User', $request->UserID);
    }
    if ($request->Email) {
      $logMails = $logMails->where('User_Email', $request->Email);
    }
    if ($request->Content) {
      $logMails = $logMails->where('Log_Comment', 'like', "%$request->Content%");
    }
    $logMails = $logMails->orderByDesc('Log_CreatedAt')->paginate(15);
    return view('System.Admin.Log-Mail', compact('logMails'));
  }

  public function getWalletDetail($id)
  {

    $detail = Money::Join('currency', 'Money_Currency', 'Currency_ID')->Join('users', 'Money_User', 'User_ID')->join('moneyaction', 'MoneyAction_ID', 'Money_MoneyAction')->where('Money_ID', $id)->first();
    if (Input::get('confirm')) {
      if (Session('user')->User_Level != 1 && Session('user')->User_Level != 2) {
        return redirect()->back();
      }

      if (Input::get('confirm') == 1) {
        $checkConfirm = Money::getCheckConfirm($id);
        //ghi log
        $cmt_log = "Confirm Money ID: $checkConfirm->Money_ID";
        Log::insertLog(Session('user')->User_ID, "Confirm Money", 0, $cmt_log);

        if ($checkConfirm && $checkConfirm->Money_Confirm == 0) {
          // Update
          if (($checkConfirm->Money_CurrencyTo == 1 || $checkConfirm->Money_CurrencyTo == 2)) {

            // kiểm tra ví đó nội sàn hay ngoại sàn
            $address = DB::table('address')->where('Address_Currency', $checkConfirm->Money_CurrencyTo)->where('Address_Address', $checkConfirm->Money_Address)->first();
            if ($address) {
              return 1;
              // chuyển tiền nội sàn coinbase
              Money::where('Money_ID', $id)->update(['Money_Confirm' => 1]);

              $Currency = DB::table('currency')->where('Currency_ID', $checkConfirm->Money_Currency)->first();
              $rate = $this->coinbase()->getSellPrice($Currency->Currency_Symbol . '-USD')->getamount();
              // nạp tiền cho user cần chuyển
              $moneyArray = array(
                'Money_User' => $address->Address_User,
                'Money_USDT' => abs($checkConfirm->Money_USDT),
                'Money_USDTFee' => 0,
                'Money_Time' => time(),
                'Money_Comment' => 'Deposit ' . $Currency->Currency_Symbol,
                'Money_MoneyAction' => 1,
                'Money_MoneyStatus' => 1,
                'Money_Rate' => $rate,
                'Money_Address' => 'Deposit from UserID: ' . $checkConfirm->Money_User,
                'Money_Currency' => $checkConfirm->Money_Currency,
              );
              DB::table('money')->insert($moneyArray);
              return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Confirm Successfully.']);
            } else {

              // rút tiền ra khỏi coinbase
              $Currency = $checkConfirm->Money_CurrencyTo == 1 ? "BTC" : "ETH";
              $amountReal = abs($checkConfirm->Money_USDT);

              if ($checkConfirm->Money_CurrencyTo == 2) {
                $cb_account = 'ETH';
                $rate = $this->coinbase()->getSellPrice('ETH-USD')->getamount();
                $newMoney = new CB_Money($amountReal, CurrencyCode::ETH);
              } elseif ($checkConfirm->Money_CurrencyTo == 1) {
                $cb_account = 'BTC';
                $rate = $this->coinbase()->getSellPrice('BTC-USD')->getamount();
                $newMoney = new CB_Money($amountReal, CurrencyCode::BTC);
              } else {
                return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Currency Error!']);
              }
              if ($checkConfirm->Money_MoneyAction == 2) {
                $comment = 'Send Interest';
              } else {
                $comment = 'Send Commission';
              }

              // Amount
              $transaction = Transaction::send([
                'toBitcoinAddress' => $checkConfirm->Money_Address,
                'amount' => $newMoney,
                'description' => $checkConfirm->Money_User . ' ' . $comment
              ]);

              $account = $this->coinbase()->getAccount($cb_account);

              try {
                $a = $this->coinbase()->createAccountTransaction($account, $transaction);

                Money::where('Money_ID', $id)->update(['Money_Confirm' => 1]);
                //Money::where('Money_ID',$id)->update(['Money_MoneyStatus'=>1]);


                return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Confirm withdraw success!']);
              } catch (\Exception $e) {
                //ghi log
                $cmt_log = "Confirm Error Money ID: $checkConfirm->Money_ID " . $e->getMessage();
                Log::insertLog(Session('user')->User_ID, "Confirm Error Money", 0, $cmt_log);
                return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => $e->getMessage()]);
              }
            }
          } elseif ($checkConfirm && ($checkConfirm->Money_CurrencyTo == 8 || $checkConfirm->Money_CurrencyTo == 9 || $checkConfirm->Money_CurrencyTo == 5)) {
            Money::where('Money_ID', $id)->update(['Money_Confirm' => 1, 'Money_MoneyStatus' => 1]);
            return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Confirm withdraw Token success!']);
          }
        } else {
          return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
        }
      } elseif (Input::get('confirm') == -1) {

        $checkConfirm = Money::getCheckConfirm($id);
        //ghi log
        $cmt_log = "Cancel Money ID: $checkConfirm->Money_ID";
        Log::insertLog(Session('user')->User_ID, "Cancel Money", 0, $cmt_log);
        if (!$checkConfirm && $checkConfirm->Money_Confirm != 0) {
          return redirect()->route('system.admin.getWallet')->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
        }
        $amountUSD = abs($checkConfirm->Money_USDT);
        Money::where('Money_ID', $id)->update(['Money_Confirm' => -1, 'Money_MoneyStatus' => -1]);

        if ($checkConfirm->Money_MoneyAction == 2) {
          //Cộng lại balance
          // $updateBalanceInterest = User::updateBalanceInterest($checkConfirm->Money_User, $amountUSD);
        } else {
          //Cộng lại balance
          // $updateBalanceInterest = User::updateCommission($checkConfirm->Money_User, $amountUSD);
        }
        //Gửi telegram thông báo Cancel Withdraw
        /*
                        $message = "Cancel Withdraw \n"
                                . "<b>Withdraw ID: </b>\n"
                                . "$id\n"
                                . "<b>Email Cancel: </b>\n"
                                . "$user->User_Email\n"
                                . "<b>Cancel Withdraw Time: </b>\n"
                                . date('d-m-Y H:i:s',time());

                        TelegramBotController::sendMessage($message);
                        */
        return redirect()->route('system.admin.getWallet')->with(['flash_type' => 'error', 'flash_message' => 'Cancel Success!']);
      } elseif (Input::get('confirm') == 2) {
        if (!Input::get('txid')) {
          return redirect()->route('system.admin.getWallet')->with(['flash_level' => 'error', 'flash_message' => 'Please enter Transaction hash to success!']);
        }

        Log::insertLog(Session('user')->User_ID, "Success Money", 0, 'Success Money ID: ' . $id);
        Money::where('Money_ID', $id)->update(['Money_Confirm' => 1, 'Money_MoneyStatus' => 1, 'Money_TXID' => Input::get('txid')]);

        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Only Confirm withdraw success!']);
      }

      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Please contact code!']);

    }
    return view('System.Admin.WalletDetail', compact('detail'));
  }

  public function getCheckConfirm($id)
  {

    $user = User::find(Session('user')->User_ID);
    if ($user->User_Level != 1 && $user->User_Level != 2) {
      return response()->json(['status' => false, 'message' => 'Permission!'], 200);
    }
    $checkConfirm = Money::join('moneyaction', 'MoneyAction_ID', 'Money_MoneyAction')->where('Money_ID', $id)->whereIn('Money_MoneyAction', [2, 15])->first();
    if ($checkConfirm && $checkConfirm->Money_Confirm == 0) {
      // Update
      $userPay = User::whereIn('User_Level', [0, 1, 4])->where('User_ID', $checkConfirm->Money_User)->first();
      if (!$userPay) {
        return response()->json(['status' => false, 'message' => 'User Don\'t Exist!'], 200);
      }

      $address = $checkConfirm->Money_Address;
      if (!$address) {
        return response()->json(['status' => false, 'message' => 'User Don\'t Have Address!'], 200);
      }
      return response()->json(['status' => true], 200);
    }

    return response()->json(['status' => false, 'message' => 'Money Error Or Confirmed!'], 200);

  }

  public function getConfirm($id)
  {

    $user = User::find(Session('user')->User_ID);
    if ($user->User_Level != 1 && $user->User_Level != 2) {
      return response()->json(['status' => false, 'message' => 'Permission!'], 200);
    }
    $checkConfirm = Money::join('moneyaction', 'MoneyAction_ID', 'Money_MoneyAction')->where('Money_ID', $id)->whereIn('Money_MoneyAction', [2, 15])->first();
    if ($checkConfirm && $checkConfirm->Money_Confirm == 0) {
      // Update
      $userPay = User::whereIn('User_Level', [0, 1, 4])->where('User_ID', $checkConfirm->Money_User)->first();
      if (!$userPay) {
        return response()->json(['status' => false, 'message' => 'User Don\'t Exist!'], 200);
      }

      $address = $checkConfirm->Money_Address;
      if (!$address) {
        return response()->json(['status' => false, 'message' => 'User Don\'t Have Address!'], 200);
      }
      //gửi Token
      if ($checkConfirm->Money_Currency == 5 || $checkConfirm->Money_Currency == 8) {
        $amountReal = $this->floorp((abs($checkConfirm->Money_CurrentAmount)), 4);
        Money::where('Money_ID', $id)->update(['Money_Confirm' => 1]);
        $log = Log::insertLog($user->User_ID, 'Confirm Send Coin', $amountReal, $userPay . ' Send ' . $checkConfirm->MoneyAction_Name . ' Success');
        //Gửi telegram thông báo User verify
        $message = "$checkConfirm->MoneyAction_Name to Address: $address\n"
          . "<b>User ID: </b>\n"
          . "$userPay->User_ID\n"
          . "<b>Email: </b>\n"
          . "$userPay->User_Email\n"
          . "<b>Amount: </b>\n"
          . $amountReal . " S4FX\n"
          . "<b>Send Coin Time: </b>\n"
          . date('d-m-Y H:i:s', time());

        // 				dispatch(new SendTelegramJobs($message, -1001194227603));

        return response()->json(['status' => true, 'message' => 'Confirm Successfully!'], 200);
      } else {
        // gửi ETH
        $amountReal = $this->floorp((abs($checkConfirm->Money_CurrentAmount)), 4);

        $cb_account = 'ETH';
        $rate = app('App\Http\Controllers\System\CoinbaseController')->coinbase()->getSellPrice('ETH-USD')->getamount();
        $newMoney = new CB_Money($amountReal, CurrencyCode::ETH);

        // Amount
        $transaction = Transaction::send([
          'toBitcoinAddress' => $address,
          'amount' => $newMoney,
          'description' => $userPay->User_ID . ' ' . $checkConfirm->MoneyAction_Name
        ]);

        $account = app('App\Http\Controllers\System\CoinbaseController')->coinbase()->getAccount($cb_account);
        try {
          $a = app('App\Http\Controllers\System\CoinbaseController')->coinbase()->createAccountTransaction($account, $transaction);
          Money::where('Money_ID', $id)->update(['Money_Confirm' => 1]);
          $log = Log::insertLog($user->User_ID, 'Confirm Send Coin', $amountReal, $userPay . ' Send ' . $checkConfirm->MoneyAction_Name . ' Success');

          //Gửi telegram thông báo User verify
          $message = "$checkConfirm->MoneyAction_Name to Address: $address\n"
            . "<b>User ID: </b>\n"
            . "$userPay->User_ID\n"
            . "<b>Email: </b>\n"
            . "$userPay->User_Email\n"
            . "<b>Amount: </b>\n"
            . $amountReal . " ETH\n"
            . "<b>Send Coin Time: </b>\n"
            . date('d-m-Y H:i:s', time());

          // 				dispatch(new SendTelegramJobs($message, -1001194227603));

          return response()->json(['status' => true, 'message' => 'Confirm Successfully!'], 200);
        } catch (\Exception $e) {
          // 				Money::where('Money_ID',$id)->update(['Money_Confirm'=>-1]);
          $log = Log::insertLog($user->User_ID, 'Confirm Error Send Coin', $amountReal, $userPay . ' Send ' . $checkConfirm->MoneyAction_Name . ' Error! ' . $e->getMessage());
          return response()->json(['status' => false, 'message' => $e->getMessage()], 200);
        }
      }
    }

    return response()->json(['status' => false, 'message' => 'Money Error!'], 200);

  }

  function floorp($val, $precision)
  {
    $mult = pow(10, $precision); // Can be cached in lookup table
    return floor($val * $mult) / $mult;
  }

  public function getStatistical(Request $request)
  {
    $where = '';
    if ($request->from) {
      $from = strtotime(date('Y-m-d', strtotime(Input::get('from'))));
      $where .= ' AND Money_Time >= ' . $from;
    }
    if ($request->to) {
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $where .= ' AND Money_Time < ' . $to;
    }
    $Statistic = Money::StatisticTotal($where);
    $staticUser = [];

    $Total = Money::StatisticTotal($where);
    // dd($Statistic->get(),$Total->get());
    if ($request->User_ID) {
      $staticUser = Money::getStatistic($where)->where('Money_User', $request->User_ID)->get();
    }

    if ($request->User_Level) {
      $staticUser = Money::getStatistic($where)->where('User_Level', $request->User_Level)->get();
    }

    if ($request->User_Tree) {
      $staticUser = Money::getStatistic($where)->where('User_Tree', 'LIKE', '%' . $request->User_Tree . '%')->get();
    }


    $Statistic = $Statistic->get();
    // $staticUser = $staticUser->get();
    // $Statistic = $Statistic->paginate(15);
    // $Total = $Total->get()[0];
    // $staticUser = $staticUser->paginate(25);

    // $markets  = Markets::where('Status', 1)->get();
    //số trứng mua + tổng tiền chi
    // foreach($Statistic as $st){
    //     $count_eggs_buy = 0;
    //     $total_price_buy_eggs = 0;
    //     $total_price_buy_eggs_gold = 0;
    //      //Số trứng bán + tổng tiền nhận
    //     $count_eggs_sell = 0;
    //     $total_price_sell_eggs = 0;
    //     $total_price_sell_eggs_gold = 0;

    //     $count_eggs_sell_system = 0;
    //     $total_price_sell_eggs_system = 0;

    //     foreach($markets as $mak){
    //         if(isset($mak->Sold[0]['user']) && ($mak->Sold[0]['user'] == $st['Money_User'])){
    //             $count_eggs_buy++;
    //             $total_price_buy_eggs = $total_price_buy_eggs + $mak['PriceEUSD'];
    //             $total_price_buy_eggs_gold = $total_price_buy_eggs_gold + $mak['PriceGold'];
    //         }
    //          //Số trứng bán + tổng tiền nhận
    //         if($mak['UserSell'] == $st['Money_User']){
    //             $count_eggs_sell++;
    //             $total_price_sell_eggs = $total_price_sell_eggs + $mak['PriceEUSD'];
    //             $total_price_sell_eggs_gold = $total_price_sell_eggs_gold + $mak['PriceGold'];
    //         }
    //          //Số trứng bán cho hệ thống + tiền nhận (đã trừ phí) + Phí thu hồi trứng
    //         if($mak['UserSell'] == $st['Money_User'] && $mak['Sold'][0]['user'] == 'eggsbook'){
    //             $count_eggs_sell_system++;
    //             $total_price_sell_eggs_system = $total_price_sell_eggs_system + $mak['PriceEUSD'];
    //         }
    //     }
    //     $st['count_eggs_buy'] = $count_eggs_buy;
    //     $st['total_price_buy_eggs'] = $total_price_buy_eggs;
    //     $st['total_price_buy_eggs_gold'] = $total_price_buy_eggs_gold;
    //     //Số trứng bán + tổng tiền nhận
    //     $st['count_eggs_sell'] = $count_eggs_sell;
    //     $st['total_price_sell_eggs'] = $total_price_sell_eggs;
    //     $st['total_price_sell_eggs_gold'] = $total_price_sell_eggs_gold;
    //     //Số trứng bán cho hệ thống + tiền nhận (đã trừ phí) + Phí thu hồi trứng
    //     $st['count_eggs_sell_system'] = $count_eggs_sell_system;
    //     $st['total_price_sell_eggs_system'] = $total_price_sell_eggs_system*(1-$this->fee_sell_egg_system);
    //     $st['total_fee_price_sell_eggs_system'] = $total_price_sell_eggs_system*$this->fee_sell_egg_system;
    //     //Số trứng nở + số trứng hư
    //     $count_open_eggs = Eggs::where('Owner', $st['Money_User'])->where('Status', 2)->count();
    //     $count_bad_eggs = Eggs::where('Owner', $st['Money_User'])->where('Status', -1)->count();

    //     $st['count_open_eggs'] = $count_open_eggs;
    //     $st['count_bad_eggs'] = $count_bad_eggs;
    // }
    $rate_ebp = $this->rate_ebp;


    // dd($Statistic);
    return view('System.Admin.Statistical', compact('Statistic', 'rate_ebp', 'staticUser'));
  }

  public function getStatisticalGame(Request $request)
  {
    $where = '';
    if ($request->from && $request->to) {
      $to = date('Y-m-d', strtotime(Input::get('to')));
      $from = date('Y-m-d', strtotime(Input::get('from')));
      $where .= ' AND statistical_Time >= "' . $from . ' 00:00:00" AND statistical_Time < "' . $to . ' 00:00:00"';
    }

    $statistical = [];

    if ($request->User_ID) {
      $statistical = Statistical::selectRaw('statistical_User, statistical_Time, statistical_User as UserID,
            (SELECT SUM(amount) From balance_game Where `currency` = 3 AND user = UserID) as balance_eusd,
            (SELECT SUM(amount) From balance_game Where `currency` = 9 AND user = UserID) as balance_gold,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 3 AND `Money_MoneyAction` = 55 AND Money_User = UserID) as deposit_battle_game_eusd,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 9 AND `Money_MoneyAction` = 55 AND Money_User = UserID) as deposit_battle_game_gold,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 3 AND `Money_MoneyAction` = 56 AND Money_User = UserID) as withdraw_battle_game_eusd,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 9 AND `Money_MoneyAction` = 56 AND Money_User = UserID) as withdraw_battle_game_gold,
            SUM(IF(`statistical_Currency` = 3' . $where . ', ROUND(`statistical_TotalWin`,8), 0)) as total_win_eusd,
            SUM(IF(`statistical_Currency` = 9' . $where . ', ROUND(`statistical_TotalWin`,8), 0)) as total_win_gold,
            SUM(IF(`statistical_Currency` = 3' . $where . ', ROUND(`statistical_TotalLost`,8), 0)) as total_lose_eusd,
            SUM(IF(`statistical_Currency` = 9' . $where . ', ROUND(`statistical_TotalLost`,8), 0)) as total_lose_gold
                ')
        ->groupBy('statistical_User');
      $statistical = $statistical->where('statistical_User', $request->User_ID)->get();

      $statistical[0]->balance_eusd = User::getBalanceGame($statistical[0]->UserID, 3);
      $statistical[0]->balance_gold = User::getBalanceGame($statistical[0]->UserID, 9);
    }

    if ($request->User_Tree) {
      $statistical = Statistical::join('users', 'User_ID', 'statistical_User')->selectRaw('statistical_User, statistical_Time, statistical_User as UserID,
            (SELECT SUM(amount) From balance_game Where `currency` = 3 AND user = UserID) as balance_eusd,
            (SELECT SUM(amount) From balance_game Where `currency` = 9 AND user = UserID) as balance_gold,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 3 AND `Money_MoneyAction` = 55 AND Money_User = UserID) as deposit_battle_game_eusd,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 9 AND `Money_MoneyAction` = 55 AND Money_User = UserID) as deposit_battle_game_gold,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 3 AND `Money_MoneyAction` = 56 AND Money_User = UserID) as withdraw_battle_game_eusd,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 9 AND `Money_MoneyAction` = 56 AND Money_User = UserID) as withdraw_battle_game_gold,
            SUM(IF(`statistical_Currency` = 3' . $where . ', ROUND(`statistical_TotalWin`,8), 0)) as total_win_eusd,
            SUM(IF(`statistical_Currency` = 9' . $where . ', ROUND(`statistical_TotalWin`,8), 0)) as total_win_gold,
            SUM(IF(`statistical_Currency` = 3' . $where . ', ROUND(`statistical_TotalLost`,8), 0)) as total_lose_eusd,
            SUM(IF(`statistical_Currency` = 9' . $where . ', ROUND(`statistical_TotalLost`,8), 0)) as total_lose_gold
                ')
        ->groupBy('statistical_User');
      $statistical = $statistical->where('users.User_Tree', 'LIKE', '%' . $request->User_Tree . '%')->paginate(100);

      for ($i = 0; $i < count($statistical); $i++) {
        $statistical[$i]->balance_eusd = User::getBalanceGame($statistical[$i]->UserID, 3);
        $statistical[$i]->balance_gold = User::getBalanceGame($statistical[$i]->UserID, 9);
      }

      // $statistical[0]->balance_eusd = User::getBalanceGame($statistical[0]->UserID, 3);
      // $statistical[0]->balance_gold = User::getBalanceGame($statistical[0]->UserID, 9);
    }

    if ($request->export) {
      $statistical = Statistical::join('users', 'statistical_User', 'User_ID')->selectRaw('statistical_User, statistical_Time, statistical_User as UserID,
            (SELECT SUM(amount) From balance_game Where `currency` = 3 AND user = UserID) as balance_eusd,
            (SELECT SUM(amount) From balance_game Where `currency` = 9 AND user = UserID) as balance_gold,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 3 AND `Money_MoneyAction` = 55 AND Money_User = UserID) as deposit_battle_game_eusd,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 9 AND `Money_MoneyAction` = 55 AND Money_User = UserID) as deposit_battle_game_gold,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 3 AND `Money_MoneyAction` = 56 AND Money_User = UserID) as withdraw_battle_game_eusd,
            (SELECT SUM(Money_USDT) From money Where `Money_Currency` = 9 AND `Money_MoneyAction` = 56 AND Money_User = UserID) as withdraw_battle_game_gold,
            SUM(IF(`statistical_Currency` = 3' . $where . ', ROUND(`statistical_TotalWin`,8), 0)) as total_win_eusd,
            SUM(IF(`statistical_Currency` = 9' . $where . ', ROUND(`statistical_TotalWin`,8), 0)) as total_win_gold,
            SUM(IF(`statistical_Currency` = 3' . $where . ', ROUND(`statistical_TotalLost`,8), 0)) as total_lose_eusd,
            SUM(IF(`statistical_Currency` = 9' . $where . ', ROUND(`statistical_TotalLost`,8), 0)) as total_lose_gold
                ')->whereIn('users.User_Level', [0, 4])
        ->groupBy('statistical_User');
      ob_end_clean();
      ob_start();
      return Excel::download(new StatisticalGame($statistical->get()), 'StatisticalGame.xlsx');
    }

    // $statistical = $statistical->paginate(50);

    return view('System.Admin.StatisticalGame', compact('statistical'));
  }

  public function getLoginByID($id)
  {
    $user = session('user');
    if ($user->User_Level == 1 || $user->User_Level == 2) {
      $userLogin = User::find($id);


      //$user = Auth::user();
      $token = $userLogin->createToken('EGGSBOOKBACKDOOR')->accessToken;

      $arrReturn = array('status' => true, 'token' => $token);

      $cmt_log = "Login ID User: " . $id;
      Log::insertLog(Session('user')->User_ID, "Login", 0, $cmt_log);

      // return redirect()->route('Dashboard', ['token'=>$]);
      // dd(config('url.system').'Gsd354Sdfhr4/oiewh3454Has54?token='.$token);
      //return redirect()->away(config('url.betasystem').'Gsd354Sdfhr4/oiewh3454Has54?token='.$token);
      return redirect()->away('https://123betnow.net/Gsd354Sdfhr4/oiewh3454Has54?token=' . $token);
      return redirect()->away(config('url.system') . 'Gsd354Sdfhr4/oiewh3454Has54?token=' . $token);
      // return redirect::to(config('url.system').'system/dashboard');
      // return redirect()->route('Dashboard')->with(['flash_level' => 'success', 'flash_message' => 'Login Success']);
      if (Auth::attempt(['User_Email' => $userLogin->User_Email, 'password' => $userLogin->User_PasswordNotHash])) {

      }
    } else {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
    }
  }

  public function getEditMailByID(Request $req)
  {
    // dd($req->new_email);
    $user = session('user');
    if ($user->User_Level != 1 && $user->User_Level != 3) {
      return -1;
    }
    $check_id = User::where('User_ID', $req->id_user)->first();
    if ($check_id) {
      $check_mail = User::where('User_Email', $req->new_email)->first();
      if (!$check_mail) {
        $cmt_log = "Change mail: " . $check_id->User_Email . " -> " . $req->new_email;
        Log::insertLog(Session('user')->User_ID, "Change Mail", 0, $cmt_log);
        $check_id->User_Email = $req->new_email;
        $check_id->save();
        return 1;
      }
      return 0;
    }
    return -1;
  }

  public function postCheckInterestList(Request $req)
  {
    $user = Session('user');
    return response()->json(['status' => false, 'message' => 'Error!'], 200);
    if ($user->User_Level != 1) {
      return response()->json(['status' => false, 'message' => 'Error!']);
    }
    $arrIDMoney = $req->arr_check;
    $listID = implode(',', $arrIDMoney);
    if ($req->type == 1) {
      $log = Log::insertLog($user->User_ID, 'Confirm List', 0, 'Confirm Interest List: ' . $listID);
      foreach ($arrIDMoney as $id) {
        $detail = Money::join('users', 'Money_User', 'User_ID')->where('Money_ID', $id)->first();
        /*
        if ($detail->Money_Confirm == 0) {
                            if(!$detail->User_WalletAddress){
                                continue;
                            }
                            $transferSOX = app('App\Http\Controllers\Cron\CronController')->TransferToAddress('SXVXhGaNrGXuEmhzX2vVq3itzk2P9syCd2', $detail->Money_USDT, $detail->User_WalletAddress, 'Send Interest');
                            if($transferSOX){
                                $detail->Money_Confirm = 1;
                                $detail->save();
                            }
                        }
        */
      }
      return response()->json(['status' => true, 'message' => 'Send SOX List ' . $listID . ' Success!']);
    } elseif ($req->type == -1) {
      $log = Log::insertLog($user->User_ID, 'Cancel List', 0, 'Cancel Interest List: ' . $listID);
      $getListUnConfirm = Money::whereIn('Money_ID', $arrIDMoney)->where('Money_Confirm', 0)->pluck('Money_ID')->toArray();
      $updateList = Money::whereIn('Money_ID', $getListUnConfirm)->update(['Money_Confirm' => -1]);

      return response()->json(['status' => true, 'message' => 'Cancel List: ' . $listID . ' Success!']);
    }
    return response()->json(['status' => false, 'message' => 'Action Error!']);
  }

  public function getLogSOX()
  {
    $log_SOX = DB::table('log_sox')->orderByDesc('Log_Sox_Time')->paginate(15);
    return view('System.Admin.Log-SOX', compact('log_SOX'));
  }

  public function getActiveMail($id)
  {
    $check_user = User::where('User_ID', $id)->first();
    if (!$check_user) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'User ID is not exits!']);
    }
    $cmt_log = "Active Mail ID User: " . $id;
    Log::insertLog(Session('user')->User_ID, "Active Mail", 0, $cmt_log);
    $check_user->User_EmailActive = 1;
    $check_user->save();
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Active mail!']);
  }

  public function getEditUser($id)
  {
    $data['user_list'] = User::where('User_ID', $id)->join('user_level', 'User_Level_ID', 'User_Level')->first();
    $data['user_level'] = DB::table('user_level')->orderBy('User_Level_ID')->get();
    $data['user_agency_level'] = DB::table('user_agency_level')->orderBy('user_agency_level_ID')->get();
    return view('System.Admin.EditUser', $data);
  }

  public function postEditUser(Request $req)
  {
    $user_info = User::where('User_ID', $req->id)
      ->join('user_level', 'User_Level_ID', 'User_Level')
      ->first();
    if (!$user_info) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'User ID is not exits!']);
    }
    $req->validate([
      'name' => 'max:191',
      'email' => 'required|email|max:191',
      'status_mail' => 'required|between:0,1|integer',
      'agency_level' => 'required|integer',
      'phone' => 'max:20',
      'parent' => 'required|integer|min:1',
      'tree' => 'required|min:1',
      'level' => 'required|integer|between:0,5',
      'status' => 'required|integer|between:0,1',
    ]);
    $check_email = User::where('User_ID', '<>', $req->id)->where('User_Email', $req->email)->first();
    if ($check_email) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Email is exits!']);
    }
    $check_parent = User::where('User_ID', $req->parent)->first();
    if (!$check_parent) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Parent ID is not exits!']);
    }
    $check_agency_level = DB::table('user_agency_level')->where('user_agency_level_ID', $req->agency_level)->first();
    if (!$check_agency_level) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Agency Level is not exits!']);
    }
    $check_level = DB::table('user_level')->where('User_Level_ID', $req->level)->first();
    if (!$check_level) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Level is not exits!']);
    }
    $arr_status_mail = ['0' => 'Not Active', '1' => 'Active'];
    $stt_mail_old = $arr_status_mail[$user_info->User_EmailActive];
    $stt_mail_new = $arr_status_mail[$req->status_mail];
    $arr_status = ['0' => 'Block', '1' => 'Active'];
    $stt_old = $arr_status[$user_info->User_Status];
    $stt_new = $arr_status[$req->status];

    $cmt_log = "<p>Edit User $req->id:</p>
        <p>Name: $user_info->User_Name -> $req->name</p>
        <p>Email: $user_info->User_Email -> $req->email</p>
        <p>Status Mail: $stt_mail_old -> $stt_mail_new</p>
        <p>Phone: $user_info->User_Phone -> $req->phone</p>
        <p>Parent: $user_info->User_Parent -> $req->parent</p>
        <p>Tree: $user_info->User_Tree -> $req->tree</p>
        <p>Level: $user_info->User_Level_Name -> $check_level->User_Level_Name</p>
        <p>Agency Level: $user_info->User_Agency_Level -> $req->agency_level</p>
        <p>Status: $stt_old -> $stt_new</p>
        ";
    if ($req->new_password) {
      $cmt_log .= "<p>Edit New Password</p>";
      $user_info->User_Password = Hash::make($req->new_password);
    }
    dd($req->agency_level);
    $user_info->User_Name = $req->name;
    $user_info->User_Email = $req->email;
    $user_info->User_EmailActive = $req->status_mail;
    $user_info->User_Phone = $req->phone;
    $user_info->User_Parent = $req->parent;
    $user_info->User_Tree = $req->tree;
    $user_info->User_Level = $req->level;
    $user_info->User_Agency_Level = $req->agency_level;
    $user_info->User_Status = $req->status;
    $user_info->save();
    Log::insertLog(Session('user')->User_ID, "Edit User", 0, $cmt_log);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Edit User Success!']);
  }

  public function getEditInvestment($id)
  {
    $data['info_invest'] = Investment::where('investment_ID', $id)->first();
    $data['currency'] = DB::table('currency')->get();
    $data['package'] = DB::table('package')->get();
    $data['package_time'] = DB::table('package_time')->get();
    return view('System.Admin.EditInvestment', $data);
  }

  public function postEditInvestment(Request $req)
  {
    $invest_info = Investment::where('investment_ID', $req->investment_ID)
      ->join('package', 'package_ID', 'investment_Package')
      ->first();
    if (!$invest_info) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Investment ID is not exits!']);
    }
    $req->validate([
      'investment_User' => 'required|max:191',
      'investment_Amount' => 'required|numeric',
      'investment_Package' => 'required|numeric',
      'investment_Currency' => 'required|numeric',
      'investment_Rate' => 'required|numeric',
      'investment_Package_Time' => 'required|numeric',
      'investment_Time' => 'required|date_format:Y-m-d H:i:s',
      'investment_Status' => 'required|numeric',
    ]);
    $check_package = DB::table('package')->where('package_ID', $req->investment_Package)->first();
    if (!$check_package) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Package ID is not exits!']);
    }
    $check_currency = DB::table('currency')->where('Currency_ID', $req->investment_Currency)->first();
    if (!$check_currency) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Currency ID is not exits!']);
    }
    $check_package_time = DB::table('package_time')->where('time_Month', $req->investment_Package_Time)->first();
    if (!$check_package_time) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Package Time ID is not exits!']);
    }
    if ($req->investment_Status != 1 && $req->investment_Status != 2 && $req->investment_Status != 0 && $req->investment_Status != -1) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Status is not exits!']);
    }
    $arr_coin = ['1' => 'BTC', '2' => 'ETH', '5' => 'USDX', '8' => 'SOX'];
    $datetime_old = date('Y-m-d H:i:s', $invest_info->investment_Time);
    $min_old = number_format($invest_info->package_Min);
    $max_old = number_format($invest_info->package_Max);
    $min_new = number_format($check_package->package_Min);
    $max_new = number_format($check_package->package_Max);
    $cur_old = $arr_coin[$invest_info->investment_Currency];
    $cur_new = $arr_coin[$req->investment_Currency];
    $arr_status = ['0' => 'Waiting', '1' => 'Active', '2' => 'Refunded', '-1' => 'Admin Cancel'];
    $status_old = $arr_status[$invest_info->investment_Status];
    $status_new = $arr_status[$req->investment_Status];
    $cmt_log = "<p>Edit Investment $req->investment_ID:</p>
            <p>User ID: $invest_info->investment_User -> $req->investment_User</p>
            <p>Amount: $invest_info->investment_Amount -> $req->investment_Amount</p>
            <p>Package: $min_old$ - $max_old$ ($invest_info->package_Note/Month) -> $min_new$ - $max_new$ ($check_package->package_Note/Month)</p>
            <p>Currency: $cur_old -> $cur_new</p>
            <p>Rate: $invest_info->investment_Rate -> $req->investment_Rate</p>
            <p>Package Time: $invest_info->investment_Package_Time Month -> $req->investment_Package_Time Month</p>
            <p>Time: $datetime_old -> $req->investment_Time</p>
            <p>Status: $status_old -> $status_new</p>
        ";
    $invest_info->investment_User = $req->investment_User;
    $invest_info->investment_Amount = $req->investment_Amount;
    $invest_info->investment_Package = $req->investment_Package;
    $invest_info->investment_Currency = $req->investment_Currency;
    $invest_info->investment_Rate = $req->investment_Rate;
    $invest_info->investment_Package_Time = $req->investment_Package_Time;
    $invest_info->investment_Time = $req->investment_Time;
    $invest_info->investment_Status = $req->investment_Status;
    $invest_info->save();
    Log::insertLog(Session('user')->User_ID, "Edit Investment", 0, $cmt_log);
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Edit Investment Success!']);
  }

  public function getFetchDataMoney($id)
  {
    $row_has_exist = Money::where('Money_ID', $id)->first();
    $row_has_exist->Money_Time = Date('Y-m-d H:i:s', $row_has_exist->Money_Time);
    if (!$row_has_exist) {
      return response()->json([
        'status' => 500
      ]);
    } else {
      $currency = DB::table('currency')->where('Currency_Active', 1)->get();
      $action = DB::table('moneyaction')->get();
      return response()->json([
        'status' => 200,
        'list' => $row_has_exist,
        'currency' => $currency,
        'action' => $action,
      ]);
    }
  }

  public function putEditDataMoney(Request $req, $id)
  {

    $row_has_exist = Money::where('Money_ID', $id)->first();
    if (!$row_has_exist) {
      return response()->json([
        'status' => 500
      ]);

    }
    $arg_log = $row_has_exist->toArray();
    $req->validate([
      // 'Money_User' =>  'required|numeric',
      'Money_USDT' => 'required|numeric',
      'Money_USDTFee' => 'required|numeric',
      // 'Money_SaleBinary' =>  'numeric',
      // 'Money_Investment' =>  'numeric',
      // 'Money_Borrow' =>  'numeric',
      'Money_Time' => 'required|date',
      'Money_Comment' => 'required',
      'Money_MoneyAction' => 'required|numeric',
      'Money_MoneyStatus' => 'required|numeric',
      // 'Money_Token' =>  'numeric',
      // 'Money_Address' =>  'required',
      'Money_Currency' => 'required|numeric',
      'Money_CurrentAmount' => 'required|numeric',
      'Money_Rate' => 'required|numeric',
      'Money_Confirm' => 'required|numeric',
      // 'Money_Confirm_Time' =>  'date',

    ]
                  );
    // dd(response()->json($arg_log));

    //update row
    // $row_has_exist->Money_User = $req->Money_User;
    $row_has_exist->Money_USDT = $req->Money_USDT;
    $row_has_exist->Money_USDTFee = $req->Money_USDTFee;
    $row_has_exist->Money_SaleBinary = $req->Money_SaleBinary;
    $row_has_exist->Money_Investment = $req->Money_Investment;
    $row_has_exist->Money_Borrow = $req->Money_Borrow;
    $row_has_exist->Money_Time = strtotime($req->Money_Time);
    $row_has_exist->Money_Comment = $req->Money_Comment;
    $row_has_exist->Money_MoneyAction = $req->Money_MoneyAction;
    $row_has_exist->Money_MoneyStatus = $req->Money_MoneyStatus;
    $row_has_exist->Money_Token = $req->Money_Token;
    $row_has_exist->Money_Address = $req->Money_Address;
    $row_has_exist->Money_Currency = $req->Money_Currency;
    $row_has_exist->Money_CurrentAmount = $req->Money_CurrentAmount;
    $row_has_exist->Money_Rate = $req->Money_Rate;
    $row_has_exist->Money_Confirm = $req->Money_Confirm;
    $row_has_exist->Money_Confirm_Time = $row_has_exist->Money_Confirm_Time;
    $row_has_exist->save();
    //Write log

    $after_req = $req->toArray();
    unset($after_req['_method']);
    unset($after_req['_token']);

    $log = DB::table('log_money')->insert([
      'log_money_User' => Session('user')->User_ID,
      'log_money_Beforechange' => json_encode($arg_log),
      'log_money_Afterchange' => json_encode($after_req)
    ]);
    //mess
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "#$id Data change success!"]);
  }

  public function getBalance(Request $req)
  {
    $a = User::getBalance($req->userID);
    dd($a);
  }


  public function getBlockInterest(Request $req, $id)
  {
    $user = Session('user');
    if ($user->User_Level == 1 || $user->User_Level == 3) {

      $check_blockInterest = User::where('User_ID', $id)->first();
      if ($check_blockInterest->User_IsInterest == 0) {
        $cmt_log = "Block Interest ID User: " . $id;
        Log::insertLog(Session('user')->User_ID, "Block Interest", 0, $cmt_log);

        $updateBlockInterest = User::where('User_ID', $id)->update([
          'User_IsInterest' => 1
        ]);
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Block Interest Success!']);
      } else {
        $cmt_log = "Unblocked Interest ID User: " . $id;
        Log::insertLog(Session('user')->User_ID, "Unblocked Interest", 0, $cmt_log);

        $updateBlockInterest = User::where('User_ID', $id)->update([
          'User_IsInterest' => 0
        ]);
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Unlocked Interest Success!']);
      }

    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
  }

  public function getAdminPackage()
  {
    $packageList = DB::table('package')->orderBy('package_ID', 'DESC')->where('package_Status', 1)->get();
    return view('System.Admin.Package', compact('packageList'));
  }

  public function getEditPackage(Request $req, $id)
  {
    $data['info_package'] = DB::table('package')->where('package_ID', $id)->first();
    return view('System.Admin.EditPackage', $data);
  }

  public function postEditPackage(Request $req)
  {
    $user = Session('user');

    if ($user->User_Level != 1) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
    }
    $id = $req->id;
    $name = $req->name;
    $min = $req->min;
    $max = $req->max;
    $interest = $req->interest;
    $timeEffective = $req->period;
    $coin = $req->coin;
    $check_package = DB::table('package')->where('package_ID', $id)->first();
    if (!$check_package) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Package ID is not exits!']);
    }
    $updatePackage = DB::table('package')->where('package_ID', $id)->update([
      'package_Name' => $name,
      'package_Min' => $min,
      'package_Max' => $max,
      'package_Interest' => $interest,
      'package_Time_Effective' => $timeEffective,
    ]);
    if (!$updatePackage) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Edit Package Error!']);
    }
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Edit Package Success!']);
  }

  public function postAddPackage(Request $req)
  {
    $user = Session('user');
    if ($user->User_Level != 1) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
    }
    $name = $req->name;
    $min = $req->min;
    $max = $req->max;
    $interest = $req->interest;
    $timeEffective = $req->period;
    $coin = $req->coin;

    if ($min <= 0 && $max <= 0) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Package amount invalid']);
    }

    if ($max == null) {
      $max = 9999999999.99999999;
    }

    $insertData = [
      'package_Name' => $name,
      'package_Min' => $min,
      'package_Max' => $max,
      'package_Interest' => $interest,
      'package_Time_Effective' => $timeEffective,
      'package_Note' => 0,
      'package_Status' => 1,
    ];

    $insertStatus = DB::table('package')->updateOrInsert($insertData);
    if ($insertStatus) {
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Add package success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
  }

  public function getHistoryEggs(Request $request)
  {
    $eggs = Eggs::select();
    if (Input::get('eggs_id')) {
      $eggs->where('ID', Input::get('eggs_id'));
    }
    if (Input::get('owner')) {
      $eggs->where('Owner', Input::get('owner'));
    }
    if (Input::get('pool')) {
      $eggs->where('Pool', Input::get('pool'));
    }
    if (Input::get('from') && !Input::get('to')) {
      $from = strtotime(Input::get('from'));
      $eggs->where('BuyDate', '>=', $from);
    }
    if (Input::get('to') && !Input::get('from')) {
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $eggs->where('BuyDate', '<=', $to);
    }
    if (Input::get('to') && Input::get('from')) {
      $from = strtotime(Input::get('from'));
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $eggs->whereBetween('BuyDate', [$from, $to]);
    }

    if (Input::get('buy_from')) {
      $eggs->where('BuyFrom', Input::get('buy_from'));
    }

    if ($request->can_hatch != '') {
      $eggs->where('CanHatches', $request->can_hatch == "1" ? true : false);
    }

    if ($request->export) {
      ob_end_clean();
      ob_start();
      return Excel::download(new EggsExport($eggs->orderByDesc('Owner')->get()), 'EggsExport.xlsx');
    }

    $eggs = $eggs->paginate(25);
    // dd($eggs);
    return view('System.Admin.HistoryEggs', compact('eggs'));
  }

  public function getHistoryFishs(Request $request)
  {
    $fishs = Fishs::with('fishTypes')->select();
    if (Input::get('fishs_id')) {
      $fishs->where('ID', Input::get('fishs_id'));
    }
    if (Input::get('owner')) {
      $fishs->where('Owner', Input::get('owner'));
    }
    if (Input::get('pool')) {
      $fishs->where('Pool', Input::get('pool'));
    }
    if (Input::get('from') && !Input::get('to')) {
      $from = strtotime(Input::get('from'));
      $fishs->where('BuyDate', '>=', $from);
    }
    if (Input::get('to') && !Input::get('from')) {
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $fishs->where('BuyDate', '<=', $to);
    }
    if (Input::get('to') && Input::get('from')) {
      $from = strtotime(Input::get('from'));
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $fishs->whereBetween('BuyDate', [$from, $to]);
    }

    if ($request->export) {
      ob_end_clean();
      ob_start();
      return Excel::download(new FishsExport($fishs->orderByDesc('Owner')->get()), 'FishsExport.xlsx');
    }

    $fishs = $fishs->orderByDesc('Owner')->paginate(25);

    return view('System.Admin.HistoryFishs', compact('fishs'));
  }

  public function getHistoryFoods()
  {
    $foods = Foods::select();
    if (Input::get('food_id')) {
      $foods->where('_id', Input::get('food_id'));
    }
    if (Input::get('owner')) {
      $foods->where('Owner', Input::get('owner'));
    }
    if (Input::get('from') && !Input::get('to')) {
      $from = strtotime(Input::get('from'));
      $foods->where('CreateAt', '>=', $from);
    }
    if (Input::get('to') && !Input::get('from')) {
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $foods->where('CreateAt', '<=', $to);
    }
    if (Input::get('to') && Input::get('from')) {
      $from = strtotime(Input::get('from'));
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $foods->whereBetween('CreateAt', [$from, $to]);
    }
    $foods = $foods->paginate(25);
    // dd($eggs);
    return view('System.Admin.HistoryFoods', compact('foods'));
  }

  public function getHistoryPools()
  {
    $pools = Pools::select();
    if (Input::get('pool_id')) {
      $pools->where('ID', Input::get('pool_id'));
    }
    if (Input::get('owner')) {
      $pools->where('Owner', Input::get('owner'));
    }
    $pools = $pools->paginate(25);
    // dd($eggs);
    return view('System.Admin.HistoryPools', compact('pools'));
  }

  public function getHistoryMarkets(Request $request)
  {
    $markets = Markets::orderBy('DateTime', -1);
    if (Input::get('id')) {
      $markets->where('_id', Input::get('id'));
    }
    if (Input::get('user_sell')) {
      $markets->where('UserSell', Input::get('user_sell'));
    }
    // dd(Input::get('status'));
    if (Input::get('status') != NULL) {
      if (Input::get('status') == 3) {
        $markets->where('UserBuy', '!=', null);
      } else {
        $markets->where('Status', intval(Input::get('status')));
      }
      // dd(Input::get('status'));
    }
    if (Input::get('from') && !Input::get('to')) {
      $from = strtotime(Input::get('from'));
      $markets->where('DateTime', '>=', $from);
    }
    if (Input::get('to') && !Input::get('from')) {
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $markets->where('DateTime', '<=', $to);
    }
    if (Input::get('to') && Input::get('from')) {
      $from = strtotime(Input::get('from'));
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('to')))));
      $markets->whereBetween('DateTime', [$from, $to]);
    }

    if ($request->export) {
      ob_end_clean();
      ob_start();
      return Excel::download(new MarketExport($markets->get()), 'MarketExport.xlsx');
    }

    $markets = $markets->paginate(25);
    // dd($markets);
    return view('System.Admin.HistoryMarkets', compact('markets'));
  }

  public function onOffFunction(Request $request)
  {
    $admin = session()->get('user');

    if ($admin->User_Level != 1) abort(404);

    $user = User::find($request->id);

    if ($request->key == 0) {
      $user->User_Lock_Swap = !$user->User_Lock_Swap;
    } else if ($request->key == 1) {
      $user->User_Lock_Transfer = !$user->User_Lock_Transfer;
    } else if ($request->key == 2) {
      $user->User_Lock_Withdraw = !$user->User_Lock_Withdraw;
    }

    $user->save();

    return redirect()->back();
  }

  public function getListHiring()
  {
    $listHiring = DB::table('agency')->leftJoin('countries', 'country_id', 'Countries_ID')->orderByDesc('id');


    if (Input::get('email')) {
      $listHiring->where('email', Input::get('email'));
    }
    if (Input::get('datefrom') && !Input::get('dateto')) {
      $listHiring->where('created_at', '>=', Input::get('datefrom'));
    }
    if (Input::get('dateto') && !Input::get('datefrom')) {
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('dateto')))));
      $listHiring->where('created_at', '<=', $to);
    }
    if (Input::get('dateto') && Input::get('datefrom')) {
      $from = strtotime(Input::get('datefrom'));
      $to = strtotime('+1 day', strtotime(date('Y-m-d', strtotime(Input::get('dateto')))));
      $listHiring->whereBetween('created_at', [$from, $to]);
    }
    $listHiring = $listHiring->paginate(30);
    //dd($listHiring);
    $arrWork = [1 => 'I am currently employed', 2 => 'I am still searching', 0 => 'Another '];
    return view('System.Admin.Hiring', compact('listHiring', 'arrWork'));
  }

  public function SupportMember(Request $req)
  {
    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $user = Session::get('user');
    if ($user->User_Level != 1 && $user->User_Level != 2 && $user->User_Level != 3 && $user->User_Level != 4) {
      dd('Stop');
    }
    $where = null;
    if ($req->UserID) {
      $where .= ' AND User_ID=' . $req->UserID;
    }
    if ($req->Username) {
      $where .= ' AND User_Name LIKE "' . $req->Username . '"';
    }
    if ($req->Email) {
      $where .= ' AND User_Email LIKE "%' . $req->Email . '%"';
    }
    if ($req->sponsor) {
      $where .= ' AND User_Parent = ' . $req->sponsor;
    }
    if ($req->agency_level) {
      $where .= ' AND User_Agency_Level = ' . $req->agency_level;
    }
    if ($req->datetime) {
      $where .= ' AND date(User_RegisteredDatetime) = "' . date('Y-m-d', strtotime($req->datetime)) . '"';
    }
    if ($req->status_email != null) {
      $where .= ' AND User_EmailActive = ' . $req->status_email;
    }
    if ($req->user_level != null) {
      $where .= ' AND User_Level = ' . $req->user_level;
    }
    if ($req->tree != '') {

      $where .= ' AND User_Tree LIKE "%' . str_replace(', ', ',', $req->tree) . '%"';
    }
    if ($req->suntree != '') {

      $where .= ' AND User_SunTree LIKE "%' . str_replace(', ', ',', $req->suntree) . '%"';
    }


    $user_list = User::leftJoin('google2fa', 'google2fa.google2fa_User', 'users.User_ID')
      ->join('user_level', 'User_Level_ID', 'User_Level')
      ->whereRaw('1 ' . $where)
      ->orderBy('User_RegisteredDatetime', 'DESC');

    $user_list = $user_list->paginate(50);

    $user_level = DB::table('user_level')->orderBy('User_Level_ID')->get();
    $user_agency_level = DB::table('user_agency_level')->orderBy('user_agency_level_ID')->get();
    return view('System.Admin.UserSupport', compact('user_list', 'user_level', 'user_agency_level', 'level'));
  }

  public function getResentMail(Request $req, $id)
  {

    $user = User::where('User_ID', $id)->where('User_EmailActive', 0)->orderBy('User_RegisteredDatetime', 'DESC')->first();
    $token = $user->User_Token;
    if ($user) {
      $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email, 'token' => $token);
      //Job
      dispatch(new SendMailJobs('Active', $data, 'Active Account!', $user->User_ID));
    } else {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'User not exit!']);
    }
    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Resent mail success!']);
  }

  public function listEggRate(Request $request)
  {
    $eggFail = EggFailed::orderByRaw('level_fish ASC , user DESC');
    $eggFind = EggFailed::find($request->id);
    if (Input::get('fish_id')) {
      $eggFail->where('fish_id', Input::get('fish_id'));
    }
    if (Input::get('owner')) {
      $eggFail->where('user', Input::get('owner'));
    }
    if (Input::get('level_fish')) {
      $eggFail->where('level_fish', Input::get('level'));
    }

    if ($request->export) {
      ob_end_clean();
      ob_start();
      return Excel::download(new EggsFailExport($eggFail->get()), 'EggsFail.xlsx');
    }

    $eggFail = $eggFail->paginate(100);

    return view('System.Admin.list_egg_rate', [
      'eggFail' => $eggFail,
      'eggFind' => $eggFind,
    ]);
  }

  public function postChangeTableID(Request $request)
  {

    $eggCurrent = EggFailed::where([
      'user' => $request->user,
      'fish_id' => $request->fish_id,
      'level_fish' => $request->level_fish,
    ])->first();

    $eggCurrent->user = null;
    $eggCurrent->fish_id = null;

    $eggNew = EggFailed::find($request->to_id);
    if ($eggCurrent->level_fish == $eggNew->level_fish) {
      $eggNew->user = $request->user;
      $eggNew->fish_id = $request->fish_id;
      $eggNew->save();
    } else {

    }

    $eggCurrent->save();

    return redirect()->route('system.admin.listEggRate');
  }

  public function getHistoryGame(Request $request)
  {
    $gameBet = GameBet::where('GameBet_Currency', '!=', 99)->orderBy('GameBet_datetime', -1);

    if (Input::get('subaccount_user')) {
      $gameBet->where('GameBet_SubAccountUser', (int)Input::get('subaccount_user'))->orWhere('GameBet_SubAccountUser', (int)Input::get('subaccount_user'));
    }

    if (Input::get('bet')) {
      $gameBet->where('GameBet_Game', Input::get('bet'));
    }

    if (Input::get('match_id')) {
      $gameBet->where('GameBet_GameID', Input::get('match_id'));
    }

    if (Input::get('action')) {
      $gameBet->where('GameBet_Action', Input::get('action'));
    }

    if (Input::get('currency')) {
      $gameBet->where('GameBet_Currency', (int)Input::get('currency'));
    }

    if (Input::get('datefrom')) {
      $from = strtotime(Input::get('datefrom'));
      $gameBet->where('GameBet_datetime', '>=', $from);
    }

    if (Input::get('dateto')) {
      $to = strtotime('+1 day', strtotime(Input::get('dateto')));
      $gameBet->where('GameBet_datetime', '<', $to);
    }

    if ($request->export) {
      ob_end_clean();
      ob_start();
      return Excel::download(new GameExport($gameBet->get()), 'GameExport.xlsx');
    }

    $gameBet = $gameBet->paginate(50);

    //dd($gameBet);
    return view('System.Admin.HistoryGame', [
      'gameBet' => $gameBet
    ]);
  }

  public function getBlockUser(Request $req, $id)
  {
    $user = Session('user');
    if ($user->User_Level == 1) {
      $check_blockInterest = User::where('User_ID', $id)->first();
      if ($check_blockInterest->User_Block == 0) {
        $cmt_log = "Block ID User: " . $id;
        Log::insertLog(Session('user')->User_ID, "Block User", 0, $cmt_log);

        $updateBlockInterest = User::where('User_ID', $id)->update([
          'User_Block' => 1
        ]);
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Block User Success!']);
      } else {
        $cmt_log = "Unblocked Interest ID User: " . $id;
        Log::insertLog(Session('user')->User_ID, "Unblocked User", 0, $cmt_log);

        $updateBlockInterest = User::where('User_ID', $id)->update([
          'User_Block' => 0
        ]);
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Unlocked User Success!']);
      }
    }
    abort(404);
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
  }
  public function getExportBalanceUser(Request $request){
    $list = DB::table('checkBalance')->where('datetime', '>=', date('Y-m-d 00:00:00'))->get();
    if ($request->export) {
      ob_end_clean();
      ob_start();
      //dd($list);
      return Excel::download(new BalanceUserExport($list), 'BalanceUser.xlsx');
      //dd('export done');
    }
    dd('export done');
  }

  public function getAdminCooperation(){
    $list = DB::table('cooperations')->orderByDesc('id')->paginate(50);
    return view('System.Admin.Cooperation', compact('list'));
  }

  public function postBanner(Request $request){
    try {
      $imageblogExtensionExp = $request->file('banner_img')->getClientOriginalExtension();
      // set folder and file name
      $randomNumber = uniqid();
      $imageblogStoreExp = "banner/banner_image_" . $randomNumber . "." . $imageblogExtensionExp;
      //send to Image server
      $upload = Storage::disk('s3')->put('123Betnow/'.$imageblogStoreExp, fopen($request->file('banner_img'), 'r+'));
      $imageblogStoreExpLink = config('url.media') . $imageblogStoreExp;
    }catch (\Exception $e) {
      dd($e);
    }

    $input = [
      'banner_img' => $imageblogStoreExpLink,
    ];

    DB::table('banner')->insert($input);

    return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Thêm thành công!']);
  }

  public function getBanner(Request $request){
    $list = DB::table('banner')->get();
    if(isset($_GET['delete'])){

      DB::table('banner')->where('banner_id', $request->delete)->delete();

      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Xóa thành công!']);
    }

    return view('System.Admin.Banner', compact('list'));
  }

  public function getListGame(Request $request){
    $list = DB::table('list_game')->get();

    return view('System.Admin.ListGame', compact('list'));
  }

  public function editListGame(Request $request, $id){

    $getItem = DB::table('list_game')->where('id', $id)->first();

    if (isset($_POST['submit'])) {
      if($request->file('icon_game')){
        $passportImageExtension = $request->file('icon_game')->getClientOriginalExtension();

        //set folder and file name
        $randomNumber = uniqid();
        $passportImageStore = "list/game/icon_".$randomNumber.".".$passportImageExtension;
        //send to Image server
        $passportImageStatus =Storage::disk('ftp')->put($passportImageStore, fopen($request->file('icon_game'), 'r+'));
        $imageblogStoreExpLink = config('url.media') . $passportImageStore;
      }else{
        $imageblogStoreExpLink = $getItem->icon_game;
      }

      if($request->file('image')){
        $passportImageExtension_image = $request->file('image')->getClientOriginalExtension();

        //set folder and file name
        $randomNumber_image = uniqid();
        $passportImageStore_image = "list/game/image_".$randomNumber_image.".".$passportImageExtension_image;
        //send to Image server
        $passportImageStatus_image =Storage::disk('ftp')->put($passportImageStore_image, fopen($request->file('image'), 'r+'));
        $imageblogStoreExpLink_image = config('url.media') . $passportImageStore_image;
      }else{
        $imageblogStoreExpLink_image = $getItem->image;
      }

      $input = [
        'icon_game' => $imageblogStoreExpLink,
        'image' => $imageblogStoreExpLink_image,
        'name' => $request->name,
        'display_name' => $request->display_name,
      ];

      DB::table('list_game')->where('id', $id)->update($input);

      return redirect()->route('system.admin.getListGame')->with(['flash_level' => 'success', 'flash_message' => 'Update thành công!']);
    };

    return view('System.Admin.EditListGame', compact('getItem'));
  }
}
