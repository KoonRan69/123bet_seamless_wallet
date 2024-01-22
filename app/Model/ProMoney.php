<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProMoney extends Model
{
  protected $table = 'pro_money';
  public $timestamps = false;

  protected $fillable = ['Money_ID', 'Money_Game', 'Money_User', 'Money_USDT', 'Money_USDTFee', 'Money_Time', 'Money_Comment', 'Money_MoneyAction', 'Money_MoneyStatus','Money_Token', 'Money_Address', 'Money_Currency', 'Money_CurrentAmount', 'Money_CurrencyFrom','Money_CurrencyTo' ,'Money_Rate', 'Money_Confirm', 'Money_Confirm_Time'];

}