<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money as CB_Money;
use Coinbase\Wallet\Enum\Param;
use DB;

use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

use Validator;
use App\Model\Profile;
use App\Model\GoogleAuth;
use App\Model\LogUser;
use App\Model\User;
use App\Model\userBalance;
use App\Jobs\SendTelegramJobs;
use App\Model\Money;
use PayusAPI\Http\Client as PayusClient;
use PayusAPI\Resources\Payus;

use GuzzleHttp\Client as G_Client;

use App\Model\Wallet;
class MetamaskController extends Controller{
    public function postLoginMetamask(Request $req){
      	$user = User::where('User_WalletAddress', $req->address)->first();
		if(!$user){
			return response()->json(['status' => false,'message' => trans('notification.Address_is_not_found')]);
		}
      	if($user->User_Block == 1){
			return $this->response(200, [], trans('notification.Please_come_back_later'), [], false);
		}
      	//$user = $req->user();
      	
		$tokenResult = $user->createToken('WINBOSS');
		$token = $tokenResult->token;


		$loginType = config('utils.action.login');
		LogUser::addLogUser($user->User_ID, $loginType['action_type'], $loginType['message'], $req->ip());

		$token->save();

		return $this->response(200, [
			'token' => $tokenResult->accessToken,
			'token_type' => 'Bearer',
			'id' => (int)$user->User_ID
		]);
      
    }  
      
  	public function getInfoMetamask(){
      	$user = User::where('User_ID', Auth::user()->User_ID)->first();
      	if($user){
          $connectAddressMetamask = false;
          if($user->User_WalletAddress){
            $connectAddressMetamask = true;
          }
          $infoMeta = [
            'address' => $user->User_WalletAddress,
            'connectAddressMetamask' => $connectAddressMetamask,
          ];
          return $this->response(200, $infoMeta, '');
        }
      	
    }
  
  	public function postDisConnectMetamask(Request $req){
	    $user = User::where('User_ID', Auth::user()->User_ID)->first();
		$check_custom = $user->User_Level;
        if($check_custom != 1){
          //return $this->response(200, [], 'Can\'t use this function!', [], false);
        }
      
        $validator = Validator::make($req->all(), [
          'address' => 'required',
          'otp' => 'required|numeric',
        ],[
          'address.required' => trans('address_requaired') ,
        ]);

        if ($validator->fails()) {
          foreach ($validator->errors()->all() as $value) {
            return $this->response(200, [], $value, $validator->errors(), false);
          }
        }
      
      	$checkProfile = Profile::where('Profile_User', $user->User_ID)->first();
        if(!$checkProfile || $checkProfile->Profile_Status != 1){
          //return $this->response(200, [], 'Your Profile KYC Is Unverify', [], false);
        }

        $google2fa = app('pragmarx.google2fa');
        $AuthUser = GoogleAuth::select('google2fa_Secret')->where('google2fa_User', $user->User_ID)->first();
        
      	
      	if(!$AuthUser){
          return $this->response(200, [], trans('notification.User_is_not_authenticated!'), [], false);
        }
        $valid = $google2fa->verifyKey($AuthUser->google2fa_Secret, $req->otp);
        if(!$valid){
          return $this->response(200, [], trans('notification.Wrong_code'), [], false);
        }
        if(!$user->User_WalletAddress){
            return $this->response(200, [], trans('notification.Your_account_is_not_connected_to_your_wallet'), [], false);
        }
      	$address = $user->User_WalletAddress;
      	
      	//$checkAddress = User::where('User_WalletAddress', $address)->first();
		//if($checkAddress){
            //return $this->response(200, [], 'This address is connected with another account!', [], false);
		//}
      	//dd($address,$req->otp);
      	$user->User_WalletAddress = null;
		$user->save();
      	LogUser::addLogUser($user->User_ID, 'Connect Wallet', 'Disconnect with wallet: '.$address, $req->ip());
        return $this->response(200, [], trans('notification.Disconnect_Address_Success'), [], true);
    }
  	
	public function postConfirmConnectMetamask(Request $req){
	    $user = User::where('User_ID', Auth::user()->User_ID)->first();
		$check_custom = $user->User_Level;
        if($check_custom != 1){
          //return $this->response(200, [], 'Can\'t use this function!', [], false);
        }
      
        $validator = Validator::make($req->all(), [
          'address' => 'required',
          'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
          foreach ($validator->errors()->all() as $value) {
            return $this->response(200, [], $value, $validator->errors(), false);
          }
        }
      
      	$checkProfile = Profile::where('Profile_User', $user->User_ID)->first();
        if(!$checkProfile || $checkProfile->Profile_Status != 1){
          //return $this->response(200, [], 'Your Profile KYC Is Unverify', [], false);
        }

        $google2fa = app('pragmarx.google2fa');
        $AuthUser = GoogleAuth::select('google2fa_Secret')->where('google2fa_User', $user->User_ID)->first();
        
      	if(!$AuthUser){
          return $this->response(200, [], trans('notification.User_is_not_authenticated!'), [], false);
        }
        $valid = $google2fa->verifyKey($AuthUser->google2fa_Secret, $req->otp);
        if(!$valid){
          return $this->response(200, [], trans('notification.Wrong_code'), [], false);
        }
        
      	$address = $req->address;
      	if($user->User_WalletAddress){
            return $this->response(200, [], 'This account is connected with address '.$user->User_WalletAddress.'!', [], false);
        }
      	$checkAddress = User::where('User_WalletAddress', $address)->first();
		if($checkAddress){
            return $this->response(200, [], trans('notification.This_address_is_connected_with_another_account'), [], false);
		}
      	//dd($address,$req->otp);
      	$user->User_WalletAddress = $address;
		$user->save();
      	LogUser::addLogUser($user->User_ID, 'Connect Wallet', 'Connect with wallet: '.$address, $req->ip());
        return $this->response(200, [], trans('notification.Confirm_Address_Success'), [], true);
    }
  	
}
