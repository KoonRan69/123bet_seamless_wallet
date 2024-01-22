<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;


use DB;
class Eggs extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'eggs';
    protected $fillable = ['_id',
                            'Pool', // ID Ho
                            'HatchesTime', // Tong thoi gian trung no (tinh theo giay)
                            'CanHatches', // Tong thoi gian trung no (tinh theo giay)
                            'ExpiryTime', // Thoi gian trung hu
                            'ActiveTime', // Thoi gian kich trung
                            'BuyDate', // Ngay mua trung
                            'Type', // Loai trung
                            'Owner', // User
                            'ID', // ID trung
                            'Status' // ID trung
                        ];
    
    public $timestamps = false;

    public function eggsTypes(){
        return $this->belongsTo('App\Model\EggTypes', 'Type', 'Type');
    }

    public static function getItem($id, $user){
        $item = Eggs::where('ID', $id)->where('Owner', $user)->first();
        if($item){
            return $item;
        }
        return false;
    }
   
    public static function RandonEggID(){
        $id = 'E'.rand(100000000 , 999999999);
        $egg = Eggs::where('ID', $id)->first();
        if(!$egg){
            return $id;
        }else{
            self::RandonEggID();
        }
    }
}
