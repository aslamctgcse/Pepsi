<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
     #realtional fn to get product varient
      public function productvarient()
  {
    return $this->hasMany('App\Varient', 'product_id', 'product_id');
  }
  
}
