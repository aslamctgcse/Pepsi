<?php

namespace App\Http\Controllers\Driverapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\Traits\SendMail;
use App\Traits\SendSms;

class DriverorderController extends Controller
{
    use SendMail;
    use SendSms;
    public function completed_orders(Request $request)
     {
         
        $dboy_id = $request->dboy_id;
        //$dboy_id = '5';
                   
        $ord =DB::table('sub_orders')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->join('store', 'sub_orders.store_id', '=', 'store.store_id')
             ->join('address', 'sub_orders.address_id','=','address.address_id')
             ->join('delivery_boy', 'sub_orders.dboy_id', '=','delivery_boy.dboy_id')
             ->select('sub_orders.sub_order_id','sub_orders.order_status','sub_orders.cart_id','sub_orders.sub_order_cart_id','sub_orders.delivery_code','users.user_name', 'users.user_phone', 'sub_orders.delivery_date', 'sub_orders.total_price','sub_orders.delivery_charge','sub_orders.coupon_discount','sub_orders.bulk_order_based_discount','sub_orders.online_payment_based_discount','sub_orders.rem_price','sub_orders.payment_status','sub_orders.payment_method','sub_orders.delivery_type','sub_orders.delivery_type_id','sub_orders.order_special_instructions as order_instruction','delivery_boy.boy_name','delivery_boy.boy_phone','sub_orders.time_slot', 'store.address as store_address', 'store.store_name','store.phone_number','store.lat as store_lat','store.lng as store_lng','address.lat as userlat', 'address.lng as userlng', 'delivery_boy.lat as dboy_lat', 'delivery_boy.lng as dboy_lng', 'address.receiver_name', 'address.receiver_phone', 'address.city','address.society','address.house_no','address.landmark','address.state')
             ->where('sub_orders.order_status' , 'completed')
             ->where('sub_orders.order_status' , 'Completed')
             ->where('sub_orders.dboy_id',$dboy_id)
             ->orderBy('sub_orders.delivery_date', 'desc')
             ->orderBy('sub_orders.sub_order_id', 'desc')
             ->get();

       //dd($ord);      
       
       if(count($ord)>0){
      foreach($ord as $ords){
             $cart_id = $ords->cart_id;
             $sub_cart_id = $ords->sub_order_cart_id;     
         $details  =   DB::table('store_orders')
                        ->join('product_varient', 'store_orders.varient_id', '=', 'product_varient.varient_id')
                        ->join('product','product_varient.product_id', '=', 'product.product_id')
                        ->select('product.product_name','product_varient.price','product_varient.mrp','product_varient.unit','product_varient.quantity','product_varient.varient_image','product_varient.description','store_orders.cancel_status','store_orders.varient_id','store_orders.store_order_id','store_orders.qty', DB::raw('SUM(store_orders.qty) as total_items'))
                       ->where('store_orders.order_cart_id',$cart_id)
                       ->where('store_orders.sub_order_cart_id',$sub_cart_id)
                       ->where('store_orders.store_approval',1)
                       ->groupBy('product.product_name','product_varient.price','product_varient.mrp','product_varient.unit','product_varient.quantity','product_varient.varient_image','product_varient.description','store_orders.varient_id','store_orders.store_order_id','store_orders.qty','store_orders.cancel_status')
                       ->get();
        $orderItemQty = $details->count();
        $orderRemainingQty = $details->where('cancel_status', '0')->count();               
        //dd($details);             
                  
        
        $data[]=array(
                        'user_address'        => $ords->house_no.','.$ords->society.','.$ords->city.','.$ords->landmark.','.$ords->state ,
                        //'sub_order_id'        => $ords->sub_order_id,
                        'order_city'          => $ords->city,
                        'order_status'        => $ords->order_status,
                        'store_name'          => $ords->store_name, 
                        'store_lat'           => $ords->store_lat, 
                        'store_lng'           => $ords->store_lng, 
                        'store_address'       => $ords->store_address, 
                        'user_lat'            => $ords->userlat, 
                        'user_lng'            => $ords->userlng, 
                        'dboy_lat'            => $ords->dboy_lat, 
                        'dboy_lng'            => $ords->dboy_lng,
                        'payment_mode'        => $ords->payment_method, 
                        'cart_id'             => $cart_id,
                        'delivery_code'       =>$ords->delivery_code,
                        'sub_cart_id'         => (string)$sub_cart_id, 
                        'user_name'           => $ords->user_name, 
                        'user_phone'          => $ords->user_phone, 
                        'total_price'         => (string)$ords->total_price ? round($ords->total_price,2) : 0,
                        'remaining_price'     => (string)$ords->rem_price ? round($ords->rem_price,2) : 0,
                        'coupon_discount'    => (string)$ords->coupon_discount ? round($ords->coupon_discount,2) : 0,
                        'bulk_order_discount' => (string)$ords->bulk_order_based_discount ? round($ords->bulk_order_based_discount,2) : 0,
                        'online_payment_discount' => (string)$ords->online_payment_based_discount ? round($ords->online_payment_based_discount,2) : 0,
                        'delivery_boy_name'   => $ords->boy_name,
                        'delivery_boy_phone'  => $ords->boy_phone,
                        'delivery_date'       => $ords->delivery_date,
                        'delivery_type_id'       => $ords->delivery_type_id,
                        'delivery_type'       => $ords->delivery_type,
                        'delivery_instruction'       => $ords->order_instruction,
                        'time_slot'           => $ords->time_slot,
                        'total_items'         => $orderRemainingQty,
                        'order_details'       => $details
                    ); 
        }
        }
        else{
            $data[]=array('order_details'=>'No Orders Found');
        }
        return $data;     
    }       
    
    
    
    public function ordersfortoday(Request $request)
    {
        $date = date('Y-m-d');
        $dboy_id = $request->dboy_id;
        //$dboy_id = '5';
                   
        $ord =DB::table('sub_orders')
                 ->join('users', 'sub_orders.user_id', '=','users.user_id')
                 ->join('store', 'sub_orders.store_id', '=', 'store.store_id')
                 ->join('address', 'sub_orders.address_id','=','address.address_id')
                 ->join('delivery_boy', 'sub_orders.dboy_id', '=','delivery_boy.dboy_id')
                 ->select('sub_orders.order_status','sub_orders.cart_id','sub_orders.sub_order_cart_id','users.user_name', 'users.user_phone', 'sub_orders.delivery_date', 'sub_orders.total_price','sub_orders.delivery_charge','sub_orders.delivery_code','sub_orders.coupon_discount','sub_orders.bulk_order_based_discount','sub_orders.online_payment_based_discount','sub_orders.rem_price','sub_orders.payment_status','sub_orders.payment_method','sub_orders.delivery_type','sub_orders.delivery_type_id','sub_orders.order_special_instructions as order_instruction','delivery_boy.boy_name','delivery_boy.boy_phone','delivery_boy.status','sub_orders.time_slot', 'store.address as store_address', 'store.store_name','store.phone_number','store.lat as store_lat','store.lng as store_lng','address.lat as userlat', 'address.lng as userlng', 'delivery_boy.lat as dboy_lat', 'delivery_boy.lng as dboy_lng', 'address.receiver_name', 'address.receiver_phone', 'address.city','address.society','address.house_no','address.landmark','address.state','address.receiver_phone')
                 ->where('sub_orders.order_status','!=', 'completed')
                 ->where('sub_orders.store_id','!=',0)
                 ->where('sub_orders.dboy_id',$dboy_id)
                 ->where('sub_orders.delivery_date', $date)
                 // ->orderBy('sub_orders.time_slot', 'ASC')
                 ->orderBy('sub_orders.sub_order_id', 'DESC')
                 ->get();

       
       if(count($ord)>0) {
            foreach($ord as $ords) {
                $cart_id = $ords->cart_id;
                $sub_cart_id = $ords->sub_order_cart_id;    
                /*$details  =   DB::table('store_orders')
                                ->join('product_varient', 'store_orders.varient_id', '=', 'product_varient.varient_id')
                                ->join('product','product_varient.product_id', '=', 'product.product_id')
                                ->select('product.product_name','product_varient.price','product_varient.mrp','product_varient.unit','product_varient.quantity','product_varient.varient_image','product_varient.description','store_orders.varient_id','store_orders.store_order_id','store_orders.qty',DB::raw('SUM(store_orders.qty) as total_items'))
                                ->groupBy('product.product_name','product_varient.price','product_varient.mrp','product_varient.unit','product_varient.quantity','product_varient.varient_image','product_varient.description','store_orders.varient_id','store_orders.store_order_id','store_orders.qty')
                                ->where('store_orders.order_cart_id',$cart_id)
                                ->where('store_orders.store_approval',1)
                                ->sum('store_orders.qty');*/
                $details  =   DB::table('store_orders')
                                ->leftJoin('product_varient', 'store_orders.varient_id','=','product_varient.varient_id')
                                ->select('store_orders.*')
                                ->where('store_orders.order_cart_id',$cart_id)
                                ->where('store_orders.sub_order_cart_id',$sub_cart_id)
                                ->where('store_orders.store_approval',1)
                                ->where('cancel_status', '0')
                                ->get();
                $orderItemQty = $details->count();                
                                                 
                //dd($orderItemQty);               
                      
            
                $data[]=array(
                                'user_address'          =>  (string)$ords->house_no.','.$ords->society.','.$ords->city.','.$ords->landmark.','.$ords->state ,
                                'order_city'            => $ords->city,
                                'order_status'          =>  (string)$ords->order_status,
                                'payment_mode'          =>  (string)$ords->payment_method,
                                'store_name'            =>  (string)$ords->store_name, 
                                'store_lat'             =>  (string)$ords->store_lat, 
                                'store_lng'             =>  (string)$ords->store_lng, 
                                'store_address'         =>  (string)$ords->store_address, 
                                'user_lat'              =>  (string)$ords->userlat, 
                                'user_lng'              =>  (string)$ords->userlng, 
                                'dboy_lat'              =>  (string)$ords->dboy_lat, 
                                'dboy_lng'              =>  (string)$ords->dboy_lng, 
                                'delivery_code'         =>  (string)$ords->delivery_code,
                                'cart_id'               =>  (string)$cart_id, 
                                'sub_cart_id'           => (string)$sub_cart_id, 
                                'total_price'           =>  (string)$ords->total_price ? round($ords->total_price,2) : '0', 
                                'user_name'             =>  (string)$ords->user_name, 
                                'user_phone'            =>  (string)$ords->receiver_phone, 
                                'remaining_price'       =>  (string)$ords->rem_price ? round($ords->rem_price,2) : '0',
                                'coupon_discount'    => (string)$ords->coupon_discount ? round($ords->coupon_discount,2) : '0',
                        'bulk_order_discount' => (string)$ords->bulk_order_based_discount ? round($ords->bulk_order_based_discount,2) : '0',
                        'online_payment_discount' => (string)$ords->online_payment_based_discount ? round($ords->online_payment_based_discount,2) : '0',
                                'delivery_boy_name'     =>  (string)$ords->boy_name,
                                'delivery_boy_phone'    =>  (string)$ords->boy_phone,
                                'delivery_boy_status'   =>  (string)$ords->status,
                                'delivery_date'         =>  (string)$ords->delivery_date,
                                'delivery_type_id'      =>  (string)$ords->delivery_type_id,
                                'delivery_type'         =>  (string)$ords->delivery_type,
                                'delivery_instruction'  =>  (string)$ords->order_instruction,
                                'time_slot'             =>  (string)$ords->time_slot,
                                'total_items'           =>  (string)$orderItemQty
                            ); 
            }
        } else {
            $data[]=array('order_details'=>'No Orders Found');
        }

        return $data;     
    }      
    
    
    
     public function ordersfornextday(Request $request)
     {
         $date = date('Y-m-d');
         $day = 1;
         $next_date = date('Y-m-d', strtotime($date.' + '.$day.' days'));
         $dboy_id = $request->dboy_id;
         //$dboy_id = '5';
                   
        $ord =DB::table('sub_orders')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->join('store', 'sub_orders.store_id', '=', 'store.store_id')
             ->join('address', 'sub_orders.address_id','=','address.address_id')
             ->join('delivery_boy', 'sub_orders.dboy_id', '=','delivery_boy.dboy_id')
             ->select('sub_orders.order_status', 'sub_orders.total_price', 'sub_orders.cart_id','sub_orders.sub_order_cart_id','sub_orders.delivery_code','users.user_name', 'users.user_phone', 'sub_orders.delivery_date', 'sub_orders.total_price','sub_orders.coupon_discount','sub_orders.bulk_order_based_discount','sub_orders.online_payment_based_discount','sub_orders.delivery_charge','sub_orders.rem_price','sub_orders.payment_status','sub_orders.payment_method','sub_orders.delivery_type','sub_orders.delivery_type_id','sub_orders.order_special_instructions as order_instruction','delivery_boy.boy_name','delivery_boy.status','delivery_boy.boy_phone','sub_orders.time_slot', 'store.address as store_address', 'store.store_name','store.phone_number','store.lat as store_lat','store.lng as store_lng','address.lat as userlat', 'address.lng as userlng', 'delivery_boy.lat as dboy_lat', 'delivery_boy.lng as dboy_lng', 'address.receiver_name', 'address.receiver_phone', 'address.city','address.society','address.house_no','address.landmark','address.state','store.phone_number','address.receiver_phone')
             ->where('sub_orders.order_status','!=', 'completed')
             ->where('sub_orders.store_id','!=',0)
             ->where('sub_orders.dboy_id',$dboy_id)
             ->whereDate('sub_orders.delivery_date', $next_date)
             //->orderBy('sub_orders.time_slot', 'ASC')
             ->orderBy('sub_orders.sub_order_id', 'DESC')
             ->get();
       
       if(count($ord)>0){
      foreach($ord as $ords){
             $cart_id = $ords->cart_id;
             $sub_cart_id = $ords->sub_order_cart_id;    
         /*$details  =   DB::table('store_orders')
                        ->join('product_varient', 'store_orders.varient_id', '=', 'product_varient.varient_id')
                        ->join('product','product_varient.product_id', '=', 'product.product_id')
                        ->select('product.product_name','product_varient.price','product_varient.mrp','product_varient.unit','product_varient.quantity','product_varient.varient_image','product_varient.description','store_orders.varient_id','store_orders.store_order_id','store_orders.qty',DB::raw('SUM(store_orders.qty) as total_items'))
                        ->groupBy('product.product_name','product_varient.price','product_varient.mrp','product_varient.unit','product_varient.quantity','product_varient.varient_image','product_varient.description','store_orders.varient_id','store_orders.store_order_id','store_orders.qty')
                       ->where('store_orders.order_cart_id',$cart_id)
                       ->where('store_orders.store_approval',1)
                       ->sum('store_orders.qty');*/
         $details  =   DB::table('store_orders')
                                ->leftJoin('product_varient', 'store_orders.varient_id','=','product_varient.varient_id')
                                ->select('store_orders.*')
                                ->where('store_orders.order_cart_id',$cart_id)
                                ->where('store_orders.sub_order_cart_id',$sub_cart_id)
                                ->where('store_orders.store_approval',1)
                                ->where('cancel_status', '0')
                                ->get();
        $orderItemQty = $details->count();              
        
        $data[] =   array(
                            'user_address'          => (string)$ords->house_no.','.$ords->society.','.$ords->city.','.$ords->landmark.','.$ords->state ,
                            'order_city'            => $ords->city,
                            'order_status'          => (string)$ords->order_status,
                            'payment_mode'          =>  (string)$ords->payment_method,
                            'store_name'            => (string)$ords->store_name,
                            'store_phone'           => (string)$ords->phone_number, 
                            'store_lat'             => (string)$ords->store_lat, 
                            'store_lng'             => (string)$ords->store_lng, 
                            'store_address'         => (string)$ords->store_address, 
                            'total_price'           => (string)$ords->total_price ? round($ords->total_price,2) : '0', 
                            'user_lat'              => (string)$ords->userlat, 
                            'user_lng'              => (string)$ords->userlng, 
                            'dboy_lat'              => (string)$ords->dboy_lat, 
                            'dboy_lng'              => (string)$ords->dboy_lng,
                            'delivery_code'         =>  (string)$ords->delivery_code, 
                            'cart_id'               => (string)$cart_id,
                            'sub_cart_id'           => (string)$sub_cart_id, 
                            'user_name'             => (string)$ords->user_name, 
                            'user_phone'            => (string)$ords->receiver_phone, 
                            'remaining_price'       => (string)$ords->rem_price ? round($ords->rem_price,2) : '0',
                            'coupon_discount'    => (string)$ords->coupon_discount ? round($ords->coupon_discount,2) : '0',
                        'bulk_order_discount' => (string)$ords->bulk_order_based_discount ? round($ords->bulk_order_based_discount,2) : '0',
                        'online_payment_discount' => (string)$ords->online_payment_based_discount ? round($ords->online_payment_based_discount,2) : '0',
                            'delivery_boy_name'     => (string)$ords->boy_name,
                            'delivery_boy_phone'    => (string)$ords->boy_phone,
                            'delivery_boy_status'   => (string)$ords->status,
                            'delivery_date'         => (string)$ords->delivery_date,
                            'time_slot'             => (string)$ords->time_slot,
                            'delivery_type_id'      => (string)$ords->delivery_type_id,
                            'delivery_type'         => (string)$ords->delivery_type,
                            'delivery_instruction'  => (string)$ords->order_instruction,
                            'total_items'           => (string)$orderItemQty
                    ); 
        }
        }
        else{
            $data[]=array('order_details'=>'No Orders Found');
        }
        return $data;     
    }      

            
    public function delivery_out(Request $request)
    {
       $cart_id     = $request->cart_id;
       $sub_cart_id = $request->sub_cart_id;
       
        // $cart_id= 'IWAP8576';
        // $sub_cart_id= 'IWAP8576-STORE-6';

        $chars2 = "0123456789";
        $deiveryCode = "";
        # random number get for make cart id. there are 2 digit numbers.
        for ($i = 0; $i < 4; $i++){
          $deiveryCode .= $chars2[mt_rand(0, strlen($chars2)-1)];
        }

       //dd($deiveryCode); 
       
       
       $ord = DB::table('orders')
            ->where('cart_id',$cart_id)
            ->first();    

        $sub_ord = DB::table('sub_orders')
            ->where('cart_id',$cart_id)
            ->where('sub_order_cart_id',$sub_cart_id)
            ->first();

        $sub_ord_total = DB::table('sub_orders')
            ->where('cart_id',$cart_id)
            ->get();    

        // $countForOutOfOrder= $sub_ord_total
        //                      ->where('order_status','!=', 'Out_For_Delivery')
        //                      ->where('order_status','!=', 'Completed')
        //                      ->where('order_status','!=', 'Completed')
        //                      ->count();
        // dd($countForOutOfOrder);    


        $user_id = $sub_ord->user_id;    
        $var= DB::table('store_orders')
               ->where('order_cart_id', $cart_id)
               ->where('cancel_status','=', 0)
               ->where('sub_order_cart_id', $sub_cart_id)
               ->get();

        $price2=0;
        $ph = DB::table('users')
                  ->select('user_phone','user_name','wallet')
                  ->where('user_id', $sub_ord->user_id)
                  ->first();
        
        $deliveryBoy = DB::table('delivery_boy')
                  ->select('*')
                  ->where('dboy_id', $sub_ord->dboy_id)
                  ->first();
        $storedetail = DB::table('store')
                  ->select('*')
                  ->where('store_id', $sub_ord->store_id)
                  ->first();          
        $deliveryBoyName = $deliveryBoy->boy_name;
        $deliveryBoyMobile = $deliveryBoy->boy_phone;
        //dd($deliveryBoyName);                    

        $user_phone = $ph->user_phone;
        $user_name = $ph->user_name;   
        foreach ($var as $h) {
            $varient_id = $h->varient_id;
            $p = DB::table('product_varient')
                    ->join('product','product_varient.product_id','=','product.product_id')
                    ->where('product_varient.varient_id', $varient_id)
                    ->first();

            $price      =   round($p->price);   
            $order_qty  =   $h->qty;
            $price2     +=  $price*$order_qty;
            $unit[]     =   $p->unit;
            $qty[]      =   $p->quantity;
            $p_name[]   =   $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
            $prod_name  =   implode(',',$p_name);
        }

        $currency   = DB::table('currency')
                        ->first();

        $apppp = DB::table('tbl_web_setting')
                  ->first();
        //dd( $sub_ord->dboy_id);          

        $update =   DB::table('delivery_boy')
                        ->where('dboy_id', $sub_ord->dboy_id)
                        ->update([
                                    'is_engaged' => '1'
                                ]);          

       $status = 'Out_For_Delivery';
       //dd('dsfd');

       // $update  = DB::table('orders')
       //                ->where('cart_id',$cart_id)
       //                ->update(['order_status' => $status]);


       $update  = DB::table('sub_orders')
                      ->where('cart_id',$cart_id)
                      ->where('sub_order_cart_id', $sub_cart_id)
                      ->update([
                        'order_status' => $status,
                        'delivery_code'=>$deiveryCode
                      ]);
                  
        if($update) {
               
            $sms = DB::table('notificationby')
                       ->select('sms','app')
                       //->where('user_id',$sub_ord->user_id)
                       ->first();

            $sms_status   = $sms->sms;
            $sms_api_key  =  DB::table('msg91')
                                  ->select('api_key', 'sender_id')
                                  ->first();

            $api_key    = $sms_api_key->api_key;
            $sender_id  = $sms_api_key->sender_id;
            if($sms_status == 1) {
                //$successmsg = $this->delout($cart_id, $prod_name, $price2, $currency, $ord, $user_phone);
                
                $cartFull = $sub_cart_id."(".$cart_id.")";
                $deliveryBoyDetail = $deliveryBoy->boy_name."(Mobile No. : ".$deliveryBoy->boy_phone.")";

                $getInvitationMsg = urlencode("Dealwy,Dear ".$user_name.", delivery Boy ".$deliveryBoyDetail." is out of delivery for your address to deliver order from ".$storedetail->store_name." with AWB no: ".$sub_cart_id. ". Your delivery confirmation code is ".$deiveryCode.".");
                //dd($getInvitationMsg);
                $apiUrl = 'http://www.onex-ultimo.in/api/pushsms?user=NFDML&authkey=92A7YFf76gJU&sender=Dealwy&mobile='.$user_phone.'&text='.$getInvitationMsg.'&rpt=1&summary=1&output=json&entityid=1201160517117234073&templateid=1207163298101952769';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                curl_close($ch);
            }
                
            //////send app notification////
            if($sms->app == 1) {

                if($sub_ord->payment_method == "COD" || $sub_ord->payment_method == "cod") {
                    $notification_title = "Out For Delivery";
                    $notification_text  = "Out For Delivery: Your sub order id #".$sub_cart_id."(".$cart_id.") contains of " .$prod_name." of price ".$currency->currency_sign." ".$price2. " is Out For Delivery by delivery boy ".$deliveryBoy->boy_name."(Mobile No. : ".$deliveryBoy->boy_phone."). Get ready with ".$currency->currency_sign." ".round($sub_ord->rem_price). " cash. Please use Delivery Code ".$deiveryCode." to verify your order.";

                    $notification_text_store  = "Out For Delivery: Sub order id #".$sub_cart_id."(".$cart_id.") contains of " .$prod_name." of price ".$currency->currency_sign." ".$price2. " is Out For Delivery by delivery boy ".$deliveryBoy->boy_name."(Mobile No. : ".$deliveryBoy->boy_phone."). Get amount ".$currency->currency_sign." ".round($sub_ord->rem_price). " in cash.";
                    
                    $date = date('d-m-Y');
           
                    $this->sendStoreNotification($notification_title, $notification_text_store, $sub_ord->store_id);
            
                    $getDevice = DB::table('users')
                             ->where('user_id', $user_id)
                            ->select('device_id')
                            ->first();
                    $created_at = Carbon::now();
    
                    if($getDevice) {
                    
                        $getFcm = DB::table('fcm')
                                    ->where('id', '1')
                                    ->first();
                                    
                        $getFcmKey  = $getFcm->server_key;
                        $fcmUrl     = 'https://fcm.googleapis.com/fcm/send';
                        $token      = $getDevice->device_id;
                        
            
                        $notification = [
                            'title' => $notification_title,
                            'body'  => $notification_text,
                            'sound' => true,
                        ];
                        
                        $extraNotificationData = ["message" => $notification];
            
                        $fcmNotification = [
                            'to'            => $token,
                            'notification'  => $notification,
                            'data'          => $extraNotificationData,
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
                        
                        $dd = DB::table('user_notification')
                            ->insert([
                                    'user_id'       =>  $user_id,
                                    'noti_title'    =>  $notification_title,
                                    'noti_message'  =>  $notification_text
                                ]);
                            
                        $results = json_decode($result);
                    }  
                } else {
                    
                    $notification_title = "Out For Delivery";
                    $notification_text = "Out For Delivery: Your sub order id #".$sub_cart_id."(".$cart_id.") contains of " .$prod_name." of price " .$currency->currency_sign." ".$price2. " is Out For Delivery by delivery boy ".$deliveryBoy->boy_name."(Mobile No. : ".$deliveryBoy->boy_phone."). Get ready. Please use Delivery Code ".$deiveryCode." to verify your order.";

                    $notification_text_store = "Out For Delivery: Your sub order id #".$sub_cart_id."(".$cart_id.") contains of " .$prod_name." of price " .$currency->currency_sign." ".$price2. " is Out For Delivery by delivery boy ".$deliveryBoy->boy_name."(Mobile No. : ".$deliveryBoy->boy_phone.").";
                    $date = date('d-m-Y');

                    $this->sendStoreNotification($notification_title, $notification_text_store, $sub_ord->store_id);
                    $getDevice = DB::table('users')
                                     ->where('user_id', $user_id)
                                    ->select('device_id')
                                    ->first();

                    $created_at = Carbon::now();
                    if($getDevice) {

                        $getFcm = DB::table('fcm')
                                    ->where('id', '1')
                                    ->first();
                                    
                        $getFcmKey  = $getFcm->server_key;
                        $fcmUrl     = 'https://fcm.googleapis.com/fcm/send';
                        $token      = $getDevice->device_id;
                        $notification = [
                            'title' => $notification_title,
                            'body'  => $notification_text,
                            'sound' => true,
                        ];
                        $extraNotificationData = ["message" => $notification];
                        $fcmNotification = [
                            'to'            => $token,
                            'notification'  => $notification,
                            'data'          => $extraNotificationData,
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

                        $dd = DB::table('user_notification')
                                    ->insert([
                                                'user_id'      => $user_id,
                                                'noti_title'   => $notification_title,
                                                'noti_message' => $notification_text
                                            ]);
                            
                        $results = json_decode($result);
                    }
                }
            }
            
            /////send mail
            $email = DB::table('notificationby')
                           ->select('email')
                           //->where('user_id',$sub_ord->user_id)
                           ->first();

            $email_status   = $email->email; 
            $rem_price      = $sub_ord->rem_price;
            if($email_status == 1) {
                if($sub_ord->payment_method == "COD" || $sub_ord->payment_method == "cod"){
                    $q = DB::table('users')
                              ->select('user_email','user_name')
                              ->where('user_id',$sub_ord->user_id)
                              ->first();

                    $user_email = $q->user_email;   
                    $user_name  = $q->user_name;
                    if($user_email){
                        $successmail = $this->coddeloutMail($cart_id, $prod_name, $price2,$user_email, $user_name,$rem_price,$sub_cart_id,$deliveryBoyName,$deliveryBoyMobile,$deiveryCode);
                    }
                } else {

                    $q = DB::table('users')
                              ->select('user_email','user_name')
                              ->where('user_id',$sub_ord->user_id)
                              ->first();

                    $user_email = $q->user_email;   
                    $user_name  = $q->user_name;
                    if($user_email){
                        $successmail = $this->deloutMail($cart_id, $prod_name, $price2,$user_email, $user_name,$rem_price,$sub_cart_id,$deliveryBoyName,$deliveryBoyMobile,$deiveryCode);
                    }
                }
            }

            $message = array('status' => '1', 'message' => 'Out For Delivery');
            return $message;
        } else {

            $message = array('status' => '0', 'message' => 'Already Out For Delivery');
            return $message;
        }       
              
    }

     //Send to user notification
    public function sendUserNotification($notification_title, $notification_text, $user_id)
    {

        $date     = date('d-m-Y');

        $getDevice = DB::table('users')
                             ->where('user_id', $user_id)
                            ->select('device_id')
                            ->first();              
        
        $created_at = Carbon::now();

        if($getDevice) {


          $getFcm = DB::table('fcm')
                      ->where('id', '1')
                      ->first();

          $getFcmKey  = $getFcm->server_key;
          $fcmUrl     = 'https://fcm.googleapis.com/fcm/send';
          $token      = $getDevice->device_id;


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
            $dd = DB::table('user_notification')
            ->insert([
                'user_id'      => $user_id,
                'noti_title'   => $notification_title,
                'noti_message' => $notification_text
            ]);
        }
    }
    
    public function sendStoreNotification($notification_title, $notification_text, $store_id)
    {

        $date     = date('d-m-Y');
        $getUser  = DB::table('store')
                        ->get();

        $getDevice = DB::table('store')
                       ->where('store_id', $store_id)
                      ->select('device_id')
                      ->first();
        
        $created_at = Carbon::now();

        if($getDevice) {

          $getFcm = DB::table('fcm')
                      ->where('id', '1')
                      ->first();

          $getFcmKey  = $getFcm->store_server_key;
          $fcmUrl     = 'https://fcm.googleapis.com/fcm/send';
          $token      = $getDevice->device_id;


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
                              'store_id'    =>  $store_id,
                              'not_title'   =>  $notification_title,
                              'not_message' =>  $notification_text
                            ]);

          
        }
    }
    
    public function delivery_completed(Request $request)
    {
		//die('fsfsd');
        $cart_id= $request->cart_id;
        $dboy_id = $request->dboy_id;
        $sub_cart_id= $request->sub_cart_id;
        $delivery_code= $request->delivery_code; 

        // $dboy_id = 6;
        // $cart_id= 'IWAP8576';
        // $sub_cart_id= 'IWAP8576-STORE-6';
        // $delivery_code= '2402';
        // dd($delivery_code);  

        $currency = DB::table('currency')
                    ->first(); 
       try {    

       //DB::beginTransaction();             
        $ord = DB::table('sub_orders')
            ->where('cart_id',$cart_id)
            ->where('sub_order_cart_id',$sub_cart_id)
            ->first();
            
            
            
        // if($ord->order_status === 'Completed'){
        //   $message = array('status'=>'0', 'message'=>'Test Order already Completed!');
        //   return $message; 
        // }    
        
        // $message = array('status'=>'0', 'message'=>$ord->order_status.' '.'Completed');
        //   return $message; 
                
        if($ord->delivery_code != $delivery_code){
          $message = array('status'=>'0', 'message'=>'Invalid Delivery Code!');
          return $message;
        }
        
          
        //}else{
      if($ord->order_status != 'Completed'){
            // $deliveryBoyupdate =   DB::table('delivery_boy')
            //             ->where('dboy_id', $dboy_id)
            //             ->update(['is_engaged' => '0']);  

            $user_id = $ord->user_id;

            $var= DB::table('store_orders')
              ->where('order_cart_id', $cart_id)
              ->where('cancel_status', 0)
              ->where('sub_order_cart_id', $sub_cart_id)
              ->get();

            $price2=0;
            foreach ($var as $h){
                $varient_id = $h->varient_id;
                $p = DB::table('product_varient')
                ->join('product','product_varient.product_id','=','product.product_id')
                ->where('product_varient.varient_id',$varient_id)
                ->first();
                $price = round($p->price);   
                $order_qty = $h->qty;
                $price2+= $price*$order_qty;
                $unit[] = $p->unit;
                $qty[]= $p->quantity;
                $p_name[] = $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
                $prod_name = implode(',',$p_name);
            }
            // $apppp = DB::table('tbl_web_setting')
            // ->first();  
            $status = 'Completed';    

            $update= DB::table('sub_orders')
              ->where('cart_id',$cart_id)
              ->where('sub_order_cart_id', $sub_cart_id)
              ->update(['order_status'=>$status]);

            //,'user_signature'=>$user_signature 
            //dd($update);  
                

            if($update){
                $ord_main_count = DB::table('sub_orders')
                  ->where('cart_id',$cart_id)
                  ->where('order_status', '!=', 'Completed')
                  ->where('order_status', '!=', 'completed')
                  ->where('order_status', '!=','cancelled')
                  ->where('order_status','!=', 'Cancelled')
                  ->get()->count();

                if($ord_main_count<=0){
                    $order_update = DB::table('orders')
                     ->where('cart_id',$cart_id)
                     ->update(['order_status'=>$status]);    
                }
              //DB::commit();
                $sms = DB::table('notificationby')
                       ->select('sms','app')
                       //->where('user_id',$ord->user_id)
                       ->first();

                $sms_status = $sms->sms;

                $sms_api_key=  DB::table('msg91')
                      ->select('api_key', 'sender_id')
                      ->first();      
                $api_key = $sms_api_key->api_key;
                $sender_id = $sms_api_key->sender_id;

                ////send notification to app///
                if($sms->app == 1){
                    $notification_title = "Order Delivered";
                    $notification_text = "Delivery Completed: Your Sub order id #".$sub_cart_id."(#".$cart_id.") contains of " .$prod_name." of price ".$currency->currency_sign." ".$price2." is Delivered Successfully.";
                    //dd($notification_text.' @ '.$ord->store_id.' @ '.$user_id);
                    $date = date('d-m-Y');

                    $this->sendStoreNotification($notification_title, $notification_text, $ord->store_id);
                    $this->sendUserNotification($notification_title, $notification_text, $user_id);


                }

                /////send mail
                $email = DB::table('notificationby')
                         ->select('email')
                         //->where('user_id',$ord->user_id)
                         ->first();
                $email_status = $email->email;       
                if($email_status == 1){
                    $q = DB::table('users')
                              ->select('user_email','user_name','user_phone')
                              ->where('user_id',$ord->user_id)
                              ->first();
                    $user_email = $q->user_email;             
                    $user_name =$q->user_name;
                    $user_phone =$q->user_phone;

                    $cartFull = $sub_cart_id."(".$cart_id.")";
                    $linkUrl = url("/")."/contact";

                    $getInvitationMsg = urlencode("Dear ".$user_name.", Your Dealwy order ".$cartFull." is delivered. Kindly share your valuable feedback by clicking on the given link ".$linkUrl.".");
                    $apiUrl = 'http://www.onex-ultimo.in/api/pushsms?user=NFDML&authkey=92A7YFf76gJU&sender=Dealwy&mobile='.$user_phone.'&text='.$getInvitationMsg.'&rpt=1&summary=1&output=json&entityid=1201160517117234073&templateid=1207163298094303731';

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $response = curl_exec($ch);
                    curl_close($ch);

                   // $successmail = $this->delcomMail($cart_id, $prod_name, $price2,$user_email,$user_name,$sub_cart_id); 
                }
                $message = array('status'=>'1', 'message'=>'Delivery Completed');
                return $message;
            }else{
                $message = array('status'=>'0', 'message'=>'Something Went Wrong');
                return $message;
            }
            
      }else{
        //   $ord = DB::table('sub_orders')
        //     ->where('cart_id',$cart_id)
        //     ->where('sub_order_cart_id',$sub_cart_id)
        //     ->first();
        //   $order_update = DB::table('sub_orders')
        //              ->where('cart_id',$cart_id)
        //              ->where('sub_order_cart_id',$sub_cart_id)
        //              ->update(['order_status'=>'Completed1','coupon_discount'=>$ord->coupon_discount+1]);
          $message = array('status'=>'0', 'message'=>'Order already Completed!');
           return $message;
      }        

         //}

       } catch (\Exception $e) {
            //dd($e->getMessage());

           return $e->getMessage();
       }  
  
              
    }

    /**
     * update engaged driver status.
     * 
     * @param Request $request || HTTP||Response.
     */
    public function driverEngagedStatus(Request $request)
    {
        $dboy_id = $request->dboy_id;

        try {
            
            $update =   DB::table('delivery_boy')
                            ->where('dboy_id', $dboy_id)
                            ->update([
                                        'is_engaged' => '1'
                                    ]);

            $message = array('status' => '1', 'message' => 'Driver Engaged Status Has Updated');
            return $message;

        } catch (\Exception $e) {
         
            $message = array('status' => '0', 'message' => 'Driver Engaged Status Has Not Updated');
            return $message;
        }
    }



    /*Not delivered reason api*/

    public function notDeliveredReason(Request $request)
    {
        # gather the form data.
        $cart_id= $request->cart_id;
        $dboy_id = $request->dboy_id;
        $sub_cart_id= $request->sub_cart_id;
        $reason= $request->reason;
        //$reason_image

        // $dboy_id = 5;
        // $cart_id= 'TQPS5357';
        // $sub_cart_id= 'TQPS5357-STORE-3';
        // $reason= 'Store not opened';

        //dd($reason);
        $currency = DB::table('currency')
                    ->first();
         
                    
        $ord = DB::table('sub_orders')
            ->where('cart_id',$cart_id)
            ->where('sub_order_cart_id',$sub_cart_id)
            ->first();
        $storeId= $ord->store_id;    
        $user_id = $ord->user_id;

        if($request->reason_image){
            $reason_image = $request->reason_image;
            $reason_image = str_replace('data:image/png;base64,', '', $reason_image);
            $fileName = date('dmyHis').'reason_image'.'.'.'png';
            $fileName = str_replace(" ", "-", $fileName);
            $pth = str_replace("/source/public", "",base_path());

            \File::put($pth. '/images/order_not_delivered_images/' . $fileName, base64_decode($reason_image));
            $reason_image ='/images/order_not_delivered_images/'.$fileName;
        }
        else{
            $reason_image = "N/A";
        }

         $var= DB::table('store_orders')
           ->where('order_cart_id', $cart_id)
           ->where('cancel_status', 0)
           ->where('sub_order_cart_id', $sub_cart_id)
           ->get();
         //dd($var);  
        $price2=0;
           
        foreach ($var as $h){
            $varient_id = $h->varient_id;
            $p = DB::table('product_varient')
                ->join('product','product_varient.product_id','=','product.product_id')
                ->where('product_varient.varient_id',$varient_id)
                ->first();
            $price = round($p->price);   
            $order_qty = $h->qty;
            $price2+= $price*$order_qty;
            $unit[] = $p->unit;
            $qty[]= $p->quantity;
            $p_name[] = $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
            $prod_name = implode(',',$p_name);
        }

        $ph = DB::table('users')
                  ->select('user_phone','user_name')
                  ->where('user_id',$user_id)
                  ->first();
        
        $user_phone = $ph->user_phone;   


        $insert = DB::table('not_delivered_reason')
                            ->insertGetId([
                              'not_del_cart_id'=>$cart_id,
                              'not_del_sub_cart_id'=>$sub_cart_id,
                              'not_del_dboy_id' =>$dboy_id,
                              'not_del_store_id'=>$storeId,
                              'not_del_reason'=>$reason,
                              'not_del_image'=>$reason_image
                            ]);            
              
        if($insert){
                   
            $sms = DB::table('notificationby')
                       ->select('sms','app')
                       //->where('user_id',$ord->user_id)
                       ->first();
            $sms_status = $sms->sms;
            $sms_api_key=  DB::table('msg91')
                      ->select('api_key', 'sender_id')
                      ->first();
            $api_key = $sms_api_key->api_key;
            $sender_id = $sms_api_key->sender_id;
                /*if($sms_status == 1){
                    $successmsg = $this->delcomsms($cart_id, $prod_name, $price2,$currency,$user_phone); 
                   
                }*/
                ////send notification to app///
                if($sms->app == 1){
                $notification_title = "Order Not Delivered Reason";
                $notification_text = "Delivery Not Delivered: Your Sub order id #".$sub_cart_id."(#".$cart_id.") contains of " .$prod_name." of price ".$currency->currency_sign." ".$price2." is not Delivered due to ".$reason.".";
                
                $date = date('d-m-Y');
        
                $this->sendStoreNotification($notification_title, $notification_text, $ord->store_id);
        
                $getDevice = DB::table('users')
                         ->where('user_id', $user_id)
                        ->select('device_id')
                        ->first();
                $created_at = Carbon::now();
        
                if($getDevice){
                
                
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
                    
             
                $dd = DB::table('user_notification')
                    ->insert(['user_id'=>$user_id,
                     'noti_title'=>$notification_title,
                     'noti_message'=>$notification_text]);
                    
                $results = json_decode($result);
               }
            }   
            /////send mail
            $email = DB::table('notificationby')
                   ->select('email')
                   //->where('user_id',$ord->user_id)
                   ->first();
            $email_status = $email->email;       
            if($email_status == 1){
                    $q = DB::table('users')
                              ->select('user_email','user_name')
                              ->where('user_id',$ord->user_id)
                              ->first();
                    $user_email = $q->user_email;             
                    $user_name =$q->user_name;
                    $successmail = $this->notdelreaMail($cart_id, $prod_name, $price2,$user_email,$user_name,$sub_cart_id, $reason); 
               }
           $message = array('status'=>'1', 'message'=>'Not delivered reason submit Successfully');
            return $message;
        }          
            else{
             $message = array('status'=>'0', 'message'=>'Something Went Wrong');
            return $message;
       }       
              
    }
}