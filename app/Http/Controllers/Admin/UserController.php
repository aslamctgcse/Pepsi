<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $title = "App User List";
         $admin_email=Session::get('bamaAdmin');

    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();

    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();

           $users = DB::table('users')
                   ->orderBy('reg_date','desc')
                    ->paginate(20);
        
    	return view('admin.user.list', compact('title',"admin", "logo","users"));
    }
    
     public function block(Request $request)
    {
        
        $user_id = $request->id;
         $users = DB::table('users')
                ->where('user_id',$user_id)
                ->update(['block'=>1]);
    if($users){   
    return redirect()->back()->withSuccess('User Blocked Successfully');
    }
    else{
      return redirect()->back()->withErrors('Something Wents Wrong');   
    }
    }
    
    public function unblock(Request $request)
    {
        
        $user_id = $request->id;
         $users = DB::table('users')
                ->where('user_id',$user_id)
                ->update(['block'=>2]);
                
         if($users){   
          return redirect()->back()->withSuccess('User Unblocked Successfully');
        }
        else{
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }

    /**
     * all customer orders.
     * 
     * @param $request
     */
    public function userOrders(Request $request)
    {
      $user_id = $request->id;

      $title = "Customer Orders";
      $admin_email=Session::get('bamaAdmin');

      $admin= DB::table('admin')
             ->where('admin_email',$admin_email)
             ->first();

      $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();

      $orders  =  DB::table('orders')
                      ->LeftJoin('users', 'users.user_id', '=', 'orders.user_id')
                      ->LeftJoin('store', 'store.store_id', '=', 'orders.store_id')
                      ->LeftJoin('delivery_boy', 'delivery_boy.dboy_id', '=', 'orders.dboy_id')
                      ->where('orders.user_id', $user_id)
                      ->paginate(20);

       $details  =   DB::table('orders')
                        ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id') 
                       ->where('store_orders.store_approval', 1)
                       ->get();

      if ($orders) {   
        return view('admin.user.list_order', compact('orders', 'logo', 'admin', 'details', 'title'));
      } else {
        return redirect()->back()->withErrors('Something Wents Wrong');   
      }
    }

    /**
     * all customer orders.
     * 
     * @param $request
     */
    public function userOrdersDetails(Request $request)
    {
      $cart_id = $request->cart_id;

      $details  =   DB::table('orders')
                        ->join('store_orders', 'orders.cart_id', '=', 'store_orders.order_cart_id') 
                       ->where('store_orders.store_approval', 1)
                       ->where('orders.cart_id', $cart_id)
                       ->get();

      if ($details) {   

        return view('admin.user.partials.show_details', compact('details', 'cart_id'));
      } else {
        return redirect()->back()->withErrors('Something Wents Wrong');   
      }
    }
}