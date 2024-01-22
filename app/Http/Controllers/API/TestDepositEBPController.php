<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money as CB_Money;
use Coinbase\Wallet\Enum\Param;
use DB;

use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

use Validator;
use App\Model\Profile;
use App\Model\GoogleAuth;
use App\Model\LogUser;
use App\Model\User;
use App\Model\userBalance;
use App\Jobs\SendTelegramJobs;
use App\Model\Money;
use PayusAPI\Http\Client as PayusClient;
use PayusAPI\Resources\Payus;

use GuzzleHttp\Client as G_Client;

use App\Model\Wallet;
class TestDepositEBPController extends Controller{
	public $feeWithdraw = 0.02;
	public $feeSwap = 0.03;

	public static function createAddressEBPNew($coin, $user){
	    switch ($coin) {
			case 8:
				$client = new \GuzzleHttp\Client();
				$res = $client->request('GET', 'https://coinbase.rezxcvbnm.co/public/address?key=JOf9HkPAPEJelIrOmMdIPwW2IzoIvimQ1Qy2jp01bksxr3dE1x&trc20=0');

				$json = json_decode($res->getBody());
				$addressArray = array(
					'name'=>'EBP (BEP-20)',
					'address'=>$json->address,
					'Qr'=>'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$json->address.'&choe=UTF-8'
				);

				break;

		}
		if($addressArray){
			return $addressArray;
		}
		return false;
    }

  	public function postWithdrawEBP(Request $req){
      	$user = User::where('User_ID', Auth::user()->User_ID)->first();
		    $check_custom = $user->User_Level;
        if($check_custom == 1){
          //dd(1);
        }

        $validator = Validator::make($req->all(), [
          'address' => 'required|string|min:1|nullable',
          'amount' => 'required|numeric|min:10|nullable',
          //'coin' => 'required|numeric|in:3,5,6,8',
        ],[
          'address.required' => trans('notification.address_required') ,
          'amount.required' => trans('notification.amount_required') ,
          'amount.min' => trans('notification.amount_min_10') ,
        ]);

        if ($validator->fails()) {
          foreach ($validator->errors()->all() as $value) {
            return $this->response(200, [], $value, $validator->errors(), false);
          }
        }

        //$user = User::where('User_ID', $user->User_ID)->first();
        if($check_custom == 4 || $check_custom == 5){
          return $this->response(200, [], trans('notification.Your_account_cant_use_this_function!'), [], false);
        }
        //if($user->User_Lock_Withdraw) return $this->response(200, [], 'Can\'t use this function!', [], false);
        //Bảo mật
        $checkProfile = Profile::where('Profile_User', $user->User_ID)->first();
        if(!$checkProfile || $checkProfile->Profile_Status != 1){
          //return $this->response(200, [], 'Your Profile KYC Is Unverify', [], false);
        }

        $google2fa = app('pragmarx.google2fa');
        $AuthUser = GoogleAuth::select('google2fa_Secret')->where('google2fa_User', $user->User_ID)->first();

      	if(!$AuthUser){
          return $this->response(200, [], trans('notification.User_is_not_authenticated!'), [], false);
        }
        $valid = $google2fa->verifyKey($AuthUser->google2fa_Secret, $req->auth);
        if(!$valid){
          return $this->response(200, [], trans('notification.Wrong_code'), [], false);
        }


        //sỐ TIỀN MUỐN RÚT
        $amount = $req->amount;
        $coin = $req->coin ?? $req->coin_to;
      	$currency = $coin;
        $coinArr = DB::table('currency')->whereIn('Currency_ID', [3,4,6,7,8,11])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
        if(!isset($coinArr[$coin])){
          return $this->response(200, [], 'Coin invalid!', [], false);
        }
        $symbol = $coinArr[$coin];
        if($coin != 5){
          $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
          //$rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
        }else{
          $rate = 1;
        }
        //Rút từ ví nào
        //$symbol_to = $req->coin;
        //Balance
      	//dd($rate,$coin,$symbol);
      //dd($user->User_ID, $coin);
      	$coinBalance = 3;
      	if($coin == 8){
         	$coinBalance = 8;
        }
        $balance = User::getBalance($user->User_ID,$coinBalance);
        $amountFee = $amount * ($this->feeWithdraw/100);
        if($coin == 5){
          $amountFee += Money::feeGas();
        }else{
          $amountFee += 0.5;
          //$amountFee = round($amountFee, 6);
        }
      	
      	if($coin == 8){
          if($amount*$rate < 20){
            return $this->response(200, [], trans('notification.Min_withdraw_EBP_is_20_EUSD'), [], false);
          }
        }
        if(($amount) > $balance){
          return $this->response(200, ['balance'=>$balance], trans('notification.Your_balance_is_not_enough'), [], false);
        }
      //dd($balance,$rate,$amountFee);
        //kiểm tra có lệnh rút nào chưa
        $withdraw = Money::where('Money_MoneyAction', 2)->where('Money_MoneyStatus', 0)->where('Money_User', $user->User_ID)->first();
        if($withdraw){
          return $this->response(200, ['balance'=>$balance], trans('notification.Please_wait_for_the_withdrawal_to_be_approved'), [], false);
        }
        $address = $req->address;
        $confirm = 0;
        $comment = 'Withdraw ' . ($amount*1) . ' ' . $symbol.' Address '.$address;
        $commentTelegram = 'WITHDRAW';
        if($req->ecosystem){
          $ecosystem = app('App\Http\Controllers\API\ReportController')->ecosystem;
          $feeWithdrawEcosystem = config('coin.'.$symbol.'.WithdrawFeeEcoSystem');
          //dd($ecosystem, $req->ecosystem);
          if(!isset($ecosystem[$req->ecosystem])){
            return $this->response(200, ['balance'=>$balance], trans('notification.Ecosystem_is_wrong'), [], false);
          }
          $amountFee = $amount * ($feeWithdrawEcosystem[$req->ecosystem]/100);
          if($req->ecosystem != 'Out'){
            if($req->ecosystem == 'BO'){
              $userID = $user->User_ID;
              $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
			  $client = new \GuzzleHttp\Client();
              $response = $client->get('abcxyz.eggsbook.com/api/v1/check-deposit-platform',[
                'query' => [
                  'address' => $address,
                  'amount' => ($amount - $amountFee),
                  'coin' => $coin,
                  'user' => $userID,
                  'key' => $key,
                ]
              ]);
              $dataResponse = json_decode($response->getBody());
              LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
              if($dataResponse->status == true){
                $confirm = 1;
                $comment .= ' (To Exchange)';
                $commentTelegram .= ' (To Exchange)';
              }else{
                return $this->response(200, [], trans('notification.Address_is_not_found_in_Ecosystem'), [], false);
              }
            }elseif($req->ecosystem == 'System'){
              $userID = $user->User_ID;
              $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
              $client = new \GuzzleHttp\Client();
              $response = $client->get('api.eggsbook.com/api/v1/check-deposit-platform',[
                'query' => [
                  'address' => $address,
                  'amount' => ($amount - $amountFee),
                  'coin' => $coin,
                  'user' => $userID,
                  'key' => $key,
                ]
              ]);
              $dataResponse = json_decode($response->getBody());
              LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
              if($dataResponse->status == true){
                $confirm = 1;
                $comment .= ' (To Eggsbook)';
                $commentTelegram .= ' (To Eggsbook)';
              }else{
                return $this->response(200, [], trans('notification.Address_is_not_found_in_Ecosystem'), [], false);
              }
            }else{
              return $this->response(200, [], trans('notification.Ecosystem_is_wrong'), [], false);
            }
          }
        }else{
          return $this->response(200, [], trans('notification.Ecosystem_is_wrong'), [], false);
          //if($user->User_Level == 1){
          $userID = $user->User_ID;
          $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
		  $client = new \GuzzleHttp\Client();
          $response = $client->get('abcxyz.eggsbook.com/api/v1/check-deposit-platform',[
            'query' => [
              'address' => $address,
              'amount' => ($amount - $amountFee),
              'coin' => $coin,
              'user' => $userID,
              'key' => $key,
            ]
          ]);
          $dataResponse = json_decode($response->getBody());
          if($dataResponse->status == true){
            $confirm = 1;
            $comment .= ' (To Exchange)';
            $commentTelegram .= ' (To Exchange)';
          }
          LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
          //}
          if($confirm == 0){
            $userID = $user->User_ID;
            $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
  		    $client = new \GuzzleHttp\Client();
            $response = $client->get('api.eggsbook.com/api/v1/check-deposit-platform',[
              'query' => [
                'address' => $address,
                'amount' => ($amount - $amountFee),
                'coin' => $coin,
                'user' => $userID,
                'key' => $key,
              ]
            ]);
            $dataResponse = json_decode($response->getBody());
            if($dataResponse->status == true){
              $confirm = 1;
              $comment .= ' (To Eggsbook)';
              $commentTelegram .= ' (To Eggsbook)';
            }
            LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
          }
        }
        // lưu lịch sử
        $arrayInsert = array(
          'Money_User' => $user->User_ID,
          'Money_USDT' => -$amount+$amountFee,
          'Money_USDTFee' => -$amountFee,
          'Money_Time' => time(),
          'Money_Comment' => $comment,
          'Money_MoneyAction' => 2,
          'Money_MoneyStatus' => 1,
          'Money_Address' => $address,
          'Money_Currency' => $coinBalance,
          'Money_CurrentAmount' => ($amount - $amountFee),
          'Money_CurrencyFrom' => 0,
          'Money_CurrencyTo' => $coin,
          'Money_Rate' => $rate,
          'Money_Confirm' => $confirm,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 1,
          //'Money_TXID' => md5(time()),
        );
      	//dd($arrayInsert);
       	$id = Money::insertGetId($arrayInsert);
        // gọi jobs
        //dispatch(new WalletJobs($id, $user->User_ID))->delay(1);
        $message = "$user->User_Email $commentTelegram ".$amount." $symbol\n"
          . "<b>User ID: </b> "
          . "$user->User_ID\n"
          . "<b>Email: </b> "
          . "$user->User_Email\n"
          . "<b>Amount USD: </b> "
          . (($amount - $amountFee)*$rate)." USD\n"
          . "<b>Amount Coin: </b> "
          . (($amount - $amountFee)).' '.$symbol."\n"
          . "<b>Address: </b> "
          . $address."\n"
          . "<b>Rate: </b> "
          . $rate."\n"
          . "<b>Submit withdraw Time: </b>\n"
          . date('d-m-Y H:i:s',time());

        dispatch(new SendTelegramJobs($message, -448649753));

        $withdraw = config('utils.action.withdraw');
        LogUser::addLogUser($user->User_ID, $withdraw['action_type'], $withdraw['message'].' '.(float)$amount.' to wallet: '.$address, $req->ip());

		return $this->response(200, ['balance'=>array('main'=>(float)User::getBalance($user->User_ID, 3)), 'wallet'=>$address], trans('notification.you_withdraw',['amount'=>$amount*1,'symbol'=>$symbol ,'address'=>$address]), [], true);

    }

  	public static function coinRateBuyEBP($system = null){
	    if($system == 'ETH' || $system == 'BTC'){
// 		    $coin[$system] = self::coinbase()->getBuyPrice($system.'-USD')->getAmount();
		    $coin[$system] = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol='.$system.'USDT'))->price;
	    }elseif($system == 'EBP'){
          	try {
                $price = json_decode(file_get_contents('https://admin-api.eggsbook.com/api/v1/price'))->price;
                $coin['EBP'] = $price;
                //update price in db
                $getLastedPrice = DB::table('rate')->where('rate_Symbol', $system)->orderByDesc('rate_ID')->first();
                $timeChange = 300;
                if (!$getLastedPrice || (time() - $getLastedPrice->rate_Time >= $timeChange)) {
                    $data = [
                        'rate_Amount' => $price,
                        'rate_Time' => time(),
                        'rate_Symbol' => $system,
                        'rate_Log' => 'From Admin',
                        'rate_Duration' => $timeChange,
                    ];
                    DB::table('rate')->insert($data);
                }
            }catch (\Exception $e){
//                dd($e->getMessage());
                $price = DB::table('rate')->where('rate_Symbol', $system)->orderByDesc('rate_ID')->first();
                $coin['EBP'] = $price->rate_Amount;
            }
		}else{
          	$listCoins = DB::table('currency')->whereIn('Currency_ID', [1, 2, 8])->get();
            $coin = [];
            foreach ($listCoins as $listcoin) {
                $coin[$listcoin->Currency_Symbol] = self::coinRateBuyEBP($listcoin->Currency_Symbol);
            }

		    // $coin['BTC'] = self::coinbase()->getBuyPrice('BTC-USD')->getAmount();
			// $coin['ETH'] = self::coinbase()->getBuyPrice('ETH-USD')->getAmount();
			//$coin['BTC'] = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=BTCUSDT'))->price;
		    //$coin['ETH'] = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=ETHUSDT'))->price;
			//$price = json_decode(file_get_contents('https://admin-api.eggsbook.com/api/v1/price'))->price;
			//$coin['EBP'] = $price->Changes_Price;

	    }

		$coin['USDT'] = 1;
		$coin['EUSD'] = 1;
		$coin['USD'] = 1;

	    if($system){
		    return $coin[$system];
		}

	    return $coin;
    }

  	public function postSwapEBP(Request $req){
        $user = User::where('User_ID', Auth::user()->User_ID)->first();
      	$keyProject = 95317;
      	$key = '4d0237f816';
      	$urlAPI = 'https://api-auto-transfer.chidetest.com/api/v1/swap';
      	$addressUSD = '0x55d398326f99059ff775485246999027b3197955';
      	$addressToken = '0x3e007b3cc775c4bd1600693aad7fac0685353272';

        $check_custom = $user->User_Level;
        if($check_custom != 1){
          return $this->response(200, [], 'Can\'t use this function!', [], false);
        }
//dd($req->all(),$req->coin_to,123);
        $validator = Validator::make($req->all(), [
          'coin_from' => 'required|in:3,8',
          'amount' => 'required|numeric|min:0',
          'coin_to' => 'required|in:3,8',
        ],[
          'coin_from.required' => trans('notification.coin_required') ,
          'coin_to.required' => trans('notification.coin_required') ,
          'amount.required' => trans('notification.amount_required') ,
        ]);

        if ($validator->fails()) {
          foreach ($validator->errors()->all() as $value) {
            return $this->response(200, [], $value, $validator->errors(), false);
          }
        }
        if($user->User_Lock_Swap) return $this->response(200, [], trans('notification.Cant_use_this_function!'), [], false);
        $arr_from_wallet = [
          3 => 'EUSD',
          8 => 'EBP',
        ];

        $coin_from = $req->coin_from;
        $coin_to = $req->coin_to;
      	//dd($coin_from,$coin_to);
        if(($coin_from == $coin_to)){
          return $this->response(200, [], trans('notification.Currency_Error!'), [], false);
        }
        $symbolFrom = $arr_from_wallet[$coin_from];
        $symbolTo = $arr_from_wallet[$coin_to];
        $amount_from = $req->amount;
        //$rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy();
        $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy();
        //$rate = $this->coinRateBuyEBP();
        //Balance
        $balance = User::getBalance($user->User_ID, $coin_from);
      	//dd($balance);
        $fee = $this->feeSwap;
        if($amount_from > $balance){
          return $this->response(200, [], trans('notification.Your_balance_is_not_enough!'), [], false);
        }

        //$amount_to = $amount_from*$rate[$symbolFrom]/$rate[$symbolTo];


      	if($amount_from*$rate[$symbolTo] < 20){
          //return $this->response(200, [], 'Min swap EBP is 20 EUSD', [], false);
        }
      	if($amount_from*$rate[$symbolTo] > 1000){
          return $this->response(200, [], trans('notification.Max_swap_EBP_is_1000_EUSD'), [], false);
        }
      	//dd($rate[$symbolFrom],$this->coinRateBuyEBP($symbolFrom));
      	$token_address_from = $addressUSD;
      	$token_address_to = $addressToken;
      	if($coin_from == 8){
          $token_address_from = $addressToken;
          $token_address_to = $addressUSD;
        }

      	include(app_path() . '/functions/xxtea.php');
      	$jquery = [
          'token_address_from' => $token_address_from,
          'token_address_to' => $token_address_to,
          'amount' => $amount_from,
          'key' => $keyProject
        ];
      	//dd($jquery,$coin_from);
        $dataEncrypt = base64_encode(xxtea_encrypt(json_encode($jquery), $key));
        //dd($dataEncrypt);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $urlAPI, [
          'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
          'body' => json_encode(['data'=>$dataEncrypt]),
        ]);

        //dd($response);
        $content = $response->getBody()->getContents();
		$data = json_decode($content);

        //$array = explode(",",$content);
        $responseData = json_decode(xxtea_decrypt(base64_decode($data->data), $key), true);
      	//dd($responseData['status']);
      	if($responseData && $responseData['status'] == true){
          $amount_to = $responseData['amount'];
          $amountFee = $amount_to*$fee;

          $insertArray = array(
              array(
                'Money_User' => $user->User_ID,
                'Money_USDT' => -$amount_from,
                'Money_USDTFee' => 0,
                'Money_Time' => time(),
                'Money_Comment' => 'Swap Coin From '.$symbolFrom.' To '.$symbolTo,
                'Money_MoneyAction' => 82,
                'Money_MoneyStatus' => 1,
                'Money_Address' => null,
                'Money_Currency' => $coin_from,
                'Money_CurrentAmount' => $amount_from,
                'Money_CurrencyFrom' => $coin_from,
                'Money_CurrencyTo' => $coin_to,
                'Money_Rate' => $rate[$symbolFrom],
                'Money_Confirm' => 0,
                'Money_Confirm_Time' => null,
                'Money_FromAPI' => 1
              ),
              array(
                'Money_User' => $user->User_ID,
                'Money_USDT' => $amount_to,
                'Money_USDTFee' => -($amountFee),
                'Money_Time' => time(),
                'Money_Comment' => 'Swap Coin From '.$symbolFrom,
                'Money_MoneyAction' => 82,
                'Money_MoneyStatus' => 1,
                'Money_Address' => null,
                'Money_Currency' => $coin_to,
                'Money_CurrentAmount' => $amount_to-$amountFee,
                'Money_CurrencyFrom' => $coin_from,
                'Money_CurrencyTo' => $coin_to,
                'Money_Rate' => $rate[$symbolTo],
                'Money_Confirm' => 0,
                'Money_Confirm_Time' => null,
                'Money_FromAPI' => 1
              ),
            );
            //dd($insertArray);
            $insert = Money::insert($insertArray);
        	return $this->response(200, [], "Swap From $symbolFrom To $symbolTo Success!", [], true);
        }
        return $this->response(200, [], "Swap From $symbolFrom To $symbolTo Error!", [], false);

        //update Balance
        //$balance = [];
        //$balance[$symbolFrom] = User::getBalance($user->User_ID, $coin_from);
        //$balance[$symbolTo] = User::getBalance($user->User_ID, $coin_to);
			//return $this->response(200, [], "Withdrawal successful!", [], true);
    }

  	public function getDepositEBP(Request $req)
    {
        $contractAddress = '0x3e007B3cC775C4bD1600693aAD7FaC0685353272';
        $apiKey = 'AGYJQ2A1CY8Y9ZE76SN552X9QPK6M3228B';
        $symbol = 'EBP';
      	$feeDeposit = 0;
        $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
        //$rate = $this->coinRateBuyEBP($symbol);
        $currency = 8;
        $getAddress = DB::table('address')->join('users', 'User_ID', 'Address_User')
            ->where('Address_Currency', $currency)
            ->where('Address_IsUse', 1);
        if ($req->address_user) {
            $getAddress = $getAddress->where('Address_Address', $req->address);
        }
        if ($req->user) {
            $getAddress = $getAddress->where('Address_User', $req->user);
        }
        $getAddress = $getAddress->paginate(30);
    //dd($getAddress,$rate);
        foreach ($getAddress as $address) {
            $client = new \GuzzleHttp\Client(); //GuzzleHttp\Client
            $getTransactions = json_decode($client->request('GET', 'api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address->Address_Address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey)->getBody()->getContents());
//                  dd(123,$getTransactions, $address, 'api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $address->Address_Address . '&offset=5000&page=1&sort=desc&apikey=' . $apiKey);
          	//dd($getTransactions->result);
            foreach ($getTransactions->result as $v) {
                //                dd($v->to, $address->Address_Address, strtoupper($v->to) != strtoupper($address->Address_Address));
                if (strtoupper($v->to) != strtoupper($address->Address_Address)) {
                    continue;
                }
              	//dd($getTransactions->result);
                $hash = DB::table('money')->where('Money_Address', $v->hash)->first();
                if (!$hash) {
                    $user = $address;
                    if (!$user) {
                        continue;
                    }

                    $value = filter_var($v->value / pow(10,$v->tokenDecimal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                  	//dd($value);
                    $amountFee = $value * $feeDeposit;
                    $amountUSDT = $value * $rate;
                    $amountUSDTFee = $amountFee * $rate;
                  	//dd($amountFee,$amountUSDT,$amountUSDTFee,$value);
                    //cộng tiền
                    $money = new Money();
                    $money->Money_User = $user->Address_User;
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
                    $money->save();

                    // 	Gửi telegram thông báo User verify
                    $message = "$user->User_Email Deposit $value $symbol\n"
                        . "<b>User ID: </b> "
                        . "$user->User_ID\n"
                        . "<b>Email: </b> "
                        . "$user->User_Email\n"
                        . "<b>Amount: </b> "
                        . $value." $symbol\n"
                        . "<b>Amount USD: </b> "
                        . ($value*$rate)." USDT\n"
                        . "<b>Rate: </b> "
                        . $rate."\n"
                        . "<b>Submit Deposit Time: </b>\n"
                        . date('d-m-Y H:i:s',time());


                  	dispatch(new SendTelegramJobs($message, -485635858));
                }
            }
        }
        $routeName = $req->route()->getName();
        $page = $getAddress->currentPage();
        $lastPage = $getAddress->lastPage();
        $timeout = 5;
        if ($page >= $lastPage) {
            $page = 0;
            $timeout = 30;
        }
        return view('Cron.reload', compact('routeName', 'page', 'timeout'));
        dd('check deposit usdt complete');
    }
}
