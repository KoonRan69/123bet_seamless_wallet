<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProUser extends Model
{

  /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
  protected $table = 'pro_users';
  protected $fillable = [
    'User_ID', 'User_Name','User_Provide', 'User_Email' , 'User_Parent_ID',	'User_WM_Password',	'User_Evo_Password','User_Agin_Password',	'User_Agin',	'User_WM555',	'User_789API',	'User_Casino',	'User_SportBook',	'User_AZ8SportBook',	'User_SkyGame',	'User_Evo',	'User_Status',	'User_AWC', 'User_AWC_Password'
  ];

  /**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
  public $timestamps = false;

}