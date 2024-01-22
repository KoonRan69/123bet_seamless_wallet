<?php

namespace App\Http\Controllers;

use App\Model\Eggs;
use App\Model\PoolTypes;
use App\Model\User;
use App\Model\Money;
use App\Model\GameBet;
use App\Model\logMoney;
use App\Jobs\SendTelegramJobs;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Artisan;
use DB, Excel;

use App\Exports\DepositExport;
use App\Jobs\SendMailVNJobs;
use App\Jobs\GetHistoryWM555Jobs;
use App\Jobs\SendMailJobs;

class TestDepositNewController extends Controller
{

  public function __construct()
  {
    $this->feeWithdraw = config('coin.EUSD.WithdrawFee');
    $this->feeTransfer = config('coin.EUSD.TransferFee');
    // dd($this->feeWithdraw);
    $amount = 50;
    $amountFee = $amount * ($this->feeWithdraw / 100);
    $this->config = config('utils.wm555');
    // dd($amountFee);
  }

  public function checkDepositTRX($userID, $userEmail, $address){
    $contractUSDT = 'TNUN6pFXEH3p3jhDZaTro6gsLCZ5fT3rqs';
    $symbol = 'TRX';
    $feeDeposit = 0.1;
    $currency = 15;
    //dd($getAddress);

    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy("TRX");
    //$rate = $this->coinRateBuyEBP($symbol);
    $currency = $currency;
    $getData = file_get_contents('https://apilist.tronscan.org/api/transfer?sort=-timestamp&count=true&limit=200&start=0&address='.$address);
    $data = json_decode($getData, true);
    $transactions = $data['data'];
    //dd($transactions);
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

  public function checkDepositBNB($userID, $userEmail, $address, $symbol, $currency)
  {
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';
    $symbol = $symbol;
    $feeDeposit = 0.1;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
    //$rate = $this->coinRateBuyEBP($symbol);
    $currency = $currency;

    //$client = new \GuzzleHttp\Client();
    //$getTransactions = json_decode($client->request('GET', 'https://api.bscscan.com/api?module=account&action=txlist&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey)->getBody()->getContents());

    $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=txlist&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
    $getTransactions = json_decode($getData);

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

    //return view('Cron.reload', compact('routeName', 'page', 'timeout'));
    //dd('check deposit usdt complete');
  }

  public function checkDepositTokenBEP20($userID, $userEmail, $address, $contract, $symbol, $currency)
  {
    $contractAddress = $contract;
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';
    $symbol = $symbol;
    $feeDeposit = 0;
    if ($currency == 4 || $currency == 12 || $currency == 13 || $currency == 14 || $currency == 15) {
      $feeDeposit = 0.1;
    } elseif ($currency == 7) {
      $feeDeposit = 0;
    }
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
    //$rate = $this->coinRateBuyEBP($symbol);
    $currency = $currency;

    //$client = new \GuzzleHttp\Client();
    //$getTransactions = json_decode($client->request('GET', 'https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey)->getBody()->getContents());

    $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
    $getTransactions = json_decode($getData);

    foreach ($getTransactions->result as $v) {
      if (strtoupper($v->to) != strtoupper($address)) {
        continue;
      }
      if($v->confirmations <= 3){
        continue;
      }
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

    //return view('Cron.reload', compact('routeName', 'page', 'timeout'));
    //dd('check deposit usdt complete');
  }

  public function getDepositNewV2($user)
  {
    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
      ->where('Address_User', $user->User_ID)
      //->where('User_Level', 1)
      ->where('Address_IsUse', 1)->get();
    foreach ($getAddress as $address) {
      if ($address->Address_Currency == 3) {
        $this->checkDepositEUSD($address->Address_User, $user->User_Email, $address->Address_Address);
      }
      if ($address->Address_Currency == 5) {
        $this->checkDepositUSDTR20($address->Address_User, $user->User_Email, $address->Address_Address);
      }
      if ($address->Address_Currency == 6) {
        $this->checkDepositUSDTR20($address->Address_User, $user->User_Email, $address->Address_Address);
      }
      //      if ($address->Address_Currency == 4) {
      //        $contract = '0xc31c29d89e1c351d0a41b938dc8aa0b9f07b4a29';
      //        $symbol = 'DP-NFT';
      //        $this->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
      //      }
      if ($address->Address_Currency == 7) {
        $contract = '0x8c2da84ea88151109478846cc7c6c06c481dbe97';
        $symbol = 'HBG';
        $this->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
      }
      if ($address->Address_Currency == 8) {
        $this->checkDepositEBP($address->Address_User, $user->User_Email, $address->Address_Address);
      }
      if ($address->Address_Currency == 11) {
        $this->getDepositUSDTBEP($address->Address_User, $user->User_Email, $address->Address_Address);
      }
      if ($address->Address_Currency == 12) {
        $contract = '0xfea6ab80cd850c3e63374bc737479aeec0e8b9a1';
        $symbol = 'SOL';
        $this->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
      }
      if ($address->Address_Currency == 13) {
        $contract = '0xaec945e04baf28b135fa7c640f624f8d90f1c3a6';
        $symbol = 'C98';
        $this->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
      }
      if ($address->Address_Currency == 14) {
        $contract = '0x3ee2200efb3400fabb9aacf31297cbdd1d435d47';
        $symbol = 'ADA';
        $this->checkDepositTokenBEP20($address->Address_User, $user->User_Email, $address->Address_Address, $contract, $symbol, $address->Address_Currency);
      }
      if ($address->Address_Currency == 15) {
        $this->checkDepositTRX($address->Address_User, $user->User_Email, $address->Address_Address);
      }
      if ($address->Address_Currency == 16) {
        $symbol = 'BNB';
        $this->checkDepositBNB($address->Address_User, $user->User_Email, $address->Address_Address, $symbol, $address->Address_Currency);
      }
    }
    //dd($getAddress);
  }

  public function getDepositUSDTBEP($userID, $userEmail, $address)
  {
    $contractUSDT = '0x55d398326f99059ff775485246999027b3197955';
    $apiKey = '6EWUSUAHDMTTGF96VRFI25NEU58R4ZV49E';
    $symbol = 'USDT';
    $currency = 11;

    //$client = new \GuzzleHttp\Client();
    //$getData = json_decode($client->request('GET', 'https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractUSDT . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey)->getBody()->getContents());
    //$transactions = $getData->result;

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

  public function checkDepositUSDTR20($userID, $userEmail, $address)
  {
    $contractUSDT = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t';
    $symbol = 'USDT';
    $currency = 6;
    $getData = file_get_contents('https://apilist.tronscan.org/api/contract/events?address=' . $address . '&start=0&limit=100&contract=' . $contractUSDT);
    $data = json_decode($getData, true);
    $transactions = $data['data'];
    //dd($transactions);
    foreach ($transactions as $v) {
      if ($v['transferToAddress'] != $address) {
        continue;
      }
      $value = $v['amount'] / 1000000;
      $hashtag = $v['transactionHash'];
      $hash = Money::where('Money_Address', $hashtag)->first();

      if (!$hash) {
        //$user = $address;

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

  public function checkDepositEUSD($userID, $userEmail, $address)
  {
    $contractUSDT = 'TNUN6pFXEH3p3jhDZaTro6gsLCZ5fT3rqs';
    $symbol = 'EUSD';
    $currency = 3;
    //dd($getAddress);

    $getData = file_get_contents('https://apilist.tronscan.org/api/contract/events?address=' . $address . '&start=0&limit=100&contract=' . $contractUSDT);
    $data = json_decode($getData, true);
    $transactions = $data['data'];
    //dd($transactions);
    foreach ($transactions as $v) {
      if ($v['transferToAddress'] != $address) {
        continue;
      }
      $value = $v['amount'] / 1000000000000000000;
      $hashtag = $v['transactionHash'];
      $hash = Money::where('Money_Address', $hashtag)->first();

      //if($address->User_ID == 439094){
      //  	dd($hash, $v, $address, $address->Address_Address);
      //}
      if (!$hash) {
        //$user = $address;

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

  public function checkDepositEBP($userID, $userEmail, $address)
  {
    $contractAddress = '0x3e007B3cC775C4bD1600693aAD7FaC0685353272';
    $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';
    $symbol = 'EBP';
    $feeDeposit = 0.1;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
    //$rate = $this->coinRateBuyEBP($symbol);
    $currency = 8;

    //$client = new \GuzzleHttp\Client();
    //$getTransactions = json_decode($client->request('GET', 'https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey)->getBody()->getContents());

    $getData = file_get_contents('https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
    $getTransactions = json_decode($getData);

    //dd($getTransactions->result);
    foreach ($getTransactions->result as $v) {
      if (strtoupper($v->to) != strtoupper($address)) {
        continue;
      }
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
        //cộng tiền
        $money = new Money();
        $money->Money_User = $userID;
        $money->Money_USDT = $value;
        $money->Money_USDTFee = $amountFee;
        $money->Money_Time = time();
        $money->Money_Comment = 'Deposit ' . ($value + 0) . ' ' . $symbol;
        $money->Money_Currency = $currency;
        $money->Money_CurrencyFrom = $currency;
        $money->Money_MoneyAction = 1;
        $money->Money_Address = $v->hash;
        $money->Money_CurrentAmount = $value;
        $money->Money_Rate = $rate;
        $money->Money_MoneyStatus = 1;
        //dd($money);
        $money->save();

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
        $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email, 'deposit_amount' => $value, 'fee_deposit' => 0, 'number_of_tokens' => $value, 'currency' => $symbol, 'EUSD' => $value, 'wallet' => $user->User_WalletAddress, 'hash_code' => $v->hash, 'network' => 'BSC');
        dispatch(new SendMailJobs('Deposit', $data, 'Deposit 123Betnow!', $user->User_ID));
      }
    }

    //return view('Cron.reload', compact('routeName', 'page', 'timeout'));
    //dd('check deposit usdt complete');
  }
}
