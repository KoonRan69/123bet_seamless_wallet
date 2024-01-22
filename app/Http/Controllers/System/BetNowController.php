<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Excel;
use App\Imports\AginSlotLastWeek;
use App\Imports\AginSlotDay;
use App\Imports\AginSlot;

use App\Model\User;

use App\Imports\AginHunterFishLastWeek;
use App\Imports\AginHunterFishDay;
use App\Imports\AginHunterFish;

use App\Imports\CasinoOnline;
use App\Imports\CasinoOnlineLastWeek;
use App\Imports\CasinoWMLastWeek;
use App\Imports\CasinoWM;
use App\Imports\CasinoWMDay;
use App\Imports\SportBook;
use App\Imports\SportBookDay;
use App\Imports\AginSportBook;
use App\Imports\AginSportDay;
use App\Imports\AginSportLastWeek;
use App\Imports\AeSexyLastWeek;
use App\Imports\AeSexyDay;
use App\Imports\AeSexyThisWeek;
use App\Imports\SbobetThisWeek;
use App\Imports\SbobetThisDay;
use App\Imports\EvolutionThisWeek;
use App\Imports\EvolutionThisDay;

use App\Model\BetHistorySbobet;
use App\Model\BetHistorySbobetCasino;
use App\Model\BetHistorySbobetVirtualSport;
use App\Model\BetHistorySbobetSeamless;
use App\Model\BetHistorySbobetThirdPartySportsBook;

use App\Exports\ExportSbobetSeamless;
use App\Exports\ExportSbobet;
use App\Exports\ExportSbobetCasino;
use App\Exports\ExportSbobetThirdPartySportsBook;
use App\Exports\ExportSbobetVirtualSport;

use App\Exports\ExportAginSportBook;
use App\Exports\ExportAginSlot;
use App\Exports\ExportAginHunterFish;
use App\Exports\ExportEvolution;
use App\Exports\ExportAeSexy;
use App\Exports\ExportHistorySbobet;
use App\Exports\ExportHistoryEvolution;

use DateTime;
class BetNowController extends Controller
{

  public function __construct()
  {
    $this->config = config('urlSBOBET.sbobet'); 
  }
  public function postImportVolumeEvolutions(Request $req){
    if($req->hasFile('ImportTotalWeekEvolution')){
      if($req->week == 'last'){
        Excel::import(new EvolutionThisWeek, $req->file('ImportTotalWeekEvolution'));
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data evolution total week success"]);
      }
      if($req->week == 'day'){
        Excel::import(new EvolutionThisDay, $req->file('ImportTotalWeekEvolution'));
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data evolution total week success"]);
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Import type has not been selected"]);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Not found file"]);
  }
  public function postImportVolumeSbobets(Request $req){
    if($req->hasFile('ImportThisWeelSbobet')){
      if($req->week == 'last'){
        Excel::import(new SbobetThisWeek, $req->file('ImportThisWeelSbobet'));
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data sbobet total week success"]);
      }
      if($req->week == 'day'){
        Excel::import(new SbobetThisDay, $req->file('ImportThisWeelSbobet'));
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data sbobet total week success"]);
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Import type has not been selected"]);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "not found file"]);
  }
  public function getHistorySbobets(Request $request){
    //date_default_timezone_set("Asia/Ho_Chi_Minh");
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');

    if($request->type_date && $request->type_date === 'day'){
      $gameWallet = DB::table('show_history_sbobet')->join('users', 'userId', '=', 'users.user_ID');
    }else{
      $gameWallet = DB::table('bet_history_sbobet_ib')->join('users', 'userId', '=', 'users.user_ID');
    }

    if($request->user_id){
      $gameWallet = $gameWallet->where('userId',($request->user_id));
    }
    if (Input::get('User_Level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('User_Level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('time_123betnow', '>=', ($request->datefrom.' 00:00:00'))
        ->where('time_123betnow', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('time_123betnow', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('time_123betnow', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportHistorySbobet($gameWallet), 'history-sbobet.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    //dd($gameWallet);

    if($request->type_date || $request->type_date === 'day'){
      $columnTable = DB::getSchemaBuilder()->getColumnListing('show_history_sbobet');
    }else{
      $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_sbobet_ib');
    }


    //dd($columnTable);
    return view('System.Admin.Game-History-Sbobets', compact('gameWallet', 'columnTable', 'level'));
  }
  public function getHistoryEvolutions(Request $request){
    //date_default_timezone_set("Asia/Ho_Chi_Minh");
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');


    if($request->type_date && $request->type_date === 'day'){
      $gameWallet = DB::table('show_history_evolution')->join('users', 'userId', '=', 'users.user_ID');
    }else{
      $gameWallet = DB::table('bet_history_evolution')->join('users', 'userId', '=', 'users.user_ID');
    }

    if($request->user_id){
      $gameWallet = $gameWallet->where('userId',($request->user_id));
    }
    if (Input::get('User_Level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('User_Level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('time_123betnow', '>=', ($request->datefrom.' 00:00:00'))
        ->where('time_123betnow', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('time_123betnow', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('time_123betnow', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportEvolution($gameWallet), 'history-evolution.xlsx');
    }
    if($request->user_id){
       $gameWallet = $gameWallet->orderByDesc('id')->paginate(20);
    }else{
       $gameWallet = $gameWallet->orderByDesc('id')->limit(500)->paginate(20);
    }
   


    if($request->type_date || $request->type_date === 'day'){
      $columnTable = DB::getSchemaBuilder()->getColumnListing('show_history_evolution');
    }else{
      $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_evolution');
    }

    return view('System.Admin.Game-History-Evolutions', compact('gameWallet', 'columnTable', 'level'));
  }


  public function getAeSexy(Request $request){
    //date_default_timezone_set("Asia/Ho_Chi_Minh");
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = DB::table('bet_history_ae_sexy')->join('users', 'userId', '=', 'users.user_ID');
    if($request->user_id){
      $gameWallet = $gameWallet->where('userId',($request->user_id));
    }
    if (Input::get('User_Level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('User_Level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('updateTime', '>=', ($request->datefrom.' 00:00:00'))
        ->where('updateTime', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('updateTime', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('updateTime', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportAeSexy($gameWallet), 'history-ae-sexy.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_ae_sexy');
    //dd($columnTable);
    return view('System.Admin.Game_AeSexy', compact('gameWallet', 'columnTable', 'level'));
  }

  public function postImportGameAeSexy(Request $req){
    if($req->hasFile('ImportTotalWeekAesexy')){
      if($req->week == 'last'){
        Excel::import(new AeSexyLastWeek, $req->file('ImportTotalWeekAesexy'));
      }elseif($req->week == 'day'){
        Excel::import(new AeSexyDay, $req->file('ImportTotalWeekAesexy'));
      }else{
        Excel::import(new AeSexyThisWeek, $req->file('ImportTotalWeekAesexy'));
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data Ae sexy total week success"]);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "not found file"]);
  }
  public function postImportGameAginHunterFish(Request $req){

    if($req->hasFile('ImportTotalWeekAgin')){
      if($req->week == 'last'){
        Excel::import(new AginHunterFishLastWeek, $req->file('ImportTotalWeekAgin'));
      }elseif($req->week == 'day'){
        Excel::import(new AginHunterFishDay, $req->file('ImportTotalWeekAgin'));
      }else{
        Excel::import(new AginHunterFish, $req->file('ImportTotalWeekAgin'));
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data Agin Hunter Fish total week success"]);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "not found file"]);
  }
  public function getAginHunterFish(Request $request){
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = DB::table('bet_history_agin_hunterfish')->join('users', 'userid', '=', 'users.user_ID');
    if($request->user_id){
      $gameWallet = $gameWallet->where('userid',($request->user_id));
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'))
        ->where('create_date', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportAginHunterFish($gameWallet), 'history-agin-hunterfish.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_agin_hunterfish');
    //dd($columnTable);
    return view('System.Admin.Game-AginHunterFish', compact('gameWallet', 'level', 'columnTable'));
  }
  public function postImportGameAginSlot(Request $req){
    if($req->hasFile('ImportTotalWeekAgin')){
      if($req->week == 'last'){
        Excel::import(new AginSlotLastWeek, $req->file('ImportTotalWeekAgin'));
      }elseif($req->week == 'day'){
        Excel::import(new AginSlotDay, $req->file('ImportTotalWeekAgin'));
      }else{
        Excel::import(new AginSlot, $req->file('ImportTotalWeekAgin'));
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data Agin Slot total week success"]);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "not found file"]);
  }
  public function getAginSlot(Request $request){
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = DB::table('bet_history_agin_slot')->join('users', 'userid', '=', 'users.user_ID');
    if($request->user_id){
      $gameWallet = $gameWallet->where('userid',($request->user_id));
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'))
        ->where('create_date', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportAginSlot($gameWallet), 'history-agin-slot.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_agin_slot');
    return view('System.Admin.Game-AginSlot', compact('gameWallet', 'level', 'columnTable'));
  }

  public function postImportGameAginSportBook(Request $req){
    if($req->hasFile('ImportTotalWeekAgin')){
      if($req->week == 'last'){
        Excel::import(new AginSportLastWeek, $req->file('ImportTotalWeekAgin'));
      }elseif($req->week == 'day'){
        Excel::import(new AginSportDay, $req->file('ImportTotalWeekAgin'));
      }else{
        Excel::import(new AginSportBook, $req->file('ImportTotalWeekAgin'));
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data Agin SportBook total week success"]);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "not found file"]);
  }
  public function getAginSportBook(Request $request){
    $type =array(0 => 'credit',1 => 'debit',2 =>'balance',3 => 'deposit' ,4 => 'withdraw');
    // dd( $type[1]);
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = DB::table('bet_history_agin')->join('users', 'userid', '=', 'users.user_ID');

    // dd($type[$request->type]);
    if($request->user_id){
      $gameWallet = $gameWallet->where('userid',($request->user_id));
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'))
        ->where('create_date', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('create_date', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportAginSportBook($gameWallet), 'history-agin-sportbook.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_agin');
    //dd($columnTable);
    return view('System.Admin.Game-AginSportBook', compact('gameWallet','type', 'level', 'columnTable'));
  }
  public function getGameWalletWM(Request $request){
    $type =array(0 => 'credit',1 => 'debit',2 =>'balance',3 => 'deposit' ,4 => 'withdraw');
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $table = "total_history_wm";
    if($request->day == 1){
      $table = "history_wm";
    }
    $gameWallet = DB::table($table)->join('users', $table.'.Username', '=', 'users.user_ID');
    if($request->user_id){
      $gameWallet = $gameWallet->where('Username',$request->user_id);
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('CreatedAt', '>=', ($request->datefrom.' 00:00:00'))
        ->where('CreatedAt', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('CreatedAt', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('CreatedAt', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id');
      Excel::create('History-Games' . date('YmdHis'), function ($excel) use ($gameWallet, $type) {
        $excel->sheet('report', function ($sheet) use ($gameWallet, $type) {
          $sheet->appendRow(array(
            'ID', 'User ID', 'Amount', 'DateTime', 'Win/Loss'
          ));
          $gameWallet->chunk(2000, function ($rows) use ($sheet, $type) {
            foreach ($rows as $row) {

              $sheet->appendRow(array(

                $row->id,
                $row->Username,
                $row->BetAmount,
                $row->CreatedAt,
                $row->WinLoss,

              ));
            }
          });
        });
      })->export('xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing($table);
    //$totalWeek = DB::table('totalhistorysa')->join('users', 'totalhistorysa.Username', '=', 'users.user_ID')->get();
    //dd($gameWallet);
    return view('System.Admin.Game-WM', compact('gameWallet','type', 'level', 'totalWeek', 'columnTable'));
  }
  public function getSportBook(Request $request){
    $type =array(0 => 'credit',1 => 'debit',2 =>'balance',3 => 'deposit' ,4 => 'withdraw');
    // dd( $type[1]);
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $table = 'sportbook_history';
    if($request->day == 1){
      $table = 'sportbook_history_day';
    }
    $gameWallet = DB::table($table)->join('users', 'account', '=', 'users.user_ID');

    // dd($type[$request->type]);
    if($request->user_id){
      $gameWallet = $gameWallet->where('account',($request->user_id));
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('CreatedAt', '>=', ($request->datefrom.' 00:00:00'))
        ->where('created_at', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id');
      Excel::create('History-Games' . date('YmdHis'), function ($excel) use ($gameWallet, $type) {
        $excel->sheet('report', function ($sheet) use ($gameWallet, $type) {
          $sheet->appendRow(array(
            'ID', 'User ID', 'Amount', 'DateTime', 'Win/Loss'
          ));
          $gameWallet->chunk(2000, function ($rows) use ($sheet, $type) {
            foreach ($rows as $row) {

              $sheet->appendRow(array(

                $row->id,
                $row->Username,
                $row->BetAmount,
                $row->CreatedAt,
                $row->WinLoss,

              ));
            }
          });
        });
      })->export('xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing($table);
    //dd($columnTable);
    return view('System.Admin.Game-SportBook', compact('gameWallet','type', 'level', 'columnTable'));
  }
  public function postImportGameSA(Request $req){
    if($req->hasFile('ImportTotalWeekWM')){
      if($req->week == 'last'){
        Excel::import(new CasinoWMLastWeek, $req->file('ImportTotalWeekWM'));
      }elseif($req->week == 'day'){
        Excel::import(new CasinoWMDay, $req->file('ImportTotalWeekWM'));
      }else{
        Excel::import(new CasinoWM, $req->file('ImportTotalWeekWM'));
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data WM555 total week success"]);
    }
    if($req->hasFile('ImportTotalWeek')){
      if($req->week == 'last'){
        //dd($req);
        Excel::import(new CasinoOnlineLastWeek, $req->file('ImportTotalWeek'));
      }else{
        Excel::import(new CasinoOnline, $req->file('ImportTotalWeek'));
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data total week success"]);
      dd(123);
      Excel::load($req->file('ImportTotalWeek'), function($reader) {
        $data = $reader->toArray();
        $dataInsert = [];
        $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $getListImported = DB::table('totalhistorysa')->where('CreatedAt', '>=', $mondayThisWeek)->pluck('Username')->toArray();
        foreach($data as $row)
        {
          $username = str_replace('@ebp', '', $row['username']);
          $username = str_replace('NOW', '', $row['username']);
          if(array_search($username, $getListImported) !== false){
            continue;
          }
          $dataInsert[] = [
            'Level' => $row['level'],
            'Username' => $username,
            'Currency' => $row['currency'],
            'NumberOfTransactions' => $row['number_of_transactions'],
            'BetAmount' => $row['bet_amount'],
            'WinLoss' => $row['winloss'],
            'RollingAmount' => $row['rolling_amount'],
            'RollingRate' => $row['rolling_rate'],
            'RollingCommission' => $row['rolling_commission'],
            'MemberTotal' => $row['member_total'],
            'CreatedAt' => date('Y-m-d H:i:s'),
            'UpdatedAt' => date('Y-m-d H:i:s'),
          ];
        }
        DB::table('totalhistorysa')->insert($dataInsert);
      })->get();
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data total week success"]);
    }
    if($req->hasFile('SportBook')){
      if($req->week == 'day'){
        Excel::import(new SportBookDay, $req->file('SportBook'));
      }else{
        Excel::import(new SportBook, $req->file('SportBook'));
      }
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "imported data total week success"]);
      Excel::load($req->file('SportBook'), function($reader) {
        $data = $reader->toArray();
        dd($data);
        $dataInsert = [];
        $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $getBetImported = DB::table('sagame_log')->pluck('bet_id')->toArray();
        foreach($data as $row)
        {
          $betID = $row['bet_id'];
          if(array_search($betID, $getBetImported) !== false){
            continue;
          }
          $convertRow = [];
          foreach($row as $key=>$value){
            $value = str_replace("'", "", $value);
            $convertRow[$key] = $value;
          }
          $convertRow['user_id'] = str_replace('@ebp', '', $row['member']);
          $dataInsert[] = $convertRow;
        }
        DB::table('sagame_log')->insert($dataInsert);
      })->get();
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "ok roi"]);
    }
    if($req->hasFile('ImportBetDetail')){
      Excel::load($req->file('ImportBetDetail'), function($reader) {
        $data = $reader->toArray();
        $dataInsert = [];
        $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $getBetImported = DB::table('sagame_log')->pluck('bet_id')->toArray();
        foreach($data as $row)
        {
          $betID = $row['bet_id'];
          if(array_search($betID, $getBetImported) !== false){
            continue;
          }
          $convertRow = [];
          foreach($row as $key=>$value){
            $value = str_replace("'", "", $value);
            $convertRow[$key] = $value;
          }
          $convertRow['user_id'] = str_replace('@ebp', '', $row['member']);
          $dataInsert[] = $convertRow;
        }
        DB::table('sagame_log')->insert($dataInsert);
      })->get();
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "ok roi"]);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "not found file"]);
  }
  public function getGameWalletSA(Request $request){
    $type =array(0 => 'credit',1 => 'debit',2 =>'balance',3 => 'deposit' ,4 => 'withdraw');
    // dd( $type[1]);
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $table = "sa_history";
    if($request->total){
      $table = "totalhistorysa";
    }else{
      //$gameWallet = DB::table('sa_history')->join('users', 'sa_history.Username', '=', 'users.user_ID');
    }
    $gameWallet = DB::table($table)->join('users', $table.'.Username', '=', 'users.user_ID');

    // dd($type[$request->type]);
    if($request->user_id){
      $gameWallet = $gameWallet->where('Username',$request->user_id);
    }
    //dd($gameWallet->get());
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('CreatedAt', '>=', ($request->datefrom.' 00:00:00'))
        ->where('CreatedAt', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('CreatedAt', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('CreatedAt', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('id');
      Excel::create('History-Games' . date('YmdHis'), function ($excel) use ($gameWallet, $type) {
        $excel->sheet('report', function ($sheet) use ($gameWallet, $type) {
          $sheet->appendRow(array(
            'ID', 'User ID', 'Amount', 'DateTime', 'Win/Loss'
          ));
          $gameWallet->chunk(2000, function ($rows) use ($sheet, $type) {
            foreach ($rows as $row) {

              $sheet->appendRow(array(

                $row->id,
                $row->Username,
                $row->BetAmount,
                $row->CreatedAt,
                $row->WinLoss,

              ));
            }
          });
        });
      })->export('xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing($table);
    //$totalWeek = DB::table('totalhistorysa')->join('users', 'totalhistorysa.Username', '=', 'users.user_ID')->get();
    //dd($gameWallet);
    return view('System.Admin.Game-SA', compact('gameWallet','type', 'level', 'totalWeek', 'columnTable'));
  }
  public function getGameEVO(Request $request){
    $type =array(0 => 'credit',1 => 'debit',2 =>'balance',3 => 'deposit' ,4 => 'withdraw');
    // dd( $type[1]);
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');

    $gameWallet = DB::table('bet_history_evo')->join('users', 'bet_history_evo.user_id', '=', 'users.user_ID');
    if($request->user_id){
      $gameWallet = $gameWallet->where('bet_history_evo.user_id',$request->user_id);
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'))
        ->where('created_at', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {

      $gameWallet = $gameWallet->orderByDesc('id')->get();
      return Excel::download(new ExportEvolution($gameWallet), 'history-evolution.xlsx');
    }

    $gameWallet = $gameWallet->orderByDesc('id')->paginate(50);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_evo');
    return view('System.Admin.Game-EVO', compact('gameWallet','type', 'level', 'totalWeek', 'columnTable'));
  }
  public function getHistorySA(Request $req){
    //$string = '2020-11-06T19:51:00.037';
    //dd(date('Y-m-d H:i:s', strtotime($string)));
    $getUsers = Money::join('users', 'User_ID', 'Money_User')->where('Money_MoneyAction', 30)->groupBy('Money_User')
      //->whereIn('Money_User', ['DAF2956047', 'DAF3348436'])
      ->paginate(5);
    //->get();
    //dd($getUsers);
    $yesterday = strtotime('-1 days');
    $Date = date('Y-m-d', $yesterday);
    //dd($yesterday, $Date);
    $dataInsert = [];
    $getBetImported = DB::table('sagame_log')->pluck('bet_id')->toArray();
    foreach($getUsers as $k=>$user){
      $query = '';
      $userID = $user->User_ID;
      $query = '?username='.$userID;
      $query .= '&date='.$Date;
      $requestHistory = file_get_contents('https://api.winboss.club/api/sagame/history'.$query);
      $dataResponse = json_decode($requestHistory);
      $gameWallet = $dataResponse->data->BetDetailList->BetDetail ?? (object)[];
      $gameWallet = json_decode(json_encode($gameWallet), true);
      foreach($gameWallet as $row){
        $betID = $row['BetID'];
        if(array_search($betID, $getBetImported) !== false){
          continue;
        }
        $convertRow = [];
        foreach($row as $key=>$value){
          if(is_array($value)){
            $convertRow[$key] = json_encode($value);
          }else{
            $value = str_replace("'", "", $value);
            $convertRow[$key] = $value;
          }
        }
        $convertRow['CreatedAt'] = date('Y-m-d H:i:s', strtotime($row['BetTime']));
        $convertRow['UpdatedAt'] = date('Y-m-d H:i:s', strtotime($row['BetTime']));
        $dataInsert[] = $convertRow;
      }
    }
    DB::table('sa_history')->insert($dataInsert);

    $page = $getUsers->currentPage();
    $lastPage = $getUsers->lastPage();
    $timeout = 320;
    if($page < $lastPage){
      return view('Cron.HistorySA',compact('page', 'timeout'));
    }
    $this->statisticalSANew($req);
    $page = 1;
    $timeout = 18000;
    return view('Cron.HistorySA',compact('page', 'timeout'));
    dd($dataInsert);
  }

  public function statisticalSANew(Request $req){
    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $date = date('Y-m-d H:i:s');
    $listTotalImportedWeek = DB::table('sa_history')->where('CreatedAt', '>=', $mondayThisWeek)/*->where('Username', 'DAF1481934 ')*/->where('statistical', 0)->get();
    //dd($listTotalImportedWeek);
    foreach($listTotalImportedWeek as $data){
      $user = User::find($data->Username);
      $getStatistical = DB::table('statistical')->where('statistical_Time', '>=', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      //dd($getStatistical, $data);
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      if($getStatistical){
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet += $data->BetAmount;
        if($data->ResultAmount < 0){
          $totalLoss += abs($data->BetAmount);
        }else{
          $totalWin += abs($data->BetAmount);
        }
        $updateStatistical = DB::table('statistical')
          ->where('statistical_Time', '>=', $mondayThisWeek)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);
      }else{
        $totalBet = $data->BetAmount;
        if($data->ResultAmount < 0){
          $totalLoss = abs($data->BetAmount);
        }else{
          $totalWin = abs($data->BetAmount);
        }
        $updateStatistical = DB::table('statistical')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => $date,
            'statistical_UpdateTime' => $date,
          ]);
      }
      $updateStatistical = DB::table('sa_history')->where('id', $data->id)->update(['statistical'=>1]);
    }
  }

  public function checkStatisticalSA(Request $req){
    if($req->key != '123123123'){
      abort(404);
    }
    $mondayLastWeek = date('Y-m-d H:i:s', strtotime('monday last week'));
    $mondayThisWeek = date('Y-m-d H:i:s', strtotime('monday this week'));
    $deleteStatistical = DB::table('statistical')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->delete();
    $listTotalImportedWeek = DB::table('totalhistorysa')->where('CreatedAt', '>=', $mondayThisWeek)/*->where('Username', 'DAF1481934 ')*/->where('Statistical', 0)->get();
    //dd($listTotalImportedWeek);
    foreach($listTotalImportedWeek as $data){
      $user = User::find($data->Username);
      $getStatistical = DB::table('statistical')->where('statistical_Time', '>=', $mondayLastWeek)->where('statistical_Time', '<', $mondayThisWeek)->where('statistical_User', $user->User_ID)->first();
      //dd($getStatistical, $data);
      $totalBet = 0;
      $totalWin = 0;
      $totalLoss = 0;
      if($getStatistical){
        $totalBet = $getStatistical->statistical_TotalBet;
        $totalWin = $getStatistical->statistical_TotalWin;
        $totalLoss = $getStatistical->statistical_TotalLost;
        $totalBet += $data->BetAmount;
        if($data->WinLoss < 0){
          $totalLoss += abs($data->WinLoss);
        }else{
          $totalWin += abs($data->WinLoss);
        }
        $updateStatistical = DB::table('statistical')
          ->where('statistical_Time', '>=', $mondayLastWeek)
          ->where('statistical_Time', '<', $mondayThisWeek)
          ->where('statistical_User', $user->User_ID)
          ->update([
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
          ]);
      }else{
        $totalBet = $data->BetAmount;
        if($data->WinLoss < 0){
          $totalLoss = abs($data->WinLoss);
        }else{
          $totalWin = abs($data->WinLoss);
        }
        $updateStatistical = DB::table('statistical')
          ->insert([
            'statistical_User'=>$user->User_ID,
            'statistical_TotalBet'=>$totalBet,
            'statistical_TotalWin'=>$totalWin,
            'statistical_TotalLost'=>$totalLoss,
            'statistical_Time' => date('Y-m-d H:i:s', strtotime('friday last week')),
            'statistical_UpdateTime' => date('Y-m-d H:i:s', strtotime('friday last week')),
          ]);
      }
      $updateStatistical = DB::table('totalhistorysa')->where('id', $data->id)->update(['Statistical'=>1]);
    }
    dd('update statistical success');
  }

  public function getSbobet(Request $request){
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = BetHistorySbobet::join('users', 'users.user_ID', 'bet_history_sbobet.user_id');
    if($request->user_id){
      $gameWallet = $gameWallet->where('bet_history_sbobet.user_id', $request->user_id);
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'))
        ->where('created_at', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet.id')->get();

      ob_end_clean();
      ob_start();
      return Excel::download(new ExportSbobet($gameWallet), 'bet_history_sbobet.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet.id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_sbobet');

    return view('System.Admin.Game-Sbobet', compact('gameWallet', 'level', 'columnTable'));
  }

  public function getSbobetCasino(Request $request){
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = BetHistorySbobetCasino::join('users', 'users.user_ID', 'bet_history_sbobet_casino.user_id');
    if($request->user_id){
      $gameWallet = $gameWallet->where('bet_history_sbobet_casino.user_id', $request->user_id);
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'))
        ->where('created_at', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet_casino.id')->get();

      ob_end_clean();
      ob_start();
      return Excel::download(new ExportSbobetCasino($gameWallet), 'bet_history_sbobet_casino.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet_casino.id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_sbobet_casino');

    return view('System.Admin.Game-Sbobet-Casino', compact('gameWallet', 'level', 'columnTable'));
  }

  public function getSbobetVirtualSport(Request $request){
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = BetHistorySbobetVirtualSport::join('users', 'users.user_ID', 'bet_history_sbobet_virtualsport.user_id');
    if($request->user_id){
      $gameWallet = $gameWallet->where('bet_history_sbobet_virtualsport.user_id', $request->user_id);
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'))
        ->where('created_at', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet_virtualsport.id')->get();

      ob_end_clean();
      ob_start();
      return Excel::download(new ExportSbobetVirtualSport($gameWallet), 'bet_history_sbobet_virtualsport.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet_virtualsport.id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_sbobet_virtualsport');

    return view('System.Admin.Game-Sbobet-VirtualSport', compact('gameWallet', 'level', 'columnTable'));
  }

  public function getSbobetSeamless(Request $request){
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = BetHistorySbobetSeamless::join('users', 'users.user_ID', 'bet_history_sbobet_seamless.user_id');
    if($request->user_id){
      $gameWallet = $gameWallet->where('bet_history_sbobet_seamless.user_id', $request->user_id);
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'))
        ->where('created_at', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet_seamless.id')->get();

      ob_end_clean();
      ob_start();
      return Excel::download(new ExportSbobetSeamless($gameWallet), 'bet_history_sbobet_seamless.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet_seamless.id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_sbobet_seamless');

    return view('System.Admin.Game-Sbobet-Seamless', compact('gameWallet', 'level', 'columnTable'));
  }

  public function getSbobetThirdPartySportsBook(Request $request){
    $level = array(0 => 'Member', 1 => 'Admin', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot', 10 => 'Admin View', 15 => 'CSKH');
    $gameWallet = BetHistorySbobetThirdPartySportsBook::join('users', 'users.user_ID', 'bet_history_sbobet_ThirdPartySportsBook.user_id');
    if($request->user_id){
      $gameWallet = $gameWallet->where('bet_history_sbobet_ThirdPartySportsBook.user_id', $request->user_id);
    }
    if (Input::get('user_level') != null) {
      $gameWallet = $gameWallet->where('User_Level', Input::get('user_level'));
    }
    if ($request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'))
        ->where('created_at', '<', ($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '>=', ($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $gameWallet = $gameWallet->where('created_at', '<', ($request->dateto.' 23:59:59') );
    }
    if ($request->export) {
      $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet_ThirdPartySportsBook.id')->get();

      ob_end_clean();
      ob_start();
      return Excel::download(new ExportSbobetThirdPartySportsBook($gameWallet), 'bet_history_sbobet_ThirdPartySportsBook.xlsx');
    }
    $gameWallet = $gameWallet->orderByDesc('bet_history_sbobet_ThirdPartySportsBook.id')->paginate(50);
    //dd($gameWallet);
    $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_sbobet_ThirdPartySportsBook');

    return view('System.Admin.Game-Sbobet-ThirdPartySportsBook', compact('gameWallet', 'level', 'columnTable'));
  }

  public function getBalancePlayer(Request $request){
    $user_data = User::select('*');
    if($request->User_Name_Sbobet){
      $user_data = $user_data->where('User_Name_Sbobet', $request->User_Name_Sbobet)->whereNotNull('User_Sbobet_Password')->get();
    }else{
      $user_data = $user_data->whereNotNull('User_Name_Sbobet')->whereNotNull('User_Sbobet_Password')->get();
    }

    if (!$user_data) {
      dd('không có người dùng');
    }
    $url = $this->config['url'].'/web-root/restricted/player/get-player-balance.aspx';
    $array_result = [];
    foreach($user_data as $item){ 
      $body = [
        "Username" => $item->User_Name_Sbobet,
        "CompanyKey"=> $this->config['CompanyKey'],
        "ServerId"=> $this->config['ServerId'],
      ];
      $topup_str = json_encode($body);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
        // 'Content-Length: '.strlen($topup_str),
      ));

      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
      $result = curl_exec($ch);
      $err = curl_error($ch);

      curl_close($ch);

      $check= json_decode($result);

      //dd($check);
      array_push($array_result, $check);

    }
    //dd($array_result);
    return  view('System.Admin.Balance-Game-Sbobet', compact('array_result'));
  }

}
