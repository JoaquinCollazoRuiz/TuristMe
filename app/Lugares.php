<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lugares extends Model
{

 public function usuarios()
 {
    return $this -> belongsTo ('App\Usuarios');
 }
}
