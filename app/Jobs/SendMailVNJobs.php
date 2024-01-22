<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Model\User;
use App\Model\Log;
use DB;

use Mail;

class SendMailVNJobs implements ShouldQueue
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

    public $email;

    public function __construct($template, $data = [], $tittle, $email)
    {
        
        $data['url_web'] = 'https://eggsbook.com/';
        
        $this->template = $template;
        $this->data = $data;
        $this->tittle = $tittle;
        $this->email = $email;
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
        $email = $this->email;
        


        //prosess
        $templateMail = 'Mail.'.$template;
        Mail::send($templateMail, $data, function($msg) use ($data, $tittle, $email){
            $msg->from('spvn.eggsbook@gmail.com','Eggs Book');
            
            $msg->to($email)->subject($tittle);
        });
    }
}
