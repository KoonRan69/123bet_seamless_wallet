<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $table = 'mission';
    public $timestamps = false;
    public function listMission(){
        return $this->hasMany('App\Model\ListMission','mission_id', 'id');
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
