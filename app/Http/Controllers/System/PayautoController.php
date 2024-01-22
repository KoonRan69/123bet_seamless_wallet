<?php

namespace App\Http\Controllers\System;

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
class PayautoController extends Controller
{
    public function getAutomaticPayment(Request $req){
      	$today = strtotime(date('Y-m-d 00:00:00'));
      	//dd($today);
      	$listMoney = Money::join('users', 'Money_User', 'User_ID')
          					->where('Money_Time', '>=', $today)
          					//->where('User_ID', 633591)
          					->where('Money_MoneyAction', 2)
          					->where('Money_Confirm', 0)
          					->whereIn('Money_MoneyStatus',[0,1])
          					->where('User_Level', 0)
          					->get();
      	//dd($listMoney);
        if(count($listMoney) <= 0){
          	dd('There are currently no withdrawal orders to spend!');
        }  
      
        include(app_path() . '/functions/xxtea.php');
      	foreach($listMoney as $money){
          	$user = User::where('User_ID', $money->User_ID)->first();
          	if($money->Money_User == "633591" ){
              //dd($amount);
            }
          	//dd($user);
          	if(!$user){
              	continue;
            }
          	$amount = abs($money->Money_USDT+$money->Money_USDTFee);
          	$amountUSD = $amount*$money->Money_Rate;
          	$amountFee = abs($money->Money_USDTFee);
          	//dd($amount,$amountFee);
          	
          	if($amountUSD > 2000){
              	continue;
            }
          	//dd($amountUSD);
          	$balance = User::getBalance($user->User_ID, $money->Money_Currency);
          
          	$id = $money->Money_ID;	
          	$rate = $money->Money_Rate;
          	$address = $money->Money_Address;
          	$coinBalance = $money->Money_Currency;
          	$coin = $money->Money_CurrencyTo;
          	$time = 0;
            $total = Money::where('Money_User', $user->User_ID)
                            ->whereIn('Money_MoneyAction', [76,2,75,7,1,67,3,4,66,65,16,64,68,77,14])
                            ->whereIn('Money_MoneyStatus',[0,1])
                            //->whereNotIn('Money_ID', [$id])
                            ->where('Money_Time', '>', $time)
                            ->where('Money_Currency', $coinBalance)
                            ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')
                            ->first();
            //$balance = (int)$balance;
            $difference = $total->total-$balance;
            if($req->long == 1421 ){
              //dd($difference,$balance,$total);
            }

            if(abs($difference) <= 1){
                $totalDeposit = Money::where('Money_User', $user->User_ID)
                              ->where('Money_MoneyAction', 1)
                              ->whereIn('Money_MoneyStatus',[0,1])
                              ->where('Money_Time', '>', $time)
                              ->where('Money_Currency', $coinBalance)
                              ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')
                              ->first();
              	$totalTransferIn = Money::where('Money_User', $user->User_ID)
                              ->where('Money_MoneyAction', 7)
                              ->whereIn('Money_MoneyStatus',[0,1])
                              ->where('Money_Time', '>', $time)
                  			  ->where('Money_Comment', 'LIKE', 'Receive'.'%')
                              ->where('Money_Currency', $coinBalance)
                              ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')
                              ->first();
                $totalWithdraw = Money::where('Money_User', $user->User_ID)
                              ->where('Money_MoneyAction', 2)
                              ->whereIn('Money_MoneyStatus',[0,1])
                              ->where('Money_Time', '>', $time)
                              ->where('Money_Currency', $coinBalance)
                              ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')
                              ->first();
              	
              	$totalTransferOut = Money::where('Money_User', $user->User_ID)
                              ->where('Money_MoneyAction', 7)
                              ->whereIn('Money_MoneyStatus',[0,1])
                              ->where('Money_Time', '>', $time)
                  			  ->where('Money_Comment', 'LIKE', 'Transfer'.'%')
                              ->where('Money_Currency', $coinBalance)
                              ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')
                              ->first();
              	$totalD = $totalDeposit->total+$totalTransferIn->total;
              	$totalW = $totalWithdraw->total+$totalTransferOut->total;
              	//dd($totalTransferOut,$totalTransferIn);
                if(abs($totalD) >= abs($totalW)){
                    if($coin == 8){
                        $network = 'bsc';
                      	$nameCoin = 'EBP';
                        $addressToken = '0x3e007b3cc775c4bd1600693aad7fac0685353272';
                    }elseif($coin == 3){
                        $network = 'tron';
                      	$nameCoin = 'EUSD';
                        $addressToken = 'TNUN6pFXEH3p3jhDZaTro6gsLCZ5fT3rqs';
                    }
                  	else{
                        $network = 'tron';
                      	$nameCoin = 'USDT';
                        $addressToken = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t';
                    }

                    $projectID = 95317; 
                    $key = '4d0237f816';
                    $urlAPI = 'https://auto-spread.123betnow.net/api/v1/spread/'.$network;
                    $wallet = [
                      'address' => $address,
                      'amount' => $amount - $amountFee,
                      'transfer_id' => $id,
                    ];
                    $data = [
                        'project_id' => $projectID,
                        'wallets' => [$wallet],
                        'token_address' => $addressToken,
                    ];

                    $dataEncrypt = base64_encode(xxtea_encrypt(json_encode($data), $key));
                    //dd($dataEncrypt);        
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('POST', $urlAPI, [
                      'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                      'body' => json_encode(['data'=>$dataEncrypt]),
                    ]);

                    //dd($response);
                    $content = $response->getBody()->getContents();
                    $dataDe = json_decode($content);
                    //$responseData = xxtea_decrypt($dataDe->data, $key);
                    $responseData = json_decode(xxtea_decrypt(base64_decode($dataDe->data), $key), true);
                    //$array = explode(",",$content); 
                    //$responseData = xxtea_decrypt($dataDe, $key);
                  	//dd($responseData);
                    if($responseData && $responseData['status'] == true){
                        //dd($responseData['data'][0]);
                      	//dd($responseData);
                      	if($responseData['data'][0] && $responseData['data'][0]['status'] == false){
                          $message = "Automatic withdraw error: $user->User_Email\n"
                              . "<b>User ID: </b> "
                              . "$user->User_ID\n"
                              . "<b>Email: </b> "
                              . "$user->User_Email\n"
                              . "<b>Status: </b> "
                              . "Error (Data automatic false)\n"
                              . "<b>Automatic Time: </b>\n"
                              . date('d-m-Y H:i:s', time());

                          dispatch(new SendTelegramJobs($message, -615343238));
                          continue;	 
                        }
                      	if(!$responseData['data'][0]['hash']){
                          $message = "Automatic withdraw error: $user->User_Email\n"
                              . "<b>User ID: </b> "
                              . "$user->User_ID\n"
                              . "<b>Email: </b> "
                              . "$user->User_Email\n"
                              . "<b>Status: </b> "
                              . "Error (Hash empty)\n"
                              . "<b>Automatic Time: </b>\n"
                              . date('d-m-Y H:i:s', time());

                          dispatch(new SendTelegramJobs($message, -615343238));
                          continue;
                        }
                        $hash = $responseData['data'][0]['hash'];
                      	
                        $timeUpdate = $responseData['data'][0]['timestamp'];
                        $updateMoney = Money::where('Money_ID', $id)
                                        ->where('Money_User', $user->User_ID)
                                        ->where('Money_Currency', $coinBalance)
                                        ->update([
                                          'Money_TXID' => $hash,
                                          'Money_Confirm' => 1,
                                          'Money_Confirm_Time' => date('Y-m-d H:i:s'),
                                          'Money_PayAuto' => 1,
                                        ]);
                        if($updateMoney){
                          	LogUser::addLogUser($user->User_ID, 'Automatic payment withdraw', 'Automatic payment withdraw success'.' '.(float)$amount.' to wallet: '.$money->Money_Address, $req->ip());
                          	$message = "Automatic withdraw success: $user->User_Email \n"
                                . "<b>User ID: </b> "
                                . "$user->User_ID\n"
                                . "<b>Email: </b> "
                                . "$user->User_Email\n"
                                . "<b>Status: </b> "
                                . "Complete\n"
                                . "<b>Amount: </b> "
                                . $amount . " $nameCoin\n"
                                . "<b>Amount Fee: </b> "
                                . ($amountFee) . " $nameCoin \n"
                                . "<b>Rate: </b> "
                                . $money->Money_Rate*1 ."\n"
                                . "<b>Automatic Time: </b>\n"
                                . date('d-m-Y H:i:s', time());

                            dispatch(new SendTelegramJobs($message, -615343238));
                            echo 'Automatic payment withdraw success ID: '.$id.' UserID: '.$user->User_ID.' Amount: '.$amount.' Fee: '.$amountFee.'<br>';
                          	continue;
                        }
                        else{
                            $updatePending = Money::where('Money_User', $user->User_ID)
                                                    ->where('Money_ID', $id)
                                                    ->where('Money_Currency', $coinBalance)
                                                    ->update(['Money_PayAuto'=> 0]);
                          	LogUser::addLogUser($user->User_ID, 'Automatic payment withdraw', 'Automatic payment withdraw error'.' '.(float)$amount.' to wallet: '.$money->Money_Address, $req->ip());
                          	$message = "Automatic withdraw error: $user->User_Email\n"
                                . "<b>User ID: </b> "
                                . "$user->User_ID\n"
                                . "<b>Email: </b> "
                                . "$user->User_Email\n"
                                . "<b>Status: </b> "
                                . "Error (Save command error)\n"
                                . "<b>Automatic Time: </b>\n"
                                . date('d-m-Y H:i:s', time());

                            dispatch(new SendTelegramJobs($message, -615343238));
                            echo 'Error ID: '.$id.' UserID: '.$user->User_ID.' Amount: '.$amount.' Fee: '.$amountFee.'<br>';
                            continue;
                        }
                    }
                    else{
                        $updatePending = Money::where('Money_User', $user->User_ID)
                                                ->where('Money_ID', $id)
                                                ->where('Money_Currency', $coinBalance)
                                                ->update(['Money_PayAuto'=> 0]);
                      	LogUser::addLogUser($user->User_ID, 'Automatic payment withdraw', 'Automatic payment withdraw error(data reponse null)'.' '.(float)$amount.' to wallet: '.$money->Money_Address, $req->ip());
                      	$message = "Automatic withdraw error: $user->User_Email\n"
                            . "<b>User ID: </b> "
                            . "$user->User_ID\n"
                            . "<b>Email: </b> "
                            . "$user->User_Email\n"
                            . "<b>Status: </b> "
                            . "Error (Data reponse null)\n"
                            . "<b>Automatic Time: </b>\n"
                            . date('d-m-Y H:i:s', time());

                        dispatch(new SendTelegramJobs($message, -615343238));
                        echo 'Error ID: '.$id.' UserID: '.$user->User_ID.' Amount: '.$amount.' Fee: '.$amountFee.'(data reponse null)<br>';
                        continue;
                    }
                    //dd($responseData,$data,$dataDe,$content);

                }
                else{
                    $updatePending = Money::where('Money_User', $user->User_ID)
                                    ->where('Money_ID', $id)
                                    ->where('Money_Currency', $coinBalance)
                                    ->update(['Money_PayAuto'=> 0]);
                  	LogUser::addLogUser($user->User_ID, 'Automatic payment withdraw', 'Automatic payment withdraw error(total deposit < total withdraw)'.' '.(float)$amount.' to wallet: '.$money->Money_Address, $req->ip());
                  	$message = "Automatic withdraw error: $user->User_Email\n"
                        . "<b>User ID: </b> "
                        . "$user->User_ID\n"
                        . "<b>Email: </b> "
                        . "$user->User_Email\n"
                        . "<b>Status: </b> "
                        . "Error (Total deposit less total withdraw)\n"
                        . "<b>Automatic Time: </b>\n"
                        . date('d-m-Y H:i:s', time());
					//dd($message);
                    dispatch(new SendTelegramJobs($message, -615343238));
                  	//dd(123);
                    echo 'Error ID: '.$id.' UserID: '.$user->User_ID.' Amount: '.$amount.' Fee: '.$amountFee.'(total deposit < total withdraw)<br>';
                    continue;
                }

            }else{
                $updatePending = Money::where('Money_User', $user->User_ID)
                                      ->where('Money_ID', $id)
                                      ->where('Money_Currency', $coinBalance)
                                      ->update(['Money_PayAuto'=> 0]);
              	LogUser::addLogUser($user->User_ID, 'Automatic payment withdraw', 'Automatic payment withdraw error(balance < 0)'.' '.(float)$amount.' to wallet: '.$money->Money_Address, $req->ip());
              	$message = "Automatic withdraw error: $user->User_Email\n"
                    . "<b>User ID: </b> "
                    . "$user->User_ID\n"
                    . "<b>Email: </b> "
                    . "$user->User_Email\n"
                    . "<b>Status: </b> "
                    . "Error (Balance less 0)\n"
                    . "<b>Automatic Time: </b>\n"
                    . date('d-m-Y H:i:s', time());

                dispatch(new SendTelegramJobs($message, -615343238));
                echo 'Error ID: '.$id.' UserID: '.$user->User_ID.' Amount: '.$amount.' Fee: '.$amountFee.'(balance < 0)<br>';
                continue;
            }
          	//$pay = $this->autoMaticSpending($user, $money->Money_ID, $amount, $amountFee, $money->Money_Rate, $money->Money_Address, $money->Money_Currency, $money->Money_CurrencyTo, $balance); 
          	
          	
          	//dd('Automatic payment withdraw success!');
        }	
      	dd('Automatic payment withdraw success!');
    }	
  	
  	public function autoMaticSpending($user, $id, $amount, $amountFee, $rate, $address, $coinBalance, $coin, $balance){
      	if($amount > 500){
          return false;
        }
    	$time = 0;
      	$total = Money::where('Money_User', $user->User_ID)
          				->whereIn('Money_MoneyAction', [76,2,75,7,1,67,3,4,66,65,16,64,68,77,14])
          				->whereIn('Money_MoneyStatus',[0,1])
          				//->whereNotIn('Money_ID', [$id])
          				->where('Money_Time', '>', $time)
          				->where('Money_Currency', $coinBalance)
          				->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')
          				->first();
      	//$balance = (int)$balance;
      	$difference = $total->total-$balance;
      	if($user->User_ID == 633591 ){
          //dd($difference,$user);
        }
      	
      	if(abs($difference) <= 1){
          	$totalDeposit = Money::where('Money_User', $user->User_ID)
                          ->where('Money_MoneyAction', 1)
                          ->whereIn('Money_MoneyStatus',[0,1])
                          ->where('Money_Time', '>', $time)
                          ->where('Money_Currency', $coinBalance)
                          ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')
                          ->first();
          	$totalWithdraw = Money::where('Money_User', $user->User_ID)
                          ->where('Money_MoneyAction', 2)
                          ->whereIn('Money_MoneyStatus',[0,1])
                          ->where('Money_Time', '>', $time)
                          ->where('Money_Currency', $coinBalance)
                          ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')
                          ->first();
          	if($totalDeposit->total >= $totalWithdraw->total){
              	if($coin == 8){
                  	$network = 'bsc';
              		$addressToken = '0x4eaed49f9ab8a3a2219a700fffd7d8da6d589b0a';
                }else{
                  	$network = 'tron';
              		$addressToken = 'TG6QMYEGJ96Ed2Ry2AAkgt1ojeiyJAbpP9';
                }
              	
              	$projectID = 95317; 
              	$key = '4d0237f816';
              	$urlAPI = 'https://auto-spread.123betnow.net/api/v1/spread/'.$network;
              	$wallet = [
                  'address' => $address,
                  'amount' => $amount - $amountFee,
                  'transfer_id' => $id,
                ];
              	$data = [
                  	'project_id' => $projectID,
                  	'wallets' => [$wallet],
                  	'token_address' => $addressToken,
                ];
              
              	include(app_path() . '/functions/xxtea.php');
                $dataEncrypt = base64_encode(xxtea_encrypt(json_encode($data), $key));
                //dd($dataEncrypt);        
                $client = new \GuzzleHttp\Client();
                $response = $client->request('POST', $urlAPI, [
                  'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                  'body' => json_encode(['data'=>$dataEncrypt]),
                ]);

                //dd($response);
                $content = $response->getBody()->getContents();
                $dataDe = json_decode($content);
              	//$responseData = xxtea_decrypt($dataDe->data, $key);
				$responseData = json_decode(xxtea_decrypt(base64_decode($dataDe->data), $key), true);
                //$array = explode(",",$content); 
                //$responseData = xxtea_decrypt($dataDe, $key);
              	if($responseData && $responseData['status'] == true){
                  	//dd($responseData['data'][0]);
                    $hash = $responseData['data'][0]['hash'];
                    $timeUpdate = $responseData['data'][0]['timestamp'];
                  	$updateMoney = Money::where('Money_ID', $id)
                      				->where('Money_User', $user->User_ID)
            						->where('Money_Currency', $coinBalance)
                      				->update([
                                      'Money_TXID' => $hash,
                                      'Money_Confirm' => 1,
                                      'Money_Confirm_Time' => date('Y-m-d H:i:s'),
                                      'Money_PayAuto' => 1,
                                    ]);
                  	if($updateMoney){
                      	echo 'Automatic payment withdraw success ID: '.$id.' UserID: '.$user->User_ID.' Amount: '.$amount.' Fee: '.$amountFee;
                    }
                  	else{
                      	$updatePending = Money::where('Money_User', $user->User_ID)
                                                ->where('Money_ID', $id)
                                                ->where('Money_Currency', $coinBalance)
                                                ->update(['Money_PayAuto'=> 0]);
                      	echo 'Error ID: '.$id.' UserID: '.$user->User_ID.' Amount: '.$amount.' Fee: '.$amountFee;
          				return false;
                    }
                }
              	else{
                    $updatePending = Money::where('Money_User', $user->User_ID)
                                            ->where('Money_ID', $id)
                                            ->where('Money_Currency', $coinBalance)
                                            ->update(['Money_PayAuto'=> 0]);
                    echo 'Error ID: '.$id.' UserID: '.$user->User_ID.' Amount: '.$amount.' Fee: '.$amountFee;
                    return false;
                }
              	//dd($responseData,$data,$dataDe,$content);

            }
          	else{
              	$updatePending = Money::where('Money_User', $user->User_ID)
            					->where('Money_ID', $id)
            					->where('Money_Currency', $coinBalance)
            					->update(['Money_PayAuto'=> 0]);
          		return false;
            }
          	
        }else{
            $updatePending = Money::where('Money_User', $user->User_ID)
                                  ->where('Money_ID', $id)
                                  ->where('Money_Currency', $coinBalance)
                                  ->update(['Money_PayAuto'=> 0]);
            return false;
        }
      	//dd($id, $total,$balance,$difference);
      	
    }
}
