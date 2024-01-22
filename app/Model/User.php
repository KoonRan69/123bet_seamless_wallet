<?php

namespace App\Model;

use Laravel\Passport\HasApiTokens;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

use App\Model\Money;
use App\Model\Investment;
use App\Model\Eggs;
use Auth;
use App\Jobs\SendTelegramJobs;
class User extends Authenticatable
{
  use HasApiTokens, Notifiable;

  /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
  protected $fillable = [
    'User_Name', 'User_Email', 'User_Password', 'Provide_Key_API', 'Provide_IP_Address',
  ];

  /**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
  protected $hidden = [
    'User_Password', 'User_Token', 'User_OTP', 'remember_token', 'User_Log','User_WM_Password','User_Agin_Password','User_Evo_Password','User_AWC_Password','User_Sbobet_Password','User_Sbobet_Password_Lucky','User_Sbobet_Password_Infinity','otp_w','otp_transfer','otp_lucky'
  ];

  protected $primaryKey = 'User_ID';
  protected $keyType = 'string';
  public $timestamps = false;

  public function eggs(){
    return $this->hasMany('App\Model\Eggs', 'ID', 'User_ID');
  }

  public function Invest()
  {
    return $this->hasMany('App\Model\Investment', 'investment_User')->where('investment_Status', 1);
  }

  public function AddressDeposit()
  {
    return $this->hasMany('App\Model\Wallet', 'Address_User')->orderBy('Address_Currency');
  }

  public function Level()
  {
    return $this->belongsTo('App\Model\UserLevel', 'User_Agency_Level');
  }

  public function Money(){
    return $this->hasMany('App\Model\Money', 'Money_User', 'User_ID');
  }

  public function getAuthPassword()
  {
    return $this->User_Password;
  }
  public static function getBalanceTicket($userId){
    $balance = DB::table('voucher')->where('User_ID', $userId)->where('status', 0)->get()->count();
    return $balance;
  }
  public static function getBalanceVoucher($userId){

    $MainBalance = DB::table('BalanceVoucherConfirm')->where('User_ID',$userId)->sum('Balance');
    return $MainBalance*1;
  }
  static function updateVoucherBalance($user,$amount = 0){
    $currency = 17;
    $money = Money::where('Money_User', $user)->whereIn('Money_MoneyStatus', [0,1])->where('Money_Currency', $currency);
    $money = $money->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')->value('total');
    $MainBalance = DB::table('BalanceVoucherConfirm')->where(array('User_ID' => $user))->first();
    if(!$MainBalance){
      $MainBalance = DB::table('BalanceVoucherConfirm')->insert(array('User_ID' => $user, 'Balance'=>$money));
    }else{
      $MainBalance = DB::table('BalanceVoucherConfirm')->where(array('User_ID' => $user))->update(['Balance'=>$money]);
    }

    return $money*1;
  }
  public static function getQuantityF($userParent, $f){
    $getF = User::where('User_Tree', $userParent->User_Tree.',%')
      ->whereRaw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " = ". $f)
      ->orderBy('User_RegisteredDatetime')
      ->get();
    return $getF;
  }

  public static function getQuantityF1($userParent, $limit){
    $getF1 = User::where('User_Parent', $userParent->User_ID)->orderBy('User_RegisteredDatetime')->limit($limit)->get();
    return $getF1;
  }

  public static function checkUserInBranchAvailable($user, $getF1){
    $arrParent = explode(',', $user->User_Tree);
    $arrParent = array_reverse($arrParent);
    $checkUserInBranch = $getF1->whereIn('User_ID', $arrParent);
    if(count($checkUserInBranch)){
      return true;
    }else{
      return false;
    }
  }

  public static function checkBlockBalance($userID, $action = 7){
    $user = User::find($userID);
    $balance = User::getBalance($userID, 3);
    if($balance < 0 || $user->User_Block == 1){

      DB::table('oauth_access_tokens')
        ->where('user_id', $userID)
        ->delete();
      $block = User::where('User_ID', $userID)->update(['User_Block' => 1]);

      $action_list = DB::table('moneyaction')->pluck('MoneyAction_Name', 'MoneyAction_ID')->toArray();
      $message = "<b> $userID ".$action_list[$action]." </b>\n"
        . "ID: <b>$user->User_ID</b>\n"
        . "EMAIL: <b>$user->User_Email</b>\n"
        . "ACTION: <b>".$action_list[$action]."</b>\n"
        . "Time: <b>".date('d-m-Y H:i:s')."</b>\n";
      dispatch(new SendTelegramJobs($message, -444849017));
      return true;
    }
    return false;
  }

  public static function getEggsUser($userID){
    $getEggs = Eggs::where('ActiveTime', '>=',0)->where('Status', 1)->where('Owner', (string)$userID)->select('_id', 'ActiveTime', 'BuyDate', 'ID', 'status')->get();
    return $getEggs;
  }

  public static function getMoneyActivesUser($userID){
    $moneys = Money::where('Money_User', $userID)->where('Money_MoneyStatus', 1)->whereIn('Money_MoneyAction', [26,27,28,29,30,36])->selectRaw('SUM(IF(Money_Currency = 3, Money_USDT, 0)) as EUSD, SUM(IF(Money_Currency = 9, Money_USDT, 0)) as GOLD')->first(); 
    return $moneys;
  }

  public static function getTotalEggActive($userID){
    $totalEggActive = Eggs::whereIn('Owner', $userID)->where('ActiveTime', '>=', 0)->get();
    return $totalEggActive;
  }

  public static function getTotalEggActiveBranch($user){
    $getEggActive = Eggs::where('Owner', $user)->where('ActiveTime', '>', 0)->orderBy('ActiveTime', 1)->first();
    if(!$getEggActive){
      return [];
    }
    $getChildren = User::where('User_Tree', 'LIKE', "%$user%")->pluck('User_ID')->toArray();
    $getEggs = Eggs::whereIn('Owner', $getChildren)->where('ActiveTime', '>', $getEggActive->ActiveTime)->get();
    return $getEggs;
  }

  public static function checkLevelUser($userID){
    $getEggActive = Eggs::where('Owner', (string)$userID)->where('ActiveTime', '>', 0)->orderBy('ActiveTime')->first();
    // dd($getEggActive);
    if(!$getEggActive){
      return 0;
    }
    $getDirect = User::where('User_Parent', $userID)->select('User_ID', 'User_Tree')->get();
    $arrayEggsTree = [];
    foreach($getDirect as $child){
      $getEggsBranch = self::getTotalEggActiveBranch($child->User_ID);
      // dd($getEggsBranch);
      $countEggsBranch = count($getEggsBranch);
      if($countEggsBranch){
        $arrayEggsTree[] = $countEggsBranch;
      }
    }
    // dd($getDirect);
    arsort($arrayEggsTree);
    $totalEggs2Branch = 0;
    $i = 0;
    foreach($arrayEggsTree as $sumEgg){
      $totalEggs2Branch += $sumEgg;
      $i++;
      //chỉ tính 2 nhánh nên $i > 1 sẽ break
      if($i > 1){
        break;
      }
    }
    if($totalEggs2Branch < 200){
      return 0;
    }
    $level = 1;
    if($totalEggs2Branch >= 500){
      $level = 2;
    }
    if($totalEggs2Branch >= 1000){
      $level = 3;
    }
    if($totalEggs2Branch >= 1500){
      $level = 4;
    }
    if($totalEggs2Branch >= 2000){
      $level = 5;
    }
    $arrLevel[] = $level;
    $parentTree = User::find($userID);
    if(!$parentTree){
      return 0;
    }
    if($level>=1 && $level > $parentTree->User_Agency_Level){
      DB::table('time_level_up')->insert([
        'Time_Leve_Up_User' => $parentTree->User_ID,
        'Time_Leve_Up_Level' => $level,
        'Time_Leve_Up_Time' => date('Y-m-d H:i:s'),
      ]);
      //gửi mail
      User::where('User_ID', $parentTree->User_ID)->update(['User_Agency_Level'=>$level]);
    }
    return $level;
  }

  public static function getInfo($userID)
  {
    $result = DB::table('users')
      ->where('User_ID', $userID)
      ->first();
    return $result;
  }

  public static function InsertRow($UserID, $username, $password, $passwordUnHash, $parents = 0, $tree)
  {
    $user = new User();

    $user->User_ID = $UserID;
    $user->User_Name = $username;
    $user->User_Password = $password;
    $user->User_PasswordUnHash = $passwordUnHash;
    $user->User_Parent = $parents;
    $user->User_RegisteredDatetime = date('Y-m-d H:i:s');
    $user->User_Level = 0;
    $user->User_Status = 1;
    $user->User_Tree = $tree . ',' . $UserID;
    if ($user->save()) {
      return true;
    }
    return false;
  }

  public static function checkBonusPoint($userID)
  {
    $getChild = User::join('profile', 'User_ID', 'Profile_User')->where('User_Parent', $userID)->where('Profile_Status', 1)->select('User_ID')->get()->count();
    $totalBonus = Money::where('Money_User', $userID)->where('Money_MoneyAction', 12)->select('Money_User')->get()->count();
    $amountChildBonus = 10;
    $amountPoint = self::floorp($getChild / $amountChildBonus, 0);
    $amount = $amountPoint - $totalBonus;
    if ($amount >= 1) {

      $currencyToken = 11;
      $money = new Money();
      $money->Money_User = $userID;
      $money->Money_USDT = $amount;
      $money->Money_Time = time();
      $money->Money_Comment = 'Bonus Point Referral ' . ($amount * $amountChildBonus) . ' Children';
      $money->Money_Currency = $currencyToken;
      $money->Money_MoneyAction = 12;
      $money->Money_Address = '';
      $money->Money_Rate = 0;
      $money->Money_MoneyStatus = 1;
      $money->save();
      //Update Balance
      $updateblance = User::updateBalance($userID, $currencyToken, $amount);
      return true;
    }
    return false;
  }

  public static function getF1($userID)
  {
    $userList = User::where('User_Parent', $userID)->select('User_ID', 'User_Level', 'User_Agency_Level')->get();
    return $userList->toArray();
  }
  public static function getF1_1($userID)
  {
    $userList = User::where('User_Parent', $userID)->select('User_ID', 'User_Level')->get();
    return $userList->toArray();
  }

  public static function getFMember($userID, $f)
  {
    $user = User::find($userID);
    $user_list = User::select('Profile_Status', 'User_ID', 'User_Email', 'User_RegisteredDatetime', 'User_Parent', DB::raw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " AS f, User_Agency_Level, User_Tree"))
      ->leftJoin('profile', 'Profile_User', 'User_ID')
      ->whereRaw('User_Tree LIKE "' . $user->User_Tree . '%"')
      ->where('User_ID', '<>', $user->User_ID)
      ->orderBy('User_RegisteredDatetime', 'DESC')
      ->having('f', '<=', $f)
      ->get();
    $member = [];
    $userF = [];
    // 
    $lengthF = $f;

    for ($i = $f; $i >= 1; $i--) {

      $member[$i] = $user_list->where('f', $i)->count();
      $userF[$i] = $user_list->where('f', $i);
    }


    // dd($member,$userF[1][0]->User_ID);

    return [$member, $userF];
  }
  public static function getMember($userID)
  {
    $member = User::where('User_Parent', $userID)->get();
    return $member;
  }
  public static function TotalVolumeF($userID, $f)
  {

    $userF = self::getFMember($userID, $f)[1];
    $total = [1 => 0, 2 => 0, 3 => 0];
    $testinv = [];
    foreach ($userF as $k => $v) {
      foreach ($v as $value) {
        $invest = DB::table('sonix_log')->where('user', $value->User_ID)->where('type', 'debit')->sum(DB::raw('amount'));

        $total[$k] +=  $invest;
      }
    }
    return $total;
  }
  public static function getBalanceLucky($userID, $coin = 18){
    $balancetemp = 0;
    $time = 0;
    $money = Money::where('Money_User', $userID)->whereIn('Money_MoneyStatus', [0,1])->where('Money_Time', '>', $time)->where('Money_Currency', $coin);
    $money = $money->selectRaw("SUM(IF(multiplay_pool = '0xDC9272e5D1511Dc406F8c26c8F05FB8B13f09133', Money_USDT, 0)) as SolarSystem, SUM(IF(multiplay_pool = '0xed5dbdc82fcfb151cc8bb7af370ef9a7b8cc8d89', Money_USDT, 0)) as infinity")->first();
    $data = [
      'SolarSystem' => ['addressPool' => '0xDC9272e5D1511Dc406F8c26c8F05FB8B13f09133', 'balance' => $money->SolarSystem, 'poolName' => 'Pool Solar System'],
      'Infinity' => ['addressPool' => '0xed5dbdc82fcfb151cc8bb7af370ef9a7b8cc8d89', 'balance' => $money->infinity, 'poolName' => 'Pool Infinity'],
    ];
    return $data;
  }
  public static function getBalance($userID, $coin = 3){
    $balancetemp = 0;
    $time = 0;
    $userBalance = DB::table('userBalance')->where('user', $userID)->where('currency', $coin)->first();

    if($userBalance){
      $balancetemp += $userBalance->balance;
      $time = strtotime($userBalance->update_at);
    }

    $money = Money::where('Money_User', $userID)->whereIn('Money_MoneyStatus', [0,1])->where('Money_Currency', $coin);

    if($coin == 10){
      //$dayStart = strtotime('2023-04-20 00:00:00');
      //$dayEnd = strtotime('2023-05-28 00:00:00');
      //$money = $money->where('Money_Time', '>=', $dayStart)->where('Money_Time', '<', $dayEnd);
    }else{
      //$money =$money->where('Money_Time', '>', $time);
    }
    $money =$money->where('Money_Time', '>', $time);

    $money = $money->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')->first();

    $balancetemp += $money->total*1;

    if($balancetemp < 0){
      $user = User::find($userID);
      if($user->User_Block == 1){
        return $balancetemp;
      }
      if($user->User_Lock_Withdraw == 1 && $user->User_Lock_Transfer == 1){
        return $balancetemp;
      }
      /*
            */
      DB::table('oauth_access_tokens')
        ->where('user_id', $userID)
        ->delete();
      $block = User::where('User_ID', $userID)->update(['User_Block' => 1, 'User_Lock_Withdraw'=>1, 'User_Lock_Transfer'=> 1]);
      //$action_list = DB::table('moneyaction')->pluck('MoneyAction_Name', 'MoneyAction_ID')->toArray();
      $message = "<b> $userID Minus Balance EUSD </b>\n"
        . "PROJECT: <b>123BetNow</b>\n"
        . "ID: <b>$user->User_ID</b>\n"
        . "EMAIL: <b>$user->User_Email</b>\n"
        . "BALANCE: <b>$balancetemp</b>\n"
        . "Time: <b>".date('d-m-Y H:i:s')."</b>\n";
      dispatch(new SendTelegramJobs($message, -398297366));
      return $balancetemp;

    }
    return $balancetemp*1;
  }


  public static function updateBalance($userID, $coin, $amount)
  {
    $user = User::find($userID);
    if (!$user) {
      return false;
    }
    $arrBalance = [
      5 => 'USDT',
      8 => 'Token',
      10 => 'Income',
      11 => 'Point',
      13 => 'AvailableUSDT',
      3 => 'EUSD'
    ];
    if (!isset($arrBalance[$coin])) {
      return false;
    }
    $symbolCoin = $arrBalance[$coin];
    $symbol = 'User_Balance' . $symbolCoin;
    $updateBalance = User::where('User_ID', $userID)->increment($symbol, $amount);
    if ($updateBalance) {
      return true;
    }
    return false;
    /*
		$amountBefore = $user->$symbol;
		$amountAfter = $user->$symbol + $amount;
		$user->$symbol = $amountAfter;
		$user->save();
		return $user->$symbol;
		*/
  }

  public static function getMatrixInfo($user)
  {
    $staticMoney = Money::whereIn('Money_MoneyAction', [19, 20, 24])->where('Money_MoneyStatus', 1)->where('Money_User', $user->User_ID)->select('Money_USDT', 'Money_MoneyAction')->get();
    // 		dd($staticMoney);
    $info['total_income'] = number_format($staticMoney->sum('Money_USDT'), 2);
    $info['direct'] = number_format($staticMoney->where('Money_MoneyAction', 24)->sum('Money_USDT'), 2);
    $info['indirect'] = number_format($staticMoney->whereIn('Money_MoneyAction', [20])->sum('Money_USDT'), 2);
    $staticMember = User::select('User_ID', 'User_Email', 'User_RegisteredDatetime', 'User_Parent', DB::raw("(CHAR_LENGTH(User_MatrixTree)-CHAR_LENGTH(REPLACE(User_MatrixTree, ',', '')))-" . substr_count($user->User_MatrixTree, ',') . " AS f, User_Agency_Level, User_MatrixTree, User_PositionMatrixTree, User_MatrixParent"))
      ->where('User_MatrixTree', 'like', '%' . $user->User_ID . '%')
      ->where('User_ID', '!=', $user->User_ID)
      ->get();
    $info['total_member'] = $staticMember->count();
    $info['count_children'] = $staticMember->where('User_MatrixParent', $user->User_ID)->count();
    $info['matrix_status'] = $user->User_MatrixStatus == 1 ? true : false;
    $info['balance_matrix'] = $user->User_BalanceMatrix;
    $info['matrix_time_join'] = $user->User_MatrixTimeJoin;
    $arrPercent = [1 => 0.2, 2 => 0.03, 3 => 0.03, 4 => 0.03, 5 => 0.03, 6 => 0.02, 7 => 0.02, 8 => 0.02, 9 => 0.02, 10 => 0.02];
    $staticLevel = [];
    for ($i = 1; $i <= 10; $i++) {
      $userIDChild = [];
      $memberFloor = $staticMember->where('f', $i);
      $staticLevel[$i]['count_member_floor'] = count($memberFloor);
      $staticLevel[$i]['count_sales_floor'] = ($info['count_children'] >= $i) ? 50 * count($memberFloor) * $arrPercent[$i] : 0;
      /*
			foreach($staticLevel[$i]['count_member_floor'] as $member){
				$userIDChild[] = $member->User_ID;
			}
*/
      /*
			$staticLevel[$i]['count_sales_floor'] = Money::whereIn('Money_MoneyAction', [19,20,24])
														->where('Money_MoneyStatus', 1)
														->where('Money_User', $user->User_ID)
														->sum('Money_USDT');
*/

      // 			dd($staticMember, $staticLevel[$i]['count_member'], $userIDChild);
    }
    $info['floor'] = $staticLevel;
    return $info;
  }	
  /**
	 * getHistoryLogin
	 *
	 * @param  mixed $userID
	 * @return list history login user by ip and time
	 */
  public static function getHistoryLogin($userID, $limit){
    $historyLogin = DB::table('log_user')->where('user', $userID)->where('comment', 'like', '%Login%')->orderByDesc('datetime')->limit(15)->get();

    return $historyLogin;
  }

  public static function getTransacstionHistory($userID, $page){
    $historyTransaction = Money::where('Money_User', $userID)->select('Money_ID', 'Money_User', 'Money_USDT', 'Money_USDTFee', 'Money_Time', 'Money_Comment', 'Money_MoneyAction', 'Money_MoneyStatus','Money_Address','Money_Currency', 'Money_TXID', 'Money_Confirm', 'Money_Rate', 'Money_CurrentAmount')->whereIn('Money_MoneyStatus', [0,1])->orderByDesc('Money_ID')->paginate($page);

    return $historyTransaction;
  }


  public static function searchTransacstionHistory($userID, $page , $query = []){
    $historyTransaction = Money::where('Money_User', $userID);
    if(count($query) > 0){
      if($query['fromdate'] != null){
        $historyTransaction = $historyTransaction->whereDate('Money_Time', '>=', strtotime($query['fromdate']));
      }
      if($query['todate'] != null){
        $historyTransaction = $historyTransaction->whereDate('Money_Time', '<=', $query['todate']);
      }else{
        $historyTransaction = $historyTransaction->whereDate('Money_Time', '<', $query['todate'])->whereDate('Money_Time', '>', $query['fromdate']);
      }

    }
    $historyTransaction = $historyTransaction->paginate($page);
    return $historyTransaction;
  }

  public static function RandonIDUser(){

    $id = rand(100000, 999999);
    $user = User::where('User_ID', $id)->first();
    if (!$user) {
      return $id;
    } else {
      return $this->RandonIDUser();
    }
  }
}
