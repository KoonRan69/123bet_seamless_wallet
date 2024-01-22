<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListMission extends Model
{
    protected $table = 'list_mission';
    public $timestamps = false;

    public static function insertMission($mission_id, $user_id){
        $mission = new ListMission;
        $mission->mission_id = $mission_id;
        $mission->user_id = $user_id;
        $mission->date_time = date('Y-m-d H:i:s');
        $mission->save();
        return $mission;
    }

    public static function playLuckySpin(){
        // $random = random_int(1, 100);
        // $arr_random_70 = [
        //     0,1,2,4,5,6,8,9,10
        // ];
        // $arr_random_30 = [
        //     3,7,11
        // ];
        // if($random <= 70){
        //     $gold = random_int(0, 8);
        //     $gold_location = $arr_random_70[$gold];
        // }else{
        //     $gold = random_int(0, 2);
        //     $gold_location = $arr_random_30[$gold];
        // }
        $random= random_int(1, 100000);
        $arr_random = [
            45734 => 10,
            45735 => 500,
            65735 => 20,
            65740 => 400,
            72740 => -1,
            82740 => 30,
            82750 => 300,
            89750 => 40,
            89800 => 200,
            92800 => 50,
            93000 => 100,
            100000 => 0
        ];
        foreach($arr_random as $k => $v){
            if($random <= $k){
                $gold = $v;
                break;
            }
        }
        return $gold;
    }
    public function mission(){
        return $this->belongsTo('App\Model\Mission', 'mission_id', 'id');
    }

    public static function listMissionType($type=null){
        $user = Auth::user();
        $list_mission = ListMission::join('mission', 'mission_id', 'mission.id');
        if($type){
            $list_mission = $list_mission->where('mission_type', $type);
        }
        $list_mission = $list_mission->select('list_mission.id','mission_id','list_mission.status','user_id','mission.mission_type','mission.mission','list_mission.get_reward','list_mission.mission_progress','mission.description','mission.mission_success','mission.gold','mission_url')->where('list_mission.status', '<>', 0)->where('mission.status', '<>', 0)->where('user_id', $user->User_ID)->get();
        return $list_mission;
    }

    public static function checkMissionExits($user_id, $mission_id){
        $check_mission = ListMission::where(['user_id' => $user_id, 'mission_id' => $mission_id])
                                    ->where('status', '<>', 0)
                                    ->first();
        if($check_mission){
            return true;
        }
        return false;
    }

    public static function checkMissionFinish($user_id, $mission_id){
        $check_mission = ListMission::where(['user_id' => $user_id, 'mission_id' => $mission_id])
                                    ->where('status', -1)
                                    ->first();
        if($check_mission){
            return true;
        }
        return false;
    }

    public static function updateMission($user_id, $mission_id, $status){
        $update_mision = ListMission::where(['user_id' => $user_id, 'mission_id' => $mission_id])->where('status', '<>', 0)->update(['status' => $status]);
        if($update_mision){
            return true;
        }
        return false;
    }

    public static function updateReward($user_id, $mission_id){
        $update_mision = ListMission::where(['user_id' => $user_id, 'id' => $mission_id, 'status' => -1])->update(['get_reward' => 1]);
        if($update_mision){
            return true;
        }
        return false;
    }
    /////Méo có sài nữa
    public static function setBalanceGame($user_id){
        $balance = app('App\Http\Controllers\API\AgGameController')->balanceGame();
        $amountBalance = $balance['balance'] ?? 0;
        $update_balance_game = User::where('User_ID', $user_id)->update(['user_balance_game' => $amountBalance]);
        if($update_balance_game){
            return true;
        }
        return false;
    }
    public static function setBalanceGameDay($user_id){
        $balance = app('App\Http\Controllers\API\AgGameController')->balanceGame();
        $amountBalance = $balance['balance'] ?? 0;
        $update_balance_game = User::where('User_ID', $user_id)->update(['user_balance_game_day' => $amountBalance, 'time_update_game_balance' => date('Y-m-d')]);
        if($update_balance_game){
            return true;
        }
        return false;
    }

    public static function checkInviteMember($user_id){
        $member_childs = User::where('User_Parent', $user_id)->get();
        $count_member = 0;
        foreach($member_childs as $mbc){
            $get_eggs = Eggs::where('ActiveTime', '>',0)->where('Status', 1)->where('Owner', $mbc->User_ID)->select('_id', 'ActiveTime', 'BuyDate', 'ID', 'status')->first();
            if($get_eggs){
                $count_member++;
            }
        }
        return $count_member;
    }

    public static function checkMissionKYC($user_id){
        $checkKYC = Profile::where('Profile_User', $user_id)->where('Profile_Status', 1)->first();
        if($checkKYC){
            return true;
        }
        return false;
    }

    public static function getReward($id, $userID){
        $getMission = ListMission::join('mission', 'mission.id', 'list_mission.mission_id')->where('user_id', $userID)->where('list_mission.id', $id)->where('list_mission.status', -1)->where('get_reward', 0)->first();
        if(!$getMission){
            return false;
        }
        $getCoin = $getMission->gold;
        return $getCoin;
    }

    public static function checkDailyMission($mission_id){
        $user = Auth::user();
        $daily_mission = ListMission::where('user_id', $user->User_ID)->where('id', $mission_id)->where('status', '<>', 0)->first();
        if(date('d-m-Y', strtotime($daily_mission->date_time)) < date('d-m-Y')){
            return true;
        }
        return false;
    }

    public static function getMission($user_id, $arr_mission){
        // $arr_mission = [
        //     5,6,7,8,9
        // ];
        $list_mission = ListMission::where('user_id', $user_id)->whereIn('mission_id', $arr_mission)->where('status', '<>', 0)->get();
        return $list_mission;
    }
}
