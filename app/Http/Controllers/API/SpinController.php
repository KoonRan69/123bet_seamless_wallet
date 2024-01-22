<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\User;
use App\Model\Money;
use App\Model\Log;
use App\Model\GameBet;
use Auth, Validator;

class SpinController extends Controller
{
  public $price = 10;
  public $listReward;

  public function __construct()
  {
    $this->middleware('captchav3', ['only' => ['postBuyVoucher', 'postSpin']]);
    $this->middleware('auth:api', ['except' => ['getVoucherList']]);
  }

  public function getInfoSpin(Request $req)
  {
    $user = Auth::user();
    $listMission = DB::table('mission')->where('status', 1)->select('id', 'name', 'step', 'status', 'description', 'icon', 'unit', 'expired')->get();
    $TimeFrom = date('Y-m-d 00:00:00', strtotime('-1 day'));
    $TimeTo = date('Y-m-d 23:59:59', strtotime('-1 day'));
    $totalTr = GameBet::getTotalBetSpin($user->User_ID, $TimeFrom, $TimeTo);
    $totalTrade = 0;
    if ($totalTr) {
      $totalTrade = $totalTr->totalBet;
    }
    $voucherReward = 0;
    foreach ($listMission as $mission) {
      $stepMission = $this->checkMission($user->User_ID, $mission->id);
      //$stepMission = $checkMission['step'];
      //$voucherReward += $checkMission['voucher'];
      if ($mission->id == 1) {
        $mission->current = $totalTrade * 1;
        if ($totalTrade < $mission->step) {
          $mission->status = 0;
        }
      } else {
        if ($stepMission < $mission->step) {
          $mission->status = 0;
        }
        $mission->current = $stepMission;
      }
      $mission->end = $mission->step;
      $mission->name = $mission->name;
      if ($mission->id == 3) {
        $mission->unit = $mission->unit;
      }
      $mission->description = $mission->description;
    }
    $priceTicket = $this->price;
    //$listReward = $this->listReward;
    $listReward = Log::listReward();
    $balance['Main'] = User::getBalance($user->User_ID);
    $balance['Ticket'] = User::getBalanceTicket($user->User_ID);
    $balance['Voucher'] = User::getBalanceVoucher($user->User_ID);

    $action = 16;
    $currency = 17;
    $TimeFromWeek = strtotime('monday this week');
    $TimeToWeek = strtotime('monday next week');

    $totalTr = GameBet::getTotalBetSpin($user->User_ID, date('Y-m-d 00:00:00', $TimeFromWeek), date('Y-m-d 00:00:00', $TimeToWeek));
    $totalTradeWeek = 0;
    if ($totalTr) {
      $totalTradeWeek = $totalTr->totalBet;
    }
    $withdrawn = Money::where('Money_User', $user->User_ID)->where('Money_MoneyAction', $action)->where('Money_Currency', $currency)->where('Money_MoneyStatus', 1)->where('Money_Time', '>=', $TimeFromWeek)->where('Money_Time', '<', $TimeToWeek)->sum('Money_USDT');

    $withdrawn = abs($withdrawn);
    $maxWithdraw = $totalTradeWeek / 100;
    $countTheSpins = DB::table('voucherList')->where('User_ID', $user->User_ID)->get()->count();
    return $this->response(200, ['list_mission' => $listMission, 'list_reward' => $listReward, 'price_ticket' => $priceTicket, 'total_trade' => $totalTrade, 'balance' => $balance, 'total_trade_week' => $totalTradeWeek, 'max_withdraw' => $maxWithdraw, 'withdraw_this_week' => $withdrawn, 'remaining_withdraw' => ($maxWithdraw - $withdrawn), 'count_the_spins' => $countTheSpins]);
  }

  public function checkMission($User_ID, $missionId, $TimeFrom = null, $TimeTo = null, $timeCheckInsert = null, $timeInsert = null)
  {
    if(!$timeCheckInsert || !$timeInsert){
      $timeCheckInsert = date('Y-m-d');
      $timeInsert = date('Y-m-d H:i:s');
    }
    if ($missionId == 1) {
      $checkMission = DB::table('mission_success')->where('userId', $User_ID)->where('mission_id', $missionId)->where('status', 1)->whereDate('created_at', $timeCheckInsert)->first();
      if ($checkMission) {
        return 1;
        //$checkMission = DB::table('mission_success')->where('id', $idMissionUser)->first();
      }
      if(!$TimeFrom || !$TimeTo){
        $TimeFrom = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $TimeTo = date('Y-m-d 23:59:59', strtotime('-1 day'));
      }

      $totalTr = GameBet::getTotalBetSpin($User_ID, $TimeFrom, $TimeTo);
      $totalTradeToday = 0;
      if ($totalTr) {
        $totalTradeToday = $totalTr->totalBet;
      }
      //dd($totalTradeToday, 123);
      if ($totalTradeToday >= 300) {
        $updateMission = DB::table('mission_success')->insertGetId(['userId' => $User_ID, 'mission_id' => $missionId, 'created_at' => $timeCheckInsert, 'status' => 1]);
        //dd($updateMission);
        if ($updateMission) {
          //trade >=200 nhận vé
          $insertVoucher = DB::table('voucher')->insert(['User_ID' => $User_ID, 'status' => 0, 'type' => 'mission', 'mission_id' => $missionId, 'datetime' => $timeInsert]);
          //dd($insertVoucher);
          $checkMissionWeek = $this->checkMission($User_ID, 2);//điểm danh đủ 7 ngày nhận vé
          if ($checkMissionWeek >= 7) {
            $insertVoucher = DB::table('voucher')->insert(['User_ID' => $User_ID, 'status' => 0, 'type' => 'mission', 'mission_id' => 2, 'datetime' => $timeInsert]);
          }
        }
        return 1;
      }
    }
    if ($missionId == 2) {
      $missionId = 1;
      $fromDate = date('Y-m-d', strtotime('monday this week'));
      $toDate = date('Y-m-d', strtotime('sunday this week'));
      $checkMissionWeek = DB::table('mission_success')
        ->where('userId', $User_ID)
        ->where('mission_id', $missionId)
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('status', 1)
        ->select('id')->get()->count();
      return $checkMissionWeek;
    }
    if ($missionId == 3) {
      $fromDateWeek = strtotime('monday this week');
      $toDateWeek = strtotime('monday next week');
      $countTotalTradeThisWeek = DB::table('statistical_123betnow')->leftJoin('users', 'users.User_ID', 'statistical_123betnow.statistical_User')->where('users.User_Parent', $User_ID)
        ->where('users.User_RegisteredDatetime', '>=', $fromDateWeek)
        ->where('users.User_RegisteredDatetime', '<', $toDateWeek)
        ->where('statistical_123betnow.statistical_Time', '>=', $fromDateWeek)
        ->where('statistical_123betnow.statistical_Time', '<', $toDateWeek)
        ->groupBy('statistical_123betnow.statistical_User')->selectRaw('SUM(statistical_123betnow.statistical_TotalBet) as totalBet, statistical_123betnow.statistical_User')
        ->havingRaw('totalBet >= 200')
        ->limit(11)->get();
      //dd($countTotalTradeThisWeek);
      if (count($countTotalTradeThisWeek) >= 10) {
        $checkMission = DB::table('mission_success')
          ->where('userId', $User_ID)
          ->where('mission_id', $missionId)
          ->whereDate('created_at', '>=', $fromDateWeek)
          ->whereDate('created_at', '<=', $toDateWeek)
          ->where('status', 1)
          ->select('id')->first();
        if (!$checkMission) {
          //nhận vé khi có 10f1 trade >=200 trong 7 ngày
          $idMissionUser = DB::table('mission_success')->insertGetId(['userId' => $User_ID, 'mission_id' => $missionId, 'created_at' => $timeCheckInsert, 'status' => 1]);
          $insertVoucher = DB::table('voucher')->insert(['User_ID' => $User_ID, 'status' => 0, 'type' => 'mission', 'mission_id' => $missionId, 'datetime' => $timeInsert]);
        }
      }
      return count($countTotalTradeThisWeek);
    }
    return 0;
  }

  public function getVoucherList($show = 0)
  {
    if ($show == 0) {
      $data = [
        ['quantity' => 1500, 'amount' => 3, 'type' => 0],
        ['quantity' => 200, 'amount' => 5, 'type' => 0],
        ['quantity' => 20, 'amount' => 20, 'type' => 0],
        ['quantity' => 10, 'amount' => 50, 'type' => 0],
        ['quantity' => 5, 'amount' => 100, 'type' => 0],
        ['quantity' => 2, 'amount' => 200, 'type' => 0],
        ['quantity' => 0, 'amount' => 500, 'type' => 0],
        ['quantity' => 500, 'amount' => 0, 'type' => 1],//thêm lượt mới
        ['quantity' => 1800, 'amount' => 0, 'type' => 0],//chúc may mắn
      ];
    } else {
      $data = [
        ['quantity' => 500, 'amount' => 3, 'type' => 0],
        ['quantity' => 400, 'amount' => 5, 'type' => 0],
        ['quantity' => 300, 'amount' => 20, 'type' => 0],
        ['quantity' => 200, 'amount' => 50, 'type' => 0],
        ['quantity' => 150, 'amount' => 100, 'type' => 0],
        ['quantity' => 150, 'amount' => 200, 'type' => 0],
        ['quantity' => 100, 'amount' => 500, 'type' => 0],
        ['quantity' => 100, 'amount' => 1000, 'type' => 0],
        ['quantity' => 50, 'amount' => 5555, 'type' => 0],
        ['quantity' => 50, 'amount' => 7777, 'type' => 0],
        ['quantity' => 500, 'amount' => 0, 'type' => 1],//thêm lượt mới
        ['quantity' => 1000, 'amount' => 0, 'type' => 0],//chúc may mắn
      ];
    }
    //2$
    $dataInsert = [];
    $datetime = date('Y-m-d H:i:s');
    foreach ($data as $d) {
      for ($i = 0; $i < $d['quantity']; $i++) {
        $dataInsert[] = ['User_ID' => 0, 'amount' => $d['amount'], 'type' => $d['type'], 'created_at' => $datetime, 'updated_at' => $datetime, 'show' => $show];
      }
    }
    foreach (array_chunk($dataInsert, 1000) as $t) {
      DB::table('voucherList')->insert($t);
    }
    return true;
  }

  public function postBuyVoucher(Request $req)
  {
    $user = Auth::user();
    $quantity = (int)$req->quantity;
    $price = $this->price;
    if (!$quantity || $quantity <= 1) {
      $quantity = 1;
    }
    $currency = 3;
    $amount = $price * $quantity;
    $balance = User::getBalance($user->User_ID);
    if ($amount > $balance) {
      return $this->response(200, [], trans("notification.Your_balance_is_insufficient"), [], false);
    }
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -(float)($amount * 1),
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Buy ' . $quantity . ' Ticket',
      'Money_MoneyAction' => 14,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currency,
      'Money_CurrentAmount' => (float)($amount * 1),
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
    );
    $insert = Money::insert($arrayInsert);
    if ($insert) {
      $updateBalance = User::getBalance($user->User_ID, $currency);
    } else {
      return $this->response(200, [], trans('notification.Error_Please_try_again'), [], false);
    }
    $dataInsertVoucher = [];
    for ($i = 1; $i <= $quantity; $i++) {
      $dataInsertVoucher[] = ['User_ID' => $user->User_ID, 'status' => 0, 'type' => 'buy', 'datetime' => date('Y-m-d H:i:s')];
    }
    DB::table('voucher')->insert($dataInsertVoucher);
    $mainBalance = User::getBalance($user->User_ID);
    //$tradeBalance = User::getTradeBalance($user->User_ID);
    $ticketBalance = User::getBalanceTicket($user->User_ID);
    $voucherBalance = User::getBalanceVoucher($user->User_ID);
    //UserLog::SetUserLog('Withdraw $'.$request->amount.' from trade balance', date('Y-m-d H:i:s'), Auth::user()->User_ID, $request->ip(), $request->header('User-Agent'));
    return $this->response(200, ['mainBalance' => (float)$mainBalance * 1, 'ticketBalance' => $ticketBalance * 1, 'voucherBalance' => $voucherBalance * 1], trans('notification.BUY_TICKET_SUCCESS'));
  }

  public function postWithdrawVoucher(Request $req)
  {
    $validator = Validator::make($req->all(), [
      'amount' => 'required|numeric|min:1',
    ],[
      'amount.required' => trans('notification.amount_required') ,
      'amount.min' => trans('notification.minimum_amount_1') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = $req->user();
    $amount = $req->amount;
    $voucherBalance = User::getBalanceVoucher($user->User_ID);
    if ($amount > $voucherBalance) {
      return $this->response(200, [], trans("notification.Your_balance_is_insufficient"), [], false);
    }
    $action = 16;
    $currency = 17;
    $TimeFrom = strtotime('monday this week');
    $TimeTo = strtotime('monday next week');
    $totalTr = GameBet::getTotalBetSpin($user->User_ID, date('Y-m-d 00:00:00', $TimeFrom), date('Y-m-d 00:00:00', $TimeTo));
    $totalTrade = 0;
    if ($totalTr) {
      $totalTrade = $totalTr->totalBet;
    }

    $withdrawn = Money::where('Money_User', $user->User_ID)->where('Money_MoneyAction', $action)->where('Money_Currency', $currency)->where('Money_MoneyStatus', 1)->where('Money_Time', '>=', $TimeFrom)->where('Money_Time', '<', $TimeTo)->sum('Money_USDT');
    $withdrawn = abs($withdrawn);
    $maxWithdraw = $totalTrade / 100;
    //dd($withdrawn, $maxWithdraw, $amount, $totalTrade, $maxWithdraw < ($withdrawn + $amount));
    if ($maxWithdraw < ($withdrawn + $amount)) {
      //return $this->response(200, [], trans('notification.Maximum_withdrawal_from_Voucher_Balance_is_$') . $maxWithdraw, [], false);
    }
    $arrayInsert[] = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -(float)($amount * 1),
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Withdraw $' . $amount . ' From Voucher Balance',
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currency,
      'Money_CurrentAmount' => (float)($amount * 1),
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
    );
    $arrayInsert[] = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => (float)($amount * 1),
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Withdraw $' . $amount . ' From Voucher Balance',
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => 3,
      'Money_CurrentAmount' => (float)($amount * 1),
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
    );
    $insert = Money::insert($arrayInsert);
    if ($insert) {
      $updateBalance = User::getBalance($user->User_ID, $currency);
      $balance = User::updateVoucherBalance($user->User_ID, -(float)($amount * 1), 17);
    } else {
      return $this->response(200, [], trans('notification.Error_Please_try_again'), [], false);
    }

    $mainBalance = User::getBalance($user->User_ID);
    //$tradeBalance = User::getTradeBalance($user->User_ID);
    $ticketBalance = User::getBalanceTicket($user->User_ID);
    $voucherBalance = User::getBalanceVoucher($user->User_ID);
    //UserLog::SetUserLog('Withdraw $'.$request->amount.' from trade balance', date('Y-m-d H:i:s'), Auth::user()->User_ID, $request->ip(), $request->header('User-Agent'));
    return $this->response(200, ['mainBalance' => (float)$mainBalance * 1, 'ticketBalance' => $ticketBalance * 1, 'voucherBalance' => $voucherBalance * 1], 'WITHDRAW VOUCHER BALANCE SUCCESS!');
  }

  public function postSpin(Request $req)
  {
    $user = Auth::user();
    $quantity = $req->quantity ?? 1;
    $quantity = (int)$quantity;
    if ($quantity <= 0) {
      return $this->response(200, [], trans('notification.Error_Please_chose_quantity_ticket'), [], false);
    }
    if ($quantity > 500) {
      $quantity = 500;
    }
    $voucher = DB::table('voucher')->where('User_ID', $user->User_ID)->where('status', 0)->orderBy('datetime')->limit($quantity)->pluck('id')->toArray();
    //        dd($voucher);
    if (!count($voucher) || count($voucher) < $quantity) {
      return $this->response(200, [], trans('notification.Error_Please_chose_quantity_ticket'), [], false);
    }
    $listReward = array_flip(Log::listReward());
    //$listReward = $this->listReward;
    //dd($getReward, array_flip($this->listReward) );
    $updateVoucher = DB::table('voucher')->whereIn('id', $voucher)->update(['status' => 1, 'used_datetime' => date('Y-m-d H:i:s')]);
    $listRewardUser = [];
    $ticket = 0;
    $amount = 0;
    $idReward = [];
    $idFirst = null;
    $countVoucher = count($voucher);
    $show = 0;
    if ($user->User_Level != 0) {
      $show = 1;
    }
    $getRewardList = DB::Table('voucherList')->where('User_ID', 0)->where('show', $show)->whereNotIn('id', $idReward)->inRandomOrder()->limit($countVoucher)->get();
    if (count($getRewardList) < $countVoucher) {
      $insert = $this->getVoucherList($show);
      $getRewardList = DB::Table('voucherList')->where('User_ID', 0)->where('show', $show)->whereNotIn('id', $idReward)->inRandomOrder()->limit($countVoucher)->get();
    }
    $arrayBot = [

    ];
    $arrayNotice = [];
    foreach ($getRewardList as $keyTicketID=>$getReward) {
      $amountBonus = 0;
      $countVoucherBuy = DB::Table('voucher')->where('User_ID', $user->User_ID)->whereIn('status', [0, 1])->where('type', 'buy')->select('id')->get()->count();
      if ($countVoucherBuy >= 5000) {
        $intVoucherThousand = floor($countVoucherBuy / 5000);
        $quantity15000 = floor($intVoucherThousand / 3);
        $quantity10000 = floor($intVoucherThousand / 2);
        $quantity5000 = $intVoucherThousand - $quantity10000;
        //dd($intVoucherThousand, $quantity2000, $quantity1000);
        if ($quantity10000 > 0) {
          $getReceive10000 = DB::Table('voucherList')->where('User_ID', $user->User_ID)->where('amount', 5555)->select('id')->get()->count();
          if ($quantity10000 > $getReceive10000) {
            $randResult = rand(1, 5);
            if ($randResult == 2) {
              $amountBonus = 5555;
            }
          }
        }
        if ($quantity5000 > 0) {
          $getReceive5000 = DB::Table('voucherList')->where('User_ID', $user->User_ID)->where('amount', 1000)->select('id')->get()->count();
          //dd($quantity1000, $getReceive1000);
          if ($quantity5000 > $getReceive5000) {
            $randResult = rand(1, 5);
            if ($randResult == 1) {
              $amountBonus = 1000;
            }
          }
        }
        if ($quantity15000 > 0) {
          $getReceive15000 = DB::Table('voucherList')->where('User_ID', $user->User_ID)->where('amount', 7777)->select('id')->get()->count();
          if ($quantity15000 > $getReceive15000) {
            $randResult = rand(1, 5);
            if ($randResult == 2) {
              $amountBonus = 7777;
            }
          }
        }
      }
      if (isset($arrayBot[$user->User_ID])) {
        $amountBonus = $arrayBot[$user->User_ID];
      }
      if ($amountBonus > 0) {
        $listRewardUser[] = $amountBonus;//.'$'
        if ($idFirst == null) {
          $idFirst = $listReward[$amountBonus];//.'$'
        }
        $amount += $amountBonus;
        $dataInsert = ['User_ID' => $user->User_ID, 'amount' => $amountBonus, 'type' => 0, 'ticket_id' => $voucher[$keyTicketID], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
        DB::table('voucherList')->insert($dataInsert);
        continue;
      }
      $idReward[] = $getReward->id;

      if ($getReward->type != 0) {
        if ($getReward->type == 1) {
          $ticket++;
          $reward = "Ticket";
          $rewardNotice = 'Ticket';
          if ($idFirst == null) {
            $idFirst = $listReward[$reward];
          }
        }
      } else {
        if ($getReward->amount == 0) {
          $reward = "Good Luck";
          if ($idFirst == null) {
            $idFirst = $listReward[$reward];
          }
        } else {
          $amount += $getReward->amount * 1;
          $reward = ($getReward->amount * 1);//.'$'
          $rewardNotice = ($getReward->amount * 1);//.'$'
          if ($idFirst == null) {
            $idFirst = $listReward[$reward];
          }
        }
      }
      if ($getReward->amount != 0) {
        $arrayNotice[] = $rewardNotice;
      }
      $listRewardUser[] = $reward;
      $updateTicketID = DB::Table('voucherList')->where('id', $getReward->id)->update(['ticket_id' => $voucher[$keyTicketID]]);
      //            dd($updateTicketID);
    }
    $currency = 17;
    if ($amount > 0) {
      $arrayInsert = array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => (float)($amount * 1),
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Get Rewarded ' . $amount . ' USDT From Ticket',
        'Money_MoneyAction' => 15,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $currency,
        'Money_CurrentAmount' => (float)($amount * 1),
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1,
      );
      $insert = Money::insert($arrayInsert);
      if ($insert) {
        $balance = User::updateVoucherBalance($user->User_ID, (float)($amount * 1), 17);
      } else {
        return $this->response(200, [], trans('notification.Error_Please_try_again'), [], false);
      }
    }
    $updateUserReward = DB::Table('voucherList')->where('User_ID', 0)->whereIn('id', $idReward)->update(['User_ID' => $user->User_ID, 'updated_at' => date('Y-m-d H:i:s')]);
    $insertVoucher = [];
    for ($i = 1; $i <= $ticket; $i++) {
      $insertVoucher[] = ['User_ID' => $user->User_ID, 'status' => 0, 'type' => 'spin', 'datetime' => date('Y-m-d H:i:s')];
    }
    DB::table('voucher')->insert($insertVoucher);
    $mainBalance = User::getBalance($user->User_ID);
    //$tradeBalance = User::getTradeBalance($user->User_ID);
    $ticketBalance = User::getBalanceTicket($user->User_ID);
    $voucherBalance = User::getBalanceVoucher($user->User_ID);
    //UserLog::SetUserLog('Withdraw $'.$request->amount.' from trade balance', date('Y-m-d H:i:s'), Auth::user()->User_ID, $request->ip(), $request->header('User-Agent'));
    //dd($listReward, $listRewardUser, count($listRewardUser)-1);
    return $this->response(200, [
      'reward' => ['id' => $idFirst/*$listReward[$listRewardUser[count($listRewardUser)-1]]*/, 'name' => $listRewardUser],
      'mainBalance' => (float)$mainBalance * 1,
      'ticketBalance' => $ticketBalance * 1,
      'voucherBalance' => $voucherBalance * 1,
    ]);
  }
}
