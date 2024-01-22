<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogAdmin extends Model
{
    protected $table = "log_admin";

    public $timestamps = false;
    public static function addLogAdmin($user, $action, $comment){
        $log_admin = new LogAdmin;
        $log_admin->user = $user;
        $log_admin->action = $action;
        $log_admin->comment = $comment;
        $log_admin->created_at = date('Y-m-d H:i:s', time());
        $log_admin->save();
        return $log_admin;
    } 
}
