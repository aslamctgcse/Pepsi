<?php

namespace App\Http\Controllers\Api;

use DB;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AppController extends Controller
{
 
    public function app(Request $request)
    {
          $app = DB::table('tbl_web_setting')
                      ->first();
                      
        if($app)   { 
            $message = array('status'=>'1', 'message'=>'App Name & Logo', 'data'=>$app);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'data not found', 'data'=>[]);
            return $message;
        }

        return $message;
    }
    
    /**
     * get all delivery fees API
     * 
     * 
     */
    public function delivery_info(Request $request)
    {
        // $del_fee = DB::table('freedeliverycart')
        //               ->first();
        try {
            
            $del_fee = DB::table('freedeliverycart')
                          ->get();

            $data = [];
            foreach ($del_fee as $key => $delFeeValue) {
                $data[]  =      [
                                    'id'                =>  (string)$delFeeValue->id ?? '',
                                    'min_cart_value'    =>  (string)$delFeeValue->min_cart_value ?? '',
                                    'del_charge'        =>  (string)$delFeeValue->del_charge ?? '',
                                ];
            }
        
            $message = Response::json(array('status' => '1', 'message' => 'Delivery fee and cart value', 'data' => $data));

            return $message;
        } catch (\Exception $e) {
            
            $message = Response::json(array('status'=>'0', 'message'=>'data not found', 'data' => []));
            return $message;
        }

        // $del_fee = DB::table('freedeliverycart')
        //               ->get();

        // $data = [];
        // foreach ($del_fee as $key => $delFeeValue) {
        //     $data  =    [
        //                     'id'                =>  $delFeeValue['id'],
        //                     'min_cart_value'    =>  $delFeeValue['min_cart_value'],
        //                     'del_charge'        =>  $delFeeValue['del_charge'],
        //                 ];
        // }
                      
        //     $message = Response::json(array('status'=>'1', 'message'=>'Delivery fee and cart value', 'data' => $data));
        //     return $message;
        // }
        // else{
        //     $message = array('status'=>'0', 'message'=>'data not found', 'data'=>[]);
        //     return $message;
        // }

        // return $message;
    }


    //New delivery charge system
    public function exact_delivery_info(Request $request)
    {
        $data             =  $request->order_array;

        // $data             =  '[{"qty":"2","varient_id":"964","product_image":"images\/product\/28-06-2021\/amuullll.jpg","store_id":"3"},{"qty":"1","varient_id":"971","product_image":"images\/product\/28-06-2021\/amuullll.jpg","store_id":"3"}]';
        //  $data             =  '[{"store_id":3, "product_image":"images/product/16-07-2021/1-LTR-FANTA.jpeg", "price":50, "qty":4, "unit_value":"1Pcs", "varient_id":964, "increament":0, "stock":10, "title":"Fanta", "product_description":"1 Ltr", "product_name":"Fanta", "rewards":0}]';

        $data_array       =  json_decode($data);
        $price2     = 0;
        $price5     = 0;
        $current          =  Carbon::now();
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
        $sub_cart_id = $val.$val2.$cr.'-STORE';
        //dd($data_array);

        try {

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
                           
          }
        //dd($checkStore);
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
         $totalDeliveryCharge =0;
         $bulkOrderDiscount =0;
         $onlineOrderDiscount=0;

        foreach ($checkStoreNew as $sh){
          //dd($sh->totalPrice);
                 $delcharge = DB::table('freedeliverycart_by_store')
                        ->where('delivery_store_id', $sh->store_id)
                        ->where('min_cart_value','>=', $sh->totalPrice)
                        ->first();          

                  if($delcharge){
                    $newCharge = $delcharge->del_charge;  
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

                  $letNewTotal= $sh->totalPrice-$bb+$newCharge;
                  $newOnlineDiscount=DB::table('online_payment_discount') 
                  ->where('online_payment_min_amount','<=',$letNewTotal)
                  ->where('online_payment_max_amount','>=',$letNewTotal)
                  ->first();
                    //dd($newOnlineDiscount); 

                  if($newOnlineDiscount){
                    if($newOnlineDiscount->online_payment_discount_type=='percentage'){
                      $oo = ($letNewTotal)*$newOnlineDiscount->online_payment_discount/100;  
                    }else{
                      $oo =$newOnlineDiscount->online_payment_discount;
                    }  
                  }else{
                    $maxValueAmountLimit = DB::table('online_payment_discount')->max('online_payment_max_amount');
                    $maxOnlineDiscountData = DB::table('online_payment_discount')
                    ->where('online_payment_max_amount',$maxValueAmountLimit)
                    ->first();
                    if($letNewTotal>$maxValueAmountLimit){
                      if($maxOnlineDiscountData->online_payment_discount_type=='percentage'){
                        $oo = ($letNewTotal)*$maxOnlineDiscountData->online_payment_discount/100;  
                      }else{
                        $oo =$maxOnlineDiscountData->online_payment_discount;
                      }
                    }else{
                      $oo = 0;
                    }
                  }

                  $onlineOrderDiscount+= $oo;


        }
        //dd($totalDeliveryCharge);
            
            $data=array('delivery_charge'=>round($totalDeliveryCharge,2),'bulk_order_discount'=>number_format($bulkOrderDiscount,2), 'online_payment_discount'=>number_format($onlineOrderDiscount,2));
        
            $message = Response::json(array('status' => '1', 'message' => 'Delivery fee and cart value', 'data' => $data));

            return $message;
        } catch (\Exception $e) {
            //$data=array('delivery_charge'=>0,'bulk_order_discount'=>0);
            
            $message = Response::json(array('status'=>'0', 'message'=>'Data not found', 'data' => []));
            return $message;
        }

    }
}
