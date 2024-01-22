<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Excel;
use App\Exports\VoucherListExport;
use App\Exports\VoucherExport;
class VoucherController extends Controller
{
  public function getVoucherList(Request $req){
    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $list = DB::table('voucherList')->join('users', 'voucherList.User_ID', 'users.User_ID');
    $user = session('user');
    if($req->UserID){
      $list = $list->where('voucherList.User_ID', $req->UserID);
    }
    if($req->datetime){
      $list = $list->where('created_at', '<', date('Y-m-d 00:00:00', (strtotime($req->datetime) + 86400)));
    }
    if($req->level){
      if($req->level == 'Member'){
        $list = $list->where('User_Level', 0);
      }else{
        $list = $list->where('User_Level', $req->level); 
      }

    }
    if($req->amountfrom){
      $list = $list->where('amount', '>=', $req->amountfrom);
    }
    if($req->amounto){
      $list = $list->where('amount', '<=', $req->amounto);
    }
    if($req->type){
      if($req->type == 1){
        $list = $list->where('type', $req->type);
      }else{
        $list = $list->where('type', '!=', 1);
      }
    }
    if($req->export){
      if ($user->User_Level != 1 && $user->User_Level != 2 ) {
        dd('Stop');
      }
      ini_set('memory_limit', '2048M');
      $list = $list->orderByDesc('id')->get();

      ob_end_clean();
      ob_start();
      return Excel::download(new VoucherListExport($list), 'VoucherListExport.xlsx');
    }
    $list = $list->orderBy('id', 'DESC')->paginate(50);
    return view('System.Admin.VoucherList', compact('list', 'level'));
  }

  public function getVoucher(Request $req){
    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'Bot');
    $list = DB::table('voucher')->join('users', 'voucher.User_ID', 'users.User_ID');
    $user = session('user');
    if($req->UserID){
      $list = $list->where('voucher.User_ID', $req->UserID);
    }
    if($req->level){
      if($req->level == 'member'){
        $list = $list->where('User_Level', 0);
      }else{
        $list = $list->where('User_Level', $req->level); 
      }

    }
    if($req->type){
      $list = $list->where('type', $req->type);
    }
    if($req->datetime){
      $list = $list->where('datetime', '<', date('Y-m-d 00:00:00', (strtotime($req->datetime) + 86400)));
    }
    if($req->status){
      if($req->status == 1){
        $list = $list->where('status', $req->status);
      }else{
        $list = $list->where('status', '!=', 1);
      }
    }
    if($req->export){
      if ($user->User_Level != 1 && $user->User_Level != 2 ) {
        dd('Stop');
      }
      ini_set('memory_limit', '2048M');
      $list = $list->orderByDesc('id')->get();
      ob_end_clean();
      ob_start();
      
      return Excel::download(new VoucherExport($list), 'VoucherExport.xlsx');
    }
    $list = $list->orderBy('id', 'DESC')->paginate(50);
    return view('System.Admin.Voucher', compact('list', 'level'));
  }
}
