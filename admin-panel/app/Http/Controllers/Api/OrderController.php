<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\Traits\SendMail;
use App\Traits\SendSms;

class OrderController extends Controller
{
    use SendMail;
    use SendSms;
    public function order(Request $request)
    {
        $current          =  Carbon::now();
        $data             =  $request->order_array;
        $data_array       =  json_decode($data);
        $user_id          =  $request->user_id;
        $delivery_date    =  $request->delivery_date;
        $delivery_charge  =  $request->delivery_charge;
        $time_slot        =  $request->time_slot;
        $chars            =  "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $val              =  "";

        # random character get for make cart id.
        for ($i = 0; $i < 4; $i++) {
          $val .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        $chars2 = "0123456789";
        $val2 = "";
        # random number get for make cart id. there are 2 digit numbers.
        for ($i = 0; $i < 2; $i++){
          $val2 .= $chars2[mt_rand(0, strlen($chars2)-1)];
        }

        # get microtime.
        $cr      = substr(md5(microtime()),rand(0, 26),2);

        # make cart id with character, number and time strings.
        $cart_id = $val.$val2.$cr;

        # get user address.
        $ar      = DB::table('address')
                      ->select('society','city','lat','lng','address_id')
                      ->where('user_id', $user_id)
                      ->where('select_status', 1)
                      ->first();

        if(!$ar) {
          $message = array('status'=>'0', 'message'=>'Select any Address');
        	return $message;
        }

        $created_at = Carbon::now();
        $user_id    = $request->user_id;
        $price2     = 0;
        $price5     = 0;
        $ph         = DB::table('users')
                        ->select('user_phone','wallet')
                        ->where('user_id',$user_id)
                        ->first();

        $user_phone = $ph->user_phone;
        $sel_store = DB::table('store')
                        ->leftjoin('store_products','store.store_id', '=', 'store_products.store_id')
                        ->select("store.store_name","store.store_id","store_products.stock"
                          ,DB::raw("6371 * acos(cos(radians(".$ar->lat . "))
                          * cos(radians(store.lat))
                          * cos(radians(store.lng) - radians(" . $ar->lng . "))
                          + sin(radians(" .$ar->lat. "))
                          * sin(radians(store.lat))) AS distance"))
                        ->where('store.city',$ar->city)
                        ->where('store.del_range','>=','distance')
                        ->orderBy('distance')
                        ->first();

        if(!$sel_store) {

          $message = array('status' => '0', 'message' => 'No Stores Near Your Selected Address');
        	return $message;

        } else {

          $store_id =$sel_store->store_id;

          foreach ($data_array as $h) {

              $varient_id = $h->varient_id;

              $p  =  DB::table('product_varient')
                        ->join('product','product_varient.product_id','=','product.product_id')
                        ->Leftjoin('deal_product','product_varient.varient_id','=','deal_product.varient_id')
                        ->where('product_varient.varient_id',$varient_id)
                        ->first();

              if($p->deal_price != NULL && $p->valid_from < $current && $p->valid_to > $current) {
                $price = $p->deal_price;
              } else {
                $price = $p->price;
              }

              $mrpprice  =  $p->mrp;
              $order_qty =  $h->qty;
              $price2    += $price*$order_qty;
              $price5    += $mrpprice*$order_qty;
              $unit[]    =  $p->unit;
              $qty[]     =  $p->quantity;
              $p_name[]  =  $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
              $prod_name =  implode(',',$p_name);
          }

          foreach ($data_array as $h)
          {
              $varient_id = $h->varient_id;
              $p = DB::table('product_varient')
                      ->join('product','product_varient.product_id','=','product.product_id')
                      ->Leftjoin('deal_product','product_varient.varient_id','=','deal_product.varient_id')
                      ->where('product_varient.varient_id',$varient_id)
                      ->first();

              if($p->deal_price != NULL &&  $p->valid_from < $current && $p->valid_to > $current){
                $price = $p->deal_price;
              }else{
                $price = $p->price;
              }

              $mrp        = $p->mrp;
              $order_qty  = $h->qty;
              $price1     = $price*$order_qty;
              $total_mrp  = $mrp*$order_qty;
              $order_qty  = $h->qty;
              $p          = DB::table('product_varient')
                               ->join('product','product_varient.product_id','=','product.product_id')
                               ->where('product_varient.varient_id',$varient_id)
                               ->first();
              $n          = $p->product_name;

              $insert = DB::table('store_orders')
                            ->insertGetId([
                              'varient_id'     => $varient_id,
                              'qty'            => $order_qty,
                              'product_name'   => $n,
                              'varient_image'  => $p->varient_image,
                              'quantity'       => $p->quantity,
                              'unit'           => $p->unit,
                              'total_mrp'      => $total_mrp,
                              'order_cart_id'  => $cart_id,
                              'order_date'     => $created_at,
                              'price'          => $price1
                            ]);

          }

          
          $delcharge = DB::table('freedeliverycart')
                         ->where('id', 1)
                         ->first();
          $charge = $delivery_charge;
          // if ($delcharge->min_cart_value <= $price2){
          //     $charge=0;
          // } else {
          //     $charge = $delcharge->del_charge;
          // }

          if($insert) {

            $oo = DB::table('orders')
                      ->insertGetId([
                      'cart_id'                 => $cart_id,
                      'total_price'             => $price2 + $charge,
                      'price_without_delivery'  => $price2,
                      'total_products_mrp'      => $price5,
                      'delivery_charge'         => $charge,
                      'user_id'                 => $user_id,
                      'store_id'                => $store_id,
                      'rem_price'               => $price2 + $charge,
                      'order_date'              => $created_at,
                      'delivery_date'           => $delivery_date,
                      'time_slot'               => $time_slot,
                      'address_id'              => $ar->address_id
                    ]);

            $ordersuccessed = DB::table('orders')
                                 ->where('order_id', $oo)
                                 ->first();

          	$message = array('status' => '1', 'message' => 'Proceed to payment', 'data' => $ordersuccessed );
          	return $message;
          } else {
          	$message = array('status' => '0', 'message' => 'insertion failed', 'data' => []);
          	return $message;
          }

        }
    }

    public function checkout(Request $request)
    { 

        $cart_id        = $request->cart_id;
        $payment_method = $request->payment_method;
        $payment_status = $request->payment_status;
        $wallet         = $request->wallet;
        $orderr = DB::table('orders')
                    ->where('cart_id', $cart_id)
                    ->first();
          //dd($orderr);
                   
        $store_id       = $orderr->store_id;
        $user_id        = $orderr->user_id;
        $delivery_date  = $orderr->delivery_date;
        $time_slot      = $orderr->time_slot;

        $var= DB::table('store_orders')
                   ->where('order_cart_id', $cart_id)
                   ->get();
                   
        $price2 = round($orderr->rem_price ?? 0);
        $ph = DB::table('users')
                  ->select('user_phone','wallet')
                  ->where('user_id',$user_id)
                  ->first();
                  
        $user_phone = $ph->user_phone;
        foreach ($var as $h) {
            $varient_id = $h->varient_id;
            $p = DB::table('store_orders')
                   ->where('order_cart_id',$cart_id)
                   ->where('varient_id',$varient_id)
                   ->first();
               
            $price      = $p->price;
            $order_qty  = $h->qty;
            $unit[]     = $p->unit;
            $qty[]      = $p->quantity;
            $p_name[]   = $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
            $prod_name  = implode(',',$p_name);
        }
      
        $charge = 0;
        $prii = $price2;
        if ($payment_method == 'COD' || $payment_method =='cod') {
             $walletamt = 0;

             $payment_status="COD";
            if($wallet == 'yes' || $wallet == 'Yes' || $wallet == 'YES') {
                if($ph->wallet >= $prii) {
                    $rem_amount = 0;
                    $walletamt = $prii;
                    $rem_wallet = $ph->wallet-$prii;
                    $walupdate = DB::table('users')
                               ->where('user_id',$user_id)
                               ->update(['wallet'=>$rem_wallet]);
                    $payment_status="success";
                    $payment_method = "wallet";
                } else {
    
                    $rem_amount= $prii - $ph->wallet;
                    $walletamt = $ph->wallet;
                    $rem_wallet = 0;
                    $walupdate = DB::table('users')
                               ->where('user_id',$user_id)
                               ->update(['wallet'=>$rem_wallet]);
                }
            } else{
                $rem_amount =  $prii;
                $walletamt  = 0;
            }

            $oo = DB::table('orders')
                       ->where('cart_id',$cart_id)
                        ->update([
                        'paid_by_wallet'=>$walletamt,
                        'rem_price'=>$rem_amount,
                        'payment_status'=>$payment_status,
                        'payment_method'=>$payment_method
                        ]);

            $sms = DB::table('notificationby')
                       ->select('sms')
                       //->where('user_id',$user_id) //commented by me 19 jan
                       ->first();
                       
            $sms_status = $sms->sms;

            //Comment for sms block 14-06-21 by Anish
                //if($sms_status == 1){
            if($sms_status == 2){
                $orderplacedmsg = $this->ordersuccessfull($cart_id,$prod_name,$price2,$delivery_date,$time_slot,$user_phone);
                
                #new added code to send sms through bulk msg beg  19jan 2021
                $getInvitationMsg = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully.You can expect your item(s) will be delivered on ".$delivery_date." between ".$time_slot.".";
                 
                $orderplacedmsg = $this->sendordermsg($getInvitationMsg,$user_phone);
                #new added code to send sms through bulk msg end
            }
            
            /////send mail
            $email = DB::table('notificationby')
                   ->select('sms','email','app')
                   //->where('user_id',$user_id) //commented by me 19 jan
                   ->first();
                   
             $q = DB::table('users')
                              ->select('user_email','user_name')
                              ->where('user_id',$user_id)
                              ->first();
                              
            $user_email = $q->user_email;
            //$user_email = 'azwar.salal92@gmail.com';
            //dd($user_email);
            //dd($user_phone);
            //$user_phone=9971665388;
            //dd($user_phone);

            $user_name = $q->user_name;
            $email_status = $email->email;
            if($email_status == 1){
               $codorderplaced = $this->codorderplacedMail($cart_id,$prod_name,$price2,$delivery_date,$time_slot,$user_email,$user_name);
                
                #new added code to send sms through bulk msg end
            }

            //Comment for sms block 14-06-21 By anish
            //if($email->sms == 1){
            // if($email->sms == 2){
            //     $orderplacedmsg = $this->ordersuccessfull($cart_id,$prod_name,$price2,$delivery_date,$time_slot,$user_phone);
            //     #new added code to send sms through bulk msg beg  19jan 2021
            //     $getInvitationMsg = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully.You can expect your item(s) will be delivered on ".$delivery_date." between ".$time_slot.".";

            //      $orderplacedmsg=$this->sendordermsg($getInvitationMsg,$user_phone);
            //     #new added code to send sms through bulk msg end
            // }
               
            if($email->app ==1) {
                
                $notification_title = "WooHoo! Your Order is Placed";
                $notification_text = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully.You can expect your item(s) will be delivered on ".$delivery_date." between ".$time_slot.".";

                $date = date('d-m-Y');


                $getDevice = DB::table('users')
                                 ->where('user_id', $user_id)
                                ->select('device_id')
                                ->first();
                        
                $created_at = Carbon::now();

                if($getDevice) {

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
          
            $orderr1 = DB::table('orders')
                   ->where('cart_id', $cart_id)
                   ->first();

            ///////send notification to store//////

            $notification_title = "WooHoo ! You Got a New Order";
            $notification_text = "you got an order cart id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " . It will have to delivered on ".$delivery_date." between ".$time_slot.".";

            $date = date('d-m-Y');
            $getUser = DB::table('store')
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
                    ->insert(['store_id'=>$store_id,
                     'not_title'=>$notification_title,
                     'not_message'=>$notification_text]);
    
                $results = json_decode($result);
            }

            ////rewards earned////
            $checkre =DB::table('reward_points')
                        ->where('min_cart_value','<=',$price2)
                        ->orderBy('min_cart_value','desc')
                        ->first();
                        
            if($checkre) {
            $reward_point = $checkre->reward_point;

            $inreward = DB::table('users')
                         ->where('user_id',$user_id)
                         ->update(['rewards'=>$reward_point]);

            $cartreward = DB::table('cart_rewards')
                            ->insert(['cart_id'=>$cart_id, 'rewards'=>$reward_point, 'user_id'=>$user_id]);
        }

            $message = array('status'=>'1', 'message'=>'Order Placed successfully', 'data'=>$orderr1 );
        	return $message;
        } else {
            $walletamt = 0;
            $prii = $price2 + $charge;
            if($request->wallet == 'yes' || $request->wallet == 'Yes' || $request->wallet == 'YES'){
                if($ph->wallet >= $prii) {
                    $rem_amount = 0;
                    $walletamt  = $prii;
                    $rem_wallet = $ph->wallet - $prii;
                    $walupdate  = DB::table('users')
                                   ->where('user_id',$user_id)
                                   ->update(['wallet'=>$rem_wallet]);
                    $payment_status="success";
                    $payment_method = "wallet";
                } else {
    
                    $rem_amount=  $prii-$ph->wallet;
                    $walletamt = $ph->wallet;
                    $rem_wallet =0;
                    $walupdate = DB::table('users')
                               ->where('user_id',$user_id)
                               ->update(['wallet'=>$rem_wallet]);
                }
            } else {
                $rem_amount=  $prii;
                $walletamt = 0;
            }
            
            if($payment_status=='success'){
                
                $oo = DB::table('orders')
                            ->where('cart_id',$cart_id)
                            ->update([
                                'paid_by_wallet'=>$walletamt,
                                'rem_price'=>$rem_amount,
                                'payment_method'=>$payment_method,
                                'payment_status'=>'success'
                            ]);
                
                $sms = DB::table('notificationby')
                           ->select('sms')
                           //->where('user_id',$user_id) //commented  14-06-21 by Anish
                           ->first();
                           
                $sms_status = $sms->sms;

                //Commented for sms block 14-06-21 by Anish
                //if($sms_status == 1){
                if($sms_status == 2){
                    $codorderplaced = $this->ordersuccessfull($cart_id,$prod_name,$price2,$delivery_date,$time_slot,$user_phone);
                    #new added code to send sms through bulk msg beg  19jan 2021
                $getInvitationMsg = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully.You can expect your item(s) will be delivered on ".$delivery_date." between ".$time_slot.".";

                 $orderplacedmsg=$this->sendordermsg($getInvitationMsg,$user_phone);
                #new added code to send sms through bulk msg end
                }
                
                /////send mail
                $email = DB::table('notificationby')
                            ->select('email','app')
                            //->where('user_id',$user_id) //commented  14-06-21 by Anish
                            ->first();
                       
                $email_status = $email->email;
                $q  =   DB::table('users')
                            ->select('user_email','user_name')
                            ->where('user_id',$user_id)
                            ->first();
                
                $user_email = $q->user_email;
                $user_name  = $q->user_name;
                if($email_status == 1) {
                    $orderplaced = $this->orderplacedMail($cart_id,$prod_name   ,$price2,$delivery_date,$time_slot,$user_email,$user_name);
                }
            
                if($email->app == 1){
                    $notification_title = "WooHoo! Your Order is Placed";
                    $notification_text = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully.You can expect your item(s) will be delivered on ".$delivery_date." between ".$time_slot.".";

                    $date = date('d-m-Y');

                    $getDevice = DB::table('users')
                                    ->where('user_id', $user_id)
                                    ->select('device_id')
                                    ->first();
                                    
                    $created_at = Carbon::now();

                    if($getDevice) {

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
             
                $orderr1 = DB::table('orders')
                                ->where('cart_id', $cart_id)
                                ->first();

                ///////send notification to store//////
                $notification_title = "WooHoo ! You Got a New Order";
                $notification_text = "you got an order cart id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " . It will have to delivered on ".$delivery_date." between ".$time_slot.".";

                $date = date('d-m-Y');
                $getUser = DB::table('store')
                                ->get();

                $getDevice = DB::table('store')
                         ->where('store_id', $store_id)
                        ->select('device_id')
                        ->first();
               
                $created_at = Carbon::now();

                if($getDevice){

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
                        ->insert(['store_id'=>$store_id,
                         'not_title'=>$notification_title,
                         'not_message'=>$notification_text]);

                    $results = json_decode($result);
                }
                
                ////rewards earned////
                $checkre = DB::table('reward_points')
                            ->where('min_cart_value','<=',$price2)
                            ->orderBy('min_cart_value','desc')
                            ->first();
                        
                if($checkre){
                    $reward_point = $checkre->reward_point;
    
                    $inreward = DB::table('users')
                                 ->where('user_id',$user_id)
                                 ->update(['rewards'=>$reward_point]);
    
                    $cartreward = DB::table('cart_rewards')
                                    ->insert(['cart_id'=>$cart_id, 'rewards'=>$reward_point, 'user_id'=>$user_id]);
                }
          
                $data = [
                            "order_id"      => (string)$orderr1->order_id,
                            "user_id"       => (string)$orderr1->user_id,
                            "store_id"      => (string)$orderr1->store_id,
                            "address_id"    => (string)$orderr1->address_id,
                            "cart_id"       => (string)$orderr1->cart_id,
                            "total_price"   => (string)$orderr1->total_price ? round($orderr1->total_price) : '',
                            "price_without_delivery" => (string)$orderr1->price_without_delivery ? round($orderr1->price_without_delivery) : '',
                            "total_products_mrp"    => (string)$orderr1->total_products_mrp,
                            "payment_method"        => (string)$orderr1->payment_method,
                            "paid_by_wallet"        => (string)$orderr1->paid_by_wallet,
                            "rem_price"             => (string)$orderr1->rem_price ? round($orderr1->rem_price) : '',
                            "order_date"            => (string)$orderr1->order_date,
                            "delivery_date"         => (string)$orderr1->delivery_date,
                            "delivery_charge"       => (string)$orderr1->delivery_charge,
                            "time_slot"             => (string)$orderr1->time_slot,
                            "dboy_id"               => (string)$orderr1->dboy_id,
                            "order_status"          => (string)$orderr1->order_status,
                            "user_signature"        => (string)$orderr1->user_signature,
                            "cancelling_reason"     => (string)$orderr1->cancelling_reason,
                            "coupon_id"             => (string)$orderr1->coupon_id,
                            "coupon_discount"       => (string)$orderr1->coupon_discount,
                            "payment_status"        => (string)$orderr1->payment_status,
                            "cancel_by_store"       => (string)$orderr1->cancel_by_store,
                            "updated_at"            => (string)$orderr1->updated_at
                        ];
        
                $message = array('status'=>'2', 'message'=>'Order Placed successfully', 'data' => $data );
            	return $message;
            } else {
                  $oo = DB::table('orders')
                           ->where('cart_id',$cart_id)
                            ->update([
                            'paid_by_wallet'=>0,
                            'rem_price'=>$rem_amount,
                            'payment_method'=>NULL,
                            'payment_status'=>'failed'
                            ]);
                            
            	$message = array('status'=>'0', 'message'=>'Payment Failed');
            	return $message;
             }
        }
    }

    public function ongoing(Request $request)
    {
        $user_id =  $request->user_id;
        $ongoing =  DB::table('orders')
                        ->leftJoin('delivery_boy', 'orders.dboy_id', '=', 'delivery_boy.dboy_id')
                        ->where('orders.user_id',$user_id)
                        ->where('orders.order_status', '!=', 'Completed')
                        ->where('orders.payment_method', '!=', NULL)
                        ->orderBy('orders.order_id', 'DESC')
                        ->get();

        if(count($ongoing) > 0) {
            foreach($ongoing as $key => $ongoings) {
                $order  =   DB::table('store_orders')
                                ->leftJoin('product_varient', 'store_orders.varient_id','=','product_varient.varient_id')
                                ->select('store_orders.*','product_varient.description')
                                ->where('store_orders.order_cart_id',$ongoings->cart_id)
                                // ->where('store_orders.cancel_status', 0)
                                ->orderBy('store_orders.order_date', 'DESC')
                                ->get();

                $data[$key] = array(
                              'order_status'     => (string)$ongoings->order_status, 
                              'delivery_date'    => (string)$ongoings->delivery_date, 
                              'time_slot'        => (string)$ongoings->time_slot,
                              'payment_method'   => (string)$ongoings->payment_method,
                              'payment_status'   => (string)$ongoings->payment_status,
                              'paid_by_wallet'   => (string)$ongoings->paid_by_wallet ? (string)round($ongoings->paid_by_wallet) : '', 
                              'cart_id'          => (string)$ongoings->cart_id ,
                              'price'            => (string)$ongoings->total_price ? (string)round($ongoings->total_price) : '',
                              'del_charge'       => (string)$ongoings->delivery_charge,
                              'remaining_amount' => (string)$ongoings->rem_price,
                              'coupon_discount'  => (string)$ongoings->coupon_discount,
                              'dboy_name'        => (string)$ongoings->boy_name,
                              'dboy_phone'       => (string)$ongoings->boy_phone,
                              'item_qty'         => (string)$order->count() ?? 0,
                              'cancel_qty'       => (string)$order->where('cancel_status', 1)->count(),
                              'remain_qty'       => (string)$order->where('cancel_status', 0)->count(),
                            //   'data'             => $order
                            );
                
                foreach($order as $orderValue) {
                    
                    $data[$key]['data'][]  =    [
                                                
                                                "store_order_id"    =>  (string)$orderValue->store_order_id ?? '',
                                                "product_name"      =>  (string)$orderValue->product_name ?? '',
                                                "varient_image"     =>  (string)$orderValue->varient_image ?? '',
                                                "quantity"          =>  (string)$orderValue->quantity ?? '',
                                                "unit"              =>  (string)$orderValue->unit ?? '',
                                                "varient_id"        =>  (string)$orderValue->varient_id ?? '',
                                                "qty"               =>  (string)$orderValue->qty ?? '',
                                                "price"             =>  (string)$orderValue->price ? (string)round($orderValue->price) : 0,
                                                "total_mrp"         =>  (string)($orderValue->total_mrp) ? (string)round($orderValue->total_mrp) : 0,
                                                "order_cart_id"     =>  (string)$orderValue->order_cart_id ?? '',
                                                "order_date"        =>  (string)$orderValue->order_date ?? '',
                                                "store_approval"    =>  (string)$orderValue->store_approval ?? '',
                                                "cancel_reason"     =>  (string)$orderValue->cancel_reason ?? '',
                                                "cancel_status"     =>  (string)$orderValue->cancel_status ?? '',
                                                "image"             =>  (string)$orderValue->image ?? '',
                                                "description"       =>  (string)$orderValue->description ?? '',
                                            ];
                }
            }
        } else {
            $data = array('data'=>[]);
        }

        return $data;
  }

    public function cancel_for(Request $request)
    {
        $cancelfor = DB::table('cancel_for')
                  ->get();

        if($cancelfor){
        	$message = array('status'=>'1', 'message'=>'Cancelling reason list', 'data'=>$cancelfor);
        	return $message;
        } else {
        	$message = array('status'=>'0', 'message'=>'no list found', 'data'=>[]);
        	return $message;
        }
    }

    public function delete_order(Request $request)
    {
        $cart_id    =   $request->cart_id;
        $user       =   DB::table('orders')
                            ->where('cart_id',$cart_id)
                            ->first();
                 
        $user_id1   =   $user->user_id;
        $userwa1    =   DB::table('users')
                            ->where('user_id',$user_id1)
                            ->first();
                     
        $reason         =   $request->reason;
        $order_status   =   'Cancelled';
        $updated_at     =   Carbon::now();
        
        # set updated data.
        $orderData  =       [
                               'cancelling_reason' =>  $reason,
                               'order_status'      =>  $order_status,
                               'updated_at'        =>  $updated_at
                            ];
        
        if($request->cancel_image){
            $cancel_image = $request->cancel_image;
            $cancel_image = str_replace('data:image/png;base64,', '', $cancel_image);
            $fileName = date('dmyHis').'cancel_image'.'.'.'png';
            $fileName = str_replace(" ", "-", $fileName);
            $pth = str_replace("/source/public", "",base_path());
            \File::put($pth. '/images/order_cancel_images/' . $fileName, base64_decode($cancel_image));
            $orderData['image']  =  '/images/order_cancel_images/'.$fileName;
        }
        
        $order  =   DB::table('orders')
                        ->where('cart_id', $cart_id)
                        ->update( $orderData );

        $storeOrderInfoActive =   DB::table('store_orders')
                                    ->where('store_orders.order_cart_id', $cart_id)
                                    ->where('cancel_status', 0)
                                    ->get();
                                    
        $storeOrderInfo =   DB::table('store_orders')
                                ->where('store_orders.order_cart_id', $cart_id)
                                ->update(['cancel_status'  =>  1]);
        
        if($order){
            if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
                $newbal1 = $userwa1->wallet + round($storeOrderInfoActive->sum('price')) + $user->delivery_charge;
            } else {
                $newbal1 = $userwa1->wallet + round($storeOrderInfoActive->sum('price')) + $user->delivery_charge;
                // if($user->payment_status=='success'){
                //     $newbal1 = $userwa1->wallet + round($storeOrderInfoActive->sum('price')) + $user->delivery_charge;
                // } else {
                //   $newbal1 = $userwa1->wallet;
                // }
            }
      
            $userwalletupdate   =   DB::table('users')
                                        ->where('user_id',$user_id1)
                                        ->update(['wallet'=>$newbal1]);
                                 
    	    $message    =   array( 'status' => '1', 'message' => 'order cancelled', 'data' => $order );
    	    return $message;
    	    
        } else {
        	$message    =   array('status' => '0', 'message' => 'something went wrong', 'data' => []);
        	return $message;
        }
    }

    public function top_selling(Request $request)
    {
        $current = Carbon::now();
        $topselling =   DB::table('product_varient')
                            ->join ('product', 'product_varient.product_id', '=', 'product.product_id')
                            ->Leftjoin ('store_orders', 'product_varient.varient_id', '=', 'store_orders.varient_id')
                            ->Leftjoin ('orders', 'store_orders.order_cart_id', '=', 'orders.cart_id')
                            ->Leftjoin ('deal_product', 'product_varient.varient_id', '=', 'deal_product.varient_id')
                            ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                            ->select('product_varient.varient_id','product.product_id','product.product_name', 'product.product_image', 'product_varient.description', 'product_varient.price', 'product_varient.mrp', 'product_varient.varient_image','product_varient.unit','product_varient.quantity', 'store_products.stock',DB::raw('count(store_orders.varient_id) as count'))
                            ->groupBy('product_varient.varient_id','product.product_id','product.product_name', 'product.product_image', 'product_varient.description', 'product_varient.price', 'product_varient.mrp', 'product_varient.varient_image','product_varient.unit','product_varient.quantity')
                            ->where('deal_product.deal_price', NULL)
                            // ->where('store_products.stock', '>','0')
                            ->OrWhere('deal_product.valid_from', '>', $current)
                            ->OrWhere('deal_product.valid_to', '<', $current)
                            ->orderBy('count','desc')
                            ->limit(10)
                            ->get();

        if(count($topselling) > 0){
        	$message = array('status' => '1', 'message' => 'top selling products', 'data' => $topselling);
        	return $message;
        } else {
        	$message = array('status' => '0', 'message' => 'nothing in top', 'data' => []);
        	return $message;
        }
    }

    public function whatsnew(Request $request){
        $current = Carbon::now();
        $new = DB::table('product_varient')
                  ->join ('product', 'product_varient.product_id', '=', 'product.product_id')
                  ->Leftjoin ('deal_product', 'product_varient.varient_id', '=', 'deal_product.varient_id')
                  ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                  ->select('product_varient.varient_id','product.product_id','product.product_name', 'product.product_image', 'product_varient.description', 'product_varient.price', 'product_varient.mrp', 'product_varient.varient_image','product_varient.unit','product_varient.quantity','store_products.stock')
                  ->limit(10)
                  ->where('deal_product.deal_price', NULL)
                //   ->where('store_products.stock', '>','0')
                  ->OrWhere('deal_product.valid_from', '>', $current)
                  ->OrWhere('deal_product.valid_to', '<', $current)
                  ->orderByRaw('RAND()')
                  ->get();

         if(count($new)>0){
        	$message = array('status'=>'1', 'message'=>'New in App', 'data'=>$new);
        	return $message;
        }
        else{
        	$message = array('status'=>'0', 'message'=>'nothing in new', 'data'=>[]);
        	return $message;
        }
  }

    public function recentselling(Request $request){
        $current = Carbon::now();
      $recentselling = DB::table('product_varient')
                  ->join ('product', 'product_varient.product_id', '=', 'product.product_id')
                  ->Leftjoin ('store_orders', 'product_varient.varient_id', '=', 'store_orders.varient_id')
                  ->Leftjoin ('orders', 'store_orders.order_cart_id', '=', 'orders.cart_id')
                  ->Leftjoin ('deal_product', 'product_varient.varient_id', '=', 'deal_product.varient_id')
                  ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                  ->select('product_varient.varient_id','product.product_id','product.product_name', 'product.product_image', 'product_varient.description', 'product_varient.price', 'product_varient.mrp', 'product_varient.varient_image','product_varient.unit','product_varient.quantity','store_products.stock',DB::raw('count(store_orders.varient_id) as count'))
                  ->groupBy('product_varient.varient_id','product.product_id','product.product_name', 'product.product_image', 'product_varient.description', 'product_varient.price', 'product_varient.mrp', 'product_varient.varient_image','product_varient.unit','product_varient.quantity')
                  ->orderByRaw('RAND()')
                  ->where('deal_product.deal_price', NULL)
                //   ->where('store_products.stock', '>','0')
                  ->OrWhere('deal_product.valid_from', '>', $current)
                  ->OrWhere('deal_product.valid_to', '<', $current)
                  ->limit(10)
                  ->get();

         if(count($recentselling)>0){
        	$message = array('status'=>'1', 'message'=>'recent selling products', 'data'=>$recentselling);
        	return $message;
        }
        else{
        	$message = array('status'=>'0', 'message'=>'nothing in top', 'data'=>[]);
        	return $message;
        }
  }

    public function completed_orders(Request $request)
    {
       $user_id = $request->user_id;

      $completeds = DB::table('orders')
                      ->leftJoin('delivery_boy', 'orders.dboy_id', '=', 'delivery_boy.dboy_id')
                       ->where('user_id', $user_id)
                      ->where('order_status', '=', 'Completed')
                      ->where('order_status', '=', 'completed')
                      ->get();
             
      if(count($completeds)>0){
            foreach($completeds as $key => $completed) {
                $order  =   DB::table('store_orders')
                               ->leftJoin('product_varient', 'store_orders.varient_id','=','product_varient.varient_id')
                               ->select('store_orders.*','product_varient.description')
                               ->where('store_orders.order_cart_id',$completed->cart_id)
                                // ->where('store_orders.cancel_status', 0)
                               ->orderBy('store_orders.order_date', 'DESC')
                               ->get();
    
                $data[$key] =   array( 
                                    'order_status'      => (string)$completed->order_status ?? '', 
                                    'delivery_date'     => (string)$completed->delivery_date ?? '', 
                                    'time_slot'         => (string)$completed->time_slot ?? '',
                                    'payment_method'    => (string)$completed->payment_method ?? '',
                                    'total_price'       => (string)$completed->total_price ? (string)round($completed->total_price) : 0, 
                                    'payment_status'    => (string)$completed->payment_status ?? '',
                                    'paid_by_wallet'    => (string)$completed->paid_by_wallet ?? '', 
                                    'cart_id'           => (string)$completed->cart_id ?? '',
                                    'price'             => (string)$completed->total_price ? (string)round($completed->total_price) : 0,
                                    'del_charge'        => (string)$completed->delivery_charge ?? 0,
                                    'remaining_amount'  => (string)$completed->rem_price ?? '',
                                    'coupon_discount'   => (string)$completed->coupon_discount ?? '',
                                    'dboy_name'         => (string)$completed->boy_name ?? '',
                                    'dboy_phone'        => (string)$completed->boy_phone ?? '', 
                                    // 'data'=>$order
                                );
                                
                foreach($order as $orderValue) {
                    
                    $data[$key]['data'][]  =    [
                                                
                                                "store_order_id"    =>  (string)$orderValue->store_order_id ?? '',
                                                "product_name"      =>  (string)$orderValue->product_name ?? '',
                                                "varient_image"     =>  (string)$orderValue->varient_image ?? '',
                                                "quantity"          =>  (string)$orderValue->quantity ?? '',
                                                "unit"              =>  (string)$orderValue->unit ?? '',
                                                "varient_id"        =>  (string)$orderValue->varient_id ?? '',
                                                "qty"               =>  (string)$orderValue->qty ?? '',
                                                "price"             =>  (string)$orderValue->price ? (string)round($orderValue->price) : 0,
                                                "total_mrp"         =>  (string)($orderValue->total_mrp) ? (string)round($orderValue->total_mrp) : 0,
                                                "order_cart_id"     =>  (string)$orderValue->order_cart_id ?? '',
                                                "order_date"        =>  (string)$orderValue->order_date ?? '',
                                                "store_approval"    =>  (string)$orderValue->store_approval ?? '',
                                                "cancel_reason"     =>  (string)$orderValue->cancel_reason ?? '',
                                                "cancel_status"     =>  (string)$orderValue->cancel_status ?? '',
                                                "image"             =>  (string)$orderValue->image ?? '',
                                                "description"       =>  (string)$orderValue->description ?? '',
                                            ];
                }
            }
        }
        else{
            $data = array('data'=>[]);
        }
        return $data;


  }

    public function can_orders(Request $request)
    {
      $user_id = $request->user_id;
      $completed = DB::table('orders')
              ->where('user_id',$user_id)
              ->where('order_status', 'cancelled')
              ->orWhere('order_status', 'Cancelled')
               ->get();

      if(count($completed)>0){
      foreach($completed as $completeds){
      $order = DB::table('store_orders')
            ->join ('product_varient', 'store_orders.varient_id', '=', 'product_varient.varient_id')
            ->join ('product', 'product_varient.product_id', '=', 'product.product_id')
                  ->select('product_varient.varient_id','product.product_name', 'product_varient.varient_image','store_orders.qty','product_varient.description','product_varient.unit','product_varient.quantity','store_orders.order_cart_id')
                  ->where('store_orders.order_cart_id',$completeds->cart_id)
                  ->groupBy('product_varient.varient_id','product.product_name', 'product_varient.varient_image','store_orders.qty','product_varient.description','product_varient.unit','product_varient.quantity','store_orders.order_cart_id')
                  ->orderBy('store_orders.order_date', 'DESC')
                  ->get();


        $data[]=array( 'cart_id'=>$completeds->cart_id ,'price'=>$completeds->total_price,'del_charge'=>$completeds->delivery_charge, 'data'=>$order);
        }
        }
        else{
            $data[]=array('data'=>'No Orders Cancelled Yet');
        }
        return $data;


  }
    
    public function cancelItem(Request $request) 
    { 
        # form data gather
        $userId       = $request->user_id;
        $cartId       = $request->cart_id;
        $storeOrderId = $request->store_order_id;
        $cancelReason = $request->cancel_reason;
        $date_of_recharge  = carbon::now();

        # set updated data.
        $storeOrderData  =  [
                                'cancel_status'  =>  1,
                                'cancel_reason'  =>  $cancelReason,
                            ];
        
        if($request->cancel_image){
            $cancel_image = $request->cancel_image;
            $cancel_image = str_replace('data:image/png;base64,', '', $cancel_image);
            $fileName = date('dmyHis').'cancel_image'.'.'.'png';
            $fileName = str_replace(" ", "-", $fileName);
            $pth = str_replace("/source/public", "",base_path());
            \File::put($pth. '/images/order_cancel_images/' . $fileName, base64_decode($cancel_image));
            $storeOrderData['image']  =  '/images/order_cancel_images/'.$fileName;
        }

        # Get product price for wallet update.
        $storeOrderInfo =   DB::table('store_orders')
                              ->leftjoin('product_varient', 'product_varient.varient_id', '=', 'store_orders.varient_id')
                              ->leftjoin('orders', 'orders.cart_id', '=', 'store_orders.order_cart_id')
                              ->select('product_varient.price', 'store_orders.qty', 'orders.delivery_charge')
                              ->where('store_orders.order_cart_id', $cartId)
                              ->where('store_orders.store_order_id', $storeOrderId)
                              ->first();
        
        # update status on store order id.
        $update = DB::table('store_orders')
                    ->where('order_cart_id', $cartId)
                    ->where('store_order_id', $storeOrderId)
                    ->update($storeOrderData);
                    
        $storeOrdersCount   =   DB::table('store_orders')
                                  ->where('order_cart_id', $cartId)
                                  ->where('cancel_status', 0)
                                  ->get();

        $userInfo   =   DB::table('users')
                          ->where('user_id', $userId)
                          ->first();
     
        if ($storeOrdersCount->count() <= 0) {

          $price = $userInfo->wallet + round($storeOrderInfo->price * $storeOrderInfo->qty) + round($storeOrderInfo->delivery_charge);

          DB::table('orders')
              ->where('cart_id', $cartId)
              ->update([
                          'order_status' => 'Cancelled'
                      ]);


            $userInfo   =   DB::table('users')
                                ->where('user_id', $userId)
                                ->update([
                                            'wallet'        =>  $price
                                        ]);

                        DB::table('wallet_recharge_history')
                            ->insert([
                                      'amount'        =>  $storeOrderInfo->price,
                                      'type'          =>  'Order Cancel Amount',
                                      'order_cart_id' =>  $cartId,
                                      'user_id'       =>  $userId,
                                      'date_of_recharge' =>  $date_of_recharge,
                                      'recharge_status'  =>  'success',
                                      'order_store_id'   =>   $storeOrderId,
                                    ]);
        } else {
           
            $price = $userInfo->wallet + round($storeOrderInfo->price * $storeOrderInfo->qty);

            $userInfo   =   DB::table('users')
                            ->where('user_id', $userId)
                            ->update([
                                      'wallet'        =>  $price
                                    ]);

                        DB::table('wallet_recharge_history')
                            ->insert([
                                      'amount'        =>  $storeOrderInfo->price,
                                      'type'          =>  'Order Cancel Amount',
                                      'order_cart_id' =>  $cartId,
                                      'user_id'       =>  $userId,
                                      'date_of_recharge' =>  $date_of_recharge,
                                      'recharge_status'  =>  'success',
                                      'order_store_id'  =>   $storeOrderId,
                                    ]);
        }
                    
        if ($update) {
            
          $message = array('status' => '1', 'message' => 'Order Product cancel successfully');

        } else {
            
          $message = array('status' => '0', 'message' => 'Order Product not cancel successfully');
          
        }
        return $message;
    }
}
