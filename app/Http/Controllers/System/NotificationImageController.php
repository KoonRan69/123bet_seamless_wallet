<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Storage;
use Image;
use App\Model\Notification;

use App\Model\Money;
use App\Model\User;
use App\Model\Investment;
use Illuminate\Support\Facades\Auth;
use App\Model\Ticket;
use App\Model\TicketSubject;
use Illuminate\Support\Facades\Crypt;
use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;
use hash;
use DB;
use App\Jobs\SendMail;
class NotificationImageController extends Controller{
  public function getAPINotification(Request $req){

    if(!$req->type){
      return $this->response(200, [], __('app.please_enter_type'), [], false);
    }

    $notification = DB::table('notification')->orderByDesc('id')->get();
    if($req->type == 1){
      $notification = DB::table('notification')->where('landing', 1)->where('status', 1)->orderByDesc('id')->get();
    }if($req->type == 2){
      $notification = DB::table('notification')->where('system', 1)->where('status', 1)->orderByDesc('id')->get();
    }
    if($notification){
      return $this->response(200, $notification);
    }
  }
  public function getApiNotificationLanding(){
    $list_noti = DB::table('notification')->where('landing', 1)->where('status', 1)->orderByDesc('id')->first();
    $data = ['list_noti'=>$list_noti];
    return response(array('status'=>true, 'data'=>$data), 200);
  }
  public function getNoti(){
    //dd(date('Y-m-d H:i:s'));
    $notiImage = DB::table('notification')->where('status', '!=', -1)->orderBy('id','desc')->get();
    // 		dd($notiImage);
    return view('System.Admin.NotificationImage', compact('notiImage'));
  }	
  public function postNoti(Request $req){
    $user = session('user');
    $this->validate($req, 
                    [
                      'notification_image' => 'required|mimes:jpeg,jpg,png',
                    ]
                   );
    $landing = $req->landing;
    $system = $req->system;
    $promotion = $req->promotion;
    $exchange = $req->exchange;
    if($landing == '' && $system == '' && $promotion == '' && $exchange== ''){
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Please selected loaction"]);
    }
    $notificationImageExtension = $req->file('notification_image')->getClientOriginalExtension();
    // set folder and file name
    $randomNumber = uniqid();
    $notificationImageStore = "notification/notification_image_" . $user->User_ID . "_" . $randomNumber . "." . $notificationImageExtension;
    //send to Image server
    // return $passportImageSelfieStore;
    $notificationImageStatus = Storage::disk('s3')->put('123Betnow/'.$notificationImageStore, fopen($req->file('notification_image'), 'r+'));

    if ($notificationImageStatus) {
      $insert = [
        'image' => config('url.media').$notificationImageStore,
        'landing' => $landing,
        'system' => $system,
        'promotion' => $promotion,
      ];
      $inserStatus = DB::table('notification')->updateOrInsert($insert);
      if ($inserStatus) {
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "Update notification success!"]);
      }
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Update notification error!"]);

    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Update notification error!"]);

  }

  public function getHideNoti(Request $req, $id){
    $check_noti_image = DB::table('notification')->where('id', $id)->first();
    if($check_noti_image->status == 1){
      $updateNoti_image = DB::table('notification')->where('id', $id)->update([
        'status'=> 0
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Turn off notification Success!']);
    }else{

      $updateNoti_image = DB::table('notification')->where('id', $id)->update([
        'status'=> 1
      ]);
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Hanging notification Success!']);
    }

  }
  public function getDeleteNoti(Request $req, $id){
    $noti_image = DB::table('notification');
    $check_noti_image = DB::table('notification')->where('id', $id)->first();
    if($check_noti_image){
      $updateDeleNoti_image = DB::table('notification')->where('id', $id)->update([
        'status'=> -1
      ]);			
      return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Delete notification Success!']);
    }
    return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Delete notification Error!']);

  }
}
