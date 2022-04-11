<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
//use Carbon;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use Mail;

class ProfileController extends Controller
{
   #razorpay credential beg
    private $razorpayid="rzp_test_cn7PNije60Rutl";
    private $razorpaykey="r05UHvc4aYYIGrlyduGijDIG";
   #razorpay credential end
    //added code for construct function beg
    public $categories;
    public $catpcount;
    public $catarray;
    public $products;
    public $catproductrels;
    public $catproductdetails;
    public $cat_crousel;
    public $coupondata;
    public $cat;
    public function __construct(){
      #added code for cat var beg
       $cat = DB::table('categories')
            ->where('level', 0)
            ->where('status', 1)
            ->get();
            if(count($cat)>0) {

              $result = array();
              $i = 0;

              foreach ($cat as $cats) {

                  array_push($result, $cats);

                  $app = json_decode($cats->cat_id);
                  $apps = array($app);
                  $app = DB::table('categories')
                          ->whereIn('parent', $apps)
                          ->where('level', 1)
                          ->where('status', 1)
                          
                          ->get();
                        //dd($app);
                          
                  $result[$i]->subcategory = $app;
                  $i++; 
                  $res =array();
                  $j = 0;

                  foreach ($app as $appss) {
                      array_push($res, $appss);
                      $c = array($appss->cat_id);
                      $app1 = DB::table('categories')
                              ->whereIn('parent', $c)
                              ->where('level', 2)
                              ->get();

                  if(count($app1)>0){        
                      $res[$j]->subchild = $app1;
                      $j++; 
                     }
                     else{
                       $res[$j]->subchild = [];
                      $j++;  
                     }
                   }   
              }

             // dd($cat);

            
            }
      #added code for cat var end
       #coupon data beg
      $coupondata=DB::table('coupon')->first();

      #coupon data end
         #added code for carousel beg
         $cat_crousel = DB::table('categories')
          ->where('level', 0)
          ->where('status', 1)
          ->get();
          

        if(count($cat_crousel)>0) {

          $result = array();
          $i = 0;

          foreach ($cat_crousel as $cats) {

              array_push($result, $cats);

              $app = json_decode($cats->cat_id);
              $apps = array($app);
              $app = DB::table('categories')
                      ->whereIn('parent', $apps)
                      ->where('level', 1)
                      ->where('status', 1)
                      ->get();
                      
              $result[$i]->subcategory = $app;
              $i++; 
              $res =array();
              $j = 0;

              foreach ($app as $appss) {
                  array_push($res, $appss);
                  $c = array($appss->cat_id);
                  $app1 = DB::table('categories')
                          ->whereIn('parent', $c)
                          ->where('level', 2)
                          ->get();

              if(count($app1)>0){        
                  $res[$j]->subchild = $app1;
                  $j++; 
                 }
                 else{
                   $res[$j]->subchild = [];
                  $j++;  
                 }
               }   
          }
      }
      $this->cat_crousel=$cat_crousel;
      $this->coupondata=$coupondata;
      $this->cat=$cat;
        #added code for carousel end
        $products=DB::table('product')->select('product.*','product_varient.*')
         ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->orderby('product.product_id','desc')
      
        ->get();
        $categories=DB::table('categories')->select('*')
        ->where('status',1)
        ->orderby('cat_id','desc')
       // ->get();
        ->paginate(3);
        $catproductrels=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->orderby('categories.cat_id','desc')
        ->get();
        
        $catarray=array();
        $catpcount=array();
        $catproductdetails=array();
        foreach($catproductrels as $catproductrel){
                if(isset($catarray[$catproductrel->pcid])){
                
                    $catarray[$catproductrel->pcid]=$catproductrel;
                    $catproductdetails[$catproductrel->pcid][]=$catproductrel;
                    $catpcount[$catproductrel->pcid]+=1;
               }else{
                    //$catarray[$catproductrel->ctitle][]=$catproductrel;
                    $catarray[$catproductrel->pcid]=$catproductrel;
                    $catpcount[$catproductrel->pcid]=1;
                    $catproductdetails[$catproductrel->pcid][]=$catproductrel;
               }

        }
       $this->catarray=$catarray;
       $this->categories=$categories;
       $this->catpcount=$catpcount;
       $this->products=$products;
       $this->catproductrels=$catproductrels;
       $this->catproductdetails=$catproductdetails;
       
    }
    //added code for construct function end
    # Bind the website path.
    protected $viewPath = 'website.user_profile.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function myProfile()
    {
       
        $cat_crousel=$this->cat_crousel;
        $coupondata=$this->coupondata;
        $cat=$this->cat;
        $get_city=DB::table('city')->get();        
       
        $user_session=Session::get('userData');
        //dd($user_session);
        //added code to get address detail of logined user beg
        $profileaddress=DB::table('address')
                       ->select('*')
                       ->where('user_id',$user_session->user_id)
                       ->first();
        
        //added code to get address detail of logined user end
        //added common variable beg
       $catarray= $this->catarray;
       $categories= $this->categories;
       $catpcount= $this->catpcount;
       $products= $this->products;
       $catproductrels= $this->catproductrels;
       $catproductdetails= $this->catproductdetails;
        //added common variable end
        # Display a listing of the resource.
        return view($this->viewPath.'profile',compact('catarray','categories','catpcount','products','catproductrels','catproductdetails','get_city','profileaddress','cat_crousel','coupondata','cat'));
    }

    //added post method to update profile beg
    public function myupdateprofile(Request $request,$id){
      
     
        $singleuserdetail=DB::table('users')
                         ->where('user_id',$id)
                         ->first();
      
    //update address table

 $address_exist_or_not=DB::table('address')
 ->where('user_id',$id)
 ->first();
 //dd($address_exist_or_not);
 $date=date('Y-m-d');
 //dd($address_exist_or_not);
 if(empty( $address_exist_or_not)){
  //dd('here');
    $insert_user_address=DB::table('address')
                 //->where('user_id',$id)
                 ->insert(['user_id'=>$id,'city'=>$request->user_city,'state'=>'','receiver_name'=>$request->user_name,'receiver_phone'=>$request->user_phone,'gst_number'=>$request->gst_number ?? '','landmark'=>$request->user_landmark ?? 'N/A','society'=>$request->user_address ?? 'N/A','house_no'=>'','pincode'=>$request->user_zip,'lat'=>'','lng'=>'','select_status'=>'','added_at'=>$date]);

 }else{
    $update_user_address=DB::table('address')
                 ->where('user_id',$id)
                 ->update(['user_id'=>$id,'city'=>$request->user_city,'state'=>'','receiver_name'=>$request->user_name,'receiver_phone'=>$request->user_phone,'gst_number'=>$request->gst_number ?? '','landmark'=>$request->user_landmark ?? 'N/A','society'=>$request->user_address ?? 'N/A','house_no'=>'','pincode'=>$request->user_zip,'lat'=>'','lng'=>'','select_status'=>'','added_at'=>$date]);

 }
 #added code to update user name,email,mobile,GST beg
//get profile img
   $image = $singleuserdetail->user_image;
 #if user has uploaded image beg
 if($request->hasFile('user_pic')){
            $user_pic  = $request->user_pic;
           // dd($user_pic); 
            $fileName       =  time() . "_" . $user_pic->getClientOriginalName();
            $fileName       = str_replace(" ", "-", $fileName);
            $user_pic->move('images/profile/', $fileName);
            $user_pic = 'images/profile/'.$fileName;
        } else {
            $user_pic = $image;
        }
 #if user has uploaded image end
  #added code insert GST number in profile beg
    if(!empty($request->gst_number)){
      $updateuser=DB::table('users')
           ->where('user_id',$id)
           ->update(['user_gst_number'=>$request->gst_number]);

    }

  #added GST already exist end 

  #added code to check if email id already exist beg
    if(!empty($request->user_email)){
      $checkEmail=DB::table('users')
                 ->where('user_email',$request->user_email)
                 ->where('user_id','<>',$id) //added to exclude login user email
                 ->first();
      if(isset($checkEmail) && $request->user_email == $checkEmail->user_email){
        
        return redirect('/my-profile')->with('message','Email already exist'); 
      }

    }
  #added code to check if email id already exist end
  #added coe to check if mobile number already exist beg
    if(!empty($request->user_phone)){
      $checkPhone=DB::table('users')
                 ->where('user_phone',$request->user_phone)
                   ->where('user_id','<>',$id) //added to exclude login user phone
                 ->first();
      if(isset($checkPhone) && $request->user_phone == $checkPhone->user_phone){
        
        return redirect('/my-profile')->with('message','Mobile No. already exist'); 
      }

    }
  #added coe to check if mobile number already exist end
 $updateuser=DB::table('users')
           ->where('user_id',$id)
           ->update(['user_name'=>$request->user_name,'user_phone'=>$request->user_phone,'user_email'=>$request->user_email,'user_image'=>$user_pic]);
 
  #session put beg
  $checkUser  =   DB::table('users')
                ->where('user_id', $id)
                ->first();
     Session::put('userData',$checkUser);
     Session::get('userData');
  #session put end
 #added code to update user name,email,mobile end
return redirect('/my-profile')->with('message','Profile updated Successfully'); 

    }
//added post method to update profile end
//added code for order listing of peofile beg
public function orderlistprofile(Request $request){
   //dd('ghgjh');
      $cat_crousel=$this->cat_crousel;
      $coupondata=$this->coupondata;
      $catarray= $this->catarray;
       $categories= $this->categories;
       $catpcount= $this->catpcount;
       $products= $this->products;
       $catproductrels= $this->catproductrels;
       $catproductdetails= $this->catproductdetails;
       $cat= $this->cat;
    //dd('here');
    $user_session_data=Session::get('userData');
    
    $profile_orders=DB::table('orders')
                    ->select('*')
                   // ->where('user_id',$user_session_data->user_id)
                    ->join('users','users.user_id','=','orders.user_id')
                    //->where('users.user_email',$user_session_data->user_email)
                    //->where('users.user_email',$user_session_data->user_email)
                    ->where('users.user_phone',$user_session_data->user_phone)
                   ->orderby('orders.order_id','desc')
                    ->get();
    //dd($profile_orders);
    return view($this->viewPath.'profileorders',compact('catarray','categories','catpcount','products','catproductrels','catproductdetails','profile_orders','cat_crousel','coupondata','cat'));

}
//added code for order listing of peofile end

//added code for sub order listing of profile beg
public function suborderlistprofile(Request $request,$id){
   //dd('ghgjh');
      $cat_crousel=$this->cat_crousel;
      $coupondata=$this->coupondata;
      $catarray= $this->catarray;
      $categories= $this->categories;
      $catpcount= $this->catpcount;
      $products= $this->products;
      $catproductrels= $this->catproductrels;
      $catproductdetails= $this->catproductdetails;
      $cat= $this->cat;
      $id=$id;     
      //dd('here');
    $user_session_data=Session::get('userData');

    $profile_main_orders=DB::table('orders')
                    ->select('*')
                    ->join('users','users.user_id','=','orders.user_id')
                    ->where('users.user_phone',$user_session_data->user_phone)
                    ->where('orders.cart_id',$id)
                    ->orderby('orders.order_id','desc')
                    ->first();
    
    $profile_sub_orders=DB::table('sub_orders')
                    ->select('*')
                    ->join('users','users.user_id','=','sub_orders.user_id')
                    ->where('users.user_phone',$user_session_data->user_phone)
                    ->where('sub_orders.cart_id',$id)
                    ->orderby('sub_orders.sub_order_id','desc')
                    ->get();
    //dd($profile_orders);
    //dd($profile_sub_orders);
    
    return view($this->viewPath.'profileSubOrders',compact('catarray','categories','catpcount','products','catproductrels','catproductdetails','profile_main_orders','profile_sub_orders','cat_crousel','coupondata','cat'));

}
//added code for sub order listing of profile end


//added code to get ordr detail beg
public function orderdetail(Request $request,$id){
  $cat_crousel=$this->cat_crousel;
  $coupondata=$this->coupondata;
  $cat=$this->cat;
  $id=$id;
  /*$orderproducts=DB::table('orderproducts')->select('orderproducts.*')
                 ->where('orderproducts.order_id','=',$id)
                 ->get();*/
    $orderid=DB::table('orders')->select('orders.*','orders.order_id','orders.user_id')->where('cart_id','=',$id)->first();
    $reviewDetails=DB::table('order_rating_reviews')->select('order_rating_reviews.*')->where('order_cart_id','=',$id)->where('status',1)->first();
    //dd($reviewDetail);
    //dd($orderid->delivery_charge);
   // dd($orderid->order_status);
    $coupon_discount=$orderid->coupon_discount;
    $order_total_price=$orderid->total_price;
    $orderproducts=DB::table('store_orders')->select('store_orders.*')
                 ->where('store_orders.order_cart_id','=',$id)
                 ->where('store_orders.cancel_status','=',0)
                 //->where('store_orders.cancel_status','=',1)
                 //->where('store_orders.cancel_status','=',0)
                 ->get();
                 //dd($orderproducts);
 return view($this->viewPath.'orderdetail',compact('cat_crousel','orderproducts','orderid','coupon_discount','order_total_price','coupondata','cat','reviewDetails'));
}
//added code to get ordr detail end

//added code to get sub ordr detail beg
public function suborderdetail(Request $request,$id){
  $cat_crousel=$this->cat_crousel;
  $coupondata=$this->coupondata;
  $cat=$this->cat;
  $id=$id;
  /*$orderproducts=DB::table('orderproducts')->select('orderproducts.*')
                 ->where('orderproducts.order_id','=',$id)
                 ->get();*/
    $orderid=DB::table('sub_orders')->select('sub_orders.*','sub_orders.sub_order_id','sub_orders.user_id')->where('sub_order_cart_id','=',$id)->first();
    $reviewDetails=DB::table('order_rating_reviews')->select('order_rating_reviews.*')->where('order_cart_id','=',$id)->where('status',1)->first();
    //dd($reviewDetail);
    //dd($orderid->delivery_charge);
   // dd($orderid->order_status);
    $coupon_discount=$orderid->coupon_discount;
    $order_total_price=$orderid->total_price;
    $orderproducts=DB::table('store_orders')->select('store_orders.*')
                 ->where('store_orders.sub_order_cart_id','=',$id)
                 ->where('store_orders.cancel_status','=',0)
                 //->where('store_orders.cancel_status','=',1)
                 //->where('store_orders.cancel_status','=',0)
                 ->get();
                 //dd($orderproducts);
 return view($this->viewPath.'sub_orderdetail',compact('cat_crousel','orderproducts','orderid','coupon_discount','order_total_price','coupondata','cat','reviewDetails'));
}
//added code to get sub ordr detail end

//added code to delete order beg
public function deleteorder(Request $request,$cartid){
  #condition to check if order already cancelled beg
  
  #condition to check if order already cancelled end
  //dd($request->all());
        $cart_id    =  $cartid;
       if(isset($request->submit)){

        $user       =   DB::table('orders')
                            ->where('cart_id',$cart_id)
                            ->first();
          //dd($user);
        $user_id1   =   $user->user_id;
        $reason         =   $request->cancel_reason;
        $order_status   =   'Cancelled'; 
         $updated_at     =   date('Y-m-d');
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
          $storeOrderInfo =   DB::table('store_orders')
                                ->where('store_orders.order_cart_id', $cart_id)
                                ->update(['cancel_status'  =>  1]);
          #get data of cancelled order
          $cancelorderdata= DB::table('orders')
                        ->where('cart_id', $cart_id)
                        ->where('order_status','Cancelled')
                        ->first();
          $cancel_amount=$cancelorderdata->total_price;
          $cancel_user_id=$cancelorderdata->user_id;
          #get user current wallet amount
           $wallet_previous_amount=DB::table('users')
                        ->where('user_id',$cancel_user_id)
                        ->first();
          #update wallet amount after cancel order beg
          $total_wallet_after_cancel=$wallet_previous_amount->wallet + $cancel_amount;
          $updatewallet=DB::table('users')
                        ->where('user_id',$cancel_user_id)
                        ->update(['wallet'=>$total_wallet_after_cancel]);
          #update wallet amount after cancel order end
          #update rewards after cancel order
            $getcart_rewards=DB::table('cart_rewards')
                            ->where('cart_id',$cart_id)
                            ->where('user_id',$cancel_user_id)
                            ->first();
              if(isset($getcart_rewards)){
                 $updatewallet=DB::table('users')
                        ->where('user_id',$cancel_user_id)
                        ->update(['rewards'=>$wallet_previous_amount->rewards-$getcart_rewards->rewards]);
          #update wallet amount after cancel order end

         
              }
               #added code to update stock after cancel order beg
          $storeOrderInfo =   DB::table('store_orders')
                                ->where('store_orders.order_cart_id', $cart_id)
                                ->get();
              //dd($storeOrderInfo);

             if(isset($storeOrderInfo)){
              foreach($storeOrderInfo as $storeOrderInfos){
            $getstockdata=DB::table('store_products')
                          ->where('varient_id','=',$storeOrderInfos->varient_id)
                          ->first();
          
          $currentstock=$getstockdata->stock ?? 0;
          $remainingstock= $currentstock + $storeOrderInfos->qty;
          $updatestore=DB::table('store_products')
                       ->where('varient_id','=',$storeOrderInfos->varient_id)
                       ->update(['stock'=>$remainingstock]);
                     }
           }
          #added code to update stock after cancel order end

          #added code to update rewards minus after cancel item beg
            $previous_reward=DB::table('users')
              ->where('user_id','=',$user->user_id)
              ->first();
          #added code to update rewards minus after cancel item end
          #get reward points of this cancel order by cartid beg
          $cartreward=DB::table('cart_rewards')
                     ->where('cart_id', $cart_id)
                     ->where('user_id', $user->user_id)
                     ->first();
            if(isset($cartreward)){
              #subdract rewards from user reward of cancelled order
              $finalreward=$previous_reward->rewards - $cartreward->rewards;
              $updatereward=DB::table('users')
                           ->where('user_id',$user->user_id)
                           ->update(['rewards'=>$finalreward]); 

            }
          #get reward points of this cancel order by cartid end

            return redirect('/orders')->with('msg', ' Order  cancelled Successfully');
  }
}


//added code to delete sub order beg
public function deletesuborder(Request $request,$cartid,$subcartid){
  #condition to check if order already cancelled beg
  
  #condition to check if order already cancelled end
  //dd($request->all());
        $cart_id    =  $cartid;
        $sub_order_id    =  $subcartid;
        $date_of_recharge  = carbon::now();

        if(isset($request->submit)){
        $curr = DB::table('currency')
             ->first();  

        $main_order       =   DB::table('orders')
                            ->where('cart_id',$cart_id)
                            ->first(); 

        // $user       =   DB::table('sub_orders')
        //                     ->where('sub_order_id',$sub_order_id)
        //                     ->first();
        $delete_sub_order  =   DB::table('sub_orders')
                            ->where('sub_order_id',$sub_order_id)
                            ->first();                    
        $storeId = $delete_sub_order->store_id;
        $sub_order_cart_id =  $delete_sub_order->sub_order_cart_id;                    

        $userData = DB::table('users')
                     ->where('user_id',$delete_sub_order->user_id)
                     ->first();

        $var = DB::table('store_orders')
            ->where('order_cart_id', $cart_id)
            ->where('sub_order_cart_id', $sub_order_cart_id)
            ->where('store_approval',1)
            ->get();

        $price2 = 0;    

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

        $refundAmt=0;
        if($delete_sub_order->payment_method == 'COD' || $delete_sub_order->payment_method == 'Cod' || $delete_sub_order->payment_method == 'cod'){
          $newbal1 = $userData->wallet;
          $refundAmt=0;

        }else if($delete_sub_order->payment_method == 'wallet' || $delete_sub_order->payment_method == 'Wallet' || $delete_sub_order->payment_method == 'WALLET'){
          $newbal1 = $userData->wallet+$delete_sub_order->paid_by_wallet;
          $refundAmt=$delete_sub_order->paid_by_wallet;

        }else{
          $newbal1 = $userData->wallet+$delete_sub_order->total_price;
          $refundAmt=$delete_sub_order->total_price;
        }

                                         


        //dd($user);
        $user_id1   =   $delete_sub_order->user_id;
        $reason         =   $request->cancel_reason;
        $order_status   =   'Cancelled'; 
        $updated_at     =   date('Y-m-d');
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

          //dd($sub_order_id);
          if($delete_sub_order->payment_method == 'COD' || $delete_sub_order->payment_method == 'Cod' || $delete_sub_order->payment_method == 'cod'){
            $sub_ordupdate = DB::table('sub_orders')
                     ->where('sub_order_id',$sub_order_id)
                     ->update(['total_price' =>0,'rem_price'=>0,'paid_by_wallet'=>0,'delivery_charge'=>0,'coupon_discount'=>  0,'coupon_id'=>0,
                      'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0,'order_status'=>$order_status,'cancelling_reason'=> $reason]);

          }else if($delete_sub_order->payment_method == 'wallet' || $delete_sub_order->payment_method == 'Wallet' || $delete_sub_order->payment_method == 'WALLET'){
            $sub_ordupdate = DB::table('sub_orders')
                     ->where('sub_order_id',$sub_order_id)
                     ->update(['total_price' =>0,'rem_price'=>0,'paid_by_wallet'=>0,'delivery_charge'=>0,'coupon_discount'=>  0,'coupon_id'=>0,
                      'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0,'order_status'=>$order_status,'cancelling_reason'=> $reason]);

          }else{
            $sub_ordupdate = DB::table('sub_orders')
                     ->where('sub_order_id',$sub_order_id)
                     ->update(['total_price' =>0,'rem_price'=>0,'paid_by_wallet'=>0,'coupon_discount'=>  0,'coupon_id'=>0,'delivery_charge'=>0,'bulk_order_based_discount'=> 0,'online_payment_based_discount'=> 0,'order_status'=>$order_status,'cancelling_reason'=> $reason]);
          }

          
          if($sub_ordupdate){
            // $order  =   DB::table('sub_orders')
            //               ->where('sub_order_id',$sub_order_id)
            //               ->update( $orderData );
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
                  'total_price'=>$main_order->total_price-$delete_sub_order->total_price,
                  'paid_by_wallet' =>$main_order->paid_by_wallet-$delete_sub_order->paid_by_wallet,
                  'rem_price'=>$main_order->rem_price-$delete_sub_order->rem_price,
                  'delivery_charge'=>$main_order->delivery_charge-$delete_sub_order->delivery_charge,
                  'coupon_discount'=>$main_order->coupon_discount-$delete_sub_order->coupon_discount,
                  'bulk_order_based_discount'=>$main_order->bulk_order_based_discount-$delete_sub_order->bulk_order_based_discount,
                  'online_payment_based_discount'=>$main_order->online_payment_based_discount-$delete_sub_order->online_payment_based_discount,
              ]);
            }

            $userwalletupdate = DB::table('users')
                                    ->where('user_id',$delete_sub_order->user_id)
                                    ->update(['wallet'=>$newbal1]);
            if($userwalletupdate){
              DB::table('wallet_recharge_history')
                ->insert([
                  'amount'        =>  $refundAmt,
                  'type'          =>  'Sub Order Cancel Amount',
                  'order_cart_id' =>  $sub_order_cart_id,
                  'user_id'       =>  $delete_sub_order->user_id,
                  'date_of_recharge' =>  $date_of_recharge,
                  'recharge_status'  =>  'success',
                ]);
            }


            $storeOrderInfo =  DB::table('store_orders')
                                ->where('store_orders.sub_order_cart_id', $sub_order_cart_id)
                                ->update(['cancel_status'  =>  1,'cancel_reason'  =>  $reason]);
            #get data of cancelled order
            $cancelorderdata= DB::table('sub_orders')
                        ->where('sub_order_id',$sub_order_id)
                        ->where('order_status','Cancelled')
                        ->first();

            //$cancel_amount=$cancelorderdata->total_price;
            $cancel_user_id=$cancelorderdata->user_id;

            #get user current wallet amount
            $wallet_previous_amount=DB::table('users')
                        ->where('user_id',$cancel_user_id)
                        ->first();
          
            #update wallet amount after cancel order beg
            /*$total_wallet_after_cancel=$wallet_previous_amount->wallet + $cancel_amount;
            $updatewallet=DB::table('users')
                        ->where('user_id',$cancel_user_id)
                        ->update(['wallet'=>$total_wallet_after_cancel]);*/
            #update wallet amount after cancel order end
          
            #update rewards after cancel order
            $getcart_rewards=DB::table('cart_rewards')
                            ->where('cart_id',$cart_id)
                            ->where('user_id',$cancel_user_id)
                            ->where('reward_status',1)
                            ->first();
            if(isset($getcart_rewards)){
              $updatewallet=DB::table('users')
                        ->where('user_id',$cancel_user_id)
                        ->update(['rewards'=>$wallet_previous_amount->rewards-$getcart_rewards->rewards]);

              $getcart_rewards_update=DB::table('cart_rewards')
                            ->where('cart_id',$cart_id)
                            ->where('user_id',$cancel_user_id)
                            ->update(['reward_status'=>0]);          
              #update wallet amount after cancel order end
            }
            #added code to update stock after cancel order beg
            $storeOrderInfo =   DB::table('store_orders')
                                ->where('store_orders.order_cart_id', $cart_id)
                                ->where('store_orders.sub_order_cart_id', $sub_order_cart_id)
                                ->get();
            //dd($storeOrderInfo);

            if(isset($storeOrderInfo)){
              foreach($storeOrderInfo as $storeOrderInfos){
                $getstockdata=DB::table('store_products')
                          ->where('varient_id','=',$storeOrderInfos->varient_id)
                          ->first();
                $currentstock=$getstockdata->stock ?? 0;
                $remainingstock= $currentstock + $storeOrderInfos->qty;
                $updatestore=DB::table('store_products')
                  ->where('varient_id','=',$storeOrderInfos->varient_id)
                  ->update(['stock'=>$remainingstock]);
              }
            }

            #added code to update stock after cancel order end

             $notification_title = "WooHoo ! Sub Order Cancelled";
                $notification_text = "The order of sub order cart id #".$sub_order_cart_id." contains of " .$prod_name." of price ".$curr->currency_sign." ".$delete_sub_order->total_price. " is cancelled.";
                
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

          }else{
            return redirect('/orders')->with('msg', ' Sub Order Cancelled Failed!');
          }           

        return redirect('/sub_orders/'.$cart_id)->with('msg', ' Sub Order Cancelled Successfully');
  }
}



  public function orderReviewSubmit(Request $request,$cartid){

    //dd($request->all());
        $cart_id    =  $cartid;
       if(isset($request->submit)){

        $user       =   DB::table('orders')
                            ->where('cart_id',$cart_id)
                            ->first();
        if($user){
          $insert=  DB::table('order_rating_reviews')
                    ->insert(['order_cart_id'=>$cart_id,
                    'user_id'=>$request->user_id,
                    'order_rating'=>$request->rating,
                    'order_comment'=> $request->order_review,
                    'status'=>1
                  ]);
        }

        return redirect()->back()->with('message','Order review submitted successfully');                   
        //return redirect('/orders')->with('msg', ' Order review submitted successfully');
      }
   }
    


//added code to delete order end
//added code to cancel item beg
  public function cancelitem(Request $request){
      //dd($request->all());
      $userId                   = $request->userid;
      $removed_item_total_price = $request->removed_item_total_price;
      $cartId                   = $request->cartid;
      $sub_cartId               = $request->subcartid;
      $varientid                = $request->varientid;
      $cancelReason             = $request->cancel_reason;
      $orderItemId              = $request->orderItemId;

      $date_of_recharge  = date('Y-m-d');

      $userInfo   =   DB::table('users')
        ->where('user_id', $userId)
        ->first();

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
        $storeOrderUpdate['image']  =  '/images/order_cancel_images/'.$fileName;
      }

      $storeOrderData = DB::table('store_orders')
          ->leftjoin('product_varient', 'store_orders.varient_id', '=', 'product_varient.varient_id')
          ->select('product_varient.*','store_orders.qty as qunatity','store_orders.price as store_total_price','store_orders.sub_order_cart_id as sub_cart_id')
          //->where('store_orders.order_cart_id', $cartId)
          //->where('store_orders.sub_order_cart_id', $sub_cartId)
          ->where('store_orders.store_order_id', $orderItemId)
          ->first();
      //dd($storeOrderData);                      

      //update store item status to hide it from order calculation brg
      $update = DB::table('store_orders')
          ->where('store_order_id', $orderItemId)
          ->update($storeOrderUpdate);
              
      //update store item status to hide it from order calculation end

      //subtract removed item price from order total in order table where cartid is common beg
      $getordertotal=DB::table('orders')
                             //->select('total_price')
          ->where('cart_id','=',$cartId)
          ->first();
      $getsubordertotal=DB::table('sub_orders')
                             //->select('total_price')
          ->where('cart_id','=',$cartId)
          ->where('sub_order_cart_id','=',$sub_cartId)
          ->first();

      $sub_orderDataMain = DB::table('store_orders')
          ->where('store_orders.order_cart_id', $cartId)
          ->where('store_orders.sub_order_cart_id', $sub_cartId)
          ->get();

      $sub_product_total_count = $sub_orderDataMain->count();
      $sub_product_cancel_count = $sub_orderDataMain->where('cancel_status', '1')->count();

      $storeOrdersCount = DB::table('store_orders')
          ->where('order_cart_id', $cartId)
          ->where('cancel_status', 0)
          ->get();

      $refundAmt=0;

      //If complete item cancel
      if (count($storeOrdersCount) <= 0) {
        //Single available item in main order
        $user = DB::table('orders')
            ->where('cart_id',$cartId)
            ->first();
        $sub_order_check  = DB::table('sub_orders')
            ->where('cart_id',$cartId)
            ->where('sub_order_cart_id',$sub_cartId)
            ->first();

        //$refundAmt =$sub_order_check->total_price;   

        $refundAmt=$sub_order_check->paid_by_wallet;          

        if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod'){
            $newbal1 = $userInfo->wallet + $refundAmt;
            $returnAmt = $sub_order_check->paid_by_wallet;
            //$refundAmt=$sub_order_check->paid_by_wallet;

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
              $walletRecHistory = DB::table('wallet_recharge_history')
                ->insert([
                  'amount'           =>  $refundAmt,
                  'type'             =>  'Item Cancel Amount',
                  'order_store_id'    => $orderItemId,
                  'order_cart_id'    =>  $sub_cartId,
                  'user_id'          =>  $userId,
                  'date_of_recharge' =>  $date_of_recharge,
                  'recharge_status'  =>  'success',
              ]);
        }                        

        $updated_at = Carbon::now();

        $sub_order_u  =   DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cartId)
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

        //End of single available item cancel
       //dd('/sub_orders/'.$cartId);      
      }else{

        //Start of multiple available item cancel

        $user    = DB::table('orders')
          ->where('cart_id',$cartId)
          ->first(); 
        $sub_order_check    = DB::table('sub_orders')
          ->where('cart_id',$cartId)
          ->where('sub_order_cart_id',$sub_cartId)
          ->first();

        //dd($sub_order_check);  


        $subRefundAmt=$sub_order_check->paid_by_wallet;
        $subDiscountAmt = $sub_order_check->coupon_discount;
        $subOnline_payment_discount = $sub_order_check->online_payment_based_discount;
        $subBulk_order_discount = $sub_order_check->bulk_order_based_discount;
        $subRemAmount=$sub_order_check->rem_price;
        $subtotalAmount=$sub_order_check->total_price;
        $couponCode=$sub_order_check->coupon_id;
        $subDeliveryCharge=$sub_order_check->delivery_charge;
        //dd($subtotalAmount);

        $itemCancelAmount =$removed_item_total_price;


        $sub_order_check_old    = DB::table('store_orders')
          ->where('order_cart_id',$cartId)
          ->where('sub_order_cart_id',$sub_cartId)
          ->get();
        $sub_order_check_new    = DB::table('store_orders')
          ->where('order_cart_id',$cartId)
          ->where('sub_order_cart_id',$sub_cartId)
          ->where('cancel_status',0)
          ->get();

        $lsubOldTotal=$sub_order_check_old->sum('price');
          
        $lsubNewTotal=$sub_order_check_new->sum('price');
        //dd($lsubOldTotal.' @ '.$lsubNewTotal);

        
        
        //dd($newSubTotalLatest);
        
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

        //dd('sds');

          
        
        //dd($getComDetail);
        //dd($returnAmontDone);

        //dd($sub_order_check_new);

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
        
        //dd($refundAmt.' @ '.$lastRemAmountDeduct);
         

        //$price = $userInfo->wallet + $storeOrderData->store_total_price;
            
        if($user->payment_method == 'COD' || $user->payment_method == 'Cod' || $user->payment_method == 'cod') {
            //$newbal1 = $userInfo->wallet + $refundAmt;
            $newbal1 = $userInfo->wallet; 
        }else if($user->payment_method == 'wallet' || $user->payment_method == 'Wallet' || $user->payment_method == 'WALLET') {
          // if($user->payment_status=='success'){
          //   $checkRePrice = $user->paid_by_wallet-$storeOrderData->store_total_price;
          //   if($checkRePrice>=0){
          //     $newbal1 = $userInfo->wallet + $refundAmt; 
          //   }else{       
          //     if($user->paid_by_wallet>0){
          //       $newbal1 = $userInfo->wallet+$refundAmt;
          //     }else{
          //       $newbal1 = $userInfo->wallet+$refundAmt;
          //     }
          //   }

          // }else{
            $newbal1 = $userInfo->wallet+$refundAmt;
          // }
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
                                      'order_store_id'=>  $orderItemId,
                                      'order_cart_id' =>  $sub_cartId,
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
          } else if($user->payment_method == 'Wallet' || $user->payment_method == 'Wallet' || $user->payment_method == 'wallet'){
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
            
          }else{
            $remainingtotal = 0;
            $paidByWallet = 0;
          }

          $updated_at = Carbon::now();

          if($sub_product_total_count==$sub_product_cancel_count){
            //when suborder last item cancel

            $newSubTotal = $sub_order_check->total_price-$refundAmt;
            //dd($newSubTotal);

            //dd('last cancel avail');
            if($sub_order_check->payment_method=='wallet' || $sub_order_check->payment_method=='Wallet' || $sub_order_check->payment_method=='WALLET'){
              
              $latestCouponCode = $sub_order_check->coupon_id;
               if($latestDisCharges==0){
                $latestCouponCode = 0;
               }

              $sub_order = DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cartId)
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
              ->where('sub_order_cart_id', $sub_cartId)
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
               ->where('sub_order_cart_id', $sub_cartId)
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

            //Item available then update
            
            $newSubTotal = $sub_order_check->total_price-$refundAmt;
                  

           //dd($newSubTotal);
           if($sub_order_check->payment_method=='wallet' || $sub_order_check->payment_method=='Wallet' || $sub_order_check->payment_method=='WALLET' || $sub_order_check->payment_method=='cod' || $sub_order_check->payment_method=='Cod' || $sub_order_check->payment_method=='COD'){ 
            $getComDetail = $this->checkAllDiscountDeliveryWithoutOnline($newSubTotalLatest,$sub_order_check->store_id);
           }else{
            $getComDetail = $this->checkAllDiscountDelivery($newSubTotalLatest,$sub_order_check->store_id);
           } 
           //dd($getComDetail);
            //'total_price' =>   $getComDetail['latestTotalAmount'],
            // if($newDeliveryCharge>0){
           //  if($couponCode!='' && $couponCode!=0){
           //  $couponCheckD = DB::table('coupon')->where('coupon_code','=',$couponCode)->first();
           //  if($couponCheckD->cart_value<=$newSubTotalLatest){
              
           //    if($couponCheckD->type=='price'){
           //      $latestDisCharges =$couponCheckD->amount;
           //    }else{
           //      $latestDisCharges =$getComDetail['totalAmount']*$couponCheckD->amount/100;
           //    }
           //    $returnAmontDone = $subtotalAmount-$getComDetail['latestTotalAmount']+$latestDisCharges;
           //  }
           // }
            if($sub_order_check->payment_method=='wallet' || $sub_order_check->payment_method=='Wallet' || $sub_order_check->payment_method=='WALLET'){
               $latestCouponCode = $sub_order_check->coupon_id;
               if($latestDisCharges==0){
                $latestCouponCode = 0;
               }

              $sub_order  =   DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cartId)
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
              ->where('sub_order_cart_id', $sub_cartId)
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
              ->where('sub_order_cart_id', $sub_cartId)
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

              

              
            /*}else{
              $sub_order  =   DB::table('sub_orders')
              ->where('cart_id', $cartId)
              ->where('sub_order_cart_id', $sub_cartId)
              ->update([
                'total_price' =>   $sub_order_check->total_price-$storeOrderData->store_total_price,
                'rem_price' =>     $sub_order_check->rem_price-$lastRemAmountDeduct,
                'paid_by_wallet' => $sub_order_check->paid_by_wallet-$refundAmt,
                'updated_at'  =>  $updated_at
              ]);
              $order  =   DB::table('orders')
              ->where('cart_id', $cartId)
              ->update([
                'total_price' =>   $user->total_price-$storeOrderData->store_total_price,
                'rem_price' =>     $user->rem_price-$lastRemAmountDeduct,
                'paid_by_wallet' =>  $user->paid_by_wallet-$refundAmt,
                'updated_at'  =>  $updated_at
              ]); 

            }*/
          } 
        }
      }                           


      // $ordertotalAfterRemovalOfItem=$getordertotal->total_price - $removed_item_total_price;
      // $updateOrderPrice=DB::table('orders')
      //                 ->where('cart_id','=',$cartId)
      //                 ->update(['total_price' =>$ordertotalAfterRemovalOfItem]);
      //subtract removed item price from order total in order table where cartid is common end
      return redirect('/sub_orders/'.$cartId)->with('msg','Item has been removed from your Order');
    }
//added code to cancel item end
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


//added code for reorder beg
  public function main_reorder(Request $request,$cartid){

          #added code to get delivery charge beg
        $delcharge = DB::table('freedeliverycart')
                               ->where('id', 1)
                               ->first();
        $deliverycharge=$delcharge->del_charge;
         $cartTotal=0;
         #added code to get delivery charge end
            
        //get all the data of store orders of that cart id which is to be reorder beg
       $getStoreItems = DB::table('store_orders')
                      ->where('order_cart_id', $cartid)
                      ->get();


      //get all the data of store orders of that cart id which is to be reorder end
      //put this data in session cart id and send it to checkout beg
             $cartpids = Session::get('cartpids'); //get  session
             $tempids=array();
             if($getStoreItems){
                
                 foreach($getStoreItems as $getStoreItem){

                       #added code to get cart total beg
                        $cartTotal+=$getStoreItem->qty * $getStoreItem->price;
                       #added code to get cart total end
                     
                        $tempids['pid'][$getStoreItem->varient_id]=$getStoreItem->qty;
                       
                    
                 }
     #deliver charge beg
               $charge=0;
               if($cartTotal >= $delcharge->min_cart_value){

                $charge=$deliverycharge; 
               }
               if($cartTotal <= $delcharge->min_cart_value){

                $charge=100; 

               }
               if($cartTotal >= 500){
               
                $charge=0; 

               }
               
               $cartTotal=round($cartTotal,2);
               Session::put('delivercharge', $charge); // session delivercharge
               $delivercharge=Session::get('delivercharge');
              #deliver charge end
              Session::put('cartpids', $tempids); //session put
              $cartpids = Session::get('cartpids'); //session

              return redirect('/checkout');
                  

     
      }
    //put this data in session cart id and send it to checkout end

  }
//added code for reorder end

  //added code for resuborder beg
  public function reorder(Request $request,$cartid,$suborderid){

          #added code to get delivery charge beg
        $delcharge = DB::table('freedeliverycart')
                               ->where('id', 1)
                               ->first();
        $deliverycharge=$delcharge->del_charge;
         $cartTotal=0;
         $qty=array();
         $totalcartqty=0; //to show cart total qty on top
         #added code to get delivery charge end
            
        //get all the data of store orders of that cart id which is to be reorder beg
       $subOrderDetail = DB::table('sub_orders')
                      ->where('cart_id', $cartid)
                      ->where('sub_order_id', $suborderid)
                      ->first();
                        
       $getStoreItems = DB::table('store_orders')
                      ->where('order_cart_id', $cartid)
                      ->where('sub_order_cart_id', $subOrderDetail->sub_order_cart_id)
                      ->get();
       //dd($getStoreItems);               


      //get all the data of store orders of that cart id which is to be reorder end
      //put this data in session cart id and send it to checkout beg
             $cartpids = Session::get('cartpids'); //get  session
             $tempids=array();
             if($getStoreItems){
                
                 foreach($getStoreItems as $getStoreItem){

                       #added code to get cart total beg
                        //$cartTotal+=$getStoreItem->qty * $getStoreItem->price;
                        $cartTotal+=$getStoreItem->price;
                       #added code to get cart total end
                     
                        $tempids['pid'][$getStoreItem->varient_id]=$getStoreItem->qty;
                       
                    
                 }
                 #deliver charge beg

                 $newCharge=0;
                 $bulkOrderDiscount=0;
                 $newDelcharge=DB::table('freedeliverycart_by_store')
                 ->where('delivery_store_id',$subOrderDetail->store_id) 
                 ->where('min_cart_value','>=',$cartTotal)
                 ->first();

                if($newDelcharge){
                  $kk = $newDelcharge->del_charge;
                }else{
                  $kk = 0;
                }   
                $newCharge= $kk;

                $newBulkDiscount=DB::table('bulk_order_discount')
                ->where('bulk_order_min_amount','<=',$cartTotal)
                ->where('bulk_order_max_amount','>=',$cartTotal)
                ->first(); 

                if($newBulkDiscount){
                  if($newBulkDiscount->bulk_order_discount_type=='percentage'){
                    $bb = $cartTotal*$newBulkDiscount->bulk_order_discount/100;  
                  }else{
                    $bb =$newBulkDiscount->bulk_order_discount;
                  }  
                }else{
                  $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
                  $maxBDiscountData = DB::table('bulk_order_discount')
                  ->where('bulk_order_max_amount',$maxValueAmountLimit)
                  ->first();
                  if($cartTotal>$maxValueAmountLimit){
                    if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                      $bb = $cartTotal*$maxBDiscountData->bulk_order_discount/100;  
                    }else{
                      $bb =$maxBDiscountData->bulk_order_discount;
                    }
                  }else{
                    $bb = 0;
                  }
                }

                $bulkOrderDiscount+= $bb;

               
               $cartTotal=round($cartTotal,2);
               $bulkOrderDiscount=round($bulkOrderDiscount,2);
               Session::put('delivercharge', $newCharge); // session delivercharge
               Session::put('bulkOrderDiscount', $bulkOrderDiscount);
               $delivercharge=Session::get('delivercharge');
              #deliver charge end
              Session::put('cartpids', $tempids); //session put
              $cartpids = Session::get('cartpids'); //session

              return redirect('/checkout');
                  

     
      }
    //put this data in session cart id and send it to checkout end

  }
//added code for sub reorder end

  #added code for rewards beg
  public function rewards(Request $request){

   
       $user_session_data=Session::get('userData');
       $cat_crousel=$this->cat_crousel;
       $coupondata=$this->coupondata;
       $catarray= $this->catarray;
       $categories= $this->categories;
       $catpcount= $this->catpcount;
       $products= $this->products;
       $catproductrels= $this->catproductrels;
       $catproductdetails= $this->catproductdetails;
       $cat= $this->cat;
       $get_rewards=DB::table('users')
                    ->select('rewards')
                  ->where('user_id','=',$user_session_data->user_id)
                  ->first();
        //dd($get_rewards);

    return view($this->viewPath.'rewards',compact('catarray','categories','catpcount','products','catproductrels','catproductdetails','cat_crousel','get_rewards','coupondata','cat'));
  }
  #added code for rewards end
  #added code for wallet amount beg
  public function wallet(Request $request){
      $cat_crousel=$this->cat_crousel;
      $coupondata=$this->coupondata;
       $catarray= $this->catarray;
       $categories= $this->categories;
       $catpcount= $this->catpcount;
       $products= $this->products;
       $catproductrels= $this->catproductrels;
       $catproductdetails= $this->catproductdetails;
       $cat= $this->cat;
     $user_session_data=Session::get('userData');
    $user_id =$user_session_data->user_id;

    $wallet   = DB::table('users')
                        ->select('wallet')
                        ->where('user_id', $user_id)
                        ->first();
    //dd($wallet->wallet);
    return view($this->viewPath.'wallet',compact('catarray','categories','catpcount','products','catproductrels','catproductdetails','cat_crousel','wallet','user_session_data','coupondata','cat'));

  }
  #added code for wallet amount end
  #recharge wallet beg
  public function walletinitiate(Request $request){
    //dd($request->rechargeAmount);
    #amount restriction beg
    $cat=$this->cat;
    if($request->rechargeAmount > 10000){
      return redirect()->back()->with('message','Recharge amount allowed up to 10,000');    }
    #amount restriction end
    #from session get user data n pass it in razorpay beg
    $user_session_data=Session::get('userData');
    //dd($user_session_data);
    #from session get user data n pass it in razorpay end

    #added code to get form data of recharge in sae it in response array beg
     $cat_crousel=$this->cat_crousel;
            $api= new Api($this->razorpayid,$this->razorpaykey);
            //generate random reciept id
            $recieptid=Str::random(20);
            //create order
            $order = $api->order->create(array(
              'receipt' => $recieptid,
              'amount' => $request->all()['rechargeAmount'] * 100,
              'currency' => 'INR'
              )
    );
            //lets return the response
            //lets create the razorpay payment page
            $response=[
             'orderId' =>$order['id'],
             'razorpayId' =>$this->razorpayid,
             'amount' =>$request->all()['rechargeAmount'] * 100,
             'name' =>$user_session_data->user_name,
             'currency' => 'INR',
             'email' => $user_session_data->user_email,
             'contactnumber' => $user_session_data->user_phone,
             'description' => 'Recharge Wallet',
             
            ];
            //dd($response);
        return view($this->viewPath.'wallet-payment-page',compact('response','cat_crousel','cat'));
    #added code to get form data of recharge in sae it in response array end
   // dd($request->all());
  
  /*$curr = DB::table('currency')
             ->first();
  
  $add_to_wallet=$request->rechargeAmount;
    #user previous wallet amount
     $wallet_amt = DB::table('users')
                    ->select('wallet')
                    ->where('user_id', $request->userid) 
                    ->first();
      $date_of_recharge= carbon::now();            
        $amount = $wallet_amt->wallet;
        $added = $add_to_wallet + $amount;
        $currentDate = date('Y-m-d'); 

        #user detail to send sms and email beg
        $ph = DB::table('users')
                  ->select('user_phone', 'user_email','user_name')
                  ->where('user_id',$request->userid)
                  ->first();
        $user_phone = $ph->user_phone;
        $user_email = $ph->user_email;
        $user_name = $ph->user_name;
        #user detail to send sms and email end
        #update wallet amount of logined user beg
        $wallet_amt = DB::table('users')
                    ->where('user_id', $request->userid)
                    ->update(['wallet'=>$added]);
             
              $insert=  DB::table('wallet_recharge_history')
                    ->insert(['user_id'=>$request->userid,
                    'amount'=>$add_to_wallet,
                    'date_of_recharge'=>$date_of_recharge,
                    'recharge_status'=> 1]);  */
        #update wallet amount of logined user end
        /*if($insert){
          //send sms start
          // start sms
            $sms = DB::table('notificationby')
                   ->select('sms')
                   ->where('user_id',$request->userid)
                   ->first();
            $sms_status = $sms->sms;  
            $sms_api_key=  DB::table('msg91')
                    ->select('api_key', 'sender_id')
                      ->first();
              $api_key = $sms_api_key->api_key;
              $sender_id = $sms_api_key->sender_id;
            if($sms_status == 1){
                 $rechargeSms = $this->rechargesms($curr,$user_name, $add_to_wallet,$user_phone);
            }
          //send sms send
            return redirect('/wallet')->with('message','wallet Rechraged Successfully');
        }*/

  }
  #recharge wallet end
  #added code to get razorpay response n update wallet amount in users table and insert in rechaege history beg
  public function walletcomplete(Request $request){
 // dd($request->all());
  #update wallet in user table n insertdata in rechrge history beg
    $curr = DB::table('currency')
             ->first();
  $user_session=Session::get('userData');
  $add_to_wallet=$request->amount/100;
    #user previous wallet amount
     $wallet_amt = DB::table('users')
                    ->select('wallet')
                    ->where('user_id', $user_session->user_id) 
                    ->first();
      $date_of_recharge= carbon::now();            
        $amount = $wallet_amt->wallet;
        $added = $add_to_wallet + $amount;
        $currentDate = date('Y-m-d'); 

        #user detail to send sms and email beg
        $ph = DB::table('users')
                  ->select('user_phone', 'user_email','user_name')
                  ->where('user_id',$user_session->user_id)
                  ->first();
        $user_phone = $ph->user_phone;
        $user_email = $ph->user_email;
        $user_name = $ph->user_name;
        #user detail to send sms and email end
        #update wallet amount of logined user beg
        $wallet_amt = DB::table('users')
                    ->where('user_id', $user_session->user_id)
                    ->update(['wallet'=>$added]);
             
              $insert=  DB::table('wallet_recharge_history')
                    ->insert(['user_id'=>$user_session->user_id,
                    'amount'=>$add_to_wallet,
                    'date_of_recharge'=>$date_of_recharge,
                    'recharge_status'=> 1]);  
        #update wallet amount of logined user end
        if($insert){
          //send sms start
          // start sms
          /*  $sms = DB::table('notificationby')
                   ->select('sms')
                   ->where('user_id',$request->userid)
                   ->first();
            $sms_status = $sms->sms;  
            $sms_api_key=  DB::table('msg91')
                    ->select('api_key', 'sender_id')
                      ->first();
              $api_key = $sms_api_key->api_key;
              $sender_id = $sms_api_key->sender_id;
            if($sms_status == 1){
                 $rechargeSms = $this->rechargesms($curr,$user_name, $add_to_wallet,$user_phone);
            } */
          //send sms send
            return redirect('/wallet')->with('message','wallet Recharged Successfully');
        }
  #update wallet in user table n insertdata in rechrge history end
  }
  #added code to get razorpay response n update wallet amount in users table and insert in rechaege history end
  #added code to reddem awrads beg i.e transfer rewards in wallet n update walet in users table
  public function rewardsredeem(Request $request){
    //dd($request->all());
    $user_id = $request->user_id;


        $rewards = DB::table('users')
                    ->select('rewards','wallet')
                    ->where('user_id',$user_id)
                    ->first();
        if($rewards->rewards > 0)  {
          $redeem_points = DB::table('reedem_values')
                                   ->select('value','reward_point')
                                   ->first();
          #update user wallet beg
                if (!is_null($redeem_points)) {
                         $rew = $rewards->rewards;
                        $new        = $rew * $redeem_points->value / $redeem_points->reward_point;
                        $newwallet  = $new + $rewards->wallet;
                        $upuser     =   DB::table('users')
                                            ->where('user_id',$user_id)
                                            ->update([
                                                'rewards' => 0,
                                                'wallet'  => $newwallet
                                            ]);
               return redirect('/rewards')->with('message','Rewards Redeemed');
              }else{
                return redirect('/rewards')->with('message','Something went Wrong');
              }                       
          #update user wallet end
        }else{
          return redirect('/rewards')->with('message','You have not get any rewards yet');
        } 
    }        
  #added code to reddem awrads end
  #added code to show n update address page  beg
    public function myaddress(Request $request,$id){
    $cat=$this->cat;
    $get_city=DB::table('city')->get();
    $cat_crousel=$this->cat_crousel;
      if(isset($request->save)){

     #added code to update address beg

        $singleuserdetail=DB::table('users')
                         ->where('user_id',$id)
                         ->first();
        //  dd($singleuserdetail);

            //update address table
    

 $address_exist_or_not=DB::table('address')
 ->where('user_id',$id)
 ->first();
 //dd($address_exist_or_not);
 $date=date('Y-m-d');
 //dd($address_exist_or_not);
 if(!isset( $address_exist_or_not)){
  //dd('here');
  $society='';
  

    $insert_user_address=DB::table('address')
                 //->where('user_id',$id)
                 ->insert(['user_id'=>$id,'city'=>$request->user_city,'state'=>'','receiver_name'=>'','receiver_phone'=>'','gst_number'=>$request->gst_number ?? '','landmark'=>$request->user_landmark ?? 'N/A','society'=>$request->user_address ?? 'N/A','house_no'=>'','pincode'=>$request->user_zip,'lat'=>'','lng'=>'','select_status'=>'','added_at'=>$date]);

    if(!empty($request->gst_number)){
      $updateuser=DB::table('users')
           ->where('user_id',$id)
           ->update(['user_gst_number'=>$request->gst_number]);

    }
                 
    return redirect('/my-address/'.$id)->with('message','Profile updated successfully');
 }else{
    $update_user_address=DB::table('address')
                 ->where('user_id',$id)
                 ->update(['user_id'=>$id,'city'=>$request->user_city,'state'=>'','gst_number'=>$request->gst_number ?? '','landmark'=>$request->user_landmark ?? 'N/A','society'=>$request->user_address ?? 'N/A','house_no'=>'','pincode'=>$request->user_zip,'lat'=>'','lng'=>'','select_status'=>'','added_at'=>$date]);
//dd($update_user_address);

    if(!empty($request->gst_number)){
      $updateuser=DB::table('users')
           ->where('user_id',$id)
           ->update(['user_gst_number'=>$request->gst_number]);
    }

    $checkUser  =   DB::table('users')
  ->where('user_id', $id)
  ->first();
     Session::put('userData',$checkUser);
     Session::get('userData');
     return redirect('/my-address/'.$id)->with('message','Profile updated successfully');
 }
     #added code to update address end

      }
       $user_session=Session::get('userData');
        //dd($user_session);
        //added code to get address detail of logined user beg
        $profileaddress=DB::table('address')
                       ->select('*')
                       ->where('user_id',$user_session->user_id)
                       ->first();
      return view($this->viewPath.'profileaddress',compact('profileaddress','get_city','cat','cat_crousel'));

    }
  #added code to show n update address page  end
}
