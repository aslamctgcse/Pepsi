<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon\Carbon;

class DeliveryChargeController extends Controller
{
	/**
	 * listing all delivery boy of store.
	 * 
	 * @param 
	 */
	public function deliveryChargeList(Request $request)
    {
       
        $title="Store Delivery Charge";
    	 
    	# get email of login store.
        $email = Session::get('bamaStore');

        # get logo.
	  	$logo = DB::table('tbl_web_setting')
		            ->where('set_id', '1')
		            ->first();

        # get store with their session email.
    	$store  = DB::table('store')
	    	 		   ->where('email', $email)
	    	 		   ->first();	
                
         $del_charge = DB::table('freedeliverycart_by_store')
                             ->where('delivery_store_id', $store->store_id) 
                              ->get();
         return view('store.delivery_charge.delivery_charge_list',compact("email", "store",'title', 'logo','del_charge'));
    }

    public function update_deliveryChargeList(Request $request)
    {
    	//dd($request);
        
        $delCharges = $request->delCharge;
        
        try {
          
            foreach ($delCharges as $key => $delChargesValue) {
                $update = DB::table('freedeliverycart_by_store')
                            ->where('id', $delChargesValue['del_charge_id'])
                            ->update([
                                'min_cart_value'=> $delChargesValue['min_cart_value'],
                                'del_charge'    => $delChargesValue['del_charge']
                              ]);
            }

            return redirect()->back()->withSuccess('Delivery Charge Updated Successfully');
        } catch (\Exception $e) {
          
             return redirect()->back()->withErrors('Something Wents Wrong');
        }
      

    }
}