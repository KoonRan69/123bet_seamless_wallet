<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;


use DB;
use App\Model\Eggs;
use App\Model\Fishs;
class Pools extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'pools';
    protected $fillable = ['_id',
                            'Skin',
                            'Type',
                            'time', 
                            'ID', 
                            'Owner',
                            'tid'
                        ];
    
    public $timestamps = false;

    public function poolType(){
        return $this->belongsTo('App\Model\PoolTypes', 'Type', 'Type');
    }
   
    public static function AddPool($user, $quantity, $PoolTypes){
        return true;
        $insertArray = array();
        $tidArray = array();
        for($i=0;$i<$quantity; $i++){
            $PoolID = self::RandonPoolID();
            $tid = uniqid();
            $tidArray[] = $tid;
            $insertArray[] = array(
                'Skin' => 0,
                'Type'=>$PoolTypes,
                'time'=>time(),
                'ID'=>$PoolID,
                'Owner'=>$user,
                'tid'=>$tid
            );
        }
        
        
        if(Pools::insert($insertArray)){
            return $tidArray;
        }
        return false;
    }

    public static function checkPool($_id){
        $checkPool = Eggs::where('Pool', $_id)->where('status', 1)->count('_id');
        return $checkPool;
    }

    public static function RandonPoolID(){
        $id = 'P'.rand(100000000 , 999999999);
        $egg = Pools::where('ID', $id)->first();
        if(!$egg){
            return $id;
        }else{
            self::RandonPoolID();
        }
    }

    public static function infoPool($_id){
        $pool = Pools::where('ID', $_id)->first();
        if(!$pool)
            return false;


        $egg = Eggs::where('Pool', $_id)->where('Status', 1)->get();

        // $returnData = array();
        
        // if($egg){
            $returnData['Item'] = ['eggs'=>[], 'fishs'=>[]];
        // }

        foreach($egg as $v){
            // if($v->Status == 1){
                $returnData['Item']['eggs'][] = array(
                    'egg'=>true,
                    'ActiveTime'=>$v->ActiveTime,
                    'HatchesTime'=>$v->HatchesTime,
                    'RemainTime'=>$v->ActiveTime+$v->HatchesTime - time(),
                    'RemainTimePercent'=> $v->HatchesTime == 0? 0: ($v->ActiveTime+$v->HatchesTime - time()) / $v->HatchesTime * 100, 
                    'BuyDate'=>$v->BuyDate,
                    'Type'=>$v->Type,
                    'ID'=>$v->ID,
                    'Owner'=>$v->Owner,
                    'WaitingActive' => $v->WaitingActive ?? 0,
                    'eggsTypes' => $v->eggsTypes,
                ); 
            // } 
            // else if($v->Status == 2){
            //     $returnData['Item']['fishs'][] = array(
            //         'fish'=>true,
            //         'ActiveTime'=>$v->ActiveTime,
            //         'HatchesTime'=>$v->HatchesTime,
            //         'RemainTime'=>$v->ActiveTime+$v->HatchesTime - time(),
            //         'BuyDate'=>$v->BuyDate,
            //         'Type'=>$v->Type,
            //         'ID'=>$v->ID,
            //         'Owner'=>$v->Owner,
            //     );
            // }
        }

        $fish = Fishs::with('fishTypes')->where('Pool', $_id)->where('Status', 1)->get();

        foreach($fish as $v){
            // if($v->Status == 1){
                $returnData['Item']['fishs'][] = array(
                    'fish'=>true,
                    'Born'=>$v->Born,
                    'PercentFeed'=>$v->CurrentFood / $v->fishTypes->MaxFood * 100,
                    'RemainTime'=>($v->ActiveTime ? $v->ActiveTime+$v->GrowTime - time() : 0),
                    'ActiveTime'=>$v->ActiveTime,
                    'GrowTime'=>$v->GrowTime,
                    'Type'=>$v->Type,
                    'ID'=>$v->ID,
                    'Owner'=>$v->Owner,
                    'FishType' => $v->fishTypes,
                );
            // } 
            // else if($v->Status == 2){
            //     $returnData['Item']['fishs'][] = array(
            //         'fish'=>true,
            //         'ActiveTime'=>$v->ActiveTime,
            //         'HatchesTime'=>$v->HatchesTime,
            //         'RemainTime'=>$v->ActiveTime+$v->HatchesTime - time(),
            //         'BuyDate'=>$v->BuyDate,
            //         'Type'=>$v->Type,
            //         'ID'=>$v->ID,
            //         'Owner'=>$v->Owner,
            //     );
            // }
        }

        return $returnData;
    }
}
