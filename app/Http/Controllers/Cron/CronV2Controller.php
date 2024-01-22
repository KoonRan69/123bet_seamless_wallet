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
use App\Model\LogUser;
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

class CronV2Controller extends Controller{

  public function checkUserLoginDeposit(Request $req){

    $listUser = LogUser::where('action','login')->orderByDesc('id')->limit('10')->get();
    foreach($listUser as $item){
      $user = User::find($item->user);
      $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
        ->where('Address_User', $user->User_ID)
        ->where('Address_IsUse', 1)->get();
      foreach ($getAddress as $address) {
        if ($address->Address_Currency == 3) {
          app('App\Http\Controllers\TestDepositNewController')->checkDepositEUSD($address->Address_User, $user->User_Email, $address->Address_Address);
        }
        if ($address->Address_Currency == 5) {
          app('App\Http\Controllers\TestDepositNewController')->checkDepositUSDTR20($address->Address_User, $user->User_Email, $address->Address_Address);
        }
        if ($address->Address_Currency == 6) {
          app('App\Http\Controllers\TestDepositNewController')->checkDepositUSDTR20($address->Address_User, $user->User_Email, $address->Address_Address);
        }
        if ($address->Address_Currency == 7) {
          $contract = '0x8c2da84ea88151109478846cc7c6c06c481dbe97';
          $symbol = 'HBG';
          app('App\Http\Controllers\TestDepositNewController')->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
        }
        if ($address->Address_Currency == 8) {
          app('App\Http\Controllers\TestDepositNewController')->checkDepositEBP($address->Address_User, $user->User_Email, $address->Address_Address);
        }
        if ($address->Address_Currency == 11) {
          app('App\Http\Controllers\TestDepositNewController')->getDepositUSDTBEP($address->Address_User, $user->User_Email, $address->Address_Address);
        }
        if ($address->Address_Currency == 12) {
          $contract = '0xfea6ab80cd850c3e63374bc737479aeec0e8b9a1';
          $symbol = 'SOL';
          app('App\Http\Controllers\TestDepositNewController')->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
        }
        if ($address->Address_Currency == 13) {
          $contract = '0xaec945e04baf28b135fa7c640f624f8d90f1c3a6';
          $symbol = 'C98';
          app('App\Http\Controllers\TestDepositNewController')->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
        }
        if ($address->Address_Currency == 14) {
          $contract = '0x3ee2200efb3400fabb9aacf31297cbdd1d435d47';
          $symbol = 'ADA';
          app('App\Http\Controllers\TestDepositNewController')->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
        }
        if ($address->Address_Currency == 15) {
          app('App\Http\Controllers\TestDepositNewController')->checkDepositTRX($address->Address_User, $user->User_Email, $address->Address_Address);
        }
        if ($address->Address_Currency == 16) {
          $symbol = 'BNB';
          app('App\Http\Controllers\TestDepositNewController')->checkDepositBNB($address->Address_User, $user->User_Email, $address->Address_Address, $symbol, $address->Address_Currency);
        }
      }
    }
    dd('Deposit success');
  }

  public function checkDepositUSDTBEP()
  {
    $contractUSDT = '0x55d398326f99059ff775485246999027b3197955';
    $apiKey = '6EWUSUAHDMTTGF96VRFI25NEU58R4ZV49E';
    $symbol = 'USDT';
    $currency = 11;

    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_Currency', $currency)//->where('Address_User',350205)
      ->where('Address_IsUse', 1)->paginate(20);

    foreach($getAddress as $value){
      $userID = $value->Address_User;
      $userEmail = $value->User_Email;
      $address = $value->Address_Address;

      $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractUSDT . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
      $transactions = json_decode($getData)->result;

      foreach ($transactions as $v) {
        if (strtolower($v->to) != strtolower($address)) {
          continue;
        }
        $hashtag = $v->hash;
        $hash = Money::where('Money_Address', $hashtag)->first();
        if (!$hash) {
          $user = $address;
          if($v->value / pow(10, $v->tokenDecimal) < 0.0001){
            continue;
          }
          $value = filter_var($v->value / pow(10, $v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

          $money = new Money();
          $money->Money_User = $userID;
          $money->Money_USDT = $value;
          $money->Money_Time = time();
          $money->Money_Comment = 'Deposit ' . ($value + 0) . ' ' . $symbol;
          $money->Money_Currency = 3;
          $money->Money_CurrencyFrom = $currency;
          $money->Money_MoneyAction = 1;
          $money->Money_Address = $hashtag;
          $money->Money_CurrentAmount = $value;
          $money->Money_Rate = 1;
          $money->Money_MoneyStatus = 1;
          $money->save();
          //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
          $bonus = logMoney::getBonusDepositBirthday($userID, $value, $currency);
          $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $value, $currency);
          $user = User::find($userID);
          //bonus Deposit
          Money::commissionDeposit($user);
          $message = "$userEmail Deposit $value $symbol\n"
            . "<b>User ID: </b> "
            . "$user->User_ID\n"
            . "<b>Email: </b> "
            . "$userEmail\n"
            . "<b>Address: </b> "
            . "$user->User_WalletAddress\n"
            . "<b>Amount: </b> "
            . $value . " $symbol\n"

            . "<b>Hash: </b> "
            . " $hashtag\n"

            . "<b>Amount USD: </b> "
            . ($value) . " USDT\n"
            . "<b>Rate: </b> "
            . "$1 \n"
            . "<b>Submit Deposit Time: </b>\n"
            . date('d-m-Y H:i:s', time());
          dispatch(new SendTelegramJobs($message, -485635858));
          $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email, 'deposit_amount' => $value, 'fee_deposit' => 0, 'number_of_tokens' => $value, 'currency' => $symbol, 'EUSD' => $value, 'wallet' => $user->User_WalletAddress, 'hash_code' => $hashtag, 'network' => 'BSC');
          dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));
        }
      }
    }
    $routeName = 'cronv2.checkDepositUSDTBEP';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function checkDepositBNB()
  {
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';
    $symbol = 'BNB';
    $feeDeposit = 0.1;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
    $currency = 16;

    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_Currency', $currency)//->where('Address_User',350205)
      ->where('Address_IsUse', 1)->paginate(20);

    foreach($getAddress as $value){
      $userID = $value->Address_User;
      $userEmail = $value->User_Email;
      $address = $value->Address_Address;

      $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=txlist&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
      $getTransactions = json_decode($getData);

      if (is_array($getTransactions->result) || is_object($getTransactions->result))
      {
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
            $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $amountUSDT, $currency);

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

              . "<b>Hash: </b> "
              . " $v->hash\n"

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
      }


    }
    $routeName = 'cronv2.checkDepositBNB';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');

  }

  public function checkDepositTRX(){
    $contractUSDT = 'TNUN6pFXEH3p3jhDZaTro6gsLCZ5fT3rqs';
    $symbol = 'TRX';
    $feeDeposit = 0.1;
    $currency = 15;


    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy("TRX");

    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_Currency', $currency)//->where('Address_User',350205)
      ->where('Address_IsUse', 1)->paginate(20);

    foreach($getAddress as $value){

      $userID = $value->Address_User;
      $userEmail = $value->User_Email;
      $address = $value->Address_Address;

      $getData = file_get_contents('https://apilist.tronscan.org/api/transfer?sort=-timestamp&count=true&limit=200&start=0&address='.$address);
      $data = json_decode($getData, true);
      $transactions = $data['data'];

      if (is_array($transactions) || is_object($transactions))
      {
        foreach($transactions as $v){
          if($v['transferToAddress'] != $address){
            continue;
          }
          if($v['confirmed'] !== true){
            continue;
          }
          if($v['tokenInfo']['tokenName'] != 'trx' || $v['tokenInfo']['tokenAbbr'] != 'trx'){
            continue;
          }
          $value = $v['amount']/pow(10, $v['tokenInfo']['tokenDecimal']);
          $hashtag = $v['transactionHash'];
          $hash = Money::where('Money_Address', $hashtag)->first();

          //if($address->User_ID == 439094){
          //  	dd($hash, $v, $address, $address->Address_Address);
          //}
          if(!$hash){
            //$user = $address;

            $amountFee = $value * $feeDeposit;
            $amountUSDT = $value * $rate;
            $amountUSDTFee = $amountFee * $rate;
            $money = new Money();
            $money->Money_User = $userID;
            $money->Money_USDT = $amountUSDT;
            $money->Money_USDTFee = -$amountUSDTFee;
            $money->Money_Time = time();
            $money->Money_Comment = 'Deposit '.($value+0).' '.$symbol;
            $money->Money_Currency = 3;
            $money->Money_CurrencyFrom = $currency;
            $money->Money_MoneyAction = 1;
            $money->Money_Address = $hashtag;
            $money->Money_CurrentAmount = $value;
            $money->Money_Rate = $rate;
            $money->Money_MoneyStatus = 1;
            $money->save();
            //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
            $bonus = logMoney::getBonusDepositBirthday($userID, $value, $currency);
            $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $value, $currency);

            $user = User::find($userID);
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

              . "<b>Hash: </b> "
              . " $hashtag\n"

              . "<b>Amount USD: </b> "
              . ($amountUSDT)." USDT\n"
              . "<b>Rate: </b> "
              . "$$rate \n"
              . "<b>Submit Deposit Time: </b>\n"
              . date('d-m-Y H:i:s',time());

            dispatch(new SendTelegramJobs($message, -485635858));
            $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'deposit_amount'=>$value,'fee_deposit'=>$amountUSDTFee, 'number_of_tokens'=>$value, 'currency'=> $symbol, 'EUSD'=>$amountUSDT,'wallet'=> $v['transferFromAddress'], 'hash_code'=>$hashtag, 'network'=>'TRX');
            dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID)) ;
          }
        }
      }
    }
    $routeName = 'cronv2.checkDepositTRX';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function checkDepositTokenBEP20ADA()
  {
    $contract = '0x3ee2200efb3400fabb9aacf31297cbdd1d435d47';
    $symbol = 'ADA';
    $currency = 14;

    $contractAddress = $contract;
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';

    $feeDeposit = 0;
    if ($currency == 4 || $currency == 12 || $currency == 13 || $currency == 14 || $currency == 15) {
      $feeDeposit = 0.1;
    } elseif ($currency == 7) {
      $feeDeposit = 0;
    }
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);

    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_Currency', $currency)
      ->where('Address_IsUse', 1)->paginate(20);

    foreach($getAddress as $value){

      $userID = $value->Address_User;
      $userEmail = $value->User_Email;
      $address = $value->Address_Address;

      $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
      $getTransactions = json_decode($getData);

      if (is_array($getTransactions->result) || is_object($getTransactions->result))
      {
        foreach ($getTransactions->result as $v) {

          if (strtoupper($v->to) != strtoupper($address)) {
            continue;
          }
          if($v->confirmations <= 3){
            continue;
          }
          $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
          if (!$hash) {
            $value = filter_var($v->value / pow(10, $v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $amountFee = $value * $feeDeposit;
            $amountUSDT = $value * $rate;
            $amountUSDTFee = $amountFee * $rate;
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
            $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $amountUSDT, $currency);

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

              . "<b>Hash: </b> "
              . " $v->hash\n"

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
      }


    }
    $routeName = 'cronv2.checkDepositTokenBEP20ADA';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function checkDepositTokenBEP20C98()
  {
    $contract = '0xaec945e04baf28b135fa7c640f624f8d90f1c3a6';
    $symbol = 'C98';
    $currency = 13;

    $contractAddress = $contract;
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';

    $feeDeposit = 0;
    if ($currency == 4 || $currency == 12 || $currency == 13 || $currency == 14 || $currency == 15) {
      $feeDeposit = 0.1;
    } elseif ($currency == 7) {
      $feeDeposit = 0;
    }
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);

    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_Currency', $currency)
      ->where('Address_IsUse', 1)->paginate(20);

    foreach($getAddress as $value){

      $userID = $value->Address_User;
      $userEmail = $value->User_Email;
      $address = $value->Address_Address;

      $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
      $getTransactions = json_decode($getData);

      if (is_array($getTransactions->result) || is_object($getTransactions->result))
      {
        foreach ($getTransactions->result as $v) {
          if (strtoupper($v->to) != strtoupper($address)) {
            continue;
          }
          if($v->confirmations <= 3){
            continue;
          }
          $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
          if (!$hash) {
            $value = filter_var($v->value / pow(10, $v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $amountFee = $value * $feeDeposit;
            $amountUSDT = $value * $rate;
            $amountUSDTFee = $amountFee * $rate;
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
            $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $amountUSDT, $currency);

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

              . "<b>Hash: </b> "
              . " $v->hash\n"

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
      }


    }
    $routeName = 'cronv2.checkDepositTokenBEP20C98';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function checkDepositTokenBEP20SOL()
  {
    $contract = '0xfea6ab80cd850c3e63374bc737479aeec0e8b9a1';
    $symbol = 'SOL';
    $currency = 12;

    $contractAddress = $contract;
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';

    $feeDeposit = 0;
    if ($currency == 4 || $currency == 12 || $currency == 13 || $currency == 14 || $currency == 15) {
      $feeDeposit = 0.1;
    } elseif ($currency == 7) {
      $feeDeposit = 0;
    }
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);

    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_Currency', $currency)
      ->where('Address_IsUse', 1)->paginate(20);

    foreach($getAddress as $value){

      $userID = $value->Address_User;
      $userEmail = $value->User_Email;
      $address = $value->Address_Address;

      $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
      $getTransactions = json_decode($getData);

      if (is_array($getTransactions->result) || is_object($getTransactions->result))
      {
        foreach ($getTransactions->result as $v) {
          if (strtoupper($v->to) != strtoupper($address)) {
            continue;
          }
          if($v->confirmations <= 3){
            continue;
          }
          $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
          if (!$hash) {
            $value = filter_var($v->value / pow(10, $v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $amountFee = $value * $feeDeposit;
            $amountUSDT = $value * $rate;
            $amountUSDTFee = $amountFee * $rate;
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
            $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $amountUSDT, $currency);

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

              . "<b>Hash: </b> "
              . " $v->hash\n"

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
      }


    }
    $routeName = 'cronv2.checkDepositTokenBEP20SOL';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }

  public function checkDepositTokenBEP20HBG()
  {
    $contract = '0x8c2da84ea88151109478846cc7c6c06c481dbe97';
    $symbol = 'HBG';
    $currency = 7;

    $contractAddress = $contract;
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';

    $feeDeposit = 0;
    if ($currency == 4 || $currency == 12 || $currency == 13 || $currency == 14 || $currency == 15) {
      $feeDeposit = 0.1;
    } elseif ($currency == 7) {
      $feeDeposit = 0;
    }
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);

    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_Currency', $currency)
      ->where('Address_IsUse', 1)->paginate(20);

    foreach($getAddress as $value){

      $userID = $value->Address_User;
      $userEmail = $value->User_Email;
      $address = $value->Address_Address;

      $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
      $getTransactions = json_decode($getData);

      if (is_array($getTransactions->result) || is_object($getTransactions->result))
      {
        foreach ($getTransactions->result as $v) {
          if (strtoupper($v->to) != strtoupper($address)) {
            continue;
          }
          if($v->confirmations <= 3){
            continue;
          }
          $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
          if (!$hash) {
            $value = filter_var($v->value / pow(10, $v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $amountFee = $value * $feeDeposit;
            $amountUSDT = $value * $rate;
            $amountUSDTFee = $amountFee * $rate;
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
            $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $amountUSDT, $currency);

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

              . "<b>Hash: </b> "
              . " $v->hash\n"

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
      }


    }
    $routeName = 'cronv2.checkDepositTokenBEP20HBG';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }


  public function checkDepositUSDTR20()
  {
    $contractUSDT = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t';
    $symbol = 'USDT';
    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->whereIn('Address_Currency', [5,6])//->where('Address_User',350205)
      ->where('Address_IsUse', 1)->paginate(20);
    foreach($getAddress as $value){
      $currency = $value->Address_Currency;

      $userID = $value->Address_User; 
      $userEmail = $value->User_Email; 
      $address = $value->Address_Address;


      $getData = file_get_contents('https://apilist.tronscan.org/api/contract/events?address=' . $address . '&start=0&limit=100&contract=' . $contractUSDT);
      $data = json_decode($getData, true);
      $transactions = $data['data'];

      if (is_array($transactions) || is_object($transactions))
      {
        foreach ($transactions as $v) {
          if ($v['transferToAddress'] != $address) {
            continue;
          }
          $value = $v['amount'] / 1000000;
          $hashtag = $v['transactionHash'];
          $hash = Money::where('Money_Address', $hashtag)->first();

          if (!$hash) {
            $money = new Money();
            $money->Money_User = $userID;
            $money->Money_USDT = $value;
            $money->Money_Time = time();
            $money->Money_Comment = 'Deposit ' . ($value + 0) . ' ' . $symbol;
            $money->Money_Currency = 3;
            $money->Money_CurrencyFrom = $currency;
            $money->Money_MoneyAction = 1;
            $money->Money_Address = $hashtag;
            $money->Money_CurrentAmount = $value;
            $money->Money_Rate = 1;
            $money->Money_MoneyStatus = 1;
            $money->save();
            //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
            $bonus = logMoney::getBonusDepositBirthday($userID, $value, $currency);
            $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $value, $currency);
            $user = User::find($userID);
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
              . $value . " $symbol\n"

              . "<b>Hash: </b> "
              . " $hashtag\n"

              . "<b>Amount USD: </b> "
              . ($value) . " USDT\n"
              . "<b>Rate: </b> "
              . "$1 \n"
              . "<b>Submit Deposit Time: </b>\n"
              . date('d-m-Y H:i:s', time());

            dispatch(new SendTelegramJobs($message, -485635858));
            $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email, 'deposit_amount' => $value, 'fee_deposit' => 0, 'number_of_tokens' => $value, 'currency' => $symbol, 'EUSD' => $value, 'wallet' => $v['transferFromAddress'], 'hash_code' => $hashtag, 'network' => 'BSC');
            dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));
          }
        }
      }


    }
    $routeName = 'cronv2.checkDepositUSDTR20';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }


  public function checkDepositEUSD()
  {

    $contractUSDT = 'TNUN6pFXEH3p3jhDZaTro6gsLCZ5fT3rqs';
    $symbol = 'EUSD';
    $currency = 3;

    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_Currency', $currency)
      ->where('Address_IsUse', 1)->paginate(20);
    foreach($getAddress as $value){
      $userID = $value->Address_User; 
      $userEmail = $value->User_Email; 
      $address = $value->Address_Address;

      $getData = file_get_contents('https://apilist.tronscan.org/api/contract/events?address=' . $address . '&start=0&limit=100&contract=' . $contractUSDT);
      $data = json_decode($getData, true);
      $transactions = $data['data'];

      if (is_array($transactions) || is_object($transactions))
      {
        foreach ($transactions as $v) {
          if ($v['transferToAddress'] != $address) {
            continue;
          }
          $value = $v['amount'] / 1000000000000000000;
          $hashtag = $v['transactionHash'];
          $hash = Money::where('Money_Address', $hashtag)->first();

          if (!$hash) {
            $money = new Money();
            $money->Money_User = $userID;
            $money->Money_USDT = $value;
            $money->Money_Time = time();
            $money->Money_Comment = 'Deposit ' . ($value + 0) . ' ' . $symbol;
            $money->Money_Currency = 3;
            $money->Money_CurrencyFrom = $currency;
            $money->Money_MoneyAction = 1;
            $money->Money_Address = $hashtag;
            $money->Money_CurrentAmount = $value;
            $money->Money_Rate = 1;
            $money->Money_MoneyStatus = 1;
            $money->save();
            //                    $bonus = logMoney::getBonusDeposit($user->Address_User, $value);
            $bonus = logMoney::getBonusDepositBirthday($userID, $value, $currency);
            $bonusFirstDay = logMoney::getBonusDailyRecharge($userID, $value, $currency);

            $user = User::find($userID);
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
              . $value . " $symbol\n"

              . "<b>Hash: </b> "
              . " $hashtag\n"

              . "<b>Amount USD: </b> "
              . ($value) . " USDT\n"
              . "<b>Rate: </b> "
              . "$1 \n"
              . "<b>Submit Deposit Time: </b>\n"
              . date('d-m-Y H:i:s', time());

            dispatch(new SendTelegramJobs($message, -485635858));
            $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email, 'deposit_amount' => $value, 'fee_deposit' => 0, 'number_of_tokens' => $value, 'currency' => $symbol, 'EUSD' => $value, 'wallet' => $v['transferFromAddress'], 'hash_code' => $hashtag, 'network' => 'BSC');
            dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));
          }
        }
      }


    }
    $routeName = 'cronv2.checkDepositEUSD';
    $page = $getAddress->currentPage();
    $lastPage = $getAddress->lastPage();
    $timeout = 5;
    if($page >= $lastPage){
      $page = 0;
      $timeout = 15;
    }
    return view('Cron.reload',compact('routeName', 'page', 'timeout'));
    dd('check success');
  }
}