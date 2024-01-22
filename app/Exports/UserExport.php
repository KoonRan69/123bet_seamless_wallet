<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Model\User;
use DB;
class UserExport implements FromCollection, WithHeadings
{
  /**
    * @return \Illuminate\Support\Collection
    */
  public $temp = '';
  use Exportable;
  public function __construct($query = null){
    $this->temp = $query->toArray();

  }
  public function collection()
  {
    $level = array(1 => 'Admin', 0 => 'Member', 2 => 'Finance', 3 => 'Support', 4 => 'Customer', 5 => 'BOT', 10=>'Admin View');
    $user = $this->temp;
    $result = [];
    $currency = DB::table('currency')->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    // dd($currency);
    foreach ($user as $row) {
      if ($row['google2fa_User']) {
        $row['google2fa_User'] = "Enable";
      } else {
        $row['google2fa_User'] = "Disable";
      }
      $kyc = 'Unverify';
      if(isset($row['Profile_Status'])){
        if($row['Profile_Status'] == 1){
          $kyc = 'Verified';
        }else{
          $kyc = 'Waiting';
        }
      }
      $listAddress = [];
      foreach($row['address_deposit'] as $address){
        $listAddress[$currency[$address['Address_Currency']]] = $address['Address_Address'];
      }
      $status = 'No';
      if($row['User_Block'] == 1)  $status = 'Block';
      $result[] = array(
        $row['User_ID'],
        $row['User_Email'],
        $row['User_Name'],
        $row['User_Name_Sbobet'],
        $row['User_RegisteredDatetime'],
        $row['User_Parent'],
        $row['User_Tree'],
        $level[$row['User_Level']],
        ($row['User_EmailActive'] ? 'Active' : 'None'),
        $status,
        $row['google2fa_User'],
        $kyc,
        isset($listAddress['USDT']) ? $listAddress['USDT'] : '',
        isset($listAddress['EBP']) ? $listAddress['EBP'] : '',
      );
    }
    return (collect($result));

  }
  public function headings(): array
  {

    return [
      'ID', 'Email', 'Username', 'SboID', 'Registred DateTime', 'ID Parent', 'Binary Tree', 'Level', 'Status','Block', 'Auth', 'KYC', 'Deposit USDT', 'Deposit EBP',
    ];

  }
}
