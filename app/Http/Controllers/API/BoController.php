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
use App\Model\User;
use App\Model\boBalance;
use App\Model\GameBet;

class BoController extends Controller{

	
	public $system = "";
	
  	public function postRegister($username){
      	$update = User::where('User_ID', $username)->update(['User_BinanryOption'=>1]);
      	if($update){
         	return true; 
        }
      	return false;
	}
	
	public function checkBalance($username){
		$username = $this->system.$username;

		$balance = boBalance::getBalance($username);
      	return $balance;
	}
	
	function depositBO($arr_depositBO){
      	$username = $this->system.$arr_depositBO['username'];
		$boActive = boBalance::where('sub', $username)->first();
      	$amount = $arr_depositBO['amount'];
      	if($amount <1){
        	return false;
        }
      	$balanceOld = boBalance::getBalance($username);
      	
      	$orderID = 'IN'.uniqid().time();
        $insert = array(
          'sub'=> $username,
          'balance'=>($balanceOld+$amount),
          'oldBalance'=> $balanceOld,
          'time'=>time(),
          'orderID'=>$orderID
        );
      	$insert = boBalance::insert($insert);
      	if($insert){
        	return $orderID;
        }
      	return false;

	}
	
	
	public function withdrawBO($arr_withdrawBO){
      	
		$username = $this->system.$arr_withdrawBO['username'];
		$boActive = boBalance::where('sub', $username)->first();
      	$amount = $arr_withdrawBO['amount'];
      	$balanceBo = boBalance::getBalance($username);
      	if($amount > $balanceBo){
        	return false;
        }
      	$balanceOld = boBalance::getBalance($username);
      	$orderID = 'OUT'.uniqid().time();
        $insert = array(
          'sub'=> $username,
          'balance'=>($balanceOld-$amount),
          'oldBalance'=> $balanceOld,
          'time'=>time(),
          'orderID'=>$orderID
        );
      	$insert = boBalance::insert($insert);
      	if($insert){
        	return $orderID;
        }
      	return false;
	}
	
  	public function getHistory($user, $from, $to){

    	$history = GameBet::where('GameBet_SubAccountUser', (int)$user)->select('GameBet_Type', 'GameBet_Symbol', 'GameBet_Amount', 'GameBet_AmountWin', 'GameBet_Status', 'GameBet_Log', 'GameBet_datetime')->where('GameBet_datetime', '>', $from)->where('GameBet_datetime', '<=', $to)->get()->toArray();
      	return $history;
    }
	
}

