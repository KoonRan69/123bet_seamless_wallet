<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;
use App\Model\FishTypes;


use DB;
class Fishs extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'fish';
    protected $fillable = ['_id',
                            'Pool', // ID Ho
                            'Born', // ngày sinh nhật
                            'Type', // Thoi gian trung hu
                            'GrowTime', // Thoi gian cần lớn
                            'ActiveTime', // Ngay mua trung
                            'FeedTime', // lần cuối cùng cho ăn theo giây
                            'Status', // User
                            'Owner', // ID trung
                            'ID', // ID trung
                            'From', // ca duoc no tu trung nao
                            'CurrentFood'
                        ];
    
    public $timestamps = false;

    public function fishTypes(){
        return $this->belongsTo('App\Model\FishTypes', 'Type', 'Type');
    }

    public function getCurrentBlood($id, $user){
        $fish = Fishs::where([
            'Owner' => $user->User_ID,
            'ID' => $id,
        ])->first();

        $SECCOND_DATE = 86400;

        if($fish){
            //blood loss per second
            $bloodLossPerSecond = (double) ($fish->fishTypes->MaxFood / 5) / $SECCOND_DATE;

            $bloodTimeLoss = (double) (time() - $fish->FeedTime)*$bloodLossPerSecond;
            $remainBlood = $fish->CurrentFood - $bloodTimeLoss;

            return $remainBlood;

        } else {
            return null;
        }
    }

    public static function getFish($user_id){
        $list_fish = Fishs::where('Owner', $user_id)->get();
        return $list_fish;
    }

    public static function countFish($user_id){
        $count_fish = Fishs::where('Owner', $user_id)->count();
        return $count_fish;
    }

    public static function checkLevelFish($fish){
        $level = FishTypes::where('Type', $fish->Type)->first()->Level;
        return $level;
    }

    public static function getIDFish(){
        $ID = "F".rand(1000000000,9999999999);
        $checkID = Fishs::where('ID', $ID)->first();
        if($checkID){
            self::getIDFish();
        } 
        return $ID;
    }
}
