<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
 
  public function rol()
 {
    return $this-> belongsTo ('App\Rol');
 } 

 public function lugares()
 {
    return $this -> hasMany ('App\Lugares');
 }

}
