<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function apply_coupon(Request $request)
    {
        $cart_id = $request->cart_id;
        $coupon_code = $request->coupon_code;
        // $cart_id = 'ERPX301f';
        // $coupon_code = '2708ABCD';


        $coupon = DB::table('coupon')
                ->where('coupon_code', $coupon_code)
                ->first();
        $check = DB::table('orders')
               ->where('cart_id',$cart_id)
               ->first();
               
        $p=$check->total_price;

        if($check->bulk_order_based_discount>0){
          //dd('bulk');
           $message = array('status'=>'0', 'message'=>'Coupon cannot be applied beacuse your order reached on bulk order discount', 'data'=>$check);
            return $message;

        }
               
        $orderchecked =DB::table('orders')
              ->where('cart_id',$cart_id)
              ->where('coupon_id',$coupon_code)
              ->first();

        $subOrderchecked =DB::table('sub_orders')
              ->where('cart_id',$cart_id)
              ->get();       
        //dd($subOrderchecked); 
       
      if(isset($coupon)){      
        if(!$orderchecked){     
          $check2 = DB::table('orders')
               ->where('coupon_id',$coupon_code)
               ->where('user_id',$check->user_id)
               ->count();
          //dd($coupon);

          $countA=0;
          foreach($subOrderchecked as $key => $value){
          if($coupon->coupon_store_id==$subOrderchecked[$key]->store_id){
           $countA++;    
       
          if($coupon->uses_restriction > $check2){
            $newCheckTotal = $subOrderchecked[$key]->total_price-$subOrderchecked[$key]->delivery_charge;
            if($coupon->cart_value > $newCheckTotal){
              $storeName = $this->get_store_name($coupon->coupon_store_id); 
               $order =DB::table('orders')
                ->where('cart_id',$cart_id)
                ->first();
               $msg= "Cart Value for ".$storeName." (store) must be greater than ".$coupon->cart_value;
               $message = array('status'=>'0', 'message'=>$msg, 'data'=>$order);
                    return $message;
            }else{

            //$mincart = $coupon->cart_value;
            $am = $coupon->amount;
            $type = $coupon->type;
            if($type=='%'||$type=='Percentage'||$type=='percentage'){
              $per = ($newCheckTotal*$am)/100;  
              $rem_price = $p-$per;
              $sub_rem_price = $subOrderchecked[$key]->total_price-$per;    
            }else{
              $per = $am;
              $rem_price = $p-$am;
              $sub_rem_price = $subOrderchecked[$key]->total_price-$per; 
            }
            $update=DB::table('orders')
                ->where('cart_id',$cart_id)
                ->update(['rem_price'=>$rem_price,
                  'total_price'=>$rem_price,
                  'coupon_discount'=>$per,
                  'coupon_id'=>$coupon_code]);

            $sub_update=DB::table('sub_orders')
                ->where('cart_id',$cart_id)
                ->where('store_id',$coupon->coupon_store_id)
                ->update(['rem_price'=>$sub_rem_price,
                  'total_price'=>$sub_rem_price,
                  'coupon_discount'=>$per,
                  'coupon_id'=>$coupon_code]);    

            $order =DB::table('orders')
                ->where('cart_id',$cart_id)
                ->first();
            if($order){   
              if($update){
                $message = array('status'=>'1', 'message'=>'Coupon Applied Successfully', 'data'=>$order);
                return $message;
              }else{
                    $message = array('status'=>'0', 'message'=>'Cannot Applied', 'data'=>$order);
                    return $message;
              }
            }else{
                 $message = array('status'=>'0', 'message'=>'Order Not Found');
                 return $message;
            }
           } 


          }else{
               $message = array('status'=>'0', 'message'=>'Maximum Use Limit Reached');
               return $message;
          }
          }else{

          }
         } 

          if($countA==0){
            $order =DB::table('orders')
              ->where('cart_id',$cart_id)
              ->first();  
              
            $message = array('status'=>'0', 'message'=>'Coupon Unapplied', 'data'=>$order);
            return $message;

          }

        }else{
          $totalAmount = $p+$orderchecked->coupon_discount;
          $update=DB::table('orders')
              ->where('cart_id',$cart_id)
              ->update(['rem_price'=>$totalAmount,
              'total_price'=>$totalAmount,  
              'coupon_discount'=>0,
              'coupon_id'=>0]);
          
          $re_subOrderchecked =DB::table('sub_orders')
              ->where('cart_id',$cart_id)
              ->where('store_id',$coupon->coupon_store_id)
              ->first();
                  
          $sub_update=DB::table('sub_orders')
              ->where('cart_id',$cart_id)
              ->where('store_id',$coupon->coupon_store_id)
              ->update(['rem_price'=>$re_subOrderchecked->rem_price+$orderchecked->coupon_discount,
              'total_price'=>$re_subOrderchecked->total_price+$orderchecked->coupon_discount,  
              'coupon_discount'=>0,
              'coupon_id'=>0]);
                  
          $order =DB::table('orders')
              ->where('cart_id',$cart_id)
              ->first();  
              
          if($update){
            $message = array('status'=>'2', 'message'=>'Coupon Unapplied', 'data'=>$order);
            return $message;
          }else{
            $message = array('status'=>'0', 'message'=>'Something Went Wrong', 'data'=>$order);
            return $message;
          }      
        }
      }else{
        $order =DB::table('orders')
              ->where('cart_id',$cart_id)
              ->first();
        $message = array('status'=>'0', 'message'=>'Please enter valid coupon code', 'data'=>$order);
            return $message;

      }
    }




    public function cancel_coupon(Request $request)
    {
        $cart_id = $request->cart_id;
        $coupon_id = $request->coupon_id;

        // $cart_id = 'FOMA35f5';
        // $coupon_id = '4';
                       
        $coupon = DB::table('coupon')
                ->where('coupon_id', $coupon_id)
                ->first();
        $storeId = $coupon->coupon_store_id;        
        $check = DB::table('orders')
               ->where('cart_id',$cart_id)
               ->first();
         $p=$check->total_price;       
         $orderchecked =DB::table('orders')
              ->where('cart_id',$cart_id)
              ->where('coupon_id',$coupon->coupon_code)
              ->first();     
              
        if(!$orderchecked){ 
            $order =DB::table('orders')
              ->where('cart_id',$cart_id)
              ->first();    
         
            $message = array('status'=>'0', 'message'=>'That Coupon Not Applied On This Order', 'data'=>$order);
            return $message;
     
        }
        else{
            $totalAmount = $p+$orderchecked->coupon_discount;
            $update=DB::table('orders')
              ->where('cart_id',$cart_id)
              ->update(['rem_price'=>$totalAmount,
               'total_price'=>$totalAmount, 
              'coupon_discount'=>0,
              'coupon_id'=>0]);
            $re_subOrderchecked =DB::table('sub_orders')
              ->where('cart_id',$cart_id)
              ->where('store_id',$coupon->coupon_store_id)
              ->first();
                  
            $sub_update=DB::table('sub_orders')
              ->where('cart_id',$cart_id)
              ->where('store_id',$coupon->coupon_store_id)
              ->update(['rem_price'=>$re_subOrderchecked->rem_price+$orderchecked->coupon_discount,
              'total_price'=>$re_subOrderchecked->total_price+$orderchecked->coupon_discount,  
              'coupon_discount'=>0,
              'coupon_id'=>0]);
                
             $order =DB::table('orders')
              ->where('cart_id',$cart_id)
              ->first();  
              
         if($update){
            $message = array('status'=>'1', 'message'=>'Coupon Cancelled Successfully', 'data'=>$order);
            return $message;
            }
        else{
            $message = array('status'=>'0', 'message'=>'Something Went Wrong', 'data'=>$order);
            return $message;
        }      
        }
    }
    
    public function coupon_list(Request $request)
    {
        $currentdate = Carbon::now();
        $cart_id = $request->cart_id;
        //$cart_id = 'XHXZ10a8';
        
        $check = DB::table('orders')
               ->where('cart_id',$cart_id)
               ->first();
        $p=$check->total_price;
        
        $coupon = DB::table('coupon')
                ->where('cart_value','<=', $p)
                ->where('start_date','<=',$currentdate)
                ->where('end_date','>=',$currentdate)
                ->get();

        if(count($coupon)>0){
            $message = array('status'=>'1', 'message'=>'Coupon List', 'data'=>$coupon);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'Coupon Not Found');
            return $message;
        }
    
    }

    public function get_store_name($store_id)
    {
      $storeData = DB::table('store')
                ->where('store_id', $store_id)
                ->select('store_name')
                ->first();
      return $storeData->store_name;          

    }
    
}