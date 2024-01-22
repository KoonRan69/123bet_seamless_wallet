<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;


use DB;
class EggTypes extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'egg_types';
    protected $fillable = ['_id','Price','GameBet_SubAccountType','Percent','HatchesTime','Fishes','Active','Type','ActiveTime'];
    
    public $timestamps = false;
   
    
}
