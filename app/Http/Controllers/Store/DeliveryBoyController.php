<?php

namespace App\Http\Controllers\Store;

use DB;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DeliveryBoyController extends Controller
{
	/**
	 * listing all delivery boy of store.
	 * 
	 * @param 
	 */
	public function deliveryBoyList()
	{
		# set title of listing.
        $title = "All Delivery Boys of store";

        # get email of login store.
        $email = Session::get('bamaStore');

        # get logo.
	  	$logo = DB::table('tbl_web_setting')
		            ->where('set_id', '1')
		            ->first();

        # get store with their session email.
    	$store  = DB::table('store')
	    	 		   ->where('email', $email)
	    	 		   ->first();

		# get all Dboy of store.
    	$dBoys  = 	DB::table('delivery_boy')
    					->LeftJoin('store', 'delivery_boy.store_id', '=', 'store.store_id')
	    	 		   	->where('delivery_boy.store_id', $store->store_id)
	    	 		   	->paginate(10);

	 	# show listing of delivery boy.
	   	return view('store.delivery_boy.delivery_boy_list', compact('title', 'dBoys', 'logo', 'store'));
	}

	public function delivery_boy_orders(Request $request)
    {
         $title = "Delivery Boy Order section";
         $id = $request->id;
         $dboy = DB::table('delivery_boy')
                ->where('dboy_id',$id)
                ->first();
         # get store with their session email.
                # get email of login store.
        $email = Session::get('bamaStore');
    	 $store  = DB::table('store')
	    	 		   ->where('email', $email)
	    	 		   ->first();        
      //    $admin_email=Session::get('bamaAdmin');
    	 // $admin= DB::table('admin')
    	 // 		   ->where('admin_email',$admin_email)
    	 // 		   ->first();
    	$logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
    
        $date = date('Y-m-d');
     $nearbydboy = DB::table('delivery_boy')
                ->leftJoin('sub_orders', 'delivery_boy.dboy_id', '=', 'sub_orders.dboy_id')
                ->leftJoin('store', 'sub_orders.store_id', '=', 'store.store_id') 
                ->select("store.store_id","store.del_range","delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city",DB::raw("Count(sub_orders.sub_order_id)as count"),DB::raw("6371 * acos(cos(radians(".$dboy->lat . ")) 
                * cos(radians(delivery_boy.lat)) 
                * cos(radians(delivery_boy.lng) - radians(" . $dboy->lng . ")) 
                + sin(radians(" .$dboy->lat. ")) 
                * sin(radians(delivery_boy.lat))) AS distance"))
               ->groupBy("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city")
               //->where('delivery_boy.boy_city', $dboy->boy_city)
               ->where('delivery_boy.dboy_id','!=',$dboy->dboy_id)
               ->having("distance", "<", 'store.del_range')
               ->orderBy("distance",'asc')
               ->orderBy('count')
               
               ->get();
       //dd($nearbydboy);          
    
                
        $ord =DB::table('sub_orders')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->orderBy('sub_orders.delivery_date','DESC')
             ->orderBy('sub_orders.sub_order_id','DESC')
             ->where('sub_orders.dboy_id',$dboy->dboy_id)
             ->where('order_status','!=', 'completed')
             //->paginate(20);
             ->get();
             
         $details  =   DB::table('sub_orders')
    	               ->join('store_orders', 'sub_orders.cart_id', '=', 'store_orders.order_cart_id') 
    	               ->where('sub_orders.dboy_id',$id)
    	               ->where('store_orders.store_approval',1)
    	               ->get();          
                
       return view('store.delivery_boy.delivery_boy_order_list', compact('title','logo','ord','dboy','details','store','nearbydboy'));         
    }
}