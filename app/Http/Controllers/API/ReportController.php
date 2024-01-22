<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\System\CoinbaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;

use Image;
use PragmaRX\Google2FA\Google2FA;

use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;
use App\Jobs\SendMailJobs;
use App\Model\User;
use App\Model\Money;
use App\Model\GoogleAuth;
use App\Model\Wallet;
use App\Model\Investment;
use App\Model\Eggs;
use App\Model\Markets;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Mail;

class ReportController extends Controller
{
  public $fee_sell_egg_system = 0.05;
  public $ecosystem;

  public function __construct()
  {
    $this->middleware('auth:api');
    $this->ecosystem = [
      'Out' => ['id' => 'Out', 'name' => 'Outside'],
      //'BO' => ['id' => 'BO', 'name' => 'Eggsbook Binary Option'],
      //'System' => ['id' => 'System', 'name' => 'Eggsbook System'],
    ];

  }

  public function getHistoryWallet(Request $req)
  {
    $user = Auth::user();
    $limit = 20;
    $money = DB::table('money')
      ->leftjoin('moneyaction', 'Money_MoneyAction', 'MoneyAction_ID')
      ->leftjoin('currency', 'Money_Currency', 'Currency_ID')
      ->where('Money_MoneyStatus', 1)
      //->whereIn('Money_MoneyAction', [1,2,3,4,7,8])
      ->where('Money_User', $user->User_ID)
      ->orderByDesc('Money_ID');
    if ($req->id) {
      $money = $money->where('Money_ID', $req->id);
    }
    if ($req->amount) {
      $money = $money->where('Money_MoneyUSDT', 'LIKE', '%' . $req->amount . '%');
    }
    if ($req->action) {
      $action = $req->action;
      // var_dump($action);exit;
      $money = $money->whereRaw('Money_MoneyAction in (' . $action . ')');
    }
    if ($req->from) {
      $from = strtotime($req->from);
      $money = $money->where('Money_Time', '>=', $from);
    }
    if ($req->to) {
      $to = strtotime($req->to);
      $money = $money->where('Money_Time', '<=', $to);
    }
    if($req->limit){
      $money = $money->paginate($req->limit);
    }else{
      $money = $money->paginate($limit);
    }


    $list = [];
    $listUrlTran = [5 => 'https://tronscan.org/#/transaction/', 3 => 'https://tronscan.org/#/transaction/', 8 => 'https://tronscan.org/#/transaction/', 18 => 'https://tronscan.org/#/transaction/'];
    for ($i = 0; $i < count($money); $i++) {
      $status = '';
      if ($money[$i]->Money_MoneyStatus == 1) {
        $status = 'Active';
      }
      if ($money[$i]->Money_MoneyStatus == 2) {
        $status = 'Waiting';
      }
      if ($money[$i]->Money_MoneyStatus == -1) {
        $status = 'Cancel';
      }
      $list[$i] = [
        'id' => $money[$i]->Money_ID,
        'Amount' => $money[$i]->Money_MoneyAction == 2 ? ($money[$i]->Money_USDT + $money[$i]->Money_USDTFee) * 1 : $money[$i]->Money_USDT * 1,
        'Fee' => $money[$i]->Money_USDTFee * 1,
        'Rate' => $money[$i]->Money_Rate * 1,
        'Currency' => $money[$i]->Currency_Name,
        'Action' => $money[$i]->MoneyAction_Name,
        'comment' => $money[$i]->Money_Comment,
        'Time' => date('Y-m-d H:i:s', $money[$i]->Money_Time),
        'Status' => $status,
      ];
      if ($money[$i]->Money_MoneyAction == 2 && $money[$i]->Money_Confirm == 1 && $money[$i]->Money_TXID) {
        $list[$i]['url_hash_withdraw'] = $listUrlTran[$money[$i]->Currency_ID] . $money[$i]->Money_TXID;
      }
    }
    // $list = $money->items();
    $ecosystem = $this->ecosystem;
    if ($user->User_Level == 1) {
      //$price = json_decode(file_get_contents('https://admin-api.eggsbook.com/api/v1/price'))->price;
      //dd($price);
    }
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy();
    $getChannel = DB::table("money_channel_1vnp")->where("money_channel_status",1)->get()->toArray();
    //    dd($rate);
    $data = ['history' => $list, 'current_page' => $money->currentPage(), 'total_page' => $money->lastPage(), 'ecosystem' => $ecosystem];
    $coin = [
      'USDTTRC20' =>
      array(
        'ID' => 6,
        'Key' => 'USDTTRC20',
        'Name' => 'USDT TRC-20',
        'Symbol' => 'USDT',
        'Deposit' => true,
        'Withdraw' => [
          ['ID' => 6, 'Name' => 'USDT TRC20'],
        ],
        'WithdrawFee' => config('coin.USDT.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.USDT.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.USDT.WithdrawMin'),
        'TransferMin' => config('coin.USDT.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.USDT.TransferFee'),
        'Mainnet' => 'trx',
        'rate' => $rate['USDT'],
      ),
      'EUSD' =>
      array(
        'ID' => 3,
        'Key' => 'EUSD',
        'Name' => 'EUSD',
        'Symbol' => 'EUSD',
        'Deposit' => true,
        'Withdraw' => [
          //['ID' => 3, 'Name' => 'EUSD'],
          //['ID'=>5, 'Name'=>'USDT ERC-20'],
          //['ID' => 6, 'Name' => 'USDT TRC-20'],
        ],
        'WithdrawFee' => config('coin.EUSD.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.EUSD.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.EUSD.WithdrawMin'),
        'TransferMin' => config('coin.EUSD.TransferMin'),
        'Transfer' => true,
        'rate' => $rate['USDT'],
        'TransferFee' => config('coin.EUSD.TransferFee'),
        'Mainnet' => 'trx',
      ),
      /*
                        "8" =>
                        array (
                          'ID' => 8,
                          'Name' => 'EBP',
                          'Symbol' => 'EBP',
                          'Deposit' => false,
                          'Withdraw' => [8],
                          'WithdrawFee' => config('coin.EBP.WithdrawFee'),
                          'WithdrawMin' => config('coin.EBP.WithdrawMin'),
                          'TransferMin' => config('coin.EBP.TransferMin'),
                          'Transfer' => false,
                          'TransferFee' => config('coin.EBP.TransferFee'),
                        ),*/
      'USDTERC20' =>
      array(
        'ID' => 5,
        'Key' => 'USDTERC20',
        'Name' => 'USDT ERC-20',
        'rate' => $rate['USDT'],
        'Symbol' => 'USDT',
        'Deposit' => true,
        'Withdraw' => [],
        'WithdrawFee' => config('coin.USDT.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.USDT.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.USDT.WithdrawMin'),
        'TransferMin' => config('coin.USDT.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.USDT.TransferFee'),
        'Mainnet' => 'eth',
      ),

      'DP-NFT' =>
      array(
        'ID' => 4,
        'Key' => 'DP-NFT',
        'Name' => 'DP-NFT BEP-20',
        'Symbol' => 'DP-NFT',
        'Deposit' => true,
        'Swap' => false,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0.03,
        'rate' => $rate['DP-NFT'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.DP-NFT.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.DP-NFT.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.DP-NFT.WithdrawMin'),
        'TransferMin' => config('coin.DP-NFT.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.DP-NFT.TransferFee'),
        'Mainnet' => 'eth',
      ),

      'USDTBEP20' =>
      array(
        'ID' => 11,
        'Key' => 'USDTBEP20',
        'Name' => 'USDT BEP-20',
        'Symbol' => 'USDT',
        'Deposit' => true,
        'Withdraw' => [
          ['ID' => 11, 'Name' => 'USDT BEP-20'],
        ],
        'WithdrawFee' => config('coin.USDT.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.USDT.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.USDT.WithdrawMin'),
        'TransferMin' => config('coin.USDT.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.USDT.TransferFee'),
        'Mainnet' => 'eth',
        'rate' => $rate['USDT'],
      ),
      'HBG' =>
      array(
        'ID' => 7,
        'Key' => 'HBG',
        'Name' => 'HBG BEP-20',
        'Symbol' => 'HBG',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['HBG'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.HBG.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.HBG.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.HBG.WithdrawMin'),
        'TransferMin' => config('coin.HBG.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.HBG.TransferFee'),
        'Mainnet' => 'eth',
      ),
      'C98' =>
      array(
        'ID' => 13,
        'Key' => 'C98',
        'Name' => 'C98 BEP-20',
        'Symbol' => 'C98',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['C98'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.C98.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.C98.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.C98.WithdrawMin'),
        'TransferMin' => config('coin.C98.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.C98.TransferFee'),
        'Mainnet' => 'eth',
      ),
      'ADA' =>
      array(
        'ID' => 14,
        'Key' => 'ADA',
        'Name' => 'ADA BEP-20',
        'Symbol' => 'ADA',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['ADA'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.ADA.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.ADA.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.ADA.WithdrawMin'),
        'TransferMin' => config('coin.ADA.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.ADA.TransferFee'),
        'Mainnet' => 'eth',
      ),
      'BNB' =>
      array(
        'ID' => 16,
        'Key' => 'BNB',
        'Name' => 'BNB',
        'Symbol' => 'BNB',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['BNB'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.BNB.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.BNB.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.BNB.WithdrawMin'),
        'TransferMin' => config('coin.BNB.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.BNB.TransferFee'),
        'Mainnet' => 'eth',
      ),
      'TRX' =>
      array(
        'ID' => 15,
        'Key' => 'TRX',
        'Name' => 'TRON',
        'Symbol' => 'TRX',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['TRX'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.TRX.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.TRX.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.TRX.WithdrawMin'),
        'TransferMin' => config('coin.TRX.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.TRX.TransferFee'),
        'Mainnet' => 'trx',
      ),
      'VNĐ' =>
      array(
        'ID' => 21,
        'Key' => 'VNĐ',
        'Name' => 'VNĐ',
        'Symbol' => 'VNĐ',
        'Deposit' => true,
        'Withdraw' => true,
        'Info' => $getChannel,
        'rate' => $rate['VNĐ'],
      ),
    ];

    if ($user->User_Level == 1) {
      //          dd($rate);
      //      $coin['SOL'] = array(
      //        'ID' => 12,
      //        'Key' => 'SOL',
      //        'Name' => 'SOL BEP-20',
      //        'Symbol' => 'SOL',
      //        'Deposit' => true,
      //        'Swap' => true,
      //        'SwapTo' => [],
      //        'MaxSwap' => 0,
      //        'FeeSwap' => 0,
      //        'rate' => $rate['SOL'],
      //        'MinSwap' => 0,//20
      //        'Withdraw' => [
      //
      //        ],
      //        'WithdrawFee' => config('coin.SOL.WithdrawFee'),
      //        'WithdrawFeeEcoSystem' => config('coin.SOL.WithdrawFeeEcoSystem'),
      //        'WithdrawMin' => config('coin.SOL.WithdrawMin'),
      //        'TransferMin' => config('coin.SOL.TransferMin'),
      //        'Transfer' => false,
      //        'TransferFee' => config('coin.SOL.TransferFee'),
      //        'Mainnet' => 'eth',
      //      );
      //            $rate['DP-NFT'] = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('DP-NFT');
      //            dd($rate);
      //            array_push($coin['HBG']['Withdraw'], ['ID' => 7, 'Name' => 'HBG BEP-20']);
      //            array_push($coin['DP-NFT']['Withdraw'], ['ID' => 4, 'Name' => 'DP-NFT BEP-20']);
      //            dd($coin);
    }
    //if ($user->User_Level == 1) {

    array_push($coin['HBG']['Withdraw'], ['ID' => 7, 'Name' => 'HBG BEP-20']);
    //    array_push($coin['DP-NFT']['Withdraw'], ['ID' => 4, 'Name' => 'DP-NFT BEP-20']);

    // }
    return response(array('status' => true, 'data' => $data, 'coin' => $coin), 200);
  }

  public function getHistoryWalletNew(Request $req)
  {

    $user = Auth::user();
    $limit = 20;
    $money = DB::table('money')
      ->leftjoin('moneyaction', 'Money_MoneyAction', 'MoneyAction_ID')
      ->leftjoin('currency', 'Money_Currency', 'Currency_ID')
      ->where('Money_MoneyStatus', 1)
      //->whereIn('Money_MoneyAction', [1,2,3,4,7,8])
      ->where('Money_User', $user->User_ID)
      ->orderByDesc('Money_ID');
    if ($req->id) {
      $money = $money->where('Money_ID', $req->id);
    }
    if ($req->amount) {
      $money = $money->where('Money_MoneyUSDT', 'LIKE', '%' . $req->amount . '%');
    }
    if ($req->action) {
      $action = $req->action;
      // var_dump($action);exit;
      $money = $money->whereRaw('Money_MoneyAction in (' . $action . ')');
    }
    if ($req->from) {
      $from = strtotime($req->from);
      $money = $money->where('Money_Time', '>=', $from);
    }
    if ($req->to) {
      $to = strtotime($req->to);
      $money = $money->where('Money_Time', '<=', $to);
    }

    $money = $money->paginate($limit);

    $list = [];
    $listUrlTran = [5 => 'https://tronscan.org/#/transaction/', 3 => 'https://tronscan.org/#/transaction/', 8 => 'https://tronscan.org/#/transaction/', 18 => 'https://tronscan.org/#/transaction/'];
    for ($i = 0; $i < count($money); $i++) {
      $status = '';
      if ($money[$i]->Money_MoneyStatus == 1) {
        $status = 'Active';
      }
      if ($money[$i]->Money_MoneyStatus == 2) {
        $status = 'Waiting';
      }
      if ($money[$i]->Money_MoneyStatus == -1) {
        $status = 'Cancel';
      }
      $list[$i] = [
        'id' => $money[$i]->Money_ID,
        'Amount' => $money[$i]->Money_MoneyAction == 2 ? ($money[$i]->Money_USDT + $money[$i]->Money_USDTFee) * 1 : $money[$i]->Money_USDT * 1,
        'Fee' => $money[$i]->Money_USDTFee * 1,
        'Rate' => $money[$i]->Money_Rate * 1,
        'Currency' => $money[$i]->Currency_Name,
        'Action' => $money[$i]->MoneyAction_Name,
        'comment' => $money[$i]->Money_Comment,
        'Time' => date('Y-m-d H:i:s', $money[$i]->Money_Time),
        'Status' => $status,
      ];
      if ($money[$i]->Money_MoneyAction == 2 && $money[$i]->Money_Confirm == 1 && $money[$i]->Money_TXID) {
        $list[$i]['url_hash_withdraw'] = $listUrlTran[$money[$i]->Currency_ID] . $money[$i]->Money_TXID;
      }
    }
    // $list = $money->items();
    $ecosystem = $this->ecosystem;
    if ($user->User_Level == 1) {
      //$price = json_decode(file_get_contents('https://admin-api.eggsbook.com/api/v1/price'))->price;
      //dd($price);
    }
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy();
    $getChannel = DB::table("money_channel_1vnp")->where("money_channel_status",1)->get()->toArray();
    $coin = [
      'USDTTRC20' =>
      array(
        'ID' => 6,
        'Key' => 'USDTTRC20',
        'Name' => 'USDT TRC-20',
        'Symbol' => 'USDT',
        'Deposit' => true,
        'Withdraw' => [
          //['ID' => 6, 'Name' => 'USDT TRC20'],
        ],
        'WithdrawFee' => config('coin.USDT.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.USDT.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.USDT.WithdrawMin'),
        'TransferMin' => config('coin.USDT.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.USDT.TransferFee'),
        'Mainnet' => 'trx',
        'rate' => $rate['USDT'],
      ),
      'EUSD' =>
      array(
        'ID' => 3,
        'Key' => 'EUSD',
        'Name' => 'EUSD',
        'Symbol' => 'EUSD',
        'Deposit' => true,
        'Withdraw' => [
          //['ID' => 3, 'Name' => 'EUSD'],
          //['ID'=>5, 'Name'=>'USDT ERC-20'],
          //['ID' => 6, 'Name' => 'USDT TRC-20'],
        ],
        'WithdrawFee' => config('coin.EUSD.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.EUSD.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.EUSD.WithdrawMin'),
        'TransferMin' => config('coin.EUSD.TransferMin'),
        'Transfer' => true,
        'rate' => $rate['USDT'],
        'TransferFee' => config('coin.EUSD.TransferFee'),
        'Mainnet' => 'trx',
      ),
      /*
                  "8" =>
                  array (
                    'ID' => 8,
                    'Name' => 'EBP',
                    'Symbol' => 'EBP',
                    'Deposit' => false,
                    'Withdraw' => [8],
                    'WithdrawFee' => config('coin.EBP.WithdrawFee'),
                    'WithdrawMin' => config('coin.EBP.WithdrawMin'),
                    'TransferMin' => config('coin.EBP.TransferMin'),
                    'Transfer' => false,
                    'TransferFee' => config('coin.EBP.TransferFee'),
                  ),*/
      'USDTERC20' =>
      array(
        'ID' => 5,
        'Key' => 'USDTERC20',
        'Name' => 'USDT ERC-20',
        'rate' => $rate['USDT'],
        'Symbol' => 'USDT',
        'Deposit' => true,
        'Withdraw' => [],
        'WithdrawFee' => config('coin.USDT.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.USDT.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.USDT.WithdrawMin'),
        'TransferMin' => config('coin.USDT.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.USDT.TransferFee'),
        'Mainnet' => 'eth',
      ),
      // 'DP-NFT' =>
      // array(
      //   'ID' => 4,
      //   'Key' => 'DP-NFT',
      //   'Name' => 'DP-NFT BEP-20',
      //   'Symbol' => 'DP-NFT',
      //   'Deposit' => true,
      //   'Swap' => false,
      //   'SwapTo' => [],
      //   'MaxSwap' => 0,
      //   'FeeSwap' => 0.03,
      //   'rate' => $rate['DP-NFT'],
      //   'MinSwap' => 0,//20
      //   'Withdraw' => [

      //   ],
      //   'WithdrawFee' => config('coin.DP-NFT.WithdrawFee'),
      //   'WithdrawFeeEcoSystem' => config('coin.DP-NFT.WithdrawFeeEcoSystem'),
      //   'WithdrawMin' => config('coin.DP-NFT.WithdrawMin'),
      //   'TransferMin' => config('coin.DP-NFT.TransferMin'),
      //   'Transfer' => false,
      //   'TransferFee' => config('coin.DP-NFT.TransferFee'),
      //   'Mainnet' => 'eth',
      // ),

      'USDTBEP20' =>
      array(
        'ID' => 11,
        'Key' => 'USDTBEP20',
        'Name' => 'USDT BEP-20',
        'Symbol' => 'USDT',
        'Deposit' => true,
        'Withdraw' => [
          ['ID' => 11, 'Name' => 'USDT BEP-20'],
        ],
        'WithdrawFee' => config('coin.USDT.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.USDT.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.USDT.WithdrawMin'),
        'TransferMin' => config('coin.USDT.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.USDT.TransferFee'),
        'Mainnet' => 'eth',
        'rate' => $rate['USDT'],
      ),
      'HBG' =>
      array(
        'ID' => 7,
        'Key' => 'HBG',
        'Name' => 'HBG BEP-20',
        'Symbol' => 'HBG',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['HBG'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.HBG.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.HBG.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.HBG.WithdrawMin'),
        'TransferMin' => config('coin.HBG.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.HBG.TransferFee'),
        'Mainnet' => 'eth',
      ),
      'C98' =>
      array(
        'ID' => 13,
        'Key' => 'C98',
        'Name' => 'C98 BEP-20',
        'Symbol' => 'C98',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['C98'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.C98.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.C98.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.C98.WithdrawMin'),
        'TransferMin' => config('coin.C98.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.C98.TransferFee'),
        'Mainnet' => 'eth',
      ),
      'ADA' =>
      array(
        'ID' => 14,
        'Key' => 'ADA',
        'Name' => 'ADA BEP-20',
        'Symbol' => 'ADA',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['ADA'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.ADA.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.ADA.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.ADA.WithdrawMin'),
        'TransferMin' => config('coin.ADA.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.ADA.TransferFee'),
        'Mainnet' => 'eth',
      ),
      'BNB' =>
      array(
        'ID' => 16,
        'Key' => 'BNB',
        'Name' => 'BNB',
        'Symbol' => 'BNB',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['BNB'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.BNB.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.BNB.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.BNB.WithdrawMin'),
        'TransferMin' => config('coin.BNB.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.BNB.TransferFee'),
        'Mainnet' => 'eth',
      ),
      'TRX' =>
      array(
        'ID' => 15,
        'Key' => 'TRX',
        'Name' => 'TRON',
        'Symbol' => 'TRX',
        'Deposit' => true,
        'Swap' => true,
        'SwapTo' => [],
        'MaxSwap' => 0,
        'FeeSwap' => 0,
        'rate' => $rate['TRX'],
        'MinSwap' => 0,//20
        'Withdraw' => [

        ],
        'WithdrawFee' => config('coin.TRX.WithdrawFee'),
        'WithdrawFeeEcoSystem' => config('coin.TRX.WithdrawFeeEcoSystem'),
        'WithdrawMin' => config('coin.TRX.WithdrawMin'),
        'TransferMin' => config('coin.TRX.TransferMin'),
        'Transfer' => false,
        'TransferFee' => config('coin.TRX.TransferFee'),
        'Mainnet' => 'trx',
      ),
      'VNĐ' =>
      array(
        'ID' => 21,
        'Key' => 'VNĐ',
        'Name' => 'VNĐ',
        'Symbol' => 'VNĐ',
        'Deposit' => true,
        'Withdraw' => true,
        'Info' => $getChannel,
        'rate' => $rate['VNĐ'],
      ),
    ];
    // $data = ['history' => $list, 'current_page' => $money->currentPage(), 'total_page' => $money->lastPage(), 'ecosystem' => $ecosystem, 'coin' => $coin];
    $list = [
      'history' => $list,
      'current_page' => $money->currentPage(),
      'total_page' => $money->lastPage(),
      'ecosystem' => $ecosystem,
    ];
    $data = ['list' => $list, 'coin' => $coin];

    if ($user->User_Level == 1) {
      //          dd($rate);
      //      $coin['SOL'] = array(
      //        'ID' => 12,
      //        'Key' => 'SOL',
      //        'Name' => 'SOL BEP-20',
      //        'Symbol' => 'SOL',
      //        'Deposit' => true,
      //        'Swap' => true,
      //        'SwapTo' => [],
      //        'MaxSwap' => 0,
      //        'FeeSwap' => 0,
      //        'rate' => $rate['SOL'],
      //        'MinSwap' => 0,//20
      //        'Withdraw' => [
      //
      //        ],
      //        'WithdrawFee' => config('coin.SOL.WithdrawFee'),
      //        'WithdrawFeeEcoSystem' => config('coin.SOL.WithdrawFeeEcoSystem'),
      //        'WithdrawMin' => config('coin.SOL.WithdrawMin'),
      //        'TransferMin' => config('coin.SOL.TransferMin'),
      //        'Transfer' => false,
      //        'TransferFee' => config('coin.SOL.TransferFee'),
      //        'Mainnet' => 'eth',
      //      );
      //            $rate['DP-NFT'] = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('DP-NFT');
      //            dd($rate);
      //            array_push($coin['HBG']['Withdraw'], ['ID' => 7, 'Name' => 'HBG BEP-20']);
      //            array_push($coin['DP-NFT']['Withdraw'], ['ID' => 4, 'Name' => 'DP-NFT BEP-20']);
      //            dd($coin);
    }
    //if ($user->User_Level == 1) {
    array_push($coin['HBG']['Withdraw'], ['ID' => 7, 'Name' => 'HBG BEP-20']);
    //array_push($coin['DP-NFT']['Withdraw'], ['ID' => 4, 'Name' => 'DP-NFT BEP-20']);

    // }
    return response(array('status' => true, 'data' => $data, 'coin' => $coin), 200);
  }

  public function getHistoryGame(Request $req)
  {
    $user = Auth::user();
    if (!isset($req->fromDate)) {
      $fromDate = strtotime(date('Y-m-d 11:59:59', strtotime('-1 day')));
    } else {
      $fromDate = strtotime($req->fromDate . ' 11:59:59');
    }
    if (!isset($req->toDate)) {
      $toDate = strtotime(date('Y-m-d 11:59:59', strtotime('-0 day')));
    } else {
      $toDate = strtotime($req->toDate . ' 11:59:59');
    }


    $history = [];

    switch ($req->game) {
      case 1:

      $history = app('App\Http\Controllers\API\SAGameController')->getHistory($user->User_ID, $fromDate, $toDate);

      break;

      case 2:

      $history = app('App\Http\Controllers\API\SAGameController')->getHistory($user->User_ID, $fromDate, $toDate);

      break;

      case 3:

      $history = app('App\Http\Controllers\API\BoController')->getHistory($user->User_ID, $fromDate, $toDate);

      break;
    }
    $data = ['history' => $history];
    return response(array('status' => true, 'data' => $data), 200);

  }

  public function getHistoryLogin(Request $req)
  {
    $user = Auth::user();
    $history = app('App\Http\Controllers\API\DashboardController')->getHistoryLogin($user->User_ID);
    return $this->response(200, ['history' => $history], "", "", true);

  }
}
