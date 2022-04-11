<?php

namespace App\Http\Controllers\Storeapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class AssignController extends Controller
{
 public function delivery_boy_list (Request $request)
     {
         
         $store_id = $request->store_id;
         $store= DB::table('store')
             ->where('store_id',$store_id)
             ->first();
         //dd($store);    
             
        $nearbydboy = DB::table('delivery_boy')
                ->leftJoin('orders', 'delivery_boy.dboy_id', '=', 'orders.dboy_id') 
                ->select("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city",DB::raw("Count(orders.order_id)as count"),DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
                * cos(radians(delivery_boy.lat)) 
                * cos(radians(delivery_boy.lng) - radians(" . $store->lng . ")) 
                + sin(radians(" .$store->lat. ")) 
                * sin(radians(delivery_boy.lat))) AS distance"))
               ->groupBy("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city")
               ->having("distance", "<", $store->del_range)
               ->orderBy("distance",'asc')
               //->where('delivery_boy.boy_city', $store->city)
               //->orderBy('distance')
               ->get();   
               
        if (count($nearbydboy)>0){

          foreach($nearbydboy as $key => $nearbydboys) {
            if($nearbydboy[$key]->distance==null || $nearbydboy[$key]->distance==0){
              $nearbydboy[$key]->distance=0.001;
            }
          }

          $message = array('status'=>'1', 'message'=>'Delivery Boy List', 'data'=>$nearbydboy);
          return $message;
              }
      else{
        $message = array('status'=>'0', 'message'=>'No Delivery Boy Available In Your City/Near Your Store');
          return $message;
      } 
      
   }
   
   
    public function storeconfirm(Request $request)
    {
        $cart_id    = $request->cart_id;
        $dboyid     = $request->dboy_id;
        $store_id   = $request->store_id;

        // $cart_id    = 'YLCC5948';
        // $dboyid     = '5';
        // $store_id   = '6';
      
        $curr       = DB::table('currency')
                        ->first();
       
        $store= DB::table('store')
                  ->where('store_id',$store_id)
                ->first();
       
        $getDevice = DB::table('delivery_boy')
                        ->where('dboy_id', $dboyid)
                        ->select('device_id','boy_name')
                        ->first();
                        
        $orr        =   DB::table('orders')
                            ->where('cart_id',$cart_id)
                            ->first();
        $sub_orr    =   DB::table('sub_orders')
                            ->where('cart_id',$cart_id)
                           ->where('store_id',$store_id) 
                            ->first();
        $sub_cart_id= $sub_orr->sub_order_cart_id;                                                            
                    
        $v = DB::table('store_orders')
           ->where('order_cart_id',$cart_id)
           ->where('sub_order_cart_id',$sub_cart_id)
           ->where('cancel_status','!=' , '1')
           ->get();
            
          
       
        foreach($v as $vs) {
                    $qt = $vs->qty;
                    $pr = DB::table('product_varient')
                            ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                            ->where('varient_id',$vs->varient_id)
                            ->first(); 
                        
                     $stoc = DB::table('store_products')
                                ->where('varient_id',$vs->varient_id)
                                ->where('store_id',$store_id) 
                                ->first();
                    //dd($stoc);             
                    if($stoc){     
                        $newstock = $stoc->stock - $qt;     
                        $st     =   DB::table('store_products')
                                    ->where('varient_id',$vs->varient_id)
                                    ->where('store_id',$store_id)
                                    ->update(['stock'=>$newstock]);
                    } else{
                        $message = array('status'=>'0', 'message'=>$pr->product_name."(".$pr->quantity." ".$pr->unit.") is not available in your store product list");
                      return $message;
                    }
                }
                
        $orderconfirm = DB::table('sub_orders')
                    ->where('cart_id',$cart_id)
                    ->where('sub_order_cart_id',$sub_cart_id)
                    ->update(['order_status'=>'Confirmed',
                    'dboy_id'=>$dboyid]);
         
       $orderconfirm = true;
         if($orderconfirm){
                $notification_title = "You Got a New Order for Delivery on ".$sub_orr->delivery_date;
                //$notification_text = "you got an order with cart id #".$cart_id." of price ".$curr->currency_sign." " .round($sub_orr->total_price). ". It will have to delivered on ".$sub_orr->delivery_date." between ".$sub_orr->time_slot.".";
                $notification_text = "you got an order with cart id #".$cart_id." of price ".$curr->currency_sign." " .round($sub_orr->total_price). ". It will have to delivered on ".$sub_orr->delivery_date.".";
                
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
             
              $message = array('status'=>'1', 'message'=>'Order is confirmed and Assigned to '.$getDevice->boy_name);
              return $message;
    }
      else{
        $message = array('status'=>'0', 'message'=>'Already Assigned to '.$getDevice->boy_name);
          return $message;
      } 
   
    }
   
   
   
}      