<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Http\Controllers\System\CoinbaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

use Image;
use PragmaRX\Google2FA\Google2FA;

use DB;
use Mail;
use GuzzleHttp\Client;
use App\Model\Wallet;
use App\Model\GoogleAuth;
use App\Model\User;
use App\Model\userBalance;
use App\Model\Money;
use App\Model\subAccount;
use App\Http\Controllers\CustomClass\DESEncrypt;
use Redirect;


class SAGameController extends Controller{

	// public $md5key = "GgaIMaiNNtg";
	// public $key = "g9G16nTs";
	// public $secretkey = "80C834C6EBE84DB3BB9698C131FF3ABE";
	// public $currency = "USD";
	// public $url = "http://api.sa-apisvr.com/api/api.aspx";
	// public $client = "https://web.sa-globalxns.com/app.aspx";
	// public $language = "en_US";
	// public $system = "WIN";

	public $md5key = "GgaIMaiNNtg";
	public $key = "g9G16nTs";
	public $secretkey = "80C834C6EBE84DB3BB9698C131FF3ABE";
	public $currency = "USD";
	public $url = "http://api.sa-apisvr.com/api/api.aspx";
	public $client = "https://web.sa-globalxns.com/app.aspx";
	public $language = "en_US";
	public $system = "NOW";

	
	public function register($id){
      	$user = User::find($id);
		$method = 'RegUserInfo';
		$date = date('YmdHis', time());
		$username = $this->system.$id;

		$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Username=".$username."&CurrencyType=".$this->currency;

		$s = md5($QS.$this->md5key.$date.$this->secretkey);

		$crypt = new DESEncrypt($this->key);

		$q = $crypt->encrypt($QS);
		$data = array('q' => $q, 's' => $s);


		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($this->url, false, $context);
		$xml=simplexml_load_string($result) or die("Error: Cannot create object");
		if($xml->ErrorMsgId == 0){
			return true;
		}else{
			return $xml->ErrorMsg;
		}

	}
	
	public function checkBalance($username){
        $user = User::find($username);
        if($user->User_Casino == 0){
            // đăng kí
            $regiter = $this->register($user->User_ID);

            if($regiter === true){
              	User::where('User_ID', $user->User_ID)->Update(['User_Casino'=>1]);
            }
        }
      	$method = 'GetUserStatusDV';
		$date = date('YmdHis', time());
		$username = $this->system.$username;
		$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Username=".$username;
      	
		$s = md5($QS.$this->md5key.$date.$this->secretkey);

		$crypt = new DESEncrypt($this->key);

		$q = $crypt->encrypt($QS);
		$data = array('q' => $q, 's' => $s);


		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($this->url, false, $context);

		$xml=simplexml_load_string($result) or die("Error: Cannot create object");
      	$result = (array)$xml;
     	if($result['ErrorMsgId'] == 129){
          	return 0;
      	}
      	
		return $result['Balance'];
	}
	
	
	
	function postVerifyUsername(){
		$method = 'VerifyUsername';
		$date = date('YmdHis', time());

		$username = "quoctestuser";
		$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Username=".$username;

		$s = md5($QS.$this->md5key.$date.$this->secretkey);

		$crypt = new DESEncrypt($this->key);

		$q = $crypt->encrypt($QS);
		$data = array('q' => $q, 's' => $s);


		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($this->url, false, $context);

		$xml=simplexml_load_string($result) or die("Error: Cannot create object");
	}
	
	function postLoginRequest(Request $req){
            return $this->response(200, [], trans('notification.The_system_is_maintenance'), [], false);
      

		$validator = Validator::make($req->all(), [
            'email' => 'required',
            'password' => 'required|min:6|max:12'
        ], [
			'email.required' => trans('notification.User_email_required!'),
			'password.required' => trans('notification.Password_required'),
		]);
        

		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				return $this->response(200, [], $value, $validator->errors(), false);
			}
        }
        $user = User::where('User_Email', $req->email)->first();

		if(!$user){
            return $this->response(200, trans('notification.User_not_exist'), '', [], false);
		}
		
		
		if($user->User_Block == 1){
            return $this->response(200, trans('notification.Error'), '', [], false);
        }
		
		
		$password = $req->password;
        if (Hash::check($req->password, $user->User_Password)) {
	        // kiểm tra có đk chưa

			if($user->User_Casino == 0){
				// đăng kí
				$regiter = $this->register($user->User_ID);
				
				if($regiter === true){
					User::where('User_ID', $user->User_ID)->Update(['User_Casino'=>1]);
				}
			}
	        
	        $UserName = $this->system.$user->User_ID;
			$method = 'LoginRequest';
			$date = date('YmdHis', time());
			
			// $username = "quoctestuser";
			$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Username=".$UserName."&CurrencyType=".$this->currency;

			$s = md5($QS.$this->md5key.$date.$this->secretkey);

			$crypt = new DESEncrypt($this->key);

			$q = $crypt->encrypt($QS);
			$data = array('q' => $q, 's' => $s);


			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data)
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($this->url, false, $context);

			
			
			$xml=simplexml_load_string($result) or die("Error: Cannot create object");
			//return Redirect::to($this->client.'?username='.$req->account.'&token='.$xml->Token.'&lobby=A3492');
			return $this->response(200, ['url' => $this->client.'?username='.$UserName.'&token='.$xml->Token.'&lobby=A3492'], 'Login to game success!!', [], true);
		}else{
			return $this->response(200, trans('notification.wrong_password'), '', [], false);

		}
		
		
		
	}
/*
	function postLogin($account, $password){
		
        $user = User::where('email', $account)->first();

		if(!$user){
            return $this->response(200, 'User not exist!', '', [], false);
		}
		// $password = $req->password;
        if (Hash::check($password, $user->subAccount_Password)) {
			$method = 'LoginRequest';
			$date = date('YmdHis', time());

			// $username = "quoctestuser";
			$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Username=".$account."&CurrencyType=".$this->currency;

			$s = md5($QS.$this->md5key.$date.$this->secretkey);

			$crypt = new DESEncrypt($this->key);

			$q = $crypt->encrypt($QS);
			$data = array('q' => $q, 's' => $s);


			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data)
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($this->url, false, $context);

			
			
			$xml=simplexml_load_string($result) or die("Error: Cannot create object");
			return $this->response(200, ['url' => $this->client.'?username='.$account.'&token='.$xml->Token.'&lobby=A3492'], 'Login to game success!!', [], true);
		}else{
			return $this->response(200, 'Wrong password!', '', [], false);

		}
		
		
		
	}
*/
	function depositSA($arr_depositSA){
      	//if($arr_depositSA['username'] != 374462){
        return false;
        //}
		$method = 'CreditBalanceDV';
		$date = date('YmdHis', time());
		
		
		$CreditAmount = (float)$arr_depositSA['amount'];

		$username = $this->system.$arr_depositSA['username'];
		$secretkey = $this->secretkey;
		$currency = $this->currency;
		$md5key = $this->md5key;
		$key = $this->key;
		$url = $this->url;
		$client = $this->client;
		$OrderId = 'IN'.date('YmdHis').$username;

		$QS = "method=$method&Key=".$secretkey."&Time=".$date."&Username=".$username."&CurrencyType=".$currency."&CreditAmount=".$CreditAmount."&OrderId=".$OrderId;


		$s = md5($QS.$md5key.$date.$secretkey);

		$crypt = new DESEncrypt($key);

		$q = $crypt->encrypt($QS);
		$data = array('q' => $q, 's' => $s);


		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		
		
		$xml=simplexml_load_string($result) or die("Error: Cannot create object");
		$val = (array)$xml->ErrorMsgId;

		if($val[0] != 0 || $val[0] != "0"){
			return false;
		}else{
			return $OrderId;
		}
	}
	
	
	public function withdrawSA($arr_withdrawSA){
		$method = 'DebitBalanceDV';
		$date = date('YmdHis', time());
		
		
		$DebitAmount = (float)$arr_withdrawSA['amount'];

		$username = $this->system.$arr_withdrawSA['username'];

		$secretkey = $this->secretkey;
		$currency = $this->currency;
		$md5key = $this->md5key;
		$key = $this->key;
		$url = $this->url;
		$client = $this->client;
		$OrderId = 'OUT'.date('YmdHis').$username;

		$QS = "method=$method&Key=".$secretkey."&Time=".$date."&Username=".$username."&CurrencyType=".$currency."&DebitAmount=".$DebitAmount."&OrderId=".$OrderId;


		$s = md5($QS.$md5key.$date.$secretkey);

		$crypt = new DESEncrypt($key);

		$q = $crypt->encrypt($QS);
		$data = array('q' => $q, 's' => $s);


		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		
		
		$xml=simplexml_load_string($result) or die("Error: Cannot create object");
		$val = (array)$xml->ErrorMsgId;


		// dd($val);
		if($val[0] != 0 || $val[0] != "0"){
			return false;
		}else{
			return $OrderId;
		}
	}
	public function querywinloss($d, $username){
		$method = 'GetAllBetDetailsDV';
		$Key = $this->key;
		$Time = date('YmdHis', time());
	
		$Username = $username;
		$Date = date('Y-m-d',strtotime('-'.$d.' day'));
		
		// $username = "quoctestuser";
		$OrderId = 'IN'.date('YmdHis').$username;
		$Type = 1;

		$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$username."&Date=".$Date;

		$s = md5($QS.$this->md5key.$Time.$this->secretkey);

		$crypt = new DESEncrypt($this->key);

		$q = $crypt->encrypt($QS);
		$data = array('q' => $q, 's' => $s);

		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($this->url, false, $context);

		$xml=simplexml_load_string($result) or die("Error: Cannot create object");
		// return $xml;
		return (array)$xml;

	}
	
	public function getHistory($username, $fromDate, $toDate){
        $user = User::find($username);
        if($user->User_Casino == 0){
            // đăng kí
            $regiter = $this->register($user->User_ID);

            if($regiter === true){
              	User::where('User_ID', $user->User_ID)->Update(['User_Casino'=>1]);
            }
        }
		$username = $this->system.$username;
		$d = ($toDate - $fromDate)/60/60/24;

		$arr = [];
		for ($i=1; $i <= $d; $i++) { 
			$history = $this->querywinloss($i, $username);
          	if($history['ErrorMsgId']==0 && count($history['BetDetailList'])){
           		array_push($arr, $history['BetDetailList']);    
            }
			
		}

		return $arr;

	}
	
	
}

