<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogMission extends Model
{
    protected $table = 'log_mission';
    public $timestamps = false;
    public static function addLogMission($user_id, $action, $comment){
        $log_mission = new LogMission;
        $log_mission->user_id = $user_id;
        $log_mission->action = $action;
        $log_mission->comment = $comment;
        $log_mission->date_time = date('Y-m-d H:i:s', time());
        $log_mission->save();
        return $log_mission;
    }
}
