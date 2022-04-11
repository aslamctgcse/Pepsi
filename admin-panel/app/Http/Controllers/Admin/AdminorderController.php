<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Traits\SendMail;
use App\Traits\SendSms;
use Carbon\carbon;

class AdminorderController extends Controller
{
    use SendMail;
    use SendSms;
    
     public function admin_com_orders(Request $request)
    {
         $title = "Completed Order section";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('orders')
             //->join('store','orders.store_id', '=', 'store.store_id')
             //->join('delivery_boy','orders.dboy_id', '=', 'delivery_boy.dboy_id')
             ->join('users', 'orders.user_id', '=','users.user_id')
             //->join('order_rating_reviews', 'orders.cart_id', '=','order_rating_reviews.order_cart_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('order_status', 'completed')
             ->orWhere('order_status', 'Completed')
             //->paginate(10)
             ->get();
        //dd($ord);  
             
         $details  =   DB::table('orders')
    	                ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id')
    	               ->where('store_orders.store_approval',1)
    	               ->get();
        //dd($details);
        $comments  =   DB::table('order_rating_reviews')
                       ->where('status',1)
                       ->get();
        //dd($comments);                               
                              
                
       return view('admin.all_orders.com_orders', compact('title','logo','ord','details','admin','comments'));         
    }

    public function admin_sub_com_orders(Request $request)
    {
         $title = "Completed Sub Order section";
         $admin_email=Session::get('bamaAdmin');
         $admin= DB::table('admin')
                   ->where('admin_email',$admin_email)
                   ->first();
          $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
         $cart_id = $request->cart_id;
         //dd($cart_id);     

                
        $ord =DB::table('sub_orders')
             ->join('store','sub_orders.store_id', '=', 'store.store_id')
             ->join('address', 'sub_orders.address_id','=','address.address_id')
             ->join('delivery_boy','sub_orders.dboy_id', '=', 'delivery_boy.dboy_id')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             //->join('order_rating_reviews', 'orders.cart_id', '=','order_rating_reviews.order_cart_id')
             ->orderBy('sub_orders.delivery_date','DESC')
             ->where('cart_id', $cart_id)
             //->where('order_status', 'completed')
             //->orWhere('order_status', 'Completed')
             //->paginate(10)
             ->get();
        //dd($ord);  
             
         $details  =   DB::table('sub_orders')
                       ->join('store_orders', 'sub_orders.cart_id', '=', 'store_orders.order_cart_id')
                       ->where('sub_orders.cart_id', $cart_id) 
                       ->get();
        //dd($details);
        foreach ($ord as $ords) {
              $details  = DB::table('store_orders')
                         ->where('store_orders.order_cart_id',$ords->cart_id)
                       ->where('store_orders.sub_order_cart_id',$ords->sub_order_cart_id)
                       ->get();         
               $ords->pro_details =  $details;
        }
        //dd($ord);
        $comments  =   DB::table('order_rating_reviews')
                       ->where('status',1)
                       ->get();
        //dd($comments);                               
                              
                
       return view('admin.all_orders.sub_com_orders', compact('title','logo','ord','details','admin','comments'));         
    }

    public function admin_out_orders(Request $request)
    {
         $title = "Confirm Orders/Out for delivery Orders list";
         $admin_email=Session::get('bamaAdmin');
         $admin= DB::table('admin')
                   ->where('admin_email',$admin_email)
                   ->first();
          $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('orders')
             ->join('store','orders.store_id', '=', 'store.store_id')
             ->join('delivery_boy','orders.dboy_id', '=', 'delivery_boy.dboy_id')
             ->join('users', 'orders.user_id', '=','users.user_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('orders.order_status', 'Out_For_Delivery')
             ->orWhere('orders.order_status', 'out_for_delivery')
             ->orWhere('orders.order_status', 'Confirmed')
             ->orWhere('orders.order_status', 'confirmed')
             ->paginate(10);
        //dd($ord);     
             
         $details  =   DB::table('orders')
                        ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id') 
                       ->where('store_orders.store_approval',1)
                       ->get();         
                
       return view('admin.all_orders.out_orders', compact('title','logo','ord','details','admin'));         
    }
    
    
    
      public function admin_can_orders(Request $request)
    {

         $title = "Cancelled Order section";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('orders')
             ->leftjoin('store','orders.store_id', '=', 'store.store_id')
             ->leftjoin('delivery_boy','orders.dboy_id', '=', 'delivery_boy.dboy_id')
             ->join('users', 'orders.user_id', '=','users.user_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('order_status', 'cancelled')
             ->orWhere('order_status', 'Cancelled')
             //->paginate(10); //old commented by me
             ->get(); //added by me to correct filter
             
         $details  =   DB::table('orders')
    	                ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id')
    	               ->where('store_orders.store_approval',1)
    	               ->get();         
                
       return view('admin.all_orders.cancelled', compact('title','logo','ord','details','admin'));         
    }


       public function admin_sub_can_orders(Request $request)
    {

         $title = "Cancelled Sub Order section";
         $admin_email=Session::get('bamaAdmin');
         $admin= DB::table('admin')
                   ->where('admin_email',$admin_email)
                   ->first();
          $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
         $cart_id = $request->cart_id;       
                
        $ord =DB::table('sub_orders')
             ->leftjoin('store','sub_orders.store_id', '=', 'store.store_id')
             //->leftjoin('delivery_boy','orders.dboy_id', '=', 'delivery_boy.dboy_id')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->orderBy('sub_orders.delivery_date','DESC')
             ->where('cart_id', $cart_id)
             //->paginate(10); //old commented by me
             ->get(); //added by me to correct filter
             
         $details  =   DB::table('sub_orders')
                        ->join('store_orders', 'sub_orders.cart_id', '=', 'store_orders.order_cart_id')
                       ->where('store_orders.store_approval',1)
                       ->get(); 
         foreach ($ord as $ords) {
              $details  =   DB::table('store_orders')
                       ->where('store_orders.order_cart_id',$ords->cart_id)
                       ->where('store_orders.sub_order_cart_id',$ords->sub_order_cart_id)
                       ->get();         
               $ords->pro_details =  $details;       

          }              
         //dd($ord);                      
                
       return view('admin.all_orders.sub_cancelled', compact('title','logo','ord','details','admin'));         
    }
    
    
      public function admin_pen_orders(Request $request)
    {
       
         $title = "Pending Order section";
         $admin_email=Session::get('bamaAdmin');
         $store= DB::table('store')
                   ->first();
         $dboy = DB::table('delivery_boy')
                //->where('dboy_id',$id)
                ->first();          

    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('orders')
             ->join('users', 'orders.user_id', '=','users.user_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('orders.order_status', 'Pending')
             ->orWhere('orders.order_status', 'pending')
             //->paginate(10); //old commented by me
             ->get();
             //dd($ord);
        $store_id = $store->store_id;
                   
          $nearbydboy = DB::table('delivery_boy')
                ->leftJoin('orders', 'delivery_boy.dboy_id', '=', 'orders.dboy_id') 
                ->select("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city",DB::raw("Count(orders.order_id)as count"),DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
                * cos(radians(delivery_boy.lat)) 
                * cos(radians(delivery_boy.lng) - radians(" . $store->lng . ")) 
                + sin(radians(" .$store->lat. ")) 
                * sin(radians(delivery_boy.lat))) AS distance"))
               ->groupBy("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city")
               ->where('delivery_boy.boy_city', $store->city)
               ->having("distance", "<", $store->del_range)
                ->orderBy("distance",'asc')
               ->get();
                    
             
         $details  =   DB::table('orders')
    	                ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id') 
    	               ->where('store_orders.store_approval',1)
    	               ->get();         
                
       return view('admin.all_orders.pending', compact('title','logo','ord','details','admin','dboy','nearbydboy'));         
    }


    public function admin_sub_pen_orders(Request $request)
    {
       
         $title = "Pending Sub Order section";
         $admin_email=Session::get('bamaAdmin');
         $store= DB::table('store')
                   ->first();
         $dboy = DB::table('delivery_boy')
                //->where('dboy_id',$id)
                ->first(); 
         $cart_id = $request->cart_id;                

         $admin= DB::table('admin')
                   ->where('admin_email',$admin_email)
                   ->first();
          $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('sub_orders')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->join('store', 'sub_orders.store_id', '=','store.store_id')
             ->orderBy('sub_orders.delivery_date','DESC')
             ->where('cart_id', $cart_id)

             //->where('sub_orders.order_status', 'pending')
             //->where('sub_orders.order_status', 'Cancelled')
             //->paginate(10); //old commented by me
             ->get();
             //dd($ord);
        //dd($ord);      
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
                ->orderBy("distance",'asc')
                ->get();
          foreach ($ord as $ords) {
              $details  =   DB::table('store_orders')
                       ->where('store_orders.order_cart_id',$ords->cart_id)
                       ->where('store_orders.sub_order_cart_id',$ords->sub_order_cart_id)
                       ->get();         
               $ords->pro_details =  $details;       

          }
          //dd($ord);      
                    
             
         $details  =   DB::table('sub_orders')
                        ->join('store_orders', 'sub_orders.cart_id', '=', 'store_orders.order_cart_id') 
                       ->where('store_orders.store_approval',1)
                       ->get();
         //dd($details);                       
                
       return view('admin.all_orders.sub_pending', compact('title','logo','ord','details','admin','dboy','nearbydboy'));         
    }

    /*Order rating & review*/
    public function admin_order_ratings(Request $request)
    {
       
         $title = "Order Rating & Review list";
         $admin_email=Session::get('bamaAdmin');
      

         $admin= DB::table('admin')
                   ->where('admin_email',$admin_email)
                   ->first();
          $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('orders')
             ->join('store','orders.store_id', '=', 'store.store_id')
             ->join('users', 'orders.user_id', '=','users.user_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('orders.order_status', 'Completed')
             ->orWhere('orders.order_status', 'completed')
             ->paginate(10);
                   
                    
             
         $details  =   DB::table('orders')
                        ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id') 
                       ->where('store_orders.store_approval',1)
                       ->get();         
                
       return view('admin.all_orders.rating_review_orders', compact('title','logo','ord','details','admin'));         
    }
    /*End orer rating & review*/
    
    
    public function admin_store_orders(Request $request)
    {

         $title = "Store Order section";
         $id = $request->id;
         $store = DB::table('store')
                ->where('store_id',$id)
                ->first();
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('sub_orders')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->where('sub_orders.store_id',$store->store_id)
             ->orderBy('sub_orders.delivery_date','DESC')
             ->orderBy('sub_orders.sub_order_id','DESC')
             ->where('order_status','!=', 'completed')
             //->paginate(10); //old commented by me
             ->get(); //added by me

             
         $details  =   DB::table('sub_orders')
    	                ->join('store_orders', 'sub_orders.sub_order_cart_id', '=', 'store_orders.sub_order_cart_id') 
    	               ->where('sub_orders.store_id',$id)
    	               ->where('store_orders.store_approval',1)
    	               ->get();         
                
       return view('admin.store.orders', compact('title','logo','ord','store','details','admin'));         
    }
    
    
    
     public function admin_dboy_orders(Request $request)
    {
         $title = "Delivery Boy Order section";
         $id = $request->id;
         $dboy = DB::table('delivery_boy')
                ->where('dboy_id',$id)
                ->first();
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
    
          $date = date('Y-m-d');
     $nearbydboy = DB::table('delivery_boy')
                ->leftJoin('sub_orders', 'delivery_boy.dboy_id', '=', 'sub_orders.dboy_id')
                ->leftJoin('store', 'sub_orders.store_id', '=', 'store.store_id') 
                ->select("store.store_id","store.del_range","delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city",DB::raw("Count(sub_orders.sub_order_id)as count"),DB::raw("6371 * acos(cos(radians(".$dboy->lat . ")) 
                * cos(radians(delivery_boy.lat)) 
                * cos(radians(delivery_boy.lng) - radians(" . $dboy->lng . ")) 
                + sin(radians(" .$dboy->lat. ")) 
                * sin(radians(delivery_boy.lat))) AS distance"))
               ->groupBy("delivery_boy.boy_name","delivery_boy.dboy_id","delivery_boy.lat","delivery_boy.lng","delivery_boy.boy_city")
               //->where('delivery_boy.boy_city', $dboy->boy_city)
               ->where('delivery_boy.dboy_id','!=',$dboy->dboy_id)
               ->having("distance", "<", 'store.del_range')
               ->orderBy("distance",'asc')
               ->orderBy('count')
               
               ->get();
       //dd($nearbydboy);          
    
                
        $ord =DB::table('sub_orders')
             ->join('users', 'sub_orders.user_id', '=','users.user_id')
             ->orderBy('sub_orders.delivery_date','DESC')
             ->orderBy('sub_orders.sub_order_id','DESC')
             ->where('sub_orders.dboy_id',$dboy->dboy_id)
             ->where('order_status','!=', 'completed')
             //->paginate(20);
             ->get();
             
         $details  =   DB::table('sub_orders')
    	               ->join('store_orders', 'sub_orders.cart_id', '=', 'store_orders.order_cart_id') 
    	               ->where('sub_orders.dboy_id',$id)
    	               ->where('store_orders.store_approval',1)
    	               ->get();         
                
       return view('admin.d_boy.orders', compact('title','logo','ord','dboy','details','admin','nearbydboy'));         
    }
    
    public function AllOrder(Request $request)
	{
		//dd($request->all());
		 $title = "All Order section";
         $admin_email = Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
        if (($request->from_date  == '') AND ($request->end_date  == '') AND ($request->pin_code == '')) {
          $ord =DB::table('orders')
             //->join('store','orders.store_id', '=', 'store.store_id')
             ->join('address', 'orders.address_id', '=','address.address_id')
             ->join('users', 'orders.user_id', '=','users.user_id')
             //->join('order_rating_reviews', 'orders.cart_id', '=','order_rating_reviews.order_cart_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('orders.order_status','!=', 'completed')
             ->where('orders.order_status','!=', 'cancelled')
			  //->limit(5)
             //->paginate(10)
             ->get();

      } else {
		  //dd($request->all());
		 if ($request->pin_code) 
		 {
             $ord =DB::table('orders')
             ->join('users', 'orders.user_id', '=','users.user_id')
             ->join('address', 'orders.address_id', '=','address.address_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('orders.order_status','!=', 'completed')
             ->where('orders.order_status','!=', 'cancelled')
             ->where('address.pincode', $request->pin_code)->get();
         }

         if($request->from_date && $request->end_date)
		 {
			 $ord =DB::table('orders')
             ->join('users', 'orders.user_id', '=','users.user_id')
			 ->join('address', 'orders.address_id', '=','address.address_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('orders.order_status','!=', 'completed')
             ->where('orders.order_status','!=', 'cancelled')
             ->whereBetween('order_date', [$request->from_date, $request->end_date])->get();
         }
		 if($request->from_date && $request->end_date && $request->pin_code)
		 {
			 $ord =DB::table('orders')
             ->join('users', 'orders.user_id', '=','users.user_id')
			 ->join('address', 'orders.address_id', '=','address.address_id')
             ->orderBy('orders.delivery_date','DESC')
             ->where('orders.order_status','!=', 'completed')
             ->where('orders.order_status','!=', 'cancelled')
			 ->where('address.pincode', $request->pin_code)
             ->whereBetween('order_date', [$request->from_date, $request->end_date])->get();
         }
         
        }
       //dd($ord);  
         $startDate = $request->from_date??'';    
         $endDate   = $request->end_date??'';    
         $pincode   = $request->pin_code??'';    
         $details  =   DB::table('orders')
    	                ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id')
    	               ->where('store_orders.store_approval',1)
    	               ->get();
        //dd($details);
        $comments  =   DB::table('order_rating_reviews')
                       ->where('status',1)
                       ->get();
        //dd($comments);  
		return view('admin.all_orders.allorders', compact('pincode','endDate','startDate','title','logo','ord','details','admin','comments'));   
	}
    
    public function store_cancelled(Request $request)
    {

         $title = "Store Cancelled Orders";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
        $ord =DB::table('orders')
             ->join('users', 'orders.user_id', '=','users.user_id')
             ->join('address', 'orders.address_id', '=','address.address_id')
             ->orderBy('orders.delivery_date','ASC')
             ->where('order_status','!=', 'completed')
             ->where('order_status','!=', 'cancelled')
              ->where('payment_method','!=', NULL)
             ->where('store_id', 0)
             //->paginate(10); //old commented by me
             ->get();
             
            
        $nearbystores = DB::table('store')
                          ->get();
         
             
         $details  =   DB::table('orders')
    	                ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id') 
    	               ->where('store_orders.store_approval',1)
    	               ->get();         
                
       return view('admin.store.cancel_orders', compact('title','logo','ord','details','admin','nearbystores'));  
    }
    
    
    public function assignstore(Request $request)
    {
         $title = "Store Cancelled Orders";
         $cart_id=$request->id;
         $store = $request->store;
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
      
          $ord =DB::table('orders')
             ->where('cart_id', $cart_id)
             ->update(['store_id'=>$store, 'cancel_by_store'=>0]);
             
      
      return redirect()->back()->withSuccess('Assigned to store successfully');
    }
    
    
    
    
       public function assigndboy(Request $request)
    {
         $cart_id=$request->cart_id;
         $d_boy = $request->d_boy;
         $sub_cart_id = $request->sub_cart_id;
         dd($request);
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
      
          $ord =DB::table('sub_orders')
             ->where('cart_id', $cart_id)
             ->where('sub_order_cart_id', $sub_cart_id)
             ->update(['dboy_id'=>$d_boy]);
             
      
      return redirect()->back()->withSuccess('Assigned to Another Delivery Boy Successfully');
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
        return view('admin.all_orders.reject_modal', compact('cart_id'));
    }
    
    
        public function rejectorder1(Request $request)
    {
         $cart_id=$request->id;
         $ord= DB::table('orders')
    	 		->where('cart_id',$cart_id)
    	 		->first();
    	 $total_price = $ord->rem_price;		
    	 $user = DB::table('users')
    	 		->where('user_id',$ord->user_id)
    	 		->first();	
		$user_id = $user->user_id;		
    	 $wall = $user->wallet;		
    	 $bywallet = $ord->paid_by_wallet;	
    	 if($ord->payment_method != 'COD' || $ord->payment_method != 'cod'|| $ord->payment_method != 'Cod'){
    	$newwallet = $wall + $total_price + $bywallet;
    	$update = DB::table('users')
    	 		->where('user_id',$ord->user_id)
    	 		->update(['wallet'=>$newwallet]);
    	 }	
    	 else{
    	     	$newwallet = $wall + $bywallet;
    	$update = DB::table('users')
    	 		->where('user_id',$ord->user_id)
    	 		->update(['wallet'=>$newwallet]);
    	 }
    	 
         $cause = $request->cause;
         
         $checknotificationby = DB::table('notificationby')
                              ->where('user_id',$user->user_id)
                              ->first();
         if($checknotificationby->sms == 1){
         $sendmsg = $this->sendrejectmsg($cause,$user,$cart_id);
         }
         if($checknotificationby->email == 1){
         $sendmail = $this->sendrejectmail($cause,$user,$cart_id);
         }
         if($checknotificationby->app == 1){
         //////send notification to user//////////
             $notification_title = "Sorry! we are cancelling your order";
                        $notification_text = 'Hello '.$user->user_name.', We are cancelling your order ('.$cart_id.') due to following reason:  '.$cause;
                        $date = date('d-m-Y');
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
         
          $ord =DB::table('orders')
             ->where('cart_id', $cart_id)
             ->update(['cancelling_reason'=>"Cancelled by Admin due to the following reason: ".$cause,
             'order_status'=>"cancelled"]);
         return redirect()->back()->withSuccess('Order Rejected Successfully');
    }

    /*Anish*/

     public function rejectorder(Request $request)
    {
         $cart_id=$request->id;
         $ord= DB::table('orders')
                ->where('cart_id',$cart_id)
                ->first();
         $total_price = $ord->rem_price;        
         $user = DB::table('users')
                ->where('user_id',$ord->user_id)
                ->first();  
        $user_id = $user->user_id;      
         $wall = $user->wallet;     
         $bywallet = $ord->paid_by_wallet;  
         if($ord->payment_method != 'COD' || $ord->payment_method != 'cod'|| $ord->payment_method != 'Cod'){
        $newwallet = $wall + $total_price + $bywallet;
        $update = DB::table('users')
                ->where('user_id',$ord->user_id)
                ->update(['wallet'=>$newwallet]);
         }  
         else{
                $newwallet = $wall + $bywallet;
        $update = DB::table('users')
                ->where('user_id',$ord->user_id)
                ->update(['wallet'=>$newwallet]);
         }
         
         $cause = $request->cause;
         
         $checknotificationby = DB::table('notificationby')
                              //->where('user_id',$user->user_id)  //by anish
                              ->first();
         /*if($user->user_phone){
           $sendmsg = $this->sendrejectmsg($cause,$user,$cart_id);
         }*/ 
         

         //dd($user->user_email);                  
         if($checknotificationby){                     
         if($checknotificationby->sms == 2){ //if($checknotificationby->sms == 1){  // By Anish 14-06-21
         $sendmsg = $this->sendrejectmsg($cause,$user,$cart_id);
         }
         if($checknotificationby->email == 1){
         $sendmail = $this->sendrejectmail($cause,$user,$cart_id);
         }
         if($checknotificationby->app == 1){
         //////send notification to user//////////
             $notification_title = "Sorry! we are cancelling your order";
                        $notification_text = 'Hello '.$user->user_name.', We are cancelling your order ('.$cart_id.') due to following reason:  '.$cause;
                        $date = date('d-m-Y');
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
        }else{
            if($user->user_email){
              $sendmail = $this->sendrejectmail($cause,$user,$cart_id);
            }
        } 
         
          $ord =DB::table('orders')
             ->where('cart_id', $cart_id)
             ->update(['cancelling_reason'=>"Cancelled/Rejected by Admin due to the following reason: ".$cause,
             'order_status'=>"cancelled"]);
         return redirect()->back()->withSuccess('Order Rejected Successfully');
    }


    public function confirm_order(Request $request)
    {
       //dd($request);
       $cart_id= $request->cart_id;
       $dboy_id = $request->dboy_id;

        //$email=Session::get('bamaStore');
         $store= DB::table('store')
                   //->where('email',$email)
                   ->first();
        $store_id = $store->store_id;  
        $curr = DB::table('currency')
             ->first();      
        
          $orr =   DB::table('orders')
                ->where('cart_id',$cart_id)
                ->first();
          $user = DB::table('users')
                ->where('user_id',$orr->user_id)
                ->first();      
               
          $v = DB::table('store_orders')
           ->where('order_cart_id',$cart_id)
           ->get(); 
         
        $getDevice = DB::table('delivery_boy')
                         ->where('dboy_id', $dboy_id)
                        ->select('device_id','boy_name')
                        ->first();  
    
       $orderconfirm = DB::table('orders')
                    ->where('cart_id',$cart_id)
                    ->update(['order_status'=>'Confirmed',
                    'dboy_id'=>$dboy_id]);
                    
          $v = DB::table('store_orders')
           ->where('order_cart_id',$cart_id)
           ->get(); 

        if($orderconfirm){   
          $cause ="Your order is confirmed"; 
          $sendmail = $this->sendrejectmail($cause,$user,$cart_id);
        }  

         if($orderconfirm){

                $notification_title = "You Got a New Order for Delivery on ".$orr->delivery_date;
                $notification_text = "you got an order with cart id #".$cart_id." of price ".$curr->currency_sign." " .$orr->total_price. ". It will have to delivered on ".$orr->delivery_date." between ".$orr->time_slot.".";
                
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


    public function complete_order(Request $request)
    {
       $cart_id= $request->cart_id;
       //$dboy_id = $request->dboy_id;
        //$email=Session::get('bamaStore');
         $store= DB::table('store')
                   //->where('email',$email)
                   ->first();
        $store_id = $store->store_id;  
        $curr = DB::table('currency')
             ->first();      
        
          $orr =   DB::table('orders')
                ->where('cart_id',$cart_id)
                ->first();
               
          $v = DB::table('store_orders')
           ->where('order_cart_id',$cart_id)
           ->get(); 
 
       $orderconfirm = DB::table('orders')
                    ->where('cart_id',$cart_id)
                    ->update(['order_status'=>'Completed']); 
           
         if($orderconfirm){
                
            return redirect()->back()->withSuccess('Order is delivered successfully to customer.');
        }
        else{
        return redirect()->back()->withErrors('Error');
        } 
    }
    public function picked_order_by_delivery_boy(Request $request)
    {
       $cart_id= $request->cart_id;
       //$dboy_id = $request->dboy_id;
        //$email=Session::get('bamaStore');
         $store= DB::table('store')
                   //->where('email',$email)
                   ->first();
        $store_id = $store->store_id;  
        $curr = DB::table('currency')
             ->first();      
        
          $orr =   DB::table('orders')
                ->where('cart_id',$cart_id)
                ->first();
               
          $v = DB::table('store_orders')
           ->where('order_cart_id',$cart_id)
           ->get(); 
 
       $orderconfirm = DB::table('orders')
                    ->where('cart_id',$cart_id)
                    ->update(['order_status'=>'Out_For_Delivery']); 
           
         if($orderconfirm){
                
            return redirect()->back()->withSuccess('Now the delivery boy has the packet for delivery.');
        }
        else{
        return redirect()->back()->withErrors('Error');
        } 
    }
    
}