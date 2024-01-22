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



class BCSportController extends Controller{
    public $companyKey;
    public $urlApi;
    public $apiPassword;
    public $currency;
    public $error;
    public $system = "NOW";

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['getGameList', 'getUrlGame', 'balanceGame', 'postLoginRequest']]);
        $bc = config('bc');
        $this->companyKey = $bc['companyKey'];
        $this->apiPassword = $bc['apiPassword'];
        $this->urlApi = $bc['urlApi'];
        $this->currency = $bc['currency'];
        $this->error = $bc['error'];

    }
    public function postRegister($username){
		$apiUrl = $this->urlApi.'SportApi/Register';
		$client = new \GuzzleHttp\Client([
		    'headers' => [ 'Content-Type' => 'application/json' ]
		]);
        $response = $client->post(
	        $apiUrl,
			['body' => json_encode(
		        [

					"MemberAccount" => $this->system.$username,
					"NickName" => "",
					"Currency" => $this->currency,
					"CompanyKey" => $this->companyKey,
					"APIPassword" => $this->apiPassword

		        ]
		    )]
		);
		$result = json_decode($response->getBody()->getContents());
		if($result->Data){
			return true;
		}else{
			return $this->error[$result->ErrorCode];
		}
	}

	public function postLoginRequest(Request $req){

		$validator = Validator::make($req->all(), [
            'email' => 'required',
            'password' => 'required|min:6|max:12'
        ],[
			'email.required' => trans('notification.email_required') ,
			'password.required' => trans('notification.password_required'),
			'password.min:6' => trans('notification.password_minimum_6_characters'),
			'password.max:12' => trans('notification.password_up_to_12_characters '),
		]);


		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				return $this->response(200, [], $value, $validator->errors(), false);
			}
        }
        $user = User::where('User_Email', $req->email)->first();


		if(!$user){
            return $this->response(200, trans('notification.user_not_exist!'), '', [], false);
		}

		if($user->User_Block == 1){
            return $this->response(200, trans('notification.error'), '', [], false);
        }

		$password = $req->password;
        if (Hash::check($req->password, $user->User_Password)) {
	        // kiểm tra có đk chưa

			if($user->User_SportBook == 0){
				// đăng kí
				$regiter = $this->postRegister($user->User_ID);


				if($regiter === true){
					User::where('User_ID', $user->User_ID)->Update(['User_SportBook'=>1]);
				}
			}

	        $apiUrl = $this->urlApi.'SportApi/Login';
			$client = new \GuzzleHttp\Client([
			    'headers' => [ 'Content-Type' => 'application/json' ]
			]);
	        $response = $client->post(
		        $apiUrl,
				['body' => json_encode(
			        [

						"MemberAccount" => $this->system.$user->User_ID,
						"LoginIP" => $this->getClientIps(),
						"CompanyKey" => $this->companyKey,
						"APIPassword" => $this->apiPassword

			        ]
			    )]
			);

			$result = json_decode($response->getBody()->getContents());
//            dd($result);
			if($result->Data){
              	return $this->response(200, ['url' => 'https:'.$result->Data], trans('notification.Login_to_game_success'), [], true);

			}else{
				return false;
			}
		}else{
			return $this->response(200, trans('notification.wrong_password'), '', [], false);

		}


	}

	public function depositSP($arr_depositSP){
		if($arr_depositSP['amount']<1){
			return false;
		}


		$apiUrl = $this->urlApi.'SportApi/Transfer';

		$OrderId = uniqid().uniqid();
		$amount = (float)abs($arr_depositSP['amount']);
		$client = new \GuzzleHttp\Client([
		    'headers' => [ 'Accept' => 'application/json' ]
		]);

        $response = $client->post(
	        $apiUrl,
			['json' => (
		        [
					"CompanyKey" => $this->companyKey,
					"APIPassword" => $this->apiPassword,
					"MemberAccount" => $this->system.$arr_depositSP['username'],
					"Amount" => $amount,
					"TransferType" => 0,
					"Key" => substr(md5(strtolower($this->apiPassword.$this->system.$arr_depositSP['username'].sprintf("%.4f", $amount))), -6),
					"SerialNumber" =>$OrderId,
		        ]
		    )]
		);
		$result = json_decode($response->getBody()->getContents());



		if($result->ErrorCode == '000000'){

			return $OrderId;
		}else{
			return false;
		}

	}

	public function withdrawSP($arr_withdrawSP){


		$apiUrl = $this->urlApi.'SportApi/Transfer';

		$OrderId = uniqid().uniqid();
		$amount = (float)abs($arr_withdrawSP['amount']);

		$username = $this->system.$arr_withdrawSP['username'];

		if($amount<1){
			return false;
		}


		$client = new \GuzzleHttp\Client([
		    'headers' => [ 'Accept' => 'application/json' ]
		]);

        $response = $client->post(
	        $apiUrl,
			['json' => (
		        [
					"CompanyKey" => $this->companyKey,
					"APIPassword" => $this->apiPassword,
					"MemberAccount" => $username,
					"Amount" => $amount,
					"TransferType" => 1,
					"Key" => substr(md5(strtolower($this->apiPassword.$username.sprintf("%.4f", $amount))), -6),
					"SerialNumber" =>$OrderId,
		        ]
		    )]
		);
		$result = json_decode($response->getBody()->getContents());


		if($result->ErrorCode == '000000'){

			return $OrderId          ;
		}else{
			return false;
		}
	}

	public function checkBalance($username){
		if(!$username)
			return false;
		//return 0;
		$username = $this->system.$username;
		$apiUrl = $this->urlApi.'SportApi/GetBalance';


		$client = new \GuzzleHttp\Client([
		    'headers' => [ 'Accept' => 'application/json' ]
		]);

        $response = $client->post(
	        $apiUrl,
			['json' => (
		        [
					"CompanyKey" => $this->companyKey,
					"APIPassword" => $this->apiPassword,
					"MemberAccount" => $username,
		        ]
		    )]
		);
		$result = json_decode($response->getBody()->getContents());
		if($result->ErrorCode == '000000'){
			return (float)$result->Data->Balance;
		}else{
			return false;
		}
	}

    public function getClientIps(){
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
	        if (array_key_exists($key, $_SERVER) === true){
	            foreach (explode(',', $_SERVER[$key]) as $ip){
	                $ip = trim($ip); // just to be safe
	                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
	                    return $ip;
	                }
	            }
	        }
	    }
	}


}
