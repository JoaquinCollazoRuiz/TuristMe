<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lugares extends Model
{
protected $table = 'lugares'; //Nombre de la tabla a la que me estoy refiriendo 
protected $fillable = ['nombre']; // Atributos de la tabla

 public function usuarios()
 {
    return $this -> belongsTo ('App\Usuarios');
 }
}
