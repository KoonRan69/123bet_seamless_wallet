<?php

namespace App\Http\Controllers\API;
use App\Exports\InvesmentExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Money;
use App\Model\Log;
use App\Model\Profile;
use App\Model\LogAdmin;
use App\Model\LogUser;
use App\Model\Eggs;
use App\Model\Foods;
use App\Model\Pools;
use App\Model\subAccount;
use App\Model\Investment;
use App\Model\Complaints;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LicenseController extends Controller
{
  public function listCurrency(){
    $list = DB::table('currency')->get();
    return $this->response(200, $list);
  }
  public function postComplaints(Request $req){
    $validator = Validator::make($req->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'message' => 'required|',
    ],[
      /*'name.required' => trans('notification.address_requaired'),
      'email.email' => trans('notification.Amount_must_be_greater_than_0'),
      'addmessage.required' => trans('notification.coin_required!'),*/
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if($req->playerid != null){
      $checkUser = User::find($req->playerid);
      if(!$checkUser){
        return $this->response(200, [], 'Player ID not found', [], false);
      }
    }
    if($req->type != 1 && $req->type != 2)  return $this->response(200, [], 'Error!', [], false);
    
    $data = new Complaints();
    $data->name = $req->name;
    $data->email = $req->email;
    $data->website = $req->website;
    $data->playerid = $req->playerid;
    $data->message = $req->message;
    $data->disputed_amount = $req->disputed_amount;
    $data->currency = $req->currency;
    $data->type = $req->type;
    $data->save();

    if($req->type == 1) $message = 'Send complaints success';
    else $message = 'Send self-exclusion success';
    return $this->response(200, [], $message, [], true);
  }
}
