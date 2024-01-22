<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;


use DB;

class GameBet extends Model
{
  protected $connection = 'mongodb';
  protected $collection = 'gamebets';
  protected $fillable = ['_id', 'GameBet_SubAccount', 'GameBet_SubAccountLevel', 'GameBet_SubAccountType', 'GameBet_SubAccountUser', 'GameBet_SubAccountEndBalance', 'GameBet_Type', 'GameBet_Symbol', 'GameBet_Amount', 'GameBet_Fund', 'GameBet_Status', 'GameBet_Log', 'GameBet_IB', 'GameBet_Notification', 'GameBet_CopyTrade', 'GameBet_datetime'];

  public $timestamps = true;

  public static function getTotalBetSpin($userID, $fromDate = null, $toDate = null)
  {
    $getEvolu = DB::table('show_history_evolution')->where('userId', $userID);
    $getSbobet = DB::table('show_history_sbobet')->where('userId', $userID);

    if ($fromDate) {
      $getEvolu = $getEvolu->where('time_123betnow', '>=', $fromDate);
      $getSbobet = $getSbobet->where('date', '>=', $fromDate);
    }
    if ($toDate) {
      $getEvolu = $getEvolu->where('time_123betnow', '<', $toDate);
      $getSbobet = $getSbobet->where('date', '<', $toDate);
    }

    $getSbobet = $getSbobet->select(DB::raw('COALESCE(SUM(`net_turnover_by_stake`), 0) as totalBet'),DB::raw('COALESCE(SUM(`member_wins`), 0) as netProfit'),DB::raw('COALESCE(SUM(IF(`member_wins` > 0,`member_wins`,0)), 0) as totalProfit'))
      ->groupBy('userId')->first();
    $getEvolu = $getEvolu->select(DB::raw('COALESCE(SUM(`evo_bet`), 0) as totalBet'),DB::raw('COALESCE(SUM(`evo_win`), 0) as netProfit'),DB::raw('COALESCE(SUM(IF(`evo_win` > 0,`evo_win`,0)), 0) as totalProfit'))
      ->groupBy('userId')->first();

    $sumVolume = 0;
    $sumProfit = 0;
    $netProfit = 0;
    if ($getSbobet) {
      $sumVolume = $sumVolume + $getSbobet->totalBet;
      $sumProfit = $sumProfit + $getSbobet->totalProfit;
      $netProfit = $netProfit + $getSbobet->netProfit;
    }
    if ($getEvolu) {
      $sumVolume = $sumVolume + $getEvolu->totalBet;
      $sumProfit = $sumProfit + $getEvolu->totalProfit;
      $netProfit = $netProfit + $getEvolu->netProfit;
    }
    $getInfo = ['totalBet' => $sumVolume, 'User_ID' => $userID];
    return (object)$getInfo;

  }

  public static function getTotalTradeBonus($userID,$fromDate = null,$toDate = null)
  {
    $arrUserVolume = [

    ];

    $arrUserProfit = [

    ];

    //set cấp cho từng ID
    if (isset($arrUserVolume[$userID])) {
      $totalBet = $arrUserVolume[$userID];
      $totalProfit = $arrUserProfit[$userID];
      return ['totalBet' => $totalBet, 'totalProfit' => $totalProfit];
    }

    $getEvolu = DB::table('show_history_evolution')->where('userId', $userID)->groupBy('userId');
    $getSbobet = DB::table('show_history_sbobet')->where('userId', $userID)->groupBy('userId');

    if ($fromDate) {
      $getEvolu = $getEvolu->where('time_123betnow', '>=', $fromDate);
      $getSbobet = $getSbobet->where('date', '>=', $fromDate);
    }
    if ($toDate) {
      $getEvolu = $getEvolu->where('time_123betnow', '<', $toDate);
      $getSbobet = $getSbobet->where('date', '<', $toDate);
    }

    $getSbobet = $getSbobet->selectRaw('SUM(net_turnover_by_stake) as totalBet,SUM(member_wins) as totalProfit')
      ->first();
    $getEvolu = $getEvolu->selectRaw('SUM(evo_bet) as totalBet,SUM(evo_win) as totalProfit')
      ->first();
    $sumVolume = 0;
    $sumProfit = 0;
    if ($getSbobet) {
      $sumVolume = $sumVolume + $getSbobet->totalBet;
      $sumProfit = $sumProfit + $getSbobet->totalProfit;
    }
    if ($getEvolu) {
      $sumVolume = $sumVolume + $getEvolu->totalBet;
      $sumProfit = $sumProfit + $getEvolu->totalProfit;
    }
    return ['totalBet' => $sumVolume, 'totalProfit' => $sumProfit];
  }

  public static function getTradeInfo($user, $fromDate, $toDate)
  {
    $static['total_trade'] = GameBet::getShowTotalBet($user->User_ID, $fromDate, $toDate)['totalBet'];
    $static['branch_trade'] = GameBet::TotalVolumeF($user->User_ID, 3, $fromDate, $toDate);
    return $static;
  }

  public static function getFMember($userID, $f)
  {
    $user = User::find($userID);
    $p2pTree = $user->User_Tree ?? $user->User_ID;
    $user_list = User::select('User_ID', 'User_Email', 'User_RegisteredDatetime', 'User_Parent', DB::raw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " AS f, User_Agency_Level, User_Tree"))
      ->whereRaw('User_Tree LIKE "' . $p2pTree . '%"')
      ->where('User_ID', '<>', $user->User_ID)
      ->orderBy('User_RegisteredDatetime', 'DESC')
      ->having('f', '<=', $f)
      ->get();
    $member = [];
    $userF = [];
    $lengthF = $f;
    for ($i = $f; $i >= 1; $i--) {
      $member[$i] = $user_list->where('f', $i)->count();
      $userF[$i] = $user_list->where('f', $i);
    }
    return [$member, $userF];
  }

  public static function TotalVolumeF($userID, $f, $fromDate, $toDate)
  {
    $userF = self::getFMember($userID, $f)[1];
    for ($i = 1; $i <= $f; $i++) {
      $total[$i] = 0;
    }
    //$total = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0];
    $testinv = [];
    foreach ($userF as $k => $v) {
      foreach ($v as $value) {
        //$lend = GameBet::getTotalBet($value->User_ID, $fromDate, $toDate);
        //$lend = $lend->totalBet ?? 0;
        $lend = GameBet::getShowTotalBet($value->User_ID, $fromDate, $toDate)['totalBet'];
        $total[$k] += $lend;
      }
    }
    for ($i = 1; $i <= $f; $i++) {
      $total[$i] = $total[$i] . ' EUSD';
    }
    return $total;
  }

  public static function getPackageUser($amount, $packageArray)
  {
    $packageUser = $packageArray[0];
    foreach ($packageArray as $p) {
      if ($p['price'] == $amount) {
        $packageUser = $p;
        break;
      }
    }
    return $packageUser;
  }

  public static function setAgencyUser($userID, $level)
  {
    if ($level == 0) {
      $cancelSet = DB::table('set_agency')->where('user', $userID)->where('status', 1)->update(['status' => -1]);
      return true;
    } elseif ($level <= 7) {
      $cancelSet = DB::table('set_agency')->where('user', $userID)->where('status', 1)->update(['status' => -1]);

      $setRank = DB::table('set_agency')
        ->insert([
          'user' => $userID,
          'level' => $level,
          'datetime' => date('Y-m-d H:i:s'),
          'status' => 1,
        ]);
      return true;
    } else {
      return false;
    }
  }

  public static function checkSetAgency($userID)
  {
    $checkSetRank = DB::table('set_agency')->where('user', $userID)->where('status', 1)->orderByDesc('level')->first();
    if (!$checkSetRank) {
      return false;
    }
    return $checkSetRank->level;
  }

  public static function getPackageUserAvailable($userID, $from, $to, $weekly = 0)
  {
    $user = User::find($userID);
    $packageList = self::getPackageAgency();
    $package = $packageList[0];
    $arrUserLevelUp = [
    ];
    $getF1Active = 0;
    $totalBetParent = 0;
    //ở hàm totalBuyAgency() trả về 999 - bỏ chính sách mua gói Agency
    $totalBuyAgency = GameBet::totalBuyAgency($userID);
    if ($totalBuyAgency < 10) {
      return $package;
    } else {
      $totalBetParent = GameBet::getTotalBet($userID, $from, $to);
      $totalBetParent = $totalBetParent->totalBet ?? 0;

      //      $getF1Active = GameBet::getF1Active($userID, $from, $to);
      //      $getF1Active = count($getF1Active);

      $salesActive = GameBet::getVolumeTradeMember($user, $from, $to, 0);
      //      dd($salesActive);
      foreach ($packageList as $p) {
        //        dd($salesActive, $p);
        //dd($userID, $from, $to, $totalBuyAgency, $totalBetParent, $salesActive, $packageList, $p);
        if ($totalBuyAgency >= $p['price'] && $totalBetParent >= $p['volume'] && $salesActive >= $p['sales']) {
          $package = $p;
        } else {
          break;
        }
      }
    }
    //set cấp cho từng ID
    if (isset($arrUserLevelUp[$userID])) {
      $package = $packageList[$arrUserLevelUp[$userID]];
      //			return $package;
    }
    $getCheckRank = GameBet::checkSetAgency($userID);
    if ($getCheckRank) {

      if($package['id'] < $getCheckRank){
        $package = $packageList[$getCheckRank];
      }

      //                return $package;
    }
    //insert theo tuần
    if ($weekly == 1) {

      $checkInserted = DB::table('package_weekly_123betnow')
        ->where('package_weekly_User', $userID)
        ->where('package_weekly_FromDate', '>=', $from)
        ->where('package_weekly_ToDate', '<=', $to)
        ->first();
      if (!$checkInserted) {
        if ($package['id'] < 6 || $getF1Active < 7) {
          $status = 1;
          //nếu có 1 tuần dưới diamond thì update tất cả status thành 1
          //                DB::table('package_weekly')->where('package_weekly_User', $userID)->where('package_weekly_Status', 0)->update(['package_weekly_Status' => 1]);
        } else {
          // ngc lại thì để status 0
          $status = 0;
        }
        $dataPackageWeek = [
          'package_weekly_User' => $userID,
          'package_weekly_FromDate' => $from,
          'package_weekly_ToDate' => $to,
          'package_weekly_Level' => $package['id'],
          'package_weekly_TotalBet' => $totalBetParent,
          'package_weekly_F1Active' => $getF1Active,
          'package_weekly_Status' => $status
        ];
        $insertPackage = DB::table('package_weekly_123betnow')->insert($dataPackageWeek);
      }
    }

    return $package;
  }

  public static function getPackageAgency()
  {
    $packageArray = [
      0 => ['id' => 0, 'name' => 'NONE', 'price' => 0, 'branch' => 0, 'f' => 0, 'percent' => 0, 'sales' => 0, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123Betnow/notification/notification_image_350205_6593beb1b3fb7.png'],
      1 => ['id' => 1, 'name' => 'BRONZE', 'price' => 0, 'branch' => 0, 'f' => 1, 'percent' => 0.002, 'sales' => 30000, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123Betnow/notification/notification_image_350205_6593beb1b3fb7.png'],
      2 => ['id' => 2, 'name' => 'SLIVER', 'price' => 0, 'branch' => 0, 'f' => 2, 'percent' => 0.003, 'sales' => 80000, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123Betnow/notification/notification_image_350205_6593beb1b3fb7.png'],
      3 => ['id' => 3, 'name' => 'GOLD', 'price' => 0, 'branch' => 0, 'f' => 3, 'percent' => 0.004, 'sales' => 300000, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123Betnow/notification/notification_image_350205_6593be407254d.png'],
      4 => ['id' => 4, 'name' => 'PLATINUM', 'price' => 0, 'branch' => 0, 'f' => 4, 'percent' => 0.005, 'sales' => 1000000, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123Betnow/notification/notification_image_350205_6593be680c7e4.png'],
      5 => ['id' => 5, 'name' => 'DIAMOND', 'price' => 0, 'branch' => 0, 'f' => 5, 'percent' => 0.006, 'sales' => 3600000, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123Betnow/notification/notification_image_350205_6593be0adc70b.png'],
      6 => ['id' => 6, 'name' => 'ROYALE', 'price' => 0, 'branch' => 0, 'f' => 6, 'percent' => 0.007, 'sales' => 6600000, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123Betnow/notification/notification_image_350205_6593bdb7ca14f.png'],
      7 => ['id' => 7, 'name' => 'CROWD', 'price' => 0, 'branch' => 0, 'f' => 7, 'percent' => 0.01, 'sales' => 999999999999, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123Betnow/notification/notification_image_350205_6593bdb7ca14f.png'],
    ];
    return $packageArray;
  }

  public static function getPackage()
  {


    $packageArray = [
      0 => ['name' => 'MEMBER', 'percent' => 0, 'f' => 1, 'volume' => 0, 'f1_active' => 0, 'image' => 'https://media.eggsbook.com/battle-game/level/sedie/0.png'],
      1 => ['name' => 'SILVER', 'percent' => 0.005, 'f' => 1, 'volume' => 200, 'f1_active' => 3, 'image' => 'https://media.eggsbook.com/battle-game/level/sedie/1.png'],
      2 => ['name' => 'TITAN', 'percent' => 0.008, 'f' => 2, 'volume' => 5000, 'f1_active' => 6, 'image' => 'https://media.eggsbook.com/battle-game/level/sedie/2.png'],
      3 => ['name' => 'GOLD', 'percent' => 0.01, 'f' => 3, 'volume' => 10000, 'f1_active' => 9, 'image' => 'https://media.eggsbook.com/battle-game/level/sedie/3.png'],
    ];
    return $packageArray;
  }

  public static function totalBuyAgency($userID)
  {
    //chính sách mới ko cần mua gói
    return 999;
    $packageList = self::getPackageAgency();
    $arrUserLevelUp = [

    ];
    //set cấp cho từng ID
    if (isset($arrUserLevelUp[$userID])) {
      $checkBuy = $arrUserLevelUp[$userID];
      return $checkBuy;
    }
    $getCheckRank = GameBet::checkSetAgency($userID);
    if ($getCheckRank) {
      $package = $packageList[$getCheckRank]['price'];
      //dd($package, $packageList, $getCheckRank);
      return $package;
    }
    $checkBuy = Money::where('Money_MoneyAction', 68)->where('Money_MoneyStatus', 1)->where('Money_User', $userID)->sum(DB::raw('ABS(Money_USDT)'));
    return $checkBuy;
  }

  public static function checkBuyAgency($userID)
  {
    $checkBuy = Money::where('Money_MoneyAction', 63)->where('Money_MoneyStatus', 1)->where('Money_User', $userID)->first();
    if ($checkBuy) {
      return 1;
    }
    return 0;
  }

  public static function NumberPackage($userID, $fromDate, $toDate, $weekly = 1)
  {
    $arrUserLevelUp = [

    ];
    //set cấp cho từng ID
    if (isset($arrUserLevelUp[$userID])) {
      $package = $arrUserLevelUp[$userID];
      return $package;
    }

    $checkInserted = DB::table('package_weekly_123betnow')
      ->where('package_weekly_User', $userID)
      ->where('package_weekly_FromDate', '>=', $fromDate)
      ->where('package_weekly_ToDate', '<=', $toDate)
      ->first();
    if ($checkInserted) {

      $package = $checkInserted->package_weekly_Level;

    } else {
      $getInfo = GameBet::getTotalBet($userID, $fromDate, $toDate);
      $package = 0;
      $totalBet = 0;
      $checkBuyAgency = GameBet::checkBuyAgency($userID);
      if (isset($getInfo->totalBet) && $checkBuyAgency && $getInfo->totalBet >= 200) {
        $package = 1;
        //Silver
        if ($getInfo->totalBet >= 400) {
          $getChildrenActive = GameBet::getF1Active($userID, $fromDate, $toDate)->count();
          if ($getChildrenActive >= 4) {
            $package = 2;
            //Gold
            if ($getInfo->totalBet >= 800 && $getChildrenActive >= 8) {
              $package = 3;
            }
          }
        }
      }
      if (isset($getInfo->totalBet) && $getInfo->totalBet > 0) {
        $totalBet = $getInfo->totalBet;
      }
      //insert theo tuần
      if ($weekly == 1) {
        $dataPackageWeek = [
          'package_weekly_User' => $userID,
          'package_weekly_FromDate' => $fromDate,
          'package_weekly_ToDate' => $toDate,
          'package_weekly_Level' => $package,
          'package_weekly_TotalBet' => $totalBet,
          'package_weekly_Status' => $status
        ];
        $insertPackage = DB::table('package_weekly_123betnow')->insert($dataPackageWeek);
      }
    }
    return $package;
  }

  public static function StaticBalanceBonus($userid,$fromday,$today){
    $balanceBonusToDay = Money::where("Money_User",$userid)
      ->where('Money_Time', '>=', $fromday)
      ->where('Money_Time', '<', $today)
      ->whereIn('Money_MoneyAction', [10])
      ->where('Money_MoneyStatus', 1)
      ->sum("Money_USDT");
    return $balanceBonusToDay;
  }

  public static function getTotalMoneySystem($userID, $fromDate = null, $toDate = null, $coin = 3, $action)
  {
    $getInfo = Money::where('Money_MoneyAction', $action)
      ->whereIn('Money_MoneyStatus', [0, 1])
      ->join('users', 'User_ID', 'Money_User')
      ->where('User_Tree', 'like', "%$userID%")
      ->where('Money_User', '!=', $userID)
      ->where('Money_Currency', $coin);
    if ($fromDate) {
      $getInfo = $getInfo->where('Money_Time', '>=', $fromDate);
    }
    if ($toDate) {
      $getInfo = $getInfo->where('Money_Time', '<', $toDate);
    }
    $getInfo = $getInfo->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')->first();
    return $getInfo->total;
  }
  public static function getTotalMoney($userID, $fromDate = null, $toDate = null, $coin = 3, $action)
  {
    $getInfo = Money::where('Money_MoneyAction', $action)->whereIn('Money_MoneyStatus', [0, 1])->where('Money_User', $userID)->where('Money_Currency', $coin);
    if ($fromDate) {
      $getInfo = $getInfo->where('Money_Time', '>=', $fromDate);
    }
    if ($toDate) {
      $getInfo = $getInfo->where('Money_Time', '<', $toDate);
    }
    $getInfo = $getInfo->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')->first();
    return $getInfo->total;
  }
  public static function getShowTotalBetSystem($userID, $fromDate = null, $toDate = null)
  {

    $getEvolu = DB::table('show_history_evolution')
      ->join('users', 'User_ID', 'userId')
      ->where('User_Tree', 'like', "%$userID%")
      ->where('User_ID', '!=', $userID);
    //      ->groupBy('userId');
    $getSbobet = DB::table('show_history_sbobet')
      ->join('users', 'User_ID', 'userId')
      ->where('User_Tree', 'like', "%$userID%")
      ->where('User_ID', '!=', $userID);
    //      ->groupBy('userId');

    if ($fromDate) {
      $getEvolu = $getEvolu->where('time_123betnow', '>=', $fromDate);
      $getSbobet = $getSbobet->where('date', '>=', $fromDate);
    }
    if ($toDate) {
      $getEvolu = $getEvolu->where('time_123betnow', '<', $toDate);
      $getSbobet = $getSbobet->where('date', '<', $toDate);
    }

    $getSbobet = $getSbobet->select(DB::raw('COALESCE(SUM(`net_turnover_by_stake`), 0) as totalBet'),DB::raw('COALESCE(SUM(`member_wins`), 0) as netProfit'),DB::raw('COALESCE(SUM(IF(`member_wins` > 0,`member_wins`,0)), 0) as totalProfit'))
      ->first();
    $getEvolu = $getEvolu->select(DB::raw('COALESCE(SUM(`evo_bet`), 0) as totalBet'),DB::raw('COALESCE(SUM(`evo_win`), 0) as netProfit'),DB::raw('COALESCE(SUM(IF(`evo_win` > 0,`evo_win`,0)), 0) as totalProfit'))
      ->first();
    $sumVolume = 0;
    $sumProfit = 0;
    $netProfit = 0;
    if ($getSbobet) {
      $sumVolume = $sumVolume + $getSbobet->totalBet;
      $sumProfit = $sumProfit + $getSbobet->totalProfit;
      $netProfit = $netProfit + $getSbobet->netProfit;
    }
    if ($getEvolu) {
      $sumVolume = $sumVolume + $getEvolu->totalBet;
      $sumProfit = $sumProfit + $getEvolu->totalProfit;
      $netProfit = $netProfit + $getEvolu->netProfit;
    }
    return ['totalBet' => $sumVolume, 'totalProfit' => $sumProfit, 'netProfit' => $netProfit];
  }
  public static function getShowTotalBet($userID, $fromDate = null, $toDate = null)
  {
    //set cấp cho từng ID
    if (isset($arrUserVolume[$userID])) {
      $totalBet = $arrUserVolume[$userID];
      $totalProfit = $arrUserProfit[$userID];
      return ['totalBet' => $totalBet, 'totalProfit' => $totalProfit];
    }

    $getEvolu = DB::table('show_history_evolution')->where('userId', $userID);
    $getSbobet = DB::table('show_history_sbobet')->where('userId', $userID);

    if ($fromDate) {
      $getEvolu = $getEvolu->where('time_123betnow', '>=', $fromDate);
      $getSbobet = $getSbobet->where('date', '>=', $fromDate);
    }
    if ($toDate) {
      $getEvolu = $getEvolu->where('time_123betnow', '<', $toDate);
      $getSbobet = $getSbobet->where('date', '<', $toDate);
    }
    //$getSbobet = $getSbobet->selectRaw('SUM(net_turnover_by_stake) as totalBet,SUM(member_wins) as totalProfit')->groupBy('userId')->first();
    //$getEvolu = $getEvolu->selectRaw('SUM(evo_bet) as totalBet,SUM(evo_win) as totalProfit')->groupBy('userId')->first();


    $getSbobet = $getSbobet->select(DB::raw('COALESCE(SUM(`net_turnover_by_stake`), 0) as totalBet'),DB::raw('COALESCE(SUM(`member_wins`), 0) as netProfit'),DB::raw('COALESCE(SUM(IF(`member_wins` > 0,`member_wins`,0)), 0) as totalProfit'))
      ->groupBy('userId')->first();
    $getEvolu = $getEvolu->select(DB::raw('COALESCE(SUM(`evo_bet`), 0) as totalBet'),DB::raw('COALESCE(SUM(`evo_win`), 0) as netProfit'),DB::raw('COALESCE(SUM(IF(`evo_win` > 0,`evo_win`,0)), 0) as totalProfit'))
      ->groupBy('userId')->first();

    $sumVolume = 0;
    $sumProfit = 0;
    $netProfit = 0;
    if ($getSbobet) {
      $sumVolume = $sumVolume + $getSbobet->totalBet;
      $sumProfit = $sumProfit + $getSbobet->totalProfit;
      $netProfit = $netProfit + $getSbobet->netProfit;
    }
    if ($getEvolu) {
      $sumVolume = $sumVolume + $getEvolu->totalBet;
      $sumProfit = $sumProfit + $getEvolu->totalProfit;
      $netProfit = $netProfit + $getEvolu->netProfit;
    }
    return ['totalBet' => $sumVolume, 'totalProfit' => $sumProfit, 'netProfit' => $netProfit];
  }

  public static function getTotalBet($userID, $fromDate = null, $toDate = null)
  {

    $arrUserLevelUp = [

    ];
    //set cấp cho từng ID
    if (isset($arrUserLevelUp[$userID])) {
      $getInfo['User_ID'] = $userID;
      $getInfo['totalBet'] = $arrUserLevelUp[$userID];
      return (object)$getInfo;
    }

    $getInfo = DB::table('statistical_123betnow')
      ->join('users', 'user_ID', 'statistical_User')
      ->where('statistical_Currency', 3)
      ->where('statistical_User', $userID)
      ->selectRaw('SUM(statistical_TotalBet) as totalBet, User_ID , User_Email, User_Tree')
      ->groupBy('statistical_User');

    if ($fromDate) {
      $getInfo = $getInfo->where('statistical_Time', '>=', $fromDate);
    }
    if ($toDate) {
      $getInfo = $getInfo->where('statistical_Time', '<', $toDate);
    }
    $getInfo = $getInfo->first();
    return $getInfo;
  }

  public static function getF1Active($userID, $fromDate, $toDate)
  {
    $getF1Active = DB::table('statistical_123betnow')
      ->join('users', 'User_ID', 'statistical_User')
      ->whereRaw("User_Parent = $userID")
      ->where('statistical_Currency', 3)
      ->where('statistical_Time', '>=', $fromDate)
      ->where('statistical_Time', '<', $toDate)
      ->selectRaw('SUM(statistical_TotalBet) as totalBet, User_ID , User_Email, User_Tree')
      ->groupBy('statistical_User')
      ->having('totalBet', '>=', 200)
      ->get();
    return $getF1Active;
  }

  public static function profitUser($userID, $fromDate = null, $toDate = null)
  {
    $total = DB::table('statistical_123betnow')
      ->join('users', 'User_ID', 'statistical_User')
      ->where('User_ID', $userID)
      ->where('statistical_Currency', 3)
      ->orderByDesc('statistical_Time');


    if ($fromDate) {
      $total = $total->where('statistical_Time', '>=', $fromDate);
    }
    if ($toDate) {
      $total = $total->where('statistical_Time', '<', $toDate);
    }
    $total = $total->sum(DB::raw('statistical_TotalWin - statistical_TotalLost'));

    return $total;
  }

  public static function getVolumeTradeMember($user, $fromDate, $toDate, $f = 5)
  {

    $getF1Active = DB::table('statistical_123betnow')
      ->join('users', 'User_ID', 'statistical_User')
      ->where('User_Tree', 'LIKE', $user->User_Tree . ',%')
      ->where('statistical_Currency', 3)
      ->where('statistical_Time', '>=', $fromDate)
      ->where('statistical_Time', '<', $toDate)
      //->where('statistical_TotalBet', '>=', 200)
      ->selectRaw('SUM(statistical_TotalBet) as totalBet, User_ID , User_Email, User_Tree')
      ->groupBy('statistical_User');
    if ($f > 0) {
      $getF1Active = $getF1Active->whereRaw(DB::raw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " <= " . $f));
    }
    //    dd($getF1Active->get());
    $getF1Active = $getF1Active->get();
    return $getF1Active->sum('totalBet');
  }

  public static function getProfitPending($user, $fromDate, $toDate)
  {
    $mondayLastWeek = $fromDate;
    $mondayThisWeek = $toDate;
    $currency = 3;
    //lấy những user có đánh tuần trước
    $getUserBet = DB::table('statistical_123betnow')
      ->join('users', 'User_ID', 'statistical_User')
      ->where('User_Tree', 'LIKE', $user->User_Tree . ',%')
      // 						->where('statistical_User', 715444)
      ->where('statistical_Time', '>=', $mondayLastWeek)
      ->where('statistical_Time', '<', $mondayThisWeek)
      ->where('statistical_Currency', $currency)
      ->selectRaw('SUM(statistical_TotalBet) as totalBet, User_ID , User_Email , User_Tree')
      ->groupBy('statistical_User')
      ->get();
    //dd($getUserBet);
    $timeToday = strtotime($mondayThisWeek);
    $action = 65;
    $PackageAgency = GameBet::getPackageAgency();
    $arrayInsert = [];
    $arrayInsertSameLevel = [];
    $percentSameLevel = 0.001;
    $totalPending = 0;
    //chưa chặn cron chạy
    foreach ($getUserBet as $item) {
      $total_play_game = $item->totalBet;

      $userTree = $item->User_Tree;
      $usersArray = explode(',', $userTree);
      $usersArray = array_reverse($usersArray);
      //% đã nhận được của parent
      $percentCurrent = 0;
      //chạy từ F1-F8
      for ($i = 1; $i <= 3; $i++) {
        if (!isset($usersArray[$i])) {
          continue;
        }
        $info_parent = User::find($usersArray[$i]);
        if (!$info_parent) {
          continue;
        }
        $totalBuyAgency = GameBet::totalBuyAgency($info_parent->User_ID);
        if ($totalBuyAgency < 10) {
          continue;
        }
        $getPackageParent = GameBet::getPackageUser($totalBuyAgency, $PackageAgency);
        //lấy dữ liệu của gói ra
        $dataInterest = $getPackageParent;
        if ($dataInterest['f'] < $i) {
          continue;
        }
        //số % nhận được = số % package của parent - số % của user con
        $percentInterest = $dataInterest['percent'] - $percentCurrent;
        //update percent parent
        $percentCurrent = $dataInterest['percent'];
        //thấp cấp hơn => ko trả
        if ($percentInterest == 0) {
          if ($getPackageParent < 6) {
            continue;
          }
          // hoa hồng đồng cấp
          if (!isset($amountInterest)) {
            $amountInterest = $total_play_game * $percentInterest;
            $amountInterest = $amountInterest * $percentSameLevel;
          } else {
            $amountInterest = $amountInterest * $percentSameLevel;
          }
          if ($info_parent->User_ID == $user->User_ID) {
            $totalPending += $amountInterest;
          }
          $userChild = $info_parent;
          continue;
        } elseif ($percentInterest < 0) {
          continue;
        }
        $userChild = $info_parent;
        $amountInterest = $total_play_game * $percentInterest;
        $amountSameLevel = $amountInterest;

        if ($info_parent->User_ID == $user->User_ID) {
          $totalPending += $amountInterest;
        }
        continue;
      }
    }
    return $totalPending;
  }
}
