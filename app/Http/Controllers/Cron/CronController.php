<?php
namespace App\Http\Controllers\Cron;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Model\Money;
use App\Model\logMoney;
use App\Model\User;
use App\Model\Investment;
use App\Model\Wallet;
use App\Model\GameBet;

use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money as CB_Money;
use IEXBase\TronAPI\Tron;
use Carbon\Carbon;

use DB;
// Queue
use App\Jobs\SendMailJobs;
use App\Jobs\SendTelegramJobs;
use App\Jobs\PayInterestJobs;
use App\Jobs\PayInterestLotJobs;
use App\Jobs\PaySalesSystemJobs;

class CronController extends Controller{
  public $feeDeposit = 0;

  public static function coinbase(){
    $apiKey = 'ibhMj6w45jNK9f0O';
    $apiSecret = '79U7PHeZjIF97onRsUN1DWlElydXKuFA';
    $configuration = Configuration::apiKey($apiKey, $apiSecret);
    $client = Client::create($configuration);
    return $client;
  }

  public function cronResetBalanceBonusYesterday(Request $req){
    $beforeDay = strtotime(date("Y-m-d 00:00:00",strtotime($req->lastday)));
    $afterDay = $today = $beforeDay + 86400;
    $arrayActionPromotion = [10];

    //Lấy ra danh sách lệnh bonus
    $listBonus = Money::where('Money_Time', '>=', $beforeDay)
      ->where('Money_Time', '<', $afterDay)
      ->whereIn('Money_MoneyAction', $arrayActionPromotion)
      ->where('Money_MoneyStatus', 1)
      ->paginate(30);

    foreach($listBonus as $item){
      $balanceBonusToDay = Money::where("Money_User",$item->Money_User)
        ->where('Money_Time', '>=', $today)
        ->whereIn('Money_MoneyAction', $arrayActionPromotion)
        ->where('Money_MoneyStatus', 1)
        ->sum("Money_USDT");

      $balanBonus = User::getBalance($item->Money_User, 10) - $balanceBonusToDay;

      if($balanBonus <= 0){
        continue;
      }

      $withdrawBonus = $item->Money_USDT*1;
      //Lấy ra lệnh nạp đầu tiên trong ngày (lệnh này được km 50%)
      $getDepositBonus = Money::where('Money_User', $item->Money_User)
        ->where('Money_Time', '>=', $beforeDay)
        ->where('Money_Time', '<', $afterDay)
        ->where('Money_MoneyAction', 1)
        ->where('Money_MoneyStatus', 1)
        ->first();
      if(!$getDepositBonus){
        continue;
      }

      $totalDeposit = $getDepositBonus->Money_USDT*1; //Tính ra số tiền nạp lúc bonus
      if($totalDeposit > 300){
        $totalDeposit = 300;
      }



      //lấy ra số volume trade trong ngày
      $totalTradeBonus = GameBet::getShowTotalBet($item->Money_User, date('Y-m-d',$beforeDay), date('Y-m-d',$afterDay))['totalBet'];
      $amountCheckVolume = (float)($totalDeposit + $withdrawBonus);
      $depositX18 = (float)$amountCheckVolume;
      $depositX18 = $depositX18*18;
      if($depositX18 > $totalTradeBonus){
        if($withdrawBonus > 0){
          /*Khuyến mãi 50% và điều kiện rút reset mỗi ngày*/
          $insertResetArray = array(
            'Money_User' => $item->Money_User,
            'Money_USDT' => -$balanBonus,
            'Money_USDTFee' => 0,
            'Money_Time' => $afterDay,
            'Money_Comment' => 'Withdraw (Reset) bonus deposit with '.$balanBonus.' EUSD (From Balance Bonus)',
            'Money_MoneyAction' => 78,
            'Money_MoneyStatus' => 1,
            'Money_Address' => null,
            'Money_Currency' => 10,
            'Money_CurrentAmount' => $balanBonus,
            'Money_Rate' => 1,
            'Money_Confirm' => 0,
            'Money_Confirm_Time' => null,
            'Money_FromAPI' => 1
          );
          if($withdrawBonus > 0){
            Money::insert($insertResetArray);
          }
          /*Khuyến mãi 50% và điều kiện rút reset mỗi ngày*/
        }
        echo "$item->Money_User : Volume must be 18 times the total deposit amount and bonus - reset balance bonus last day $balanBonus";
        continue;
      }

      if(round($balanBonus) != round($withdrawBonus)){
        //dd(round($balanBonus) ,round($withdrawBonus),$item->Money_User);
        //dd($item->Money_User,User::getBalance($item->Money_User, 10),$balanceBonusToDay,round($balanBonus) , round($withdrawBonus),$balanceBonusToDay);
        echo "$item->Money_User : balance bonus error";
        continue;
      }

      if($withdrawBonus <= 0){
        echo "$item->Money_User : balance bonus = 0";
        continue;
      }
      //payment
      if($withdrawBonus > 0){
        $insertArray = array(
          array(
            'Money_User' => $item->Money_User,
            'Money_USDT' => -$withdrawBonus,
            'Money_USDTFee' => 0,
            'Money_Time' => $afterDay,
            'Money_Comment' => 'Withdraw bonus deposit with '.$withdrawBonus.' USD (From Balance Bonus)',
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
            'Money_User' => $item->Money_User,
            'Money_USDT' => $withdrawBonus,
            'Money_USDTFee' => 0,
            'Money_Time' => $afterDay,
            'Money_Comment' => 'Withdraw bonus deposit with '.$withdrawBonus.' USD (To Main Balance)',
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
        if(count($insertArray)){
          Money::insert($insertArray);
          echo "$item->Money_User : withdrawal $withdrawBonus$ successful";
        }
      }
    }
    $routeName = 'cron.cronResetBalanceBonusYesterday';
    $page = $listBonus->currentPage();
    $lastPage = $listBonus->lastPage();
    //dd($page, $lastPage);
    $timeout = 3;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 5;
      //return view('cron.reload',compact('routeName', 'page', 'timeout'));
    }
    //dd(32145);
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');

  }

  public function getDepositLuckyHero(Request $req)
  {
    $symbol = 'USDT';
    $currency = 18;
    try {
      $url = 'https://api.luckyhero.io/api/v1/history-deposit-123betnow';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
      ));
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
      $result = json_decode($result);
      $result = $result->data;
      foreach($result as $value){
        //check email có tồn tại
        $getEmail =  User::where('User_Email', trim($value->Money_Address))->first();
        if(!$getEmail){
          continue;
        }
        $hashtag = $value->Money_TXID;
        $hash = Money::where('Money_TXID', $hashtag)->where('Money_Address',  $value->Money_Address)->first();

        if(!$hash){
          $user = $getEmail;
          $price = $value->Money_USDT*-1;
          $money = new Money();
          $money->Money_User = $user->User_ID;
          $money->Money_USDT = $price;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.($price+0).' '.$symbol.' from Lucky Hero - ID Money: '.$value->Money_ID.' of ID User: '.$value->Money_User;
          $money->Money_Currency = $currency;
          $money->Money_CurrencyFrom = $currency;
          $money->Money_MoneyAction = 1;
          $money->Money_TXID = $hashtag;
          $money->Money_Address = $value->Money_Address;
          $money->Money_CurrentAmount = $price;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->multiplay_pool =  $value->multiplay_pool;
          $money->save();

          //$bonus = logMoney::getBonusDepositBirthday($user->User_ID, $price, $currency);
          $user = User::find($user->User_ID);
          //bonus Deposit
          Money::commissionDeposit($user);
          $message = "$user->User_Email Deposit from Lucky Hero $price $symbol\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$user->User_Email\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount: </b> "
            . $price." $symbol\n"
            . "<b>Amount USD: </b> "
            . ($price)." USDT\n"
            . "<b>Rate: </b> "
            . "$1 \n"
            . "<b>Info Lucky Hero: </b> "
            . " ID Money: $value->Money_ID - User ID: $value->Money_User - Address Pool: $value->multiplay_pool\n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());
          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$price, 'number_of_tokens'=>$price, 'currency'=> $symbol, 'EUSD'=>$price,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'LuckyHero');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));

        }

      }
      dd('check success');
    }
    catch (\Exception $e) {
      dd($e);
    }
  }

  public function getDepositUSDTHBG(Request $req)
  {
    $contractUSDT = '0x8c2da84ea88151109478846cc7c6c06c481dbe97';
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';
    $symbol = 'HBG';
    $currency = 11;
    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')->where('Address_Currency', $currency)->where('Address_IsUse', 1)->paginate(20);//->where('Address_User', 261438)
    //dd( $getAddress);
    foreach($getAddress as $address){
      $client = new \GuzzleHttp\Client();

      $contractUSDT = '0x55d398326f99059ff775485246999027b3197955';
      $apiKey = '6EWUSUAHDMTTGF96VRFI25NEU58R4ZV49E';
      $symbol = 'USDT';
      $currency = 11;
      $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractUSDT . '&address=' . $address->Address_Address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
      $result = json_decode($getData);
      $transactions = $result->result;

      foreach($transactions as $v){
        if (strtolower($v->to) != strtolower($address->Address_Address)) {
          continue;
        }
        $hashtag = $v->hash;
        $hash = Money::where('Money_Address', $hashtag)->first();
        if(!$hash){
          $user = $address;

          $value = filter_var($v->value / pow(10, $v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

          $money = new Money();
          $money->Money_User = $user->Address_User;
          $money->Money_USDT = $value;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.($value+0).' '.$symbol;
          $money->Money_Currency = 3;
          $money->Money_CurrencyFrom = $currency;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $hashtag;
          $money->Money_CurrentAmount = $value;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->save();
          //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
          $bonus = logMoney::getBonusDepositBirthday($user->Address_User, $value, $currency);
          $user = User::find($user->Address_User);
          //bonus Deposit
          Money::commissionDeposit($user);
          $message = "$user->User_Email Deposit $value $symbol\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$user->User_Email\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount: </b> "
            . $value." $symbol\n"
            . "<b>Amount USD: </b> "
            . ($value)." USDT\n"
            . "<b>Rate: </b> "
            . "$1 \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());
          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$value, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$value,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'BSC');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));

        }

      }

    }
    $routeName = 'cron.getDepositUSDTHBG';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    //dd($page, $lastPage);
    $timeout = 3;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 5;
      //return view('cron.reload',compact('routeName', 'page', 'timeout'));
    }
    //dd(32145);
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }
  public function getDepositUSDTBEP(Request $req)
  {
    $contractUSDT = '0x55d398326f99059ff775485246999027b3197955';
    $apiKey = '6EWUSUAHDMTTGF96VRFI25NEU58R4ZV49E';
    $symbol = 'USDT';
    $currency = 11;
    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')->where('Address_Currency', $currency)->where('Address_IsUse', 1)->paginate(20);
    foreach($getAddress as $address){

      $url = 'https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractUSDT . '&address=' . $address->Address_Address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey;
      $getData = file_get_contents($url);
      $result = json_decode($getData);
      $transactions = $result->result;
      foreach($transactions as $v){
        if (strtolower($v->to) != strtolower($address->Address_Address)) {
          continue;
        }
        $hashtag = $v->hash;
        $hash = Money::where('Money_Address', $hashtag)->first();
        if(!$hash){
          $user = $address;

          $value = filter_var($v->value / pow(10,$v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

          $money = new Money();
          $money->Money_User = $user->Address_User;
          $money->Money_USDT = $value;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.($value+0).' '.$symbol;
          $money->Money_Currency = 3;
          $money->Money_CurrencyFrom = $currency;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $hashtag;
          $money->Money_CurrentAmount = $value;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->save();
          //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
          $bonus = logMoney::getBonusDepositBirthday($user->Address_User, $value, $currency);
          $user = User::find($user->Address_User);
          //bonus Deposit
          Money::commissionDeposit($user);
          $message = "$user->User_Email Deposit $value $symbol\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$user->User_Email\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount: </b> "
            . $value." $symbol\n"
            . "<b>Amount USD: </b> "
            . ($value)." USDT\n"
            . "<b>Rate: </b> "
            . "$1 \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());
          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$value, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$value,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'BSC');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));

        }

      }

    }
    $routeName = 'cron.getDepositUSDTBEP';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    //dd($page, $lastPage);
    $timeout = 3;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 5;
      //return view('cron.reload',compact('routeName', 'page', 'timeout'));
    }
    //dd(32145);
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function depositTRC20Address(Request $req){
    $contractUSDT = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t';
    $symbol = 'USDT';
    $currency = 6;
    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')->where('Address_Currency', $currency)->where('Address_IsUse', 1)/*->where('Address_User', 851274)*/->paginate(20);
    foreach($getAddress as $address){
      $getData = file_get_contents('https://apilist.tronscan.org/api/contract/events?address='.$address->Address_Address.'&start=0&limit=100&contract='.$contractUSDT);//
      $data = json_decode($getData, true);
      $transactions = $data['data'];

      foreach($transactions as $v){
        if($v['transferToAddress'] != $address->Address_Address){ 
          continue;
        }
        $value = $v['amount']/1000000;
        $hashtag = $v['transactionHash'];
        $hash = Money::where('Money_Address', $hashtag)->first();
        if(!$hash){

          $user = $address;

          $money = new Money();
          $money->Money_User = $user->Address_User;
          $money->Money_USDT = $value;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.($value+0).' '.$symbol;
          $money->Money_Currency = 3;
          $money->Money_CurrencyFrom = $currency;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $hashtag;
          $money->Money_CurrentAmount = $value;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->save();
          //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
          $bonus = logMoney::getBonusDepositBirthday($user->Address_User, $value, $currency);
          $user = User::find($user->Address_User);
          //bonus Deposit
          Money::commissionDeposit($user);
          $message = "$user->User_Email Deposit $value $symbol\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$user->User_Email\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount: </b> "
            . $value." $symbol\n"
            . "<b>Amount USD: </b> "
            . ($value)." USDT\n"
            . "<b>Rate: </b> "
            . "$1 \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());

          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$value, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$value,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'TRC');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;

        }

      }
    }
    $routeName = 'cron.depositTRC20Address';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    //dd($page, $lastPage);
    $timeout = 3;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 5;
      //return view('cron.reload',compact('routeName', 'page', 'timeout'));
    }
    //dd(32145);
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function depositTrc20(Request $req){
    if($req->address){
      $this->depositTRC20Address($req);
    }
    $to = time()*1000;
    $from = $to - 86400;
    $aa = file_get_contents('https://apilist.tronscan.org/api/token_trc20/transfers?limit=100&start=0&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');

    $symbol = 'USDT';
    $currency = 6;
    $data = json_decode($aa, true);
    foreach($data['token_transfers'] as $v){
      $check = DB::table('address')->join('users', 'User_ID', 'Address_User')->where('Address_Address', $v['to_address'])->where('Address_IsUse', 1)->where('Address_Currency', $currency)->first();
      if($check){
        if($v['to_address'] != $check->Address_Address){
          continue;
        }
        $user = User::where('User_ID', $check->Address_User)->first();
        $value = $v['quant']/1000000;
        $hashtag = $v['transaction_id'];
        $hash = Money::where('Money_Address', $hashtag)->first();
        if(!$hash){
          $user = $check;

          $money = new Money();
          $money->Money_User = $user->Address_User;
          $money->Money_USDT = $value;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.($value+0).' '.$symbol;
          $money->Money_Currency = 3;
          $money->Money_CurrencyFrom = $currency;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $hashtag;
          $money->Money_CurrentAmount = $value;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->save();
          //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
          $bonus = logMoney::getBonusDepositBirthday($user->Address_User, $value, $currency);
          $user = User::find($user->Address_User);
          //bonus Deposit
          Money::commissionDeposit($user);
          $message = "$user->User_Email Deposit $value $symbol\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$user->User_Email\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount: </b> "
            . $value." $symbol\n"
            . "<b>Amount USD: </b> "
            . ($value)." USDT\n"
            . "<b>Rate: </b> "
            . "$1 \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());

          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$value, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$value,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'TRC');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;
        }
      }
    }
    return 1;
  }

  public function getHistorySA(Request $req){
    //$string = '2020-11-06T19:51:00.037';
    //dd(date('Y-m-d H:i:s', strtotime($string)));
    $getUsers = Money::join('users', 'User_ID', 'Money_User')->where('Money_MoneyAction', 3)->groupBy('Money_User')
      //->whereIn('Money_User', ['DAF2956047', 'DAF3348436'])
      ->where('User_Casino', 1)
      ->where('Money_Comment', 'LIKE', '%Casino Game%')
      ->paginate(5);
    //->get();
    //dd($getUsers);
    $yesterday = strtotime('-1 days');
    $yesterday = time();
    $Date = date('Y-m-d', $yesterday);
    //dd($yesterday, $Date);
    $dataInsert = [];
    $getBetImported = DB::table('sa_history')->pluck('BetID')->toArray();
    $upline = 'NOW';
    foreach($getUsers as $k=>$user){
      $query = '';
      $userID = $upline.$user->User_ID;
      $query = '?username='.$userID;
      $query .= '&date='.$Date;
      $requestHistory = file_get_contents('https://api.winboss.club/api/sagame/history'.$query);
      $dataResponse = json_decode($requestHistory);
      $gameWallet = $dataResponse->data->BetDetailList->BetDetail ?? (object)[];
      $gameWallet = json_decode(json_encode($gameWallet), true);
      foreach($gameWallet as $row){
        $betID = $row['BetID'];
        if(array_search($betID, $getBetImported) !== false){
          continue;
        }
        $convertRow = [];
        foreach($row as $key=>$value){
          if(is_array($value)){
            $convertRow[$key] = json_encode($value);
          }else{
            $value = str_replace("'", "", $value);
            $convertRow[$key] = $value;
          }
        }
        $convertRow['Username'] = str_replace($upline, '', $row['Username']);
        $convertRow['CreatedAt'] = date('Y-m-d H:i:s', strtotime($row['BetTime']));
        $convertRow['UpdatedAt'] = date('Y-m-d H:i:s', strtotime($row['BetTime']));
        $dataInsert[] = $convertRow;
      }
    }
    DB::table('sa_history')->insert($dataInsert);

    $page = $getUsers->currentPage();
    $lastPage = $getUsers->lastPage();
    $timeout = 120;
    $route = \Route::currentRouteName();
    if($page < $lastPage){
      return view('Cron.HistorySA',compact('page', 'timeout', 'route'));
    }
    $this->statisticalSANew($req);
    $page = 0;
    $timeout = 18000;
    return view('Cron.HistorySA',compact('page', 'timeout', 'route'));
    dd($dataInsert);
  }

  public function statisticalSANew(Request $req){
    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $mondayThisWeek = date('Y-m-d H:i:s');
    $date = date('Y-m-d H:i:s');
    $listTotalImportedWeek = DB::table('sa_history')->where('CreatedAt', '>=', $mondayLastWeek)/*->where('Username', 'DAF1481934 ')*/->where('statistical', 0)->get();
    //dd($listTotalImportedWeek);
    $game = 'Casino';
    $currency = 3;
    foreach($listTotalImportedWeek as $data){
      $userID = str_replace('NOW', '', $data->Username);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Currency', $currency)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_User', $user->User_ID)->first();
      //dd($getStatistical, $data);
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      if($getStatistical){
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet += $data->BetAmount;
        if($data->ResultAmount < 0){
          $totalLoss += abs($data->BetAmount);
        }else{
          $totalWin += abs($data->BetAmount);
        }
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Game', $game)
          ->where('statistical_Currency', $currency)
          ->where('statistical_Time', '>=', $mondayThisWeek)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);
      }else{
        $totalBet = $data->BetAmount;
        if($data->ResultAmount < 0){
          $totalLoss = abs($data->BetAmount);
        }else{
          $totalWin = abs($data->BetAmount);
        }
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $date,
            'statistical_Game' => $game,
            'statistical_Currency' => $currency,
            'statistical_UpdateTime' => $date,
          ]);
      }
      $updateStatistical = DB::table('sa_history')->where('id', $data->id)->update(['statistical'=>1]);
    }
  }

  public function depositEUSDAddress(Request $req){
    $contractUSDT = 'TNUN6pFXEH3p3jhDZaTro6gsLCZ5fT3rqs';
    $symbol = 'EUSD';
    $currency = 3;
    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')->where('Address_IsUse', 1)->where('Address_Currency', $currency)->paginate(20);
    //dd($getAddress);
    foreach($getAddress as $address){
      $getData = file_get_contents('https://apilist.tronscan.org/api/contract/events?address='.$address->Address_Address.'&start=0&limit=100&contract='.$contractUSDT);
      $data = json_decode($getData, true);
      $transactions = $data['data'];
      foreach($transactions as $v){
        if($v['transferToAddress'] != $address->Address_Address){
          continue;
        }
        $value = $v['amount']/1000000000000000000;
        $hashtag = $v['transactionHash'];
        $hash = Money::where('Money_Address', $hashtag)->first();

        //if($address->User_ID == 439094){
        //  	dd($hash, $v, $address, $address->Address_Address);
        //}
        if(!$hash){
          $user = $address;

          $money = new Money();
          $money->Money_User = $user->Address_User;
          $money->Money_USDT = $value;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.($value+0).' '.$symbol;
          $money->Money_Currency = 3;
          $money->Money_CurrencyFrom = $currency;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $hashtag;
          $money->Money_CurrentAmount = $value;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->save();
          //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
          $bonus = logMoney::getBonusDepositBirthday($user->Address_User, $value, $currency);

          $user = User::find($user->Address_User);
          //bonus Deposit
          Money::commissionDeposit($user);
          $message = "$user->User_Email Deposit $value $symbol\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$user->User_Email\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount: </b> "
            . $value." $symbol\n"
            . "<b>Amount USD: </b> "
            . ($value)." USDT\n"
            . "<b>Rate: </b> "
            . "$1 \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());

          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$value, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$value,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'TRC');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;

        }

      }
    }
    $routeName = 'cron.depositEUSDAddress';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    //dd($page, $lastPage);
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 10;
      //return view('cron.reload',compact('routeName', 'page', 'timeout'));
    }
    //dd(32145);
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function getDeposit(Request $req){

    $coin = DB::table('currency')->where('Currency_Symbol', $req->coin)->first();
    if(!$coin){
      dd('coin not exit');
    }
    $symbol = $coin->Currency_Symbol;
    $blockcypher = 'https://api.blockcypher.com/v1/'.strtolower($symbol).'/main/txs/';
    $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy();
    $transactions = app('App\Http\Controllers\System\CoinbaseController')->getAccountTransactions($symbol);
    $priceCoin = $rate[$symbol];
    // $tokenPrice = $rate['SKC'];

    foreach($transactions as $v){
      if($v->getamount()->getamount() > 0){
        $hash = Money::where('Money_Address', $v->getnetwork()->gethash())->first();

        if(!$hash){
          $transactionHash = $coin->Currency_ID == 2 ? "0x".$v->getnetwork()->gethash() : $v->getnetwork()->gethash();
          // dd($transactionHash);
          $client = new \GuzzleHttp\Client();
          $res = $client->request('GET', $blockcypher.$transactionHash);
          $response = $res->getBody();
          $json = json_decode($response);

          $addArray = array();

          foreach($json->addresses as $j){
            if($coin->Currency_Symbol == 'ETH'){
              $addArray[] = '0x'.$j;
            }else{
              $addArray[] = $j;
            }
          }

          $address = Wallet::select('Address_User')->whereIn('Address_Address', $addArray)->where('Address_IsUse', 1)->first();

          if($address){
            $amount = $v->getamount()->getamount();

            $money = new Money();
            $money->Money_User = $address->Address_User;
            $money->Money_USDT = $amount*$priceCoin;
            $money->Money_Time = time();
            $money->Money_Comment = 'Deposit '.($amount+0).' '.$symbol;
            $money->Money_Currency = 3;
            $money->Money_CurrencyFrom = $coin->Currency_ID;
            $money->Money_MoneyAction = 1;
            $money->Money_Address = $v->getnetwork()->gethash();
            $money->Money_CurrentAmount = $amount;
            $money->Money_Rate = $priceCoin;
            $money->Money_MoneyStatus = 1;
            $money->save();
            //                        $bonus = logMoney::getBonusDeposit($address->Address_User, $amount*$priceCoin);
            $bonus = logMoney::getBonusDepositBirthday($address->Address_User, $amount*$priceCoin, $coin->Currency_ID);

            //$updatebalance = User::updateBalance($address->Address_User, 5, $amount*$priceCoin);
            $user = User::find($address->Address_User);
            //bonus Deposit
            Money::commissionDeposit($user);
            $message = "$user->User_Email Deposit $amount $symbol\n"
              . "<b>User ID: </b> "
              . "$user->User_ID\n"
              . "<b>Email: </b> "
              . "$user->User_Email\n"
              . "<b>Address: </b> "
              . "$user->User_WalletAddress\n"
              . "<b>Amount: </b> "
              . $amount." $symbol\n"
              . "<b>Amount USD: </b> "
              . ($amount*$priceCoin)." USDT\n"
              . "<b>Rate: </b> "
              . "$ $priceCoin \n"
              . "<b>Submit Deposit Time: </b>\n"
              . date('d-m-Y H:i:s',time());

            dispatch(new SendTelegramJobs($message, -485635858));
            $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$amount, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$amount*$priceCoin,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'BSC');
            dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;

          }

        }
      }
    }
    echo 'check deposit success';exit;
  }

  public function getDepositUSDTWithAddress($address){
    if(!$address){
      dd('done');
    }
    $contractAddress = '0xdac17f958d2ee523a2206206994597c13d831ec7';
    $apiKey = 'GMGAYV28HNBZSAHUQQD3PQDXMFGZU7BMBP';
    $client = new \GuzzleHttp\Client(); //GuzzleHttp\Client

    $getData = file_get_contents('https://api.etherscan.io/api?module=account&action=tokentx&contractaddress='.$contractAddress.'&address='.$address.'&offset=5000&page=1&sort=desc&apikey='.$apiKey);
    $getTransactions = json_decode($getData);

    $address = DB::table('address')->select('Address_Address', 'Address_User')->where('Address_Currency', 5)->where('Address_IsUse', 1)->pluck('Address_Address')->toArray();


    foreach($getTransactions->result as $v){
      if(array_search($v->to, $address) !== false) {

        $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
        if(!$hash){

          $user = DB::table('address')->join('users', 'Address_User', 'User_ID')->where('Address_Address', $v->to)->where('Address_IsUse', 1)->where('Address_Currency', 5)->first();
          if(!$user){
            continue;
          }
          $value = filter_var($v->value/1000000, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
          $amountFee = $value * $this->feeDeposit;
          //cộng tiền
          $money = new Money();
          $money->Money_User = $user->Address_User;
          $money->Money_USDT = $value - $amountFee;
          $money->Money_USDTFee = $amountFee;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.$value.' USDT';
          $money->Money_Currency = 3;
          $money->Money_CurrencyFrom = 5;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $v->hash;
          $money->Money_CurrentAmount = $value;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->save();
          $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
          $bonus = logMoney::getBonusDepositBirthday($user->Address_User, $value, 5);
          //bonus Deposit
          $user = User::find($user->Address_User);
          Money::commissionDeposit($user);
          // 	Gửi telegram thông báo User verify
          $message = "$user->User_Email Deposit ".$value." USDT\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$user->User_Email\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount USD: </b> "
            . $value." USD\n"
            . "<b>Amount Coin: </b> "
            . $value." USDT\n"
            . "<b>Rate: </b> "
            . "$ 1 \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());

          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$value, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$value,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'ETH');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;

        }
      }
    }
    dd('stop');



  }

  public function getDepositUSDT(Request $req){
    if($req->address){
      $this->getDepositUSDTWithAddress($req->address);
      dd('check deposit address done');
    }
    // 		$address = DB::table('address')->where('Address_Currency', 5)->get();
    $contractAddress = '0xdac17f958d2ee523a2206206994597c13d831ec7';
    $apiKey = 'GMGAYV28HNBZSAHUQQD3PQDXMFGZU7BMBP';
    $client = new \GuzzleHttp\Client(); //GuzzleHttp\Client

    $getData = file_get_contents('https://api.etherscan.io/api?module=account&action=tokentx&contractaddress='.$contractAddress.'&offset=5000&page=1&sort=desc&apikey='.$apiKey);
    $getTransactions = json_decode($getData);

    $address = DB::table('address')->select('Address_Address', 'Address_User')->where('Address_Currency', 5)->where('Address_IsUse', 1)->pluck('Address_Address')->toArray();

    foreach($getTransactions->result as $v){
      if(array_search($v->to, $address) !== false) {

        $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
        if(!$hash){
          $user = DB::table('address')->join('users', 'Address_User', 'User_ID')->where('Address_Address', $v->to)->where('Address_IsUse', 1)->where('Address_Currency', 5)->first();
          if(!$user){
            continue;
          }

          $value = filter_var($v->value/1000000, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
          $amountFee = $value * $this->feeDeposit;
          //cộng tiền
          $money = new Money();
          $money->Money_User = $user->Address_User;
          $money->Money_USDT = $value - $amountFee;
          $money->Money_USDTFee = $amountFee;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.$value.' USDT';
          $money->Money_Currency = 3;
          $money->Money_CurrencyFrom = 5;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $v->hash;
          $money->Money_CurrentAmount = $value;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->save();
          $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
          $bonus = logMoney::getBonusDepositBirthday($user->Address_User, $value, 5);


          //$updatebalance = User::updateBalance($user->Address_User, 5, $value - $amountFee);

          //bonus Deposit
          $user = User::find($user->Address_User);
          Money::commissionDeposit($user);
          // 	Gửi telegram thông báo User verify
          $message = "$user->User_Email Deposit ".$value." USDT\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$user->User_Email\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount USD: </b> "
            . $value." USD\n"
            . "<b>Amount Coin: </b> "
            . $value." USDT\n"
            . "<b>Rate: </b> "
            . "$ 1 \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());

          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$value, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$value,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'ETH');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;
        }
      }
    }
    dd('check deposit usdt complete');
  }

  public function depositTRXWithHash($transactionHash){
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://apilist.tronscan.org/api/transaction-info?hash='.$transactionHash, [
    ])->getBody()->getContents();
    $v = json_decode($response)->contractData;
    $hash = Money::where('Money_Address', $transactionHash)->first();
    if(!$hash){
      $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('TRX');
      $priceCoin = $rate;
      $address = $v->to_address;
      $infoAddress = Wallet::join('users', 'users.User_ID', 'Address_User')->select('Address_User','User_Email')->where('Address_Address', $address)->first();

      if($infoAddress){
        $amount = $v->amount/1000000;
        $amountUSD =$amount * $priceCoin;
        $amountFee = $amountUSD * $this->feeDeposit;
        $amountFeeCoin = $amount * $this->feeDeposit;

        $money = new Money();
        $money->Money_User = $infoAddress->Address_User;
        $money->Money_USDT = $amount - $amountFeeCoin;
        $money->Money_USDTFee = $amountFeeCoin;
        // $money->Money_USDT = $amountUSD - $amountFee;
        // $money->Money_USDTFee = $amountFee;
        $money->Money_Time = time();
        $money->Money_Comment = 'Deposit '.($amount+0).' TRX';
        $money->Money_Currency = 9;
        $money->Money_MoneyAction = 1;
        $money->Money_Address = $transactionHash;
        $money->Money_CurrentAmount = $amount;
        $money->Money_Rate = $priceCoin;
        $money->Money_MoneyStatus = 1;
        $money->save();

        $user = User::find($infoAddress->Address_User);
        Money::checkSwapTRXToUSDT($user);
        // $tranfer = $this->TransferToAddress($address);
        // 	Gửi telegram thông báo User verify
        $message = "$infoAddress->User_Email Deposit $amount TRX\n"
          . "<b>User ID: </b> "
          . "$infoAddress->Address_User\n"
          . "<b>Email: </b> "
          . "$infoAddress->User_Email\n"
          . "<b>Amount: </b> "
          . $amount." TRX\n"
          . "<b>Rate: </b> "
          . "$ $priceCoin \n"
          . "<b>Submit Deposit Time: </b>\n"
          . date('d-m-Y H:i:s',time());

        //dispatch(new SendTelegramJobs($message, -485635858));

        $this->TransferToAddress($address);
      }

    }
    return true;

  }

  public function getDepositTRXWithAddress(Request $req){
    if($req->hash){
      $depositWithHash = $this->depositTRXWithHash($req->hash);
      dd('check success');
    }
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://apilist.tronscan.org/api/transfer?sort=-timestamp&count=true&limit=40&start=0', [
    ])->getBody()->getContents();
    $transactions = json_decode($response)->data;
    $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('TRX');
    $priceCoin = $rate;
    foreach($transactions as $v){
      $transactionHash = $v->transactionHash;
      $hash = Money::where('Money_Address', $transactionHash)->first();
      if(!$hash){
        $address = $v->transferToAddress;
        $infoAddress = Wallet::join('users', 'users.User_ID', 'Address_User')->select('Address_User','User_Email')->where('Address_Address', $address)->first();

        if($infoAddress){
          $amount = $v->amount/1000000;
          $amountUSD =$amount * $priceCoin;
          $amountFee = $amountUSD * $this->feeDeposit;
          $amountFeeCoin = $amount * $this->feeDeposit;

          $money = new Money();
          $money->Money_User = $infoAddress->Address_User;
          $money->Money_USDT = $amount - $amountFeeCoin;
          $money->Money_USDTFee = $amountFeeCoin;
          // $money->Money_USDT = $amountUSD - $amountFee;
          // $money->Money_USDTFee = $amountFee;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.($amount+0).' TRX';
          $money->Money_Currency = 9;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $transactionHash;
          $money->Money_CurrentAmount = $amount;
          $money->Money_Rate = $priceCoin;
          $money->Money_MoneyStatus = 1;
          $money->save();

          $user = User::find($infoAddress->Address_User);
          Money::checkSwapTRXToUSDT($user);
          // $tranfer = $this->TransferToAddress($address);
          // 	Gửi telegram thông báo User verify
          $message = "$infoAddress->User_Email Deposit $amount TRX\n"
            . "<b>User ID: </b> "
            . "$infoAddress->Address_User\n"
            . "<b>Email: </b> "
            . "$infoAddress->User_Email\n"
            . "<b>Amount: </b> "
            . $amount." TRX\n"
            . "<b>Rate: </b> "
            . "$ $priceCoin \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());

          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$amount, 'number_of_tokens'=>$amount, 'currency'=> $symbol, 'EUSD'=>$amount*$priceCoin,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'TRC');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;
          $this->TransferToAddress($address);
        }
      }
    }
    echo 'check deposit success';exit;
  }

  public function getDepositTRX(Request $req){
    if($req->hash){
      $this->depositTRXWithHash($req->hash);
    }
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://apilist.tronscan.org/api/transfer?sort=-timestamp&count=true&limit=500&start=0', [
    ])->getBody()->getContents();
    $transactions = json_decode($response)->data;
    $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('TRX');
    $priceCoin = $rate;
    foreach($transactions as $v){
      $transactionHash = $v->transactionHash;
      $hash = Money::where('Money_Address', $transactionHash)->first();
      if(!$hash){
        $address = $v->transferToAddress;
        $infoAddress = Wallet::join('users', 'users.User_ID', 'Address_User')->select('Address_User','User_Email')->where('Address_Address', $address)->first();

        if($infoAddress){
          $amount = $v->amount/1000000;
          $amountUSD =$amount * $priceCoin;
          $amountFee = $amountUSD * $this->feeDeposit;
          $amountFeeCoin = $amount * $this->feeDeposit;
          $money = new Money();
          $money->Money_User = $infoAddress->Address_User;
          $money->Money_USDT = $amount - $amountFeeCoin;
          $money->Money_USDTFee = $amountFeeCoin;
          // $money->Money_USDT = $amountUSD - $amountFee;
          // $money->Money_USDTFee = $amountFee;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit '.($amount+0).' TRX';
          $money->Money_Currency = 9;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $transactionHash;
          $money->Money_CurrentAmount = $amount;
          $money->Money_Rate = $priceCoin;
          $money->Money_MoneyStatus = 1;
          $money->save();

          $user = User::find($infoAddress->Address_User);
          Money::checkSwapTRXToUSDT($user);
          // $tranfer = $this->TransferToAddress($address);
          // 	Gửi telegram thông báo User verify
          $message = "$infoAddress->User_Email Deposit $amount TRX\n"
            . "<b>User ID: </b> "
            . "$infoAddress->Address_User\n"
            . "<b>Email: </b> "
            . "$infoAddress->User_Email\n"
            . "<b>Amount: </b> "
            . $amount." TRX\n"
            . "<b>Rate: </b> "
            . "$ $priceCoin \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s',time());

          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$amount, 'number_of_tokens'=>$amount, 'currency'=> $symbol, 'EUSD'=>$amount*$priceCoin,'wallet'=> $user->User_WalletAddress, 'hash_code'=>$hashtag, 'network'=>'TRC');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;
          $this->TransferToAddress($address);
        }

      }
    }
    echo 'check deposit success';exit;
  }

  public function TransferToAddress($from, $amount = 0, $to = 'TJNV36KNQq81EFNbdWH57vRRKmo5muz79G', $action = 'Send To Big Address'){
    $checkAddressTo = Wallet::where('Address_Address', $to)->where('Address_Currency', 9)->first();
    // dd($checkAddressTo);
    if($checkAddressTo){
      $hexAddress = $checkAddressTo->Address_HexAddress;
    }else{
      $hexAddress = Wallet::base58check2HexString($to);
      if(!$hexAddress){
        $dataLog = [
          'Log_TRX_From' => $from,
          'Log_TRX_To' => $to,
          'Log_TRX_Amount' => 0,
          'Log_TRX_Action' => $action,
          'Log_TRX_Comment' => 'Transfer From '.$from.' To '.$to,
          'Log_TRX_Error' => 'Error Hex Address',
          'Log_TRX_Time' => date('Y-m-d H:i:s'),
          'Log_TRX_Status' => 1
        ];
        DB::table('log_TRX')->insert($dataLog);
        return 'Don\'t find hex address';
      }
    }
    $checkAddress = Wallet::where('Address_Address', $from)->first();
    // dd($checkAddress, $hexAddress);
    if(!$checkAddress){
      $dataLog = [
        'Log_TRX_From' => $from,
        'Log_TRX_To' => $to,
        'Log_TRX_Amount' => 0,
        'Log_TRX_Action' => $action,
        'Log_TRX_Comment' => 'Transfer From '.$from.' To '.$to,
        'Log_TRX_Error' => 'Address TRX Not found in Database',
        'Log_TRX_Time' => date('Y-m-d H:i:s'),
        'Log_TRX_Status' => 1
      ];
      DB::table('log_TRX')->insert($dataLog);
      return 'Don\'t find address';
    }
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'https://api.trongrid.io/wallet/getaccount', [
      'json'    => ['address' => $checkAddress->Address_HexAddress],
    ])->getBody()->getContents();
    $data = json_decode($response);
    dd($checkAddress->Address_HexAddress, $response, $data);
    if(!isset($data->balance) || $data->balance <= 0){
      $dataLog = [
        'Log_TRX_From' => $from,
        'Log_TRX_To' => $to,
        'Log_TRX_Amount' => 0,
        'Log_TRX_Action' => $action,
        'Log_TRX_Comment' => 'Transfer From '.$from.' To '.$to,
        'Log_TRX_Error' => 'Account From Not Found Data',
        'Log_TRX_Time' => date('Y-m-d H:i:s'),
        'Log_TRX_Status' => 1
      ];
      DB::table('log_TRX')->insert($dataLog);
      return 'Account Not Found Data';
    }
    $balance = round($amount > 0 ? $amount*1000000 : $data->balance);
    if($balance > $data->balance){
      $dataLog = [
        'Log_TRX_From' => $from,
        'Log_TRX_To' => $to,
        'Log_TRX_Amount' => $amount,
        'Log_TRX_Action' => $action,
        'Log_TRX_Comment' => 'Transfer From '.$from.' To '.$to,
        'Log_TRX_Error' => 'Balance Is Not Enough',
        'Log_TRX_Time' => date('Y-m-d H:i:s'),
        'Log_TRX_Status' => 1
      ];
      DB::table('log_TRX')->insert($dataLog);
      return 'Balance Is Not Enough';
    }

    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'https://api.trongrid.io/wallet/easytransferbyprivate', [
      'json'    => ['privateKey' => $checkAddress->Address_PrivateKey,
                    'toAddress' => $hexAddress,
                    'amount' => $balance
                   ],
    ])->getBody()->getContents();
    $dataSend = json_decode($response);
    if(isset($dataSend->result->result) && $dataSend->result->result == true){
      $dataLog = [
        'Log_TRX_From' => $checkAddress->Address_HexAddress,
        'Log_TRX_To' => $hexAddress,
        'Log_TRX_Amount' => $balance/1000000,
        'Log_TRX_Action' => $action,
        'Log_TRX_Hash' => $dataSend->transaction->txID,
        'Log_TRX_Comment' => 'Transfer '.($balance/1000000).' TRX From '.$from.' To '.$to,
        'Log_TRX_Time' => date('Y-m-d H:i:s'),
        'Log_TRX_Status' => 1
      ];
      DB::table('log_TRX')->insert($dataLog);
      return true;
    }
    $message = hex2bin($dataSend->result->message);
    $dataLog = [
      'Log_TRX_From' => $checkAddress->Address_HexAddress,
      'Log_TRX_To' => $hexAddress,
      'Log_TRX_Amount' => $balance/1000000,
      'Log_TRX_Action' => $action,
      'Log_TRX_Comment' => 'Transfer '.($balance/1000000).' TRX From '.$from.' To '.$to,
      'Log_TRX_Error' => $message,
      'Log_TRX_Time' => date('Y-m-d H:i:s'),
      'Log_TRX_Status' => 1
    ];
    DB::table('log_TRX')->insert($dataLog);
    return $message;
  }
}
