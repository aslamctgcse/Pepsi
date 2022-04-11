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
        $delivery_type    =  $request->delivery_type;
        $delivery_type_id  =  $request->delivery_type_id;
        if($request->delivery_type_id==0){
          $delivery_type_id  =  1;
        }else{
          $delivery_type_id  =  $request->delivery_type_id;
        }
        $delivery_instruction  =  $request->delivery_instruction;
        $time_slot        =  $request->time_slot;
        $chars            =  "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $val              =  "";


        // $data             =  '[{"qty":"2","varient_id":"882","product_image":"images\/product\/24-06-2021\/Goat-Boneless.png","store_id":"3"},{"qty":"10","varient_id":"883","product_image":"images\/product\/07-07-2021\/toiletries-hygiene.jpg","store_id":"3"},{"qty":"1","varient_id":"958","product_image":"images\/product\/08-06-2021\/Fried-Rice.jpg","store_id":"3"}]';

        // $data             =  '[{"qty":"3","varient_id":"961","product_image":"images\/product\/28-06-2021\/amuullll.jpg","store_id":"6"},{"qty":"1","varient_id":"958","product_image":"images\/product\/08-06-2021\/Fried-Rice.jpg","store_id":"5"},{"qty":"2","varient_id":"949","product_image":"images\/product\/04-06-2021\/Veg-Biriyani.jpg","store_id":"6"},{"qty":"1","varient_id":"732","product_image":"images\/product\/28-05-2021\/14-Chakki-aata.jpg","store_id":"3"}]';

         // $data             =  '[{"qty":"3","varient_id":"964","product_image":"images\/product\/28-06-2021\/amuullll.jpg","store_id":"3"}, {"qty":"2","varient_id":"966","product_image":"images\/product\/04-06-2021\/Veg-Biriyani.jpg","store_id":"3"}]';

        // $data             =  '[{"qty":"3","varient_id":"964","product_image":"images\/product\/28-06-2021\/amuullll.jpg","store_id":"3"},{"qty":"1","varient_id":"971","product_image":"images\/product\/28-06-2021\/amuullll.jpg","store_id":"3"}]';

        
        // $data_array       =  json_decode($data);
        // $user_id          =  115;
        // $delivery_date    =  '2021-08-09';
        // $delivery_charge  =  0;
        // $delivery_type    =  "Normal Delivery - 3 hrs";
        // $delivery_type_id =  1;
        // $delivery_instruction =  "Testing instruction";
        // $time_slot        =  "";
        //dd($data_array); 



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
        $sub_cart_id = $val.$val2.$cr.'-STORE';

        # get user address.
        $ar      = DB::table('address')
                      ->select('society','city','lat','lng','address_id','receiver_phone','receiver_name','landmark','house_no','state')
                      ->where('user_id', $user_id)
                      ->where('select_status', 1)
                      ->first();            

        if(!$ar) {
          $message = array('status'=>'0', 'message'=>'Select Any Address');
          return $message;
        }

        $created_at = Carbon::now();
        $price2     = 0;
        $price5     = 0;
        $ph         = DB::table('users')
                        ->select('user_phone','user_email','wallet')
                        ->where('user_id',$user_id)
                        ->first();                

        $user_phone = $ph->user_phone;
        $sel_store = DB::table('store')
                        //->leftjoin('store_products','store.store_id', '=', 'store_products.store_id')
                        ->select("store.store_name","store.store_id"
                          ,DB::raw("6371 * acos(cos(radians(".$ar->lat . "))
                          * cos(radians(store.lat))
                          * cos(radians(store.lng) - radians(" . $ar->lng . "))
                          + sin(radians(" .$ar->lat. "))
                          * sin(radians(store.lat))) AS distance"))
                        //->where('store.city',$ar->city)
                        //->where('store.del_range','>=','distance')
                        ->having('distance', '<=', '3000')  
                        ->orderBy("distance",'asc')
                        ->first();
        //dd($sel_store);                

        if(!$sel_store) {

          $message = array('status' => '0', 'message' => 'No Stores Near Your Selected Address');
          return $message;

        } else {

          $store_id =$sel_store->store_id;
          //dd($data_array);

          foreach($data_array as $h) {

              $varient_id = $h->varient_id;

              $p  =  DB::table('product_varient')
                        ->join('product','product_varient.product_id','=','product.product_id')
                        ->Leftjoin('deal_product','product_varient.varient_id','=','deal_product.varient_id')
                        ->where('product_varient.varient_id',$varient_id)
                        ->first();
              //dd($p);          

              if($p->deal_price != NULL && $p->valid_from < $current && $p->valid_to > $current) {
                $price = $p->deal_price;
              } else {
                $price = $p->price;
              }

              $mrpprice  =  $p->mrp;
              $order_qty =  $h->qty;
              $store_id_new = $p->product_store_id;  
              $price2    += $price*$order_qty;
              $price5    += $mrpprice*$order_qty;
              $unit[]    =  $p->unit;
              $qty[]     =  $p->quantity;
              $p_name[]  =  $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
              $prod_name =  implode(',',$p_name);
          }

          $checkStore=array();
          $checka1='';
          $checka2=0;
          $secondCount = 0;

          //foreach ($data_array as $h)
          //dd($data_array);
          $count = count($data_array);

          for($i=0;$i<=$count-1;$i++)
          {
              $varient_id = $data_array[$i]->varient_id;
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
              $order_qty  = $data_array[$i]->qty;
              $store_id_new = $p->product_store_id;
              $price1     = $price*$order_qty;
              $total_mrp  = $mrp*$order_qty;

              $order_qty  = $data_array[$i]->qty;
              $p          = DB::table('product_varient')
                               ->join('product','product_varient.product_id','=','product.product_id')
                               ->where('product_varient.varient_id',$varient_id)
                               ->first();
              $n          = $p->product_name;

              
              $checka1 = $data_array[$i]->store_id;


              if($checka2!=$checka1){
                $data_array[$i]->totalPrice =   $price1;
                $data_array[$i]->totalMRP =   $total_mrp;
                $data_array[$i]->sub_order_cart_id =   $sub_cart_id.'-'.$store_id_new;
                $checkStore[] = $data_array[$i];
                $checka2=$checka1;
                $secondCount++;
                
              }else{
                $checkStore[$secondCount-1]->totalPrice +=   $price1;
                $checkStore[$secondCount-1]->totalMRP +=   $total_mrp;
              }

              $insert = DB::table('store_orders')
                            ->insertGetId([
                              'varient_id'     => $varient_id,
                              'qty'            => $order_qty,
                              'product_name'   => $n,
                              'sub_order_cart_id' =>$sub_cart_id.'-'.$store_id_new,
                              'varient_image'  => $p->varient_image,
                              'quantity'       => $p->quantity,
                              'unit'           => $p->unit,
                              'total_mrp'      => $total_mrp,
                              'order_cart_id'  => $cart_id,
                              'order_date'     => $created_at,
                              'actual_price'   => $price,
                              'price'          => $price1
                            ]);                           

          }


          $list = array();
          //dd($checkStore);
          foreach($checkStore as $k => $v) {
             $list[$v->store_id][]=$v;
           }
 
          $checkStoreNew=array();
          $count11 = count($list);
          //dd($list);
          foreach ($list as $list222) {
            //dd($list222);
            $totalPrice = 0;
            $totalMrp = 0;
            $DstoreId = '';
            $Dsub_order_cart_id = '';
            foreach ($list222 as $key=>$value) {
               //dd($value->totalPrice);
               $totalPrice+= $value->totalPrice;
               $totalMrp+= $value->totalMRP;
               $DstoreId=   $value->store_id;
               $Dsub_order_cart_id=   $value->sub_order_cart_id;              
            }
            $ffff = array("store_id"=>$DstoreId,"totalPrice"=>$totalPrice,"totalMRP"=>$totalMrp,"sub_order_cart_id"=>$Dsub_order_cart_id);
            //dd(json_decode($ffff));
            $ffffff = json_encode($ffff);
            $checkStoreNew[] =json_decode($ffffff);
          }
          //dd($checkStoreNew);


          //dd($checkStore);

          $delcharge = DB::table('freedeliverycart')
                         ->where('id', 1)
                         ->first();

          $charge = $delivery_charge;

          $chargePerPrice = $charge/$price2;
           
          //dd($chargePerPrice);
          //dd(($checkStore[0]->totalPrice+$checkStore[0]->totalPrice*$chargePerPrice).'  '.($checkStore[1]->totalPrice+$checkStore[1]->totalPrice*$chargePerPrice).'  '.($checkStore[2]->totalPrice+$checkStore[2]->totalPrice*$chargePerPrice));

          // if ($delcharge->min_cart_value <= $price2){
          //     $charge=0;
          // } else {
          //     $charge = $delcharge->del_charge;
          // }
          
         //$insert=1;          

          if($insert) {
            $totalDeliveryCharge =0;
            $bulkOrderDiscount=0;
            $onlineOrderDiscount=0;

            foreach ($checkStoreNew as $sh){
              //dd($sh->totalPrice);
                 $delcharge = DB::table('freedeliverycart_by_store')
                        ->where('delivery_store_id', $sh->store_id)
                         ->where('min_cart_value','>=', $sh->totalPrice)
                          
                         ->first();
                  
                  //$chargePerPrice = $delcharge->del_charge/$calLastTotal;
                  //dd( $delcharge->del_charge);
                  if($delcharge){
                    $newCharge = $delcharge->del_charge;
                    //dd($newCharge);  
                  }else{
                    $newCharge = 0;
                  }
                  $totalDeliveryCharge +=   $newCharge;


                  $newBulkDiscount=DB::table('bulk_order_discount')
                  //->where('bulk_order_store_id',$sh->product_store_id) 
                  ->where('bulk_order_min_amount','<=',$sh->totalPrice)
                  ->where('bulk_order_max_amount','>=',$sh->totalPrice)
                  ->first();

                  if($newBulkDiscount){
                    if($newBulkDiscount->bulk_order_discount_type=='percentage'){
                      $bb = $sh->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
                    }else{
                      $bb =$newBulkDiscount->bulk_order_discount;
                    }  
                  }else{
                    $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
                    $maxBDiscountData = DB::table('bulk_order_discount')
                    ->where('bulk_order_max_amount',$maxValueAmountLimit)
                    ->first();
                    if($sh->totalPrice>$maxValueAmountLimit){
                      if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                        $bb = $sh->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
                      }else{
                        $bb =$maxBDiscountData->bulk_order_discount;
                      }
                    }else{
                      $bb = 0;
                    }
                  }

                  $bulkOrderDiscount+= $bb;

                  // $letNewTotal= $sh->totalPrice-$bb+$newCharge;
                  // $newOnlineDiscount=DB::table('online_payment_discount') 
                  // ->where('online_payment_min_amount','<=',$letNewTotal)
                  // ->where('online_payment_max_amount','>=',$letNewTotal)
                  // ->first(); 

                  // if($newOnlineDiscount){
                  //   if($newOnlineDiscount->online_payment_discount_type=='percentage'){
                  //     $opd = ($letNewTotal)*$newOnlineDiscount->online_payment_discount/100;  
                  //   }else{
                  //     $opd =$newOnlineDiscount->online_payment_discount;
                  //   }  
                  // }else{
                  //   $maxValueAmountLimit = DB::table('online_payment_discount')->max('online_payment_max_amount');
                  //   $maxOnlineDiscountData = DB::table('online_payment_discount')
                  //   ->where('online_payment_max_amount',$maxValueAmountLimit)
                  //   ->first();
                  //   if($letNewTotal>$maxValueAmountLimit){
                  //     if($maxOnlineDiscountData->online_payment_discount_type=='percentage'){
                  //       $opd = ($letNewTotal)*$maxOnlineDiscountData->online_payment_discount/100;  
                  //     }else{
                  //       $opd =$maxOnlineDiscountData->online_payment_discount;
                  //     }
                  //   }else{
                  //     $opd = 0;
                  //   }
                  // }

                  // $onlineOrderDiscount+= $opd;
 


            $sub_oo = DB::table('sub_orders')
                      ->insertGetId([
                      'cart_id'                   => $cart_id,
                      'sub_order_cart_id'         => $sh->sub_order_cart_id,
                      'total_price'               => round($sh->totalPrice-$bb +$newCharge,2),
                      'total_price_without_delivery_discount'=> $sh->totalPrice,
                      'price_without_delivery'    => $sh->totalPrice,
                      'total_products_mrp'        => $sh->totalMRP,
                      'delivery_charge'           => round($newCharge,2),
                      'user_id'                   => $user_id,
                      'store_id'                  => $sh->store_id,
                      'rem_price'                 => round($sh->totalPrice-$bb + $newCharge,2),
                      'order_date'                => $created_at,
                      'delivery_date'             => $delivery_date,
                      'time_slot'                 => $time_slot,
                      'address_id'                => $ar->address_id,
                      'delivery_type_id'          => $delivery_type_id,
                      'delivery_type'             => $delivery_type,
                      'delivery_mobile'           => $ar->receiver_phone,
                      'delivery_fname'            => $ar->receiver_name,
                      'delivery_email'            => $ph->user_email,
                      'delivery_city'             => $ar->city,
                      'bulk_order_based_discount' => $bb,
                      'delivery_landmark'          => $ar->landmark,
                      'delivery_address'          => $ar->house_no.','.$ar->society.','.$ar->city.','.$ar->landmark.','.$ar->state,
                      'order_special_instructions'=> $delivery_instruction,
                    ]);

            }

            //dd($totalDeliveryCharge);

            $oo = DB::table('orders')
                      ->insertGetId([
                      'cart_id'                   => $cart_id,
                      'total_price'               => $price2 - $bulkOrderDiscount + $totalDeliveryCharge,
                      'price_without_delivery'    => $price2,
                      'total_price_without_delivery_discount'=> $price2,
                      'total_products_mrp'        => $price5,
                      'delivery_charge'           => $totalDeliveryCharge,
                      'user_id'                   => $user_id,
                      'store_id'                  => $store_id,
                      'rem_price'                 => $price2 - $bulkOrderDiscount + $totalDeliveryCharge,
                      'order_date'                => $created_at,
                      'delivery_date'             => $delivery_date,
                      'time_slot'                 => $time_slot,
                      'address_id'                => $ar->address_id,
                      'delivery_type_id'          => $delivery_type_id,
                      'delivery_type'             => $delivery_type,
                      'delivery_mobile'           => $ar->receiver_phone,
                      'delivery_fname'            => $ar->receiver_name,
                      'delivery_email'            => $ph->user_email,
                      'delivery_city'             => $ar->city,
                      'delivery_landmark'          => $ar->landmark,
                      'delivery_address'          => $ar->house_no.','.$ar->society.','.$ar->city.','.$ar->landmark.','.$ar->state,
                      'order_special_instructions'=> $delivery_instruction,
                      'bulk_order_based_discount'=> $bulkOrderDiscount,
                    ]);

            $ordersuccessed = DB::table('orders')
                                 ->where('order_id', $oo)
                                 ->first();
            //dd($ordersuccessed);                     


            $message = array('status' => '1', 'message' => 'Proceed To Payment', 'data' => $ordersuccessed );
            return $message;
          } else {
            $message = array('status' => '0', 'message' => 'Insertion Failed', 'data' => []);
            return $message;
          }

        }
    }

    public function checkout(Request $request)
    { 
		//dd($request->all());
        $cart_id        = $request->cart_id;
        $payment_method = $request->payment_method;
        $payment_status = $request->payment_status;
        $wallet         = $request->wallet;

        // $cart_id        = 'IWAP8576';
        // $payment_method = 'card';
        // $payment_status = 'success';
        // $wallet         = 'no';

        //dd($cart_id);


        $orderr = DB::table('orders')
                    ->where('cart_id', $cart_id)
                    ->first();
        //dd($orderr->total_price);            

        $sub_orderr_count = DB::table('sub_orders')
                    ->where('cart_id', $cart_id)
                    ->get()->count();
        
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
       if($orderr->payment_status!='success'){
 
        if($payment_method == 'COD' || $payment_method =='cod') {
          //payment mode is cash on delivery
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
              // if($sub_orderr_count>'1' ){
                $message = array('status'=>'0', 'message'=>'You have insufficient wallet balance. Please unselect wallet option');
                return $message;
            }
          }else{
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

          if($sub_orderr_count=='1' && $wallet=='yes'){
            $sub_oo = DB::table('sub_orders')
                      ->where('cart_id',$cart_id)
                      ->update([
                        'paid_by_wallet'=>$walletamt,
                        'rem_price'=>$rem_amount,  
                        'payment_status'=>$payment_status,
                        'payment_method'=>$payment_method
                      ]);
          }else{
            $sub_oo = DB::table('sub_orders')
                       ->where('cart_id',$cart_id)
                        ->update([
                        'payment_status'=>$payment_status,
                        'payment_method'=>$payment_method
                        ]);
          }

          $sms = DB::table('notificationby')
                       ->select('sms')
                       //->where('user_id',$user_id)
                       ->first();

          /*Sms block*/
          $sms_status = $sms->sms;

          if($sms_status == 1){
                // $orderplacedmsg = $this->ordersuccessfull($cart_id,$prod_name,$price2,$delivery_date,$time_slot,$user_phone);

            
          }
          /*End Sms block*/
            
          /////send mail
          
          $email = DB::table('notificationby')
                   ->select('email','app')
                   //->where('user_id',$user_id)
                   ->first();
                   
          $q = DB::table('users')
                              ->select('user_email','user_name')
                              ->where('user_id',$user_id)
                              ->first();
                              
          $user_email = $q->user_email;

          $user_name = $q->user_name;
          $email_status = $email->email;
          if($email_status == 1){
            //$codorderplaced = $this->codorderplacedMail($cart_id,$prod_name,$price2,$delivery_date,$time_slot,$user_email,$user_name);
          }
               
          if($email->app ==1) {
            $notification_title = "WooHoo! Your Order is Placed";
            // $notification_text = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully.You can expect your item(s) will be delivered on ".$delivery_date." between ".$time_slot.".";
            $notification_text = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully. You can expect your item(s) will be delivered on ".$delivery_date.".";

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
                'body'  => $notification_text,
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
                ->insert([
                  'user_id'=>$user_id,
                  'noti_title'=>$notification_title,
                  'noti_message'=>$notification_text
                ]);

              $results = json_decode($result);
            }
            $getInvitationMsg = urlencode("Dear Customer, your Dealwy order no is ".$cart_id." and the bill amount is ".$orderr->total_price." and delivery within 48 hours.");
            $apiUrl = 'http://www.onex-ultimo.in/api/pushsms?user=NFDML&authkey=92A7YFf76gJU&sender=Dealwy&mobile='.$user_phone.'&text='.$getInvitationMsg.'&rpt=1&summary=1&output=json&entityid=1201160517117234073&templateid=1207163298118080010';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
          }
          
          $orderr1 = DB::table('orders')
                   ->where('cart_id', $cart_id)
                   ->first();

          ///////send notification to store//////

          $notification_title = "WooHoo ! You Got a New Order";
          // $notification_text = "you got an order cart id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " . It will have to delivered on ".$delivery_date." between ".$time_slot.".";
          $notification_text = "you got an order cart id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " . It will have to delivered on ".$delivery_date.".";

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
            //Commented reward for COD
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

          $message = array('status'=>'1', 'message'=>'Order Placed Successfully', 'data'=>$orderr1 );
          return $message;
        //
        } else {
            $walletamt = 0;
            $prii = $price2 + $charge;

          if($payment_method=='wallet' ||  $payment_method=='Wallet'){  
            if($wallet == 'yes' || $wallet == 'Yes' || $wallet == 'YES'){
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
                    // if($sub_orderr_count>'1' ){
                          
                    
                       $message = array('status'=>'0', 'message'=>'Insufficient balance in wallet. Please choose cash on delivery or Online Payment.');
                       return $message;
                    // }

    
                    // $rem_amount=  $prii-$ph->wallet;
                    // $walletamt = $ph->wallet;

                    // $rem_wallet =0;
                    // $walupdate = DB::table('users')
                    //            ->where('user_id',$user_id)
                    //            ->update(['wallet'=>$rem_wallet]);          
                }
            } else {
                $rem_amount=  $prii;
                $walletamt = 0;
            }
          }else{
            $rem_amount=  0;
            $walletamt = 0;

            $new_check_oo = DB::table('orders')
                       ->where('cart_id',$cart_id)
                       ->first();
            $new_check_sub_oo = DB::table('sub_orders')
                       ->where('cart_id',$cart_id)
                       ->get();

            $onlineOrderDiscount=0;           
            foreach($new_check_sub_oo as $checkStoreWise){
              $letNewTotal= $checkStoreWise->total_price;
                  $newOnlineDiscount=DB::table('online_payment_discount') 
                  ->where('online_payment_min_amount','<=',$letNewTotal)
                  ->where('online_payment_max_amount','>=',$letNewTotal)
                  ->first();
                  //dd($newOnlineDiscount); 

                  if($newOnlineDiscount){
                    if($newOnlineDiscount->online_payment_discount_type=='percentage'){
                      $opd = ($letNewTotal)*$newOnlineDiscount->online_payment_discount/100;  
                    }else{
                      $opd =$newOnlineDiscount->online_payment_discount;
                    }  
                  }else{
                    $maxValueAmountLimit = DB::table('online_payment_discount')->max('online_payment_max_amount');
                    $maxOnlineDiscountData = DB::table('online_payment_discount')
                    ->where('online_payment_max_amount',$maxValueAmountLimit)
                    ->first();
                    if($letNewTotal>$maxValueAmountLimit){
                      if($maxOnlineDiscountData->online_payment_discount_type=='percentage'){
                        $opd = ($letNewTotal)*$maxOnlineDiscountData->online_payment_discount/100;  
                      }else{
                        $opd =$maxOnlineDiscountData->online_payment_discount;
                      }
                    }else{
                      $opd = 0;
                    }
                  }

                  $onlineOrderDiscount+= $opd;
                  $latest_sub_oo = DB::table('sub_orders')
                       ->where('cart_id',$cart_id)
                       ->where('sub_order_id',$checkStoreWise->sub_order_id)
                        ->update([
                        'total_price'=>$checkStoreWise->total_price-$opd,
                        'rem_price'=>$checkStoreWise->rem_price-$opd,
                        'online_payment_based_discount'=>$opd,
                        ]);     
                      
              }
              
              $latest_oo = DB::table('orders')
                       ->where('cart_id',$cart_id)
                        ->update([
                        'total_price'=>$new_check_oo->total_price-$onlineOrderDiscount,
                        'rem_price'=>$new_check_oo->rem_price-$onlineOrderDiscount,
                        'online_payment_based_discount'=>$onlineOrderDiscount,
                        ]);
              //dd($onlineOrderDiscount);

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
             
            if($payment_method=='wallet' ||  $payment_method=='Wallet'||$payment_method=='cod' ||  $payment_method=='COD'){
              if($sub_orderr_count=='1' && $wallet=='yes'){
                   
                   $sub_oo = DB::table('sub_orders')
                       ->where('cart_id',$cart_id)
                        ->update([
                        'paid_by_wallet'=>$walletamt,
                        'rem_price'=>$rem_amount,  
                        'payment_status'=>$payment_status,
                        'payment_method'=>$payment_method
                        ]);
              }else{
                if($ph->wallet >= $prii) {
                    $sub_oo_1 = DB::table('sub_orders')
                       ->where('cart_id',$cart_id)
                        ->get();
                    foreach($sub_oo_1 as $sub_oo_1_data){
                      $sub_oo_2 = DB::table('sub_orders')
                       ->where('cart_id',$cart_id)
                       ->where('sub_order_id',$sub_oo_1_data->sub_order_id)
                        ->update([
                        "paid_by_wallet" => $sub_oo_1_data->rem_price,
                        "rem_price" => 0,  
                        'payment_status'=>$payment_status,
                        'payment_method'=>$payment_method
                        ]);
                    }        
                }else{
                    $sub_oo = DB::table('sub_orders')
                       ->where('cart_id',$cart_id)
                        ->update([
                        'payment_status'=>$payment_status,
                        'payment_method'=>$payment_method
                        ]);
                }       
              }
            }else{
                 $sub_oo = DB::table('sub_orders')
                       ->where('cart_id',$cart_id)
                        ->update([
                        "rem_price" => 0,  
                        'payment_status'=>$payment_status,
                        'payment_method'=>$payment_method
                        ]);

            }                           
                
                $sms = DB::table('notificationby')
                           ->select('sms')
                           //->where('user_id',$user_id)
                           ->first();
                           
                /*$sms_status = $sms->sms;
                if($sms_status == 1){
                    $codorderplaced = $this->ordersuccessfull($cart_id,$prod_name,$price2,$delivery_date,$time_slot,$user_phone);
                }*/
                
                /////send mail
                $email = DB::table('notificationby')
                            ->select('email','app')
                            //->where('user_id',$user_id)
                            ->first();
                       
                $email_status = $email->email;
                $q  =   DB::table('users')
                            ->select('user_email','user_name')
                            ->where('user_id',$user_id)
                            ->first();
                $user_phone = $ph->user_phone;
                $user_email = $q->user_email;
                $user_name  = $q->user_name;

                if($email_status == 1) {
                  //dd($user_phone);
                    //$orderplaced = $this->codorderplacedMail($cart_id,$prod_name   ,$price2,$delivery_date,$time_slot,$user_email,$user_name);
                  
                }
            
                if($email->app == 1){
                    $notification_title = "WooHoo! Your Order is Placed";
                    //$notification_text = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully. You can expect your item(s) will be delivered on ".$delivery_date." between ".$time_slot.".";
                    $notification_text = "Order Successfully Placed: Your order id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " is placed Successfully. You can expect your item(s) will be delivered on ".$delivery_date.".";

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
                    $getInvitationMsg = urlencode("Dear Customer, your Dealwy order no is ".$cart_id." and the bill amount is ".$orderr->total_price." and delivery within 48 hours.");
                  $apiUrl = 'http://www.onex-ultimo.in/api/pushsms?user=NFDML&authkey=92A7YFf76gJU&sender=Dealwy&mobile='.$user_phone.'&text='.$getInvitationMsg.'&rpt=1&summary=1&output=json&entityid=1201160517117234073&templateid=1207163298118080010';

                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $apiUrl);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                  $response = curl_exec($ch);
                  curl_close($ch);
                }
             
                $orderr1 = DB::table('orders')
                                ->where('cart_id', $cart_id)
                                ->first();

                ///////send notification to store//////
                $notification_title = "WooHoo ! You Got a New Order";
                // $notification_text = "You got an order cart id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " . It will have to delivered on ".$delivery_date." between ".$time_slot.".";
                $notification_text = "You got an order cart id #".$cart_id." contains of " .$prod_name." of price rs ".$price2. " . It will have to delivered on ".$delivery_date.".";

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
        
                $message = array('status'=>'1', 'message'=>'Order Placed Successfully', 'data' => $data );
              return $message;
            } else {
                  $oo = DB::table('orders')
                           ->where('cart_id',$cart_id)
                            ->update([
                            'paid_by_wallet'=>$walletamt,
                            'rem_price'=>$rem_amount,
                            'payment_method'=>NULL,
                            'payment_status'=>'failed'
                            ]);

                  $sub_oo = DB::table('sub_orders')
                           ->where('cart_id',$cart_id)
                            ->update([
                            'payment_method'=>NULL,
                            'payment_status'=>'failed'
                            ]);          
                            
              $message = array('status'=>'0', 'message'=>'Payment Failed!');
              return $message;
             }
        }
       }else{
          $message = array('status'=>'0', 'message'=>'Order Already Placed Successfully!');
              return $message;
        
       } 
    }

    public function ongoing(Request $request)
    {
    $user_id = $request->user_id;
    //$user_id = '115';
    $ongoing = DB::table('orders')
                    ->leftJoin('delivery_boy', 'orders.dboy_id', '=', 'delivery_boy.dboy_id')
                    ->select('orders.*','delivery_boy.boy_name as boy_name','delivery_boy.boy_phone as boy_phone')
                    ->where('orders.user_id',$user_id)
                    ->where('orders.order_status', '!=', 'Completed')
                    ->where('orders.payment_method', '!=', NULL)
                    ->orderBy('orders.order_id', 'DESC')
                    ->get(); 
    //dd($ongoing);                                

    if(count($ongoing)>0) {
        foreach($ongoing as $ongoings) {
                  

            $orderProducts = DB::table('store_orders')
                        ->leftJoin('product_varient', 'store_orders.varient_id','=','product_varient.varient_id')
                        ->leftJoin('product', 'product.product_id','=','product_varient.product_id')
                        ->leftJoin('store', 'store.store_id','=','product.product_store_id')
                        ->select('store_orders.*','product_varient.description', 'product.product_store_id as store_id','store.store_name as store_name')
                        ->where('store_orders.order_cart_id',$ongoings->cart_id)
                        ->orderBy('store_orders.order_date', 'DESC')
                        ->get();
     
            $orderItemQty = $orderProducts->count();
            $orderRemainingQty = $orderProducts->where('cancel_status', '0')->count();
            $orderCancelQty = $orderProducts->where('cancel_status', '1')->count();            

            $data[] = array(
                          'order_status'     => (string)$ongoings->order_status, 
                          'delivery_date'    => (string)$ongoings->delivery_date,
                          'delivery_type_id'    => (string)$ongoings->delivery_type_id,  
                          'time_slot'        => (string)$ongoings->time_slot,
                          'payment_method'   => (string)$ongoings->payment_method,
                          'payment_status'   => (string)$ongoings->payment_status,
                          'paid_by_wallet'   => (string)$ongoings->paid_by_wallet ? (string)round($ongoings->paid_by_wallet) : '', 
                          'cart_id'          => (string)$ongoings->cart_id ,
                          'price'   => (string)$ongoings->total_price ? (string)round($ongoings->total_price,2) : '0',
                          'exact_purchase_amt'   => (string)$ongoings->total_price_without_delivery_discount ? (string)round($ongoings->total_price_without_delivery_discount,2) : '0',
                          'del_charge'       => (string)$ongoings->delivery_charge,
                          'remaining_amount' => (string)$ongoings->rem_price,
                          'coupon_discount'  => (string)$ongoings->coupon_discount,
                          'bulk_order_discount'  => (string)$ongoings->bulk_order_based_discount ? (string)round($ongoings->bulk_order_based_discount,2) : '0',
                          'online_payment_discount'  => (string)$ongoings->online_payment_based_discount ? (string)round($ongoings->online_payment_based_discount,2) : '0',
                          'dboy_name'        => (string)$ongoings->boy_name,
                          'dboy_phone'       => (string)$ongoings->boy_phone,
                          //'store_id'     => (string)$ongoings->store_id, 
                          'quantity'       => (string)$orderItemQty ? (string)$orderItemQty : '0',
                          'remain_qty'   => (string)$orderRemainingQty ? (string)$orderRemainingQty : '0',
                          'cancel_qty'   => (string)$orderCancelQty ? (string)$orderCancelQty : '0',
                          //'data'             => $orderProducts
                        );
        }
      } else {
          $data = array('data'=>[]);
      }

      return $data;
  }

 
     public function ongoingStoreOrders(Request $request)
    {
       $cart_id = $request->cart_id;
       //$cart_id = 'JSSU76b9';
       $ongoing = DB::table('sub_orders')
                    ->leftJoin('delivery_boy', 'sub_orders.dboy_id', '=', 'delivery_boy.dboy_id')
                    ->select('sub_orders.*','delivery_boy.boy_name as boy_name','delivery_boy.boy_phone as boy_phone')
                    ->where('sub_orders.cart_id',$cart_id)
                    ->where('sub_orders.order_status', '!=', 'Completed')
                    ->where('sub_orders.payment_method', '!=', NULL)
                    ->orderBy('sub_orders.sub_order_id', 'DESC')
                    ->get(); 

         //dd($ongoing);           
                                    

        if(count($ongoing)>0) {
          foreach($ongoing as $ongoings) {
            $orderProducts = DB::table('store_orders')
                        ->leftJoin('product_varient', 'store_orders.varient_id','=','product_varient.varient_id')
                        ->leftJoin('product', 'product.product_id','=','product_varient.product_id')
                        ->leftJoin('store', 'store.store_id','=','product.product_store_id')
                        ->select('store_orders.*','product_varient.description', 'product.product_store_id as store_id','store.store_name as store_name')
                        ->where('store_orders.order_cart_id',$ongoings->cart_id)
                        ->where('store_orders.sub_order_cart_id',$ongoings->sub_order_cart_id)
                        ->orderBy('store_orders.order_date', 'DESC')
                        ->get();
            //dd($orderProducts);     
     
            $orderItemQty = $orderProducts->count();
            $orderRemainingQty = $orderProducts->where('cancel_status', '0')->count();
            $orderCancelQty = $orderProducts->where('cancel_status', '1')->count();            

            $data[] = array(
                          'order_status'     => (string)$ongoings->order_status, 
                          'delivery_date'    => (string)$ongoings->delivery_date,
                          'delivery_type_id'    => (string)$ongoings->delivery_type_id, 
                          'time_slot'        => (string)$ongoings->time_slot,
                          'payment_method'   => (string)$ongoings->payment_method,
                          'payment_status'   => (string)$ongoings->payment_status,
                          'paid_by_wallet'   => (string)$ongoings->paid_by_wallet ? (string)round($ongoings->paid_by_wallet) : '', 
                          'cart_id'          => (string)$ongoings->cart_id ,
                          'price'            => (string)$ongoings->total_price ? (string)round($ongoings->total_price,2) : '0',
                          'exact_purchase_amt'   => (string)$ongoings->total_price_without_delivery_discount ? (string)round($ongoings->total_price_without_delivery_discount,2) : '0',
                          'del_charge'       => (string)$ongoings->delivery_charge,
                          'remaining_amount' => (string)$ongoings->rem_price,
                          'coupon_discount'  => (string)$ongoings->coupon_discount,
                          'bulk_order_discount'  => (string)$ongoings->bulk_order_based_discount ? (string)round($ongoings->bulk_order_based_discount,2) : '0',
                          'online_payment_discount'  => (string)$ongoings->online_payment_based_discount ? (string)round($ongoings->online_payment_based_discount,2) : '0',
                          'dboy_name'        => (string)$ongoings->boy_name,
                          'dboy_phone'       => (string)$ongoings->boy_phone,
                          //'store_id'     => (string)$ongoings->store_id, 
                          'quantity'       => (string)$orderItemQty ? (string)$orderItemQty : '0',
                          'remain_qty'   => (string)$orderRemainingQty ? (string)$orderRemainingQty : '0',
                          'cancel_qty'   => (string)$orderCancelQty ? (string)$orderCancelQty : '0',
                          'data'             => $orderProducts
                        );
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
          $message = array('status'=>'1', 'message'=>'Cancelling Reason List', 'data'=>$cancelfor);
          return $message;
        }
        else{
          $message = array('status'=>'0', 'message'=>'No List Found', 'data'=>[]);
          return $message;
        }
    }

    public function delete_order(Request $request)
    {
      $cart_id = $request->cart_id;
       $user = DB::table('orders')
              ->where('cart_id',$cart_id)
              ->first();
        $user_id1 = $user->user_id;
         $userwa1 = DB::table('users')
                     ->where('user_id',$user_id1)
                     ->first();
      $reason = $request->reason;
      $order_status = 'Cancelled';
      $updated_at = Carbon::now();
      $order = DB::table('orders')
                  ->where('cart_id', $cart_id)
                  ->update([
                        'cancelling_reason'=>$reason,
                        'order_status'=>$order_status,
                        'updated_at'=>$updated_at]);

       if($order){
        if($user->payment_method == 'COD' || $ordr->payment_method == 'Cod' || $ordr->payment_method == 'cod'){
                $newbal1 = $userwa1->wallet + $user->paid_by_wallet;
          }
          else{
              if($user->payment_status=='success'){
                  $newbal1 = $userwa1->wallet + $user->rem_price + $user->paid_by_wallet;
              }
              else{
                   $newbal1 = $userwa1->wallet;
              }
             }
           $userwalletupdate = DB::table('users')
             ->where('user_id',$user_id1)
             ->update(['wallet'=>$newbal1]);
          $message = array('status'=>'1', 'message'=>'Order Cancelled', 'data'=>$order);
          return $message;
        }
        else{
          $message = array('status'=>'0', 'message'=>'Something Went Wrong', 'data'=>[]);
          return $message;
        }


  }

    public function top_selling(Request $request){
       $current = Carbon::now();
      $topselling = DB::table('product_varient')
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

         if(count($topselling)>0){
          $message = array('status'=>'1', 'message'=>'top selling products', 'data'=>$topselling);
          return $message;
        }
        else{
          $message = array('status'=>'0', 'message'=>'nothing in top', 'data'=>[]);
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
       //$user_id = '115';

      $completeds = DB::table('orders')

                      ->leftJoin('delivery_boy', 'orders.dboy_id', '=', 'delivery_boy.dboy_id')
                      ->where('user_id', $user_id)
                      ->where('order_status', '=', 'Completed')
                      ->where('order_status', '=', 'completed')
                      ->orderBy('orders.order_id', 'DESC')
                      ->get(); 

             
      if(count($completeds)>0){
      foreach($completeds as $completed){
                
      $order = DB::table('store_orders')
               ->leftJoin('product_varient', 'store_orders.varient_id','=','product_varient.varient_id')
               ->leftJoin('product', 'product.product_id','=','product_varient.product_id')
              ->select('store_orders.*','product_varient.description','product.product_store_id as store_id')
              ->where('store_orders.order_cart_id',$completed->cart_id)
              ->orderBy('store_orders.order_date', 'DESC')
              ->get();
      //dd($order);        
      /*$count = count($order);
      for($x = 0; $x < $count; $x++){
        $order[$x]->store_id = $completed->store_id;
      } */       

      $orderReviews = DB::table('order_rating_reviews')               
              ->select('order_rating_reviews.rating_id','order_rating_reviews.order_rating','order_rating_reviews.order_comment as order_review')
              ->where('order_rating_reviews.order_cart_id',$completed->cart_id)
              ->get();        


        $data[]=array('order_status'=>$completed->order_status, 'delivery_date'=>$completed->delivery_date, 'time_slot'=>$completed->time_slot,'payment_method'=>$completed->payment_method,'total_price' => $completed->total_price, 'payment_status'=>$completed->payment_status,'paid_by_wallet'=>$completed->paid_by_wallet, 'cart_id'=>$completed->cart_id ,'price'=>$completed->total_price,'exact_purchage_amt'=> (string)$completed->total_price_without_delivery_discount ? (string)round($completed->total_price_without_delivery_discount,2) : '0','del_charge'=>$completed->delivery_charge,'remaining_amount'=>$completed->rem_price,'coupon_discount'=>$completed->coupon_discount,'bulk_order_discount'=>$completed->bulk_order_based_discount,'online_payment_discount'=>$completed->online_payment_based_discount,'dboy_name'=>$completed->boy_name,'dboy_phone'=>$completed->boy_phone, 'data'=>$order, 'reviews'=>$orderReviews, );
        }
        }
        else{
            $data=array('data'=>[]);
        }
        return $data;


  }

  public function completedStoreOrders(Request $request)
    {
       $cart_id = $request->cart_id;
       //$cart_id = 'KZMR9644';

      $completeds = DB::table('sub_orders')

                      ->leftJoin('delivery_boy', 'sub_orders.dboy_id', '=', 'delivery_boy.dboy_id')
                      ->where('sub_orders.cart_id',$cart_id)
                      ->where('order_status', '=', 'Completed')
                      ->where('order_status', '=', 'completed')
                      ->orderBy('sub_orders.sub_order_id', 'DESC')
                      ->get();               
             
      if(count($completeds)>0){
      foreach($completeds as $completed){

                
      $order = DB::table('store_orders')
               ->leftJoin('product_varient', 'store_orders.varient_id','=','product_varient.varient_id')
               ->leftJoin('product', 'product.product_id','=','product_varient.product_id')
              ->select('store_orders.*','product_varient.description','product.product_store_id as store_id')
              ->where('store_orders.order_cart_id',$completed->cart_id)
              ->where('store_orders.sub_order_cart_id',$completed->sub_order_cart_id)
              ->orderBy('store_orders.order_date', 'DESC')
              ->get();
      

      $orderReviews = DB::table('order_rating_reviews')               
              ->select('order_rating_reviews.rating_id','order_rating_reviews.order_rating','order_rating_reviews.order_comment as order_review')
              ->where('order_rating_reviews.order_cart_id',$completed->cart_id)
              ->get();        


        $data[]=array('order_status'=>$completed->order_status, 'delivery_date'=>$completed->delivery_date, 'time_slot'=>$completed->time_slot,'payment_method'=>$completed->payment_method,'total_price' => $completed->total_price,'exact_purchage_amt'=> (string)$completed->total_price_without_delivery_discount ? (string)round($completed->total_price_without_delivery_discount,2) : '0','payment_status'=>$completed->payment_status,'paid_by_wallet'=>$completed->paid_by_wallet, 'store_id'=>$completed->store_id ,'cart_id'=>$completed->cart_id ,'sub_cart_id'=>$completed->sub_order_cart_id ,'price'=>$completed->total_price,'del_charge'=>$completed->delivery_charge,'remaining_amount'=>$completed->rem_price,'coupon_discount'=>$completed->coupon_discount,'dboy_name'=>$completed->boy_name,'dboy_phone'=>$completed->boy_phone, 'data'=>$order, 'reviews'=>$orderReviews);
        }
        }
        else{
            $data=array('data'=>[]);
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
        //$subCartId       = $request->sub_cart_id;
        $storeOrderId = $request->store_order_id;
        $cancelReason = $request->cancel_reason;

        // $userId       = 115;
        // $cartId       = 'JSSU76b9';
        // $storeOrderId = 987;
        // $cancelReason = 'Order testing cancel';

        $date_of_recharge  = carbon::now();

        # set updated data.
        $storeOrderUpdate  =  [
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
        $orderDataMain = DB::table('orders')
                              ->select('orders.*')
                              ->where('orders.cart_id', $cartId)
                              ->first();

       if($orderDataMain->delivery_type_id==2){
         $message = array('status' => '0', 'message' => 'Express delivery order item cannot be cancelled');
          return $message;
       }

        $storeOrderData = DB::table('store_orders')
                              ->leftjoin('product_varient', 'store_orders.varient_id', '=', 'product_varient.varient_id')
                              ->select('product_varient.*','store_orders.qty as qunatity','store_orders.price as store_total_price','store_orders.sub_order_cart_id as sub_cart_id')
                              ->where('store_orders.order_cart_id', $cartId)
                              ->where('store_orders.store_order_id', $storeOrderId)
                              ->first();
        //dd($storeOrderData);                      
        $removed_item_total_price = $storeOrderData->store_total_price;
        $varientid = $storeOrderData->varient_id;                      
        $sub_cart_id = $storeOrderData->sub_cart_id; 
                                                          
          //$newArray = array();
          //array_push($newArray,  $storeOrderData);

          # update status on sub store order id.        
        //dd($varientid);
        $update = DB::table('store_orders')
                    ->where('store_order_id', $storeOrderId)
                    ->update($storeOrderUpdate);                  

        $sub_orderDataMain = DB::table('store_orders')
                              ->where('store_orders.order_cart_id', $cartId)
                              ->where('store_orders.sub_order_cart_id', $sub_cart_id)
                              ->get();
        $sub_product_total_count = $sub_orderDataMain->count();
        $sub_product_cancel_count = $sub_orderDataMain->where('cancel_status', '1')->count();

                    
        $storeOrdersCount   =   DB::table('store_orders')
                                  ->where('order_cart_id', $cartId)
                                  ->where('cancel_status', 0)
                                  ->get();
                                  

        $userInfo   =   DB::table('users')
                          ->where('user_id', $userId)
                          ->first();

        //dd(count($storeOrdersCount));
        $refundAmt=0;

        if (count($storeOrdersCount) <= 0) {
          //dd('dsad');
          $user    = DB::table('orders')
                    ->where('cart_id',$cartId)
                    ->first();
          $sub_order_check    = DB::table('sub_orders')
                    ->where('cart_id',$cartId)
                    ->where('sub_order_cart_id',$sub_cart_id)
                    ->first();
          $refundAmt=$sub_order_check->paid_by_wallet;          


          // if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
          //       $newbal1 = $userInfo->wallet + $refundAmt;
          //       $returnAmt = $user->paid_by_wallet;
          //       $refundAmt=0; 

          // } else {

          //       if($user->payment_status=='success'){
          //           $newbal1 = $userInfo->wallet+$refundAmt;
          //           $returnAmt = $user->paid_by_wallet;
          //       } else {
          //          $newbal1 = $userInfo->wallet+$refundAmt;
          //          $returnAmt = 0;
          //       }
          // }

          if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod'){
            $newbal1 = $userInfo->wallet + $refundAmt;
            $returnAmt = $sub_order_check->paid_by_wallet;

          }else if($user->payment_method == 'wallet' || $user->payment_method == 'Wallet' || $user->payment_method == 'WALLET'){

            $newbal1 = $userInfo->wallet+$refundAmt;
            $refundAmt=$refundAmt;


          }else{
            $newbal1 = $userInfo->wallet + $sub_order_check->total_price;
            $refundAmt = $sub_order_check->total_price;
            $returnAmt = $sub_order_check->total_price;
          }


          $checkre =DB::table('cart_rewards')
                        ->where('cart_id',$cartId)
                        ->where('user_id',$userId)
                        ->where('reward_status','1')
                        ->first();
          if($checkre) {
              $reward_point = $checkre->rewards;
              $newRewardPoints = $userInfo->rewards - $reward_point;
              
              $inreward = DB::table('users')
                         ->where('user_id',$userId)
                         ->update(['rewards'=>$newRewardPoints]);
              
              $inUpdatereward = DB::table('cart_rewards')
                                ->where('cart_id',$cartId)
                                ->where('user_id',$userId)
                                ->update(['reward_status'=>0]);           
          }            
            
          $userwalletupdate = DB::table('users')
                              ->where('user_id',$userId)
                              ->update(['wallet'=>$newbal1]);
          if($userwalletupdate){
            DB::table('wallet_recharge_history')
                      ->insert([
                                'amount'           =>  $refundAmt,
                                'type'             =>  'Item Cancel Amount',
                                'order_store_id'    => $storeOrderId,
                                'order_cart_id'    =>  $sub_cart_id,
                                'user_id'          =>  $userId,
                                'date_of_recharge' =>  $date_of_recharge,
                                'recharge_status'  =>  'success',
                      ]);
          }                        

          $updated_at = Carbon::now();

          $sub_order_u  =   DB::table('sub_orders')
                        ->where('cart_id', $cartId)
                        ->where('sub_order_cart_id', $sub_cart_id)
                        ->update([
                          'total_price'         =>  0,
                          'rem_price'         =>  0,
                          'paid_by_wallet'    =>  0,
                          'coupon_discount'=>  0,
                          'coupon_id'=>0,
                          'delivery_charge' =>   0,
                          'bulk_order_based_discount'=> 0,
                          'online_payment_based_discount'=> 0,
                          'cancelling_reason' =>  $cancelReason,
                          'order_status'      =>  'Cancelled',
                          'updated_at'        =>  $updated_at
                        ]);   

          $order  =   DB::table('orders')
                        ->where('cart_id', $cartId)
                        ->update([
                          'rem_price'         =>  0,
                          'paid_by_wallet'    =>  0,
                          'coupon_discount'=>  0,
                          'delivery_charge' =>   0,
                          'bulk_order_based_discount'=> 0,
                          'online_payment_based_discount'=> 0,
                          'cancelling_reason' =>  $cancelReason,
                          'order_status'      =>  'Cancelled',
                          'updated_at'        =>  $updated_at
                        ]);
          //dd('single');              

        } else {
           //dd('multiple'); 
          //dd('single -'.count($storeOrdersCount));


          $user    = DB::table('orders')
                    ->where('cart_id',$cartId)
                    ->first(); 
          $sub_order_check    = DB::table('sub_orders')
                    ->where('cart_id',$cartId)
                    ->where('sub_order_cart_id',$sub_cart_id)
                    ->first();

          $subRefundAmt=$sub_order_check->paid_by_wallet;
          $subDiscountAmt = $sub_order_check->coupon_discount;
          $subOnline_payment_discount = $sub_order_check->online_payment_based_discount;
          $subBulk_order_discount = $sub_order_check->bulk_order_based_discount;
          $subRemAmount=$sub_order_check->rem_price;
          $subtotalAmount=$sub_order_check->total_price;
          $couponCode=$sub_order_check->coupon_id;
          $subDeliveryCharge=$sub_order_check->delivery_charge;
          
          $itemCancelAmount =$removed_item_total_price;

          $sub_order_check_old    = DB::table('store_orders')
          ->where('order_cart_id',$cartId)
          ->where('sub_order_cart_id',$sub_cart_id)
          ->get();
          $sub_order_check_new    = DB::table('store_orders')
          ->where('order_cart_id',$cartId)
          ->where('sub_order_cart_id',$sub_cart_id)
          ->where('cancel_status',0)
          ->get();

          $lsubOldTotal=$sub_order_check_old->sum('price');
          
          $lsubNewTotal=$sub_order_check_new->sum('price');
          //dd($lastRemAmountDeduct);
         

          if($sub_order_check->payment_method=='cod' || $sub_order_check->payment_method=='COD' ||$sub_order_check->payment_method=='Cod'){
           $newSubTotalLatest = $subtotalAmount-$subDeliveryCharge+$subDiscountAmt+$subOnline_payment_discount+$subBulk_order_discount-$itemCancelAmount;
           $getComDetail = $this->checkAllDiscountDeliveryWithoutOnline($newSubTotalLatest,$sub_order_check->store_id);
          //dd($getComDetail); 
           $returnAmontDone = $subtotalAmount-$getComDetail['latestTotalAmount'];
           $latestDisCharges = 0;
           if($couponCode!='' && $couponCode!=0){
            //dd($couponCode);
            $couponCheckD = DB::table('coupon')->where('coupon_code','=',$couponCode)->first();
            if($couponCheckD->cart_value<=$newSubTotalLatest){
              
              if($couponCheckD->type=='price'){
                $latestDisCharges =$couponCheckD->amount;
              }else{
                $latestDisCharges =$getComDetail['totalAmount']*$couponCheckD->amount/100;
              }
              //dd($latestDisCharges);
              $returnAmontDone = $subtotalAmount-$getComDetail['latestTotalAmount']+$latestDisCharges;
            }
           }
          if($sub_product_total_count==$sub_product_cancel_count){
            $returnAmontDone = $subtotalAmount;
          }
          //dd($returnAmontDone);

        }else if($sub_order_check->payment_method=='wallet' || $sub_order_check->payment_method=='Wallet'){
          
           $newSubTotalLatest = $subtotalAmount-$subDeliveryCharge+$subDiscountAmt+$subOnline_payment_discount+$subBulk_order_discount-$itemCancelAmount;
           //dd($newSubTotalLatest);
           $getComDetail = $this->checkAllDiscountDeliveryWithoutOnline($newSubTotalLatest,$sub_order_check->store_id);
           //dd($getComDetail); 
           $returnAmontDone = $subtotalAmount-$getComDetail['latestTotalAmount'];
           //dd($returnAmontDone);
           $latestDisCharges = 0;
           if($couponCode!='' && $couponCode!=0){
            //dd($couponCode);
            $couponCheckD = DB::table('coupon')->where('coupon_code','=',$couponCode)->first();
            if($couponCheckD->cart_value<=$newSubTotalLatest){
              
              if($couponCheckD->type=='price'){
                $latestDisCharges =$couponCheckD->amount;
              }else{
                $latestDisCharges =$getComDetail['totalAmount']*$couponCheckD->amount/100;
              }
              //dd($latestDisCharges);
              $returnAmontDone = $subtotalAmount-$getComDetail['latestTotalAmount']+$latestDisCharges;
            }
           }
          if($sub_product_total_count==$sub_product_cancel_count){
            $returnAmontDone = $subtotalAmount;
          }
          //dd($returnAmontDone);

        }else{
          $newSubTotalLatest = $subtotalAmount-$subDeliveryCharge+$subRefundAmt+$subDiscountAmt+$subOnline_payment_discount+$subBulk_order_discount-$itemCancelAmount;
          //dd($subtotalAmount.' @ '.$subDeliveryCharge.' @ '.$subRefundAmt.' @ '.$subDiscountAmt.' @ '.$subOnline_payment_discount.' @ '.$subBulk_order_discount.' @ '.$itemCancelAmount);
          //dd($newSubTotalLatest);
          $getComDetail = $this->checkAllDiscountDelivery($newSubTotalLatest,$sub_order_check->store_id);
          //dd($getComDetail);
          $returnAmontDone = $subtotalAmount-$getComDetail['latestTotalAmount'];

          $latestDisCharges = 0;
           if($couponCode!='' && $couponCode!=0){
            //dd($couponCode);
            $couponCheckD = DB::table('coupon')->where('coupon_code','=',$couponCode)->first();
            if($couponCheckD->cart_value<=$newSubTotalLatest){
              
              if($couponCheckD->type=='price'){
                $latestDisCharges =$couponCheckD->amount;
              }else{
                $latestDisCharges =$getComDetail['totalAmount']*$couponCheckD->amount/100;
              }
              //dd($latestDisCharges);
              $returnAmontDone = $subtotalAmount-$getComDetail['latestTotalAmount']+$latestDisCharges;
            }
           }

          if($sub_product_total_count==$sub_product_cancel_count){
            $returnAmontDone = $subtotalAmount;
          }
          //dd($returnAmontDone);
        }
        //dd($returnAmontDone);


        if($subRefundAmt>=$returnAmontDone){
            $refundAmt = $returnAmontDone;
            $lastRemAmountDeduct= 0;
        }else{
          if($subRefundAmt==0){
            $refundAmt =$subRefundAmt;
            $lastRemAmountDeduct= $returnAmontDone;
          }else{
            $refundAmt =$subRefundAmt;
            $lastRemAmountDeduct= $returnAmontDone-$subRefundAmt;
          }    
        }

        if($sub_order_check->payment_method!='cod' || $sub_order_check->payment_method!='COD' ||$sub_order_check->payment_method!='Cod'||$sub_order_check->payment_method!='wallet' || $sub_order_check->payment_method!='Wallet'){
          $refundAmt =$returnAmontDone;
          $lastRemAmountDeduct=0;

        }
            
        if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
            $newbal1 = $userInfo->wallet; 
        }else if($user->payment_method == 'wallet' || $user->payment_method == 'Wallet' || $user->payment_method == 'WALLET') {
            $newbal1 = $userInfo->wallet+$refundAmt;
        }else{
          $refundAmt = $returnAmontDone;
          $newbal1 = $userInfo->wallet + $refundAmt;
        }

        //dd($refundAmt.' @ '.$newbal1);

            
            
            $userwalletupdate = DB::table('users')
                                    ->where('user_id',$userId)
                                    ->update(['wallet'=>$newbal1]);
            if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
              $walletAddingAmount = 0;
            }else{
              $walletAddingAmount = $refundAmt;
            }                         
            if($userwalletupdate){
              DB::table('wallet_recharge_history')
                            ->insert([
                                      'amount'        =>  $walletAddingAmount,
                                      'type'          =>  'Item Cancel Amount',
                                      'order_store_id'    => $storeOrderId,
                                      'order_cart_id'    =>  $sub_cart_id,
                                      'user_id'       =>  $userId,
                                      'date_of_recharge' =>  $date_of_recharge,
                                      'recharge_status'  =>  'success',
                                    ]);

            }

            $checkre =DB::table('cart_rewards')
                        ->where('cart_id',$cartId)
                        ->where('user_id',$userId)
                        ->where('reward_status','1')
                        ->first();
            //dd($checkre);            
            if($checkre) {
               $reward_point = $checkre->rewards;
               $newRewardPoints = $userInfo->rewards - $reward_point;
              
               $inreward = DB::table('users')
                         ->where('user_id',$userId)
                         ->update(['rewards'=>$newRewardPoints]);
               $inUpdatereward = DB::table('cart_rewards')
                         ->where('cart_id',$cartId)
                        ->where('user_id',$userId)
                         ->update(['reward_status'=>0]);          
            }
            //dd($checkre);
            //dd($refundAmt.'  '.$subRemAmount.' '.$storeOrderData->store_total_price);
            if ($update) {

              
              $updated_at = Carbon::now();

            if($sub_product_total_count==$sub_product_cancel_count){
                //dd('last cancel avail');
              $newSubTotal = $sub_order_check->total_price-$refundAmt;

            if($sub_order_check->payment_method=='wallet' || $sub_order_check->payment_method=='Wallet' || $sub_order_check->payment_method=='WALLET'){
              
              $latestCouponCode = $sub_order_check->coupon_id;
               if($latestDisCharges==0){
                $latestCouponCode = 0;
               }

              $sub_order = DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cart_id)
              ->update([
              'total_price' =>   0,
              'rem_price' =>     0,
              'paid_by_wallet' => 0,
              'updated_at'  =>  $updated_at,
              'coupon_discount'=>  0,
              'coupon_id'=>0,
              'delivery_charge'=> 0,
              'bulk_order_based_discount'=> 0,
              'online_payment_based_discount'=> 0,
              'order_status'  =>  'Cancelled',
              'cancelling_reason'  =>  $cancelReason,
              ]);

              $orderDeliveryCharge=  $user->delivery_charge-$sub_order_check->delivery_charge;
              $orderCouponDiscountCharge=  $user->coupon_discount-$sub_order_check->coupon_discount;
              $orderBulkDis=  $user->bulk_order_based_discount-$sub_order_check->bulk_order_based_discount;
              $orderOnlineDis=  0;

              $order  =   DB::table('orders')
              ->where('cart_id', $cartId)
              ->update([
              'total_price' =>   $user->total_price-$refundAmt,
              'rem_price' =>     0,
              'coupon_discount'=>  $orderCouponDiscountCharge,
              'delivery_charge'=>$orderDeliveryCharge,
              'bulk_order_based_discount'=> $orderBulkDis,
              'online_payment_based_discount'=> $orderOnlineDis,
              'paid_by_wallet' =>  $user->paid_by_wallet-$refundAmt,
              'updated_at'  =>  $updated_at
              ]);

            }else if($sub_order_check->payment_method=='cod' || $sub_order_check->payment_method=='COD' ||$sub_order_check->payment_method=='Cod'){
               $latestCouponCode = $sub_order_check->coupon_id;
               if($latestDisCharges==0){
                $latestCouponCode = 0;
               }

              $sub_order = DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cart_id)
              ->update([
              'total_price' =>   0,
              'rem_price' =>     0,
              'paid_by_wallet' => 0,
              'updated_at'  =>  $updated_at,
              'coupon_discount'=>  0,
              'coupon_id'=>0,
              'delivery_charge'=> 0,
              'bulk_order_based_discount'=> 0,
              'online_payment_based_discount'=> 0,
              'order_status'  =>  'Cancelled',
              'cancelling_reason'  =>  $cancelReason,
              ]);

              $orderDeliveryCharge=  $user->delivery_charge-$sub_order_check->delivery_charge;
              $orderCouponDiscountCharge=  $user->coupon_discount-$sub_order_check->coupon_discount;
              $orderBulkDis=  $user->bulk_order_based_discount-$sub_order_check->bulk_order_based_discount;
              $orderOnlineDis=  0;

              $order  =   DB::table('orders')
              ->where('cart_id', $cartId)
              ->update([
              'total_price' =>   $user->total_price-$refundAmt,
              'rem_price' =>     $user->rem_price-$refundAmt,
              'coupon_discount'=>  $orderCouponDiscountCharge,
              'delivery_charge'=>$orderDeliveryCharge,
              'bulk_order_based_discount'=> $orderBulkDis,
              'online_payment_based_discount'=> $orderOnlineDis,
              'paid_by_wallet' =>  0,
              'updated_at'  =>  $updated_at
              ]);


            }else{

              $latestCouponCode = $sub_order_check->coupon_id;
               if($latestDisCharges==0){
                $latestCouponCode = 0;
               }
              
               $sub_order = DB::table('sub_orders')
               ->where('cart_id', $cartId)
               ->where('sub_order_cart_id', $sub_cart_id)
               ->update([
                'total_price' => 0,
                'rem_price' =>     0,
                'paid_by_wallet' => 0,
                'updated_at'  =>  $updated_at,
                'delivery_charge'=> 0,
                'bulk_order_based_discount'=> 0,
                'online_payment_based_discount'=> 0, 
                'order_status'  =>  'Cancelled',
                'cancelling_reason'  =>  $cancelReason,
               ]);

               $orderDeliveryCharge =  $user->delivery_charge-$sub_order_check->delivery_charge;
               $orderCouponDiscountCharge=  $user->coupon_discount-$sub_order_check->coupon_discount;
               $orderBulkDis=  $user->bulk_order_based_discount-$sub_order_check->bulk_order_based_discount;
               $orderOnlineDis=  $user->online_payment_based_discount-$sub_order_check->online_payment_based_discount;;

               $order  =   DB::table('orders')
               ->where('cart_id', $cartId)
               ->update([
               'total_price' =>   $user->total_price-$refundAmt,
               'rem_price' =>     0,
               'paid_by_wallet' => 0,
               'coupon_discount'=>  $orderCouponDiscountCharge,
               'delivery_charge'=>$orderDeliveryCharge,
               'bulk_order_based_discount'=> $orderBulkDis,
               'online_payment_based_discount'=> $orderOnlineDis,
               'updated_at'  =>  $updated_at
              ]);
              //dd('dsads');
              
            }


                  $mainOrderLast    = DB::table('orders')
                    ->where('cart_id',$cartId)
                    ->first();
                  $calLastTotal = $mainOrderLast->total_price-$mainOrderLast->delivery_charge;
                  $lastDeliveryCharge= 0;

                  
                                
              }else{
                //dd('one by one cancel avail');
                $newSubTotal = $sub_order_check->total_price-$refundAmt;
                //dd($newSubTotal);

                if($sub_order_check->payment_method=='wallet' || $sub_order_check->payment_method=='Wallet' || $sub_order_check->payment_method=='WALLET' || $sub_order_check->payment_method=='cod' || $sub_order_check->payment_method=='Cod' || $sub_order_check->payment_method=='COD'){ 
                  $getComDetail = $this->checkAllDiscountDeliveryWithoutOnline($newSubTotalLatest,$sub_order_check->store_id);
                }else{
                  $getComDetail = $this->checkAllDiscountDelivery($newSubTotalLatest,$sub_order_check->store_id);
                } 

              if($sub_order_check->payment_method=='wallet' || $sub_order_check->payment_method=='Wallet' || $sub_order_check->payment_method=='WALLET'){
               $latestCouponCode = $sub_order_check->coupon_id;
               if($latestDisCharges==0){
                $latestCouponCode = 0;
               }

              $sub_order  =   DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cart_id)
              ->update([
                'total_price' =>   $sub_order_check->total_price-$refundAmt,
                'rem_price' =>     0,
                'coupon_discount'=>  $latestDisCharges,
                'coupon_id'=>$latestCouponCode,
                'delivery_charge'=> $getComDetail['deliveryCharge'],
                'bulk_order_based_discount'=> $getComDetail['bulkDiscount'],
                'online_payment_based_discount'=> $getComDetail['onlineDiscount'],
                'paid_by_wallet' => $sub_order_check->paid_by_wallet-$refundAmt,
                'updated_at'  =>  $updated_at
              ]);
              
              $orderDeliveryCharge=  $user->delivery_charge-$sub_order_check->delivery_charge+$getComDetail['deliveryCharge'];
              $orderCouponDiscountCharge=  $user->coupon_discount-$sub_order_check->coupon_discount+$latestDisCharges;
              $orderBulkDis=  $user->bulk_order_based_discount-$sub_order_check->bulk_order_based_discount+$getComDetail['bulkDiscount'];
              $orderOnlineDis=  $user->online_payment_based_discount-$sub_order_check->online_payment_based_discount+$getComDetail['onlineDiscount'];

              $order  =   DB::table('orders')
              ->where('cart_id', $cartId)
              ->update([
                'total_price' =>   $user->total_price-$refundAmt,
                'rem_price' =>     0,
                'coupon_discount'=>  $orderCouponDiscountCharge,
                'delivery_charge' =>   $orderDeliveryCharge,
                'bulk_order_based_discount'=> $orderBulkDis,
                'online_payment_based_discount'=> $orderOnlineDis,
                'paid_by_wallet' =>  $user->paid_by_wallet-$refundAmt,
                'updated_at'  =>  $updated_at
              ]);


            }else if($sub_order_check->payment_method=='cod' || $sub_order_check->payment_method=='COD' ||$sub_order_check->payment_method=='Cod'){
               $latestCouponCode = $sub_order_check->coupon_id;
               if($latestDisCharges==0){
                $latestCouponCode = 0;
               }

              $sub_order  =   DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cart_id)
              ->update([
                'total_price' =>   $sub_order_check->total_price-$refundAmt,
                'rem_price' =>     $sub_order_check->rem_price-$refundAmt,
                'coupon_discount'=>  $latestDisCharges,
                'coupon_id'=>$latestCouponCode,
                'delivery_charge'=> $getComDetail['deliveryCharge'],
                'bulk_order_based_discount'=> $getComDetail['bulkDiscount'],
                'online_payment_based_discount'=> $getComDetail['onlineDiscount'],
                'paid_by_wallet' => 0,
                'updated_at'  =>  $updated_at
              ]);
              
              $orderDeliveryCharge = $user->delivery_charge-$sub_order_check->delivery_charge+$getComDetail['deliveryCharge'];
              $orderCouponDiscountCharge=  $user->coupon_discount-$sub_order_check->coupon_discount+$latestDisCharges;
              $orderBulkDis=  $user->bulk_order_based_discount-$sub_order_check->bulk_order_based_discount+$getComDetail['bulkDiscount'];
              $orderOnlineDis=  $user->online_payment_based_discount-$sub_order_check->online_payment_based_discount+$getComDetail['onlineDiscount'];

              $order  =   DB::table('orders')
              ->where('cart_id', $cartId)
              ->update([
                'total_price' =>   $user->total_price-$refundAmt,
                'rem_price' =>     $user->rem_price-$refundAmt,
                'coupon_discount'=>  $orderCouponDiscountCharge,
                'delivery_charge' =>   $orderDeliveryCharge,
                'bulk_order_based_discount'=> $orderBulkDis,
                'online_payment_based_discount'=> $orderOnlineDis,
                'paid_by_wallet' =>  0,
                'updated_at'  =>  $updated_at
              ]);
              

            }else{

              $latestCouponCode = $sub_order_check->coupon_id;
               if($latestDisCharges==0){
                $latestCouponCode = 0;
               }

              $sub_order  =   DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cart_id)
              ->update([
                'total_price' =>   $sub_order_check->total_price-$refundAmt,
                'rem_price' =>     0,
                'coupon_discount'=>  $latestDisCharges,
                'coupon_id'=>$latestCouponCode,

                'delivery_charge'=> $getComDetail['deliveryCharge'],
                'bulk_order_based_discount'=> $getComDetail['bulkDiscount'],
                'online_payment_based_discount'=> $getComDetail['onlineDiscount'],
                'paid_by_wallet' => 0,
                'updated_at'  =>  $updated_at
              ]);
              $orderDeliveryCharge = $user->delivery_charge-$sub_order_check->delivery_charge+$getComDetail['deliveryCharge'];
              $orderCouponDiscountCharge=  $user->coupon_discount-$sub_order_check->coupon_discount+$latestDisCharges;
              $orderBulkDis=  $user->bulk_order_based_discount-$sub_order_check->bulk_order_based_discount+$getComDetail['bulkDiscount'];
              $orderOnlineDis=  $user->online_payment_based_discount-$sub_order_check->online_payment_based_discount+$getComDetail['onlineDiscount'];

              $order  =   DB::table('orders')
              ->where('cart_id', $cartId)
              ->update([
                'total_price' =>   $user->total_price-$returnAmontDone,
                'rem_price' =>     0,
                'coupon_discount'=>  $orderCouponDiscountCharge,
                'delivery_charge' =>   $orderDeliveryCharge,
                'bulk_order_based_discount'=> $orderBulkDis,
                'online_payment_based_discount'=> $orderOnlineDis,
                'paid_by_wallet' =>  0,
                'updated_at'  =>  $updated_at
              ]);
              //dd('complete');

              

            }  

               
                          
 

                  

            }

              

              //dd($refundAmt.'  '.$lastRemAmountDeduct.' total-'.$storeOrderData->price);   

              

              
            }


          /*$price = $userInfo->wallet + $storeOrderData->store_total_price;


          $userInfo   =   DB::table('users')
                            ->where('user_id', $userId)
                            ->update([
                                      'wallet'        =>  $price,
                                    ]);

                        DB::table('wallet_recharge_history')
                            ->update([
                                      'amount'        =>  $storeOrderData->store_total_price,
                                      'type'          =>  'Order Cancel Amount',
                                      'order_cart_id' =>  $cartId,
                                      'user_id'       =>  $userId,
                                      'date_of_recharge' =>  $date_of_recharge,
                                      'recharge_status'  =>  'success',
                                    ]);*/
        }
                    
        if ($update) {
            
          $message = array('status' => '1', 'message' => 'Order Product Cancel Successfully');

        } else {
            
          $message = array('status' => '0', 'message' => 'Order Product Not Canceled');
          
        }
        return $message;


     
        /*     $cart_id = $request->cart_id;
        $user    = DB::table('orders')
                    ->where('cart_id',$cart_id)
                    ->first();
              
        $user_id1   = $user->user_id;
        $userwa1    = DB::table('users')
                        ->where('user_id',$user_id1)
                        ->first();
                     
        $reason         = $request->reason;
        $order_status   = 'Cancelled';
        $updated_at     = Carbon::now();
        $order  =   DB::table('orders')
                        ->where('cart_id', $cart_id)
                        ->update([
                            'cancelling_reason' =>  $reason,
                            'order_status'      =>  $order_status,
                            'updated_at'        =>  $updated_at
                        ]);

        if($order){
            if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
                $newbal1 = $userwa1->wallet + $user->paid_by_wallet;
            } else {
                if($user->payment_status=='success'){
                    $newbal1 = $userwa1->wallet + $user->rem_price + $user->paid_by_wallet;
                } else {
                   $newbal1 = $userwa1->wallet;
                }
            }
            
            $userwalletupdate = DB::table('users')
                                    ->where('user_id',$user_id1)
                                    ->update(['wallet'=>$newbal1]);
                                 
          $message = array( 'status' => '1', 'message' => 'Order Cancelled', 'data' => $order );
          return $message;
          
        } else {
          $message = array('status' => '0', 'message' => 'Something Went Wrong', 'data' => []);
          return $message;
        }*/


    }

    //added code check all discount & delivery without online charges
    public function checkAllDiscountDeliveryWithoutOnline($newtotalAmount, $store_id){

    $newDelcharge=DB::table('freedeliverycart_by_store')
          ->where('delivery_store_id',$store_id) 
          ->where('min_cart_value','>=',$newtotalAmount)
          ->first();

          if($newDelcharge){
            $dCharge = $newDelcharge->del_charge;
          }else{
            $dCharge = 0;
          }   

          $newBulkDiscount=DB::table('bulk_order_discount')
          ->where('bulk_order_min_amount','<=',$newtotalAmount)
          ->where('bulk_order_max_amount','>=',$newtotalAmount)
          ->first(); 

          if($newBulkDiscount){
            if($newBulkDiscount->bulk_order_discount_type=='percentage'){
              $bDiscount = $newtotalAmount*$newBulkDiscount->bulk_order_discount/100;  
            }else{
              $bDiscount =$newBulkDiscount->bulk_order_discount;
            }  
          }else{
            $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
            $maxBDiscountData = DB::table('bulk_order_discount')
            ->where('bulk_order_max_amount',$maxValueAmountLimit)
            ->first();
            if($newtotalAmount>$maxValueAmountLimit){
              if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                $bDiscount = $newtotalAmount*$maxBDiscountData->bulk_order_discount/100;  
              }else{
                $bDiscount =$maxBDiscountData->bulk_order_discount;
              }
            }else{
              $bDiscount = 0;
            }
          }

         $latestTotalAmount= $newtotalAmount+ $dCharge-$bDiscount;

         $message = array('totalAmount'=>$newtotalAmount,'latestTotalAmount'=>$latestTotalAmount,'deliveryCharge'=>$dCharge,'bulkDiscount'=>$bDiscount,'onlineDiscount'=>0);
            return $message; 
          
      }

      //end code check all discount & delivery charges without online


   //added code check all discount & delivery charges
   public function checkAllDiscountDelivery($newtotalAmount, $store_id){

    $newDelcharge=DB::table('freedeliverycart_by_store')
          ->where('delivery_store_id',$store_id) 
          ->where('min_cart_value','>=',$newtotalAmount)
          ->first();

          if($newDelcharge){
            $dCharge = $newDelcharge->del_charge;
          }else{
            $dCharge = 0;
          }   

          $newBulkDiscount=DB::table('bulk_order_discount')
          ->where('bulk_order_min_amount','<=',$newtotalAmount)
          ->where('bulk_order_max_amount','>=',$newtotalAmount)
          ->first(); 

          if($newBulkDiscount){
            if($newBulkDiscount->bulk_order_discount_type=='percentage'){
              $bDiscount = $newtotalAmount*$newBulkDiscount->bulk_order_discount/100;  
            }else{
              $bDiscount =$newBulkDiscount->bulk_order_discount;
            }  
          }else{
            $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
            $maxBDiscountData = DB::table('bulk_order_discount')
            ->where('bulk_order_max_amount',$maxValueAmountLimit)
            ->first();
            if($newtotalAmount>$maxValueAmountLimit){
              if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                $bDiscount = $newtotalAmount*$maxBDiscountData->bulk_order_discount/100;  
              }else{
                $bDiscount =$maxBDiscountData->bulk_order_discount;
              }
            }else{
              $bDiscount = 0;
            }
          }

          $newOnlineDiscount=DB::table('online_payment_discount') 
          ->where('online_payment_min_amount','<=',$newtotalAmount-$bDiscount+$dCharge)
          ->where('online_payment_max_amount','>=',$newtotalAmount-$bDiscount+$dCharge)
          ->first();
          //dd($newOnlineDiscount); 

          if($newOnlineDiscount){
            if($newOnlineDiscount->online_payment_discount_type=='percentage'){
              $oDiscount = ($newtotalAmount-$bDiscount+$dCharge)*$newOnlineDiscount->online_payment_discount/100;  
            }else{
              $oDiscount =$newOnlineDiscount->online_payment_discount;
            }  
          }else{
            $maxValueAmountLimit = DB::table('online_payment_discount')->max('online_payment_max_amount');
            $maxOnlineDiscountData = DB::table('online_payment_discount')
            ->where('online_payment_max_amount',$maxValueAmountLimit)
            ->first();
            if($newtotalAmount-$bDiscount+$dCharge>$maxValueAmountLimit){
              if($maxOnlineDiscountData->online_payment_discount_type=='percentage'){
                $oDiscount = ($newtotalAmount-$bDiscount+$dCharge)*$maxOnlineDiscountData->online_payment_discount/100;  
              }else{
                $oDiscount =$maxOnlineDiscountData->online_payment_discount;
              }
            }else{
              $oDiscount = 0;
            }
          }
         $latestTotalAmount= $newtotalAmount+ $dCharge-$bDiscount-$oDiscount;

         $message = array('totalAmount'=>$newtotalAmount,'latestTotalAmount'=>$latestTotalAmount,'deliveryCharge'=>$dCharge,'bulkDiscount'=>$bDiscount,'onlineDiscount'=>$oDiscount);
            return $message; 
          
      }

      //end code check all discount & delivery charges
}