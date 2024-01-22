<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Model\User;
use App\Model\Log;
use App\Model\BetHistoryWM;
use GuzzleHttp\Client;

use DB;

use Mail;

class GetHistoryWM555Jobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $userID;
  	public $config;

    public function __construct($userID)
    {
        $this->userID = $userID;
      	$this->config = config('utils.wm555');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    $userID = $this->userID;
        $user = User::find($userID);
        if($user->User_WM555 != 1){
            //return $this->response(200, [], 'You have no game account, just register!', [], false);
              return false;
        }

        //if(!$user->User_Name) return $this->response(200, [], 'You have no game account, just register!', [], false);

        $startDate = (string) date('Y/m/d 00:00:00', strtotime('-1 month'));
        $endDate = (string) date('Y/m/d H:i:s', strtotime('+1 day', time()));

        $username = 'now'.$user->User_ID;
        $body = [
          'username' => $username,
          'startDate' => $startDate,
          'endDate' => $endDate,
        ];

        $client = new Client();
        $apiKey = $this->config['key'];
        $res = $client->request('POST', $this->config['url'].'winloss?apikey='.$apiKey, [
          'body' => json_encode($body)
        ]);

        $data = json_decode($res->getBody()->getContents());
		//dd($data);
        //return $this->response(200, ['data' => $res->getBody()], 'Deposit to WM555 game successful');

        if($data->error_code != 0) return false;
        $betHistoryWM = BetHistoryWM::where('username', $username)->pluck('bet_id')->toArray();

        $results = [];

        foreach($data->data as $value){

          if(!in_array($value->BetID, $betHistoryWM)){
              $results[] = [
              'username' => $value->Username,
              'game_type' => $value->GameType,
              'game_id' => $value->GameID,
              'web' => $value->web,
              'bet_id' => $value->BetID,
              'bet_amount' => $value->BetAmount,
              'rolling' => $value->Rolling,
              'result_amount' => $value->ResultAmount,
              'balance' => $value->Balance,
              'game_result' => $value->GameResult,
              'transaction_id' => $value->TransactionID,
              'bet_source' => $value->BetSource,
              'bet_type' => $value->BetType,
              'bet_time' => $value->BetTime,
              'payout_time' => $value->PayoutTime,
              'game_set' => $value->GameSet,
              'host_id' => $value->HostID,
              'host_name' => $value->HostName,
              'off_set' => $value->Offset,
            ];
          }
        }

        if(count($results) > 0) BetHistoryWM::insert($results);
                                   
		return true;
    }
}
