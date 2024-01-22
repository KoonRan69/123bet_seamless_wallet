<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// Define the namespaces to use
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Jobs\SendTelegramJobs;

class Binance extends Model
{

  public static function exchange($asset, $fiat, $tradeType)
  {
    $arr_rate_vnd = [];
    $arrAsset = ["BUY","SELL"];
    foreach($arrAsset as $item){
      $url = "https://p2p.binance.com/bapi/c2c/v2/friendly/c2c/adv/search";
      // Parameters for the API request
      $params = [
        'asset' => 'USDT',
        'tradeType' => $item,
        'publisherType' => null,
        'page' => 1,
        'rows' => 1,
        'payTypes' => [],
        'fiat' => 'VND',
      ];

      try{
        $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
        $body=json_encode($params);
        $response = $client->request('POST',$url,['body'=>$body]);
        $response =json_decode($response->getBody(), true); //dd($response);

        if($response['code'] * 1 == 0 && $response['success'] * 1 == true){
          $arr_rate_vnd[$item] = $rateBuy = $response['data'][0]['adv']['price'];
        }
      } catch (\Exception $e) {
        $arr_rate_vnd[$item] = 0;
        $message = "Get rate $item p2p vnđ error".$e->getMessage()."\n"
          . "<b>Project: </b>"
          . "beta-v2.123betnow.net\n"
          . "<b>Time: </b>"
          . date('d-m-Y H:i:s',time());
        dispatch(new SendTelegramJobs($message, -398297366));
      }
    }
    dd($arr_rate_vnd);
  }
}
