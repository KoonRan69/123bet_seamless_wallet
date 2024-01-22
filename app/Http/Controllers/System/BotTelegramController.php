<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Money;
use App\Model\User;
use Illuminate\Support\Facades\Storage;
use App\Model\Investment;
use App\Model\Stringsession;
use GuzzleHttp\Client;
use Session;
use DB;

class BotTelegramController extends Controller
{
  	public function addChanelBot(Request $req){
      //dd(123);
      	$req->validate([
          'name' => 'required',
        ]);
      	$user = session('user');
        if(!$user){
          return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']); 
        }
        if($user->User_Level != 1){
          return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Unauthorized!']); 
        }
        if($user->User_Active_BotTelegram != 1){
          return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Unauthorized!']); 
        }
      	$update = DB::table('chanel_bots')->insert([
          'name' => $req->name,
          'status' => 1,
        ]);
      	//dd($update);
      	if($update){
          return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Updated success!']); 
        }
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']); 
    }
  	public function getBotTelegram(){
      $user = session('user');
      if(!$user){
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']); 
      }
      if($user->User_Level != 1){
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Unauthorized!']); 
      }
      if($user->User_Active_BotTelegram != 1){
        return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Unauthorized!']); 
      }
      $idChanel = DB::table('chanel_bots')->where('status', 1)->orderByDesc('id')->first();
      //dd($idChanel);
      return view('System.Admin.BotTelegram', compact('idChanel'));
    }	
  
	public function postBotTelegram(Request $req){
      $a = $req;
      if(!$req->image && !$req->title && !$req->description){
        return response()->json(['status' => 'error', 'message' => 'Data Not Found!', 'data' => $a], 200);
      	//return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Data Not Found!']); 
      }
      $user = session('user');
      if(!$user){
        return response()->json(['status' => 'error', 'message' => 'Error!'], 200);
        //return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']); 
      }
      if($user->User_Level != 1){
        return response()->json(['status' => 'error', 'message' => 'Unauthorized!'], 200);
        //return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Unauthorized!']); 
      }
      if($user->User_Active_BotTelegram != 1){
        return response()->json(['status' => 'error', 'message' => 'Unauthorized!'], 200);
        //return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Unauthorized!']); 
      }
      $idChanel = DB::table('chanel_bots')->where('status', 1)->orderByDesc('id')->first();
      if(!$idChanel){
       	$chanel = 'chanel123betnow';
      }else{
        $chanel = $idChanel->name;
      }
      $img = '';
      if($req->image){
        $req->validate([
          'image' => 'image|mimes:jpeg,jpg,bmp,png,gif',
        ]);
        
        $imageExtension = $req->file('image')->getClientOriginalExtension();
        $imageStore = "message/image_".time().".".$imageExtension;
        $passportImageStatus = Storage::disk('ftp')->put($imageStore, fopen($req->file('image'), 'r+'));
        if($passportImageStatus){
          $img = config('url.media').$imageStore;
        }
      }
      $data = [
        'title'=>$req->title,
        'description'=>$req->description,
        'img'=>$img,
        'user_id'=>$user->User_ID
      ];
      $insert = DB::table('message_bots')->insert($data);
      if($insert){
        $client = new Client();
        if($req->image){
            $messageImg = $img;
            $res = $client->request('GET', "https://api.telegram.org/bot5279300935:AAEN8uupo_542FlZqKYuudB4NrlK2EOTAWw/sendPhoto?chat_id=@$chanel&photo=$messageImg", []);
      		$data = json_decode($res->getBody()->getContents());
        }
        if($req->title || $req->description){
          	$message = "<b>$req->title</b> \n" 
            ."$req->description";
          
            $res = $client->request('POST', "https://api.telegram.org/bot5279300935:AAEN8uupo_542FlZqKYuudB4NrlK2EOTAWw/sendMessage?chat_id=@$chanel&parse_mode=html&text=".urlencode($message), ['headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json']]);
      		$data = json_decode($res->getBody()->getContents());
        }
        return response()->json(['status' => 'success', 'message' => 'Success!'], 200);
        //return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Success!']); 
      }
    }
    
}
