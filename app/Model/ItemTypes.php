<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ItemTypes extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'item_types';
    public $timestamps = false;
}
