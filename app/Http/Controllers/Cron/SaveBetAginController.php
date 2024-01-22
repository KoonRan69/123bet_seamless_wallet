<?php

namespace App\Http\Controllers\Cron;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Validator;
use App\Model\User;
use App\Model\Money;
use App\Model\LogUser;
use App\Model\BetHistoryWM;
use App\Model\BetHistoryAgin;
use App\Model\CreditHistoryAgin;
use App\Model\BetHistoryAginSlot;
use App\Model\BetHistoryAginHunterFish;
use Carbon\Carbon;
use DB;

class SaveBetAginController extends Controller
{


  public function checkStatisticalSbobetDay(Request $req){
    if($req->key != '321321321'){
      abort(404);
    }

    $mondayLastWeek = date('Y-m-d 11:00:00', strtotime('last day'));
    $mondayThisWeek = date('Y-m-d 11:00:00', strtotime('today'));

    //$mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    //$mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Sbobet')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('show_history_sbobet')->where('statistical', 0)->get();

    foreach($listTotalImportedWeek as $data){
      $timeInsert = $data->time_123betnow;
      $userID = $data->userId;
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Sbobet')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->net_turnover_by_stake;
      $amount = $data->member_wins;
      if($amount < 0){
        $totalLoss += abs($amount);
      }elseif($amount >= 0){
        $totalWin += abs($amount);
      }else{
        continue;
      }
      if($getStatistical){
        $totalBet += $getStatistical->statistical_TotalBet;
        $totalWin += $getStatistical->statistical_TotalWin;
        $totalLoss += $getStatistical->statistical_TotalLost;
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          /*->where('statistical_Time', '<', $mondayThisWeek)*/
          ->where('statistical_Game', 'Sbobet')
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
            'statistical_Game' => 'Sbobet',
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = DB::table('show_history_sbobet')->where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical sbobet day success');
  }

  public function checkStatisticalEvolutionWeek(Request $req){
    if($req->key != '123123123'){
      abort(404);
    }
    abort(404);
    //$mondayLastWeek = date('Y-m-d 11:00:00', strtotime('last day'));
    //$mondayThisWeek = date('Y-m-d 11:00:00', strtotime('today'));

    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Evolution')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('bet_history_evolution')->where('evo_status','Resolved')->where('evo_result','View')->where('statistical', 0)->get();
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));

    foreach($listTotalImportedWeek as $data){
      $prefix = 'now_';
      $userID = $data->userId;
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Evolution')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->evo_bet;
      $amount = $data->evo_win;
      if($amount < 0){
        $totalLoss += abs($amount);
      }elseif($amount >= 0){
        $totalWin += abs($amount);
      }else{
        continue;
      }
      if($getStatistical){
        $totalBet += $getStatistical->statistical_TotalBet;
        $totalWin += $getStatistical->statistical_TotalWin;
        $totalLoss += $getStatistical->statistical_TotalLost;
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          /*->where('statistical_Time', '<', $mondayThisWeek)*/
          ->where('statistical_Game', 'Evolution')
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
            'statistical_Game' => 'Evolution',
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = DB::table('bet_history_evolution')->where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical evolution week success');
  }

  public function checkStatisticalSbobetWeek(Request $req){
    if($req->key != '123123123'){
      abort(404);
    }
    abort(404);
    //$mondayLastWeek = date('Y-m-d 11:00:00', strtotime('last day'));
    //$mondayThisWeek = date('Y-m-d 11:00:00', strtotime('today'));

    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Sbobet')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('bet_history_sbobet_ib')->where('statistical', 0)->get();
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));

    foreach($listTotalImportedWeek as $data){
      $prefix = 'now_';
      $userID = $data->userId;
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Sbobet')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->net_turnover_by_stake;
      $amount = $data->member_wins;
      if($amount < 0){
        $totalLoss += abs($amount);
      }elseif($amount >= 0){
        $totalWin += abs($amount);
      }else{
        continue;
      }
      if($getStatistical){
        $totalBet += $getStatistical->statistical_TotalBet;
        $totalWin += $getStatistical->statistical_TotalWin;
        $totalLoss += $getStatistical->statistical_TotalLost;
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          /*->where('statistical_Time', '<', $mondayThisWeek)*/
          ->where('statistical_Game', 'Sbobet')
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
            'statistical_Game' => 'Sbobet',
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = DB::table('bet_history_sbobet_ib')->where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical sbobet week success');
  }

  //thống kê Sbobet Virtualsport
  public function checkStatisticalSbobetVirtualsport(Request $req){
    return;
    $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday last week'));
    $mondayThisWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
    //$mondayThisWeek =  strtotime('friday this week');
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    //dd($req->week);
    if($req->week == 'this'){
      $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s', strtotime('+ 7 hours', time()));
      $timeInsert = $mondayThisWeek;
    }
    //dd($mondayLastWeek,$mondayThisWeek);
    //dd($mondayLastWeek<$mondayThisWeek);

    //$deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    //dd($deleteStatistical);
    $listTotalImportedWeek = DB::table('bet_history_sbobet_virtualsport')->Join('users', 'users.User_ID', 'bet_history_sbobet_virtualsport.user_id')->where('bet_history_sbobet_virtualsport.statistical_time123betnow', '>=', $mondayLastWeek)
      ->where('bet_history_sbobet_virtualsport.statistical_time123betnow', '<', $mondayThisWeek)->whereIn('status',['won','lose'])->where('statistical',0)->get();
    //$key = 0;
    foreach($listTotalImportedWeek as $data){
      //$key ++;
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $user = User::find($data->user_id);
      if($req->week == 'this'){
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Game', $data->Portfolio)->first();
      }else{
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_Game', $data->Portfolio)->first();
      }
      //
      if($getStatistical){
        //dd($getStatistical);
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet = $totalBet + $data->stake;
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win;
          //echo $data->awardmoney - $data->betmoney ;
        }

        //dd($totalBet,$totalWin,$totalLoss);
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          ->where('statistical_Time', '<', $mondayThisWeek)
          ->where('statistical_Game', $data->Portfolio)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);


      }else{
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win ;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win ;
          //echo $data->awardmoney - $data->betmoney ;
        }

        $totalBet = $totalBet + $data->stake;
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => "Sbobet $data->Portfolio",
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s',strtotime('+ 7 hours', time())),
          ]);
      }

      $updateStatistical = DB::table('bet_history_sbobet_virtualsport')->where('id', $data->id)->update(['statistical'=>1]);
    }
    //dd($key);
    dd('update statistical success');
  }

  //thống kê Sbobet Sportbook, Sbolive
  public function checkStatisticalSbobetSportbookSbolive(Request $req){
    return;
    $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday last week'));
    $mondayThisWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
    //$mondayThisWeek =  strtotime('friday this week');
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    //dd($req->week);
    if($req->week == 'this'){
      $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s', strtotime('+ 7 hours', time()));
      $timeInsert = $mondayThisWeek;
    }
    //dd($mondayLastWeek,$mondayThisWeek);
    //dd($mondayLastWeek<$mondayThisWeek);
    $type = 'SportsBook' ;

    //$deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    //dd($deleteStatistical);
    $listTotalImportedWeek = DB::table('bet_history_sbobet')->Join('users', 'users.User_ID', 'bet_history_sbobet.user_id')->where('bet_history_sbobet.statistical_time123betnow', '>=', $mondayLastWeek)->where('bet_history_sbobet.statistical_time123betnow', '<', $mondayThisWeek)
      ->whereIn('status',['won','lose'])->where('statistical',0)->get();
    //dd($listTotalImportedWeek);
    //$key = 0;
    foreach($listTotalImportedWeek as $data){
      //$key ++;
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $user = User::find($data->user_id);
      if($req->week == 'this'){
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Game', $data->Portfolio)->first();
      }else{
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_Game', $data->Portfolio)->first();
      }
      //
      if($getStatistical){
        //dd($getStatistical);
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet = $totalBet + $data->stake;
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win;
          //echo $data->awardmoney - $data->betmoney ;
        }

        //dd($totalBet,$totalWin,$totalLoss);
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          ->where('statistical_Time', '<', $mondayThisWeek)
          ->where('statistical_Game', $data->Portfolio)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);


      }else{
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win ;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win ;
          //echo $data->awardmoney - $data->betmoney ;
        }

        $totalBet = $totalBet + $data->stake;
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => "Sbobet $data->Portfolio",
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s',strtotime('+ 7 hours', time())),
          ]);
      }

      $updateStatistical = DB::table('bet_history_sbobet')->where('id', $data->id)->update(['statistical'=>1]);
    }
    //dd($key);
    dd('update statistical success');
  }

  //thống kê Sbobet Casino
  public function checkStatisticalSbobetCasino(Request $req){
    return;
    $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday last week'));
    $mondayThisWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
    //$mondayThisWeek =  strtotime('friday this week');
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    //dd($req->week);
    if($req->week == 'this'){
      $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s', strtotime('+ 7 hours', time()));
      $timeInsert = $mondayThisWeek;
    }
    //dd($mondayLastWeek,$mondayThisWeek);
    //dd($mondayLastWeek<$mondayThisWeek);

    //$deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    //dd($deleteStatistical);
    $listTotalImportedWeek = DB::table('bet_history_sbobet_casino')->Join('users', 'users.User_ID', 'bet_history_sbobet_casino.user_id')->where('bet_history_sbobet_casino.statistical_time123betnow', '>=', $mondayLastWeek)
      ->where('bet_history_sbobet_casino.statistical_time123betnow', '<', $mondayThisWeek)->whereIn('status',['won','lose'])->where('statistical',0)->get();
    //dd($listTotalImportedWeek);
    //$key = 0;
    foreach($listTotalImportedWeek as $data){
      //$key ++;
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $user = User::find($data->user_id);
      if($req->week == 'this'){
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Game', $data->Portfolio)->first();
      }else{
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_Game', $data->Portfolio)->first();
      }
      //
      if($getStatistical){
        //dd($getStatistical);
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet = $totalBet + $data->stake;
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win;
          //echo $data->awardmoney - $data->betmoney ;
        }

        //dd($totalBet,$totalWin,$totalLoss);
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          ->where('statistical_Time', '<', $mondayThisWeek)
          ->where('statistical_Game', $data->Portfolio)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);


      }else{
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win ;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win ;
          //echo $data->awardmoney - $data->betmoney ;
        }

        $totalBet = $totalBet + $data->stake;
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => "Sbobet $data->Portfolio",
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s',strtotime('+ 7 hours', time())),
          ]);
      }

      $updateStatistical = DB::table('bet_history_sbobet_casino')->where('id', $data->id)->update(['statistical'=>1]);
    }
    //dd($key);
    dd('update statistical success');
  }

  //thống kê Sbobet Seamless
  public function checkStatisticalSbobetSeamless(Request $req){
    return;
    $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday last week'));
    $mondayThisWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
    //$mondayThisWeek =  strtotime('friday this week');
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    //dd($req->week);
    if($req->week == 'this'){
      $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s', strtotime('+ 7 hours', time()));
      $timeInsert = $mondayThisWeek;
    }
    //dd($mondayLastWeek,$mondayThisWeek);
    //dd($mondayLastWeek<$mondayThisWeek);

    //$deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    //dd($deleteStatistical);
    $listTotalImportedWeek = DB::table('bet_history_sbobet_seamless')->Join('users', 'users.User_ID', 'bet_history_sbobet_seamless.user_id')->where('bet_history_sbobet_seamless.statistical_time123betnow', '>=', $mondayLastWeek)
      ->where('bet_history_sbobet_seamless.statistical_time123betnow', '<', $mondayThisWeek)->whereIn('status',['won','lose'])->where('statistical',0)->get();
    //$key = 0;
    foreach($listTotalImportedWeek as $data){
      //$key ++;
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $user = User::find($data->user_id);
      if($req->week == 'this'){
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Game', $data->Portfolio)->first();
      }else{
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_Game', $data->Portfolio)->first();
      }
      //
      if($getStatistical){
        //dd($getStatistical);
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet = $totalBet + $data->stake;
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win;
          //echo $data->awardmoney - $data->betmoney ;
        }

        //dd($totalBet,$totalWin,$totalLoss);
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          ->where('statistical_Time', '<', $mondayThisWeek)
          ->where('statistical_Game', $data->Portfolio)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);


      }else{
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win ;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win ;
          //echo $data->awardmoney - $data->betmoney ;
        }

        $totalBet = $totalBet + $data->stake;
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => "Sbobet $data->Portfolio",
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s',strtotime('+ 7 hours', time())),
          ]);
      }

      $updateStatistical = DB::table('bet_history_sbobet_seamless')->where('id', $data->id)->update(['statistical'=>1]);
    }
    //dd($key);
    dd('update statistical success');
  }

  //thống kê Sbobet ThirdPartySportsBook
  public function checkStatisticalSbobetThirdPartySportsBook(Request $req){
    return;
    $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday last week'));
    $mondayThisWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
    //$mondayThisWeek =  strtotime('friday this week');
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    //dd($req->week);
    if($req->week == 'this'){
      $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s', strtotime('+ 7 hours', time()));
      $timeInsert = $mondayThisWeek;
    }
    //dd($mondayLastWeek,$mondayThisWeek);
    //dd($mondayLastWeek<$mondayThisWeek);

    //$deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    //dd($deleteStatistical);
    $listTotalImportedWeek = DB::table('bet_history_sbobet_ThirdPartySportsBook')->Join('users', 'users.User_ID', 'bet_history_sbobet_ThirdPartySportsBook.user_id')->where('bet_history_sbobet_ThirdPartySportsBook.statistical_time123betnow', '>=', $mondayLastWeek)
      ->where('bet_history_sbobet_ThirdPartySportsBook.statistical_time123betnow', '<', $mondayThisWeek)->whereIn('status',['won','lose'])->where('statistical',0)->get();
    //dd($listTotalImportedWeek);
    //$key = 0;
    foreach($listTotalImportedWeek as $data){
      //$key ++;
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $user = User::find($data->user_id);
      if($req->week == 'this'){
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Game', $data->Portfolio)->first();
      }else{
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_Game', $data->Portfolio)->first();
      }
      //
      if($getStatistical){
        //dd($getStatistical);
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet = $totalBet + $data->stake;
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win;
          //echo $data->awardmoney - $data->betmoney ;
        }

        //dd($totalBet,$totalWin,$totalLoss);
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          ->where('statistical_Time', '<', $mondayThisWeek)
          ->where('statistical_Game', $data->Portfolio)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);


      }else{
        if($data->amount_win > 0 ){
          $totalWin = $totalWin + $data->amount_win ;
        }
        elseif($data->amount_win <= 0){
          $totalLoss = $totalLoss + $data->amount_win ;
          //echo $data->awardmoney - $data->betmoney ;
        }

        $totalBet = $totalBet + $data->stake;
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => "Sbobet $data->Portfolio",
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s',strtotime('+ 7 hours', time())),
          ]);
      }

      $updateStatistical = DB::table('bet_history_sbobet_ThirdPartySportsBook')->where('id', $data->id)->update(['statistical'=>1]);
    }
    //dd($key);
    dd('update statistical success');
  }

  //thống kê ae sexy
  public function checkStatisticalAeSexy(Request $req){

    $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday last week'));
    $mondayThisWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
    //$mondayThisWeek =  strtotime('friday this week');
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    //dd($req->week);
    if($req->week == 'this'){
      $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s', strtotime('+ 7 hours', time()));
      $timeInsert = $mondayThisWeek;
    }
    //dd($mondayLastWeek,$mondayThisWeek);
    //dd($mondayLastWeek<$mondayThisWeek);
    $type = 'AeSexy' ;

    //$deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    //dd($deleteStatistical);
    $listTotalImportedWeek = DB::table('bet_history_ae_sexy')->Join('users', 'users.User_ID', 'bet_history_ae_sexy.userId')->where('bet_history_ae_sexy.updateTime', '>=', $mondayLastWeek)->where('bet_history_ae_sexy.updateTime', '<', $mondayThisWeek)->where('statistical',0)->get();
    //dd($listTotalImportedWeek);
    //$key = 0;
    foreach($listTotalImportedWeek as $data){
      //$key ++;
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $user = User::find($data->userId);
      if($req->week == 'this'){
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Game', $type)->first();
      }else{
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_Game', $type)->first();
      }
      //
      if($getStatistical){
        //dd($getStatistical);
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet = $totalBet + $data->realBetAmount;
        if($data->realWinAmount - $data->realBetAmount > 0 ){
          $totalWin = $totalWin + ($data->realWinAmount - $data->realBetAmount) ;
        }
        elseif($data->realWinAmount - $data->realBetAmount < 0){
          $totalLoss = $totalLoss + ($data->realWinAmount - $data->realBetAmount);
          //echo $data->awardmoney - $data->betmoney ;
        }

        //dd($totalBet,$totalWin,$totalLoss);
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
        if($data->realWinAmount - $data->realBetAmount > 0 ){
          $totalWin = $totalWin + ($data->realWinAmount - $data->realBetAmount) ;
        }
        elseif($data->realWinAmount - $data->realBetAmount < 0){
          $totalLoss = $totalLoss + ($data->realWinAmount - $data->realBetAmount) ;
          //echo $data->awardmoney - $data->betmoney ;
        }

        $totalBet = $totalBet + $data->realBetAmount ;
        $updateStatistical = DB::table('statistical_123betnow')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_Game' => $type,
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s',strtotime('+ 7 hours', time())),
          ]);
      }

      $updateStatistical = DB::table('bet_history_ae_sexy')->where('id', $data->id)->update(['statistical'=>1]);
    }
    //dd($key);
    dd('update statistical success');
  }

  //thống kê game spost book
  public function checkStatisticalAginSportBook(Request $req){
    if($req->week){
      $this->checkStatisticalAginSportBookWeek($req);
    }
    $game = 'Agin';
    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $mondayThisWeek = date('Y-m-d H:i:s');
    $timeInsert = date('Y-m-d H:i:s');
    //dd($mondayLastWeek,$mondayThisWeek,$timeInsert);
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('bet_history_agin')->whereIn('flag',[0,1,4])->where('statistical', 0)->get();
    foreach($listTotalImportedWeek as $data){
      $prefix = config('urlAgin.agin_api');
      $userID = str_replace($prefix['prefix'], '', $data->username);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->account;
      $checkWin = strpos($data->simplified_result, 'Win');
      $checkLose = strpos($data->simplified_result, 'Lose');
      //dd($data,$data->simplified_result,$checkWin,$checkLose);
      if($checkWin === false && $checkLose === false){
        //$updateStatistical = DB::table('sportbook_history')->where('id', $data->id)->update(['Statistical'=>1]);
        continue;
      }
      $amount = $data->cus_account;
      if($checkLose !== false){
        $totalLoss += abs($amount);
      }elseif($checkWin !== false){
        $totalWin += abs($amount);
      }else{
        continue;
      }
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
      $updateStatistical = DB::table('bet_history_agin')->where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical agin sportbook daily success');
  }

  public function checkStatisticalAginSportBookWeek(Request $req){
    if($req->key != '123123123'){
      abort(404);
    }
    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Agin')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('bet_history_agin')->whereIn('flag',[0,1,4])->where('statistical', 0)->get();
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));

    foreach($listTotalImportedWeek as $data){
      $prefix = config('urlAgin.agin_api');
      $userID = str_replace($prefix['prefix'], '', $data->username);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Agin')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->account;
      $checkWin = strpos($data->simplified_result, 'Win');
      $checkLose = strpos($data->simplified_result, 'Lose');
      if($checkWin === false && $checkLose === false){
        //$updateStatistical = DB::table('bet_history_agin')->where('id', $data->id)->update(['Statistical'=>1]);
        continue;
      }
      $amount = $data->cus_account;
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
          ->where('statistical_Game', 'Agin')
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
            'statistical_Game' => 'Agin',
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = DB::table('bet_history_agin')->where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical agin sportbook week success');
  }
  //lưu lịch sử spotbook
  public function saveHistoryBest(){
    //////////////////////////// GET WIN LOST //////////////////////////////////
    date_default_timezone_set("America/Anguilla");
    $cagent = 'JT9'; 
    $t =time(); //2022-02-14 05:55:08
    //dd(date('Y-m-d H:i:s'));
    $t1 = strtotime('-1 minutes',$t); 
    $t2 = strtotime('-10 minutes',$t1); 
    $startdate = date('Y-m-d H:i:s',$t2); 
    $enddate = date('Y-m-d H:i:s',$t1); 

    $key = md5($cagent.$startdate.$enddate.'6377A2D3DC3F79BFA3684DC886F28365');
    $url = 'http://jde6t9.gdcapi.com:3333/getagsportorders_ex.xml?startdate='.$startdate.'&enddate='.$enddate.'&cagent='.$cagent.'&key='.$key;
    //$url = 'http://jde6t9.gdcapi.com:3333/getorders.xml?cagent='.$cagent.'&startdate='.$startdate.'&enddate='.$enddate.'&key='.$key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    $xml = simplexml_load_string($data);
    $betHistory = BetHistoryAgin::where('create_date', '>=', strtotime('-1 days'))->pluck('billno')->toArray();
    $results = [];
    foreach($xml->row as $value){
      if ( !in_array($value['billno'], $betHistory) ) {
        $results[] = [
          'userid' => str_replace('now_', '', $value['username']),
          'username' => $value['username'],
          'billno' => $value['billno'],
          'productid' => $value['productid'],
          'billtime' => $value['billtime'],
          'currency' => $value['currency'],
          'gametype' => $value['gametype'],
          'betIP' => $value['betIP'],
          'account' => $value['account'],
          'cus_account' => $value['cus_account'],
          'valid_account' => $value['valid_account'],
          'flag' => $value['flag'],
          'platformtype' => $value['platformtype'],
          'odds' => $value['odds'],
          'sport' => $value['sport'],
          'category' => $value['category'],
          'extbillno' => $value['extbillno'],
          'thirdbillno' => $value['thirdbillno'],
          'bettype' => $value['bettype'],
          'system' => $value['system'],
          'live' => $value['live'],
          'current_score' => $value['current_score'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['billtime']))),
          'reckontime' => $value['reckontime'],
          'competition' => $value['competition'],
          'market' => $value['market'],
          'selection' => $value['selection'],
          'simplified_result' => $value['simplified_result'],
        ];
      }
      else{
        $betUpdateStatus = BetHistoryAgin::where('billno',$value['billno'])->first();
        BetHistoryAgin::where('billno',$value['billno'])->update([
          'productid' => $value['productid'],
          'billtime' => $value['billtime'],
          'currency' => $value['currency'],
          'gametype' => $value['gametype'],
          'betIP' => $value['betIP'],
          'account' => $value['account'],
          'cus_account' => $value['cus_account'],
          'valid_account' => $value['valid_account'],
          'flag' => $value['flag'],
          'platformtype' => $value['platformtype'],
          'odds' => $value['odds'],
          'sport' => $value['sport'],
          'category' => $value['category'],
          'extbillno' => $value['extbillno'],
          'thirdbillno' => $value['thirdbillno'],
          'bettype' => $value['bettype'],
          'system' => $value['system'],
          'live' => $value['live'],
          'current_score' => $value['current_score'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['billtime']))),
          'reckontime' => $value['reckontime'],
          'competition' => $value['competition'],
          'market' => $value['market'],
          'selection' => $value['selection'],
          'simplified_result' => $value['simplified_result'],
        ]);
        /*if($betUpdateStatus->simplified_result == null){
          BetHistoryAgin::where('billno',$value['billno'])->update(['reckontime' => $value['reckontime'],'simplified_result'=>$value['simplified_result']]);
        }*/
      }
    }
    if (count($results) > 0) BetHistoryAgin::insert($results);
    dd('Save History Best startdate: '.$startdate.' - enddate: '.$enddate.' Success');

  }
  //lưu lịch sử game slot
  public function saveHistoryBestSlot(){
    //////////////////////////// GET WIN LOST //////////////////////////////////
    date_default_timezone_set("America/Anguilla");
    $cagent = 'JT9'; 
    $t =time(); //strtotime('2022-04-18 03:05:55'); 
    //dd(date('Y-m-d H:i:s'));
    $t1 = strtotime('-1 minutes',$t); 
    $t2 = strtotime('-10 minutes',$t1); 
    $startdate = date('Y-m-d H:i:s',$t2); 
    $enddate = date('Y-m-d H:i:s',$t1); 

    $key = md5($cagent.$startdate.$enddate.'6377A2D3DC3F79BFA3684DC886F28365');
    $url = 'http://jde6t9.gdcapi.com:3333/getslotorders_ex.xml?startdate='.$startdate.'&enddate='.$enddate.'&cagent='.$cagent.'&key='.$key;
    //$url = 'http://jde6t9.gdcapi.com:3333/getorders.xml?cagent='.$cagent.'&startdate='.$startdate.'&enddate='.$enddate.'&key='.$key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    $xml = simplexml_load_string($data);
    $betHistory = BetHistoryAginSlot::where('create_date', '>=', strtotime('-1 days'))->pluck('billno')->toArray();
    $results = [];
    foreach($xml->row as $value){
      if ( !in_array($value['billno'], $betHistory) ) {
        $results[] = [
          'userid' => str_replace('now_', '', $value['username']),
          'username' => $value['username'],
          'billno' => $value['billno'],
          'productid' => $value['productid'],
          'billtime' => $value['billtime'],
          'reckontime' => $value['reckontime'],
          'slottype' => $value['slottype'],
          'currency' => $value['currency'],
          'gametype' => $value['gametype'],
          'betIP' => $value['betIP'],
          'account' => $value['account'],
          'cus_account' => $value['cus_account'],
          'valid_account' => $value['valid_account'],
          'account_base' => $value['account_base'],
          'account_bonus' => $value['account_bonus'],
          'cus_account_base' => $value['cus_account_base'],
          'cus_account_bonus' => $value['cus_account_bonus'],
          'src_amount' => $value['src_amount'],
          'dst_amount' => $value['dst_amount'],
          'flag' => $value['flag'],
          'platformtype' => $value['platformtype'],
          'devicetype' => $value['devicetype'],
          'exttxid' => $value['exttxid'],
          'mainbillno' => $value['mainbillno'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['billtime']))),
        ];
      }
      else{
        $betUpdateStatus = BetHistoryAginSlot::where('billno',$value['billno'])->first();
        BetHistoryAginSlot::where('billno',$value['billno'])->update([
          'productid' => $value['productid'],
          'billtime' => $value['billtime'],
          'reckontime' => $value['reckontime'],
          'slottype' => $value['slottype'],
          'currency' => $value['currency'],
          'gametype' => $value['gametype'],
          'betIP' => $value['betIP'],
          'account' => $value['account'],
          'cus_account' => $value['cus_account'],
          'valid_account' => $value['valid_account'],
          'account_base' => $value['account_base'],
          'account_bonus' => $value['account_bonus'],
          'cus_account_base' => $value['cus_account_base'],
          'cus_account_bonus' => $value['cus_account_bonus'],
          'src_amount' => $value['src_amount'],
          'dst_amount' => $value['dst_amount'],
          'flag' => $value['flag'],
          'platformtype' => $value['platformtype'],
          'devicetype' => $value['devicetype'],
          'exttxid' => $value['exttxid'],
          'mainbillno' => $value['mainbillno'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($value['billtime']))),
        ]);
      }
    }
    if (count($results) > 0) BetHistoryAginSlot::insert($results);
    dd('Save History Best Slot startdate: '.$startdate.' - enddate: '.$enddate.' Success');
  }
  //lưu lịch sử game bắn cá (hunter)
  public function saveHistoryBestHunter(){
    //////////////////////////// GET WIN LOST //////////////////////////////////
    date_default_timezone_set("America/Anguilla");
    $cagent = 'JT9'; 
    $t = time(); //strtotime('2022-05-03 04:20:00'); 2022-05-03 21:54:44
    //dd(date('Y-m-d H:i:s'));
    $t1 = strtotime('-1 minutes',$t); 
    $t2 = strtotime('-10 minutes',$t1); 
    $startdate = strtotime(date('Y-m-d H:i:s',$t2)); 
    $enddate = strtotime(date('Y-m-d H:i:s',$t1)); 

    $key = md5($cagent.$startdate.$enddate.'6377A2D3DC3F79BFA3684DC886F28365');
    $url = 'http://jde6t9.gdcapi.com:3333/gethunterscene.xml?startdate='.$startdate.'&enddate='.$enddate.'&cagent='.$cagent.'&key='.$key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    $xml = simplexml_load_string($data);
    $betHistory = BetHistoryAginHunterFish::where('create_date', '>=', strtotime('-1 days'))->pluck('sceneid')->toArray();
    $results = [];
    foreach($xml->row as $value){
      if ( !in_array($value['sceneid'], $betHistory) ) {
        $results[] = [
          'userid' => str_replace('now_', '', $value['username']),
          'username' => $value['username'],
          'productid' => $value['productid'],
          'roomid' => $value['roomid'],
          'betx' => $value['betx'],
          'sceneid' => $value['sceneid'],
          'starttime' => $value['starttime'],
          'endtime' => $value['endtime'],
          'billtime' => $value['billtime'],
          'gametype' => $value['gametype'],
          'currency' => $value['currency'],
          'totalbulletcost' => $value['totalbulletcost'],
          'totalfishcost' => $value['totalfishcost'],
          'profit' => $value['profit'],
          'totaljpcontribute' => $value['totaljpcontribute'],
          'totaljackpot' => $value['totaljackpot'],
          'totalfirstprize' => $value['totalfirstprize'],
          'remark' => $value['remark'],
          'devicetype' => $value['devicetype'],
          'totalweaponHit' => $value['totalweaponHit'],
          'totalcollection' => $value['totalcollection'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',$value['billtime']*1)),
        ];
      }
      else{
        $betUpdateStatus = BetHistoryAginHunterFish::where('sceneid',$value['sceneid'])->first();
        BetHistoryAginHunterFish::where('sceneid',$value['sceneid'])->update([
          'productid' => $value['productid'],
          'roomid' => $value['roomid'],
          'betx' => $value['betx'],
          'starttime' => $value['starttime'],
          'endtime' => $value['endtime'],
          'billtime' => $value['billtime'],
          'gametype' => $value['gametype'],
          'currency' => $value['currency'],
          'totalbulletcost' => $value['totalbulletcost'],
          'totalfishcost' => $value['totalfishcost'],
          'profit' => $value['profit'],
          'totaljpcontribute' => $value['totaljpcontribute'],
          'totaljackpot' => $value['totaljackpot'],
          'totalfirstprize' => $value['totalfirstprize'],
          'remark' => $value['remark'],
          'devicetype' => $value['devicetype'],
          'totalweaponHit' => $value['totalweaponHit'],
          'totalcollection' => $value['totalcollection'],
          'time_123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',$value['billtime']*1)),
        ]);
      }
    }
    if (count($results) > 0) BetHistoryAginHunterFish::insert($results);
    dd('Save History Best Hunter Fish startdate: '.$startdate.' - enddate: '.$enddate.' Success');
  }
  //thống kê game slot
  public function checkStatisticalAginSlot(Request $req){
    if($req->week){
      $this->checkStatisticalAginSlotWeek($req);
    }
    $game = 'Agin';
    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $mondayThisWeek = date('Y-m-d H:i:s');
    $timeInsert = date('Y-m-d H:i:s');
    //dd($mondayLastWeek,$mondayThisWeek,$timeInsert);
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = BetHistoryAginSlot::where('time_123betnow', '>=', $mondayLastWeek)->where('time_123betnow', '<', $mondayThisWeek)
      ->whereIn('flag',[0,1])->where('statistical', 0)->get();
    foreach($listTotalImportedWeek as $data){
      $prefix = config('urlAgin.agin_api');
      $userID = str_replace($prefix['prefix'], '', $data->username);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->account;
      $amount = $data->cus_account;
      if($amount < 0){
        $totalLoss += abs($amount);
      }elseif($amount >= 0){
        $totalWin += abs($amount);
      }else{
        continue;
      }
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
      $updateStatistical = BetHistoryAginSlot::where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical agin slot daily success');
  }

  public function checkStatisticalAginSlotWeek(Request $req){
    if($req->key != '123123123'){
      abort(404);
    }
    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Agin')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = BetHistoryAginSlot::whereIn('flag',[0,1])->where('statistical', 0)->get();
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));

    foreach($listTotalImportedWeek as $data){
      $prefix = config('urlAgin.agin_api');
      $userID = str_replace($prefix['prefix'], '', $data->username);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Agin')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->account;
      $amount = $data->cus_account;
      if($amount < 0){
        $totalLoss += abs($amount);
      }elseif($amount >= 0){
        $totalWin += abs($amount);
      }else{
        continue;
      }
      if($getStatistical){
        $totalBet += $getStatistical->statistical_TotalBet;
        $totalWin += $getStatistical->statistical_TotalWin;
        $totalLoss += $getStatistical->statistical_TotalLost;
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          /*->where('statistical_Time', '<', $mondayThisWeek)*/
          ->where('statistical_Game', 'Agin')
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
            'statistical_Game' => 'Agin',
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = BetHistoryAginSlot::where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical agin slot week success');
  }
  //thống kê hunter fish
  public function checkStatisticalAginHunterFish(Request $req){
    if($req->week){
      $this->checkStatisticalAginHunterFishWeek($req);
    }
    $game = 'Agin';
    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $mondayThisWeek = date('Y-m-d H:i:s');
    $timeInsert = date('Y-m-d H:i:s');
    //dd($mondayLastWeek,$mondayThisWeek,$timeInsert);
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = BetHistoryAginHunterFish::where('time_123betnow', '>=', $mondayLastWeek)->where('time_123betnow', '<', $mondayThisWeek)
      ->where('statistical', 0)->get();
    foreach($listTotalImportedWeek as $data){
      $prefix = config('urlAgin.agin_api');
      $userID = str_replace($prefix['prefix'], '', $data->username);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $game)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->totalbulletcost;
      $amount = $data->profit;
      if($amount < 0){
        $totalLoss += abs($amount);
      }elseif($amount >= 0){
        $totalWin += abs($amount);
      }else{
        continue;
      }
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
      $updateStatistical = BetHistoryAginHunterFish::where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical agin hunter fish daily success');
  }

  public function checkStatisticalAginHunterFishWeek(Request $req){
    if($req->key != '123123123'){
      abort(404);
    }
    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Agin')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = BetHistoryAginHunterFish::where('statistical', 0)->get();
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));

    foreach($listTotalImportedWeek as $data){
      $prefix = config('urlAgin.agin_api');
      $userID = str_replace($prefix['prefix'], '', $data->username);
      $user = User::find($userID);
      if(!$user){
        continue;
      }
      $getStatistical = DB::table('statistical_123betnow')->where('statistical_Game', 'Agin')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $totalBet = $data->totalbulletcost;
      $amount = $data->profit;
      if($amount < 0){
        $totalLoss += abs($amount);
      }elseif($amount >= 0){
        $totalWin += abs($amount);
      }else{
        continue;
      }
      if($getStatistical){
        $totalBet += $getStatistical->statistical_TotalBet;
        $totalWin += $getStatistical->statistical_TotalWin;
        $totalLoss += $getStatistical->statistical_TotalLost;
        $updateStatistical = DB::table('statistical_123betnow')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          /*->where('statistical_Time', '<', $mondayThisWeek)*/
          ->where('statistical_Game', 'Agin')
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
            'statistical_Game' => 'Agin',
            'statistical_Currency' => 3,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $timeInsert,
            'statistical_UpdateTime' => date('Y-m-d H:i:s'),
          ]);
      }
      $updateStatistical = BetHistoryAginHunterFish::where('id', $data->id)->update(['statistical'=>1]);
    }
    dd('update statistical agin hunter fish week success');
  }
  public function checkStatisticalEvo(Request $req){
    $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday last week'));
    $mondayThisWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
    //$mondayThisWeek =  strtotime('friday this week');
    $timeInsert = date('Y-m-d H:i:s', strtotime('friday last week'));
    //dd($req->week);
    if($req->week == 'this'){
      $mondayLastWeek =  date('Y-m-d H:i:s',strtotime('monday this week'));
      $mondayThisWeek = date('Y-m-d H:i:s');
      $timeInsert = $mondayThisWeek;
    }
    //dd($mondayLastWeek<$mondayThisWeek);
    $type = 'Evolution' ;

    //$deleteStatistical = DB::table('statistical_123betnow')->where('statistical_Game', $type)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('bet_history_evo')->Join('users', 'users.User_ID', 'bet_history_evo.user_id')->where('bet_history_evo.timestring', '>=', strtotime($mondayLastWeek))->where('bet_history_evo.timestring', '<', strtotime($mondayThisWeek))->where('Statistical',0)->get();

    foreach($listTotalImportedWeek as $data){
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      $user = User::find($data->user_id);
      if($req->week == 'this'){
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Game', $type)->first();
      }else{
        $getStatistical = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_Game', $type)->first();
      }
      //
      if($getStatistical){
        //dd($getStatistical);
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet += $data->betmoney;
        if($data->awardmoney - $data->betmoney > 0 ){
          $totalWin += $data->awardmoney - $data->betmoney ;
        }
        else if($data->awardmoney - $data->betmoney < 0){
          $totalLoss -= $data->awardmoney - $data->betmoney ;
          //echo $data->awardmoney - $data->betmoney ;
        }

        //dd($totalBet,$totalWin,$totalLoss);
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
        if($data->awardmoney - $data->betmoney > 0 ){
          $totalWin += $data->awardmoney - $data->betmoney ;
        }
        else if($data->awardmoney - $data->betmoney < 0){
          $totalLoss -= $data->awardmoney - $data->betmoney ;
          //echo $data->awardmoney - $data->betmoney ;
        }

        $totalBet += $data->betmoney ;
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



      $updateStatistical = DB::table('bet_history_evo')->where('id', $data->id)->update(['Statistical'=>1]);
    }
    dd('update statistical success');
  }
}
