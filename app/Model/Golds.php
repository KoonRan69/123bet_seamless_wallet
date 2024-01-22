<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Golds extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'golds';
    protected $fillable = ['_id',
                            'Price', // ID Ho
                            'Amount', // Tong thoi gian trung no (tinh theo giay)
                            'Active', // Thoi gian trung hu
                            'ID'
                        ];
    
    public $timestamps = false;
}
