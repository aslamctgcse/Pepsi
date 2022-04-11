<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon\Carbon;

class AssignorderController extends Controller
{
    public function assignedorders(Request $request)
    {
         $title = "Order section (Assigned)";
         $email=Session::get('bamaStore');
    	 $store= DB::table('store')
    	 		   ->where('email',$email)
    	 		   ->first();
    	 $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('sub_orders')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->join('delivery_boy', 'sub_orders.dboy_id','=','delivery_boy.dboy_id')
             ->where('sub_orders.store_id',$store->store_id)
             ->orderBy('sub_orders.delivery_date','DESC')
             ->orderBy('sub_orders.sub_order_id','DESC')
             ->where('payment_method', '!=', NULL)
             ->where('sub_orders.order_status','!=','Pending')
             ->where('sub_orders.order_status','!=','cancelled')
             ->where('sub_orders.dboy_id','!=','0')
             ->paginate(8);
           $nearbydboy = DB::table('delivery_boy')
                ->leftJoin('sub_orders', 'delivery_boy.dboy_id', '=', 'sub_orders.dboy_id') 
                ->select("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city",DB::raw("Count(sub_orders.sub_order_id)as count"),DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
                * cos(radians(delivery_boy.lat)) 
                * cos(radians(delivery_boy.lng) - radians(" . $store->lng . ")) 
                + sin(radians(" .$store->lat. ")) 
                * sin(radians(delivery_boy.lat))) AS distance"))
               ->groupBy("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city")
               ->where('delivery_boy.boy_city', $store->city)
               ->orderBy('distance')
               ->get();      
         $details  =   DB::table('sub_orders')
    	                ->join('store_orders', 'sub_orders.cart_id', '=', 'store_orders.order_cart_id') 
    	               ->where('sub_orders.store_id',$store->store_id)
    	               ->where('store_orders.store_approval',1)
    	               ->get();         
                
       return view('store.orders.assignedorders', compact('title','logo','ord','store','details','nearbydboy'));         
    }
        
    
    public function orders(Request $request)
    {
         $title = "Order section (Unassigned)";
         $email=Session::get('bamaStore');
    	 $store= DB::table('store')
    	 		   ->where('email',$email)
    	 		   ->first();
    	 $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
     
        $ord =DB::table('sub_orders')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->where('sub_orders.store_id',$store->store_id)
             ->orderBy('sub_orders.delivery_date','DESC')
             ->orderBy('sub_orders.sub_order_id','DESC')
             ->where('sub_orders.order_status', 'Pending')
             ->where('payment_method', '!=', NULL)
            // ->where('payment_status', '!=', NULL)
             ->where('sub_orders.dboy_id',0)
             ->paginate(8);
 
         $details  =   DB::table('sub_orders')
            	                ->join('store_orders', 'sub_orders.cart_id', '=', 'store_orders.order_cart_id') 
            	                ->where('sub_orders.store_id',$store->store_id)
            	                ->where('store_orders.store_approval',1)
            	                ->get();

          $store_id = $store->store_id;
    	 		   
    	  $nearbydboy = DB::table('delivery_boy')
                ->leftJoin('sub_orders', 'delivery_boy.dboy_id', '=', 'sub_orders.dboy_id') 
                ->select("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city",DB::raw("Count(sub_orders.sub_order_id)as count"),DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
                * cos(radians(delivery_boy.lat)) 
                * cos(radians(delivery_boy.lng) - radians(" . $store->lng . ")) 
                + sin(radians(" .$store->lat. ")) 
                * sin(radians(delivery_boy.lat))) AS distance"))
               ->groupBy("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city")
               //->where('delivery_boy.boy_city', $store->city)
               ->having("distance", "<", $store->del_range)
               ->orderBy('distance','asc')
               ->get();

       return view('store.orders.orders', compact('title','logo','ord','store','details', 'nearbydboy'));         
    }    

       
    public function confirm_order(Request $request)
    {
       $sub_order_id= $request->sub_order_id; 
       //$sub_cart_id= $request->sub_cart_id;
       $dboy_id = $request->dboy_id;
        $email=Session::get('bamaStore');
    	 $store= DB::table('store')
    	 		   ->where('email',$email)
    	 		   ->first();
        $store_id = $store->store_id;  
        $curr = DB::table('currency')
             ->first(); 
        //dd('dfs');          
        
          /*$orr =   DB::table('orders')
                ->where('cart_id',$cart_id)
                ->first();*/
         /*$sub_orr =   DB::table('sub_orders')
                ->where('sub_order_cart_id',$sub_cart_id)
                ->first();*/
         $sub_orr =   DB::table('sub_orders')
                ->where('sub_order_id',$sub_order_id)
                ->first();
          $sub_cart_id= $sub_orr->sub_order_cart_id;
          
                 
         $cart_id= $sub_orr->cart_id; 
         $ord_main_total_qty = DB::table('sub_orders')
                     ->where('cart_id',$cart_id)
                     ->get()->count(); 
                            
               
          $v = DB::table('store_orders')
 		   ->where('order_cart_id',$cart_id)
 		   ->get(); 
 		 
        $getDevice = DB::table('delivery_boy')
                         ->where('dboy_id', $dboy_id)
                        ->select('device_id','boy_name')
                        ->first();  
 		    
 		 foreach($v as $vs){
                $qt = $vs->qty;
                $pr = DB::table('product_varient')
                    ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                    ->where('varient_id',$vs->varient_id)
                    ->first(); 
                    
                 $stoc = DB::table('store_products')
                    ->where('varient_id',$vs->varient_id)
                    ->where('store_id',$store_id) 
                    ->first();
              if($stoc){     
                $newstock = $stoc->stock - $qt;     
                $st = DB::table('store_products')
                    ->where('varient_id',$vs->varient_id)
                    ->where('store_id',$store_id)
                    ->update(['stock'=>$newstock]);
              }
              else{
                  return redirect()->back()->withErrors($pr->product_name."(".$pr->quantity." ".$pr->unit.") is not available in your product list");
              }
             }
    
         
 
       $orderconfirm = DB::table('sub_orders')
                    ->where('sub_order_cart_id',$sub_cart_id)
                    ->update(['order_status'=>'Confirmed',
                    'dboy_id'=>$dboy_id]);
                    
          $v = DB::table('store_orders')
 		   ->where('sub_order_cart_id',$sub_cart_id)
 		   ->get();  
 		   
         if($orderconfirm){
                $notification_title = "You Got a New Order for Delivery on ".$sub_orr->delivery_date;
                $notification_text = "you got an order with cart id #".$cart_id." of price ".$curr->currency_sign." " .$sub_orr->total_price. ". It will have to delivered on ".$sub_orr->delivery_date." between ".$sub_orr->time_slot.".";
                
                $date = date('d-m-Y');
                $getUser = DB::table('delivery_boy')
                                ->get();
        
                $created_at = Carbon::now();
        
                
                $getFcm = DB::table('fcm')
                            ->where('id', '1')
                            ->first();
                            
                $getFcmKey = $getFcm->driver_server_key;
                $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
                $token = $getDevice->device_id;
                    
        
                    $notification = [
                        'title' => $notification_title,
                        'body' => $notification_text,
                        'sound' => true,
                    ];
                    
                    $extraNotificationData = ["message" => $notification];
        
                    $fcmNotification = [
                        'to'        => $token,
                        'notification' => $notification,
                        'data' => $extraNotificationData,
                    ];
        
                    $headers = [
                        'Authorization: key='.$getFcmKey,
                        'Content-Type: application/json'
                    ];
        
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                    $result = curl_exec($ch);
                    curl_close($ch);
                   $results = json_decode($result);
             
             
        	return redirect()->back()->withSuccess('Order is confirmed and Assigned to '.$getDevice->boy_name);
              }
    	else{
    	return redirect()->back()->withErrors('Already Assigned to '.$getDevice->boy_name);
    	} 
    }



    //Not delivered order reason

    public function notDeliveredOrdersReason(Request $request)
    {

         $title = "Not delivered Order Reason";
         $email=Session::get('bamaStore');
         $store= DB::table('store')
                   ->where('email',$email)
                   ->first();
         $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();

                
                
        $ord =DB::table('not_delivered_reason')
             ->join('sub_orders', 'not_delivered_reason.not_del_sub_cart_id', '=','sub_orders.sub_order_cart_id')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->join('delivery_boy', 'not_delivered_reason.not_del_dboy_id','=','delivery_boy.dboy_id')
             ->where('not_delivered_reason.not_del_store_id',$store->store_id)
             ->orderBy('not_delivered_reason.not_del_datetime','DESC')
             ->orderBy('not_delivered_reason.not_del_id','DESC')
             //->where('not_delivered_reason.not_del_status','!=','1')
            // ->paginate(8); //old commented by me
             ->get(); //added by me
          //dd($ord);   

           $nearbydboy = DB::table('delivery_boy')
                ->leftJoin('sub_orders', 'delivery_boy.dboy_id', '=', 'sub_orders.dboy_id') 
                ->select("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city",DB::raw("Count(sub_orders.sub_order_id)as count"),DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
                * cos(radians(delivery_boy.lat)) 
                * cos(radians(delivery_boy.lng) - radians(" . $store->lng . ")) 
                + sin(radians(" .$store->lat. ")) 
                * sin(radians(delivery_boy.lat))) AS distance"))
               ->groupBy("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city")
               ->where('delivery_boy.boy_city', $store->city)
               ->orderBy('distance')
               ->get();      
         $details  =   DB::table('sub_orders')
                        ->join('store_orders', 'sub_orders.cart_id', '=', 'store_orders.order_cart_id') 
                       ->where('sub_orders.store_id',$store->store_id)
                       ->where('store_orders.store_approval',1)
                       ->get(); 
         //dd($details);              

                
       return view('store.orders.notdeliveredorders', compact('title','logo','ord','store','details','nearbydboy'));         
    }
            
    
   
   
}      