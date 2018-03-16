<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [ 'name', 'state' ];
    
    public function foodtabletemps(){
        return $this->hasMany('App\Foodtabletemp');
    }
}
