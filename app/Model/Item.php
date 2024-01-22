<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Item extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'item';
    protected $fillable = [
        '_id',
        'Owner',
        'Type',
        "Pool",
        "Status",
        "ID",
        "PoolTime",
        "UpdateTime",
        "LiveTime",
        "FeedTime",
    ];
    
    public $timestamps = false;

    public function itemTypes(){
        return $this->belongsTo('App\Model\ItemTypes', 'Type', 'Type');
    }

    public static function getIDItem(){
        $ID = "I".rand(1000000000,9999999999);
        $checkID = Item::where('ID', $ID)->first();
        if($checkID){
            self::getIDItem();
        } 
        return $ID;
    }

}
