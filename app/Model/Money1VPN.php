<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\User;
use App\Model\Investment;
use App\Model\Eggs;
use App\Model\MoneyAction;
use App\Model\GameBet;
use App\Model\Profile;
use Illuminate\Support\Facades\Auth;
class Money1VPN extends Model
{
  protected $table = 'money_1vnp';
  public $timestamps = false;
  protected $primaryKey = 'Money_1VPN_ID';
}
