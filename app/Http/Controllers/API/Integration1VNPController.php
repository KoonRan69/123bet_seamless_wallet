<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Profile;
use App\Model\GoogleAuth;
use App\Model\LogUser;
use App\Model\User;
use App\Model\userBalance;
use App\Jobs\SendTelegramJobs;
use App\Model\Money;
use App\Model\logMoney;
use App\Model\Money1VPN;
use Illuminate\Support\Facades\Http;
use Validator;
use DB;

class Integration1VNPController extends Controller
{
  public $difference_fee;
  public $merchant;
  public $apikey;
  public $domainGateUSDT;
  public $domainGateVND;
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['returnResultDeposit']]);
    $this->merchant = "vnpay103135";
    $this->apikey = "fcce2d2e12704f78f55703ee3323e04f";
    $this->domainGateVND = "https://pay.1vnpay.org/api/v1/"; //Test
    $this->difference_fee = 50; //vnđ
  }

  /*
  *Cổng nạp VNĐ: MOMO , BANK , VIETTEL PAY , ZALO , THẺ CÀO
  *User đặt lệnh nạp và rút - Admin duyệt nạp và rút thì sẽ call API qua 1VNP
  */
  public function listBank(Request $req){
    $list = DB::table("bank")->where("bank_status",1)->get();
    return $this->response(200, $list);
  }

  public function postOrderPayout(Request $req){
    $user = $req->user();
    $validator = Validator::make($req->all(), [
      'amount' => 'required|numeric|min:3|nullable',
      'otp' => 'required',
      'channel' => 'required|exists:money_channel_1vnp,money_channel_code',
      'bank_code' => 'required|exists:bank,bank_code',
      'bank_number' => 'required|numeric',
      'beneficiary_name' => 'nullable',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $channel = $req->channel;

    $getChannel = DB::table("money_channel_1vnp")->where("money_channel_code",$channel)->where("money_channel_status",1)->where("money_channel_withdraw",1)->first();
    if (!$getChannel) {
      return $this->response(200, [], "Channel invalid", [], false);
    }

    $user = User::where('User_ID', $user->User_ID)->first();

    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    //check OTP
    $tokenOTP = $user->otp_w;
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
    if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] != $req->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);


    $amount = $req->amount;
    $coin = 21;

    $coinArr = DB::table('currency')->whereIn('Currency_ID', [3,21])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    if (!isset($coinArr[$coin])) {
      return $this->response(200, [], trans('notification.Coin_invalid'), [], false);
    }

    $symbol_from = $coinArr[3];
    $rate_from = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol_from);
    //Rút từ ví nào
    $symbol_to = $coinArr[$coin];
    $rate_to = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol_to)['SELL'];
    //Balance
    $balance = User::getBalance($user->User_ID, 3);
    if($amount > $balance){
      return $this->response(200, ['balance'=>$balance], trans('notification.Your_balance_is_not_enough'), [], false);
    }


    $amountFee = $amount * ($getChannel->money_channel_fee_withdraw / 100); //sẽ update thêm phần trừ 50 vnđ

    $amountOrderWidthdraw = $amount - $amountFee;
    $amountOrderWidthdraw = $amountOrderWidthdraw * $rate_to;

    $comment = 'Withdraw ' . ($amount * 1) . ' ' . $symbol_from. '~'.($amount * $rate_to). ' VNĐ - channel: ' . $channel.' fee: $getChannel->money_channel_fee_withdraw'.'%';
    $commentTelegram = 'WITHDRAW';
    $order_no = $this->generateHashPayment($user->User_ID);

    // lưu lịch sử
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$amount + $amountFee,
      'Money_USDTFee' => -$amountFee,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => 2,
      'Money_MoneyStatus' => 1,
      'Money_Currency' => 3,
      'Money_CurrentAmount' => ($amount - $amountFee),
      'Money_CurrencyFrom' => 3,
      'Money_CurrencyTo' => $coin,
      'Money_Rate' => $rate_from,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
    );
    $id = Money::insertGetId($arrayInsert);

    $data = new Money1VPN();
    $data->Money_1VPN_User = $user->User_ID;
    $data->Money_1VPN_Amount = -$amountOrderWidthdraw;
    $data->Money_1VPN_Fee = $amountFee;
    $data->Money_1VPN_Rate_USDTVND = $rate_to;
    $data->Money_1VPN_Rate_VNDUSDT = $rate_from;
    $data->Money_1VPN_Hash = $order_no;
    $data->Money_1VPN_Comment = "Order payout $amountOrderWidthdraw VNĐ";
    $data->Money_1VPN_Time = time();
    $data->Money_1VPN_Currency = 'VNĐ';
    $data->Money_1VPN_Status = 0;
    $data->Money_1VPN_Channel = $channel;
    $data->Money_1VPN_Action = 2;
    $data->Money_1VPN_MoneyID = $id;

    $data->Money_1VPN_Bank_Code = $req->bank_code;
    $data->Money_1VPN_Bank_Number = $req->bank_number;
    $data->Money_1VPN_Beneficiary_Name = $req->beneficiary_name;
    $data->save();

    $username = !$user->User_Name ? $user->User_Email : $user->User_Name;
    $message = "$username $commentTelegram " . $amount . " $symbol_from\n"
      . "<b>User ID: </b> "
      . "$user->User_ID\n"
      . "<b>Username or Email: </b> "
      . "$username\n"
      . "<b>Amount USD: </b> "
      . ($amount - $amountFee) . " USD\n"
      . "<b>Amount VNĐ: </b> "
      . number_format($amountOrderWidthdraw) . ' VNĐ' . "\n"
      . "<b>Bank Info</b> - "
      . "<b>Bank:</b> $req->bank_code - "
      . "<b>Bank Number:</b> $req->bank_number - "
      . "<b>Beneficiary Name:</b> $req->bank_code\n"
      . "<b>Rate USDT/VNĐ: </b> "
      . $rate_from."/".$rate_to."\n"
      . "<b>Submit withdraw Time: </b>\n"
      . date('d-m-Y H:i:s', time());

    dispatch(new SendTelegramJobs($message, -448649753));
    $withdraw = config('utils.action.withdraw');
    LogUser::addLogUser($user->User_ID, $withdraw['action_type'], $withdraw['message'] . ' ' .$comment, $req->ip());

    $user->otp_w = '';
    $user->save();

    return $this->response(200, ['balance' => array('main' => (float)User::getBalance($user->User_ID, 3))], 'You ' . $comment, [], true);

  }
  public function orderStatusQuery(Request $req){
    $user = $req->user();

    $getMoneyOrderCheck = Money1VPN::where("Money_1VPN_ID",$req->id)->where("Money_1VPN_User",$user->User_ID)->first();
    if(!$getMoneyOrderCheck){
      return $this->response(200, [],  "The command to be checked does not exist!", [], false);
    }
    if($getMoneyOrderCheck->Money_1VPN_Status == 1){
      return $this->response(200, [],  "The deposit order has been processed. Please check again!", [], false);
    }

    $merchant_no = $this->merchant;
    $order_no = $getMoneyOrderCheck->Money_1VPN_Hash;
    $amount = $getMoneyOrderCheck->Money_1VPN_Amount;
    $channel = $getMoneyOrderCheck->Money_1VPN_Channel;
    $sign = md5($merchant_no."|".$order_no."|".$amount."|".$channel."|".$this->apikey);
    $url = $this->domainGateVND.'checkOrderStatus';
    $arrdata = [
      "merchant_no" => $merchant_no,
      "order_no"=> $order_no,
      "amount"=> $amount,
      "channel"=> $channel,
      "sign"=>$sign
    ];
    try{ 
      $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
      $response = $client->request('GET', $url, [
        'query' => [
          "merchant_no" => $merchant_no,
          "order_no"=> $order_no,
          "amount"=> $amount,
          "channel"=> $channel,
          "sign"=>$sign
        ],
      ]);
      $response =json_decode($response->getBody(), true);
      return $this->response(200, $response);
    } catch (\Exception $e) {
      $message = $user->User_ID. " check order error ".$e->getMessage()."\n"
        . "<b>Project: </b>"
        . "123betnow.co\n"
        . "<b>User ID: </b>"
        . "$user->User_ID\n"
        . "<b>Email: </b>"
        . "$user->User_Email\n"
        . "<b>Time: </b>"
        . date('d-m-Y H:i:s',time());
      dispatch(new SendTelegramJobs($message, -398297366));
      return $this->response(200, [],  $e->getMessage(), [], false);
    }
  }


  public function getChannel(){
    $list = DB::table("money_channel_1vnp")->where("money_channel_status",1)->get();
    return $this->response(200, $list);
  }

  public function getHistoryOrder(Request $req){
    $user = $req->user();

    $where = null;
    if ($req->datefrom and $req->dateto) {
      $where .= " AND Money_1VPN_Time >=".date('Y-m-d H:i:s',strtotime($req->datefrom))." AND Money_1VPN_Time < ".date('Y-m-d H:i:s',strtotime($req->dateto) + 86400);
    }
    if ($req->datefrom and !$req->dateto) {
      $where .= " AND Money_1VPN_Time >=".date('Y-m-d H:i:s',strtotime($req->datefrom));
    }
    if (!$req->datefrom and $req->dateto) {
      $where .= " AND Money_1VPN_Time < ".date('Y-m-d H:i:s',strtotime($req->dateto) + 86400);
    }
    if ($req->status != '') {
      if ($req->status == 0) {
        $where .= ' AND Money_1VPN_Status = 0';
      } else {
        $where .= ' AND Money_1VPN_Status = '.(int)$req->status;
      }
    }
    if ($req->action) {
      $where .= ' AND Money_1VPN_Action IN ('.$req->action.')';
    } 
    $list = Money1VPN::where("Money_1VPN_User",$user->User_ID)->whereRaw('1 ' . $where)->orderByDesc("Money_1VPN_Time")->paginate(15);
    return $this->response(200, $list);
  }

  public function returnResultDeposit(Request $req){

    $order_no = $req->order_no;
    $merchant_no = $req->merchant_no;
    $ylt_order_no = $req->ylt_order_no;
    $amount = $req->amount;
    $channel = $req->channel;
    $sign = $req->sign;
    $result_code = $req->result_code;
    $extra_param = $req->extra_param;

    if($this->merchant != $merchant_no){
      return $this->response(200, [], "Error!", [], false);
    }

    $checkMoneyOrder = Money1VPN::where('Money_1VPN_Hash',$order_no)->where('Money_1VPN_Channel',$channel)->where('Money_1VPN_Action',1)->first();
    if($checkMoneyOrder){

      if($checkMoneyOrder->Money_1VPN_Status == 1){
        return $this->response(200, [], "Order confirmed", [], false);
      }
      $user = User::find($checkMoneyOrder->Money_1VPN_User);
      $username = !$user->User_Name ? $user->User_Email : $user->User_Name;
      $amountFee = $checkMoneyOrder->Money_1VPN_Fee;
      $rate_usdtvnd = $checkMoneyOrder->Money_1VPN_Rate_USDTVND;
		
      if($result_code == 'success'){
	      $amountUSD = $amount / $rate_usdtvnd;
	      $amountUSDFee = $amountFee / $rate_usdtvnd;
        $arrayInsert = array(
          'Money_User' => $user->User_ID,
          'Money_USDT' => $amountUSD,
          'Money_USDTFee' => -$amountUSDFee,
          'Money_Time' => time(),
          'Money_Comment' => "Complete payment successfully ID order: $checkMoneyOrder->Money_1VPN_ID",
          'Money_MoneyAction' => 1,
          'Money_MoneyStatus' => 1,
          'Money_Currency' => 3,
          'Money_CurrentAmount' => ($amountUSD - $amountUSDFee),
          'Money_CurrencyFrom' => 21,
          'Money_CurrencyTo' => 3,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 1,
          'Money_TXID' => $order_no
        );
        $id = Money::insertGetId($arrayInsert);

        $checkMoneyOrder->Money_1VPN_Status = 1;
        $checkMoneyOrder->save();

        $message = "$username (Wallet ID Deposit: $id) Complete payment successfully ID order: $checkMoneyOrder->Money_1VPN_ID\n"
          . "<b>User ID: </b> "
          . "$user->User_ID\n"
          . "<b>Username or Email: </b> "
          . "$username\n"
          . "<b>Channel: </b> "
          . "$channel\n"
          . "<b>Amount VNĐ: </b> "
          . number_format($amount) . " VNĐ\n"
          . "<b>Amount USDT: </b> "
          . number_format(($amount / $rate_usdtvnd),4) . ' USDT' . "\n"
          . "<b>Rate USDTVND: </b> "
          . number_format($rate_usdtvnd)." \n"
          . "<b>Complete payment successfully time: </b>\n"
          . date('d-m-Y H:i:s', time());

        dispatch(new SendTelegramJobs($message, -485635858));
        return $this->response(200, [], "Complete payment successfully", [], true);
      }else{
        $checkMoneyOrder->Money_1VPN_Status = -1;
        $checkMoneyOrder->save();
        $message = "$username Payment failed ID order: $checkMoneyOrder->Money_1VPN_ID\n"
          . "<b>User ID: </b> "
          . "$user->User_ID\n"
          . "<b>Username or Email: </b> "
          . "$username\n"
          . "<b>Channel: </b> "
          . "$channel\n"
          . "<b>Amount VNĐ: </b> "
          . number_format($amount) . " VNĐ\n"
          . "<b>Amount USDT: </b> "
          . number_format(($amount / $rate_usdtvnd),4) . ' USDT' . "\n"
          . "<b>Rate USDTVND: </b> "
          . number_format($rate_usdtvnd)." \n"
          . "<b>Payment failed time: </b>\n"
          . date('d-m-Y H:i:s', time());

        dispatch(new SendTelegramJobs($message, -485635858));
        return $this->response(200, [], "Payment failed", [], false);
      }
    }
    return $this->response(200, [], "Transaction code does not exist", [], false);

  }

  public function postOrderDeposit(Request $req){
    /*
    * Test/trải nghiệm chức năng nạp
    */
    $validator = Validator::make($req->all(), [
      'amount' => 'required|numeric|min:3|nullable',
      //'otp' => 'required',
      'channel' => 'required|exists:money_channel_1vnp,money_channel_code',
    ]);
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $channel = $req->channel;
    if($channel == "bank_transfer" || $channel == "bank_qr"){
      $validator = Validator::make($req->all(), [
        'bank_code' => 'required|exists:bank,bank_code',
      ]);
      if ($validator->fails()) {
        foreach ($validator->errors()->all() as $value) {
          return $this->response(200, [], $value, $validator->errors(), false);
        }
      }
    }


    $user = $req->user();

    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    //check OTP
    /*$tokenOTP = $user->otp_order_deposit;
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
    if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] != $req->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);*/


    $amount = $req->amount;
    $coin = 21; //VNĐ

    $coinArr = DB::table('currency')->whereIn('Currency_ID', [3,21])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    if (!isset($coinArr[$coin])) {
      return $this->response(200, [], trans('notification.Coin_invalid'), [], false);
    }

    $symbol_vnd = $coinArr[$coin];
    $rate_usdtvnd = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol_vnd)['BUY'];

    $symbol_usdt = $coinArr[3];
    $rate_vndusdt = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol_usdt);


    $getChannel = DB::table("money_channel_1vnp")->where("money_channel_code",$channel)->where("money_channel_status",1)->where("money_channel_deposit",1)->first();
    if (!$getChannel) {
      return $this->response(200, [], "Channel invalid", [], false);
    }

    $cmt = "Order deposit ".number_format($amount)." VNĐ";
    // lưu lịch sử
    $merchant_no = $this->merchant;
    $amount = $amount;
    $order_no = $this->generateHashPayment($user->User_ID);
    $channel = $req->channel;

    $bank_code = $req->bank_code;
    $bank_number =$req->bank_number;

    $notify_url = "https://".$_SERVER['SERVER_NAME'].'/api/v1/1vnp/callback-notify-order';
    $sign = md5($merchant_no."|".$order_no."|".$amount."|".$channel."|".$this->apikey);
    $url = $this->domainGateVND.'createOrder';
    $extra_param = $order_no;

    $arrdata = [
      "merchant_no" => $merchant_no,
      "order_no"=> $order_no,
      "amount"=> $amount,
      "channel"=> $channel,
      "bank_code"=> $bank_code,
      "bank_number"=> $bank_number,
      "notify_url"=>$notify_url,
      "sign"=>$sign,
      "extra_param"=>$extra_param
    ];
    $amountFee = $amount * ($getChannel->money_channel_fee_deposit / 100); 
    try{ 
      $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
      $body=json_encode($arrdata);
      $response = $client->request('POST',$url,['body'=>$body]);
      $response =json_decode($response->getBody(), true);
      if($response['code'] == 0 && $response['error'] == 0){
        $data = new Money1VPN();
        $data->Money_1VPN_User = $user->User_ID;
        $data->Money_1VPN_Amount = $amount - $amountFee;
        $data->Money_1VPN_Fee = $amountFee;
        $data->Money_1VPN_Rate_USDTVND = $rate_usdtvnd;
        $data->Money_1VPN_Rate_VNDUSDT = $rate_vndusdt;
        $data->Money_1VPN_Hash = $order_no;
        $data->Money_1VPN_Comment = $cmt;
        $data->Money_1VPN_Time = time();
        $data->Money_1VPN_Currency = 'VNĐ';
        $data->Money_1VPN_Status = 0;
        $data->Money_1VPN_Channel = $channel;
        $data->Money_1VPN_Action = 1;
        $data->Money_1VPN_Bank_Code = $bank_code;
        $data->save();

        $username = !$user->User_Name ? $user->User_Email : $user->User_Name;
        $message = "$username $cmt\n"
          . "<b>User ID: </b> "
          . "$user->User_ID\n"
          . "<b>Username or Email: </b> "
          . "$username\n"
          . "<b>Channel: </b> "
          . "$channel\n"
          . "<b>Amount VNĐ: </b> "
          . number_format($amount) . " VNĐ\n"
          . "<b>Amount USDT: </b> "
          . number_format(($amount / $rate_usdtvnd),4) . ' USDT' . "\n"
          . "<b>Rate USDTVND: </b> "
          . number_format($rate_usdtvnd)." \n"
          . "<b>Submit Order Deposit Time: </b>\n"
          . date('d-m-Y H:i:s', time());

        dispatch(new SendTelegramJobs($message, -485635858));
        LogUser::addLogUser($user->User_ID, "order_deposit_vnd", $cmt . ' ' .$cmt, $req->ip());

        $user->otp_order_deposit = '';
        $user->save();

        return $this->response(200, ['hash'=>$order_no,'amount'=>$amount,'rate_vndusdt'=>$rate_usdtvnd,'url'=>$response['data']], 'You ' . $cmt, [], true);
      }else{
        return $this->response(200, [],  "Error!. ".$response['message'], [], false);
      }
    } catch (\Exception $e) {

      $message = $user->User_ID. " order error ".$e->getMessage()."\n"
        . "<b>Project: </b>"
        . "123betnow.co\n"
        . "<b>User ID: </b>"
        . "$user->User_ID\n"
        . "<b>Email: </b>"
        . "$user->User_Email\n"
        . "<b>Time: </b>"
        . date('d-m-Y H:i:s',time());

      dispatch(new SendTelegramJobs($message, -398297366));

      return $this->response(200, [],  $e->getMessage(), [], false);
    }
  }
  public function generateHashPayment($userid){

    $hash = $userid . time();
    $checkHash = Money1VPN::where('Money_1VPN_Hash', $hash)->first();
    if (!$checkHash) {
      return $hash;
    } else {
      return $this->generateHashPayment($userid);
    }
  }
}
