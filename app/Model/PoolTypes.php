<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;


use DB;
class PoolTypes extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'pool_types';
    protected $fillable = ['_id','MaxEgg','MaxFish','Price','Active','Type'];
    
    public $timestamps = false;
   
    
}
