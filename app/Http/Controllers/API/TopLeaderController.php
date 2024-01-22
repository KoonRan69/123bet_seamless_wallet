<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Model\Money;
use App\Model\User;
use App\Model\GameBet;
use App\Jobs\SendTelegramJobs;
class TopLeaderController  extends Controller
{
  	public function getListTopTrader()
    {	
      	$arrBonusTop = [
           1 => ['amount'=>1000],
           2 => ['amount'=>750],
           3 => ['amount'=>500],
           4 => ['amount'=>0],
           5 => ['amount'=>0],
           6 => ['amount'=>0],
           7 => ['amount'=>0],
           8 => ['amount'=>0],
           9 => ['amount'=>0],
           10 => ['amount'=>0],
        ];
      	$dateStart = date('Y-m-d', strtotime('2022-01-25 03:30:00'));
      	$dateEnd = date('Y-m-d', strtotime('2022-02-26 00:00:00'));
      	$dateNow = date('Y-m-d');
      	$user_auth = Auth::user();
      	//if(strtotime($dateNow) > strtotime($dateEnd)){
          
        //}
	    //$user = User::find($user_auth->User_ID);
        //if(!$user){
            //return $this->response(200, [], "error!", [], false);
        //}
      	$list = DB::table('statistical_123betnow')
                            ->join('users', 'User_ID', 'statistical_User')
                            //->whereIn('User_Level', [1])
                            ->whereIn('User_Level', [0,5])
                            ->where('statistical_Currency', 3)
                            ->where('statistical_Time', '>=', $dateStart)
                            ->where('statistical_Time', '<', $dateEnd)
                            ->selectRaw('SUM(statistical_TotalBet) as totalBet, User_ID , User_Email, User_Tree')
                            ->groupBy('statistical_User')
          					->orderByDesc('totalBet')
                            //->having('totalBet', '>', 50000)
          					->limit(10)
                            ->get();
      
      	$data = [];
      	$id = 0;
      	//dd($list);
      	foreach($list as $k=>$user){
          	if($user->totalBet >= 50000){
                $id = $k+1;
                $amount = number_format($arrBonusTop[$id]['amount'], 0);
                $ib = '$'.number_format($user->totalBet,4);
                $userID=substr_replace($user->User_ID,"**",2,2);

                $data[]=['id'=>$id,'amount'=>$amount,'volume'=>$ib,'userID'=>$userID];
            }
        }
      	$numnberData = count($data);
      	$max = 0;
        if($numnberData < 10){
          $max = 10-$numnberData;
          for($i=$numnberData; $i < 10; $i++){
            $id = $id+1;
            
              	$data[]=['id'=>$id,'amount'=>'N/A','volume'=>'N/A','userID'=>'N/A'];
            
          }
        }
		//dd($data);
      	return $this->response(200, [ 'listTopTrader'=> $data], '', [], true);
    }
  
  	public function getInforTopTrader(){
      	$user_auth = Auth::user();
      	$user = User::find($user_auth->User_ID);
        if(!$user){
            return $this->response(200, [], trans('notification.user_does_not_exist'), [], false);
        }
      
      	$dateStart = date('Y-m-d', strtotime('2022-01-25 03:30:00'));
      	$dateEnd = date('Y-m-d', strtotime('2022-02-26 00:00:00'));
      	$dateNow = date('Y-m-d');
      	$list = DB::table('statistical_123betnow')
            				->where('statistical_User', $user->User_ID)
            				->where('statistical_Currency', 3)
            				->where('statistical_Time', '>=', $dateStart)
            				->where('statistical_Time', '<', $dateEnd)
            				->get();
      	$volume = $list->sum('statistical_TotalBet');
      	$status = '0';
      	if($volume >= 50000){
          $status = '1';
        }
      	$data = [
          'userID' => $user->User_ID,
          'status' => $status,
          'volume' => number_format($volume ?? 0, 4),
        ];
      
		//dd($data);
      	return $this->response(200, [ 'infoTopTrader'=> $data], '', [], true);
    }
  
    public function getInforTopLeader()
    {	
      	$dateCurrent = date('Y-m-d');
      	$user_auth = Auth::user();
	    $user = User::find($user_auth->User_ID);
        if(!$user){
            return $this->response(200, [], trans('notification.User_does_not_exist'), [], false);
        }
      	$dateStart = date('Y-m-d', strtotime('2022-01-25 03:30:00'));
      	$dateEnd = date('Y-m-d', strtotime('2022-02-26 00:00:00'));
      
      	$info = DB::table('commission_user')->where('user', $user->User_ID)->where('status', '!=', -1)->first();
      	$list = DB::table('statistical_123betnow')
            				->where('statistical_User', $user->User_ID)
            				->where('statistical_Currency', 3)
            				->where('statistical_Time', '>=', $dateStart)
            				->where('statistical_Time', '<', $dateEnd)
            				//->where('statistical_Time', '>=', date('Y-m-01'))
            				//->where('statistical_Time', '<=', date("Y-m-t", strtotime($dateCurrent)))
            				->get();
      	$volume = $list->sum('statistical_TotalBet');
      	$data = [
          'userID' => $user->User_ID,
          'status' => $info->status ?? 0,
          'volume' => number_format($volume ?? 0, 4),
          'ib' => number_format($info->amount ?? 0, 4),
        ];
      
		//dd($data);
      	return $this->response(200, [ 'infoTopLeader'=> $data], '', [], true);
    }
  
  	public function getListTopLeader()
    {	
      	$arrBonusTop = [
           1 => ['amount'=>1500],
           2 => ['amount'=>750],
           3 => ['amount'=>500],
           4 => ['amount'=>200],
           5 => ['amount'=>200],
           6 => ['amount'=>200],
           7 => ['amount'=>200],
           8 => ['amount'=>200],
           9 => ['amount'=>200],
           10 => ['amount'=>200],
        ];
      	$dateCurrent = date('Y-m-d');
      	$user_auth = Auth::user();
	    $user = User::find($user_auth->User_ID);
        if(!$user){
            return $this->response(200, [], trans('notification.error'), [], false);
        }
      	$listTop = DB::table('commission_user')
          				->leftJoin('users', 'users.User_ID', 'commission_user.user')
          				->whereIn('User_Level', [0,5])->where('status', 1)
          				->where('amount', '>', 0)
          				->orderByDesc('amount')
          				->limit(10)
          				->get();
      	$data = [];
      	$id = 0;
      	foreach($listTop as $k=>$user){
          	if($user->amount >= 10000){
                $id = $k+1;
                $amount = number_format($arrBonusTop[$id]['amount'], 0);
                $ib = '$'.number_format($user->amount,4);
                $userID=substr_replace($user->user,"**",2,2);

                $data[]=['id'=>$id,'amount'=>$amount,'ib'=>$ib,'userID'=>$userID];
            }
        }
      	
        if(count($data) < 10){
          $max = 10-count($data);
          for($i=count($data); $i < 10; $i++){
            $id = $id+1;
            /*if($user->User_Level != 1){
              	if($id == 1){
                    $data[]=['id'=>1,'amount'=>'N/A','ib'=>'$3,035,250','userID'=>'24**13'];
                }elseif($id == 2){
                    $data[]=['id'=>2,'amount'=>'N/A','ib'=>'$3,030,328','userID'=>'94**77'];
                }elseif($id == 3){
                    $data[]=['id'=>3,'amount'=>'N/A','ib'=>'$974,634','userID'=>'17**53'];
                }elseif($id == 4){
                    $data[]=['id'=>4,'amount'=>'N/A','ib'=>'$124,183','userID'=>'15**71'];
                }
                else{
                    $data[]=['id'=>$id,'amount'=>'N/A','ib'=>'N/A','userID'=>'N/A'];
                }
            }
            else{*/
              	$data[]=['id'=>$id,'amount'=>'N/A','ib'=>'N/A','userID'=>'N/A'];
            //}
            //$data[]=['id'=>$id,'amount'=>'N/A','ib'=>'N/A','userID'=>'N/A'];
          }
        }
		//dd($data);
      	return $this->response(200, [ 'listTopLeader'=> $data], '', [], true);
    }
}
