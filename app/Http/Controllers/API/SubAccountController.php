<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\Model\User; 
use App\Model\subAccount; 
use App\Model\logMoney; 
use Hash;
use App\Model\Money;
use App\Jobs\SendMail;
use App\Model\subAccountBalance;
use App\Model\HistorySubaccount;

use Illuminate\Support\Facades\Auth;
use App\Model\GoogleAuth;
use App\Jobs\SubAccountJobs;
use GuzzleHttp\Client;
use App\Http\Controllers\API\DESEncrypt;
use App\Http\Controllers\API\AgGameController;
use App\Http\Controllers\API\SAGameController;
use Illuminate\Support\Facades\Validator;
use Redirect;
class SubAccountController extends Controller{
	
	public $minGold = 100;
    public $key='Exchange123!';
    public $system = "WIN";
    public function getListSubaccount(){
        $user = Auth::user();
        if($user){
	        return response(array($user));
        }
        

    }
	public function postSubAccount(Request $req){
        $user = Auth::user();
		//check spam
        // $checkSpam = DB::table('string_token')->where('User', $user->User_ID)->where('Token', $req->CodeSpam)->first();
        
        
        // if($checkSpam == null){
        //     //khoong toonf taij
        //     // return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Misconduct!']);
        // }
        // else{
        //     DB::table('string_token')->where('User', $user->User_ID)->delete();
        // }
        $validator = Validator::make($req->all(), [
			'password' => 'required|min:6|max:12',
			'phone' => 'required'
		]);

		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				// return $error;
				return $this->response(200, [], $value, $validator->errors(), false);
			}
		}

		
		$subAccountID = $this->CreateSubAccountID($user->User_ID, $req->typeAccount);
		
		$insertData = array(
			'subAccount_ID' => strtolower($subAccountID),
			'subAccount_User' => $user->User_ID, 
			'subAccount_phone' => $req->phone,
			'subAccount_Type' => 0, 
			'subAccount_Password' => Hash::make($req->password), 
			'subAccount_PasswordNotHash' => $req->password,
			'subAccount_Balance' => 0, 
			'subAccount_RegisterDay' => date('Y-m-d H:i:s'), 
			'subAccount_LastLogin' => null, 
			'subAccount_Level' => $user->User_Level, 
			'subAccount_Status' => 1
        );

		$data_game = array(
			'subAccount_ID' => $subAccountID,
			'subAccount_Password' => $req->password, 
		);
/*
        $sagame = app('App\Http\Controllers\API\SAGameController')->register($subAccountID);
        $aggame = app('App\Http\Controllers\API\AgGameController')->registerGame($data_game);
		if($sagame!==true || $aggame !== true){
			return $this->response(200, [], 'Create sub account failed! Please contact admin', [], false);
        }
*/
       
      
		$insert = subAccount::insertSucAccount($insertData);
		
		if($insert){
            return $this->response(200, [], 'Create a new sub account success!');
        }
        return $this->response(200, [], 'Create a sub account failed! Please contact admin!', [], false);

	}
	// block-unblock subaccount
	// isblock = 0 block sub-account
	// isblock = 1 un-block sub-account

	public function isBlockSubAccount($id){
		$user = Auth::user();
		$check_isblock = subAccount::where('subAccount_ID', $id)->value('subAccount_Status');
		if($check_isblock != 0){
			$block = subAccount::changeStatusSucAccount($user->User_ID, $id, 0);

			if($block){
				return $this->response(200, [], 'Block a sub account success!', [], true);
			}
		}
		if($check_isblock != 1){
			$unblock = subAccount::changeStatusSucAccount($user->User_ID, $id, 1);

			if($unblock){
				return $this->response(200, [], 'Un-Block a sub account success!', [], true);
			}
		}
		
	}
	
	public function manageSubAccount(Request $req){
		$RandomToken = Money::RandomToken();
        $user_auth = Auth::user();
        $user = User::find($user_auth->User_ID);

		$main_balance = User::getBalance($user_auth->User_ID, 5);
		$subAccountList = subAccount::where('subAccount_User', $user->User_ID);
		if(isset($req->subaccount)){
			$subAccountList = $subAccountList->where('subAccount_ID', 'like', $req->subaccount);
		}
		if(isset($req->status)){
			$subAccountList = $subAccountList->where('subAccount_Status', $req->status);
		}
		$subAccountList = $subAccountList->select('subAccount_ID','subAccount_RegisterDay','subAccount_LastLogin','subAccount_SAaccount','subAccount_AGaccount','subAccount_SPaccount','subAccount_BOaccount')->get();
		
		

		$subList = array();
		for($i=0; $i<count($subAccountList) ; $i++){
			$subList[$i]['id']=$subAccountList[$i]['subAccount_ID'];
			$subList[$i]['registerTime']=$subAccountList[$i]['subAccount_RegisterDay'];
			$subList[$i]['lastLogin']=$subAccountList[$i]['subAccount_LastLogin'];
			$subList[$i]['status']=1;
			$subList[$i]['balanceGame']=0;
			$subList[$i]['balanceSportBook'] = 0;
			$subList[$i]['balanceCasino']=0;
			$subList[$i]['balanceBO']=0;
			if($subAccountList[$i]->subAccount_SAaccount == 1){
				$subList[$i]['balanceCasino']=(float)app('App\Http\Controllers\API\SAGameController')->checkBalance($subAccountList[$i]->subAccount_ID);	
			}
          	
          	if($subAccountList[$i]->subAccount_SPaccount == 1){
				$subList[$i]['balanceSportBook']=(float)app('App\Http\Controllers\API\BCSportController')->checkBalance($subAccountList[$i]->subAccount_ID);	
			}
          
          	if($subAccountList[$i]->subAccount_BOaccount == 1){
				$subList[$i]['balanceBO']=(float)subAccountBalance::getBalance($subAccountList[$i]->subAccount_ID);	
			}
          
          
			

			
		}
		
        $data = [
            "subAccountList" => $subList,
            "user" => $user,
            "RandomToken" => $RandomToken,
            "main_balance" => $main_balance,
          	"bo" => 'https://bo.winboss.club/',
          	"casino" => 'https://casino.winboss.club/',
          	"sportbook" => 'https://sportbook.winboss.club/',

        ];
        return $this->response(200, $data, "", [], true);
	}
	
	function CreateSubAccountID($user, $typeAccount = 1){
        $randomletter = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
		$subAccount = 'D';
		if($typeAccount == 0){
			$subAccount = 'L';
		}
		
		$subAccountName = $subAccount.$randomletter.$user;
		
		$checkSubAccount = subAccount::checkAccount($subAccountName);
		
		if(!$checkSubAccount){
			return $subAccountName;
		}
		return $this->CreateSubAccountID($user, $typeAccount);
    }
    
    public function depositSubAccount(Request $req){
		
		$validator = Validator::make($req->all(), [
			'password' => 'required|min:6|max:12',
			'amount' => 'required|numeric|min:1|max:999999',
			'typeDeposit' => 'required',
			'account' => 'required',
		]);

		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				// return $error;
				return $this->response(200, [], $value, $validator->errors(), false);
			}
		}

        $user_auth = Auth::user();
	    $user = User::find($user_auth->User_ID);
		$main_balance = User::getBalance($user_auth->User_ID, 5);
	    // kiểm tra Subaccount có bị block ko
		$subAccount = subAccount::where('subAccount_ID', $req->account)->where('subAccount_User', $user->User_ID)->first();
		
	    if(!$subAccount){
            return $this->response(200, [], "Sub-account does not exist", [], false);
        }
	    
	    if($subAccount->subAccount_Status != 1){
            return $this->response(200, [], "", "Sub-account has been locked", false);
	    }
	    
		
		if($req->amount > $main_balance){
            return $this->response(200, [], "", "Account balance is not enough", false);

		}
		
		$amount = abs($req->amount);
		
		
		$type_deposit = [
			1 => "subAccount_BalanceGame",
			2 => "subAccount_BalanceCasino",
			3 => "subAccount_BalanceBet",
			4 => "subAccount_BalanceBO"
		];
		if (Hash::check($req->password, $user->User_Password)) {
			$system = null;
			$orderid;
			if($req->typeDeposit == 1){
				$system = 'AG Game';
				// kiểm tra tk có tồn tại chưa
				if($subAccount->subAccount_AGaccount == 0){
					// dk tài khoảng
					$arr_register = array(
						'subAccount_ID' => $subAccount->subAccount_ID,
						'subAccount_Password' => $subAccount->subAccount_PasswordNotHash,
					);
					$aggame_register = app('App\Http\Controllers\API\AgGameController')->registerGame($arr_register);
					if($aggame_register){
						subAccount::where('subAccount_ID', $req->account)->where('subAccount_User', $user->User_ID)->update(['subAccount_AGaccount'=>1]);
					}
				}
				$arr_deposit = array(
					'user_ID'=> $user_auth->User_ID,
					'sub_id' => $subAccount->subAccount_ID,
					'amount' => $amount,
				);
                $aggame_deposit = app('App\Http\Controllers\API\AgGameController')->depositGame($arr_deposit);
        
				// $result = $this->depositGame($arr_deposit);
				if($aggame_deposit != true ){
                    return $this->response(200, [], "", "Game deposit failed", false);
				}
				$orderid = $aggame_deposit['orderid'];
			}elseif($req->typeDeposit == 2){
				$system = 'SA Game';
				if($subAccount->subAccount_SAaccount == 0){
					$sagame_register = app('App\Http\Controllers\API\SAGameController')->register($subAccount->subAccount_ID);
					if($sagame_register){
						subAccount::where('subAccount_ID', $req->account)->where('subAccount_User', $user->User_ID)->update(['subAccount_SAaccount'=>1]);
					}
					
				}
				$arr_depositsa = array(
					'user_ID'=> Auth::user()->User_ID,
					'username' => $subAccount->subAccount_ID,
					'amount' => $amount,

				);

                // $result = $this->postDebit($arr_depositsa);
                $sagame = app('App\Http\Controllers\API\SAGameController')->depositSA($arr_depositsa);

				if($sagame != true){
                    return $this->response(200, [], "", "Casino deposit failed", false);
				}
				$orderid = $sagame['orderID'];
            }elseif($req->typeDeposit == 3){
				$system = 'BCSport';
				if($subAccount->subAccount_SPaccount == 0){
					$bcsport_register = app('App\Http\Controllers\API\BCSportController')->postRegister($subAccount->subAccount_ID);
					if($bcsport_register){
						subAccount::where('subAccount_ID', $req->account)->where('subAccount_User', $user->User_ID)->update(['subAccount_SPaccount'=>1]);
					}
					
				}
				$arr_depositBCsport = array(
					'user_ID'=> Auth::user()->User_ID,
					'username' => $subAccount->subAccount_ID,
					'amount' => $amount,

				);

                // $result = $this->postDebit($arr_depositsa);
                $bcsport = app('App\Http\Controllers\API\BCSportController')->postCredit($subAccount->subAccount_ID, $amount);

				if($bcsport != true){
                    return $this->response(200, [], "", "bet sport deposit failed", false);
				}
				$orderid = $bcsport;
			}else if($req->typeDeposit == 4){
				$system = "Binary option";

				if($subAccount->subAccount_BOaccount == 0){
					$regis = subAccountBalance::insert(["sub"=>$req->account, "time"=>time()]);
					// $regis = $this->registerBO($arr_bo);
					if($regis){
						subAccount::where('subAccount_ID', $req->account)->where('subAccount_User', $user->User_ID)->update(['subAccount_BOaccount'=>1]);
					}

				}
				
				$update =  subAccountBalance::where('sub', $req->account)->increment('balance', $amount);
				
				if(!$update){
					return $this->response(200, [], "", "Binary option deposit failed", false);
				}
				$orderid = time();
			}
            $arrayInsert[] = array(
                'Money_User' => $user_auth->User_ID,
                'Money_USDT' => -$amount,
                'Money_USDTFee' => 0,
                'Money_Time' => time(),
                'Money_Comment' => $user_auth->User_ID." Deposit to system ".$system." account ".$subAccount->subAccount_ID." with : $".$amount,
                'Money_MoneyAction' => 1,
				'Money_MoneyStatus' => 1,
				'Money_TXID' => $orderid,
                'Money_Address' => null,
                'Money_Currency' => 5,
                'Money_CurrentAmount' => $amount,
                'Money_Rate' => 1,
                'Money_Confirm' => 0,
                'Money_Confirm_Time' => null,
                'Money_FromAPI' => 0,
            );

            if(count($arrayInsert)){
                Money::insert($arrayInsert);
            }
			
			
            return $this->response(200, [], "Deposit complete!", "", true);

			
		}else{
            return $this->response(200, [], "", "Incorrect password", false);

		}
		
		
    }
	
	
    public function withdrawSubAccount(Request $req){

		//check spam
        // $checkSpam = DB::table('string_token')->where('User', Session('user')->user_ID)->where('Token', $req->CodeSpam)->first();
        
        
        // if($checkSpam == null){
        //     //khoong toonf taij
        //     return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Misconduct!']);
        // }
        // else{
        //     DB::table('string_token')->where('User', Session('user')->user_ID)->delete();
        // }
		$validator = Validator::make($req->all(), [
			'amount' => 'required|numeric|min:1|max:999999',
			'typeWithdraw' => 'required',
			'account' => 'required',
		]);

		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				// return $error;
				return $this->response(200, [], $value, $validator->errors(), false);
			}
		}

        $user_auth = Auth::user();
	    $user = User::find($user_auth->User_ID);
        $main_balance = User::getBalance($user_auth->User_ID, 5);
	    // kiểm tra Subaccount có bị block ko
	    $subAccount = subAccount::where('subAccount_ID', $req->account)->where('subAccount_User', $user->User_ID)->first();
        
        if(!$subAccount){
            return $this->response(200, [], "Sub-account does not exist", [], false);
        }
	    
	    if($subAccount->subAccount_Status != 1){
            return $this->response(200, [], "", "Sub-account has been locked", false);
	    }
	    $amount = abs($req->amount);
	    
	    
		$type_deposit = [
			1 => "subAccount_BalanceGame",
			2 => "subAccount_BalanceCasino",
			3 => "subAccount_BalanceBet",
			4 => "subAccount_BalanceBO"
		];
		// $balance = subAccountBalance::getBalance($subAccount->subAccount_ID);
		$b = DB::table('subaccount')->where('subAccount_ID', $req->account)->value($type_deposit[$req->typeWithdraw]);
		
		$balance_sub = subAccount::getBalanceSub($req->account, $type_deposit[$req->typeWithdraw]);
		// var_dump($balance_sub);exit();
		if($req->Amount > $balance_sub){
            return $this->response(200, [], "", "Sub account balance is not enough", false);


		}
		
		
		
		if (Hash::check($req->password, $user->User_Password)) {
			$system = null;
			if($req->typeWithdraw == 2){
				$system = 'SA Game';
				$arr_withdrawSA = array(
					'user_ID'=> $user_auth->user_ID,
					'username' => $subAccount->subAccount_ID,
					'amount' => $amount,

                );
                $sagame = app('App\Http\Controllers\API\SAGameController')->postCreditSA($arr_withdrawSA);

				if($sagame != true){
                    return $this->response(200, [], "", "Casino withdraw failed", false);
				}
				
            }elseif($req->typeWithdraw == 3){
				$system = 'BCSport';
				$arr_withdrawBCSport = array(
					'user_ID'=> $user_auth->user_ID,
					'username' => $subAccount->subAccount_ID,
					'amount' => $amount,

                );
                $bcsport = app('App\Http\Controllers\API\BCSportController')->postDedit($subAccount->subAccount_ID, $amount);

				if($bcsport != true){
                    return $this->response(200, [], "", "bet sport withdraw failed", false);
				}
				
				$orderid = $bcsport;
			}elseif($req->typeWithdraw == 4){
				$system = "Binary option";	
              	// kiểm tra balance
              	$balanceBo = subAccountBalance::getBalance($subAccount->subAccount_ID);
              	if(abs($amount)>$balanceBo){
                  	return $this->response(200, [], "", "Binary option withdraw failed", false);
                }
              	
				$update =  subAccountBalance::where('sub', $subAccount->subAccount_ID)->increment('balance', -$amount);
				
				
				$orderid = time();
			}
            $arrayInsert[] = array(
                'Money_User' => $user_auth->User_ID,
                'Money_USDT' => $amount,
                'Money_USDTFee' => 0,
                'Money_Time' => time(),
                'Money_Comment' => $user_auth->User_ID." Withdraw from system ".$system.' account '.$subAccount->subAccount_ID." with : $".$amount,
                'Money_MoneyAction' => 2,
                'Money_MoneyStatus' => 1,
                'Money_Address' => null,
                'Money_Currency' => 5,
                'Money_CurrentAmount' => $amount,
                'Money_Rate' => 1,
                'Money_Confirm' => 0,
                'Money_Confirm_Time' => null,
                'Money_FromAPI' => 0,
            );
            if(count($arrayInsert)){
                Money::insert($arrayInsert);
            }
			return $this->response(200, [], "Withdraw complete!", "", true);
			


			
		}else{
            return $this->response(200, [], "", "Incorrect password", false);

		}
		
		
	}
	public function getLoginByID(Request $req){

		// return response(array('status'=>true, 'message'=>config('url.system').'exchange/game/gamelist?'.$req->id), 200);
		$result = DB::table('subAccount')->where('subAccount_ID', $req->id)->first();
		$data = $result->subAccount_ID.':'.time().':'.'systemBO123!';
		
		include(app_path() . '/functions/xxtea.php');
		$token = xxtea_encrypt($data, 'Exchange123!');
		$token = base64_encode($token);
		// $tid = xxtea_decrypt(base64_decode($token), 'Exchange123!');
		// dd($token, $tid);

		// $token = Crypt::encryptString($result->subAccount_ID.':'.$result->subAccount_Password);
		return response(array('status'=>true, 'message'=>config('url.system').'getlogin?tid='.urlencode($token)), 200);
		$user = session('user');
        if ($user->User_Level == 1 || $user->User_Level == 2) {
            $userLogin = User::find($id);
            if(Auth::attempt(['User_Email' => $userLogin->User_Email, 'password' => $userLogin->User_PasswordNotHash])){ 

				$user = Auth::user(); 
				$token = $user->createToken('EXCHANGE')->accessToken;
				
				$arrReturn = array('status'=>true, 'token'=>$token);
			
                $cmt_log = "Login ID User: " . $id;
                Log::insertLog(Session('user')->User_ID, "Login", 0, $cmt_log);
                
                // return redirect()->route('Dashboard', ['token'=>$]);
                // dd(config('url.system').'Gsd354Sdfhr4/oiewh3454Has54?token='.$token);
                // return redirect()->away(config('url.system').'Gsd354Sdfhr4/oiewh3454Has54?token='.$token);
                // return redirect::to(config('url.system').'system/dashboard');
                // return redirect()->route('Dashboard')->with(['flash_level' => 'success', 'flash_message' => 'Login Success']);
            }
        } else {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
        }
	}
    public function changePasswordSubAccount(Request $req){
		$this->validate($req, 
            [
                'OldPassword'         =>  'required',
                'Password'      =>  'required',
                'ConfirmPassword'    =>  'required|same:Password'
            ],
            [
                'OldPassword.required'   =>  'Password is Required',
                'Password.required'   =>  'New Password is Required',
                'ConfirmPassword.required'   =>  'Confirm Password is Required',
                'ConfirmPassword.same'   =>  'The Passwords do not same'
            ]
		);
		if($req->Password != $req->ConfirmPassword){
			return $this->response(200, [], "", "Password confirm does not match!", false);
		}
		$user_auth = Auth::user();
		$user = User::find($user_auth->User_ID);
		$subAccountsubAccount = subAccount::where('subAccount_ID', $req->account)->where('subAccount_User', $user->User_ID)->firstorfail();
		
		if (Hash::check($req->OldPassword, $subAccountsubAccount->subAccount_Password)){
			
			$update = subAccount::where('subAccount_ID', $req->account)->where('subAccount_User', $user->User_ID)->update(['subAccount_Password' => bcrypt($req->Password), 'subAccount_PasswordNotHash'=>$req->Password]);
			return $this->response(200, [], "Change password complete!", "", true);
		}
		return $this->response(200, [], "", "Current password does not match!", false);

    }
    public function getForgotPassSubAccount($id){
		//return redirect()->route('getForgotPass')->with(['flash_level'=>'error', 'flash_message'=>'Please Contact Support!']);
        if(!$id){
			return $this->response(200, [], "", "Missing Sub Account ID", false);
		}
		include(app_path() . '/functions/xxtea.php');
		$user = Auth::user();
        $resultSub = subAccount::join('users', 'User_ID', 'subAccount_User')->where('subAccount_ID', $id)->where('subAccount_User', $user->User_ID)->first();
        if(!$resultSub){
			return $this->response(200, [], "", "Account is not exist!", false);

        }
        $pass = $this->generateRandomString(6);
        
        $token = Crypt::encryptString($resultSub->User_ID.':'.$resultSub->subAccount_ID.':'.time().':'.$pass);
		$data = array('User_Email'=>$user->User_Email, 'account'=>$resultSub->subAccount_ID, 'pass'=>$pass,'token'=>$token);
        
		// gửi mail thông báo
		
        // dispatch(new SendMail('ForgotSubAccount', $data, 'New Password!', $resultSub->user_ID));
		return $this->response(200, [], "Please. check your email! We sent a new password to the email address you are register", "", true);
    }
    public function activePassSubAccount(Request $req){
        include(app_path() . '/functions/xxtea.php');
        $json = Crypt::decryptString($req->token);
        $json = explode(':', $json);
        if(count($json) < 3){
	        return redirect()->route('getLogin');
        }
        if(time() - $json[2] > 300){
            return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Token expires!']);
        }
        //update pass
        $pass = subAccount::join('users', 'user_ID', 'subAccount_User')->where('subAccount_ID', $json[1])->where('subAccount_User', $json[0])->update(['subAccount_Password' => bcrypt($json[3])]);
        if(!$pass){
	        return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Error! Please try again!']);
        }
		$user = User::find($json[0]);
        Session::put('user', $user);  
		return redirect()->route('system.dashboard')->with(['flash_level'=>'success', 'flash_message'=>'Your SubAccount Password Is Changed!']);
	}
	
	//get info transfer history
	public function getTransferHistory(Request $req){
		$this->validate($req, 
            [
                'account'         =>  'required',
                'start_date'      =>  'required',
				'end_date'    =>  'required|same:Password',
				'page'    =>  'required|number | min:1',
				
            ],
            [
                'account.required'   =>  'account does not struct',
                'start_date.required'   =>  'New Password is Required',
                'end_date.required'   =>  'Confirm Password is Required',
                'page.required'   =>  'The Passwords do not same'
            ]
		);
		

	}
	public function walletHistory(Request $req){
		$user = Auth::user();
		$data = Money::where('Money_User', $user->User_ID)
		->select('Money_ID', 'Money_Comment')
		->selectRaw('DATE_FORMAT(FROM_UNIXTIME(`Money_Time`), "%Y-%m-%d %H:%i:%s") as Money_Time')
		->orderBy('Money_Time', 'desc');
		// time = 124354352 => date d
        if(isset($req->Money_MoneyAction)){
            $data->where('Money_MoneyAction', $req->Money_MoneyAction);
        }
        if(isset($req->Money_ID)){
            $data->where('Money_ID', $req->Money_ID);
        }
        if(isset($req->Money_MoneyStatus)){
            $data->where('Money_MoneyStatus', $req->Money_MoneyStatus);
        }
        if(isset($req->from_date)){
            $data->whereDate('Money_Time','>=', $req->from_date);
        }
        if(isset($req->to_date)){
            $data->whereDate('Money_Time','<=', $req->to_date);
        }
        $data = $data->paginate(15);
        return $this->response(200, $data, '', [], true);

	}
	public function winlossReport(Request $req){
		$user_auth = Auth::user();
		
		$type = [
			1 => 'All',
			2 => 'Game Mini',
			3 => 'Casino online',
			4 => 'Sport book',
			5 => 'Binary option', 
		];
		
		if($req->type == 1 ){
			$game_history = app('App\Http\Controllers\API\AgGameController')->gethistory($req->account_id, $req->fromdate, $req->todate);
			if($game_history != true){
				return $this->response(200, [], 'Miss data', [], false);
			}
			$casino_history = app('App\Http\Controllers\API\SAGameController')->GetUserWinLost($req->account_id, $req->fromdate, $req->todate);
			if($casino_history != true){
				return $this->response(200, [], 'Miss data', [], false);
			}
			return [$game_history, $casino_history];
		}
		else if($req->type == 2){
			$game_history = app('App\Http\Controllers\API\AgGameController')->gethistory($req->account_id, $req->fromdate, $req->todate);
			if($game_history != true){
				return $this->response(200, [], 'Miss data', [], false);
			}
			return [$game_history];
		}
		else if($req->type == 3){
			$casino_history = app('App\Http\Controllers\API\SAGameController')->GetUserWinLost($req->account_id, $req->fromdate, $req->todate);
			if($casino_history != true){
				return $this->response(200, [], 'Miss data', [], false);
			}
			return [$casino_history];
		}
	}
	public function gameHistory(Request $req){
		$user_auth = Auth::user();
		$account_id = $req->account_id;
		$game_history = app('App\Http\Controllers\API\AgGameController')->gethistory($account_id, $req->fromdate, $req->todate);
		if($game_history != true){
			return $this->response(200, [], 'Miss data', [], false);
		}
		return  $game_history;
		// return $this->response(200, $game_history, '', [], true);
	}
	public function casinoHistory(Request $req){
		$user_auth = Auth::user();
		
		$casino_history = app('App\Http\Controllers\API\SAGameController')->GetUserWinLost($req->account, $req->fromdate, $req->todate);
		if($casino_history != true){
			return $this->response(200, [], 'Miss data', [], false);
		}
		return $casino_history;

	}

    public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	
	public function PlayGame($arr_play){
		$param = [
            'agid' => config('ag.agid'),
			'username' => $arr_play['sub_id'],
			'game_code' =>  $arr_play['game_code'],
			'game_support' =>  $arr_play['game_support'],
			'lang' => config('ag.lang'),
			'game_back_url' => $arr_play['game_back_url'],
		];
		$key = config('ag.key');
		$signature = $this->Signature_Genarate($param, $key);
		$urlAPI = config('ag.url');
		$api = $urlAPI.'/user_play_game';
		$param += $signature;
		$client = new \GuzzleHttp\Client();
		$response = $client->post(
            $api,
            array(
                'form_params' => $param
            )
		);
		$result = json_decode($response->getBody()->getContents());
		return $result;	

	}
	public static function Signature_Genarate($Params,$privateKey = false){
    	if(!empty($Params['signature']))
    	{
        	unset($Params['signature']);
    	}
        ksort($Params);

        if(isset($_GET['debug']) && $_GET['debug'] ==1)
            echo implode("", $Params) . $privateKey;

   	 	$Params['signature'] = sha1(implode("", $Params) . $privateKey);
        return $Params;
	}
	public function checkBalance(Request $req){
		$balancesport = app('App\Http\Controllers\API\BCSportController')->checkBalance($req->account);
		return $balancesport;
	}
	public function loginBC(Request $req){
	
		$account = $req->account;
		$password =  $req->password;
		$subacc = DB::table('subaccount')->where('subAccount_ID', $account)->first();

		if(!$subacc){
			return Redirect::to('https://sportbook.winboss.club/');
		}
		if($subacc->subAccount_SPaccount == 0){
			return "Please deposit to bet sport";
		}
		if(Hash::check($password, $subacc->subAccount_Password) != true){
			return "wrong password";
		}
		$bc = app('App\Http\Controllers\API\BCSportController')->postLoginRequest($account);
		return Redirect::to($bc);
	}
	public function loginCasino(Request $req){
	
		$account = $req->account;
		$password =  $req->password;
		$subacc = DB::table('subaccount')->where('subAccount_ID', $account)->first();
		
		if(!$subacc){
			// return "wrong subacc";
			return Redirect::to('http://casino.winboss.club/')->withErrors('msg', 'Login Failed');
		}
		if($subacc->subAccount_SAaccount == 0){
			return "Please deposit to casino";
		}
		if(Hash::check($password, $subacc->subAccount_Password) != true){
			return "wrong password";
		}
		$bc = app('App\Http\Controllers\API\SAGameController')->postLogin($account, $password);
		$json = json_decode($bc->getContent(), true);
		$url = ($json["data"])["url"];
		// dd(($json["data"])["url"]);exit;
		return Redirect::to($url);
	}
	public function loginGame(Request $req){
	
		$account = $req->account;
		$password =  $req->password;
		$subacc = DB::table('subaccount')->where('subAccount_ID', $account)->first();

		if(!$subacc){
			return "wrong subacc";
			return Redirect::to('http://localhost/loginform/login.php');
		}
		if($subacc->subAccount_AGaccount == 0){
			return "Please deposit to game";
		}
		if(Hash::check($password, $subacc->subAccount_Password) != true){
			return "wrong password";
		}
		$bc = app('App\Http\Controllers\API\BCSportController')->postLoginRequest($account);
		return Redirect::to($bc);
	}
	public function registerBO($arr_bo){
		$arrData = [
			"sub" => $arr_bo["sub"],
			"balance" => 0
		];
		$insert = subAccountBalance::add($arrData);
		if($insert){
			return true;
		}else{
			return false;
		}


	}


	
	
	
	
}