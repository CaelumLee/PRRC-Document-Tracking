<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statuscode extends Model
{
     //Table Name
     protected $table = 'statuscode';
     //Primary Key
     public $primaryKey = 'id';

     public $timestamps = false;

     public function routeInfo()
     {
          return $this->hasOne('App\RouteInfo');
     }
}
