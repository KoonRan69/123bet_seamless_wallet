<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Jobs\SendTelegramJobs;
use App\Model\TournamentSetting;
use App\Model\TournamentUser;
use App\Model\User;
use App\Model\Money;

class TicketController extends Controller
{
  public function getTournament(Request $req)
  {
    $user = Auth::user();
    $tickets_user = TournamentUser::with('tournament')
        ->where('user_id', $user->User_ID)
        ->whereIn('status', [0,1])
//      ->whereHas('tournament', function (Builder $query) {
//        $query->where('status', 1);
//      })
      ->orderByDesc('id')->get();
    $tournament = TournamentSetting::whereIn('status', [0,1]);
    if($req->name){
      $tournament->where('name', 'like', '%'.$req->name.'%')->orWhere('description', 'like', '%'.$req->name.'%');
    }
    $tournament = $tournament->get();
    // dd($ticket);
    $data['tournaments_user'] = $tickets_user;
    $data['tournaments'] = $tournament;
    return $this->response(200, $data, '');
  }

  public function postBuyTournamentTicket(Request $req)
  {
    $user = Auth::user();
    $tournament_id = $req->id;
    $tournament = TournamentSetting::whereIn('status', [0])
        ->where('id', $tournament_id)
        ->first();
    if(!$tournament){
      return $this->response(200, ['ip' => $req->ip()], trans('notification.tournament_not_found'), [], false);
    }
    $check_bought_ticket = TournamentUser::where('user_id', $user->User_ID)
                                        ->where('tournament_id', $tournament_id)->first();
    if($check_bought_ticket){
      return $this->response(200, ['ip' => $req->ip()], trans('notification.tournament_purchased'), [], false);
    }
    $price = $tournament->price;
    $coin_from = 3;
    $balance = User::getBalance($user->User_ID, $coin_from);
    if($price > $balance){
      return $this->response(200, ['ip' => $req->ip()], trans('notification.Account_balance_is_not_enough'), [], false);
    }
    $ticket_data = [
      'tournament_id' => $tournament_id,
      'user_id' => $user->User_ID,
      'status' => 1,
    ];
    $add_ticket = TournamentUser::create($ticket_data);
    // lưu lịch sử
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$price,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => "Buy 1 Ticket Promotion",
      'Money_MoneyAction' => 17,
      'Money_MoneyStatus' => 1,
      'Money_Address' => "",
      'Money_Currency' => $coin_from,
      'Money_CurrentAmount' => $price,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 1,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      'Money_TXID' => "tournament id ".$add_ticket->id,
    );
    $id = Money::insertGetId($arrayInsert);
    $tickets_user = TournamentUser::with('tournament')
      ->where('user_id', $user->User_ID)
      ->whereIn('status', [0,1])
      ->orderByDesc('id')->get();
    $data['list_ticket'] = $tickets_user;
    return $this->response(200, $data, 'Successful ticket purchase!!');
  }

  public function getTicket()
  {
    $user = Auth::user();
    $tickets = DB::table('ticket')->join('ticket_subject', 'ticket_subject_id', 'ticket_Subject')
      ->where('ticket_User', $user->User_ID)
      ->where('ticket_ReplyID', 0)
      ->orderByDesc('ticket_ID')->get();
    $dataTicket = [];
    foreach ($tickets as $ticket) {
      $findlastRep = DB::table('ticket')->where('ticket_ReplyID', $ticket->ticket_ID)->orderBy('ticket_ID', 'DESC')->first();
      if (!$findlastRep || $findlastRep == null) {
        $status = 'Waiting';
      } else {
        $getInfo = DB::table('users')->whereIn('User_Level', [1, 2, 3])->where('User_ID', $findlastRep->ticket_User)->first();
        if ($getInfo) {
          $status = 'Replied';
        } else {
          $status = 'Waiting';;
        }
      }
      $dataTicket[] = [
        'ticket_ID' => $ticket->ticket_ID,
        'ticket_User' => $ticket->ticket_ID,
        'email' => $user->User_Email,
        'ticket_subject_name' => $ticket->ticket_subject_name,
        'ticket_subject_id' => $ticket->ticket_subject_id,
        'ticket_ReplyID' => $ticket->ticket_ReplyID,
        'ticket_Content' => $ticket->ticket_Content,
        'ticket_Time' => $ticket->ticket_Time,
        'count' => (DB::table('ticket')->where('ticket_ReplyID', $ticket->ticket_ID)->count()) + 1,
        'ticket_Status' => $status,
      ];
    }
    $subject = DB::table('ticket_subject')->get();
    // dd($ticket);
    $data['list_ticket'] = $dataTicket;
    $data['subject_ticket'] = $subject;
    return $this->response(200, $data, '');
    //return view('System.Ticket.Ticket', compact('ticket', 'subject'));
  }

  public function postTicket(Request $req)
  {
    if (!$req->content || (!$req->subject && !$req->replyID)) {
      return $this->response(200, ['ip' => $req->ip()], trans('notification.Please_enter_a_message_before_sending'), [], false);
      //return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Miss data']);
    }
    $user = Auth::user();
    if ($req->subject) {
      $subjectID = $req->subject;
    }
    $replyID = 0;
    if ($req->replyID && is_numeric($req->replyID)) {
      $replyID = $req->replyID;
      $subject = DB::table('ticket')->where('ticket_ID', $replyID)->select('ticket_Subject')->first();
      $subjectID = $subject->ticket_Subject;
    }

    $addArray = array(
      'ticket_User' => $user->User_ID,
      'ticket_Time' => date('Y-m-d H:i:s'),
      'ticket_Subject' => $subjectID,
      'ticket_Content' => $req->content,
      'ticket_Status' => 0,
      'ticket_ReplyID' => $replyID
    );

    $data = DB::table('ticket')->insert([$addArray]);

    $id = DB::getPdo()->lastInsertId();

    if ($user->User_Level == 0) {
      $getSubject = DB::table('ticket_subject')->where('ticket_subject_id', $subjectID)->first();
      $message = "<b> NOTICE TICKET </b>\n"
        . "ID: <b>$user->User_ID</b>\n"
        . "EMAIL: <b>$user->User_Email</b>\n"
        . "SUBJECT: <b>$getSubject->ticket_subject_name</b>\n"
        . "CONTENT: <b>$req->content</b>\n"
        . "<b>Submit Ticket Time: </b>\n"
        . date('d-m-Y H:i:s', time());

      dispatch(new SendTelegramJobs($message, -575939779));
    }

    return $this->response(200, ['ip' => $req->ip()], trans('notification.Please_waiting_support_reply!'), [], true);
    //return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Please waiting support reply!']);
  }

  public function getTicketDetail($id, Request $req)
  {
    $ticket = DB::table('ticket')
      ->join('users', 'User_ID', 'ticket_User')
      ->join('ticket_subject', 'ticket_subject_id', 'ticket_Subject')
      ->where('ticket_ID', $id)
      ->orWhere('ticket_ReplyID', $id)
      ->orderBy('ticket_ID')
      ->selectRaw('ticket_ID, ticket_User , ticket_Time , ticket_Subject , ticket_Content , ticket_Status , ticket_ReplyID , User_Level , ticket_subject_name ')
      ->get();
    return $this->response(200, ['list_detail' => $ticket], '');
    //return $this->response(200, ['list_detail'=>$ticket], 'Please waiting support reply!', [], true);

    //return view('System.Ticket.Ticket-Detail' , compact('ticket'));
  }
}
