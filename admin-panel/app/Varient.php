<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Varient extends Model
{
	  protected $table = 'product_varient';
	  #make relation for stock
	   public function productstock()
  {
    return $this->hasOne('App\Storeproduct', 'varient_id', 'varient_id');
  }




   
}
