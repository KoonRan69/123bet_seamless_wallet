<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Profile;
use App\Model\GoogleAuth;
use App\Model\LogUser;
use App\Model\Log;
use App\Model\User;
use App\Model\userBalance;
use App\Jobs\SendTelegramJobs;
use App\Model\Money;
use App\Model\logMoney;
use App\Model\Money1VPN;
use Illuminate\Support\Facades\Http;
use Validator;
use DB;
use Illuminate\Support\Facades\Input;

class FiatController extends Controller
{

  public $difference_fee;
  public $merchant;
  public $apikey;
  public $domainGateVND;
  public function __construct()
  {
    $this->merchant = "vnpay103135";
    $this->apikey = "fcce2d2e12704f78f55703ee3323e04f";
    $this->domainGateVND = "https://pay.1vnpay.org/api/"; //Test
    $this->difference_fee = 0; //vnđ
  }

  public function returnResultWithdraw(Request $req){
    $order_no = $req->order_no;
    $merchant_no = $req->merchant_no;
    $ylt_order_no = $req->ylt_order_no;
    $amount = $req->amount;
    $sign = $req->sign;
    $status = $req->result_code;

    if($this->merchant != $merchant_no){
      return $this->response(200, [], "Error!", [], false);
    }

    $checkMoneyOrder = Money1VPN::where('Money_1VPN_Hash',$order_no)->where('Money_1VPN_Channel',$channel)->where('Money_1VPN_Action',2)->first();
    if($checkMoneyOrder){
      if($checkMoneyOrder->Money_1VPN_Status == 1){
        return $this->response(200, [], "Order confirmed", [], false);
      }
      $channel = $checkMoneyOrder->Money_1VPN_Channel;

      $user = User::find($checkMoneyOrder->Money_1VPN_User);
      $username = !$user->User_Name ? $user->User_Email : $user->User_Name;
      $rate_vndusdt = $checkMoneyOrder->Money_1VPN_Rate_VNDUSDT;

      if($status == 'success'){

        $checkWalletWithdraw = Money::where("Money_ID",$checkMoneyOrder->Money_1VPN_MoneyID)->where("Money_MoneyAction",2)->first();
        if($checkWalletWithdraw){
          $checkWalletWithdraw->Money_MoneyStatus = 1;
          $checkWalletWithdraw->Money_Confirm = 1;
          $checkWalletWithdraw->save();
        }

        $checkMoneyOrder->Money_1VPN_Status = 1;
        $checkMoneyOrder->save();

        $message = "$username (Wallet ID withdraw: $checkWalletWithdraw->Money_ID) Complete payment successfully ID order: $checkMoneyOrder->Money_1VPN_ID\n"
          . "<b>User ID: </b> "
          . "$user->User_ID\n"
          . "<b>Username or Email: </b> "
          . "$username\n"
          . "<b>Channel: </b> "
          . "$channel\n"
          . "<b>Amount VNĐ: </b> "
          . number_format($amount) . " VNĐ\n"
          . "<b>Amount USDT: </b> "
          . number_format(($amount / $rate_vndusdt),4) . ' USDT' . "\n"
          . "<b>Rate VNĐUSDT: </b> "
          . number_format($rate_vndusdt)." \n"
          . "<b>Complete payment successfully time: </b>\n"
          . date('d-m-Y H:i:s', time());

        dispatch(new SendTelegramJobs($message, -485635858));
        return $this->response(200, [], "Withdraw complete payment successfully", [], true);
      }else{
        $checkWalletWithdraw = Money::where("Money_ID",$checkMoneyOrder->Money_1VPN_MoneyID)->where("Money_MoneyAction",2)->first();
        if($checkWalletWithdraw){
          $checkWalletWithdraw->Money_MoneyStatus = -1;
          $checkWalletWithdraw->Money_Confirm = -1;
          $checkWalletWithdraw->save();
        }

        $checkMoneyOrder->Money_1VPN_Status = -1;
        $checkMoneyOrder->save();
        $message = "$username (Wallet ID withdraw: $checkWalletWithdraw->Money_ID) Withdraw Payment failed ID order: $checkMoneyOrder->Money_1VPN_ID\n"
          . "<b>User ID: </b> "
          . "$user->User_ID\n"
          . "<b>Username or Email: </b> "
          . "$username\n"
          . "<b>Channel: </b> "
          . "$channel\n"
          . "<b>Amount VNĐ: </b> "
          . number_format($amount) . " VNĐ\n"
          . "<b>Amount USDT: </b> "
          . number_format(($amount / $rate_vndusdt),4) . ' USDT' . "\n"
          . "<b>Rate USDTVND: </b> "
          . number_format($rate_vndusdt)." \n"
          . "<b>Payment failed time: </b>\n"
          . date('d-m-Y H:i:s', time());

        dispatch(new SendTelegramJobs($message, -485635858));
        return $this->response(200, [], "Withdraw payment failed", [], false);
      }
    }
    return $this->response(200, [], "Transaction code does not exist", [], false);
  }

  public function getDetailFiat(Request $req){
    $detail = Money1VPN::join('users','User_ID','Money_1VPN_User')
      ->join('moneyaction','MoneyAction_ID','Money_1VPN_Action')
      ->join('money_channel_1vnp','money_channel_code','Money_1VPN_Channel')
      ->join('currency','Currency_Symbol','Money_1VPN_Currency')
      ->leftjoin('bank','bank_code','Money_1VPN_Bank_Code')
      ->where('Money_1VPN_ID',$req->id)->orderByDesc("Money_1VPN_Time")->first();

    if ($req->status) {
      if (Session('user')->User_Level != 1 && Session('user')->User_Level != 2) {
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'There are no permissions to use this feature']);
      }
      if ($req->status == -1) {
        $checkConfirm = Money1VPN::where('Money_1VPN_ID',$req->id)->first();
        //ghi log
        $cmt_log = "Cancel Money Order 1VNP ID: $checkConfirm->Money_1VPN_ID";
        Log::insertLog(Session('user')->User_ID, "Cancel Money Order 1VNP", 0, $cmt_log);
        if (!$checkConfirm && $checkConfirm->Money_1VPN_Status != 0) {
          return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
        }
        $checkConfirm->Money_1VPN_Status = -1;
        $checkConfirm->save();

        $checkMoney = Money::where("Money_ID",$checkConfirm->Money_1VPN_MoneyID)->first();
        if($checkMoney){
          $checkMoney->Money_Confirm = -1;
          $checkMoney->Money_MoneyStatus = -1;
          $checkMoney->save();
        }
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Cancel Success!']);
      } elseif ($req->status == 2) {
        if (!Input::get('txid')) {
          return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Please enter Transaction hash to success']);
        }
        $checkConfirm = Money1VPN::where('Money_1VPN_ID',$req->id)->first();
        $userClient = User::find($checkConfirm->Money_1VPN_User);
        if($checkConfirm->Money_1VPN_Action == 2){
          //Chức năng xác nhận rút thành công - call API qua 1VNP khi admin duyệt rút

          $merchant_no = $this->merchant;
          $order_no = $checkConfirm->Money_1VPN_Hash;
          $amount = round(abs($checkConfirm->Money_1VPN_Amount), 0);
          $channel = $checkConfirm->Money_1VPN_Channel;
          $notify_url = "https://".$_SERVER['SERVER_NAME'].'/1vnp/callback-notify-order-withdraw';

          $bank_code = $checkConfirm->Money_1VPN_Bank_Code;
          $bank_number = $checkConfirm->Money_1VPN_Bank_Number;

          $sign = md5($merchant_no."|".$order_no."|".$amount."|".$channel."|".$this->apikey);
          $url = $this->domainGateVND.'v2/payOut';

          $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
          $response = $client->request('GET', $url, [
            'query' => [
              "merchant_no" => $merchant_no,
              "order_no"=> $order_no,
              "amount"=> $amount,
              "channel"=> $channel,
              "bank_code"=> $bank_code,
              "bank_number"=> $bank_number,
              "notify_url"=>$notify_url,
              "sign"=>$sign,
            ],
          ]);
          $response =json_decode($response->getBody(), true);

          if($response['status'] == 0){
            Log::insertLog(Session('user')->User_ID, "Success Money Order 1VNP", 0, 'Success Money ID: ' . $checkConfirm->Money_1VPN_ID);
            $checkConfirm->Money_1VPN_Status = 1;
            $checkConfirm->save();

            $checkMoney = Money::where("Money_ID",$checkConfirm->Money_1VPN_MoneyID)->first();
            if($checkMoney){
              $checkMoney->Money_Confirm = 1;
              $checkMoney->Money_MoneyStatus = 1;
              $checkMoney->save();
            }
            // gọi jobs

            $username = !$userClient->User_Name ? $userClient->User_Email : $userClient->User_Name;
            $message = "Admin Confirm Withdraw (Wallet ID: $checkMoney->Money_ID - Fiat ID: $checkConfirm->Money_1VPN_ID) VNĐ: ".$amount." $checkConfirm->Money_1VPN_Currency\n"
              . "<b>User ID: </b> "
              . "$userClient->User_ID\n"
              . "<b>Username or Email: </b> "
              . "$username\n"
              . "<b>Amount USD: </b> "
              . number_format($checkMoney->Money_USDT) . " USD\n"
              . "<b>Amount VNĐ: </b> "
              . number_format($amount) . ' VNĐ' . "\n"
              . "<b>Submit withdraw Time: </b>\n"
              . date('d-m-Y H:i:s', time());

            dispatch(new SendTelegramJobs($message, -448649753));

          }else{
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Error!. ".$response['message']]);
          }

          try{ 


          } catch (\Exception $e) {
            $message = Session('user')->User_ID. " admin confirm order withdraw error ".$e->getMessage()."\n"
              . "<b>Project: </b>"
              . "123betnow.co\n"
              . "<b>User ID: </b>"
              . "$userClient->User_ID\n"
              . "<b>Email: </b>"
              . "$userClient->User_Email\n"
              . "<b>Time: </b>"
              . date('d-m-Y H:i:s',time());

            dispatch(new SendTelegramJobs($message, -398297366));

            return $this->response(200, [],  $e->getMessage(), [], false);
          }
        }else{
          Log::insertLog(Session('user')->User_ID, "Success Money Order 1VNP", 0, 'Success Money ID: ' . $checkConfirm->Money_1VPN_ID);
          $checkConfirm->Money_1VPN_Status = 1;
          $checkConfirm->save();

          $checkMoney = Money::where("Money_ID",$checkConfirm->Money_1VPN_MoneyID)->first();
          if($checkMoney){
            $checkMoney->Money_Confirm = 1;
            $checkMoney->Money_MoneyStatus = 1;
            $checkMoney->save();
          }
        }

        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Only Confirm withdraw success!']);
      }

      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Please contact code!']);

    }

    return view('System.Admin.FiatDetail', compact('detail'));
  }
  public function getListFiat(Request $req){
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
    if ($req->comment) {
      $where .= "AND Money_1VPN_Comment like '%".$req->comment."%'";
    }
    if ($req->action) {
      $where .= ' AND Money_1VPN_Action IN ('.$req->action.')';
    } 
    if ($req->export) {
      $list = Money1VPN::join('users','User_ID','Money_1VPN_User')
        ->join('moneyaction','MoneyAction_ID','Money_1VPN_Action')
        ->join('money_channel_1vnp','money_channel_code','Money_1VPN_Channel')
        ->join('currency','Currency_ID','Money_1VPN_Currency')
        ->join('bank','bank_code','Money_1VPN_Bank_Code')
        ->whereRaw('1 ' . $where)->orderByDesc("Money_1VPN_Time")->get();
      set_time_limit(300);
      ob_end_clean();
      ob_start();
      return Excel::download(new WalletExport($walletList->orderByDesc('Money_ID')->get()), 'WalletExport.xlsx');
    }

    $list = Money1VPN::join('users','User_ID','Money_1VPN_User')
      ->join('moneyaction','MoneyAction_ID','Money_1VPN_Action')
      ->join('money_channel_1vnp','money_channel_code','Money_1VPN_Channel')
      ->join('currency','Currency_Symbol','Money_1VPN_Currency')
      ->leftjoin('bank','bank_code','Money_1VPN_Bank_Code')
      ->whereRaw('1 ' . $where)->orderByDesc("Money_1VPN_Time")->paginate(30);
    return view('System.Admin.Fiat', compact('list'));
  }
}
