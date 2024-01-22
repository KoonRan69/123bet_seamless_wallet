<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;


use DB;
class FishTypes extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'fish_types';
    protected $fillable = ['_id','Type','Name','Level','Image','MaxFood','ActiveCost','ActiveGold','LevelUpTime','EggBreed','NextType','Active','EggTypes','AutoLevelUp'];
    
    public $timestamps = false;
   
    public static function getFishTypes(){
        $fish_types = FishTypes::get();
        return $fish_types;
    }
}
