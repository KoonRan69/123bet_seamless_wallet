<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;


use DB;
class FoodTypes extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'food_types';
    protected $fillable = ['_id','Image','Price','Active','Type'];
    
    public $timestamps = false;
   
    
}
