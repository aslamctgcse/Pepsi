<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class DeliveryTypeController extends Controller
{

    public function deliveryType(Request $request)
    {
     
     $store_lat =$request->latitude;
     $store_long =$request->longitude;
     $catName =$request->city_name;
     

     $deliveryType = DB::table('delivery_type')
          ->where('status', 1)
          ->select('delivery_type.id as delivery_type_id','delivery_type.delivery_type as delivery_type_name','delivery_type.time as delivery_time','delivery_type.fee as delivery_fee')
          ->get();

        if(count($deliveryType)>0) {


          $message = array('status'=>'1', 'message'=>'data found', 'data'=>$deliveryType);
          return $message;
        } else {
            $message = array('status'=>'0', 'message'=>'data not found', 'data'=>[]);
            return $message;
        }
    }

   // break here
         
    
}