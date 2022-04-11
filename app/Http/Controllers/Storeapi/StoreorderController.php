<?php

namespace App\Http\Controllers\Storeapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class StoreorderController extends Controller
{
    public function storeunassigned(Request $request)
    {
         $store_id = $request->store_id;
         //$store_id = '3';

         $store= DB::table('store')
    	 		   ->where('store_id',$store_id)
    	 		   ->first();
    	 		   
        $ord =DB::table('sub_orders')
                 ->join('users', 'sub_orders.user_id', '=','users.user_id')
                 ->leftJoin('address','sub_orders.address_id','=','address.address_id')
                 ->select('sub_orders.cart_id',
                           'sub_orders.sub_order_cart_id as sub_cart_id',
                            'users.user_name', 
                            'users.user_phone', 
                            'sub_orders.delivery_date', 
                            'sub_orders.total_price',
                            'sub_orders.coupon_discount',
                            'sub_orders.bulk_order_based_discount',
                            'sub_orders.online_payment_based_discount',
                            'sub_orders.delivery_charge',
                            'sub_orders.rem_price',
                            'sub_orders.payment_status',
                            'sub_orders.order_status',
                            'sub_orders.payment_method',
                            'sub_orders.sub_order_id',
                            'sub_orders.dboy_id',
                            'sub_orders.store_id',
                            'users.user_phone',
                            'address.*')
                 ->where('sub_orders.store_id',$store_id)
                 ->where('payment_method', '!=', NULL)
                 ->orderBy('sub_orders.sub_order_id','DESC')
                 ->where('sub_orders.order_status','!=', 'Completed')
                 ->where('sub_orders.order_status','!=', 'completed')
                 ->where('order_status','!=', 'cancelled')
                 ->where('order_status', '!=', 'Cancelled')
                 ->where('sub_orders.dboy_id',0)
                 ->get();
                 
        if(count($ord)>0){
            foreach($ord as $ords){
                $cart_id = $ords->cart_id;
                $sub_cart_id = $ords->sub_cart_id;    
                $details  =   DB::table('store_orders')
                	               ->where('order_cart_id',$cart_id)
                                 ->where('sub_order_cart_id',$sub_cart_id)
                                 ->where('cancel_status','!=', '1')
                	               ->where('store_approval',1)
                	               ->get();
                     //dd($details);             
                  
        
                $detailsOutput = [];
                foreach ($details as $key => $detailsValue) {
                    $detailsOutput[]  = [
        
                                            "store_order_id"  => (string)$detailsValue->store_order_id,
                                            "store_id"  => (string)$ords->store_id,
                                            "product_name"    => (string)$detailsValue->product_name,
                                            "varient_image"   => (string)$detailsValue->varient_image,
                                            "quantity"        => (string)$detailsValue->quantity,
                                            "unit"            => (string)$detailsValue->unit,
                                            "cancel_status"   => (string)$detailsValue->cancel_status,
                                            "varient_id"      => (string)$detailsValue->varient_id,
                                            "qty"             => (string)$detailsValue->qty,
                                            "price"           => (string)($detailsValue->price) ? round($detailsValue->price) : '',
                                            "total_mrp"       => (string)($detailsValue->total_mrp) ? round($detailsValue->total_mrp) : '',
                                            "order_cart_id"   => (string)$detailsValue->order_cart_id,
                                            "sub_order_cart_id"   => (string)$detailsValue->sub_order_cart_id,
                                            "order_date"      => (string)$detailsValue->order_date,
                                            "store_approval"  => (string)$detailsValue->store_approval
                                        ];
                }
                if($ords->payment_method=='wallet'){
                  $ords->payment_method='Wallet';
                }
            
                $data[]  =  array(
                                'user_address'   =>   $ords->house_no.','.$ords->society.','.$ords->city.','.$ords->landmark.','.$ords->state.','.$ords->pincode, 
                                'cart_id'         =>  $cart_id,
                                'sub_cart_id'     =>  $sub_cart_id,
                                "store_id"        => (string)$ords->store_id,
                                'dboy_id'         =>  $ords->dboy_id,
                                'user_name'       =>  $ords->user_name, 
                                'user_phone'      =>  $ords->user_phone, 
                                'remaining_price' =>  (string)$ords->rem_price ? round($ords->rem_price,2) : '',
                                'order_price'     =>  (string)$ords->total_price ? round($ords->total_price,2) : '',
                                'coupon_discount'   =>  (string)$ords->coupon_discount ? round($ords->coupon_discount,2) : '',
                                'bulk_order_discount'=>  (string)$ords->bulk_order_based_discount ? round($ords->bulk_order_based_discount,2) : '',
                                'online_payment_discount'  =>  (string)$ords->online_payment_based_discount ? round($ords->online_payment_based_discount,2) : '',
                                'delivery_date'   =>  $ords->delivery_date,
                                'payment_mode'    =>  $ords->payment_method, 
                                'order_status'    =>  $ords->order_status, 
                                'customer_phone'  =>  $ords->user_phone,
                                'order_details'   =>  $detailsOutput
                              ); 
            }
        }
        else{
            //$data[]=array('order_details'=>'No Orders Found');
           $data[]=array('order_details'=>[],'message'=>'No Orders Found');
        }
        return $data;     
    }          
    
    
    public function storeassigned(Request $request)
    {
        $store_id = $request->store_id;
        //$store_id = '3';
        $store    = DB::table('store')
              	 		   ->where('store_id',$store_id)
              	 		   ->first();
    	 		   
        $ord =DB::table('sub_orders')
               ->join('users', 'sub_orders.user_id', '=','users.user_id')
               ->leftJoin('address','sub_orders.address_id','=','address.address_id')
               ->join('delivery_boy', 'sub_orders.dboy_id', '=','delivery_boy.dboy_id')
               ->select('sub_orders.cart_id','sub_orders.sub_order_cart_id as sub_cart_id','sub_orders.store_id','users.user_name', 'users.user_phone', 'sub_orders.delivery_date', 'sub_orders.total_price','sub_orders.delivery_charge','sub_orders.rem_price','sub_orders.coupon_discount','sub_orders.bulk_order_based_discount','sub_orders.online_payment_based_discount','sub_orders.payment_status','delivery_boy.boy_name','delivery_boy.boy_phone','sub_orders.time_slot','sub_orders.order_status','sub_orders.payment_method','users.user_phone','address.*')
               ->where('sub_orders.store_id',$store_id)
               ->where('payment_method', '!=', NULL)
               ->orderBy('sub_orders.sub_order_id','DESC')
               ->where('sub_orders.order_status','!=', 'completed')
               ->where('sub_orders.order_status','!=', 'Completed')
               ->where('order_status','!=', 'cancelled')
               ->where('order_status', '!=', 'Cancelled')
               ->where('sub_orders.dboy_id','!=',0)
               ->get();
         //dd($ord);     
       
        if(count($ord)>0){
          foreach($ord as $ords){
              $cart_id  =   $ords->cart_id;
              $sub_cart_id  =   $ords->sub_cart_id;    
              $details  =   DB::table('store_orders')
                	               ->where('order_cart_id',$cart_id)
                                 ->where('sub_order_cart_id',$sub_cart_id)
                                 ->where('cancel_status','!=', '1')
                	               ->where('store_approval',1)
                	               ->get(); 

              $detailsOutput = [];
             foreach ($details as $key => $detailsValue) {
                $detailsOutput[]  = [

                                      "store_order_id"  => (string)$detailsValue->store_order_id,
                                      "product_name"    => (string)$detailsValue->product_name,
                                      "varient_image"   => (string)$detailsValue->varient_image,
                                      "quantity"        => (string)$detailsValue->quantity,
                                      "cancel_status"   => (string)$detailsValue->cancel_status,
                                      "unit"            => (string)$detailsValue->unit,
                                      "varient_id"      => (string)$detailsValue->varient_id,
                                      "qty"             => (string)$detailsValue->qty,
                                      "price"           => (string)($detailsValue->price) ? round($detailsValue->price) : '',
                                      "total_mrp"       => (string)($detailsValue->total_mrp) ? round($detailsValue->total_mrp) : '',
                                      "order_cart_id"   => (string)$detailsValue->order_cart_id,
                                      "sub_order_cart_id"   => (string)$detailsValue->sub_order_cart_id,
                                      "order_date"      => (string)$detailsValue->order_date,
                                      "store_approval"  => (string)$detailsValue->store_approval,
                                      "store_id"        => (string)$ords->store_id
                                  ];
             }
            
              $data[]=array(

                            'user_address' => $ords->house_no.','.$ords->society.','.$ords->city.','.$ords->landmark.','.$ords->state.','.$ords->pincode,  
                            'cart_id'             =>  $cart_id,
                            'sub_cart_id'             =>  $sub_cart_id,
                            'user_name'           =>  $ords->user_name, 
                            'user_phone'          =>  $ords->user_phone, 
                            'remaining_price'     =>  (string)$ords->rem_price ? round($ords->rem_price,2) : '',
                            'order_price'         =>  (string)$ords->total_price ? round($ords->total_price,2) : '',
                            'coupon_discount'   =>  (string)$ords->coupon_discount ? round($ords->coupon_discount,2) : '',
                            'bulk_order_discount'=>  (string)$ords->bulk_order_based_discount ? round($ords->bulk_order_based_discount,2) : '',
                            'online_payment_discount'  =>  (string)$ords->online_payment_based_discount ? round($ords->online_payment_based_discount,2) : '',
                            'delivery_boy_name'   =>  $ords->boy_name,
                            'delivery_boy_phone'  =>  $ords->boy_phone,
                            'delivery_date'       =>  $ords->delivery_date,
                            'time_slot'           =>  $ords->time_slot,
                            'payment_mode'        =>  $ords->payment_method, 
                            'order_status'        =>  $ords->order_status, 
                            'customer_phone'      =>  $ords->user_phone,
                            'order_details'       =>  $detailsOutput
                          ); 
          }
        } else {
          //$data[]=array('order_details'=>'No Orders Found');
           $data=array();
           $data[]=array('order_details'=>$data,'message'=>'No Orders Found');
        }

        return $data;     
    }


    public function productcancelled(Request $request)
    {
       $storeOrderId= $request->store_order_id;
       $storeId= $request->store_id;

       // $storeOrderId=155;
       // $storeId= 3;

       $date_of_recharge  = carbon::now();
       $cancelReason = 'Item cancel by store';

       $storeOrderDataByStore = DB::table('store_orders')
                              ->where('store_orders.store_order_id', $storeOrderId)
                              ->first();
       $cartId       = $storeOrderDataByStore->order_cart_id; 
       $orderDataMain = DB::table('orders')
                              ->select('orders.*')
                              ->where('orders.cart_id', $cartId)
                              ->first(); 
       $userId       = $orderDataMain->user_id; 

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
                                                  
      //dd($orderDataMain); 


        $storeOrderData = DB::table('store_orders')
                              ->leftjoin('product_varient', 'store_orders.varient_id', '=', 'product_varient.varient_id')
                              ->select('product_varient.*','store_orders.qty as qunatity','store_orders.price as store_total_price','store_orders.sub_order_cart_id as sub_cart_id')
                              ->where('store_orders.order_cart_id', $cartId)
                              ->where('store_orders.store_order_id', $storeOrderId)
                              ->first();
        $sub_cart_id = $storeOrderData->sub_cart_id; 
                                                          
          //$newArray = array();
          //array_push($newArray,  $storeOrderData);

          # update status on sub store order id.        

        $update = DB::table('store_orders')
                    ->where('order_cart_id', $cartId)
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


          if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
                $newbal1 = $userInfo->wallet + $refundAmt;
                $returnAmt = $user->paid_by_wallet;
                $refundAmt=0; 

          } else {

                if($user->payment_status=='success'){
                    //$newbal1 = $userInfo->wallet + $user->rem_price + $user->paid_by_wallet;
                    $newbal1 = $userInfo->wallet+$refundAmt;
                    $returnAmt = $user->paid_by_wallet;
                } else {
                   $newbal1 = $userInfo->wallet+$refundAmt;
                   $returnAmt = 0;
                }
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
                      ->update([
                                'amount'           =>  $refundAmt,
                                'type'             =>  'Order Cancel Amount',
                                'order_cart_id'    =>  $cartId,
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
                            'rem_price'         =>  $sub_order_check->rem_price+$sub_order_check->paid_by_wallet,
                            'paid_by_wallet'    =>  0,
                            'cancelling_reason' =>  $cancelReason,
                            'cancel_by_store'   =>  1,
                            'order_status'      =>  'Cancelled',
                            'updated_at'        =>  $updated_at
                        ]);   

          $order  =   DB::table('orders')
                        ->where('cart_id', $cartId)
                        ->update([
                            'rem_price'         =>  $user->rem_price+$sub_order_check->paid_by_wallet,
                            'paid_by_wallet'    =>  0,
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
          $subRemAmount=$sub_order_check->rem_price;
          //dd($refundAmt);
          //dd($storeOrderData->store_total_price);
          //$storeOrdersCount
          //dd($subRefundAmt.' @@ '.$storeOrderData->store_total_price);
          if($subRefundAmt>=$storeOrderData->store_total_price){
            $refundAmt =$storeOrderData->store_total_price;
            $lastRemAmountDeduct= 0;
          }else{
            if($subRefundAmt==0){
              $refundAmt =$subRefundAmt;
              $lastRemAmountDeduct= $storeOrderData->store_total_price;
            }else{
              $refundAmt =$subRefundAmt;
              $lastRemAmountDeduct= $storeOrderData->store_total_price-$subRefundAmt;
            }
            
          }
          //dd($lastRemAmountDeduct);
         

          //$price = $userInfo->wallet + $storeOrderData->store_total_price;
            
            if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
                $newbal1 = $userInfo->wallet + $refundAmt;
            } else {
                if($user->payment_status=='success'){
                    $checkRePrice = $user->paid_by_wallet-$storeOrderData->store_total_price;
                    if($checkRePrice>=0){
                      //$remainingtotal = $user->rem_price;
                      //$paidByWallet = $checkRePrice;
                      $newbal1 = $userInfo->wallet + $refundAmt; 

                    }else{
                      //$remainingtotal = $user->rem_price+$checkRePrice;
                      //$paidByWallet = $user->paid_by_wallet;
                      
                      if($user->paid_by_wallet>0){
                         $newbal1 = $userInfo->wallet+$refundAmt;
                      }else{
                         $newbal1 = $userInfo->wallet+$refundAmt;
                      }
                    }

                    
                } else {
                   $newbal1 = $userInfo->wallet+$refundAmt;
                }
            }

            //dd($newbal1);

            
            
            $userwalletupdate = DB::table('users')
                                    ->where('user_id',$userId)
                                    ->update(['wallet'=>$newbal1]);
            if($userwalletupdate){
              DB::table('wallet_recharge_history')
                            ->update([
                                      'amount'        =>  $refundAmt,
                                      'type'          =>  'Order Cancel Amount',
                                      'order_cart_id' =>  $cartId,
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
              $total = $user->total_price-$storeOrderData->store_total_price;
              $totalWithoutDelivery = $user->price_without_delivery-$storeOrderData->store_total_price;
              $paidByWallet = $user->paid_by_wallet-$refundAmt;

              if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
                //$newbal1 = $userInfo->wallet + $user->paid_by_wallet;
                $remainingtotal = $user->rem_price-$user->paid_by_wallet-$storeOrderData->store_total_price;
                $paidByWallet = 0;
              } else {
                if($user->payment_status=='success'){
                    //$newbal1 = $userInfo->wallet + $user->rem_price + $user->paid_by_wallet;



                    $checkRePrice = $user->paid_by_wallet-$storeOrderData->store_total_price;
                    if($checkRePrice>=0){
                      $remainingtotal = $user->rem_price;
                      $paidByWallet = $checkRePrice;  
                    }else{

                      
                      if($user->paid_by_wallet>0){
                         $remainingtotal = $user->rem_price+$checkRePrice-$user->paid_by_wallet;
                         $paidByWallet = $user->paid_by_wallet-$user->paid_by_wallet;
                         
                      }else{
                         $remainingtotal = $user->rem_price+$checkRePrice;
                         $paidByWallet = $user->paid_by_wallet;
                      }

                    }
                } else {
                    //$newbal1 = $userInfo->wallet;
                    $checkRePrice = $user->paid_by_wallet-$storeOrderData->store_total_price;
                    if($checkRePrice>=0){
                      $remainingtotal = $user->rem_price;
                      $paidByWallet = $checkRePrice;  
                    }else{
                      if($user->paid_by_wallet>0){
                         $remainingtotal = $user->rem_price+$checkRePrice-$user->paid_by_wallet;
                         $paidByWallet = $user->paid_by_wallet-$user->paid_by_wallet;
                         
                      }else{
                         $remainingtotal = $user->rem_price+$checkRePrice;
                         $paidByWallet = $user->paid_by_wallet;
                      }
                    }
                    
                    
                }
              }
              
              $updated_at = Carbon::now();

              if($sub_product_total_count==$sub_product_cancel_count){
                //dd('last cancel avail');
                $sub_order = DB::table('sub_orders')
                    ->where('cart_id', $cartId)
                    ->where('sub_order_cart_id', $sub_cart_id)
                    ->update([
                                'total_price' =>   $sub_order_check->total_price-$storeOrderData->store_total_price-$sub_order_check->delivery_charge,
                                'rem_price' =>     $sub_order_check->rem_price-$storeOrderData->store_total_price-$sub_order_check->delivery_charge,
                                'paid_by_wallet' => $sub_order_check->paid_by_wallet-$refundAmt,
                                'updated_at'  =>  $updated_at,
                                'delivery_charge'=>0, 
                                'order_status'  =>  'Cancelled',
                                'cancelling_reason'  =>  $cancelReason,
                            ]);
                    $order  =   DB::table('orders')
                      ->where('cart_id', $cartId)
                      ->update([
                        'total_price' =>   $user->total_price-$storeOrderData->store_total_price-$sub_order_check->delivery_charge,
                        'rem_price' =>     $user->rem_price-$storeOrderData->store_total_price-$sub_order_check->delivery_charge,
                        'delivery_charge'=>$user->delivery_charge-$sub_order_check->delivery_charge,
                        //'price_without_delivery' =>   $totalWithoutDelivery,
                        'paid_by_wallet' =>  $user->paid_by_wallet-$refundAmt,
                        'updated_at'  =>  $updated_at
                      ]);


                  $mainOrderLast    = DB::table('orders')
                    ->where('cart_id',$cartId)
                    ->first();
                  $calLastTotal = $mainOrderLast->total_price-$mainOrderLast->delivery_charge;
                  $lastDeliveryCharge= 0;

                  
                                
              }else{
                //dd('one by one cancel avail');
                $newSubTotal = $sub_order_check->total_price-$storeOrderData->store_total_price;
                //dd($newSubTotal);

                $delcharge = DB::table('freedeliverycart_by_store')
                         ->where('delivery_store_id',$sub_order_check->store_id) 
                         ->where('min_cart_value','>=', $newSubTotal)
                         ->first();
                $newDeliveryCharge=$delcharge->del_charge;
                //dd($newDeliveryCharge);
                if($newDeliveryCharge>0){
                    $sub_order  =   DB::table('sub_orders')
                  ->where('cart_id', $cartId)
                  ->where('sub_order_cart_id', $sub_cart_id)
                  ->update([
                    'total_price' =>   $sub_order_check->total_price-$storeOrderData->store_total_price+$newDeliveryCharge,
                    'rem_price' =>     $sub_order_check->rem_price-$lastRemAmountDeduct+$newDeliveryCharge,
                    //'price_without_delivery' =>   $totalWithoutDelivery,
                    'delivery_charge'=> $newDeliveryCharge,
                    'paid_by_wallet' => $sub_order_check->paid_by_wallet-$refundAmt,
                    'updated_at'  =>  $updated_at
                  ]);
                 $orderDeliveryCharge=  $user->delivery_charge-$sub_order_check->delivery_charge+$newDeliveryCharge;
                $order  =   DB::table('orders')
                    ->where('cart_id', $cartId)
                    ->update([
                      'total_price' =>   $user->total_price-$storeOrderData->store_total_price+$newDeliveryCharge,
                      'rem_price' =>     $user->rem_price-$lastRemAmountDeduct+$newDeliveryCharge,
                      'delivery_charge' =>   $orderDeliveryCharge,
                      'paid_by_wallet' =>  $user->paid_by_wallet-$refundAmt,
                      'updated_at'  =>  $updated_at
                    ]);

                }else{
                  $sub_order  =   DB::table('sub_orders')
                  ->where('cart_id', $cartId)
                  ->where('sub_order_cart_id', $sub_cart_id)
                  ->update([
                    'total_price' =>   $sub_order_check->total_price-$storeOrderData->store_total_price,
                    'rem_price' =>     $sub_order_check->rem_price-$lastRemAmountDeduct,
                    //'price_without_delivery' =>   $totalWithoutDelivery,
                    'paid_by_wallet' => $sub_order_check->paid_by_wallet-$refundAmt,
                    'updated_at'  =>  $updated_at
                  ]);
                $order  =   DB::table('orders')
                    ->where('cart_id', $cartId)
                    ->update([
                      'total_price' =>   $user->total_price-$storeOrderData->store_total_price,
                      'rem_price' =>     $user->rem_price-$lastRemAmountDeduct,
                      //'price_without_delivery' =>   $totalWithoutDelivery,
                      'paid_by_wallet' =>  $user->paid_by_wallet-$refundAmt,
                      'updated_at'  =>  $updated_at
                    ]); 

                }          


                  

              }

              
            }


        }
                    
        if ($update) {
            
          $message = array('status' => '1', 'message' => 'Order Product Cancel Successfully');

        } else {
            
          $message = array('status' => '0', 'message' => 'Order Product Not Cancelled');
          
        }
        return $message;
                  
  }
            
  public function productcancelled1(Request $request)
    {
       $id= $request->store_order_id;
       $storeId= $request->store_id;

       //dd('ss');

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
        $price = round($p->price);
        $mrpprice = $p->mrp;
        $order_qty = $h->qty;
        $price2+= $price*$order_qty;
        $unit[] = $p->unit;
        $qty[]= $p->quantity;
        $p_name[] = $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
        $prod_name = implode(',',$p_name);
        }    
       $v = DB::table('product_varient')
       ->where('varient_id', $cart->varient_id)
       ->first();
       
       $v_price =$v->price * $cart->qty;       
      $ordr = DB::table('orders')
            ->where('cart_id', $cart->order_cart_id)
            ->first();
       $user_id = $ordr->user_id;
       $userwa = DB::table('users')
                     ->where('user_id',$user_id)
                     ->first();
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
     if($ordr->payment_method == 'COD' || $ordr->payment_method == 'Cod' || $ordr->payment_method == 'cod'){          
        $newbal = $userwa->wallet; 
        $ordr = DB::table('orders')
            ->where('cart_id', $cart->order_cart_id)
            ->update(['total_price'=>$tot_price2,
            'rem_price'=>$rem_price2]);                  
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
           ->having('distance', '<', 15)
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
               $userwa1 = DB::table('users')
                             ->where('user_id',$user_id1)
                             ->first();
                $newbal1 = $userwa1->wallet - $v_price1;
                 $userwalletupdate = DB::table('users')
                     ->where('user_id',$user_id1)
                     ->update(['wallet'=>$newbal1]);
            }
            
            
            $cart_update= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->update(['store_approval'=>1]);
             
              ///////send notification to store//////
                $notification_title = "WooHoo ! You Got a New Order";
                //$notification_text = "you got an order cart id #".$cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$price2. ". It will have to delivered on ".$ordr->delivery_date." between ".$ordr->time_slot.".";
                $notification_text = "you got an order cart id #".$cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$price2. ". It will have to delivered on ".$ordr->delivery_date.".";
                
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
            
           $data[]=array('result'=>'Order Cancelled Successfully');
            }
            else{
            $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart->order_cart_id)
                     ->update(['store_id'=>0,
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
               $userwa1 = DB::table('users')
                             ->where('user_id',$user_id1)
                             ->first();
                $newbal1 = $userwa1->wallet - $v_price1;
                 $userwalletupdate = DB::table('users')
                     ->where('user_id',$user_id1)
                     ->update(['wallet'=>$newbal1]);
            }    
            
            $cart_update= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->update(['store_approval'=>1]); 
            $data[]=array('result'=>'Order Cancelled Successfully');
            }
        }
        else{
            $cancel=2;
             $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart->order_cart_id)
                     ->update(['store_id'=>0,
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
               $userwa1 = DB::table('users')
                             ->where('user_id',$user_id1)
                             ->first();
                $newbal1 = $userwa1->wallet - $v_price1;
                 $userwalletupdate = DB::table('users')
                     ->where('user_id',$user_id1)
                     ->update(['wallet'=>$newbal1]);
            }    
            
            $cart_update= DB::table('store_orders')
            ->where('order_cart_id', $cart->order_cart_id)
            ->update(['store_approval'=>1]);
        $data[]=array('result'=>'Order Cancelled Successfully');
        }    
        $data[]=array('result'=>'Order Cancelled Successfully');
         
        }    
            
        else{    
       $cancel_product = DB::table('store_orders')
                       ->where('store_order_id', $id)
                       ->update(['store_approval'=>0]);

         $userwallet = DB::table('users')
                     ->where('user_id',$user_id)
                     ->update(['wallet'=>$newbal]);
         $data[]=array('result'=>'Product Cancelled Successfully');                  
                       
        }             
       return $data;            
    }



    public function order_rejected(Request $request)
    {
      $cart_id= $request->cart_id;
      $store_id = $request->store_id;
      $storeId = $request->store_id;
      $date_of_recharge  = carbon::now();

      // $cart_id= 'LLFF69a1';
      // $store_id = '3';
      // $storeId = '3';

      $ordr = DB::table('orders')
            ->where('cart_id', $cart_id)
            ->first();
      $sub_ordr = DB::table('sub_orders')
            ->where('cart_id', $cart_id)
            ->where('store_id', $store_id)
            ->first();

      $sub_cart_id = $sub_ordr->sub_order_cart_id;
      $userId = $sub_ordr->user_id;
                             
      $curr = DB::table('currency')
             ->first();
      $orders = DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('sub_order_cart_id', $sub_cart_id)
            ->where('store_approval',1)
            ->get(); 
      $var = DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('sub_order_cart_id', $sub_cart_id)
            ->where('store_approval',1)
            ->get();        
    	$store= DB::table('store')
    	 		   ->where('store_id',$store_id)
    	 		   ->first();
      $userData = DB::table('users')
                     ->where('user_id',$sub_ordr->user_id)
                     ->first();                      
    	             
      $v_price1 = 0;
      $cartss= DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('sub_order_cart_id', $sub_cart_id)
            ->where('store_approval',0)
            ->get();

      if(count($cartss)>0){
        foreach($cartss as $carts){
                $v1 = DB::table('store_orders')
               ->where('store_order_id', $carts->store_order_id)
               ->first();
               
          $v_price1 += $v1->price * $v1->qty;       
              
        }      
          $user_id1 = $sub_ordr->user_id;
          $userwa1 = DB::table('users')
                     ->where('user_id',$user_id1)
                     ->first();
          

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

          $userwalletupdate = DB::table('users')
             ->where('user_id',$user_id1)
             ->update(['wallet'=>$newbal1]);
       } 


    	 		   
        $price2 = 0; 
        //dd($var);    
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

        //dd($userData->wallet); 
        //dd($sub_ordr->total_price.' '.$sub_ordr->paid_by_wallet.' '.$sub_ordr->rem_price);

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

               
        if($sub_ordr->cancel_by_store==0){

            $cancel=1;
            $store_id = DB::table('store')
              ->select("store_id","store_name",DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
              * cos(radians(lat)) 
              * cos(radians(lng) - radians(" . $store->lng . ")) 
              + sin(radians(" .$store->lat. ")) 
              * sin(radians(lat))) AS distance"))
              //->where('city',$store->city) 
              ->where('store_id','!=',$store->store_id)
              ->orderBy('distance')
              ->first();
              //dd($price2);

              if($store_id){
 
                $sub_ordupdate = DB::table('sub_orders')
                     ->where('cart_id', $cart_id)
                     ->where('sub_order_cart_id', $sub_cart_id)
                     ->update([
                      'total_price' =>   0,
                      'rem_price' =>     0,
                      'paid_by_wallet' =>  0,
                      'delivery_charge'=>0,
                      'coupon_discount'=>  0,
                      'coupon_id'=>0,
                      'delivery_charge'=>0,
                      'bulk_order_based_discount'=> 0,
                      'online_payment_based_discount'=> 0,
                      'cancel_by_store'=>$cancel,
                      'order_status'=>'Cancelled',
                      'cancelling_reason'=>'Cancel by Store']);


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
                      ->update(['order_status'=>'Cancelled','cancel_by_store'=>1,'paid_by_wallet' =>0,'rem_price'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0]);   
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
                    ->update(['store_approval'=>1,'cancel_status'=>1,'cancel_reason'=>'Cancel By Store']);
            
                ///////send notification to store//////
                //dd('dsa');     


              
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
                    ->insert([
                      //'store_id'=>$store_id->store_id,
                      'store_id'=>$storeId,
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
                    
                $results = json_decode($result);

                $data[]=array('result'=>'Order Cancelled Successfully');
              }else{
            

                $sub_ordupdate = DB::table('sub_orders')
                     ->where('cart_id', $cart_id)
                     ->where('sub_order_cart_id', $sub_cart_id)
                     ->update(['cancel_by_store'=>$cancel,'total_price' =>   0,
                      'rem_price'=>0,'paid_by_wallet'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0,'order_status'=>'Cancelled','cancelling_reason'=>'cancel by store']);

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
                      ->update(['order_status'=>'Cancelled','cancel_by_store'=>1,'paid_by_wallet' =>0,'rem_price'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0]);   
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
                  ->update(['store_approval'=>1,'cancel_status'=>1,'cancel_reason'=>'Cancel By Store']);
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
                    
                $results = json_decode($result);

                $data[]=array('result'=>'Order Cancelled Successfully');
              }
        }else{
            $cancel=2;
             
            $sub_ordupdate = DB::table('sub_orders')
                     ->where('cart_id', $cart_id)
                     ->where('sub_order_cart_id', $sub_cart_id)
                     ->update(['cancel_by_store'=>$cancel,'total_price' =>   0,
                      'rem_price'=>0,'paid_by_wallet'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0,'order_status'=>'Cancelled','cancelling_reason'=>'cancel by store']);
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
                      ->update(['order_status'=>'Cancelled','cancel_by_store'=>1,'paid_by_wallet' =>0,'rem_price'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0]);   
                    }else{
                      $ordupdate = DB::table('orders')
                     ->where('cart_id', $cart_id)
                     ->update(['total_price'=>$ordr->total_price-$sub_ordr->total_price,
                      //'price_without_delivery'=>$ordr->price_without_delivery-$price2,
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
            ->update(['store_approval'=>1,'cancel_status'=>1,'cancel_reason'=>'Cancel By Store']);

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
                    
                $results = json_decode($result);
        $data[]=array('result'=>'Order Cancelled Successfully');
      }    
      return $data;
                       
                    
    }
    
    

   public function storeconfirm(Request $request)
    {
       $cart_id= $request->cart_id;
       $store_id = $request->store_id;

       // $cart_id= 'YLCC5948';
       // $store_id = '3';
      
       $curr = DB::table('currency')
             ->first();
       
     $store= DB::table('store')
        	->where('store_id',$store_id)
    	 	->first();
             
      $del_boy = DB::table('delivery_boy')
          ->select("boy_name","boy_phone","dboy_id"
        ,DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
        * cos(radians(lat)) 
        * cos(radians(lng) - radians(" . $store->lng . ")) 
        + sin(radians(" .$store->lat. ")) 
        * sin(radians(lat))) AS distance"))
       ->where('delivery_boy.boy_city',$store->city)    
       ->orderBy('distance')
       ->first();         
        
        $orr =   DB::table('orders')
                ->where('cart_id',$cart_id)
                ->first();
                    
           $v = DB::table('store_orders')
 		   ->where('order_cart_id',$cart_id)
 		   ->get(); 
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
                  $message = array('status'=>'0', 'message'=>$pr->product_name."(".$pr->quantity." ".$pr->unit.") is not available in your product list");
	              return $message;
              }
             }        
    if($del_boy){   
       $orderconfirm = DB::table('orders')
                    ->where('cart_id',$cart_id)
                    ->update(['order_status'=>'Confirmed',
                    'dboy_id'=>$del_boy->dboy_id]);
         
 		   
         if($orderconfirm){
                $notification_title = "You Got a New Order for Delivery on ".$orr->delivery_date;
                $notification_text = "you got an order with cart id #".$cart_id." of price ".$curr->currency_sign." " .round($orr->total_price). ". It will have to delivered on ".$orr->delivery_date." between ".$orr->time_slot.".";
                
                $date = date('d-m-Y');
                $getUser = DB::table('delivery_boy')
                                ->get();
        
                $getDevice = DB::table('delivery_boy')
                         ->where('dboy_id', $del_boy->dboy_id)
                        ->select('device_id')
                        ->first();
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
             
             
        	$message = array('status'=>'1', 'message'=>'Order Is Confirmed');
	        return $message;
              }
    	else{
    		$message = array('status'=>'0', 'message'=>'Something Went Wrong');
	        return $message;
    	} 
    }
    else{
        	$message = array('status'=>'0', 'message'=>'No Delivery Boy In Your City');
	        return $message;
    }
    }


}