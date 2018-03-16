<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foodtabletemp extends Model
{
    public function table(){
        return $this->belongsTo('App\Table');
    }
    public function food(){
        return $this->belongsTo('App\Food');
    }
}
