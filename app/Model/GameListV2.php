<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class GameListV2 extends Model{
  protected $table = "list_game_v2";
  public $timestamps = true;

  public function gameChildens()
  {
    return $this->hasMany('App\Model\GameListV2', 'parent')->where('show', 1);
  }
  public function gameParent()
  {
    return $this->hasOne('App\Model\GameListV2', 'id','parent')->where('show', 1);
  }
}
