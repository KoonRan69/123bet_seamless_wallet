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



class SkyGameController extends Controller{
    public $key;
    public $urlApi;
    public $agid;
    public $lang;
  	public $passwordGame = 'JKNjwjxjqn';
    public $minGold;
  	public $system = 'now';
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['getGameList', 'getUrlGame', 'balanceGame','postLoginRequest']]);
        $ag = config('ag');
        $this->key = $ag['key'];
        $this->urlApi = $ag['url'];
        $this->agid = $ag['agid'];
        $this->lang = $ag['lang'];
        $this->minGold = 100;
    }


  	public function postLoginRequest(Request $req){
      	$validator = Validator::make($req->all(), [
            'email' => 'required',
            'password' => 'required|min:6|max:12'
        ],[
            'email.required' => trans('notification.email_required'),
            'password.min:6' => trans('notification.password_minimum_6_characters'),
            'password.max:12' => trans('notification.password_up_to_12_characters '),
            'password.required' => trans('notification.password_required'),
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
            return $this->response(200, trans('notification.error'), '', [], false);
        }

		$password = $req->password;
      	if (Hash::check($req->password, $user->User_Password)) {
        	if($user->User_SkyGame == 0){
                // đăng kí
              	$arr_register = array(
                  						'username'=>$user->User_ID,
                					);
                $regiter = $this->registerGame($arr_register);

                if($regiter === true){
                    User::where('User_ID', $user->User_ID)->Update(['User_SkyGame'=>1]);
                }
            }

          	$arr_login = array(
                                  	'username'=>$user->User_ID
                                );
            if($req->system == 'fish'){
				$arr_login['code'] = 'fishing_goldentoad_multiplayer';
            }else{
              	$arr_login['code'] = 'slot_seacaptain';
            }
          	$login = $this->getUrlGame($arr_login);

          	if($login){
            	return $this->response(200, ['url' => $login], trans('notification.Login_to_game_success'), [], true);
            }
          	return $this->response(200, ['url' => $login], trans('notification.Login_to_game_false'), [], false);
        }
    }

  	public function getUrlGame($arr_login){

        $key = $this->key;
        $url = $this->urlApi.'user_play_game';
        $params = [];
        $params['agid']	  		 	= $this->agid;
        $params['username'] 	 	= $this->system.$arr_login['username'];
        $params['game_code'] 	 	= $arr_login['code'];;
        $params['game_support'] 	= null;
        $params['lang'] 	 	    = $this->lang;
        $params['game_back_url'] 	= '';
        $params			 		 = $this->Signature_Genarate($params,$key);
        $paramsUrl = '';

        if ($params)
          foreach ($params as $key => $value)
          $paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

          $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response, true);


        if($json['status'] == 'OK'){

          return $json['url'];
        }else{
          return false;
        }


    }

    public function registerGame($arr_register){
		/* đăng kí bên game */
		$param = [
            'agid' => $this->agid,
            'username' => $this->system.$arr_register['username'],
            'password' => $this->passwordGame,
            'lang' => $this->lang
		];

		$key = $this->key;
        $signature = $this->Signature_Genarate($param, $key);
        $urlAPI = config('ag.url');
        $api = $urlAPI.'/user_register';
		$param += $signature;
		$client = new \GuzzleHttp\Client();
        $response = $client->post(
            $api,
            array(
                'form_params' => $param
            )
		);
		$result = json_decode($response->getBody()->getContents());

        if($result->status == 'OK'){
            return  true;

        }else{
            return false;
        }
	}



  	public function depositGame($arr_deposit){

      	$user = User::find($arr_deposit['username']);
      	if($user->User_SkyGame == 0){
          	$arr_register['username'] = $user->User_ID;
          	$register = $this->registerGame($arr_register);
          	if($register){
              	$user->User_SkyGame = 1;
              	$user->save();
            }
        }

        $param = [
            'agid' => $this->agid,
			'amount' => (float)$arr_deposit['amount'],
			'username' => $this->system.$arr_deposit['username'],
            'orderid' => time()
		];
		$key = $this->key;
        $signature = $this->Signature_Genarate($param, $key);
        $urlAPI =  $this->urlApi;
        $api = $urlAPI.'/user_transfer';
		$param += $signature;
		$client = new \GuzzleHttp\Client();
		$response = $client->post(
            $api,
            array(
                'form_params' => $param
            )
		);
        $result = json_decode($response->getBody()->getContents());

        if($result->status != 'OK'){
          	return false;

	    }else{
            return $param['orderid'];
        }
	}

  	public function withdrawSkyGame($arr_withdraw){

        $param = [
            'agid' => $this->agid,
			'amount' => -(float)$arr_withdraw['amount'],
			'username' => $this->system.$arr_withdraw['username'],
            'orderid' => time()
        ];
        $key = $this->key;
        $signature = $this->Signature_Genarate($param, $key);
        $urlAPI =  $this->urlApi;
        $api = $urlAPI.'/user_transfer';
		$param += $signature;
		$client = new \GuzzleHttp\Client();
		$response = $client->post(
            $api,
            array(
                'form_params' => $param
            )
        );
        $result = json_decode($response->getBody()->getContents());

        if($result->status != 'OK'){
            return false;
	    }else{
            return $param['orderid'];
        }
    }


  	public function checkBalance($username){
      	//$username = $this->system.939272;
        $key = $this->key;
        $url = $this->urlApi.'user_detail';
        $params = [];
        $params['agid']	  		 	= $this->agid;
        $params['username'] 	 	= $this->system.$username;


        $params			 		 = $this->Signature_Genarate($params,$key);
        $paramsUrl = '';

        if ($params)
          foreach ($params as $key => $value)
          $paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

          $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response);


      	if(!isset($json->status) || $json->status != 'OK'){
            return false;
	    }else{
            return $json->balance;
        }
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

  	public static function Signature_Verify($Params , $privateKey = false){
    	if(!is_array($Params) || !$privateKey)
    	{
    		return false;
    	}

    	$CSignature = '';
    	if(!empty($Params['signature']))
    	{
        	$CSignature = $Params['signature'];
        	unset($Params['signature']);
    	}

        ksort($Params);
    	$Signature = sha1(implode("", $Params) . $privateKey);
        return ($Signature === $CSignature) ? true : false;
    }

  	public function getUserTransferHistory(){
        $user = Auth::user();
        $key = $this->key;
		$url = $this->urlApi.'user_transfer_history';
        $params = [];
		$params['agid']	  		 	= $this->agid;
        $params['username'] 	 	= $user->User_ID;
        $params['start_date'] 	 	= date('Y-m-d H:i:s', strtotime('-1 day'));
        $params['end_date'] 	 	= date('Y-m-d H:i:s');
        $params			 		 = $this->Signature_Genarate($params,$key);

        $paramsUrl = '';

		if ($params)
			foreach ($params as $key => $value)
				$paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

        $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response, true);
        if($json['status'] == 'OK'){

            return $this->response(200, $json, '', [], true);
        }else{
            return $this->response(200, $json, '', [], false);
        }
    }

    public function gethistory($account_id){
        // $user = Auth::user();
        $key = $this->key;
		$url = $this->urlApi.'user_game_history';
        $params = [];
		$params['agid']	  		 	= $this->agid;
        $params['username'] 	 	= $account_id;
        $params['start_date'] 	 	= date('Y-m-d H:i:s');
        $params['end_date'] 	 	= date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $params			 		 = $this->Signature_Genarate($params,$key);

        $paramsUrl = '';

		if ($params)
			foreach ($params as $key => $value)
				$paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

        $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response, true);
        if($json['status'] == 'OK'){

            return $this->response(200, $json, '', [], true);
        }else{
            return $this->response(200, $json, '', [], false);
        }
    }

    public function postDeposit(Request $req){
        $user = Auth::user();
        $validator = Validator::make($req->all(), [
            'amount' => 'required|numeric',
            'CodeSpam' => 'required',
        ]);


		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				return $this->response(200, [], $value, $validator->errors(), false);
			}
        }

        $checkSpam = DB::table('string_token')->where('User', $user->User_ID)->where('Token', $req->CodeSpam)->first();

        if ($checkSpam == null) {
            //khoong toonf taij
            return $this->response(200, [], 'Misconduct!', [], false);
        }else{
            DB::table('string_token')->where('User', $user->User_ID)->delete();
        }
        $minGold = $this->minGold;
        $balance = User::getBalance($user->User_ID, 9);
        if((int)$req->amount < $minGold){
            return $this->response(200, [], 'Min deposit '.$minGold.' gold', [], false);
        }
        if((int)$req->amount > $balance){
            return $this->response(200, ['balance'=>$balance], 'Your balance is not enough', [], false);
        }

        $key = $this->key;
		$url = $this->urlApi.'user_transfer';
        $params = [];
		$params['agid']	  		 	= $this->agid;
        $params['username'] 	 	= $user->User_ID;
        $params['amount'] 	 	= (float)$req->amount/100;
        $params['orderid'] 	 	= time();


        $params			 		 = $this->Signature_Genarate($params,$key);
        $paramsUrl = '';

		if ($params)
			foreach ($params as $key => $value)
				$paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

        $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response, true);
        if($json['status'] == 'OK'){
            // trừ tiền người nạp
            $arrayInsert = array(
                'Money_User' => $user->User_ID,
                'Money_USDT' => -(float)($req->amount*1),
                'Money_USDTFee' => 0,
                'Money_Time' => time(),
                'Money_Comment' => 'Deposit '.(float)($req->amount*1).' gold to balance game',
                'Money_MoneyAction' => 31,
                'Money_MoneyStatus' => 1,
                'Money_Address' => null,
                'Money_Currency' => 9,
                'Money_CurrentAmount' => (float)($req->amount*1),
                'Money_Rate' => 1,
                'Money_Confirm' => 0,
                'Money_Confirm_Time' => null,
                'Money_FromAPI' => 1,
            );
            $insert = Money::insert($arrayInsert);
            $balance = User::getBalance($user->User_ID, 9);
            $json['GOLD'] = $balance;
            return $this->response(200, $json, 'Deposit Mini Game Success', [], true);
        }else{
            return $this->response(200, $json, 'Deposit Mini Game Fail', [], false);
        }
    }
    public function withdrawGame($arr_withdraw, $type_withdraw){
        $minGold = $this->minGold;
		$balance = subAccount::getBalanceSub($arr_withdraw['sub_id'], $type_withdraw);
        // $balance = DB::table('users')->where('user_ID', $arr_withdraw['user_ID'])->value('user_Balance');
        if((int)$arr_withdraw['amount'] < $minGold){
            return $this->response(200, [], 'Min deposit '.$minGold.' gold', [], false);
        }
        if((int)$arr_withdraw['amount'] > $balance){
            return $this->response(200, ['balance'=>$balance], 'Your balance is not enough', [], false);
        }

        $param = [
            'agid' => $this->agid,
			'amount' => -(float)$arr_withdraw['amount'],
			'username' => $arr_withdraw['sub_id'],
            'orderid' => time()
        ];
        $key = $this->key;
        $signature = $this->Signature_Genarate($param, $key);
        $urlAPI =  $this->urlApi;
        $api = $urlAPI.'/user_transfer';
		$param += $signature;
		$client = new \GuzzleHttp\Client();
		$response = $client->post(
            $api,
            array(
                'form_params' => $param
            )
        );
        $result = json_decode($response->getBody()->getContents());
        if($result->status != 'OK'){
            return false;
	    }else{
            return true;
        }
    }
    public function postWithdraw(Request $req){
        $user = Auth::user();
        $validator = Validator::make($req->all(), [
            'amount' => 'required|numeric',
            'CodeSpam' => 'required',
		]);

		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				return $this->response(200, [], $value, $validator->errors(), false);
			}
        }
        if((int)$req->amount < $this->minGold){
            return $this->response(200, [], 'Min withdraw '.$this->minGold.' gold', [], false);
        }

        $checkSpam = DB::table('string_token')->where('User', $user->User_ID)->where('Token', $req->CodeSpam)->first();

        if ($checkSpam == null) {
            //khoong toonf taij
            return $this->response(200, [], 'Misconduct!', [], false);
        }else{
            DB::table('string_token')->where('User', $user->User_ID)->delete();
        }

        $key = $this->key;
		$url = $this->urlApi.'user_transfer';
        $params = [];
		$params['agid']	  		 	= $this->agid;
        $params['username'] 	 	= $user->User_ID;
        $params['amount'] 	 	= -(float)abs($req->amount/100);
        $params['orderid'] 	 	= time();


        $params			 		 = $this->Signature_Genarate($params,$key);
        $paramsUrl = '';

		if ($params)
			foreach ($params as $key => $value)
				$paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

        $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response, true);
        if($json['status'] == 'OK'){
            // trừ tiền người nạp
            $arrayInsert = array(
                'Money_User' => $user->User_ID,
                'Money_USDT' => (float)abs($req->amount),
                'Money_USDTFee' => 0,
                'Money_Time' => time(),
                'Money_Comment' => 'Withdraw '.(float)abs($req->amount*1).' gold from balance game',
                'Money_MoneyAction' => 32,
                'Money_MoneyStatus' => 1,
                'Money_Address' => null,
                'Money_Currency' => 9,
                'Money_CurrentAmount' => (float)abs($req->amount*1),
                'Money_Rate' => 1,
                'Money_Confirm' => 0,
                'Money_Confirm_Time' => null,
                'Money_FromAPI' => 1,
            );
            $insert = Money::insert($arrayInsert);
            $balance = User::getBalance($user->User_ID, 9);
            $json['GOLD'] = $balance;
            return $this->response(200, $json, 'Withdraw Mini Game Success', [], true);
        }else{
            return $this->response(200, $json, 'Withdraw Mini Game Fail', [], false);
        }
    }

    public function getGameList(){
        $gameList = DB::table('agGameList')->where('game_show', 1)->get();
        $list[0]['list'] = $gameList->where('game_typeWeb', 'slot');
        $list[0]['title'] = 'Slot';
        $list[1]['list'] = $gameList->where('game_typeWeb', 'online_casino');
        $list[1]['title'] = 'Online Casino';
        $list[2]['list'] = $gameList->where('game_typeWeb', 'fishing');
        $list[2]['title'] = 'Fishing';
        return $this->response(200, $list, '', [], true);
        // $key = $this->key;
		// $url = $this->urlApi.'user_game_list';
        // $params = [];
		// $params['agid']	  	= $this->agid;
        // $params			 	 = $this->Signature_Genarate($params,$key);
        // $paramsUrl = '';

		// if ($params)
		// 	foreach ($params as $key => $value)
		// 		$paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

        // $url = $url . '?' . $paramsUrl;
        // $client = new \GuzzleHttp\Client();
        // $res = $client->request('GET', $url);
        // $response = $res->getBody();
        // $json = json_decode($response, true);
        // if($json['status'] == 'OK'){
        //     $insert = array();
        //     foreach($json['list'] as $v){
        //         $insert[] = array(
        //             'game_code' => $v['game_code'],
        //             'game_name' => $v['game_name'],
        //             'game_type' => $v['game_type'],
        //             'game_h5' => $v['game_h5'],
        //             'game_jackpot' => $v['game_jackpot'],
        //             'game_image_url' => $v['game_image_url'],
        //             'game_display_name' => $v['game_display_name']['english'],
        //             'game_play' => 0,
        //         );
        //     }
        //     DB::table('agGameList')->insert($insert);
        //     return $this->response(200, $insert, '', [], true);

        // }else{
        //     return $this->response(200, $json, '', [], false);
        // }
    }

    public function getBalance(){
        $json = $this->balanceGame();
        if($json['status'] == 'OK'){
            $json['rate'] = 1;
            return $this->response(200, $json, '', [], true);
        }else{
            return $this1->response(200, $json, '', [], false);
        }

    }






    // reset password
    public static function reset_Password($user_id, $password){
        $user = Auth::user();
        $key = $this->key;
		$url = $this->urlApi.'user_reset_password';
        $params = [];
		$params['agid']	  		 	= $this->agid;
		$params['username'] 	 	= $user_id;
        $params['password'] 	 	= $password;
        $params			 		 = $this->Signature_Genarate($params,$key);
        $paramsUrl = '';

		if ($params)
			foreach ($params as $key => $value)
				$paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

        $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response, true);
        if($json['status'] == 'OK'){

            return $this->response(200, $json, '', [], true);
        }else{
            return $this->response(200, $json, '', [], false);
        }

    }
    //get info data game history
    public function game_History($user_id, $start_date, $end_date, $page = 1){
        $user = Auth::user();
        $key = $this->key;
		$url = $this->urlApi.'user_game_history';
        $params = [];
		$params['agid']	  		 	= $this->agid;
		$params['username'] 	 	= $user_id;
        $params['start_date'] 	 	= $start_date;
        $params['end_date'] 	 	= $end_date;
        $params['page'] 	 	= $page;
        $params			 		 = $this->Signature_Genarate($params,$key);
        $paramsUrl = '';

		if ($params)
			foreach ($params as $key => $value)
				$paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

        $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response, true);
        if($json['status'] == 'OK'){
            return $json;
            // return $this->response(200, $json, '', [], true);
        }else{
            return false;
        }
    }
    public static function transfer_History($user_id, $start_date, $end_date, $page = 1){
        $user = Auth::user();
        $key = $this->key;
		$url = $this->urlApi.'user_transfer_history';
        $params = [];
		$params['agid']	  		 	= $this->agid;
		$params['username'] 	 	= $user_id;
        $params['start_date'] 	 	= $start_date;
        $params['end_date'] 	 	= $end_date;
        $params['page'] 	 	= $page;
        $params			 		 = $this->Signature_Genarate($params,$key);
        $paramsUrl = '';

		if ($params)
			foreach ($params as $key => $value)
				$paramsUrl .= (!empty($paramsUrl) ? "&" : "") . rawurlencode($key) . "=" . rawurlencode($value);

        $url = $url . '?' . $paramsUrl;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $response = $res->getBody();
        $json = json_decode($response, true);
        if($json['status'] == 'OK'){

            return $this->response(200, $json, '', [], true);
        }else{
            return $this->response(200, $json, '', [], false);
        }
    }




}
