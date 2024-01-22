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

class TestController extends Controller
{
  public function getDepositUSDTBEP(Request $req)
  {
    $contractUSDT = '0x55d398326f99059ff775485246999027b3197955';
    $apiKey = '6EWUSUAHDMTTGF96VRFI25NEU58R4ZV49E';
    $symbol = 'USDT';
    $currency = 11;
    $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')->where('Address_Currency', $currency)->where('Address_IsUse', 1)/*->where('Address_User', 851274)*/->paginate(20);
    foreach($getAddress as $address){
      $client = new \GuzzleHttp\Client();
      $getData = json_decode($client->request('GET', 'api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractUSDT . '&address='.$address->Address_Address.'&offset=5000&page=1&sort=desc&apikey=' . $apiKey)->getBody()->getContents());
      $transactions = $getData->result;
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
}
