<?php

namespace App\Http\Controllers\Store;

use DB;
use Session;
use App\Http\Controllers\Controller;

class DeliveryBoyController extends Controller
{
	/**
	 * listing all delivery boy of store.
	 * 
	 * @param 
	 */
	public function deliveryBoyList()
	{

		# set title of listing.
        $title = "All Delivery Boys of store";

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

		# get all Dboy of store.
    	$dBoys  = 	DB::table('delivery_boy')
    					->LeftJoin('store', 'delivery_boy.store_id', '=', 'store.store_id')
	    	 		   	->where('delivery_boy.store_id', $store->store_id)
	    	 		   	//->paginate(10); //old commented by me
	    	 		   	->get(); //added by me

	 	# show listing of delivery boy.
	   	return view('store.delivery_boy.delivery_boy_list', compact('title', 'dBoys', 'logo', 'store'));
	}
}