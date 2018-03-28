<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [ 'name', 'state', 'active'];
    
    public function foodtabletemps(){
        return $this->hasMany('App\Foodtabletemp');
    }
    public function sales(){
        return $this->hasMany('App\Sale');
    }
}
