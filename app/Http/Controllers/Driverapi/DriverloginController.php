<?php

namespace App\Http\Controllers\Driverapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class DriverloginController extends Controller
{
 public function driver_login(Request $request)
    
     {
    	$phone = $request->phone;
    	$password = $request->password;
    	$device_id = $request->device_id;
    	$checkdriver1 = DB::table('delivery_boy')
    					->where('boy_phone', $phone)
    					->first();
    if($checkdriver1){					
    	$checkdriver = DB::table('delivery_boy')
    					->where('boy_phone', $phone)
    					->where('password', $password)
    					->first();

    	if($checkdriver){
    		   $updateDeviceId = DB::table('delivery_boy')
    		                       ->where('boy_phone', $phone)
    		                        ->update(['device_id'=>$device_id]);
    		                       
    		                        
    			$message = array('status'=>'1', 'message'=>'login successfully', 'data'=>[$checkdriver]);
	        	return $message;
    	   }	   
    	
    	
    	else{
    		$message = array('status'=>'0', 'message'=>'Wrong Password', 'data'=>[]);
	        return $message;
    	}
    }
    else{
        	$message = array('status'=>'0', 'message'=>'Driver Not Registered', 'data'=>[]);
	        return $message;
    }
    }
    
    
    
    
    public function driverprofile(Request $request)
    {   
        $boy_id = $request->dboy_id;
         $diver=  DB::table('delivery_boy')
                ->where('dboy_id', $boy_id )
                ->first();
                        
    if($diver){
        	$message = array('status'=>'1', 'message'=>'Delivery Boy Profile', 'data'=>$diver);
	        return $message;
              }
    	else{
    		$message = array('status'=>'0', 'message'=>'Delivery Boy not found', 'data'=>[]);
	        return $message;
    	}
        
    }


    public function driverBoyCurrentLoc(Request $request)
     {
		$boy_id = $request->dboy_id;
        $latitude = $request->lat;
        $longitude = $request->lng;

        // $boy_id = 5;
        // $latitude = '22.67657657';
        // $longitude = '77.432432';

        $checkdriver1 = DB::table('delivery_boy')
        ->where('dboy_id', $boy_id)
        ->first();
        if($checkdriver1){ 

           $insert = DB::table('rider_current_location')
                            ->insertGetId([
                              'loc_dboy_id' => $boy_id,
                              'loc_lat'     => $latitude,
                              'loc_long'    => $longitude,
                            ]);
				//dd($insert);
            if($insert) {
                 $message = array('status'=>'1', 'message'=>'Driver location entered successfully', 'data'=>[]);
                 return $message;

            }else{
                 $message = array('status'=>'0', 'message'=>'Driver location not entered', 'data'=>[]);
                return $message;
            }                

        }else{
          $message = array('status'=>'0', 'message'=>'Driver Not Registered', 'data'=>[]);
         return $message;
        }
    }
}