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
use App\Jobs\SendTelegramJobs;
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

class InsuranceController extends Controller
{
  	public $feeInsur = 0.1;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getInsurance(Request $req){
      	$user = Auth::user();
        $balance['EUSD']  = User::getBalance($user->User_ID , 3);
      	$fee = DB::table('promotion_date')->where('Status', 1)->pluck('Fee', 'Date')->toArray();
      	$list_game = DB::table('promotion_game')->where('Promotion_Game_Status', 1)->get();
      	$list_countries = DB::table('promotion_countries')->where('Status', 1)->get();
      	$time_zoom = DB::table('promotion_time_zoom')->where('status', 1)->get();
      	$time_limit = DB::table('promotion_date')->where('Status', 1)->get();
      	$history = DB::table('promotion_sub')->where('user_id', $user->User_ID)->get();
        //$data = ['balancce'=>$list, 'fee'=>$money->currentPage(), 'total_page'=>$money->lastPage() ];
      	$data = ['balance'=>$balance['EUSD'], 'fee'=>$fee, 'list_game'=>$list_game, 'list_countries'=> $list_countries, 'list_zoom'=>$time_zoom, 'list_limit'=> $time_limit, 'history'=>$history];
        return response(array('status'=>true, 'data'=>$data), 200);
    }

  	public function postInsurance(Request $req){
      	$user = Auth::user();
      	
        $validator = Validator::make($req->all(), [
            'amount' => 'required|numeric|min:0',
            'time' => 'required|string',
            'countries' => 'required|string',
            'game' => 'required|string',
          	'days' => 'required|numeric',
		],[
          'amount.required' => trans('notification.amount_required') ,
    	]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $value) {
                return $this->response(200, [], $value, $validator->errors(), false);
            }
        }
      	$check_date = DB::table('promotion_date')->where('Status', 1)->where('Date', $req->days)->first();
      	if(!$check_date){
          return $this->response(200, [], trans('notification.Date_Invalid'), [], false);
        }
        //ID người nhận
      	$amount = $req->amount;
		//amount am
		if(!$req->amount || $req->amount <= 0){
            return $this->response(200, [], trans('notification.Invalid'), [], false);
        }
        //check balance
        $balance = User::getBalance($user->User_ID, 3);
        //Tính toán phí
      	$days = $req->days;
      	$fee = $check_date->Fee;
      	$feeInsur = $fee;
        $amountFee = $feeInsur*$amount;
        //check m\amount balance
        if($amountFee > $balance){
            return $this->response(200, [], trans('notification.Your_balance_is_not_enough!'), [], false);
		}
      	$minInsurrance = DB::table('promotion_min')->where('Status', 1)->orderBy('ID', 'DESC')->first();
      	$minInsurrance = $minInsurrance->Min * 1;
      	if($req->amount < $minInsurrance){
            return $this->response(200, [], 'Min Buy Insurrance $'.$minInsurrance.'!', [], false);
        }
        // lưu lịch sử
        $arrayInsert = array(
          'Money_User' => $user->User_ID,
          'Money_USDT' => -($amountFee),
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => 'Buy Insurrance Time '.$req->time.' $'.$req->amount.' Fee $'.$amountFee,
          'Money_MoneyAction' => 73,
          'Money_MoneyStatus' => 1,
          'Money_Currency' => 3,
          'Money_CurrentAmount' => $amountFee,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 1,
        );
      	$dataInsr = [
          	'user_id' => $user->User_ID,
          	'amount' => $amount,
          	'game' => $req->game,
          	'time' => $req->time,
          	'days' => $days,
          	'countries' => $req->countries,
          	'created_time' => date('Y-m-d H:i:s'),
          	'expired_time' => date('Y-m-d H:i:s', strtotime('+'.$days.' days')),
          	'balance' => $balance,
        ];
        $id = Money::insertGetId($arrayInsert);
      	$cancelOld = DB::table('promotion_sub')->where('user_id', $user->User_ID)->where('game', $req->game)->where('status', 0)->update(['status'=>-1]);
      	$insertPromo = DB::table('promotion_sub')->insert($dataInsr);
      
        $message = "<b> NOTICE PROMOTION INSURRANCE</b>\n"
          . "ID: <b>$user->User_ID</b>\n"
          . "NAME: <b>$user->User_Name</b>\n"
          . "EMAIL: <b>$user->User_Email</b>\n"
          . "AMOUNT: <b>$$amount</b>\n"
          . "TIME: <b>$req->time</b>\n"
          . "CONTENT: <b>Buy Insurrance $$req->amount Fee $$amountFee</b>\n"
          . "<b>Submit Time: </b>\n"
          . date('d-m-Y H:i:s',time());
		//dd($message);
       	dispatch(new SendTelegramJobs($message, -419904681));
      
        return $this->response(200, [], "Buy Insurrance $ $req->amount Fee $$amountFee Success!", [], true);
    }
  
  	public function postIncreaAmount(Request $req){
		$user = Auth::user();
        $validator = Validator::make($req->all(), [
            'id' => 'required|exists:promotion_sub,id',
            'amount' => 'required|numeric|min:0',
          	'days' => 'required|numeric|in:7,30',
		]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $value) {
                return $this->response(200, [], $value, $validator->errors(), false);
            }
        }
      	$getProInsur = DB::table('promotion_sub')->where('id', $req->id)->first();
      	if(!$getProInsur){
            return $this->response(200, [], trans('notification.Insurrance_Not_Found'), [], false);
        }
      	if($getProInsur->status != 0){
            return $this->response(200, [], trans('notification.Please_submit_another_request_insurrance'), [], false);
        }
      	$amountOld = $getProInsur->amount;
      	$amount = $req->amount;
		//amount am
		if(!$req->amount || $req->amount <= 0){
            return $this->response(200, [], trans('notification.Invalid'), [], false);
        }
      	$minInsurrance = 500;
      	if($req->amount < $minInsurrance){
            return $this->response(200, [], 'Min Increa Insurrance $'.$minInsurrance.'!', [], false);
        }
      	$fee = $this->feeInsur;
      	$days = $req->days;
      	$feeInsur = $fee;
        //check balance
        $balance = User::getBalance($user->User_ID, 3);
      	$calDay = ((strtotime($getProInsur->expired_time) - time())/86400);
        //Tính toán phí
        $amountFee = $amount*($feeInsur/$days)*$calDay;
        //check m\amount balance
        if($amountFee > $balance){
            return $this->response(200, [], trans('notification.Your_balance_is_not_enough!'), [], false);
		}
        // lưu lịch sử
        $arrayInsert = array(
          'Money_User' => $user->User_ID,
          'Money_USDT' => -($amountFee),
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => 'Increament Insurrance Time '.$req->time.' $'.$req->amount.' Fee $'.$amountFee,
          'Money_MoneyAction' => 74,
          'Money_MoneyStatus' => 1,
          'Money_Currency' => 3,
          'Money_CurrentAmount' => $amountFee,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 1,
        );
      	$dataInsr = [
          	'user_id' => $user->User_ID,
          	'amount' => $amountOld+$amount,
          	'time' => $getProInsur->time,
          	'days' => $getProInsur->days,
          	'created_time' => date('Y-m-d H:i:s'),
          	'expired_time' => $getProInsur->expired_time,
          	'balance' => $balance,
        ];
      	$insertPromo = DB::table('promotion_sub')->where('id', $getProInsur->id)->update(['status'=>-1]);
        $id = Money::insertGetId($arrayInsert);
      	$insertPromo = DB::table('promotion_sub')->insert($dataInsr);
		//trừ tiền thăngf chuyển
		$packageName = DB::table('package')->where('package_ID', $user->user_Agency_Level)->value('package_Name');
        $message = "<b> NOTICE INCREAMENT INSURRANCE</b>\n"
          . "ID: <b>$user->User_ID</b>\n"
          . "NAME: <b>$user->User_Name</b>\n"
          . "EMAIL: <b>$user->User_Email</b>\n"
          . "AMOUNT: <b>$$amount</b>\n"
          . "TIME: <b>$getProInsur->time</b>\n"
          . "CONTENT: <b>Increament Insurrance $$req->amount Fee $$amountFee</b>\n"
          . "<b>Submit Time: </b>\n"
          . date('d-m-Y H:i:s',time());
		//dd($message);
       	dispatch(new SendTelegramJobs($message, -256866825));
      
        return $this->response(200, [], "Increament Insurrance $ $req->amount Fee $$amountFee Success!", [], true);
    }
}
