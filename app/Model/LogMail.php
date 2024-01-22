<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogMail extends Model
{
  protected $table = 'log_mail';
  protected $fillable = [
    'Log_User',
    'Log_Email',
    'Log_Active',
    'Log_Content',
    'Log_Datetime'
  ];

  public $timestamps = false;

  public static function insertLog($user, $action, $content)
  {
    $result = new LogMail;
    $result->Log_User = $user->User_ID;
    $result->Log_Email = $user->User_Email;
    $result->Log_Action = $action;
    $result->Log_Content = $content;
    $result->Log_Datetime = date('Y-m-d H:m:s');
    $result->save();
    return $result;
  }
}
