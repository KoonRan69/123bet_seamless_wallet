<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Model\User;
use App\Model\Log;
use App\Model\LogMail;
use DB;
use App\Jobs\SendTelegramJobs;
use Mail;

class SendMailJobs implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
     * Create a new job instance.
     *
     * @return void
     */
  public $template;
  public $data;
  public $tittle;

  public $userID;

  public function __construct($template, $data = [], $tittle, $userID)
  {

    $data['url_web'] = 'https://apiv2.123betnow.net/';

    $this->template = $template;
    $this->data = $data;
    $this->tittle = $tittle;
    $this->userID = $userID;
  }

  /**
     * Execute the job.
     *
     * @return void
     */
  public function handle()
  {
    $template = $this->template;
    $data = $this->data;
    $tittle = $this->tittle;
    $userID = $this->userID;
    $user = User::find($userID);
    $checkSpam = LogMail::where('Log_Action', 'Mail Error')->where('Log_User', $user->User_ID)->first();
    if ($checkSpam) {
      LogMail::insertLog($user, 'Mail Blocked', $tittle);
      return false;
    }

    $templateMail = 'Mail.'.$template;
    try{
      Mail::send($templateMail, $data, function($msg) use ($data, $tittle){
        $msg->from('no-reply@123betnow.net','123betnow');

        $msg->to($data['User_Email'])->subject($tittle);
      });
    }catch (\Exception $e) {
      LogMail::insertLog($user, 'Mail Error', $e->getMessage());
      $message = $user->User_ID. " Mail error ".$e->getMessage()."\n"
        . "<b>Project: </b>"
        . "123betnow.co\n"
        . "<b>User ID: </b>"
        . "$user->User_ID\n"
        . "<b>Email: </b>"
        . "$user->User_Email\n"
        . "<b>Time: </b>"
        . date('d-m-Y H:i:s',time());

      dispatch(new SendTelegramJobs($message, -398297366));
    }
    LogMail::insertLog($user, 'Send Email', $tittle);
    $log = Log::insertLog($userID, $tittle, 0, $tittle);
  }
}
