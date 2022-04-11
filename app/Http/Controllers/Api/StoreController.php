<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class StoreController extends Controller
{

    public function store(Request $request)
    {
     
     $store_lat =$request->latitude;
     $store_long =$request->longitude;
     $city =$request->city;
     $pincode =$request->pincode;

     // $store_lat ='28.7041';
     // $store_long ='77.1025';
     // $city ='delhi';
     // $pincode ='1100916';
     

     $storeList = DB::table('store')
          ->where('store_status', 1)
          ->where('store_delete_status', 0)
          ->select('store.store_id as store_id','store.store_name as store_name','store.store_name as store_name','store.store_image as store_image','store.city as store_city','store.del_range as store_range','store.lat as store_lat','store.lng as store_lng','store.zip_code as store_zipcode','store.address as store_address','store.store_state as store_state','store.store_desc as store_cat')
            ->orderby('store.store_id','asc');
      if($city){
            $storeList->where('store.city','like','%' . $city . '%');
      }             
      if($pincode){
            $storeList->where('store.zip_code','=',$pincode);
      }      
      $storeList=$storeList->get();

        if(count($storeList)>0) {

          $result = array();
          $i = 0;


          foreach ($storeList as $stores) {
            if($stores->store_image){
              $stores->store_image=url('/').'/admin-panel/'.$stores->store_image;
            }else{
              //dd('no');
              $stores->store_image=url('/').'/assets/img/sailogo.png';
            }

                 
          }

          $message = array('status'=>'1', 'message'=>'data found', 'data'=>$storeList);
          return $message;
        } else {
            $message = array('status'=>'0', 'message'=>'data not found', 'data'=>[]);
            return $message;
        }
    }

   // break here
         
    
}