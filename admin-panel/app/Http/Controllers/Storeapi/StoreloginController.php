<?php

namespace App\Http\Controllers\Storeapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class StoreloginController extends Controller
{
 public function store_login(Request $request)
    
     {
    	$email = $request->email;
    	$password = $request->password;
    	$device_id = $request->device_id;
    	$checkstore1 = DB::table('store')
    					->where('email', $email)
    					->first();
    if($checkstore1){					
    	$checkstore = DB::table('store')
    					->where('email', $email)
    					->where('password', $password)
    					->first();

    	if($checkstore){
    		   $updateDeviceId = DB::table('store')
    		                       ->where('email', $email)
    		                        ->update(['device_id'=>$device_id]);
    		                       
    		                        
    			$message = array('status'=>'1', 'message'=>'login successfully', 'data'=>[$checkstore]);
	        	return $message;
    	   }	   
    	
    	
    	else{
    		$message = array('status'=>'0', 'message'=>'Wrong Password', 'data'=>[]);
	        return $message;
    	}
    }
    else{
        	$message = array('status'=>'0', 'message'=>'Store Not Registered', 'data'=>[]);
	        return $message;
    }
    }
    
    
    
    
    public function storeprofile(Request $request)
    {   
        $store_id = $request->store_id;
        $store  =   DB::table('store')
                        ->join('orders','store.store_id','=','orders.store_id')
                        ->leftJoin('store_earning','store.store_id','=','store_earning.store_id')
                        ->select('store.store_name','store.phone_number','store.email','store.address','store_earning.paid',DB::raw('SUM(orders.total_price)-SUM(orders.total_price)*(store.admin_share)/100 as store_earning'))
                        ->groupBy('store.store_name','store.phone_number','store.email','store.address','store_earning.paid','store.admin_share')
                        ->where('orders.order_status','Completed')
                        ->where('store.store_id', $store_id )
                        ->first();
                
        $storeData  =       [
                                "store_name"     =>  (string)$store->store_name ?? '',
                                "phone_number"   =>  (string)$store->phone_number ?? '',
                                "email"          =>  (string)$store->email ?? '',
                                "address"        =>  (string)$store->address ?? '',
                                "paid"           =>  (string)($store->paid) ? round($store->paid) : '',
                                "store_earning"  =>  (string)($store->store_earning) ? round($store->store_earning) : ''
                            ];
                            
                        
        if($store) {
        	$message = array('status' => '1', 'message' => 'Store Profile', 'data' => $storeData);
	        return $message;
        } else {
    		$message = array('status' => '0', 'message' => 'Store not found', 'data' => []);
	        return $message;
        }
        
    }
}