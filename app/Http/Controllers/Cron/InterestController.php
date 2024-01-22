<?php

namespace App\Http\Controllers\Cron;

use App\Model\Money;
use App\Model\User;
use App\Model\Wallet;
use App\Model\Log;
use App\Model\Investment;
use App\Model\logMoney;
use App\Model\GameBet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use DB;

use App\Jobs\SendTelegramJobs;
use App\Jobs\CommissionResonanceJobs;
class InterestController extends Controller
{
  public function checkBonusTicket(Request $request){
    $TimeFrom = date('Y-m-d 00:00:00', strtotime('-1 days'));
    $TimeTo = date('Y-m-d 23:59:59', strtotime('-1 days'));
    $timeCheckInsert = date('Y-m-d');
    $timeInsert = date('Y-m-d H:i:s');
    //        $TimeFrom = date('2021-08-07 00:00:00');
    //        $TimeTo = date('2021-08-07 23:59:59');
    //        $timeCheckInsert = '2021-08-08';
    //        $timeInsert = date('2021-08-08 H:i:s');
    $getUserBet = DB::table('history_wm')
      ->where('DateTime', '>=', $TimeFrom)
      ->where('DateTime', '<', $TimeTo)
      ->where('ValidBetReal', '>=', 300)
      ->select('Username')
      ->pluck('Username')
      ->toArray();
    //        dd($getUserBet, $TimeFrom, $TimeTo);
    $listMission = DB::table('mission')->where('status', 1)->select('id', 'name', 'step', 'status', 'description', 'icon', 'unit', 'expired')->get();

    foreach($getUserBet as $userID){
      foreach ($listMission as $mission) {
        $checkMission = app('App\Http\Controllers\API\SpinController')->checkMission($userID, $mission->id, $TimeFrom, $TimeTo, $timeCheckInsert, $timeInsert);
      }
    }
    dd('check pay ticket success');
  }

  public function getResetBalanceBonus(Request $req){
    dd(123);
    $fromBonus = strtotime('2023-01-20 00:00:00');
    $listBonus = Money::join('users', 'User_ID', 'Money_User')
      //->whereIn('User_Level', [1])
      ->select('Money_User', 'User_ID', 'User_Level', 'Money_USDT')
      //->where('Money_MoneyAction', 10)
      //->where('User_ID', 969462)
      //->where('Money_Time', '>', $fromBonus)
      ->where('Money_Currency', 10)
      ->whereIn('Money_MoneyStatus', [0,1])
      ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as Money_USDT')
      ->groupBy('User_ID')
      ->get();
    $insertArray = [];
    foreach($listBonus as $k=>$money){
      $balanceBonus = User::getBalance($money->Money_User, 10);
      //$amount = 0 - $balanceBonus;
      if($balanceBonus <= 0){
        continue;
      }
      $amount = $balanceBonus;
      $insertArray[] = array(
        'Money_User' => $money->Money_User,
        'Money_USDT' => -$amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Reset balance bonus '.($amount*1).' EUSD (balance bonus: '.$balanceBonus.')',
        'Money_MoneyAction' => 78,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 10,
        'Money_CurrentAmount' => $amount,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      );
      echo $money->Money_User.' ('.$money->User_Level.') : Balance:'.$balanceBonus.' EUSD - Reset balance bonus: '.$amount.'<br>';
    }
    if(count($insertArray) && $req->resets == 1){
      Money::insert($insertArray);
    }
  }
  public function getBonusV2(Request $req){
    $fromBonus = strtotime('2021-06-10 00:00:00');
    $toDate = strtotime('2021-07-01 00:00:00');

    $usersBonus = Money::join('users', 'User_ID', 'Money_User')
      //          					->whereIn('User_Level', [1])
      ->select('Money_User', 'User_ID', 'User_Level', 'Money_USDT')
      ->where('Money_MoneyAction', 10)
      //          					->where('Money_MoneyAction', 10)
      ->where('Money_Time', '>=', $fromBonus)
      ->where('Money_Time', '<', $toDate)
      ->where('Money_Currency', 10)
      ->where('Money_MoneyStatus', 1)
      ->get();
    $insertArray = [];
    //dd($usersBonus);
    foreach($usersBonus as $k=>$money){
      $balanceBonus = User::getBalance($money->Money_User, 10);
      if($balanceBonus <= 0){
        continue;
      }
      $totalTradeBonusArr = GameBet::getTotalTradeBonus($money->Money_User);
      $totalTradeBonus = $totalTradeBonusArr->totalBet ?? 0;
      $arrWithdrawCurren = Money::where('Money_User', $money->Money_User)
        ->where('Money_Time', '>=', $fromBonus)
        ->where('Money_Time', '<', $toDate)
        ->where('Money_MoneyAction', 77)
        ->where('Money_MoneyStatus', 1)
        ->where('Money_Currency', 10)
        ->get();
      if(count($arrWithdrawCurren) > 0){
        continue;
      }
      $withdrawCurren = abs($arrWithdrawCurren->sum('Money_USDT'));
      $amountbonus = $money->Money_USDT;
      if($totalTradeBonus < $amountbonus*100){
        continue;
      }
      if($totalTradeBonus >= $amountbonus*100){
        $amountAvailable = $amountbonus;
      }
      //else{
      //if($withdrawCurren > 0 and $balanceBonus < $amountbonus){
      //continue;
      //}
      //$amountAvailable = $amountbonus/2;
      //}
      if($balanceBonus < $amountAvailable){
        $amount = $balanceBonus;
      }else{
        $amount = $amountAvailable;
      }

      if($amount <= 0){
        continue;
      }

      $insertArray[] = array(
        'Money_User' => $money->Money_User,
        'Money_USDT' => -$amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.($amount*1).' EUSD (From Balance Bonus)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 10,
        'Money_CurrentAmount' => $amount,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      );
      $insertArray[] = array(
        'Money_User' => $money->Money_User,
        'Money_USDT' => $amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.($amount*1).' EUSD (To Main Balance)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 3,
        'Money_CurrentAmount' => $amount,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      );
      echo $money->Money_User.' ('.$money->User_Level.') : '.$amount.' EUSD - Bonus: '.$amountbonus.' EUSD - Balance: '.$balanceBonus.' EUSD - Total Trade: '.$totalTradeBonus.'<br>';
    }
    if(count($insertArray) && $req->pay == 1){
      Money::insert($insertArray);
    }
  }
  public function getBonus(Request $req){
    $fromBonus = date('Y-m-d 00:00:00', strtotime('2021-05-12 00:00:00'));
    $toDate = date('Y-m-d H:i:s');
    $usersBonus = Money::join('users', 'User_ID', 'Money_User')
      //->whereIn('User_Level', [1])
      ->select('Money_User', 'User_ID', 'User_Level', 'Money_USDT')
      ->where('Money_MoneyAction', 10)
      ->where('Money_Currency', 10)
      ->where('Money_MoneyStatus', 1)
      ->get();
    $insertArray = [];
    foreach($usersBonus as $money){
      $balanceBonus = User::getBalance($money->Money_User, 10);
      if($balanceBonus <= 0){
        continue;
      }
      $totalTradeBonusArr = GameBet::getTotalTradeBonus($money->Money_User);
      $totalTradeBonus = $totalTradeBonusArr->totalBet ?? 0;
      $arrWithdrawCurren = Money::where('Money_User', $money->Money_User)->where('Money_MoneyAction', 77)->where('Money_MoneyStatus', 1)->where('Money_Currency', 10)->get();
      $withdrawCurren = abs($arrWithdrawCurren->sum('Money_USDT'));
      $amountbonus = $money->Money_USDT;
      if($totalTradeBonus < $amountbonus*50){
        continue;
      }
      if($totalTradeBonus >= $amountbonus*100){
        $amountAvailable = $amountbonus;
      }else{
        if($withdrawCurren > 0 and $balanceBonus < $amountbonus){
          continue;
        }
        $amountAvailable = $amountbonus/2;
      }
      if($balanceBonus < $amountAvailable){
        $amount = $balanceBonus;
      }else{
        $amount = $amountAvailable;
      }

      if($amount <= 0){
        continue;
      }

      $insertArray[] = array(
        'Money_User' => $money->Money_User,
        'Money_USDT' => -$amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.($amount*1).' EUSD (From Balance Bonus)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 10,
        'Money_CurrentAmount' => $amount,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      );
      $insertArray[] = array(
        'Money_User' => $money->Money_User,
        'Money_USDT' => $amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.($amount*1).' EUSD (To Main Balance)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 3,
        'Money_CurrentAmount' => $amount,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      );
      echo $money->Money_User.' ('.$money->User_Level.') : '.$amount.' EUSD - Bonus: '.$amountbonus.' EUSD - Balance: '.$balanceBonus.' EUSD - Total Trade: '.$totalTradeBonus.'<br>';
    }
    if(count($insertArray) && $req->pay == 1){
      Money::insert($insertArray);
    }
  }

  public function getProfits(Request $req){

    $currency = 3;
    $thisDay = date('N');
    if($thisDay != "1"){
      //dd('interest trade only pay on monday');
    }
    //get user
    $mondayLastWeek = date('Y-m-d 11:00:00', strtotime('last day'));
    //$mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    // 	    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('2019-11-01'));
    // thứ 2 tuần này
    $mondayThisWeek = date('Y-m-d 11:00:00', strtotime('today'));
    //$mondayThisWeek = date('Y-m-d H:i:s');
    //lấy những user có đánh tuần trước
    $getUserBet = DB::table('statistical_123betnow')
      ->join('users', 'User_ID', 'statistical_User')
      ->where('User_Block', 0)
      //      ->whereIn('User_Level', [1,5])
      //      ->where('User_ID', 325219)
      //->where('User_Tree', 'LIKE', '%705558%')
      //->where('statistical_User', 175153)
      ->where('statistical_Time', '>=', $mondayLastWeek)
      ->where('statistical_Time', '<', $mondayThisWeek)
      ->where('statistical_Currency', $currency)
      ->selectRaw('SUM(statistical_TotalBet) as totalBet, User_ID , User_Email , User_Tree')
      ->groupBy('statistical_User')
      ->get();
    //dd($getUserBet,$mondayLastWeek,$mondayThisWeek);
    $timeToday = strtotime($mondayThisWeek);
    $action = 65;
    $arrayInsert = [];
    $arrayInsertSameLevel = [];
    $percentSameLevel = 0.1;
    //chưa chặn cron chạy
    foreach($getUserBet as $item){
      $checkPaidDup = Money::where('Money_MoneyAction', $action)
        ->where('Money_Comment', 'LIKE', "%$item->User_ID%")
        ->where('Money_Time', '>=', $timeToday)
        ->where('Money_Currency', $currency)
        ->where('Money_MoneyStatus', 1)
        ->first();
      if($checkPaidDup){
        continue;
      }
      $total_play_game = $item->totalBet;

      $userTree = $item->User_Tree;
      $usersArray = explode(',', $userTree);
      $usersArray = array_reverse($usersArray);
      //% đã nhận được của parent
      $percentCurrent = 0;
      //chạy từ F1-F8
      for($i=1; $i<=7; $i++){
        if(!isset($usersArray[$i])){
          continue;
        }
        $info_parent = User::find($usersArray[$i]);
        if(!$info_parent){
          continue;
        }
        $getPackageParent = GameBet::getPackageUserAvailable($info_parent->User_ID, $mondayLastWeek, $mondayThisWeek, 0);
        //dd($getPackageParent,$info_parent,$usersArray);
        $dataInterest = $getPackageParent;
        if($dataInterest['f'] < $i){
          //continue;
        }
        //số % nhận được = số % package của parent - số % của user con
        $percentInterest = $dataInterest['percent'] - $percentCurrent;
        //thấp cấp hơn => ko trả
        if($percentInterest == 0){
          if($getPackageParent['id'] < 3){
            continue;
          }
          // hoa hồng đồng cấp
          if(!isset($amountInterest)){
            $amountInterest = $total_play_game*$percentInterest;
            $amountInterest = $amountInterest*$percentSameLevel;
          }else{
            $amountInterest = $amountInterest*$percentSameLevel;
          }
          //save
          $arrayInsertSameLevel[] = array(
            'Money_User' => $info_parent->User_ID,
            'Money_USDT' => $amountInterest,
            'Money_USDTFee' => 0,
            'Money_Time' => time(),
            'Money_Comment' => 'Weekly Agency Same Level '.$dataInterest['name'].' Commission $'.($amountInterest/$percentSameLevel+0).' '.($percentSameLevel*100).'% From '.$userChild->User_ID,
            'Money_MoneyAction' => 66,
            'Money_MoneyStatus' => 1,
            'Money_Address' => null,
            'Money_Currency' => $currency,
            'Money_CurrentAmount' => $amountInterest,
            'Money_Rate' => 1,
            'Money_Confirm' => 0,
            'Money_Confirm_Time' => null,
            'Money_FromAPI' => 0,
          );
          echo $info_parent->User_ID.' : $'.$amountInterest.' Weekly Agency Same Level '.$dataInterest['name'].' Commission $'.($amountInterest/$percentSameLevel+0).' '.($percentSameLevel*100).'% From '.$userChild->User_ID.'<br>';
          $userChild = $info_parent;

          continue;
        }elseif($percentInterest < 0){
          continue;
        }
        //update percent parent
        $percentCurrent = $dataInterest['percent'];

        $userChild = $info_parent;
        $amountInterest = $total_play_game*$percentInterest;
        $amountSameLevel = $amountInterest;
        //save
        $arrayInsert[] = array(
          'Money_User' => $info_parent->User_ID,
          'Money_USDT' => $amountInterest,
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => 'Weekly Agency '.$dataInterest['name'].' Commission $'.($total_play_game+0).' '.($percentInterest*100).'% From F'.$i.': '.$item->User_ID,
          'Money_MoneyAction' => $action,
          'Money_MoneyStatus' => 1,
          'Money_Address' => null,
          'Money_Currency' => $currency,
          'Money_CurrentAmount' => $amountInterest,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 0,
        );
        echo $info_parent->User_ID.' : $'.$amountInterest.' Weekly Agency '.$dataInterest['name'].' Commission $'.($total_play_game+0).' '.($percentInterest*100).'% From F'.$i.': '.$item->User_ID.'<br>';
        continue;
      }
    }
    if($req->pay == 1){
      $insert = Money::insert($arrayInsert);
      $insert = Money::insert($arrayInsertSameLevel);
    }
    // 		return view('Cron.Profit');
    dd('check interest Weekly success!');
  }
  public function checkStatisticalWM(Request $req){
    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    if($req->week == 'this'){
      $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s');
      $timeInsert = $mondayThisWeek;
    }
    $type = 'WM555';
    $columnTable = DB::getSchemaBuilder()->getColumnListing('total_history_wm');
    $listColume = implode( ', ', $columnTable );
    //$listColume = str_replace(',', ', ', $columnTable );
    //dd($columnTable, $listColume);
    $listTotalImportedWeek = DB::table('total_history_wm')->join('users', 'users.User_ID', 'total_history_wm.Username')->where('DateTime', '>=', $mondayLastWeek)->where('DateTime', '<', $mondayThisWeek)->selectRaw($listColume)->get();
    //dd($listColume, $listTotalImportedWeek);
    //$timeInsert = date('Y-m-d H:i:s');
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    foreach($listTotalImportedWeek as $data){
      $user = User::find($data->Username);
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      //dd($getStatistical, $data);
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      if($getStatistical){
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet += $data->ValidBetReal;
        if($data->WinLoss < 0){
          $totalLoss += abs($data->WinLoss);
        }else{
          $totalWin += abs($data->WinLoss);
        }
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          ->where('statistical_Time', '<', $mondayThisWeek)
          ->where('statistical_Game', $type)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);
      }else{
        $totalBet = $data->ValidBetReal;
        if($data->WinLoss < 0){
          $totalLoss = abs($data->WinLoss);
        }else{
          $totalWin = abs($data->WinLoss);
        }
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => $type,
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = DB::table('total_history_wm')->where('id', $data->id)->update(['Statistical'=>1]);
    }
    dd('update statistical success');
  }
  public function checkStatisticalSportBook(Request $req){
    $thisDay = date('N');
    if($req->week){
      $this->checkStatisticalSportBookWeek($req);
    }
    $game = 'SportBook';
    //if($thisDay == "1"){
    //    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday last week'));
    //$mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    //    $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    //    $timeInsert = date('Y-m-d H:i:s', strtotime('sunday last week'));
    //}else{
    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    //$mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $mondayThisWeek = date('Y-m-d H:i:s');
    $timeInsert = date('Y-m-d H:i:s');
    //}
    //$mondayThisWeek = date('Y-m-d H:i:s');
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('sportbook_history')->where('date', '>=', $mondayLastWeek)->where('date', '<', $mondayThisWeek)/*->where('Username', 'DAF1481934 ')*/->where('statistical', 0)->get();
    //dd($listTotalImportedWeek, $mondayLastWeek, $mondayThisWeek);
    //$timeInsert = date('Y-m-d H:i:s');
    foreach($listTotalImportedWeek as $data){
      $userID = str_replace('now', '', $data->account);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Time', '>=', $mondayLastWeek)/*->where('statistical_Time', '<', $mondayThisWeek)*/->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = str_replace(' USD', '', $data->stake);
      $checkWin = strpos($data->status, 'Win');
      $checkLose = strpos($data->status, 'Lose');
      if($checkWin === false && $checkLose === false){
        $updateStatistical = DB::table('sportbook_history')->where('id', $data->id)->update(['Statistical'=>1]);
        continue;
      }
      $amount = str_replace('Win ', '', $data->status);
      $amount = str_replace('Win/2 ', '', $amount);
      $amount = str_replace('Lose ', '', $amount);
      $amount = str_replace('Lose/2 ', '', $amount);
      if($checkLose !== false){
        $totalLoss += abs($amount);
      }elseif($checkWin !== false){
        $totalWin += abs($amount);
      }else{
        continue;
      }
      //dd($data, $checkWin, $checkLose, $totalBet, $amount, $totalLoss, $totalWin);
      //dd($getStatistical, $data, $checkWin, $checkLose, $totalBet, $amount, $totalLoss, $totalWin);
      if($getStatistical){
        $totalBet += $getStatistical->statistical_TotalBet;
        $totalWin += $getStatistical->statistical_TotalWin;
        $totalLoss += $getStatistical->statistical_TotalLost;
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_ID', $getStatistical->statistical_ID)
          /*->where('statistical_Time', '>=', $mondayLastWeek)
                  						->where('statistical_Time', '<', $mondayThisWeek)
                  						->where('statistical_Game', $game)
                  						->where('statistical_User', $user->User_ID)*/
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);
      }else{
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => $game,
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = DB::table('sportbook_history')->where('id', $data->id)->update(['Statistical'=>1]);
    }
    dd('update statistical sportbook daily success');
  }

  public function checkStatisticalSportBookWeek(Request $req){
    if($req->key != '123123123'){
      abort(404);
    }
    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    //$mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    //$mondayThisWeek = date('Y-m-d H:i:s');
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'SportBook')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('sportbook_history')/*->where('CreatedAt', '>=', $mondayThisWeek)*//*->where('Username', 'DAF1481934 ')*/->where('statistical', 0)->get();
    //dd($listTotalImportedWeek);
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    //$timeInsert = date('Y-m-d H:i:s');
    foreach($listTotalImportedWeek as $data){
      $userID = str_replace('now', '', $data->account);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'SportBook')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      //dd($getStatistical, $data);
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = str_replace(' USD', '', $data->stake);
      $checkWin = strpos($data->status, 'Win');
      $checkLose = strpos($data->status, 'Lose');
      if($checkWin === false && $checkLose === false){
        $updateStatistical = DB::table('sportbook_history')->where('id', $data->id)->update(['Statistical'=>1]);
        continue;
      }
      $amount = str_replace('Win ', '', $data->status);
      $amount = str_replace('Win/2 ', '', $amount);
      $amount = str_replace('Lose ', '', $amount);
      $amount = str_replace('Lose/2 ', '', $amount);
      if($checkLose !== false){
        $totalLoss += abs($amount);
      }elseif($checkWin !== false){
        $totalWin += abs($amount);
      }else{
        continue;
      }
      //dd($data, $checkWin, $checkLose, $totalBet, $amount, $totalLoss, $totalWin);
      if($getStatistical){
        $totalBet += $getStatistical->statistical_TotalBet;
        $totalWin += $getStatistical->statistical_TotalWin;
        $totalLoss += $getStatistical->statistical_TotalLost;
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          /*->where('statistical_Time', '<', $mondayThisWeek)*/
          ->where('statistical_Game', 'SportBook')
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }else{
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => 'SportBook',
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = DB::table('sportbook_history')->where('id', $data->id)->update(['Statistical'=>1]);
    }
    dd('update statistical sportbook week success');
  }

  public function getAgencyCommission(Request $req){
    abort(404);
    //get user
    //$mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday last week'));
    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    // $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('2019-11-01'));
    // thứ 2 tuần này
    $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    //$mondayThisWeek = date('Y-m-d H:i:s');
    //lấy những user có đánh tuần trước
    $start = (strtotime($mondayLastWeek));
    $end = (strtotime($mondayThisWeek));
    $action = 68;
    $currency = 3;
    $packageArray = GameBet::getPackageAgency();
    //lấy những user có đánh tuần trước
    $getUserBet = Money::where('Money_MoneyAction', $action)
      ->where('Money_Time', '>=', $start)
      ->where('Money_Time', '<', $end)
      ->where('Money_MoneyStatus', 1)
      //->where('Money_User', 969399)
      ->selectRaw('SUM(Money_USDT) as Money_USDT, Money_User, Money_MoneyAction')
      ->groupBy('Money_User')
      ->get();
    foreach($getUserBet as $item){
      dd($getUserBet, $item);
      $user = User::find($item->Money_User);
      $amount = abs($item->Money_USDT);
      $agencyUser = GameBet::getPackageUser($amount, $packageArray);
      //dd($user, $amount, $agencyUser);
      //Money::checkCommissionAgency($user, $agencyUser, $amount);
    }
  }

  public function getRefundBet(Request $req){
    $currency = 3;
    $thisDay = date('N');

    if($thisDay != "1"){
      //dd('interest trade only pay on monday');
    }
    //get user
    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday last week'));
    //$mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    // 	    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('2019-11-01'));
    $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    // 	    $mondayThisWeek = date('Y-m-d H:i:s');
    //lấy những user có đánh tuần trước
    $getUserBet = DB::table('statistical_123betnow')
      ->join('users', 'User_ID', 'statistical_User')
      //						->whereIn('User_Level', [1,5])
      //                        ->where('User_Tree', 'like', '%456319%')
      //->where('statistical_User', 442061)
      ->where('statistical_Time', '>=', $mondayLastWeek)
      ->where('statistical_Time', '<', $mondayThisWeek)
      ->where('statistical_Currency', $currency)
      ->selectRaw('SUM(statistical_TotalBet) as totalBet, SUM(statistical_TotalWin - statistical_TotalLost) as Profit, User_ID , User_Email , User_Tree')
      ->groupBy('statistical_User')
      ->having('totalBet', '>=', 200)
      ->having('Profit', '<', 0)
      ->get();
    //dd($getUserBet, $mondayLastWeek,$mondayThisWeek );
    $timeToday = strtotime($mondayThisWeek);
    $action = 67;
    $arrayInsert = [];
    foreach($getUserBet as $item){
      $checkPaidDup = Money::where('Money_MoneyAction', $action)
        ->where('Money_User', $item->User_ID)
        ->where('Money_Time', '>=', $timeToday)
        ->where('Money_Currency', $currency)
        ->first();
      if($checkPaidDup){
        continue;
      }
      $profit = abs($item->Profit);
      $totalBet = $item->totalBet;
      //lấy gói thoả điều kiện

      $bonus = 1;
      $perecent = 0.003*$bonus;
      if($profit >= 501){
        $perecent = 0.004*$bonus;
      }
      if($profit > 3001){
        $perecent = 0.006*$bonus;
      }
      if($profit > 10001){
        $perecent = 0.008*$bonus;
      }
      if($profit > 30001){
        $perecent = 0.01*$bonus;
      }

      $amountRefund = $profit*$perecent;
      //save
      $arrayInsert[] = array(
        'Money_User' => $item->User_ID,
        'Money_USDT' => $amountRefund,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Weekly 123BetNow Refund $'.($amountRefund+0).' '.($perecent*100).'% From $'.$profit.' Lose',
        'Money_MoneyAction' => $action,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $currency,
        'Money_CurrentAmount' => $amountRefund,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 0,
      );
      echo $item->User_ID.' : $'.$amountRefund.' Weekly 123BetNow Refund $'.($amountRefund+0).' '.($perecent*100).'% From $'.$profit.' Lose <br>';
      continue;
    }
    if($req->pay == 1){
      $insert = Money::insert($arrayInsert);
    }
    // 		return view('Cron.Profit');
    dd('check refund Weekly success!');
  }

  public function checkStatisticalSA(Request $req){
    if($req->key != '123123123'){
      abort(404);
    }
    if($req->sportbook == 1){
      $this->checkStatisticalSportBook($req);
      dd('done sportbook');
    }
    if($req->wm555 == 1){
      $this->checkStatisticalWM($req);
      dd('done wm555');
    }

    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    if($req->thisweek == 1){
      $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s');
      $timeInsert = $mondayThisWeek;
    }
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Casino')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('totalhistorysa')->join('users', 'User_ID', 'Username')->where('CreatedAt', '>=', $mondayLastWeek)->where('CreatedAt', '<', $mondayThisWeek)->where('Statistical', 0)->get();
    //dd($listTotalImportedWeek);
    //$timeInsert = date('Y-m-d H:i:s');
    foreach($listTotalImportedWeek as $data){
      $user = User::find($data->Username);
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Casino')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      //dd($getStatistical, $data);
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      if($getStatistical){
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet += $data->BetAmount;
        if($data->WinLoss < 0){
          $totalLoss += abs($data->WinLoss);
        }else{
          $totalWin += abs($data->WinLoss);
        }
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          ->where('statistical_Time', '<', $mondayThisWeek)
          ->where('statistical_Game', 'Casino')
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);
      }else{
        $totalBet = $data->BetAmount;
        if($data->WinLoss < 0){
          $totalLoss = abs($data->WinLoss);
        }else{
          $totalWin = abs($data->WinLoss);
        }
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => 'Casino',
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = DB::table('totalhistorysa')->where('id', $data->id)->update(['Statistical'=>1]);
    }
    dd('update statistical success');
  }

  function floorp($val, $precision){
    $mult = pow(10, $precision); // Can be cached in lookup table
    return floor($val * $mult) / $mult;
  }

}

