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

use PayusAPI\Http\Client as PayusClient;
use PayusAPI\Resources\Payus;

use GuzzleHttp\Client as G_Client;

use App\Model\Wallet;
use App\Model\Binance;

class CoinbaseController extends Controller
{
  public $access_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBfaWQiOiI1ZGM1MzNhZWQ0NWMwNDJmZTdhY2FlYWQiLCJhcGlfa2V5IjoiWlczTjlLRjVRR00zTks0TkZNTktKQTlMVjZGTFNLNkk3RiIsInVzZXJfaWQiOiI1ZGM1MzI0ZWQ0NWMwNDJmZTdhY2FlODYiLCJpYXQiOjE1NzMyMDQ5MTN9.RdPKuEYcurqtQpNBE38lxTdDqXgbjOZqBNYexRBRVQI';

  public static function coinbase()
  {
    $apiKey = 'nbZclTlYvz5mkhNN';
    $apiSecret = 'AUHqsUV0lyLHd9H7RMWtIVhwDpYOOWhG';

    $configuration = Configuration::apiKey($apiKey, $apiSecret);
    $client = Client::create($configuration);

    return $client;
  }

  public function Payus()
  {
    $access_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBfaWQiOiI1ZGM1MzNhZWQ0NWMwNDJmZTdhY2FlYWQiLCJhcGlfa2V5IjoiWlczTjlLRjVRR00zTks0TkZNTktKQTlMVjZGTFNLNkk3RiIsInVzZXJfaWQiOiI1ZGM1MzI0ZWQ0NWMwNDJmZTdhY2FlODYiLCJpYXQiOjE1NzMyMDQ5MTN9.RdPKuEYcurqtQpNBE38lxTdDqXgbjOZqBNYexRBRVQI';

    $client = new PayusClient(['access_token' => $access_token]);
    $payus = new Payus($client);

    return $payus;
  }


  public function rateVND(){
    $binance = new Binance();
    $asset = "USDT";
    $fiat = "VND";
    $tradeType = ["BUY","SELL"];

    $data_json = json_encode($binance::exchange($asset, $fiat, $tradeType)); dd($data_json);
    $price = $data_json->price;
    $min_price = $data_json->minSingle;
    $max_price = $data_json->dynamicMaxSingle;
    dd($price);
  }

  public static function coinRateBuy($system = null)
  {



    if ($system == 'ETH' || $system == 'BTC' || $system == 'SOL' || $system == 'C98' || $system == 'ADA' || $system == 'TRX' || $system == 'BNB') {
      // 		    $coin[$system] = self::coinbase()->getBuyPrice($system.'-USD')->getAmount();
      $coin[$system] = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=' . $system . 'USDT'))->price;
    } elseif ($system == 'DP-NFT') {
      //      try {
      //        $price = json_decode(file_get_contents('https://api.dragonpool.app/api/v1/market/price'))->data;
      //        $coin[$system] = $price;
      //        //update price in db
      //        $getLastedPrice = DB::table('rate')->where('rate_Symbol', $system)->orderByDesc('rate_ID')->first();
      //        $timeChange = 60;
      //        if (!$getLastedPrice || (time() - $getLastedPrice->rate_Time >= $timeChange)) {
      //          $data = [
      //            'rate_Amount' => $price,
      //            'rate_Time' => time(),
      //            'rate_Symbol' => $system,
      //            'rate_Log' => 'From Admin',
      //            'rate_Duration' => $timeChange,
      //          ];
      //          DB::table('rate')->insert($data);
      //        }
      //      } catch (\Exception $e) {
      //        dd($e->getMessage());
      $price = DB::table('rate')->where('rate_Symbol', $system)->orderByDesc('rate_ID')->first();
      $coin[$system] = $price->rate_Amount;
      //      }

      //$price = DB::table('changes')->orderBy('Changes_ID', 'DESC')->first();
      //$coin['EBP'] = $price->Changes_Price;
    } elseif ($system == 'HBG') {
      try {

        $stream_opts = [
          "ssl" => [
            "verify_peer"=>false,
            "verify_peer_name"=>false,
          ]
        ];  
        //$price = json_decode(file_get_contents('https://api-mainnet-gameplay.dragonpool.app/api/price/token?token=HBG'))->data->price;
        $price = json_decode(file_get_contents('https://api-market.herobook.io/api/system/getPrice/0x8c2da84ea88151109478846cc7c6c06c481dbe97',
                                               false, stream_context_create($stream_opts)))->data;
        $coin[$system] = $price;
        //update price in db
        /*$data = [
          'rate_Amount' => $price,
          'rate_Time' => time(),
          'rate_Symbol' => $system,
          'rate_Log' => 'From Admin',
          'rate_Duration' => $timeChange,
        ];
        DB::table('rate')->insert($data);

        $getLastedPrice = DB::table('rate')->where('rate_Symbol', $system)->orderByDesc('rate_ID')->first();
        $timeChange = 60;
        if (!$getLastedPrice || (time() - $getLastedPrice->rate_Time >= $timeChange)) {

        }*/

      } catch (\Exception $e) {
        $price = json_decode(file_get_contents('https://api-market.herobook.io/api/system/getPrice/0x8c2da84ea88151109478846cc7c6c06c481dbe97'))->data;
        $coin[$system] = $price;
        /*$data = [
          'rate_Amount' => $price,
          'rate_Time' => time(),
          'rate_Symbol' => $system,
          'rate_Log' => 'From Admin',
          'rate_Duration' => $timeChange,
        ];
        DB::table('rate')->insert($data);

        $getLastedPrice = DB::table('rate')->where('rate_Symbol', $system)->orderByDesc('rate_ID')->first();
        $timeChange = 60;
        if (!$getLastedPrice || (time() - $getLastedPrice->rate_Time >= $timeChange)) {
        }

        $price = DB::table('rate')->where('rate_Symbol', $system)->orderByDesc('rate_ID')->first();
        $coin[$system] = $price->rate_Amount;*/
      }

      //$price = DB::table('changes')->orderBy('Changes_ID', 'DESC')->first();
      //$coin['EBP'] = $price->Changes_Price;
    } elseif ($system == 'EBP') {
      try {
        //$price = json_decode(file_get_contents('https://admin-api.eggsbook.com/api/v1/price'))->price;
        $price = 0;
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
      } catch (\Exception $e) {
        //                dd($e->getMessage());¬
        $price = DB::table('rate')->where('rate_Symbol', $system)->orderByDesc('rate_ID')->first();
        $coin['EBP'] = $price->rate_Amount;
      }
      //        $coin['EBP'] = 0;

      //$price = DB::table('changes')->orderBy('Changes_ID', 'DESC')->first();
      //$coin['EBP'] = $price->Changes_Price;
    } elseif ($system == 'VNĐ'){

      $arr_rate_vnd = [];
      $arrAsset = ["BUY","SELL"];
      foreach($arrAsset as $item){
        $url = "https://p2p.binance.com/bapi/c2c/v2/friendly/c2c/adv/search";
        // Parameters for the API request
        $params = [
          'asset' => 'USDT',
          'tradeType' => $item,
          'publisherType' => null,
          'page' => 1,
          'rows' => 1,
          'payTypes' => [],
          'fiat' => 'VND',
        ];

        try{
          $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
          $body=json_encode($params);
          $response = $client->request('POST',$url,['body'=>$body]);
          $response =json_decode($response->getBody(), true); //dd($response);

          if($response['code'] * 1 == 0 && $response['success'] * 1 == true){
            $arr_rate_vnd[$item] = $rateBuy = $response['data'][0]['adv']['price']*1;
          }
        } catch (\Exception $e) {
          $arr_rate_vnd[$item] = 0;
          $message = "Get rate $item p2p vnđ error".$e->getMessage()."\n"
            . "<b>Project: </b>"
            . "beta-v2.123betnow.net\n"
            . "<b>Time: </b>"
            . date('d-m-Y H:i:s',time());
          dispatch(new SendTelegramJobs($message, -398297366));
        }
      }
      $coin[$system] = $arr_rate_vnd;

    } else {
      // $coin['BTC'] = self::coinbase()->getBuyPrice('BTC-USD')->getAmount();
      // $coin['ETH'] = self::coinbase()->getBuyPrice('ETH-USD')->getAmount();
      $listCoins = DB::table('currency')->whereIn('Currency_ID', [1, 2, 4, 7, 8, 12, 13, 14, 15, 16,21])->get();
      $coin = [];
      foreach ($listCoins as $listcoin) {
        $coin[$listcoin->Currency_Symbol] = self::coinRateBuy($listcoin->Currency_Symbol);
      }
      //cu
      //$coin['BTC'] = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=BTCUSDT'))->price;
      //$coin['ETH'] = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=ETHUSDT'))->price;
      //$price = DB::table('changes')->orderBy('Changes_ID', 'DESC')->first();
      //$coin['EBP'] = $price->Changes_Price;

    }

    $coin['USDT'] = 1;
    $coin['EUSD'] = 1;
    $coin['USD'] = 1;
    //    $coin['HBG'] = 0.03;

    if ($system) {
      return $coin[$system];
    }

    return $coin;
  }


  public static function getAccountTransactions($symbol)
  {
    $account = self::coinbase()->getAccount($symbol);
    $transactions = self::coinbase()->getAccountTransactions($account, [
      Param::LIMIT => 20,
    ]);

    return $transactions;
  }

  public static function getAccountDeposit($symbol)
  {
    $account = self::coinbase()->getAccount($symbol);
    $transactions = self::coinbase()->getAccountDeposit($account);
    return $transactions;
  }

  public function getCoinbase(Request $req)
  {

    if (!$req->Coin) {
      $coin = 'BTC';
    } else {
      $coin = $req->Coin;
    }
    $account = $this->coinbase()->getAccount($coin);
    $balance = $account->getbalance()->getamount();


    $transactions = $this->coinbase()->getAccountTransactions($account, [
    ]);

    $excel = array();
    $i = 0;
    foreach ($transactions as $v) {
      if ($i == 0) {
        $plus = 0;
      } else {
        $plus = $transactions[$i - 1]->getamount()->getamount();
      }
      if ($v->getdescription() != null) {
        $getdescription = $v->getdescription();

      } else {
        $getdescription = 'User Deposit';
      }
      array_push($excel, array(
        $i + 1,
        $v->getcreatedAt()->format('Y-m-d H:i:s'),
        number_format($balance + $plus, 8),
        $v->getamount()->getamount(),
        $v->getnetwork()->gethash(),
        $getdescription
      ));
      $i++;
    }
    if (Input::get('export')) {
      if (Session('user')->User_Level != 1 && Session('user')->User_Level != 2) {
        dd('stop');
      }
      $history = $excel;

      $listHistory = array();

      //xuất excel
      $listHistoryExcel[] = array('ID', 'Time', 'Balance', 'Amount', 'Description', 'Transaction ID');
      $i = 1;

      foreach ($history as $d) {
        $listHistoryExcel[$i][0] = $d[0];
        $listHistoryExcel[$i][1] = $d[1];
        $listHistoryExcel[$i][2] = $d[2];
        $listHistoryExcel[$i][3] = $d[3];
        $listHistoryExcel[$i][4] = $d[5];
        $listHistoryExcel[$i][5] = $d[4];
        $i++;
      }
      Excel::create('Transaction-' . $coin . '' . date('YmdHis'), function ($excel) use ($listHistoryExcel, $coin) {
        $excel->setTitle('Transaction-' . $coin . '' . date('YmdHis'));
        $excel->setCreator('Transaction-' . $coin . '' . date('YmdHis'))->setCompany('SBANK');
        $excel->setDescription('Transaction-' . $coin . '' . date('YmdHis'));
        $excel->sheet('sheet1', function ($sheet) use ($listHistoryExcel) {
          $sheet->fromArray($listHistoryExcel, null, 'A1', false, false);
        });
      })->download('xls');
    }
    return view('System.Admin.Admin-Coinbase');
  }

  public function checkWallet($coin)
  {
    $user = Session::get('user') ?? Auth::user();
    // thông tin coin
    $coinInfo = DB::table('currency')->where('Currency_ID', $coin)->where('Currency_Active', 1)->first();
    $addressArray = [
      'name' => 'Error!',
      'address' => 'Server Maintain',
      'Qr' => 'Server Maintain'
    ];
    if ($coinInfo) {

      $address = DB::table('address')->where('Address_User', $user->User_ID)->where('Address_Currency', $coin)->where('Address_IsUse', 0)->first();
      if ($address) {
        $addressArray = array(
          'name' => $coinInfo->Currency_Symbol,

          'address' => $address->Address_Address,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . ($coin == 1 ? 'bitcoin:' : '') . '' . $address->Address_Address . '&choe=UTF-8'
        );
        return $addressArray;
      }
      return null;
    }
    return null;
  }

  public function checkWallet2($user, $coin)
  {

    // thông tin coin
    $coinInfo = DB::table('currency')->where('Currency_ID', $coin)->where('Currency_Active', 1)->first();

    if ($coinInfo) {

      $address = DB::table('address')->where('Address_User', $user->User_ID)->where('Address_Currency', $coin)->where('Address_IsUse', 0)->first();
      if ($address) {
        $addressArray = array(
          'name' => $coinInfo->Currency_Symbol,

          'address' => $address->Address_Address,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . ($coin == 1 ? 'bitcoin:' : '') . '' . $address->Address_Address . '&choe=UTF-8'
        );
        return $addressArray;
      }
      return null;
    }
    return null;
  }

  public function getDeposit(Request $req)
  {
    $user = Session::get('user') ?? Auth::user();
    switch ($req->coin) {
      case 1:
      // btc
      $addressArray = $this->checkWallet(1);
      if ($addressArray) {
        return response()->json($addressArray, 200);
      } else {

        $addressArray = array(
          'name' => 'BTC',
          'address' => '35XoDK2iwU7iNTa9XTf55v6KYHz3BvSZoy',
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=bitcoin:' . '35XoDK2iwU7iNTa9XTf55v6KYHz3BvSZoy' . '&choe=UTF-8'
        );
        return response()->json($addressArray, 200);

        $account = $this->coinbase()->getAccount('BTC');
        $address = new Address([
          'name' => 'New Address BTC of ID:' . $user->User_ID
        ]);
        $info = $this->coinbase()->createAccountAddress($account, $address);

        $btcAddress = $info->getaddress();

        $addressArray = array(
          'name' => 'BTC',
          'address' => $btcAddress,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=bitcoin:' . $btcAddress . '&choe=UTF-8'
        );

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 1;
        $wallet->Address_Address = $btcAddress;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return response()->json($addressArray, 200);
      }
      break;
      case 2:
      // eth
      $addressArray = $this->checkWallet(2);
      if ($addressArray) {
        return response()->json($addressArray, 200);
      } else {

        $addressArray = array(
          'name' => 'ETH',
          'address' => '0xb9DfF5584157B16E8315A63C268BB15670da2664',
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . '0xb9DfF5584157B16E8315A63C268BB15670da2664' . '&choe=UTF-8'
        );
        return response()->json($addressArray, 200);

        $account = $this->coinbase()->getAccount('ETH');


        $address = new Address([
          'name' => 'New Address ETH of ID:' . $user->User_ID
        ]);


        $info = $this->coinbase()->createAccountAddress($account, $address);

        $ethAddress = $info->getaddress();
        $addressArray = array(
          'name' => 'ETH',
          'address' => $ethAddress,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $ethAddress . '&choe=UTF-8'
        );

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 2;
        $wallet->Address_Address = $ethAddress;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return response()->json($addressArray, 200);
      }

      break;
      case 5:

      // Token
      $addressArray = $this->checkWallet(5);
      if ($addressArray) {
        return response()->json($addressArray, 200);
      } else {

        $addressArray = array(
          'name' => 'SystemUSDT',
          'address' => '0x00115be7e8f28f93c5215bcc34a56b239bb055ba',
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . '0x00115be7e8f28f93c5215bcc34a56b239bb055ba' . '&choe=UTF-8'
        );
        return response()->json($addressArray, 200);

        $createAddress = $this->createAddressUSDT();
        $createAddress = json_decode($createAddress);

        if (!$createAddress || $createAddress->status !== true) {
          exit();
        }
        $addressArray = array(
          'name' => 'SystemUSDT',
          'address' => $createAddress->address,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $createAddress->address . '&choe=UTF-8'
        );
        // Thêm địa chỉ ví vào DB

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 5;
        $wallet->Address_Address = $createAddress->address;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_PrivateKey = '';
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return response()->json($addressArray, 200);
      }
      break;
      case 12:

      // Token
      $addressArray = $this->checkWallet(12);
      if ($addressArray) {
        return response()->json($addressArray, 200);
      } else {

        $addressArray = array(
          'name' => 'AvailableUSDT',
          'address' => '0x00115be7e8f28f93c5215bcc34a56b239bb055ba',
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . '0x00115be7e8f28f93c5215bcc34a56b239bb055ba' . '&choe=UTF-8'
        );
        return response()->json($addressArray, 200);

        $createAddress = $this->createAddressUSDT();
        $createAddress = json_decode($createAddress);

        if (!$createAddress || $createAddress->status !== true) {
          exit();
        }
        $addressArray = array(
          'name' => 'USDT',
          'address' => $createAddress->address,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $createAddress->address . '&choe=UTF-8'
        );
        // Thêm địa chỉ ví vào DB

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 5;
        $wallet->Address_Address = $createAddress->address;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_PrivateKey = '';
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return response()->json($addressArray, 200);
      }
      break;

      case 8:


      $addressArray = array(
        'name' => 'EBP',
        'address' => '0xCa403a3820F3EDDFc31b1BDbDA10deE376df2FC6',
        'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . '0xCa403a3820F3EDDFc31b1BDbDA10deE376df2FC6' . '&choe=UTF-8'
      );
      return response()->json($addressArray, 200);
      // Token
      $addressArray = $this->checkWallet(8);
      if ($addressArray) {
        return response()->json($addressArray, 200);
      } else {

        $createAddress = $this->createAddressUSDT();
        $createAddress = json_decode($createAddress);

        if (!$createAddress || $createAddress->status !== true) {
          exit();
        }
        $addressArray = array(
          'name' => 'EBP',
          'address' => $createAddress->address,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $createAddress->address . '&choe=UTF-8'
        );
        // Thêm địa chỉ ví vào DB

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 8;
        $wallet->Address_Address = $createAddress->address;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_PrivateKey = $createAddress->pubic_key;
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return response()->json($addressArray, 200);
      }
      break;
    }
  }

  public function getAddressPri($user, $coin)
  {

    switch ($coin) {
      case 1:
      // btc
      $addressArray = $this->checkWallet2($user, 1);
      if ($addressArray) {
        return $addressArray;
      } else {
        $account = $this->coinbase()->getAccount('BTC');
        $address = new Address([
          'name' => 'New Address BTC of ID:' . $user->User_ID
        ]);
        $info = $this->coinbase()->createAccountAddress($account, $address);

        $btcAddress = $info->getaddress();

        $addressArray = array(
          'name' => 'BTC',
          'address' => $btcAddress,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=bitcoin:' . $btcAddress . '&choe=UTF-8'
        );

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 1;
        $wallet->Address_Address = $btcAddress;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return $addressArray;
      }
      break;
      case 2:
      // eth
      $addressArray = $this->checkWallet2($user, 2);
      if ($addressArray) {
        return $addressArray;
      } else {
        $account = $this->coinbase()->getAccount('ETH');


        $address = new Address([
          'name' => 'New Address ETH of ID:' . $user->User_ID
        ]);


        $info = $this->coinbase()->createAccountAddress($account, $address);

        $ethAddress = $info->getaddress();
        $addressArray = array(
          'name' => 'ETH',
          'address' => $ethAddress,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $ethAddress . '&choe=UTF-8'
        );

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 2;
        $wallet->Address_Address = $ethAddress;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return $addressArray;
      }

      break;

      case 5:

      // Token
      $addressArray = $this->checkWallet2($user, 5);
      if ($addressArray) {
        return $addressArray;
      } else {

        $createAddress = $this->createAddressUSDT();
        $createAddress = json_decode($createAddress);

        if (!$createAddress || $createAddress->status !== true) {
          exit();
        }
        $addressArray = array(
          'name' => 'USDT',
          'address' => $createAddress->address,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $createAddress->address . '&choe=UTF-8'
        );
        // Thêm địa chỉ ví vào DB

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 5;
        $wallet->Address_Address = $createAddress->address;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_PrivateKey = '';
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return $addressArray;
      }
      break;


      case 8:

      // Token
      $addressArray = $this->checkWallet($user, 8);
      if ($addressArray) {
        return $addressArray;
      } else {

        $createAddress = $this->createAddressUSDT();
        $createAddress = json_decode($createAddress);

        if (!$createAddress || $createAddress->status !== true) {
          exit();
        }
        $addressArray = array(
          'name' => 'EBP',
          'address' => $createAddress->address,
          'Qr' => 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $createAddress->address . '&choe=UTF-8'
        );
        // Thêm địa chỉ ví vào DB

        // Thêm địa chỉ ví vào DB
        $wallet = new Wallet();
        $wallet->Address_Currency = 8;
        $wallet->Address_Address = $createAddress->address;
        $wallet->Address_User = $user->User_ID;
        $wallet->Address_PrivateKey = $createAddress->pubic_key;
        $wallet->Address_IsUse = 0;
        $wallet->Address_Comment = 'Create new address';
        $wallet->save();
        return $addressArray;
      }
      break;


    }
  }

  public function createAddressUSDT()
  {

    $key = 'mj2ndXGskiNGB2inDprZ2i9AsnegdFPwxrlf0flkyCnVCzk3mp';
    $content = file_get_contents("https://tech.rezxcvbnm.co/public/address?key=$key");
    return $content;
  }
}
