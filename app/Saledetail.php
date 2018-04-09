<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saledetail extends Model
{
    protected $fillable = ['sale_id', 'food_id', 'foodprice', 'quantity', 'pricetotal'];
    public function food(){
        return $this->belongsTo('App\Food');
    }
}
