<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'name', 'description', 'price'
    ];
    public function foodtabletemps(){
        return $this->hasMany('App\Foodtabletemp');
    }
    public function scopeName($query, $name){
        if($name){
            $query->where('name', 'LIKE', '%'.$name.'%');
        }
    }
}
