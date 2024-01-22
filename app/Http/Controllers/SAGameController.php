<?php
namespace App\Http\Controllers;

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
use App\Model\HistorySA;
use App\Http\Controllers\CustomClass\DESEncrypt;



class SAGameController extends Controller{

	public $md5key = "GgaIMaiNNtg";
	public $key = "g9G16nTs";
	public $secretkey = "80C834C6EBE84DB3BB9698C131FF3ABE";
	public $currency = "USD";
	public $url = "http://api.sa-apisvr.com/api/api.aspx";
	public $client = "https://web.sa-globalxns.com/app.aspx";
	public $language = "en_US";
	public $system = "HK";
	
	public function register(Request $req){
      	if($req->ip() !='35.241.109.90' && $req->ip() !='34.92.103.47'){
          return $this->response(200, [], 'ip block', [], false); 
        }
		$method = 'RegUserInfo';
		$date = date('YmdHis', time());
		$username = $req->user_name;
		if(!$username){
          	return $this->response(200, [],trans('notification.miss_user_name') , [], false); 
        }
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
          	return $this->response(200, [], trans('notification.Register_game_success'), [], true);
			return true;
		}else{
          	return $this->response(200, [], '$xml->ErrorMsg', [], true);
			return $xml->ErrorMsg;
		}

	}
	
	public function checkBalance(Request $req){

		$method = 'GetUserStatusDV';
		$date = date('YmdHis', time());
		$username = $req->user_name;
		if(!$username){
          	return $this->response(200, [], trans('notification.miss_user_name'), [], false); 
        }

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
		// dd($xml);
		return $xml->Balance;

	}
	
	function postLoginRequest(Request $req){
		
		$username = $req->user_name;
		if(!$username){
          	return $this->response(200, [], trans('notification.miss_user_name'), [], false); 
        }
        $method = 'LoginRequest';
        $date = date('YmdHis', time());

        // $username = "quoctestuser";
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
        return $this->response(200, ['url' => $this->client.'?username='.$username.'&token='.$xml->Token.'&lobby=A3492'], 'Login to game success!!', [], true);


		
		
	}
	function depositSA(Request $req){
      	if($req->ip() !='35.241.109.90' && $req->ip() !='34.92.103.47'){
          return $this->response(200, [], 'ip block', [], false); 
        }
		$username = $req->user_name;
		if(!$username){
          	return $this->response(200, [], trans('notification.miss_user_name'), [], false); 
        }
      	if($req->amount <= 0){
          	return $this->response(200, [], trans('notification.amount_invalid'), [], false); 
        }
		$method = 'CreditBalanceDV';
		$date = date('YmdHis', time());
		
		$CreditAmount = (float)$req->amount;

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
			return $this->response(200, [], trans('notification.Deposit_fail_Please_try_again'), [], false); 
		}else{
          	return $this->response(200, $data, trans('notification.deposit_success'), [], true); 
		}
	}
	public function postCreditSA(Request $req){
      	if($req->ip() !='35.241.109.90' && $req->ip() !='34.92.103.47'){
          return $this->response(200, [], 'ip block', [], false); 
        }
		$username = $req->user_name;
		if(!$username){
          	return $this->response(200, [], trans('notification.miss_user_name'), [], false); 
        }
      	if($req->amount <= 0){
          	return $this->response(200, [], trans('notification.amount_invalid'), [], false); 
        }
		$method = 'DebitBalanceDV';
		$date = date('YmdHis', time());
		
		$DebitAmount = (float)$req->amount;

		$username = $username;
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
      	$data = ["orderID" => $OrderId];
		if($val[0] != 0 || $val[0] != "0"){
			return $this->response(200, [], 'Withdraw Fail!', [], false); 
		}else{
          	return $this->response(200, $data, 'Withdraw Success!', [], true); 
		}
	}
	public function GetUserWinLost(Request $req){
		$method = 'GetAllBetDetailsDV';
		$Time = date('YmdHis', time());
		// $Time = strtotime($Time)-86400;
		$Date = date('Y-m-d',strtotime('-1 day'));
		if($req->DateTime){
			$Date = date('Y-m-d',strtotime($req->DateTime));
		}
		// dd();
		$CreditAmount = (float)100;
		$username = $req->username;
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
		// var_dump($xml);exit;
		// return $this->response(200, ['url' => $this->client.'?username='.$username.'&token='.$xml->Token.'&lobby=A3492'], '', [], true);
	}

	public function getAllTransaction(Request $req){
		$method = 'GetAllBetDetailsDV';
		$Time = date('YmdHis');
		$FromTime = date('Y-m-d H:i:s',strtotime('-5 day'));
		$ToTime = date('Y-m-d H:i:s');
		//$ToTime = date('Y-m-d H:i:s', strtotime('+ 1 day'));
		
		// $CreditAmount = (float)100;

		// $username = "quoctestuser";
		$OrderId = 'IN'.date('YmdHis').$req->username;
		$Type = 1;

		$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."";
		$QS .= "&Date=".$FromTime;
		if($req->username){
			$QS .= "&Username=".$req->username;
		}
		// if($req->date){
		// 	$Date = date('Y-m-d H:i:s',strtotime('-1 day', strtotime($req->from)));
		// 	$QS .= "&Date=".$FromTime;
		// }
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
		dd($xml, simplexml_load_string($xml->BetDetailList));
		return (array)$xml;
	}
	
	public function gamehistory(Request $req){
		$username = $req->username;
		if(!$username){
			return $this->response(200, [], strans('user_not_found'), [], false); 
		}
		$method = 'GetAllBetDetailsDV';
		$Time = date('YmdHis', time());
		// $Time = strtotime($Time)-86400;
		$Date = date('Y-m-d');
		// dd();
		if($req->date){
			$Date = date('Y-m-d',strtotime($req->date));
		}
		$CreditAmount = (float)100;
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
		$result = (array)$xml;
		return $this->response(200, $result, '', [], true);
		// return (array)$xml;
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

	public function searchDetailhistory(Request $req){
		// $method = 'GetAllBetDetailsDV';
		// $Time = date('YmdHis', time());
		// $fromDate = strtotime($req->fromDate);
		// $toDate = strtotime($req->toDate);
		// $d = ($toDate - $fromDate)/60/60/24;
		
		// $fromDate = date('Y-m-d H:i:s',strtotime('-5 day'));
		// $toDate = date('Y-m-d H:i:s',strtotime('-1 day'));
		
		// $arr = [];
		// for ($i=1; $i <= $d; $i++) { 
		// 	$arr[$i] = $this->querywinloss($i, $req->username);
		// }

		// return $arr;
		

		$method = 'GetAllBetDetailsDV';
		$Key = $this->key;
		$Time = date('YmdHis', time());
	
		$Username = $req->username;
		$Date = date('Y-m-d',strtotime('-3 day'));
		
		// $username = "quoctestuser";
		$OrderId = 'IN'.date('YmdHis').$Username;
		$Type = 1;

		$QS = "method=$method&Key=".$this->secretkey."&Time=".$Time."&Username=".$Username."&Date=".$Date;

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
		$historySA = HistorySA::insertHistory($arr);
		if($historySA){
			return response(array('status'=>true, 'msg'=>'history success'), 200);
		}
		return response(array('status'=>false, 'msg'=>'history fail'), 200);
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
	
	
}

