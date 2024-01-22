<?php

namespace App\Http\Controllers;

use App\Model\Eggs;
use App\Model\PoolTypes;
use App\Model\User;
use App\Model\Money;
use App\Model\GameBet;
use App\Model\logMoney;
use App\Model\Log;
use App\Model\BetHistoryAeSexy;
use App\Model\BetHistorySbobet;
use App\Model\BetHistorySbobetCasino;
use App\Model\BetHistorySbobetVirtualSport;
use App\Model\BetHistorySbobetSeamless;
use App\Model\BetHistorySbobetThirdPartySportsBook;
use App\Model\GoogleAuth;
use App\Model\LogUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Artisan;
use DB, Excel;

use App\Exports\MinusBalanceUserExport;
use App\Exports\DepositExport;
use App\Exports\UserBalanceExport;
use App\Jobs\SendMailVNJobs;
use App\Jobs\GetHistoryWM555Jobs;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendMailJobs;
use App\Jobs\SendTelegramJobs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
//use phpseclib\Crypt\Hash;

class TestController extends Controller
{
  public $feeWithdraw = 0.02;
  public $feeTransfer = 0;
  public $feeSwap = 0;
  public $config;
  public $configSB;

  public function __construct()
  {
    $this->feeWithdraw = config('coin.EUSD.WithdrawFee');
    $this->feeTransfer = config('coin.EUSD.TransferFee');
    // dd($this->feeWithdraw);
    $amount = 50;
    $amountFee = $amount * ($this->feeWithdraw / 100);
    $this->config = config('utils.wm555');
    $this->configSB = config('urlSBOBET.sbobet');
  }

  public function setDateDeposit(Request $req){
    $getMoney = Money::where("Money_ID",$req->money_id*1)->where("Money_MoneyStatus",1)->first();
    if(!$getMoney){
      dd("ID lệnh không tồn tại");
    }
    $set_date = strtotime($req->set_date);
    $getMoney->Money_Time = $set_date;
    $getMoney->save();
    dd("Sét thành công");
  }

  public function setWithdrawBonusBirthday(Request $req){
    dd(123);
    $getUser = User::join("money","Money_User","User_ID")->whereIn("Money_MoneyAction",[10,13])->where("Money_MoneyStatus",1)->groupby("User_ID")->paginate(100);
    foreach($getUser as $user){
      $balanBonus = User::getBalance($user->User_ID, 10);
      $balanBonus = round($balanBonus, 2);
      if(!$balanBonus || $balanBonus == 0){
        continue;
      }

      $withdrawBonus = $balanBonus;

      if($withdrawBonus < 0){
        continue;
      }

      $insertArray = array(
        array(
          'Money_User' => $user->User_ID,
          'Money_USDT' => -$withdrawBonus,
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => 'Withdraw (Reset the event has ended) bonus deposit with '.$withdrawBonus.' EUSD (From Balance Bonus)',
          'Money_MoneyAction' => 78,
          'Money_MoneyStatus' => 1,
          'Money_Address' => null,
          'Money_Currency' => 10,
          'Money_CurrentAmount' => $withdrawBonus,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 1
        )
      );
      if($withdrawBonus > 0){
        Money::insert($insertArray);
        echo "Reset: ".$user->User_ID." ".$withdrawBonus ." USD <br>";
      }
    }
    dd("done");
  }

  public function getBalancePlayer($user ,$Username,$lucky = 0){
    $user_data = User::find($user);
    if($lucky == 1){
      $User_Sbobet_Password = $user_data->User_Sbobet_Password_Lucky;
    } else {
      $User_Sbobet_Password = $user_data->User_Sbobet_Password;
    }
    if ($User_Sbobet_Password == NULL) {
      $balance = 0;
      return $balance;
    }
    $url = $this->configSB['url'].'/web-root/restricted/player/get-player-balance.aspx';
    $body = [
      "Username" => $Username,
      "CompanyKey"=> $this->configSB['CompanyKey'],
      "ServerId"=> $this->configSB['ServerId'],
    ];
    $topup_str = json_encode($body);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);

    if ($err) {
      return 0;
    }
    if($check->error->id != 0){
      return 0;
    }

    $total = $check->balance;

    return $total;
  }

  public function withdrawBrand(Request $request){

    dd(123);

    //$getUser = User::whereIn("User_ID",[353306,580615,722945,146671,919912,124807,854982])->get(); 
    foreach($getUser as $user){
      $balance = User::getBalance($user->User_ID,3);
      if($balance > 0){
        $arrayInsert = array(
          'Money_User' => $user->User_ID,
          'Money_USDT' => -$balance,
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => "(Admin reset) Withdraw $balance USD",
          'Money_MoneyAction' => 2,
          'Money_MoneyStatus' => 1,
          'Money_Address' => null,
          'Money_Currency' => 3,
          'Money_CurrentAmount' => $balance,
          'Money_CurrencyFrom' => 0,
          'Money_CurrencyTo' => 0,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 1,
        );
        echo "$user->User_ID (Admin reset) Withdraw $balance USD";
        Money::insert($arrayInsert);
      }else{
        echo "$user->User_ID no balance =  $balance USD";
      }

    }

    dd("Withdraw sbobet success");

    dd("Dưới là rút theo nhánh");

    $getListUserParent = User::whereIn("User_ID",[763550,854982,353306])->get();
    foreach($getListUserParent as $parent){
      //Lấy ra nhánh dưới của các tài khoản parent
      $getBrand = User::where('User_Tree','like',$parent->User_Tree.'%')->get();
      foreach($getBrand as $children){
        $user = User::find($children->User_ID); 
        //Kiểm tra balance game còn tiền không, nếu còn thì update trạng thái về 1 để client thực hiện gọi api rút của game về hệ thống

        //Sbo
        $checkBalanbceSbo = $this->getBalancePlayer($user->User_ID,$user->User_Name_Sbobet);
        if($checkBalanbceSbo <= 0){
          echo $user->User_ID.' checkBalanbceSbo <= 0 <br>';
          continue;
        }
        $address_pool = '';
        $User_Name_Sbobet = $user->User_Name_Sbobet;

        $Money_MoneyAction = 92;
        $coin = 3;

        if($User_Name_Sbobet == NULL){
          echo $user->User_ID.' User_Name_Sbobet Null <br>';
          continue;
        }

        $balanceGame = $this->getBalancePlayer($user->User_ID, $User_Name_Sbobet);
        if($balanceGame <= 0){
          echo $user->User_ID.' balanceGame <= 0 <br>';
          continue;
        }


        $txCode = Str::random(29);
        $url = $this->configSB['url'].'/web-root/restricted/player/withdraw.aspx';
        $body = [
          "Username" => $User_Name_Sbobet,
          "Amount"=> $balanceGame,
          "CompanyKey"=> $this->configSB['CompanyKey'],
          "ServerId"=> $this->configSB['ServerId'],
          "IsFullAmount" => false,
          "TxnId" => "$txCode",
        ];
        $topup_str = json_encode($body);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
        ));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $check= json_decode($result);

        if ($err) {
          echo $user->User_ID.' err Withdraw failed sbobet <br>';
          continue;
        } 

        if($check->error->id != 0){
          echo $user->User_ID.' err id Withdraw failed sbobet <br>';
          continue;
        }
        //////////////////// CHUYEN TIEN VAO  /////////////////   
        $cmt = '(Admin reset) Withdraw from sbobet with ' . $balanceGame . ' USD';
        $arrayInsert = array(
          array(
            'Money_User' => $user->User_ID,
            'Money_USDT' => $balanceGame,
            'Money_USDTFee' => 0,
            'Money_Time' => time(),
            'Money_Comment' => $cmt,
            'Money_MoneyAction' => $Money_MoneyAction,
            'Money_MoneyStatus' => 1,
            'Money_Address' => null,
            'Money_Currency' => $coin,
            'Money_CurrentAmount' => $balanceGame,
            'Money_CurrencyFrom' => 0,
            'Money_CurrencyTo' => 0,
            'Money_Rate' => 1,
            'Money_Confirm' => 0,
            'Money_Confirm_Time' => null,
            'Money_FromAPI' => 1,
            'Money_TXID' => $txCode,
          ),
          array(
            'Money_User' => $user->User_ID,
            'Money_USDT' => -$balanceGame,
            'Money_USDTFee' => 0,
            'Money_Time' => time(),
            'Money_Comment' => "(Admin reset) Withdraw $balanceGame USD",
            'Money_MoneyAction' => 2,
            'Money_MoneyStatus' => 1,
            'Money_Address' => null,
            'Money_Currency' => $coin,
            'Money_CurrentAmount' => $balanceGame,
            'Money_CurrencyFrom' => 0,
            'Money_CurrencyTo' => 0,
            'Money_Rate' => 1,
            'Money_Confirm' => 0,
            'Money_Confirm_Time' => null,
            'Money_FromAPI' => 1,
            'Money_TXID' => $txCode,
          )
        );
        Money::insert($arrayInsert);
        LogUser::addLogUser($user->User_ID,'Withdraw sbobet success', 'withraw', $request->ip());
        echo $user->User_ID.' Withdraw sbobet success <br>';
      }
    }
    dd(123);
  }

  public function getBlockAccount(Request $req)
  {

    $check_blockInterest = User::where('User_Level','>',0)->paginate(500);
    foreach($check_blockInterest as $user){
      $userTree = $user->User_Tree;

      $cmt_log = "Admin Unlock ID User: " . $user->User_ID;
      Log::insertLog(350205, "Block Account (Admin)", 0, $cmt_log);

      $updateBlock = User::where('User_ID',  $user->User_ID)->update([
        'User_Block' => 1
      ]);

      /*if (strpos($userTree, '930092') !== false || strpos($userTree, '732351') !== false) {
        $cmt_log = "Admin Block Tree Not In (930092,732351) ID User: " . $user->User_ID;
        Log::insertLog(350205, "Block Account (Admin)", 0, $cmt_log);

        $updateBlock = User::where('User_ID',  $user->User_ID)->update([
          'User_Block' => 0
        ]);
      }*/
      //dd(strpos($userTree, '930092'),strpos($userTree, '732351'),$user);

    }
    $routeName = 'getBlockAccount';
    $page = $check_blockInterest->currentPage();
    $lastPage = $check_blockInterest->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function postSetWithdrawBonusBirthday(Request $req){
    //dd(123);
    $user = User::find(222825);
    $dayNow = time();
    $dayStart = strtotime('2023-07-01 00:00:00');
    $dayEnd = strtotime('2023-08-08 00:00:00');

    //dd($user->User_Level,date('Y-m-d H:i:s',$dayStart),date('Y-m-d H:i:s',$dayEnd));
    if($dayNow < $dayStart || $dayNow >= $dayEnd){
      return $this->response(200, [], trans('notification.Error_The_promotion_is_closed'), [], false);
    }

    $balanBonus = User::getBalance($user->User_ID, 10);
    $balanBonus = round($balanBonus, 2);
    if(!$balanBonus || $balanBonus == 0){
      return $this->response(200, [],trans('notification.withdraw_failed_bonus_wallet_is_not_enough'), [], false);
    }

    $checkdate = Money::where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', $dayStart)
      ->where('Money_Time', '<', $dayEnd)
      ->where('Money_MoneyAction', 10)
      ->where('Money_MoneyStatus', 1)
      ->first();
    $dayBonus = $checkdate->Money_Time;
    $dayExpired = strtotime('+8 days', $dayBonus);
    $fromDate = date('Y-m-d 00:00:00', $dayBonus);
    $toDate = date('Y-m-d H:i:s');
    if(time() > $dayExpired){
      return $this->response(200, [], "Error! Promotion expires!", [], false);
    }


    $totalTradeBonus = GameBet::getShowTotalBet($user->User_ID, date('Y-m-d',$dayBonus), date('Y-m-d',$dayEnd))['totalBet'];

    dd($checkdate,$totalTradeBonus,$dayStart);
    if($user->User_Level != 1){
      return $this->response(200, [], "Error! This function is maintained", [], false);
    }

    //chỉ được rút 1 lần duy nhất (cả 3 sự kiện bonus nếu user thực hiện rút rồi thì thôi/chỉ được 1 lần 100%) 
    $checkWithdrawBonus = Money::where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', $dayStart)
      ->where('Money_Time', '<', $dayEnd)
      ->where('Money_MoneyAction', 77)
      ->where('Money_MoneyStatus', 1)
      ->orderByDesc('Money_ID')
      ->first();
    if($checkWithdrawBonus){
      return $this->response(200, [], "Only 100% withdrawal is allowed", [], false);
    } 



    $amountTotal = 0;
    $arrayActionPromotion = [10,13];

    //Lấy ra danh sách lệnh nạp bonus
    $listDepositBonus = Money::where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', $dayStart)
      ->where('Money_Time', '<', $dayEnd)
      ->whereIn('Money_MoneyAction', $arrayActionPromotion)
      ->where('Money_MoneyStatus', 1)
      ->get();

    if(count($listDepositBonus) <= 0){
      return $this->response(200, [], trans('notification.Amount_must_be_greater_than_0'), [], false);
    }  


    //lấy ra số volume trade hiện tại
    $totalTradeBonus = GameBet::getShowTotalBet($user->User_ID, date('Y-m-d',$dayStart), date('Y-m-d',$dayEnd))['totalBet'];
    $amountCheckVolume = 0;

    $withdrawBonus = $balanBonus;
    //dd($withdrawBonus);
    //payment
    //dd($withdrawBonus);
    $insertArray = array(
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -$withdrawBonus,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.$withdrawBonus.' EUSD (From Balance Bonus)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 10,
        'Money_CurrentAmount' => $withdrawBonus,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => $withdrawBonus,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.$withdrawBonus.' EUSD (To Main Balance)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 3,
        'Money_CurrentAmount' => $withdrawBonus,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
    );
    //dd($insertArray);
    if($withdrawBonus > 0){
      if(count($insertArray)){
        Money::insert($insertArray);
        return $this->response(200, [], trans('notification.withdrawal_successful'), [], true);
      }
      return $this->response(200, [], "Not eligible for bonus withdrawal.", [], false);
    }
    return $this->response(200, [], "Not eligible for bonus withdrawal.", [], false);
  }

  public function cronWithdrawSbobet(Request $request){

    $user = User::find(938689);
    $User_Name_Sbobet = $user->User_Name_Sbobet;
    $User_Sbobet_Password = $user->User_Sbobet_Password;
    $amount = 210.86;
    $cmt = 'Withdraw from sbobet with ' . $amount . ' USD';
    $coin = 3;
    if($User_Name_Sbobet == NULL || $User_Sbobet_Password == NULL){
      return $this->response(200, [], 'Please register!', [], false);
    }
    $txCode = Str::random(29);
    $url = $this->configSB['url'].'/web-root/restricted/player/withdraw.aspx';
    $body = [
      "Username" => $User_Name_Sbobet,
      "Amount"=> $amount,
      "CompanyKey"=> $this->configSB['CompanyKey'],
      "ServerId"=> $this->configSB['ServerId'],
      "IsFullAmount" => false,
      "TxnId" => "$txCode",
    ];
    $topup_str = json_encode($body);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);

    if ($err) {
      dd('false');
    } 

    if($check->error->id != 0){
      dd('false');
    }
    dd('ok');
  }
  public function updateSercetKeyOTP(Request $req){
    return;
    include(app_path() . '/functions/xxtea.php');
    $key = 'X21B9TT2AI';
    $auth = GoogleAuth::where('google2fa_User',397628)->first();

    $responseSecret = json_decode(xxtea_decrypt(base64_decode($auth->google2fa_Secret), $key), true);
    dd($responseSecret,$auth);
    foreach($auth as $value){
      $dataToken = array('secret'=>$value->google2fa_Secret,'user_id' => $value->google2fa_User , 'time' => time()); 
      $tokenSecret = base64_encode(xxtea_encrypt(json_encode($dataToken), $key));
      //$responseSecret = json_decode(xxtea_decrypt(base64_decode($tokenSecret), $key), true);
      //dd($value,$tokenSecret,$responseSecret);
      $value->google2fa_Secret = $tokenSecret; //$req->secret
      $value->save();
    }
    dd($auth);
  }

  public function updateDate(){
    $list = BetHistorySbobetThirdPartySportsBook::get();
    foreach($list as $item){
      $dateUpdate = date('Y-m-d H:i:s', strtotime("+4 hours", strtotime($item->modifyDate)));
      $item->statistical_time123betnow = $dateUpdate;
      $item->save();
    }
  }

  public function listBalanceUser(Request $req){
    $getUser = User::select('User_ID','User_Email','User_Block','User_Level')->where('User_Level',0);
    if($req->userid){
      $getUser = $getUser->where('User_ID',$req->userid);
    }
    $getUser = $getUser->paginate(100);
    foreach($getUser as $value){
      $balance = User::getBalance($value->User_ID, 3);
      if($balance < $req->minbalance) continue;
      $status = 'No';
      if($value->User_Block == 1)  $status = 'Block';
      echo 'User ID: '.$value->User_ID.' --- Email: '.$value->User_Email.' --- Block: '.$status.' --- Balance: '.$balance.'<br>';
    }
    $page = $getUser->currentPage();
    $lastPage = $getUser->lastPage();
    dd('Truyền page để xem thêm nha - Page: '.$page.' LastPage: '.$lastPage);
  }
  public function checkDepositTokenBEP20()
  {
    $contractAddress = '0xfea6ab80cd850c3e63374bc737479aeec0e8b9a1';
    $address = '0x3030327D03bAe2143923ba136e7ED97482de49b6';
    $user = 102887;
    $email = 'candyaz2020+1@gmail.com';
    $currency = 12;


    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';
    $symbol = 'SOL';
    $feeDeposit = 0;
    if ($currency == 4 || $currency == 12 || $currency == 13 || $currency == 14) {
      $feeDeposit = 0.1;
    } elseif ($currency == 7) {
      $feeDeposit = 0;
    }
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
    //$rate = $this->coinRateBuyEBP($symbol);
    $currency = $currency;

    $client = new \GuzzleHttp\Client();
    $getTransactions = json_decode($client->request('GET', 'api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey)->getBody()->getContents());

    dd($getTransactions->result, 'api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
    foreach ($getTransactions->result as $v) {
      if (strtoupper($v->to) != strtoupper($address)) {
        continue;
      }
      dd($address, $v);
      $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
      //dd($hash,$v);

      if (!$hash) {
        //$user = $address;
        //if (!$user) {
        //  continue;
        //}

        $value = filter_var($v->value / pow(10, $v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        //dd($value);
        $amountFee = $value * $feeDeposit;
        $amountUSDT = $value * $rate;
        $amountUSDTFee = $amountFee * $rate;
        //dd($amountFee,$amountUSDT,$amountUSDTFee,$value);
      }
    }

    //return view('Cron.reload', compact('routeName', 'page', 'timeout'));
    //dd('check deposit usdt complete');
  }


  public function checkDepositBNB()
  {
    $userID = 486152;
    $userEmail = "nguyentrinhphuonglinh998@gmail.com";
    $address = "0x580B8CbD1b278398D8133A8839f2C977116eCCbd";
    $symbol = "BNB";
    $currency = 16;


    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';
    $symbol = $symbol;
    $feeDeposit = 0.1;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy("BNB");
    //$rate = $this->coinRateBuyEBP($symbol);
    $currency = $currency;

    $client = new \GuzzleHttp\Client();
    $getTransactions = json_decode($client->request('GET', 'api.bscscan.com/api?module=account&action=txlist&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey)->getBody()->getContents());

    //    dd($getTransactions->result);
    foreach ($getTransactions->result as $v) {
      if (strtoupper($v->to) != strtoupper($address)) {
        continue;
      }
      if($v->value <= 0){
        continue;
      }
      if($v->confirmations <= 5){
        continue;
      }
      $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
      //dd($hash,$v);

      if (!$hash) {
        //$user = $address;
        //if (!$user) {
        //  continue;
        //}

        $value = filter_var($v->value / pow(10, 18), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        //dd($value);
        $amountFee = $value * $feeDeposit;
        $amountUSDT = $value * $rate;
        $amountUSDTFee = $amountFee * $rate;
        //dd($amountFee,$amountUSDT,$amountUSDTFee,$value);
        //cộng tiền
        $money = new Money();
        $money->Money_User = $userID;
        $money->Money_USDT = $amountUSDT;
        $money->Money_USDTFee = -$amountUSDTFee;
        $money->Money_Time = time();
        $money->Money_Comment = 'Deposit ' . ($value + 0) . ' ' . $symbol;
        $money->Money_Currency = 3;
        $money->Money_CurrencyFrom = $currency;
        $money->Money_MoneyAction = 1;
        $money->Money_Address = $v->hash;
        $money->Money_CurrentAmount = $value;
        $money->Money_Rate = $rate;
        $money->Money_MoneyStatus = 1;
        //dd($money);
        $money->save();
        $bonus = logMoney::getBonusDepositBirthday($userID, $amountUSDT, $currency);

        $user = User::find($userID);

        // 	Gửi telegram thông báo User verify
        $message = "$userEmail Deposit $value $symbol\n"
          . "<b>User ID: </b> "
          . "$userID\n"
          . "<b>Email: </b> "
          . "$userEmail\n"
          . "<b>Address: </b> "
          . "$user->User_WalletAddress\n"
          . "<b>Amount: </b> "
          . $value . " $symbol\n"
          . "<b>Amount USD: </b> "
          . ($value * $rate) . " USDT\n"
          . "<b>Rate: </b> "
          . $rate . "\n"
          . "<b>Submit Deposit Time: </b>\n"
          . date('d-m-Y H:i:s', time());


        dispatch(new SendTelegramJobs($message, -485635858));
        $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email, 'deposit_amount' => $value, 'fee_deposit' => $amountUSDTFee, 'number_of_tokens' => $value, 'currency' => $symbol, 'EUSD' => $value * $rate, 'wallet' => $v->from, 'hash_code' => $v->hash, 'network' => 'BSC');
        dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));
      }
    }

    //return view('Cron.reload', compact('routeName', 'page', 'timeout'));
    //dd('check deposit usdt complete');
  }

  public function getCheckMinusBalance($user, Request $req)
  {
    if ($req->key != '123123321') {
      abort(404);
    }
    $userMoney = Money::where('Money_User', $user)->whereIn('Money_MoneyStatus', [0, 1])->get();
    if ($req->export == 1) {
      set_time_limit(300);
      ob_end_clean();
      ob_start();
      //dd('done');
      return Excel::download(new MinusBalanceUserExport($userMoney), 'moneyUser' . '.xlsx');
    }
    dd($userMoney);
  }

  //
  public function getTest(Request $req)
  {
    //abort(404);
    // dd(date('Y-m-d H:i:s', 1690610717));
    //$userID = 487117;
    //$user = User::find($userID);
    //$mondayLastWeek = $from = date('Y-m-d 00:00:00', strtotime('monday last week'));
    //$mondayThisWeek = $to = date('Y-m-d 00:00:00', strtotime('monday this week'));
    //$totalBetParent = GameBet::getTotalBet($userID, $from, $to);
    //$totalBetParent = $totalBetParent->totalBet ?? 0;
    //$salesActive = GameBet::getVolumeTradeMember($user, $from, $to, 0);
    //dd($getF1Active);
    //dd($totalBetParent, $salesActive);
    // $getPackageParent = GameBet::getPackageUserAvailable($user->User_ID, $mondayLastWeek, $mondayThisWeek, 0);
    //dd($getPackageParent);
    if ($req->key != '321321321') {
      abort(404);
    }
    if ($req->bnb) {
      $this->checkDepositBNB($req);
      dd('check deposit success');
    }
    if ($req->ib) {
      $this->insertIBTest($req);
      dd('insert success');
    }
    if ($req->package) {
      $this->getInsertPackage($req);
      dd('insert success');
    }
    if ($req->change) {
      $this->changeTree($req);
    }
    if ($req->deposit) {
      $fromDate = date('Y-m-d');
      $toDate = date('Y-m-d', strtotime('+1 day'));
      if ($req->from) {
        $fromDate = date('Y-m-d', strtotime($req->from));
      }
      if ($req->to) {
        $toDate = date('Y-m-d', strtotime($req->to) + 86400);
      }
      $walletList = Money::leftjoin('currency', 'Money_Currency', '=', 'currency.Currency_ID')
        ->leftjoin('currency as currency_to', 'Money_CurrencyTo', '=', 'currency_to.Currency_ID')
        ->leftjoin('currency as currency_from', 'Money_CurrencyFrom', '=', 'currency_from.Currency_ID')
        ->join('users', 'Money_User', 'User_ID')
        ->select('Money_ID', 'Money_User', 'User_Level', 'Money_MoneyAction', 'Money_USDT', 'Money_Currency', 'Money_USDTFee', 'Money_Time', 'currency.Currency_Symbol as Currency_Symbol', 'currency_from.Currency_Symbol as Currency_From_Symbol', 'currency_to.Currency_Symbol as Currency_To_Symbol', 'Money_Comment', 'Money_MoneyStatus', 'Money_Confirm', 'Money_Rate', 'Money_CurrentAmount', 'Money_Address', 'Money_CurrencyTo', 'Money_CurrencyFrom')
        ->where('Money_Time', '>=', strtotime($fromDate))
        ->where('Money_Time', '<', strtotime($toDate))->orderByDesc('Money_ID');
      if ($req->action) {
        $walletList = $walletList->where('Money_MoneyAction', $req->action);
        $fileName = "WithdrawExport";
      } else {
        $walletList = $walletList->where('Money_MoneyAction', 1);
        $fileName = "DepositExport";
      }
      $walletList = $walletList->get();
      //dd($walletList);
      set_time_limit(300);
      ob_end_clean();
      ob_start();
      //dd('done');
      return Excel::download(new DepositExport($walletList), $fileName . '.xlsx');
      //$this->getExportDeposit($req);
      dd('done');
    }

    $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=0x55d398326f99059ff775485246999027b3197955&address=0x4b6f990ba6971e377b564471db2873b67bb33ae8&offset=5000&page=1&sort=desc&apikey=6EWUSUAHDMTTGF96VRFI25NEU58R4ZV49E');
    $data = json_decode($getData, true);
    dd($data['data'][0]);
    //        $bonus = logMoney::getBonusDepositBirthday(304409, 1500, 3);
    //        dd($bonus);
    $client = new Client();
    //        $res = $client->request('GET', 'https://api.dragonpool.app/api/v1/market/price');
    $price = file_get_contents('https://api.dragonpool.app/api/v1/market/price');
    dd($price);
    $balanBonus = User::getBalance(869468, 10);
    $balanBonus = round($balanBonus, 2);
    $amount = 2340.3;
    if ($amount > $balanBonus) {
      return $this->response(200, [], "Withdraw failed. Bonus wallet is not enough!", [], false);
    }
    dd($balanBonus, $amount > $balanBonus);

    //        $apiKey = $this->config['key'];
    //        $client = new Client();
    //        $res = $client->request('POST', $this->config['url'].'userinfo?apikey='.$apiKey, [
    //            'body' => '{"username":"now942666"}'
    //        ]);
    //        $data = $res->getBody()->getContents();
    ////        dd($data);
    //        dd($data, json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data)), json_last_error());
    //        $curl = curl_init();
    //
    //        curl_setopt_array($curl, array(
    //            CURLOPT_URL => 'http://34.101.189.102/api/userinfo?apikey=6bbcac-528732-6502f6-37dfc5-e5aa9f',
    //            CURLOPT_RETURNTRANSFER => true,
    //            CURLOPT_ENCODING => '',
    //            CURLOPT_MAXREDIRS => 10,
    //            CURLOPT_TIMEOUT => 0,
    //            CURLOPT_FOLLOWLOCATION => true,
    //            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //            CURLOPT_CUSTOMREQUEST => 'POST',
    //            CURLOPT_POSTFIELDS =>'{
    //    "username":"now942666"
    //}',
    //            CURLOPT_HTTPHEADER => array(
    //                'Content-Type: application/json'
    //            ),
    //        ));
    //
    //        $response = curl_exec($curl);
    //
    //        curl_close($curl);
    //        echo $response;
    //        exit;
    //
    //$this->checkBalance($req);
    //$this->wm555History(986127);
    //$jobs = dispatch(new GetHistoryWM555Jobs(986127));
    dd(123);
    abort(404);
    $user = User::find(436628);
    $lastWeek = date('Y-m-d 00:00:00', strtotime('monday last week'));
    $fromDate = date('Y-m-d 00:00:00', strtotime('monday this week'));
    //$fromDate = date('Y-m-d 00:00:00', strtotime('monday last week'));
    // thứ 2 tuần này
    $toDate = date('Y-m-d H:i:s');
    $totalTrade = GameBet::getTotalBet($user->User_ID, $fromDate, $toDate);
    $F1Active = GameBet::getF1Active($user->User_ID, $fromDate, $toDate);
    $VolumeTrade = GameBet::getVolumeTradeMember($user, $fromDate, $toDate);
    dd($totalTrade, $F1Active, $VolumeTrade);
    $user = User::find(436628);
    $PackageAgency = GameBet::getPackageAgency();
    $package = $PackageAgency[1];
    $checkCom = Money::checkCommissionAgency($user, $package, 10);
    dd($checkCom);
    $getF1 = User::where('User_Parent', 999999)->orderBy('User_RegisteredDatetime')->limit(10)->get();
    $user = User::find(310637);
    $checkUser = User::checkUserInBranchAvailable($user, $getF1);
    dd($user, $getF1, $checkUser);
    $userID = 120867;
    $fromDate = date('Y-m-d H:i:s', strtotime('monday last week'));
    $toDate = date('Y-m-d H:i:s');
    $checkBuyAgency = GameBet::NumberPackage($userID, $fromDate, $toDate, 0);
    dd($checkBuyAgency);
    if ($req->mail == 1) {
      $this->sendMailTotal();
      dd('send done');
    }
    $user = User::find(Session('user')->User_ID);
    $deposit_game = Money::where('Money_User', $user->User_ID)->where('Money_MoneyAction', 31)->where('Money_Currency', 9)->where('Money_MoneyStatus', 1)->where('Money_Time', '>=', strtotime('today'))->sum('Money_USDT');
    $withdraw_game = Money::where('Money_User', $user->User_ID)->where('Money_MoneyAction', 32)->where('Money_Currency', 9)->where('Money_MoneyStatus', 1)->where('Money_Time', '>=', strtotime('today'))->sum('Money_USDT');
    $balance_check = -$deposit_game - $withdraw_game + $user->user_balance_game_day;
    $balance = app('App\Http\Controllers\API\AgGameController')->balanceGame();
    $amountBalance = $balance['balance'];
    dd($amountBalance, $balance_check, ($amountBalance) * 100 != $balance_check);
    // $checkBalance = User::getBalance(314206, 3);
    // dd($checkBalance);
    // User::checkLevelUser(976042);
    // dd(321);
    $user = User::find(168050);
    $currency_id = 3;
    $price = 100;
    dd('insert interest done');
  }

  public function getInsertPackage(Request $req)
  {
    $user = $req->user;
    $package_id = $req->level;
    $f1_active = $req->f1;
    if (!$package_id) {
      dd('thiếu package level');
    }
    if (!$user) {
      dd('thiếu user id');
    }
    if (!$f1_active) {
      dd('thiếu số F1 Active');
    }
    $from = '2022-01-20 00:00:00';
    $to = '2022-02-26 00:00:00';
    $dataPackageWeek = [];
    $delete = DB::table('package_weekly_123betnow')->where('package_weekly_FromDate', '>=', $from)->where('package_weekly_User', $user)->delete();
    for ($i = 1; $i <= 4; $i++) {
      $dataPackageWeek[] = [
        'package_weekly_User' => $user,
        'package_weekly_FromDate' => $from,
        'package_weekly_ToDate' => $to,
        'package_weekly_Level' => $package_id,
        'package_weekly_TotalBet' => 0,
        'package_weekly_F1Active' => $f1_active,
        'package_weekly_Status' => 0
      ];
      $from = date('Y-m-d H:i:s', strtotime('+7 days', strtotime($from)));
      $to = date('Y-m-d H:i:s', strtotime('+7 days', strtotime($to)));
    }
    $insertPackage = DB::table('package_weekly_123betnow')->insert($dataPackageWeek);
    dd('insert package success');
  }

  public function insertIBTest(Request $req)
  {
    $user_id = $req->user;
    $amount = $req->amount;
    if (!$amount) {
      dd('thiếu số ib');
    }
    if (!$user_id) {
      dd('thiếu user id');
    }
    //save
    $currency = 3;
    $action = 65;
    $arrayInsert = array(
      'Money_User' => $user_id,
      'Money_USDT' => $amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Test Insert IB',
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currency,
      'Money_CurrentAmount' => $amount,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 0,
    );
    $insert = Money::insert($arrayInsert);
    dd('insert ib test success');
  }

  public function sendMailTotal()
  {

    $arrayMail = ['vanphg@gmail.com'];

    foreach ($arrayMail as $email) {
      dd($email, $arrayMail);
      $data = [];
      dispatch(new SendMailVNJobs('MailNotice2109', $data, 'Bạn sẽ được gặp trực tiếp CEO của dự án chơi Game kiếm $1000!', $email));
    }
    dd('done');
  }

  public function changeTree(Request $req)
  {
    abort(404);
    $parentNew = User::find(930092);
    $IDGetChild = 930092;
    $treeChild = User::find($IDGetChild);
    $child = User::where('User_Tree', 'LIKE', "%$IDGetChild%")->where('User_RegisteredDatetime', '>=', '2022-02-20 00:00:00')->orderBy('User_RegisteredDatetime')->get();
    $treeOld = str_replace(",$IDGetChild", "", $treeChild->User_Tree);
    dd($treeOld, $parentNew, $treeChild, $child);
    $arrChange = [];
    foreach ($child as $c) {
      //dd($c);
      if ($c->User_ID == $IDGetChild) {
        $c->User_Parent = $parentNew->User_ID;
      }
      $newTree = str_replace($treeOld, $parentNew->User_Tree, $c->User_Tree);
      $c->User_Tree = $newTree;
      //dd($c);
      $c->save();
      $arrChange[] = $c;
    }
    dd($arrChange);
  }

  public function checkBalance(Request $req)
  {
    //        $memberWM = app('App\Http\Controllers\API\WM555Controller')->getBalance(282048);
    //        dd($memberWM, 123);
    $user = User::paginate(15);
    if (!$req->page) {
      $page = 1;
    } else {
      $page = $req->page;
    }
    if ($page > $user->lastPage()) {
      dd('top');
    }
    $timeout = 3;

    $insert = array();
    foreach ($user as $v) {
      $SABalance = 0;
      $SPBalance = 0;
      $wmBalamce = 0;
      //            if ($v->User_Casino == 1) {
      //                $CasinoBalance = (array)app('App\Http\Controllers\API\SAGameController')->checkBalance($v->User_ID);
      //                $SABalance = $CasinoBalance * 1;
      //            }
      if ($v->User_WM555 == 1) {
        $wmBalamce = app('App\Http\Controllers\API\WM555Controller')->getBalance($v->User_ID);
        $wmBalamce = $wmBalamce * 1;
      }
      /*
                if ($v->User_SportBook == 1) {
                    $SportBookBalance = app('App\Http\Controllers\API\BCSportController')->checkBalance($v->User_ID);

                    $SPBalance = $SportBookBalance * 1;

                }*/
      $insert[] = array(
        'user' => $v->User_ID,
        'main' => User::getBalance($v->User_ID, 3),
        'casino' => $wmBalamce,
        'sportbook' => $SPBalance,
        'datetime' => date('Y-m-d H:i:s'),
      );
    }
    DB::table('checkBalance')->insert($insert);
    sleep(1);
    return view('Cron.quocReload', compact('page', 'timeout'));
    dd($insert);
    $users = Money::/*whereIn('User_ID', [])->*/ where('Money_MoneyStatus', -1)->where('Money_MoneyAction', 65)->where('Money_Time', '>=', 1617660000)->get();
    //dd($users);
    $arr = [];
    foreach ($users as $user) {
      $balance = User::getBalance($user->Money_User, 3);
      if ($balance < 0) {
        $arr[] = ['user' => $user->Money_User, 'balance' => $balance, $user];
      }
    }
    dd($arr);
  }

  public function postTest(Request $req)
  {
    dd('available');
  }

  public function wm555History($userID)
  {
    //$userID = $this->userID;
    $user = User::find($userID);
    if ($user->User_WM555 != 1) {
      //return $this->response(200, [], 'You have no game account, just register!', [], false);
      return false;
    }

    //if(!$user->User_Name) return $this->response(200, [], 'You have no game account, just register!', [], false);

    $startDate = (string)date('Y/m/d 00:00:00', strtotime('-1 month'));
    $endDate = (string)date('Y/m/d H:i:s', strtotime('+1 day', time()));

    $username = 'now' . $user->User_ID;
    $body = [
      'username' => $username,
      'startDate' => $startDate,
      'endDate' => $endDate,
    ];

    $client = new Client();
    $apiKey = config('utils.key');
    $res = $client->request('POST', 'http://ag.sieuhen.com/api/winloss?apikey=' . $apiKey, [
      'body' => json_encode($body)
    ]);

    $data = json_decode($res->getBody()->getContents());
    //return $this->response(200, ['data' => $res->getBody()], 'Deposit to WM555 game successful');

    if ($data->error_code != 0) return false;
    $betHistoryWM = BetHistoryWM::where('username', $username)->pluck('bet_id')->toArray();

    $results = [];

    foreach ($data->data as $value) {

      if (!in_array($value->BetID, $betHistoryWM)) {
        $results[] = [
          'username' => $value->Username,
          'game_type' => $value->GameType,
          'game_id' => $value->GameID,
          'web' => $value->web,
          'bet_id' => $value->BetID,
          'bet_amount' => $value->BetAmount,
          'rolling' => $value->Rolling,
          'result_amount' => $value->ResultAmount,
          'balance' => $value->Balance,
          'game_result' => $value->GameResult,
          'transaction_id' => $value->TransactionID,
          'bet_source' => $value->BetSource,
          'bet_type' => $value->BetType,
          'bet_time' => $value->BetTime,
          'payout_time' => $value->PayoutTime,
          'game_set' => $value->GameSet,
          'host_id' => $value->HostID,
          'host_name' => $value->HostName,
          'off_set' => $value->Offset,
        ];
      }
    }

    if (count($results) > 0) BetHistoryWM::insert($results);

    return true;
  }

  public function clearCache(Request $req)
  {
    //Clear route cache
    if ($req->route) {
      $exitCode = Artisan::call('route:cache');
      return 'Routes cache cleared';
    }

    //Clear config cache:
    if ($req->config) {
      $exitCode = Artisan::call('config:cache');
      return 'Config cache cleared';
    }

    // Clear application cache:
    if ($req->cache) {
      $exitCode = Artisan::call('cache:clear');
      return 'Application cache cleared';
    }

    // Clear view cache:
    if ($req->view) {
      $exitCode = Artisan::call('view:cache');
      return 'View cache cleared';
    }
  }

  public function getTestMiddw()
  {
    dd(123);
  }

  public function testMailDeposit()
  {
    $user = User::where('User_Email', 'minhkitoon@gmail.com')->first();
    $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email);
    //Job
    dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));

    return $this->response(200, [], 'Success!', [], true);
  }
}
