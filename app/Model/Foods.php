<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;


use DB;
class Foods extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'foods';
    protected $fillable = ['_id',
                            'Amount', // ID Ho
                            'Type', // Tong thoi gian trung no (tinh theo giay)
                            'CreateAt', // Thoi gian trung hu
                        ];
    
    public $timestamps = false;
   
    public static function AddFood($user, $quantity, $EggTypes){
        return true;
    }

    public function foodsType(){
        return $this->belongsto('App\Model\FoodTypes', 'Type', 'Type');
    }
}
