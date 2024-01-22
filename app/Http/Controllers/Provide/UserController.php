<?php

namespace App\Http\Controllers\Provide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Model\ProUser;

class UserController extends Controller
{
    public function getUser(Request $request){
      $parent_id = session('user')->User_ID;
      $user_list = ProUser::where('User_Provide',$parent_id);
      if($request->user_id){
        $user_list = $user_list->where('User_ID', $request->user_id);
      }
      
      if($request->email){
        $user_list = $user_list->where('User_Email', $request->email);
      }
      
      $user_list = $user_list->orderBy('User_ID', 'ASC')->paginate(50);
      return view('Provide.users', compact('user_list'));
    }
  
}
