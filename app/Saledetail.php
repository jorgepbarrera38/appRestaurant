<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saledetail extends Model
{
    public function food(){
        return $this->belongsTo('App\Food');
    }
}
