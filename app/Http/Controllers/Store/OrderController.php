<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon\Carbon;

class OrderController extends Controller
{
    
    public function cancel_products(Request $request)
    {
       $id= $request->store_order_id;
       $cart = DB::table('store_orders')
            ->select('order_cart_id','varient_id','qty')
            ->where('store_order_id', $id)
            ->first();
      $curr = DB::table('currency')
            ->first();
      $cart_id = $cart->order_cart_id;
      $var = DB::table('store_orders')
    ->where('order_cart_id', $cart_id)
    ->get();
       $price2 = 0;
     
     foreach ($var as $h){
        $varient_id = $h->varient_id;
        $p = DB::table('product_varient')
            ->join('product','product_varient.product_id','=','product.product_id')
           ->where('product_varient.varient_id',$varient_id)
           ->first();
        $price = $p->price;
        $mrpprice = $p->mrp;
        $order_qty = $h->qty;
        $price2+= $price*$order_qty;
        $unit[] = $p->unit;
        $qty[]= $p->quantity;
        $p_name[] = $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
        $prod_name = implode(',',$p_name);
        }
      $ordr = DB::table('orders')
            ->where('cart_id', $cart->order_cart_id)
            ->first();
        $v = DB::table('product_varient')
       ->where('varient_id', $cart->varient_id)
       ->first();
       
       $v_price =$v->price * $cart->qty;       
      $ordr = DB::table('orders')
            ->where('cart_id', $cart->order_cart_id)
            ->first();
       $user_id = $ordr->user_id;
       $tot_price = $ordr->total_price-$v_price;
       $rem_price = $ordr->rem_price-$v_price;
       if($rem_price>0){
          $rem_price2 = $ordr->rem_price-$v_price; 
       }else{
           $rem_price2 = 0;
       }
       if($tot_price>0){
          $tot_price2 =$ordr->total_price-$v_price; 
       }else{
           $tot_price2 = 0;
       }
       $userwa = DB::table('users')
                     ->where('user_id',$user_id)
                     ->first();
      if($ordr->payment_method == 'COD' || $ordr->payment_method == 'Cod' || $ordr->payment_method == 'cod'){         
        $newbal = $userwa->wallet;   
      }
      else{
        $newbal = $userwa->wallet + $v_price;  
      }
       $orders = DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->where('store_approval',1)
            ->get();   
       
        if(count($orders)==1 || count($orders)==0){
         $email=Session::get('bamaStore');
    	 $store= DB::table('store')
    	 		   ->where('email',$email)
    	 		   ->first();
         if($ordr->cancel_by_store==0){
            $cancel=1;
          $store_id = DB::table('store')
              ->select("store_id","store_name"
            ,DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
            * cos(radians(lat)) 
            * cos(radians(lng) - radians(" . $store->lng . ")) 
            + sin(radians(" .$store->lat. ")) 
            * sin(radians(lat))) AS distance"))
           ->where('city',$store->city) 
           ->where('store_id','!=',$store->store_id)
           ->orderBy('distance')
           ->first();
           
            if($store_id){
            $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart->order_cart_id)
                     ->update(['store_id'=>$store_id->store_id,
                     'cancel_by_store'=>$cancel]);
            $carte= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->where('store_approval',0)
            ->get();
         
            foreach($carte as $carts){
                $v1 = DB::table('product_varient')
               ->where('varient_id', $carts->varient_id)
               ->first();
               
               $v_price1 =$v1->price * $carts->qty;       
               $ordr1 = DB::table('orders')
                    ->where('cart_id', $carts->order_cart_id)
                    ->first();
               $user_id1 = $ordr1->user_id;
               if($ordr->payment_method != 'COD' || $ordr->payment_method != 'Cod' || $ordr->payment_method != 'cod'){
               $userwa1 = DB::table('users')
                             ->where('user_id',$user_id1)
                             ->first();
                $newbal1 = $userwa1->wallet - $v_price1;
                
                 $userwalletupdate = DB::table('users')
                     ->where('user_id',$user_id1)
                     ->update(['wallet'=>$newbal1]);
               }
            }
            
            $cart_update= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->update(['store_approval'=>1]);
               ///////send notification to store//////
              
                $notification_title = "WooHoo ! You Got a New Order";
                $notification_text = "you got an order cart id #".$cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$price2. ". It will have to delivered on ".$ordr->delivery_date." between ".$ordr->time_slot.".";
                
                $date = date('d-m-Y');
                $getUser = DB::table('store')
                                ->get();
        
                $getDevice = DB::table('store')
                         ->where('store_id', $store_id->store_id)
                        ->select('device_id')
                        ->first();
                $created_at = Carbon::now();
        
                
                $getFcm = DB::table('fcm')
                            ->where('id', '1')
                            ->first();
                            
                $getFcmKey = $getFcm->store_server_key;
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
                    
                     ///////send notification to store//////
             
                $dd = DB::table('store_notification')
                    ->insert(['store_id'=>$store_id->store_id,
                     'not_title'=>$notification_title,
                     'not_message'=>$notification_text]);
                    
                $results = json_decode($result);
            return redirect()->back()->withSuccess('Order cancelled successfully');
            }
            else{
            $carte= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->where('store_approval',0)
            ->get();
            
            foreach($carte as $carts){
                $v1 = DB::table('product_varient')
               ->where('varient_id', $carts->varient_id)
               ->first();
               
               $v_price1 =$v1->price * $carts->qty;       
               $ordr1 = DB::table('orders')
                    ->where('cart_id', $carts->order_cart_id)
                    ->first();
               $user_id1 = $ordr1->user_id;
                if($ordr->payment_method != 'COD' || $ordr->payment_method != 'Cod' || $ordr->payment_method != 'cod'){
               $userwa1 = DB::table('users')
                             ->where('user_id',$user_id1)
                             ->first();
                $newbal1 = $userwa1->wallet - $v_price1;
                 $userwalletupdate = DB::table('users')
                     ->where('user_id',$user_id1)
                     ->update(['wallet'=>$newbal1]);
                }
            }    
            $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart->order_cart_id)
                     ->update(['store_id'=>0,
                     'cancel_by_store'=>$cancel]);
            
            
            $cart_update= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->update(['store_approval'=>1]); 
            return redirect()->back()->withSuccess('Order cancelled successfully');
            }
        }
        else{
            $cancel=2;
            $carte= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->where('store_approval',0)
            ->get();
            
            foreach($carte as $carts){
                $v1 = DB::table('product_varient')
               ->where('varient_id', $carts->varient_id)
               ->first();
               
               $v_price1 =$v1->price * $carts->qty;       
               $ordr1 = DB::table('orders')
                    ->where('cart_id', $carts->order_cart_id)
                    ->first();
               $user_id1 = $ordr1->user_id;
              if($ordr->payment_method != 'COD' || $ordr->payment_method != 'Cod' || $ordr->payment_method != 'cod'){
               $userwa1 = DB::table('users')
                             ->where('user_id',$user_id1)
                             ->first();
                $newbal1 = $userwa1->wallet - $v_price1;
                 $userwalletupdate = DB::table('users')
                     ->where('user_id',$user_id1)
                     ->update(['wallet'=>$newbal1]);
              }
            }
             $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart->order_cart_id)
                     ->update(['store_id'=>0,
                     'cancel_by_store'=>$cancel]);
            
            
            $cart_update= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->update(['store_approval'=>1]);
        return redirect()->back()->withSuccess('Order cancelled successfully');
        }    
        return redirect()->back()->withSuccess('Order cancelled successfully');
         
        }    
            
        else{    
       $cancel_product = DB::table('store_orders')
                       ->where('store_order_id', $id)
                       ->update(['store_approval'=>0]);
     if($ordr->payment_method != 'COD' || $ordr->payment_method != 'Cod' || $ordr->payment_method != 'cod'){       
        $userwallet = DB::table('users')
                     ->where('user_id',$user_id)
                     ->update(['wallet'=>$newbal]);   
        $ordr = DB::table('orders')
            ->where('cart_id', $cart->order_cart_id)
            ->update(['total_price'=>$tot_price2,
            'rem_price'=>$rem_price2]);             
        }
        return redirect()->back()->withSuccess('product cancelled successfully');                  
                       
        }             
                   
    }

    /**
     * open reject pop up.
     * 
     * @param Request || $request
     */
    public function rejectOrderModal(Request $request)
    {
        # get cart id from request.
        $cart_id = $request->cart_id;

        # view reject modal.
        return view('store.orders.reject_modal', compact('cart_id'));
    }
    
    
    public function reject_order(Request $request)
    {
      $cart_id = $request->cart_id;
      $cause   = $request->cause;
      $date_of_recharge  = carbon::now();
      //dd('store based');

      # get order according his cart id
      $ordr = DB::table('orders')
                  ->where('cart_id', $cart_id)
                  ->first();

      # get user id of order
      $user_id1 = $ordr->user_id;

      // get the all order of store approval.
      $orders = DB::table('store_orders')
                  ->where('order_cart_id', $cart_id)
                  ->where('store_approval',1)
                  ->get();

      # get currency.
      $curr = DB::table('currency')
                  ->first(); 

      # get session email of store
      $email=Session::get('bamaStore');

      # get store details
    	$store= DB::table('store')
        	 		   ->where('email',$email)
        	 		   ->first();
      $store_id = $store->store_id;
      $storeId = $store->store_id;

      $sub_ordr = DB::table('sub_orders')
                  ->where('cart_id', $cart_id)
                  ->where('store_id',$store_id)
                  ->first(); 
      $sub_cart_id = $sub_ordr->sub_order_cart_id;           
      $userData = DB::table('users')
                     ->where('user_id',$sub_ordr->user_id)
                     ->first();
      $user_id1 = $sub_ordr->user_id;
      $userId = $sub_ordr->user_id;                                       

      # define a variable.            
      $v_price1 = 0;

      # Get the store orders un-approved.
      $cartss = DB::table('store_orders')
                    ->where('order_cart_id', $cart_id)
                    ->where('store_approval', 0)
                    ->get();
      
    
      # check unapproval exist or not.
      if(count($cartss) > 0) {
        # set price
        foreach($cartss as $carts) {
          $v1 = DB::table('store_orders')
                   ->where('store_order_id', $carts->store_order_id)
                   ->first();
         
          $v_price1 += $v1->price * $v1->qty;       
        }

        # get user id.
        $user_id1 = $ordr->user_id;
        $userwa1 = DB::table('users')
                       ->where('user_id',$user_id1)
                       ->first();

        // if($ordr->payment_method == 'COD' || $ordr->payment_method == 'Cod' || $ordr->payment_method == 'cod') {
        //     $newbal1 = $userwa1->wallet;   
        // } else{
        //     $newbal1 = $userwa1->wallet - $v_price1;
        // }

        $refundAmt=0;
        if($sub_ordr->payment_method == 'COD' || $sub_ordr->payment_method == 'Cod' || $sub_ordr->payment_method == 'cod'){
          $newbal1 = $userwa1->wallet;
          $refundAmt=0;

        }else if($sub_ordr->payment_method == 'wallet' || $sub_ordr->payment_method == 'Wallet' || $sub_ordr->payment_method == 'WALLET'){
          $newbal1 = $userwa1->wallet+$sub_ordr->paid_by_wallet;
          $refundAmt=$sub_ordr->paid_by_wallet;

        }else{
          $newbal1 = $userwa1->wallet+$sub_ordr->total_price;
          $refundAmt=$sub_ordr->total_price;
        }

        # update user wallet
        $userwalletupdate = DB::table('users')
                               ->where('user_id',$user_id1)
                               ->update(['wallet'=>$newbal1]);
      }

      # get store order approval according his cart id
      $var = DB::table('store_orders')
                ->where('order_cart_id', $cart_id)
                ->where('store_approval',1)
                ->get();

      $price2 = 0;     
      foreach ($var as $h){

        $varient_id = $h->varient_id;

        # get product detail according his varient.
        $p = DB::table('product_varient')
                    ->join('product','product_varient.product_id','=','product.product_id')
                    ->where('product_varient.varient_id', $varient_id)
                    ->first();

        $price      =   $p->price;   
        $order_qty  =   $h->qty;
        $price2     +=  $price * $order_qty;
        $unit[]     =   $p->unit;
        $qty[]      =   $p->quantity;
        $p_name[]   =   $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
        $prod_name  =   implode(',',$p_name);
      }


      $refundAmt=0;
        if($sub_ordr->payment_method == 'COD' || $sub_ordr->payment_method == 'Cod' || $sub_ordr->payment_method == 'cod'){
          $newbal1 = $userData->wallet;
          $refundAmt=0;

        }else if($sub_ordr->payment_method == 'wallet' || $sub_ordr->payment_method == 'Wallet' || $sub_ordr->payment_method == 'WALLET'){
          $newbal1 = $userData->wallet+$sub_ordr->paid_by_wallet;
          $refundAmt=$sub_ordr->paid_by_wallet;

        }else{
          $newbal1 = $userData->wallet+$sub_ordr->total_price;
          $refundAmt=$sub_ordr->total_price;
        }
               
      if($ordr->cancel_by_store == 0){

          $cancel=1;
          $store_id = DB::table('store')
                          ->select("store_id","store_name"
                        ,DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
                        * cos(radians(lat)) 
                        * cos(radians(lng) - radians(" . $store->lng . ")) 
                        + sin(radians(" .$store->lat. ")) 
                        * sin(radians(lat))) AS distance"))
                       //->where('city',$store->city) 
                       ->where('store_id','!=',$store->store_id)
                       //->orderBy('distance')
                       ->having("distance", "<", $store->del_range)
                       ->orderBy("distance",'asc')
                       ->first();
                   
          if($store_id) {

            //Old code for reassign to other

           /* $ordupdate = DB::table('orders')
                             ->where('cart_id', $cart_id)
                             ->update([
                                'store_id'        =>  $store_id->store_id,
                                'cancel_by_store' =>  $cancel,
                                'order_status'    =>  'Pending',
                                'cancelling_reason'=> $cause,
                             ]);
          
            $cart_update= DB::table('store_orders')
                              ->where('order_cart_id', $cart_id)
                              ->update(['store_approval' => 1]);
          
            ///////send notification to store//////
            $notification_title = "WooHoo ! You Got a New Order";

            $notification_text = "you got an order cart id #".$cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$price2. ". It will have to delivered on ".$ordr->delivery_date." between ".$ordr->time_slot.".";
            
            $date = date('d-m-Y');
            $getUser = DB::table('store')
                            ->get();
    
            $getDevice = DB::table('store')
                            ->where('store_id', $store_id->store_id)
                            ->select('device_id')
                            ->first();
            $created_at = Carbon::now();
    
            
            $getFcm = DB::table('fcm')
                        ->where('id', '1')
                        ->first();
                        
            $getFcmKey = $getFcm->store_server_key;
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
            
            ///////send notification to store//////
            $dd = DB::table('store_notification')
                      ->insert([
                        'store_id'=>$store_id->store_id,
                        'not_title'=>$notification_title,
                        'not_message'=>$notification_text
                      ]);
                
            $results = json_decode($result);*/

            //End for reassign to other store


            $sub_ordupdate = DB::table('sub_orders')
                     ->where('cart_id', $cart_id)
                     ->where('sub_order_cart_id', $sub_cart_id)
                     ->update(['cancel_by_store'=>$cancel,'total_price' =>   0,
                      'rem_price'=>0,'paid_by_wallet'=>0,'delivery_charge'=>0,'coupon_discount'=>  0,'coupon_id'=>0,
                      'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0,'order_status'=>'Cancelled','cancelling_reason'=>$cause]);

                    $ord_main_total_qty = DB::table('sub_orders')
                     ->where('cart_id',$cart_id)
                     ->get()->count();
                    $ord_main_cancel_qty = DB::table('sub_orders')
                     ->where('cart_id',$cart_id)
                     ->where('order_status','cancelled')
                     ->where('order_status','Cancelled')
                     ->get()->count();              

                    if($ord_main_total_qty==$ord_main_cancel_qty){
                      $order_update = DB::table('orders')
                      ->where('cart_id',$cart_id)
                      ->update(['order_status'=>'Cancelled','cancelling_reason'=>$cause,'cancel_by_store'=>1,'paid_by_wallet' =>0,'rem_price'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0]);   
                    }else{
                      $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart_id)
                     ->update([
                      'total_price'=>$ordr->total_price-$sub_ordr->total_price,
                      'paid_by_wallet' =>$ordr->paid_by_wallet-$sub_ordr->paid_by_wallet,
                      'rem_price'=>$ordr->rem_price-$sub_ordr->rem_price,
                      'delivery_charge'=>$ordr->delivery_charge-$sub_ordr->delivery_charge,
                      'coupon_discount'=>$ordr->coupon_discount-$sub_ordr->coupon_discount,
                      'bulk_order_based_discount'=>$ordr->bulk_order_based_discount-$sub_ordr->bulk_order_based_discount,
                      'online_payment_based_discount'=>$ordr->online_payment_based_discount-$sub_ordr->online_payment_based_discount,
                     ]);
                    }  


                    $userwalletupdate = DB::table('users')
                                    ->where('user_id',$userId)
                                    ->update(['wallet'=>$newbal1]);
                  if($userwalletupdate){
                     DB::table('wallet_recharge_history')
                            ->insert([
                                      'amount'        =>  $refundAmt,
                                      'type'          =>  'Sub Order Cancel Amount',
                                      'order_cart_id' =>  $sub_cart_id,
                                      'user_id'       =>  $userId,
                                      'date_of_recharge' =>  $date_of_recharge,
                                      'recharge_status'  =>  'success',
                                    ]);
                   }                                 
            
             $carte= DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('store_approval',0)
            ->get();
            $cart_update= DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('sub_order_cart_id', $sub_cart_id)
            ->update(['store_approval'=>1,'cancel_status'=>1,'cancel_reason'=>$cause]);

             ///////send notification to store//////
              
                $notification_title = "WooHoo ! Sub Order Cancelled";
                $notification_text = "The order of sub order cart id #".$sub_cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$sub_ordr->total_price. " is cancelled.";
                
                $date = date('d-m-Y');
                $getUser = DB::table('store')
                                ->get();
        
                $getDevice = DB::table('store')
                         ->where('store_id', $storeId)
                        ->select('device_id')
                        ->first();
                $created_at = Carbon::now();
        
                
                $getFcm = DB::table('fcm')
                            ->where('id', '1')
                            ->first();
                            
                $getFcmKey = $getFcm->store_server_key;
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

                   $dd = DB::table('store_notification')
                    ->insert(['store_id'=>$storeId,
                     'not_title'=>$notification_title,
                     'not_message'=>$notification_text]); 
                    
                     ///////send notification to store//////


                    /*Notification for User*/

                 $notification_title = "WooHoo! Your Sub Order Cancelled";
                 $notification_text = "Your order sub cart id #".$sub_cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$sub_ordr->total_price. " is cancelled.";
                
                
                $date = date('d-m-Y');

        
                $getDevice = DB::table('users')
                         ->where('user_id',$sub_ordr->user_id)
                        ->select('device_id')
                        ->first();
                $created_at = Carbon::now();
        
                
                $getFcm = DB::table('fcm')
                            ->where('id', '1')
                            ->first();

                $getFcmKey = $getFcm->server_key;
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
             
                    /*End for notification for user*/


                $dd_u = DB::table('user_notification')
                      ->insert([
                        'user_id'       =>  $sub_ordr->user_id,
                        'noti_title'    =>  $notification_title,
                        'noti_message'  =>  $notification_text
                      ]);

            return redirect()->back()->withSuccess('Order Rejected successfully');
          } else{

            // $ordupdate = DB::table('orders')
            //                  ->where('cart_id', $cart_id)
            //                  ->update([
            //                   'store_id'=>0,
            //                   'cancel_by_store'=>$cancel,
            //                   'order_status'=>'Pending',
            //                   'cancelling_reason'=> $cause,
            //                 ]);
          
            
            // $cart_update= DB::table('store_orders')
            //                 ->where('order_cart_id', $cart_id)
            //                 ->update(['store_approval'=>1]); 


            $sub_ordupdate = DB::table('sub_orders')
                     ->where('cart_id', $cart_id)
                     ->where('sub_order_cart_id', $sub_cart_id)
                     ->update(['cancel_by_store'=>$cancel,'total_price' =>   0,
                      'rem_price'=>0,'paid_by_wallet'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0,'order_status'=>'Cancelled','cancelling_reason'=>$cause]);

                    $ord_main_total_qty = DB::table('sub_orders')
                     ->where('cart_id',$cart_id)
                     ->get()->count();
                    $ord_main_cancel_qty = DB::table('sub_orders')
                     ->where('cart_id',$cart_id)
                     ->where('order_status','cancelled')
                     ->where('order_status','Cancelled')
                     ->get()->count();              

                    if($ord_main_total_qty==$ord_main_cancel_qty){
                      $order_update = DB::table('orders')
                      ->where('cart_id',$cart_id)
                      ->update(['order_status'=>'Cancelled','cancelling_reason'=>$cause,'cancel_by_store'=>1,'paid_by_wallet' =>0,'rem_price'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0]);   
                    }else{
                      $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart_id)
                     ->update([
                      'total_price'=>$ordr->total_price-$sub_ordr->total_price,
                      'paid_by_wallet' =>$ordr->paid_by_wallet-$sub_ordr->paid_by_wallet,
                      'rem_price'=>$ordr->rem_price-$sub_ordr->rem_price,
                      'delivery_charge'=>$ordr->delivery_charge-$sub_ordr->delivery_charge,
                      'coupon_discount'=>$ordr->coupon_discount-$sub_ordr->coupon_discount,
                      'bulk_order_based_discount'=>$ordr->bulk_order_based_discount-$sub_ordr->bulk_order_based_discount,
                      'online_payment_based_discount'=>$ordr->online_payment_based_discount-$sub_ordr->online_payment_based_discount
                     ]);
                    }  


                    $userwalletupdate = DB::table('users')
                                    ->where('user_id',$userId)
                                    ->update(['wallet'=>$newbal1]);
                  if($userwalletupdate){
                     DB::table('wallet_recharge_history')
                            ->insert([
                                      'amount'        =>  $refundAmt,
                                      'type'          =>  'Sub Order Cancel Amount',
                                      'order_cart_id' =>  $sub_cart_id,
                                      'user_id'       =>  $userId,
                                      'date_of_recharge' =>  $date_of_recharge,
                                      'recharge_status'  =>  'success',
                                    ]);
                   }                                 
            
             $carte= DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('store_approval',0)
            ->get();
            $cart_update= DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('sub_order_cart_id', $sub_cart_id)
            ->update(['store_approval'=>1,'cancel_status'=>1,'cancel_reason'=>$cause]);

             ///////send notification to store//////
              
                $notification_title = "WooHoo ! Sub Order Cancelled";
                $notification_text = "The order of sub order cart id #".$sub_cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$sub_ordr->total_price. " is cancelled.";
                
                $date = date('d-m-Y');
                $getUser = DB::table('store')
                                ->get();
        
                $getDevice = DB::table('store')
                         ->where('store_id', $storeId)
                        ->select('device_id')
                        ->first();
                $created_at = Carbon::now();
        
                
                $getFcm = DB::table('fcm')
                            ->where('id', '1')
                            ->first();
                            
                $getFcmKey = $getFcm->store_server_key;
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

                   $dd = DB::table('store_notification')
                    ->insert(['store_id'=>$storeId,
                     'not_title'=>$notification_title,
                     'not_message'=>$notification_text]); 
                    
                     ///////send notification to store//////


                    /*Notification for User*/

                 $notification_title = "WooHoo! Your Sub Order Cancelled";
                 $notification_text = "Your order sub cart id #".$sub_cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$sub_ordr->total_price. " is cancelled.";
                
                
                $date = date('d-m-Y');

        
                $getDevice = DB::table('users')
                         ->where('user_id',$sub_ordr->user_id)
                        ->select('device_id')
                        ->first();
                $created_at = Carbon::now();
        
                
                $getFcm = DB::table('fcm')
                            ->where('id', '1')
                            ->first();

                $getFcmKey = $getFcm->server_key;
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
             
                    /*End for notification for user*/


                $dd_u = DB::table('user_notification')
                      ->insert([
                        'user_id'       =>  $sub_ordr->user_id,
                        'noti_title'    =>  $notification_title,
                        'noti_message'  =>  $notification_text
                      ]);
           
            return redirect()->back()->withSuccess('Order Rejected successfully');
          }
      } else {
        
          $cancel=2;
          // $ordupdate = DB::table('orders')
          //              ->where('cart_id', $cart_id)
          //              ->update([
          //               'store_id'=>0,
          //               'cancel_by_store'=>$cancel,
          //               'order_status'=>'Pending',
          //               'cancelling_reason'=> $cause,
          //             ]);
          
          // $cart_update= DB::table('store_orders')
          //                 ->where('order_cart_id', $cart_id)
          //                 ->update(['store_approval'=>1]);


          $sub_ordupdate = DB::table('sub_orders')
                     ->where('cart_id', $cart_id)
                     ->where('sub_order_cart_id', $sub_cart_id)
                     ->update(['cancel_by_store'=>$cancel,'total_price' =>   0,
                      'rem_price'=>0,'paid_by_wallet'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0,'order_status'=>'Cancelled','cancelling_reason'=>$cause]);
            $ord_main_total_qty = DB::table('sub_orders')
                     ->where('cart_id',$cart_id)
                     ->get()->count();
                    $ord_main_cancel_qty = DB::table('sub_orders')
                     ->where('cart_id',$cart_id)
                     ->where('order_status','cancelled')
                     ->where('order_status','Cancelled')
                     ->get()->count();              

                    if($ord_main_total_qty==$ord_main_cancel_qty){
                      $order_update = DB::table('orders')
                      ->where('cart_id',$cart_id)
                      ->update(['order_status'=>'Cancelled','cancelling_reason'=>$cause,'cancel_by_store'=>1,'paid_by_wallet' =>0,'rem_price'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0]);   
                    }else{
                      $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart_id)
                     ->update(['total_price'=>$ordr->total_price-$sub_ordr->total_price,
                      'paid_by_wallet' =>$ordr->paid_by_wallet-$sub_ordr->paid_by_wallet,
                      'rem_price'=>$ordr->rem_price-$sub_ordr->rem_price,
                      'delivery_charge'=>$ordr->delivery_charge-$sub_ordr->delivery_charge,
                      'coupon_discount'=>$ordr->coupon_discount-$sub_ordr->coupon_discount,
                      'bulk_order_based_discount'=>$ordr->bulk_order_based_discount-$sub_ordr->bulk_order_based_discount,
                      'online_payment_based_discount'=>$ordr->online_payment_based_discount-$sub_ordr->online_payment_based_discount
                    ]);
                    } 

                    $userwalletupdate = DB::table('users')
                                    ->where('user_id',$userId)
                                    ->update(['wallet'=>$newbal1]);
                    if($userwalletupdate){
                     DB::table('wallet_recharge_history')
                            ->insert([
                                      'amount'        =>  $refundAmt,
                                      'type'          =>  'Sub Order Cancel Amount',
                                      'order_cart_id' =>  $sub_cart_id,
                                      'user_id'       =>  $userId,
                                      'date_of_recharge' =>  $date_of_recharge,
                                      'recharge_status'  =>  'success',
                                    ]);
                   }                 
            
             $carte= DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('store_approval',0)
            ->get();
            $cart_update= DB::table('store_orders')
            ->where('sub_order_cart_id', $sub_cart_id)
            ->where('order_cart_id', $cart_id)
            ->update(['store_approval'=>1,'cancel_status'=>1,'cancel_reason'=>$cause]);

            ///////send notification to store//////
              
                $notification_title = "WooHoo ! Sub Order Cancelled";
                $notification_text = "The order of sub order cart id #".$sub_cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$sub_ordr->total_price. " is cancelled.";
                
                $date = date('d-m-Y');
                $getUser = DB::table('store')
                                ->get();
        
                $getDevice = DB::table('store')
                         ->where('store_id', $storeId)
                        ->select('device_id')
                        ->first();
                $created_at = Carbon::now();
        
                
                $getFcm = DB::table('fcm')
                            ->where('id', '1')
                            ->first();
                            
                $getFcmKey = $getFcm->store_server_key;
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
                  $dd = DB::table('store_notification')
                    ->insert(['store_id'=>$storeId,
                     'not_title'=>$notification_title,
                     'not_message'=>$notification_text]);   
                    
                     ///////send notification to store//////


                    /*Notification for User*/

                 $notification_title = "WooHoo !Your Sub Order Cancelled";
                 $notification_text = "Your order sub cart id #".$sub_cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$sub_ordr->total_price. " is cancelled.";
                
                
                $date = date('d-m-Y');

        
                $getDevice = DB::table('users')
                         ->where('user_id',$sub_ordr->user_id)
                        ->select('device_id')
                        ->first();
                $created_at = Carbon::now();
        
                
                $getFcm = DB::table('fcm')
                            ->where('id', '1')
                            ->first();

                $getFcmKey = $getFcm->server_key;
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
             
                    /*End for notification for user*/

                $dd_u = DB::table('user_notification')
                      ->insert([
                        'user_id'       =>  $sub_ordr->user_id,
                        'noti_title'    =>  $notification_title,
                        'noti_message'  =>  $notification_text
                      ]);
          
          return redirect()->back()->withSuccess('Order Rejected successfully');
      }

      return redirect()->back()->withSuccess('Order Rejected successfully');
    }
}