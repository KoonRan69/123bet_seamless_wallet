<?php
namespace App\Http\Controllers\API;
use App\Exports\InvesmentExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Money;
use App\Model\Log;
use App\Model\Profile;
use App\Model\LogAdmin;
use App\Model\LogUser;
use App\Model\Eggs;
use App\Model\Foods;
use App\Model\Pools;
use App\Model\subAccount;
use App\Model\GameListV2;

use Illuminate\Support\Facades\Auth;
use App\Exports\UserExport;
use App\Exports\WalletExport;
use App\Model\Investment;
use App\Model\MUser;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendMailJobs;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller{
  /**
     * getDashboard
     *
     * @return void
     */

  //API Kingca168
  public function listGameV2(Request $req){
    $listGames = GameListV2::with('gameChildens')->where('show',1)->where('parent',0)->get();
    return $this->response(200,$listGames);
  }
  //API Kingca168

  public function getDashboard(Request $req){

    $user = Auth::user();
    $info = User::find($user->User_ID);
    $user_id = $user->User_ID;
    $totalMember = [];
    $tranHistory = [];
    $loginHistory = [];
    $balance['EUSD']  = User::getBalance($user_id, 3);
    $balance['USD']  = User::getBalance($user_id, 8);
    $totalMember = $this->totalMember($user_id);
    $checkAuth = DB::table('google2fa')->where('google2fa_ID', $user->User_ID)->first();
    $auth = false;
    if(!$checkAuth && $checkAuth == null){
      $auth = false;
    }
    else{
      $auth = true;
    }
    $totalSub = 0;
    $tranHistory = $this->getTransactionHistory($user_id);
    $loginHistory = $this->getHistoryLogin($user_id);
    $checkKYC = DB::table('profile')->where('Profile_User', $user->User_ID)->first();
    $kycStatus = false;
    $passport = '';
    $passport_image = '';
    $passport_image_selfie = '';
    if(!$checkKYC && $checkKYC == null){
      $kycStatus = false;
    }
    else{
      $kycStatus = $checkKYC->Profile_Status;
      $passport = $checkKYC->Profile_Passport_ID;
      $passport_image = 'https://media.123betnow.net/'.$checkKYC->Profile_Passport_Image;
      $passport_image_selfie = 'https://media.123betnow.net/'.$checkKYC->Profile_Passport_Image_Selfie;
    }
    $statusPassword = false;
    if($user->User_EmailActive == 1){
      $activeEmail = true;
    }else{
      $activeEmail = false;
    }
    if($user->User_Password != null){
      $statusPassword = true;
    }
    $topLeader = 1;
    if($user->User_Level == 1){
      $topLeader = 1;
    }
    $connectAddressMetamask = false;
    if($user->User_WalletAddress != null){
      $connectAddressMetamask = true;
    }
    if($info->User_AWC_Password == NULL){
      $ae_sexy = 0;
    }else{
      $ae_sexy = 1;
    }
    if($info->User_Sbobet_Password == NULL){
      $sbobet = 0;
    }else{
      $sbobet = 1;
    }
    $SbobetLuckySolar = 0;
    $SbobetLuckyInfinity = 0;
    if($info->User_Sbobet_Password_Lucky)$SbobetLuckySolar = 1;
    if($info->User_Sbobet_Password_Infinity)$SbobetLuckyInfinity = 1;
    $data = [
      'User_Email_LuckyHero' => $user->User_Email_LuckyHero,
      'User_Email_LuckyHero_Active' => $user->User_Email_LuckyHero_Active,
      'topLeader' => $topLeader,
      'balance'=> array(
        'EUSD' => $balance['EUSD'],
        'USD' => $balance['USD'],
        //'Casino' => 0,
        //'SportBook' => 0,
        //'BinanryOption' => 0,
        //'SkyGame' => 0,
        //'WM555' => 0,
        //'AGINSPOSTBOOK' => 0,
        'EVOLUTION' => 0,
        //'AESEXY' => 0,
      ),
      'game_status'=> array(
        'BinanryOption' => 0,
        //'WM555' => 0,
        'AGINSPOSTBOOK' => $info->User_Agin,
        'EVOLUTION' => $info->User_Evo,
        'AESEXY' => $ae_sexy,
        'Sbobet' => $sbobet,
        'SbobetLuckySolar' => $SbobetLuckySolar,
        'SbobetLuckyInfinity' => $SbobetLuckyInfinity,
      ),
      'info'=>array(	'id'=>(int)$info->User_ID,
                    'username'=>$info->User_Name,
                    'email'=>$info->User_Email,
                    'phone'=>$info->User_Phone,
                    'walletAddress'=>$user->User_WalletAddress,
                    'auth'=> $auth,
                    'level'=>$info->User_Level,
                    'agency'=>$info->User_Level_Agency,
                    'statusPassword'=> $statusPassword,
                    'activeEmail'=> $activeEmail,
                    'connectAddressMetamask'=>$connectAddressMetamask,
                   ),
      'url_game'=>array(
        'base_url' => 'https://sys.123betnow.net/',
        'Casino' => 'https://casino.123betnow.net/',
        'SportBook' => 'https://sportBook.123betnow.net/',
        'SkyGame' => 'https://fish.123betnow.net/',
        //'WM555' => 'https://wm111.net/',
        'AGINSPOSTBOOK' => 'https://gi.123betnow.net/doBusiness.do?params=',
        'EVOLUTION' => 'https://evo.luckyliveasia.com/entry?params',
        'AESEXY' => 'https://tttuat.onlinegames22.com/',
      ),
      'checkKYC'=> array(
        'status' => $kycStatus,
        'passport' => $passport,
        'passport_image' => $passport_image,
        'passport_image_selfie' => $passport_image_selfie,
      ),
      'totalMember' => $totalMember,
      'totalSub' => $totalSub,
      'tranHistory' => $tranHistory,
      'loginHistory' => $loginHistory,
      'change_password' => [
        'Casino' => 0,
        'SportBook' => 0,
        'BinanryOption' => 0,
        'SkyGame' => 0,
        //'WM555' => 0,//$user->User_Level == 1 ? 1 : 0,
        'AGINSPOSTBOOK' => 0,//$user->User_Level == 1 ? 1 : 0,
        'EVOLUTION' => 0,

      ],

    ];
    if($info->User_Casino == 1){
      //	        $CasinoBalance = (array)app('App\Http\Controllers\API\SAGameController')->checkBalance($user->User_ID);
      $CasinoBalance[0] = 0;
      //$data['balance']['Casino'] = $CasinoBalance[0]*1;
    }
    if($info->User_SportBook == 1){
      //	        $SportBookBalance = app('App\Http\Controllers\API\BCSportController')->checkBalance($user->User_ID);
      $SportBookBalance = 0;
      //$data['balance']['SportBook'] = $SportBookBalance*1;

    }
    /*if($info->User_BinanryOption == 1){
      $BoBalance = app('App\Http\Controllers\API\BoController')->checkBalance($user->User_ID);
     $data['balance']['BinanryOption'] =  $BoBalance;
    }

    if($info->User_SkyGame == 1){
      $skyBalance = app('App\Http\Controllers\API\SkyGameController')->checkBalance($user->User_ID);
      if($skyBalance){
        $data['balance']['SkyGame'] =  $skyBalance*1;
      }
    }*/

    /*if($info->User_WM555 == 1){

      $wm555Balance = 'View Ingame';
      if($wm555Balance){
        $data['balance']['WM555'] =  $wm555Balance;
      }
    }*/
    //if($info->User_Agin == 1){

    // $aginbalance = 'View Ingame';
    //if($aginbalance){
    // $data['balance']['AGINSPOSTBOOK'] =  $aginbalance;
    // }
    //}
    if($info->User_Evo == 1){
      //$data['change_password']['EVOLUTION'] =  1;
      $evobalance = 'View Ingame';
      if($evobalance){
        $data['balance']['EVOLUTION'] =  $evobalance;
      }
    }
    //if($info->User_AWC_Password != NULL){
    //$aebalance = 'View Ingame';
    //if($aebalance){
    // $data['balance']['AESEXY'] =  $aebalance;
    //}
    // }

    return $this->response(200, $data, '');
  }

  /**
     * getMainBalance
     *
     * @param  mixed $user_id
     * @return void
     */
  public function getMainBalance($user_id){
    $balance = User::getBalance($user_id, 3);
    return $balance;
  }
  /**
     * totalMember
     *
     * @param  mixed $user_id
     * @return void
     */
  public function totalMember($user_id){
    $totalMember = User::getMember($user_id);

    return count($totalMember);
  }
  /**
     * totalSubAccount
     *
     * @param  mixed $user_id
     * @return void
     */
  public function totalSubAccount($user_id){
    $totalSub = subAccount::getTotalSubaccount($user_id);
    return count($totalSub);
  }

  /**
     * getTransactionHistory
     *
     * @param  mixed $user_id
     * @return void
     */
  public function getTransactionHistory($user_id, $page = 50, $query = []){
    $getMember = User::getTransacstionHistory($user_id, $page = 50);
    return $getMember;
  }

  /**
     * getHistoryLogin
     *
     * @param  mixed $user_id
     * @param  mixed $limit
     * @return void
     */
  public function getHistoryLogin($user_id){
    $loginHistory = User::getHistoryLogin($user_id, $limit = 50, $query = []);
    return $loginHistory;
  }

  public function searchHistoryLogin(Request $req){
    $user = Auth::user();
    $user_id = $user->User_ID;
    $historyLogin = DB::table('log_user')->where('user', $user_id)->where('comment', 'like', '%Login%');
    if($req->fromdate){
      $historyLogin = $historyLogin->whereDate('datetime', '>=', $req->fromdate);
    }
    if($req->todate){
      $historyLogin = $historyLogin->whereDate('datetime', '<=', $req->todate);
    }
    $historyLogin = $historyLogin->take(50)->get();
    return $historyLogin;
  }
  public function searchTransactionHistory(Request $req){
    // var_dump(strtotime($req->fromdate));exit;
    $page = 50;
    $user = Auth::user();
    $user_id = $user->User_ID;
    $historyTransaction = Money::where('Money_User', $user_id);
    if($req->fromdate){
      $historyTransaction = $historyTransaction->where('Money_Time', '>', strtotime($req->fromdate));
    }
    if($req->todate){
      $historyTransaction = $historyTransaction->where('Money_Time', '<=', strtotime($req->todate));
    }
    $historyTransaction  = $historyTransaction->paginate($page);
    return $historyTransaction;
  }

  public function topTrending(){
    $result = [];

    $count_ae_sexy = DB::table('bet_history_ae_sexy')->selectRaw('count(id) as count_ae_sexy')->first();
    $count_agin = DB::table('bet_history_agin')->selectRaw('count(id) as count_agin')->first();
    $count_agin_hunterfish = DB::table('bet_history_agin_hunterfish')->selectRaw('count(id) as count_agin_hunterfish')->first();
    $count_agin_slot = DB::table('bet_history_agin_slot')->selectRaw('count(id) as count_agin_slot')->first();
    $count_evo = DB::table('bet_history_evo')->selectRaw('count(id) as count_evo')->first();  
    $count_wm = DB::table('bet_history_wm')->selectRaw('game_type, count(id) as count_wm')->groupby('game_type')->get();

    $total_count_wm = 0;
    foreach($count_wm as $key => $item){
      $total_count_wm = $total_count_wm + $item->count_wm;
    }

    $total_count = $count_ae_sexy->count_ae_sexy + $count_agin->count_agin + $count_agin_hunterfish->count_agin_hunterfish + $count_agin_slot->count_agin_slot + $count_evo->count_evo + $total_count_wm;

    $result_ae_sexy = $count_ae_sexy->count_ae_sexy * 100 / $total_count;
    $result_agin = $count_agin->count_agin * 100 / $total_count;
    $result_agin_hunterfish = $count_agin_hunterfish->count_agin_hunterfish * 100 / $total_count;
    $result_agin_slot = $count_agin_slot->count_agin_slot * 100 / $total_count;
    $result_evo = $count_evo->count_evo * 100 / $total_count;
    foreach($count_wm as $key => $item){
      $percent = $item->count_wm * 100 / $total_count;
      $result[] = ['percent' => $percent, 'name' => $item->game_type];
    }

    $result[] = ['percent'=>$result_ae_sexy, 'name'=>'ae_sexy'];
    $result[] = ['percent'=>$result_agin, 'name'=>'agin'];
    $result[] = ['percent'=>$result_agin_hunterfish, 'name'=>'agin_hunterfish'];
    $result[] = ['percent'=>$result_agin_slot, 'name'=>'agin_slot'];
    $result[] = ['percent'=>$result_evo, 'name'=>'evo'];

    $percent = array_column($result, 'percent');

    array_multisort($percent, SORT_ASC, $result);

    $result_top = array_slice($result, 4);

    return $this->response(200, $result_top, '');
  }

  public function recentlyGame(){
    $data = DB::table('list_game')->orderBy('created_at', 'DESC')->limit(2)->get();

    return $this->response(200, $data, '');
  }

  public function topGame(){
    $result = [];

    $sum_ae_sexy = DB::table('bet_history_ae_sexy')->selectRaw('SUM((CASE WHEN (realWinAmount - realBetAmount) <= 0 THEN 0 ELSE (realWinAmount - realBetAmount) END)) as sum_ae_sexy')->first();
    $sum_agin = DB::table('bet_history_agin')->selectRaw('SUM((CASE WHEN cus_account <= 0 THEN 0 ELSE cus_account END)) as sum_agin')->first();
    $sum_agin_hunterfish = DB::table('bet_history_agin_hunterfish')->selectRaw('SUM((CASE WHEN profit <= 0 THEN 0 ELSE profit END)) as sum_agin_hunterfish')->first();
    $sum_agin_slot = DB::table('bet_history_agin_slot')->selectRaw('SUM((CASE WHEN cus_account <= 0 THEN 0 ELSE cus_account END)) as sum_agin_slot')->first();
    $sum_evo = DB::table('bet_history_evo')->selectRaw('SUM((CASE WHEN (awardmoney - betmoney) <= 0 THEN 0 ELSE (awardmoney - betmoney) END)) as sum_evo')->first();
    $sum_wm = DB::table('bet_history_wm')->selectRaw('game_type, SUM((CASE WHEN result_amount <= 0 THEN 0 ELSE result_amount END)) as sum_wm')->groupby('game_type')->get();

    foreach($sum_wm as $key => $item){
      $result[] = ['total_amount' => number_format($item->sum_wm, 2), 'name' => $item->game_type];
    }

    $result[] = ['total_amount'=>number_format($sum_ae_sexy->sum_ae_sexy, 2), 'name'=>'AE sexy'];
    $result[] = ['total_amount'=>number_format($sum_agin->sum_agin, 2), 'name'=>'Agin SportBook'];
    $result[] = ['total_amount'=>number_format($sum_agin_hunterfish->sum_agin_hunterfish, 2), 'name'=>'Agin Fish Shooter'];
    $result[] = ['total_amount'=>number_format($sum_agin_slot->sum_agin_slot, 2), 'name'=>'Agin Slot'];
    $result[] = ['total_amount'=>number_format($sum_evo->sum_evo, 2), 'name'=>'Casino Evolution'];

    $sum = array_column($result, 'total_amount');

    array_multisort($sum, SORT_ASC, $result);

    $result_top = array_slice($result, 1);

    return $this->response(200, $result_top, '');
  }

  public function topGameOfWeek(){
    $result = [];

    $sum_ae_sexy_week = DB::table('bet_history_ae_sexy')->selectRaw('SUM((CASE WHEN (realWinAmount - realBetAmount) <= 0 THEN 0 ELSE (realWinAmount - realBetAmount) END)) as sum_ae_sexy_week')->groupBy(DB::raw('WEEK(CURDATE(),time123bet)'))->first();
    $sum_agin_week = DB::table('bet_history_agin')->selectRaw('SUM((CASE WHEN cus_account <= 0 THEN 0 ELSE cus_account END)) as sum_agin_week')->groupBy(DB::raw('WEEK(CURDATE(), time_123betnow)'))->first();
    $sum_agin_hunterfish_week = DB::table('bet_history_agin_hunterfish')->selectRaw('SUM((CASE WHEN profit <= 0 THEN 0 ELSE profit END)) as sum_agin_hunterfish_week')->groupBy(DB::raw('WEEK(CURDATE(), time_123betnow)'))->first();
    $sum_agin_slot_week = DB::table('bet_history_agin_slot')->selectRaw('SUM((CASE WHEN cus_account <= 0 THEN 0 ELSE cus_account END)) as sum_agin_slot_week')->groupBy(DB::raw('WEEK(CURDATE(), time_123betnow)'))->first();
    $sum_evo_week = DB::table('bet_history_evo')->selectRaw('SUM((CASE WHEN (awardmoney - betmoney) <= 0 THEN 0 ELSE (awardmoney - betmoney) END)) as sum_evo_week')->groupBy(DB::raw('WEEK(CURDATE(), created_at)'))->first();
    $sum_wm_week = DB::table('bet_history_wm')->selectRaw('game_type, SUM((CASE WHEN result_amount <= 0 THEN 0 ELSE result_amount END)) as sum_wm_week')->groupby('game_type', DB::raw('WEEK(CURDATE(), bet_time)'))->get();

    foreach($sum_wm_week as $key => $item){
      $result[] = ['total_amount' => number_format($item->sum_wm_week, 2), 'name' => $item->game_type];
    }

    $result[] = ['total_amount'=>number_format($sum_ae_sexy_week->sum_ae_sexy_week, 2), 'name'=>'AE sexy'];
    $result[] = ['total_amount'=>number_format($sum_agin_week->sum_agin_week, 2), 'name'=>'Agin SportBook'];
    $result[] = ['total_amount'=>number_format($sum_agin_hunterfish_week->sum_agin_hunterfish_week, 2), 'name'=>'Agin Fish Shooter'];
    $result[] = ['total_amount'=>number_format($sum_agin_slot_week->sum_agin_slot_week, 2), 'name'=>'Agin Slot'];
    $result[] = ['total_amount'=>number_format($sum_evo_week->sum_evo_week, 2), 'name'=>'Casino Evolution'];

    $sum = array_column($result, 'total_amount');

    array_multisort($sum, SORT_ASC, $result);

    $result_top = array_slice($result, 1);

    return $this->response(200, $result_top, '');
  }
  public function userTopGame(){

    $result = [];
    //->groupBy('users.User_ID') -> leftJoin('users', 'User_ID','=','userid')
    //-> where('User_ID','userid')
    // ->selectRaw('SUM((CASE WHEN (realWinAmount - realBetAmount) <= 0 THEN 0 ELSE (realWinAmount - realBetAmount) END)) as sum_ae_sexy, User_Name')


    $sum_ae_sexy = DB::table('bet_history_ae_sexy')->selectRaw('SUM((CASE WHEN (realWinAmount - realBetAmount) <= 0 THEN 0 ELSE (realWinAmount - realBetAmount) END)) as sum_ae_sexy')->first();
    $sum_agin = DB::table('bet_history_agin')->selectRaw('SUM((CASE WHEN cus_account <= 0 THEN 0 ELSE cus_account END)) as sum_agin')->first();
    $sum_agin_hunterfish = DB::table('bet_history_agin_hunterfish')->selectRaw('SUM((CASE WHEN profit <= 0 THEN 0 ELSE profit END)) as sum_agin_hunterfish')->first();
    $sum_agin_slot = DB::table('bet_history_agin_slot')->selectRaw('SUM((CASE WHEN cus_account <= 0 THEN 0 ELSE cus_account END)) as sum_agin_slot')->first();
    $sum_evo = DB::table('bet_history_evo')->selectRaw('SUM((CASE WHEN (awardmoney - betmoney) <= 0 THEN 0 ELSE (awardmoney - betmoney) END)) as sum_evo')->first();
    $sum_wm = DB::table('bet_history_wm')->selectRaw('game_type, SUM((CASE WHEN result_amount <= 0 THEN 0 ELSE result_amount END)) as sum_wm')->groupby('game_type')->get();

    foreach($sum_wm as $key => $item){
      $result[] = ['total_amount' => number_format($item->sum_wm, 2), 'name' => $item->game_type];
    }

    $result[] = ['total_amount'=>number_format($sum_ae_sexy->sum_ae_sexy, 2), 'name'=>'AE sexy'];
    $result[] = ['total_amount'=>number_format($sum_agin->sum_agin, 2), 'name'=>'Agin SportBook'];
    $result[] = ['total_amount'=>number_format($sum_agin_hunterfish->sum_agin_hunterfish, 2), 'name'=>'Agin Fish Shooter'];
    $result[] = ['total_amount'=>number_format($sum_agin_slot->sum_agin_slot, 2), 'name'=>'Agin Slot'];
    $result[] = ['total_amount'=>number_format($sum_evo->sum_evo, 2), 'name'=>'Casino Evolution'];

    $sum = array_column($result, 'total_amount');

    array_multisort($sum, SORT_ASC, $result);

    $result_top = array_slice($result, 1);

    return $this->response(200, $result_top, '');
  }

  public function getGameType(){
    $list = DB::table('game_type')->where('game_type_status', 1)->get();

    return $this->response(200, $list, '');
  }

  public function getLiveCasino(Request $request){
    $list = DB::table('list_game')->where('show', 1);

    if($request->dealer == 'Casino'){
      $list = $list->where('dealer', 'Casino');
    }
    if($request->type){
      $list = $list->where('type', $request->type);
    }

    $list = $list->get();

    return $this->response(200, $list, '');
  }

  public function topListWinner(){
    $result = [];

    $list = DB::table('statistical_123betnow')
      ->join('users', 'User_ID', 'statistical_User')
      ->where('User_Level', 0)
      ->where('statistical_Currency', 3)
      ->selectRaw('statistical_TotalBet as totalBet, User_ID , User_Email, User_WalletAddress')
      ->groupBy('statistical_User')
      ->orderByDesc('statistical_Time')
      ->limit(3)
      ->get();

    return $this->response(200, $list, '');
  }

  public function historyLatestBets(){
    $result = [];

    $ae_sexy_week = DB::table('bet_history_ae_sexy')
      ->selectRaw('userId, realBetAmount, (realWinAmount - realBetAmount) as profit, gameName, time123bet')
      ->get();
    $agin_week = DB::table('bet_history_agin')
      ->selectRaw('userid, account, cus_account, gametype, time_123betnow')
      ->get();
    $agin_hunterfish_week = DB::table('bet_history_agin_hunterfish')
      ->selectRaw('userid, gametype, totalfishcost, profit, time_123betnow')
      ->get();
    $agin_slot_week = DB::table('bet_history_agin_slot')
      ->selectRaw('userid, gametype, cus_account, account, time_123betnow')
      ->get();
    $evo_week = DB::table('bet_history_evo')
      ->selectRaw('user_id, betmoney, (awardmoney - betmoney) as profit, game_name, bettime')
      ->get();
    $wm_week = DB::table('bet_history_wm')
      ->selectRaw('user_id, game_type, bet_amount, result_amount, bet_time')
      ->get();

    foreach($ae_sexy_week as $key => $item){
      if(($item->profit * 1) > 0){
        $icon = DB::table('list_game')->where('id', 1)->first();
        $result[] = ['player' => $item->userId, 'bet_amount' => $item->realBetAmount * 1, 'profit_amount' => $item->profit * 1, 'game_name' => $item->gameName, 'time' => $item->time123bet, 'icon_game' => $icon->icon_game];
      }
    }

    foreach($agin_week as $key => $item){
      if(($item->cus_account * 1) > 0){
        $icon = DB::table('list_game')->where('id', 3)->first();
        $result[] = ['player' => $item->userid, 'bet_amount' => $item->account * 1, 'profit_amount' => $item->cus_account * 1, 'game_name' => $item->gametype, 'time' => $item->time_123betnow, 'icon_game' => $icon->icon_game];
      }
    }

    foreach($agin_hunterfish_week as $key => $item){
      if(($item->profit * 1) > 0){
        $icon = DB::table('list_game')->where('id', 4)->first();
        $result[] = ['player' => $item->userid, 'bet_amount' => $item->totalfishcost * 1, 'profit_amount' => $item->profit * 1, 'game_name' => $item->gametype, 'time' => $item->time_123betnow, 'icon_game' => $icon->icon_game];
      }
    }

    foreach($agin_slot_week as $key => $item){
      if(($item->cus_account * 1) > 0){
        $icon = DB::table('list_game')->where('id', 5)->first();
        $result[] = ['player' => $item->userid, 'bet_amount' => $item->account * 1, 'profit_amount' => $item->cus_account * 1, 'game_name' => $item->gametype, 'time' => $item->time_123betnow, 'icon_game' => $icon->icon_game];
      }
    }

    foreach($evo_week as $key => $item){
      if(($item->profit * 1) > 0){
        $result[] = ['player' => $item->user_id, 'bet_amount' => $item->betmoney * 1, 'profit_amount' => $item->profit * 1, 'game_name' => $item->game_name, 'time' => $item->bettime, 'icon_game' => $icon->icon_game];
      }
    }

    foreach($wm_week as $key => $item){
      if(($item->result_amount * 1) > 0){
        $icon = DB::table('list_game')->where('dealer', 'Casino')->get();
        $new_icon = '';
        foreach($icon as $key_icon => $item_icon){
          if($item->game_type == $item_icon->display_name){
            $new_icon = $item_icon->icon_game;
          }
        }
        $result[] = ['player' => $item->user_id, 'bet_amount' => $item->bet_amount * 1, 'profit_amount' => $item->result_amount * 1, 'game_name' => $item->game_type, 'time' => $item->bet_time, 'icon_game' => $new_icon];
      }
    }

    $arr_column = array_column($result, 'time');

    array_multisort($arr_column, SORT_DESC, $result);

    $result_history = array_slice($result, 0, 50);

    return $this->response(200, $result_history, '');
  }

  public function highRollers(){
    $result = [];

    $list = DB::table('statistical_123betnow')
      ->join('users', 'User_ID', 'statistical_User')
      ->where('User_Level', 0)
      ->where('statistical_Currency', 3)
      ->selectRaw('statistical_TotalBet as bet_amount, User_ID , User_Email, statistical_Game as game, statistical_TotalWin as profit_amount')
      ->groupBy('statistical_User')
      ->orderByDesc('statistical_Time')
      ->limit(10)
      ->get();

    return $this->response(200, $list, '');
  }

}
