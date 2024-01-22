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

class TopLeaderController extends Controller
{
    public function getUpdateVolumeTopTrader(Request $request)
    {

    }

    public function getRewardVolumeTopTrader(Request $req)
    {
		$arrBonusTop = [
           1 => ['amount'=>1000],
           2 => ['amount'=>750],
           3 => ['amount'=>500],
        ];

        $dayStart = strtotime('2022-01-25 03:30:00');
        $dayEnd = strtotime('2022-02-26 00:00:00');
        $dateExpired = strtotime(date('2021-02-29 00:00:00'));
        //dd(date('Y-m-d H:i:s', $dateExpired));
      	if(strtotime(date('Y-m-d H:i:s')) < strtotime($dateExpired)){
          dd('Time expired!');
        }
      
      	$list = DB::table('statistical_123betnow')
                            ->join('users', 'User_ID', 'statistical_User')
                            //->whereIn('User_Level', [1])
                            ->whereIn('User_Level', [0,5])
                            ->where('statistical_Currency', 3)
                            ->where('statistical_Time', '>=', $dayStart)
                            ->where('statistical_Time', '<', $dayEnd)
                            ->selectRaw('SUM(statistical_TotalBet) as totalBet, User_ID , User_Email, User_Tree')
                            ->groupBy('statistical_User')
          					->orderByDesc('totalBet')
                            ->having('totalBet', '>', 50000)
          					->limit(3)
                            ->get();
      
      	$arrayInsert = [];
        foreach ($list as $k => $comUser) {
            $id = $k + 1;
            $amount = $arrBonusTop[$id]['amount'];
            $vol = $comUser->totalBet;
            //check dup
            $checkDup = Money::where('Money_User', $comUser->User_ID)
                ->where('Money_MoneyAction', 83)
                ->where('Money_Currency', 3)
                ->whereIn('Money_MoneyStatus', [0, 1])
                ->where('Money_Time', '>=', ($dayStart))
                ->where('Money_Time', '<', ($dateExpired))
                /*->where('Money_Time', '<', $dateExpired)*/
                ->first();
            if ($checkDup) {
                continue;
            }
            $arrayInsert[] = array(
                'Money_User' => $comUser->User_ID,
                'Money_USDT' => $amount,
                'Money_USDTFee' => 0,
                'Money_Time' => time(),
                'Money_Comment' => 'Awarding TOP ' . $id . ' Trader Commission $' . ($amount + 0),
                'Money_MoneyAction' => 83,
                'Money_MoneyStatus' => 1,
                'Money_Address' => null,
                'Money_Currency' => 3,
                'Money_CurrentAmount' => $amount,
                'Money_Rate' => 1,
                'Money_Confirm' => 0,
                'Money_Confirm_Time' => null,
                'Money_FromAPI' => 0,
            );
            echo $comUser->user . ' : $' . $amount . ' Awarding TOP ' . $id . ' with total volume: $' . ($vol + 0) . '<br>';
        }
      	//dd($arrayInsert);
        if ($req->pay == 142) {
            $insert = Money::insert($arrayInsert);
        }
        dd('Check Awarding top leader success!');
    }

    public function getUpdateCommissionTopleader(Request $request)
    {
        //dd('Promotion period is over!');
        //$monthCurren = date('m');
        $dayStart = strtotime('2022-01-25 03:30:00');
        $dayEnd = strtotime('2022-02-26 00:00:00');
        $dateCurrent = date('Y-m-d');
        //$dateExpired = '2021-12-30';
        //dd(2);
        if ($dateCurrent > $dayEnd) {
            dd('Promotion period is over!');
        }
        $getIB = $this->checkConditionLeader($dayStart, $dayEnd);
        //dd($getIB);
        foreach ($getIB as $ibUser) {
            $checkCommission = DB::table('commission_user')->where('user', $ibUser->Money_User)->first();
            if (!$checkCommission) {
                $data = array();
                $data['user'] = $ibUser->Money_User;
                $data['amount'] = $ibUser->ib;
                $data['status'] = 0;
                $data['time_update'] = time();
                DB::table('commission_user')->insert($data);
            } else {
                $update = DB::table('commission_user')->where('user', $ibUser->Money_User)->update(['amount' => $ibUser->ib, 'time_update' => time()]);
            }

            //dd($data,$checkCommission);
            $updateCommission = DB::table('commission_user')->where('user', $ibUser->Money_User)->first();

            //Check dkien F1 và cấp royal
            $checkSetAgency = DB::table('set_agency')->where('user', $updateCommission->user)->where('status', 1)->first();
            //$weekHasReached = 0;
            $getLeveIB = DB::table('package_weekly_123betnow')
                ->where('package_weekly_User', $ibUser->Money_User)
                ->where('package_weekly_FromDate', '>=', date('Y-m-d H:i:s', $dayStart))
                ->where('package_weekly_ToDate', '<=', date('Y-m-d H:i:s', $dayEnd))
                ->get();
            $amountF1 = 0;
          	$checkLevelIB = 0;
            if ($checkSetAgency && $checkSetAgency->level >= 7) {
                $checkLevelIB = 1;
            	$amountF1 = 20;
            } 
          	else{
                if (count($getLeveIB) == 0) {
                    $checkLevelIB = 0;
                    $amountF1 = 0;
                } 
              	else{
                    foreach ($getLeveIB as $item) {
                      	$amountF1 = $item->package_weekly_F1Active;
                        if ($item->package_weekly_Level >= 7) {
                            if ($amountF1 >= 20) {
                                //$weekHasReached = $weekHasReached + 1;
                                $checkLevelIB = 1;
                            } 
                          	else {
                                $checkLevelIB = 0;
                            }
                        } 
                      	else {
                            $checkLevelIB = 0;
                        }
                    }
                }
            }
            //check volume và ib > 10k
            $totalIB = $updateCommission->amount;
            $listVolume = DB::table('statistical_123betnow')
                ->where('statistical_User', $ibUser->Money_User)
                ->where('statistical_Currency', 3)
                ->where('statistical_Time', '>=', date('Y-m-01'))
                ->where('statistical_Time', '<=', date("Y-m-t", strtotime($dateCurrent)))
                ->get();
            $totalVolume = $listVolume->sum('statistical_TotalBet');
            if ($ibUser->Money_User == 544693) {
                //dd($getLeveIB,$amountF1);
            }
            //dd(date('Y-m-01'),date("Y-m-t", strtotime($dateCurrent)));

            $status = 'unqualified';
            if ($totalIB >= 3000 && $checkLevelIB == 1) {
                $status = 'eligible';
                DB::table('commission_user')->where('user', $updateCommission->user)->update(['status' => 1]);
            }
            echo 'User: ' . $ibUser->Money_User . ', IB: ' . $totalIB . ', number F1: ' . $amountF1 . ', Status: ' . $status . '<br>';
        }

        dd('Update commission top leader all user success!');
    }

    public function checkConditionLeader($dayStart, $dayEnd)
    {
        $getIB = Money::join('users', 'User_ID', 'Money_User')
            ->whereIn('User_Level', [0,5])
            //->whereIn('User_Level', [1])
            ->where('Money_Time', '>=', ($dayStart))->where('Money_Time', '<', ($dayEnd))
            ->whereIn('Money_MoneyStatus', [0, 1])
            ->groupBy('Money_User')
            ->selectRaw('COALESCE(SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 65, Money_USDT, 0)), 0) as `ib`,`Money_User`, `User_Email`')
            ->get();
        return $getIB;
    }

    public function getRewardCommissionTopleader(Request $req)
    {
        //dd('Promotion period is over!');
        $arrBonusTop = [
            1 => ['amount' => 1500],
            2 => ['amount' => 750],
            3 => ['amount' => 500],
            4 => ['amount' => 200],
            5 => ['amount' => 200],
            6 => ['amount' => 200],
            7 => ['amount' => 200],
            8 => ['amount' => 200],
            9 => ['amount' => 200],
            10 => ['amount' => 200],
        ];

        $dayStart = strtotime('2022-01-25 03:30:00');
        $dayEnd = strtotime('2022-02-26 00:00:00');
        $dateExpired = strtotime(date('2022-02-28 00:00:00'));
        //dd(date('Y-m-d H:i:s', $dateExpired));
      	if(strtotime(date('Y-m-d H:i:s')) < strtotime($dateExpired)){
          dd('Time expired!');
        }
        $listCommision = DB::table('commission_user')
            ->join('users', 'User_ID', 'user')
            ->where('status', 1)
            //->whereIn('User_Level', [1])
            ->whereIn('User_Level', [0,5])
            ->orderByDesc('amount')
            ->limit(10)
            ->get();
        //dd($listCommision);
        $arrayInsert = [];
        foreach ($listCommision as $k => $comUser) {
            $id = $k + 1;
            $amount = $arrBonusTop[$id]['amount'];
            $ib = $comUser->amount;
            //check dup
            $checkDup = Money::where('Money_User', $comUser->user)
                ->where('Money_MoneyAction', 81)
                ->where('Money_Currency', 3)
                ->whereIn('Money_MoneyStatus', [0, 1])
                ->where('Money_Time', '>=', ($dayStart))
                //->where('Money_Time', '<', ($dateExpired))
                /*->where('Money_Time', '<', $dateExpired)*/
                ->first();
            if ($checkDup) {
                continue;
            }
            $arrayInsert[] = array(
                'Money_User' => $comUser->user,
                'Money_USDT' => $amount,
                'Money_USDTFee' => 0,
                'Money_Time' => time(),
                'Money_Comment' => 'Awarding TOP ' . $id . ' Commission $' . ($amount + 0),
                'Money_MoneyAction' => 81,
                'Money_MoneyStatus' => 1,
                'Money_Address' => null,
                'Money_Currency' => 3,
                'Money_CurrentAmount' => $amount,
                'Money_Rate' => 1,
                'Money_Confirm' => 0,
                'Money_Confirm_Time' => null,
                'Money_FromAPI' => 0,
            );
            echo $comUser->user . ' : $' . $amount . ' Awarding TOP ' . $id . ' with total ib: $' . ($ib + 0) . '<br>';
        }
        if ($req->pay == 142) {
            $insert = Money::insert($arrayInsert);
        }
        dd('Check Awarding top leader success!');
    }
}

