<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;


use DB;
class Markets extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'markets';
    protected $fillable = ['_id',
                            'Item', // ID item
                            'Sold',
                            'Cancel',
                            'Type', // Loại item
                            'UserSell', // người bán
                            'PriceEUSD', // Giá
                            'PriceGold', // Giá
                            'Status', // Trạng thái giao dịch
                            'Password',
                            'DateTime'
                            ];
    
    public $timestamps = true;

    public static function CreateMarket($data){
        $insertArray = array(
            'Item' => $data['Item'],
            'Sold' => $data['Sold'],
            'Cancel' => array(),
            'Type'=> $data['Type'],
            'UserSell'=> $data['UserSell'],
            'PriceEUSD'=> (float)$data['PriceEUSD'],
            'PriceGold'=> (float)$data['PriceGold'],
            'UserBalanceSell'=> $data['UserBalanceSell'],
            'Status'=> $data['Status'],
            'Password'=> $data['Password'],
            'DateTime'=>time(),
        );

        $insertid = Markets::insertGetId($insertArray);
        return $insertid;
    }

}
