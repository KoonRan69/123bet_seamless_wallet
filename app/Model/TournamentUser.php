<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TournamentUser extends Model
{
  protected $table = 'tournament_user';
  protected $fillable = ['id', 'tournament_id', 'user_id', 'created_at', 'updated_at', 'expired_at', 'status'];
  public function tournament(){
    return $this->belongsTo('App\Model\TournamentSetting','tournament_id', 'id');
  }

  public static function addMission($mission, $description, $mission_url, $gold, $mission_type){
    $add_mission = new Mission;
    $add_mission->mission = $mission;
    $add_mission->description = $description;
    $add_mission->mission_url = $mission_url;
    $add_mission->gold = $gold;
    $add_mission->mission_type = $mission_type;
    $add_mission->save();
    return $add_mission;
  }

  public static function editMission($id, $mission, $description, $mission_url, $gold, $mission_type){
    $edit_mission = Mission::find($id);
    $edit_mission->mission = $mission;
    $edit_mission->description = $description;
    $edit_mission->mission_url = $mission_url;
    $edit_mission->gold = $gold;
    $edit_mission->mission_type = $mission_type;
    $edit_mission->save();
    return $edit_mission;
  }
}