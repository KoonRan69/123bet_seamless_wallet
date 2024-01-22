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



class TestSaController extends Controller{

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
	public $system = "HK";
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
	
	public function testsearch($id, Request $req){
		if(!isset($req->fromDate)){
			$fromDate = strtotime(date('Y-m-d H:i:s',strtotime('-2 day')));
		}else{
			$fromDate = strtotime($req->fromDate);
		}
		if(!isset($req->toDate)){
			$toDate = strtotime(date('Y-m-d H:i:s',strtotime('-0 day')));
		}else{
			$toDate = strtotime($req->toDate);
		}
		$d = ($toDate - $fromDate)/60/60/24;
		$arr = [];
		for ($i=1; $i <= $d; $i++) { 
			$arr[$i] = $this->querywinloss($i, $id);
		}

		return $arr;

	}
	
	public function checkBalance($username){
		$method = 'GetUserStatusDV';
		$date = date('YmdHis', time());

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

		return $xml->Balance;

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
		dd($result);
		$xml=simplexml_load_string($result) or die("Error: Cannot create object");
	}
	
	function postLoginRequest(Request $req){
		$validator = Validator::make($req->all(), [
            'account' => 'required',
            'password' => 'required|min:6|max:12'
        ]);
        

		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				return $this->response(200, [], $value, $validator->errors(), false);
			}
        }
        $user = DB::table('subaccount')->where('subAccount_ID', $req->account)->first();
		if(!$user){
            return $this->response(200, 'User not exist!', '', [], false);
		}
		$password = $req->password;
        if (Hash::check($req->password, $user->subAccount_Password)) {
			$method = 'LoginRequest';
			$date = date('YmdHis', time());

			// $username = "quoctestuser";
			$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Username=".$req->account."&CurrencyType=".$this->currency;

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
			return $this->response(200, ['url' => $this->client.'?username='.$req->account.'&token='.$xml->Token.'&lobby=A3492'], 'Login to game success!!', [], true);
		}else{
			return $this->response(200, 'Wrong password!', '', [], false);

		}
		
		
		
	}
	function postLogin($account, $password){
		
        $user = DB::table('subaccount')->where('subAccount_ID', $account)->first();
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
	function depositSA($arr_depositSA){

		$method = 'CreditBalanceDV';
		$date = date('YmdHis', time());
		
		
		$CreditAmount = (float)$arr_depositSA['amount'];

		$username = $arr_depositSA['username'];
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
		$data = ['orderID' => $OrderId];
		if($val[0] != 0 || $val[0] != "0"){
			return false;
		}else{
			return $data;
		}
	}
	public function postCreditSA($arr_withdrawSA){
		$method = 'DebitBalanceDV';
		$date = date('YmdHis', time());
		
		
		$DebitAmount = (float)$arr_withdrawSA['amount'];

		$username = $arr_withdrawSA['username'];
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
			return true;
		}
	}
	function postDebit(){

		$method = 'CreditBalanceDV';
		$date = date('YmdHis', time());
		
		
		$CreditAmount = (float)100;

		$username = "quoctestuser";
		$OrderId = 'IN'.date('YmdHis').$username;

		$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Username=".$username."&CurrencyType=".$this->currency."&CreditAmount=".$CreditAmount."&OrderId=".$OrderId;


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
		$val = (array)$xml->ErrorMsgId;
		// dd($val);
		if($val[0] != 0 || $val[0] != "0"){
			return false;
		}
		// var_dump($xml);exit;
		return $this->response(200, ['url' => $this->client.'?username='.$username.'&token='.$xml->Token.'&lobby=A3492'], 'Email is not exist', [], true);
	}
	
	public function GetUserWinLost($username, $fromDate = null, $toDate = null){
		$method = 'GetAllBetDetailsDV';
		$Time = date('YmdHis', time());
		// $Time = strtotime($Time)-86400;
		$Date = date('Y-m-d',strtotime('-3 day'));
		// dd();
		//$ToTime = date('Y-m-d H:i:s', strtotime('+ 1 day'));

		
		$CreditAmount = (float)100;

		// $username = "quoctestuser";
		$OrderId = 'IN'.date('YmdHis').$username;
		$Type = 1;

		$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$username."&Date=".$Date;

		if(($fromDate) || ($toDate)){
			$method = 'GetAllBetDetailsForTimeIntervalDV';
			$Time = date('YmdHis', strtotime('-1 day'));
          	$fromDate = date('Y-m-d H:i:s', strtotime($fromDate));
          	$toDate = date('Y-m-d H:i:s', strtotime($toDate));
			$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$username."&FromTime=".$fromDate."&ToTime=".$toDate;
		}

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
		// var_dump($xml);exit;
		// return $this->response(200, ['url' => $this->client.'?username='.$username.'&token='.$xml->Token.'&lobby=A3492'], '', [], true);
	}
	public function searchDetailhistory(Request $req){
		$method = 'GetAllBetDetailsForTimeIntervalDV';
		$Time = date('YmdHis', time());
		$fromDate = $req->fromDate;
		$toDate = $req->toDate;
		$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$req->username."&FromTime=".$fromDate."&ToTime=".$toDate;
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
		return (array)$xml;

	}
	public function gamehistory(Request $req){
		
		$method = 'GetAllBetDetailsDV';
		$Time = date('YmdHis', time());
		
		$Date = date('Y-m-d',strtotime('-0 day'));
		//$ToTime = date('Y-m-d H:i:s', strtotime('+ 1 day'));
		
		
		$CreditAmount = (float)100;

		// $username = "quoctestuser";
		$OrderId = 'IN'.date('YmdHis').$req->username;
		$Type = 1;

		// $QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$req->username."&Date=".$Date;
		$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$req->username."&Date=".$Date;
		

		if(isset($req)){
			$method = 'GetAllBetDetailsForTimeIntervalDV';
			$Time = date('YmdHis', time());
			$fromDate = $req->fromDate;
			$toDate = $req->toDate;
			$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$req->username."&FromTime=".$fromDate."&ToTime=".$toDate;
		}
		// dd($QS);
		// return $QS;

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
		return (array)$xml;
		// var_dump($xml);exit;
		// return $this->response(200, ['url' => $this->client.'?username='.$username.'&token='.$xml->Token.'&lobby=A3492'], '', [], true);
	}
	public function callmethod(Request $req){
		$method = $req->method;
		$Time = date('YmdHis', time());
		$Date = date('Y-m-d',strtotime('-1 day'));
		//$ToTime = date('Y-m-d H:i:s', strtotime('+ 1 day'));

		
		$CreditAmount = (float)100;

		// $username = "quoctestuser";
		$OrderId = 'IN'.date('YmdHis').$req->username;
		$Type = 1;
		if(isset($req->username)){
			$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$req->username."&Date=".$Date;
		}else{
			$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time;
		}

		// return $QS;

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
		return (array)$xml;
	}
	public function queryBetLimit(){
		$method = 'QueryBetLimit';
		$date = date('YmdHis', time());
		$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Currency=".$this->currency;

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
		$val = (array)$xml->ErrorMsgId;
		// dd($val);
		if($val[0] != 0 || $val[0] != "0"){
			return false;
		}
		return (array)$xml;

	}
	public function setUserMaxWin(Request $req){
		$method = 'SetUserMaxWinning';
		$date = date('YmdHis', time());
		
		
		$CreditAmount = (float)100;

		$username = $req->account;
		$MaxWinning = $req->MaxWinning;
		$OrderId = 'IN'.date('YmdHis').$username;

		$QS = "method=$method&Key=".$this->secretkey."&Time=".$date."&Username=".$username."&MaxWinning=".$MaxWinning;

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
		$val = (array)$xml->ErrorMsgId;
		
		if($val[0] != 0 || $val[0] != "0"){
			return false;
		}
		return (array)$xml;
		

	}
	
	
}

