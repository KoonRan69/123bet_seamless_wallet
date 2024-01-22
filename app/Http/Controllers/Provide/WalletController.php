<?php

namespace App\Http\Controllers\Provide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Model\ProMoney;
use App\Model\MoneyAction;
use App\Exports\ExportWallet;
use Excel;

class WalletController extends Controller
{
  public function getWallet(Request $request){
    $user = Session::get('user');
    $action = ProMoney::groupBy('Money_MoneyAction')->join('moneyaction', 'pro_money.Money_MoneyAction', '=', 'moneyaction.MoneyAction_ID')->select('Money_MoneyAction', 'MoneyAction_Name')->get();
   
    //$action = MoneyAction::all();
    $walletList = ProMoney::where('Money_Parent_ID', $user->User_ID)->leftJoin('moneyaction', 'pro_money.Money_MoneyAction', '=', 'moneyaction.MoneyAction_ID');
    if($request->user_id){
      $walletList=  $walletList->where('Money_User', $request->user_id);
    }
    if($request->action){
      $walletList=  $walletList->where('Money_MoneyAction', $request->action);
    }
    if ($request->datefrom and $request->dateto) {
      $walletList = $walletList->where('Money_Time', '>=', strtotime($request->datefrom.' 00:00:00'))
        ->where('Money_Time', '<', strtotime($request->dateto.' 23:59:59'));
    }
    if ($request->datefrom and !$request->dateto) {
      $walletList = $walletList->where('Money_Time', '>=', strtotime($request->datefrom.' 00:00:00'));
    }
    if (!$request->datefrom and $request->dateto) {
      $walletList = $walletList->where('Money_Time', '<', strtotime($request->dateto.' 23:59:59') );
    }
	if($request->export){
      $walletList = $walletList->orderByDesc('Money_ID')->get();
      //dd($walletList);
      ob_end_clean();
      ob_start();
      return Excel::download(new ExportWallet($walletList), 'history-wallet.xlsx');
    }
    $walletList=  $walletList->orderBy('pro_money.Money_Time', 'DESC')
      ->paginate(50); 
    return view('Provide.wallet', compact('walletList', 'action'));
  }
}
