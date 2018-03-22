<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function table(){
        return $this->belongsTo('App\Table');
    }
    public function saledetails(){
        return $this->hasMany(Saledetail::class);
    }
}
