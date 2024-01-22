<?php

namespace App\Http\Controllers\Provide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use DB;
use Session;
use Excel;
use App\Exports\ExportWM555;
use App\Model\ProBetHistoryWM;
use App\Model\ProUser;


class WM555Controller extends Controller
{
  public $config;
  public $is_maintain = 0;

  public function __construct()
  {
    $this->config = config('utils.wm555');
  }

  public function getHistoryWM(Request $request){
    $table = "pro_bet_history_wm";
    $user = Session::get('user');
    $gameWallet = DB::table($table)->where('user_parent', $user->User_ID);
    if($request->user_id){
      $gameWallet = $gameWallet->where('user_id',$request->user_id);
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('bet_time', '>=', ($request->datefrom.' 00:00:00'))
        ->where('CreatedAt', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('bet_time', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('bet_time', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id')->get();
      return Excel::download(new ExportWM555($gameWallet), 'history-wm555.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing($table);
    //$totalWeek = DB::table('totalhistorysa')->join('users', 'totalhistorysa.Username', '=', 'users.user_ID')->get();
    //dd($gameWallet);
    return view('Provide.wm555', compact('gameWallet', 'columnTable'));
  }

  public function saveHistoryWM(Request $request)
  {
    $checkInsertBet = ProBetHistoryWM::orderByDesc('created_at')->first();
    $timePeriod = 300;
    //dd($checkInsertBet,$timePeriod, $checkInsertBet->created_at + $timePeriod > time());
    if ($checkInsertBet && $checkInsertBet->created_at + $timePeriod > time()) {
      return 0;
    }
    $user_pro = ProUser::where('User_WM555', '<>',NULL)->get();
    foreach($user_pro as $user){
      //if(!$user->User_Name) return $this->response(200, [], 'You have no game account, just register!', [], false);

      $startDate = (string)date('Y/m/d 00:00:00', strtotime('-1 month'));
      $endDate = (string)date('Y/m/d H:i:s', strtotime('+1 day', time()));

      $dataRaw = [
        'username' => 'now' . $user->User_ID,
        'startDate' => $startDate,
        'endDate' => $endDate,
      ];

      $client = new Client();
      $apiKey = $this->config['key'];
      $res = $client->request('POST', $this->config['url'] . 'winloss?apikey=' . $apiKey, [
        'body' => json_encode($dataRaw)
      ]);

      $data = $res->getBody()->getContents();
      $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data));
      //dd($data);
      if ($data->error_code != 0 || $data->data == false || $data->data->status == false) {
        return 0;
        return $this->response(200, [], 'Get history fail', [], false);
      }
      $username = 'now' . $user->User_ID;
      $betHistoryWM = ProBetHistoryWM::where('created_at', '>=', strtotime('-7 days'))->pluck('bet_id')->toArray();
      //dd($betHistoryWM);
      $results = [];
      return 0;
      foreach ($data->data as $value) {

        if (!in_array($value->BetID, $betHistoryWM)) {
          $results[] = [
            'username' => $value->Username,
            'user_id' => str_replace('now', '', $value->Username),
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
            'created_at' => time(),
          ];
        }
      }

      if (count($results) > 0) BetHistoryWM::insert($results);
    }

    return 1;
  }
}

