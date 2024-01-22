<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SportBook extends Model
{
  function sendPost($url, $post_data)
  {
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($post_data));
    $result=curl_exec($ch);
    curl_close($ch);
    return  $result;
  }
}
