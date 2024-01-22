<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\SportBook;
use App\Model\User;
class SportBookController extends Controller
{
  public $config;

  public function __construct()
  {
    $this->middleware('auth:api');
    $this->config = config('sportBook.sportbook');
  }
  public function DoCreateMember(Request $req)
  {   
    $user = User::find($req->user()->User_ID);
    if ($user->User_AZ8SportBook == 1){
      return $this->response(200, [], trans('notification.You_are_already_registered'), [], false);
    }

    $_CreateMemberResult = json_decode($this->CreateMember($user->User_ID),false);
    dd($_CreateMemberResult);
    var_dump($_CreateMemberResult); 
    if ($_CreateMemberResult->error_code > 0)
    {
    }
    else
    {
    }    
  }
  public function CreateMember(Request $req)
  {
    $getData = file_get_contents('https://g5a1ob.hn555.com/Newindex?lang=vn');
    dd($getData);
    $user = User::find($req->user()->User_ID);
    if ($user->User_AZ8SportBook == 1){
      return $this->response(200, [], trans('notification.You_are_already_registered'), [], false);
    }
    $_url = $this->config['url'];
    $_APIVendorID = $this->config['APIVendorID'];
    $Funtion = "CreateMember";
    $Vendor_Member_ID = "az8_".$user->User_ID."_test";
    $FirstName = "Test";
    $LastName = $user->User_ID;
    $OddsType = "a";
    $Currency = "20";
    $OperatorId = "az8"; // the default value usually is site name
    $MaxTransfer = "100";
    $MinTransfer = "1";
    $post_data = [
      'vendor_id' => $_APIVendorID,
      'Vendor_Member_ID' => $Vendor_Member_ID,
      'OperatorId' => $OperatorId,
      'FirstName' => $FirstName,
      'LastName' => $LastName,
      'UserName' => $Vendor_Member_ID,
      'OddsType' => $OddsType,
      'Currency' => $Currency,
      'MaxTransfer' => $MaxTransfer,
      'MinTransfer' => $MinTransfer,
    ];
    $httpService = new SportBook();
    return $httpService->sendPost($_url.'/'.$Funtion , $post_data);
  }
}
