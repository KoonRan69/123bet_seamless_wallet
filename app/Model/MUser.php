<?php

namespace App\Model;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class MUser extends Model
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'users';

    //Eggs, food, pool, fish,
    public function egg()
    {
        return $this->hasMany('App\Model\Eggs', 'Owner', 'ID');
    }

    public function food()
    {
        return $this->hasMany('App\Model\Foods', 'Owner', 'ID');
    }

    public function pool()
    {
        return $this->hasMany('App\Model\Pools', 'Owner', 'ID');
    }
}
