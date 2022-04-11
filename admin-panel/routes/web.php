<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    Artisan::call('config:cache');
    return "Cache is cleared";  
});

Route::group(['prefix'=>'', ['middleware' => ['XSS']], 'namespace'=>'Admin'], function(){

	// for login
	Route::get('/', 'LoginController@adminLogin')->name('adminLogin');
	Route::any('loginCheck','LoginController@adminLoginCheck')->name('adminLoginCheck');

	Route::group(['middleware'=>'bamaAdmin'], function(){
		Route::get('home', 'HomeController@adminHome')->name('adminHome');
		Route::get('logout', 'LoginController@logout')->name('logout');
		Route::get('profile','ProfileController@adminProfile')->name('prof');
		Route::post('profile/update/{id}','ProfileController@adminUpdateProfile')->name('updateprof');
		Route::get('password/change','ProfileController@adminChangePass')->name('passchange');
		Route::post('password/update/{id}','ProfileController@adminChangePassword')->name('updatepass');

		/////settings/////
		Route::get('app_details','SettingsController@app_details')->name('app_details');
		Route::post('app_details/update','SettingsController@updateappdetails')->name('updateappdetails');


		Route::get('msgby','SettingsController@msg91')->name('msg91');
		Route::post('msg91/update','SettingsController@updatemsg91')->name('updatemsg91');
		Route::post('twilio/update','TwilioController@updatetwilio')->name('updatetwilio');
		Route::post('msgoff','TwilioController@msgoff')->name('msgoff');

		Route::get('map_api','SettingsController@mapapi')->name('mapapi');
		Route::post('map_api/update','SettingsController@updatemap')->name('updatemap');

		Route::get('fcm','SettingsController@fcm')->name('fcm');
		Route::post('fcm/update','SettingsController@updatefcm')->name('updatefcm');

		Route::get('del_charge','SettingsController@del_charge')->name('del_charge');
		Route::post('del_charge/update','SettingsController@updatedel_charge')->name('updatedel_charge');

		Route::get('bulk_order_discount','SettingsController@bulk_order_discount')->name('bulk_order_discount');
		Route::post('bulk_order_discount/update','SettingsController@updatebulk_order_discount')->name('updatebulk_order_discount');

		Route::get('online_payment_discount','SettingsController@online_payment_discount')->name('online_payment_discount');
		Route::post('online_payment_discount/update','SettingsController@updateonline_payment_discount')->name('updateonline_payment_discount');

		Route::get('Notification','NotificationController@adminNotification')->name('adminNotification');
		Route::post('Notification/send','NotificationController@adminNotificationSend')->name('adminNotificationSend');

		Route::get('currency','SettingsController@currency')->name('currency');
		Route::post('currency/update','SettingsController@updatecurrency')->name('updatecurrency');

		Route::get('delivery_type','DeliveryTypeController@deliveryType')->name('deliveryType');
		
		Route::get('delivery_type/add','DeliveryTypeController@AddDeliveryType')->name('AddDeliveryType');
		Route::post('delivery_type/add/new','DeliveryTypeController@AddNewDeliveryType')->name('AddNewDeliveryType');
		Route::get('delivery_type/edit/{id}','DeliveryTypeController@EditDeliveryType')->name('EditDeliveryType');
		Route::post('delivery_type/update/{id}','DeliveryTypeController@updateDeliveryType')->name('updateDeliveryType');
		Route::get('delivery_type/unblock/{id}','DeliveryTypeController@deliveryTypeUnblock')->name('deliveryTypeUnblock');
	    Route::get('delivery_type/block/{id}','DeliveryTypeController@deliveryTypeBlock')->name('deliveryTypeBlock');


		Route::get('Notification_to_store','NotificationController@Notification_to_store')->name('Notification_to_store');
		Route::post('Notification_to_store/send','NotificationController@Notification_to_store_Send')->name('adminNotificationSendtostore');
	    ///////category////////
	    Route::get('category/list', 'CategoryController@list')->name('catlist');

	    #parent category list 
	    Route::get('parentcategory/list', 'CategoryController@parentcategory')->name('parentcategory');
	    #subcategory list
	    Route::get('subcategory/list/{id?}', 'CategoryController@subcategory')->name('subcategory');


	    Route::get('category/add','CategoryController@AddCategory')->name('AddCategory');
		Route::post('category/add/new','CategoryController@AddNewCategory')->name('AddNewCategory');
		Route::get('category/edit/{category_id}','CategoryController@EditCategory')->name('EditCategory');
	 	Route::post('category/update/{category_id}','CategoryController@UpdateCategory')->name('UpdateCategory');

	 	Route::get('subcategory/add','CategoryController@AddSubCategory')->name('AddSubCategory');
		Route::post('subcategory/add/new','CategoryController@AddNewSubCategory')->name('AddNewSubCategory');
		Route::get('subcategory/edit/{category_id}','CategoryController@EditSubCategory')->name('EditSubCategory');
	 	Route::post('subcategory/update/{category_id}','CategoryController@UpdateSubCategory')->name('UpdateSubCategory');


	 	Route::get('category/delete/{category_id}','CategoryController@DeleteCategory')->name('DeleteCategory');
	    Route::get('category/unblock/{id}','CategoryController@categoryUnblock')->name('categoryUnblock');
	    Route::get('category/block/{id}','CategoryController@categoryBlock')->name('categoryBlock');
	 	
	 	
	 	///////Product////////
	    Route::get('product/list', 'ProductController@list')->name('productlist');
	    Route::get('product/add','ProductController@AddProduct')->name('AddProduct');
	    Route::get('product/unblock/{id}','ProductController@productUnblock')->name('productUnblock');
	    Route::get('product/block/{id}','ProductController@productBlock')->name('productBlock');

	    Route::get('product/unapproved/{id}','ProductController@productUnApproved')->name('productUnApproved');
	    Route::get('product/approved/{id}','ProductController@productApproved')->name('productApproved');
	    
	    Route::post('product/import-products/store', 'ProductController@addBulkProduct')->name('addBulkProduct');

		Route::post('product/add/new','ProductController@AddNewProduct')->name('AddNewProduct');
		Route::get('product/edit/{product_id}','ProductController@EditProduct')->name('EditProduct');
	 	Route::post('product/update/{product_id}','ProductController@UpdateProduct')->name('UpdateProduct');
	 	Route::get('product/delete/{product_id}','ProductController@DeleteProduct')->name('DeleteProduct');
	      
	      
	    //////Product Varient//////////
	    Route::get('varient/{id}','VarientController@varient')->name('varient');
	    Route::get('varient/add/{id}','VarientController@Addproduct')->name('add-varient');
	    Route::post('varient/add/new','VarientController@AddNewproduct')->name('AddNewvarient');
	    Route::get('varient/edit/{id}','VarientController@Editproduct')->name('edit-varient');
	    Route::post('varient/update/{id}','VarientController@Updateproduct')->name('update-varient');
	    Route::get('varient/delete/{id}','VarientController@deleteproduct')->name('delete-varient');
	    
	      ///////Delivery Boy////////
	    Route::get('d_boy/list', 'DeliveryController@list')->name('d_boylist');
	    Route::get('d_boy/add','DeliveryController@AddD_boy')->name('AddD_boy');
		Route::post('d_boy/add/new','DeliveryController@AddNewD_boy')->name('AddNewD_boy');
		Route::get('d_boy/edit/{id}','DeliveryController@EditD_boy')->name('EditD_boy');
	 	Route::post('d_boy/update/{id}','DeliveryController@UpdateD_boy')->name('UpdateD_boy');
	 	Route::get('d_boy/delete/{id}','DeliveryController@DeleteD_boy')->name('DeleteD_boy');

	 	Route::get('d_boy/location/{id}','DeliveryController@location_track')->name('location_track');
	 	
	 	    ///////Deal Product////////
	    Route::get('deal/list', 'DealController@list')->name('deallist');
	    Route::get('deal/add','DealController@AddDeal')->name('AddDeal');
		Route::post('deal/add/new','DealController@AddNewDeal')->name('AddNewDeal');
		Route::get('deal/edit/{id}','DealController@EditDeal')->name('EditDeal');
	 	Route::post('deal/update/{id}','DealController@UpdateDeal')->name('UpdateDeal');
	 	Route::get('deal/delete/{id}','DealController@DeleteDeal')->name('DeleteDeal');

	 	//User Queries
	 	Route::get('query/list', 'QueryController@list')->name('querylist');
	 	Route::get('query/details/{id}','QueryController@queryDetails')->name('queryDetails');
	      
	      
	      ///////User////////
	    Route::get('user/list', 'UserController@list')->name('userlist');  
	    Route::get('user/block/{id}','UserController@block')->name('userblock');
	    Route::get('user/unblock/{id}','UserController@unblock')->name('userunblock');
	    Route::get('user/orders/{id}','UserController@userOrders')->name('userOrders');
	    Route::get('user/orders/details/{cart_id}','UserController@userOrdersDetails')->name('userOrdersDetails');
	    // for city
		Route::get('citylist','CityController@citylist')->name('citylist');
		Route::get('city','CityController@city')->name('city');
		Route::post('cityadd','CityController@cityadd')->name('cityadd');
		Route::get('cityedit/{city_id}','CityController@cityedit')->name('cityedit');
		Route::post('cityupdate','CityController@cityupdate')->name('cityupdate');
		Route::get('citydelete/{city_id}','CityController@citydelete')->name('citydelete');
		// for society
		Route::get('societylist','SocietyController@societylist')->name('societylist');
		Route::get('society','SocietyController@society')->name('society');
		Route::post('societyadd','SocietyController@societyadd')->name('societyadd');
		Route::get('societyedit/{society_id}','SocietyController@societyedit')->name('societyedit');
		Route::post('societyupdate','SocietyController@societyupdate')->name('societyupdate');
		Route::get('societydelete/{society_id}','SocietyController@societydelete')->name('societydelete');
		// for banner
		Route::get('bannerlist','BannerController@bannerlist')->name('bannerlist');
		Route::get('banner','BannerController@banner')->name('banner');
		Route::post('banneradd','BannerController@banneradd')->name('banneradd');
		Route::get('banneredit/{banner_id}','BannerController@banneredit')->name('banneredit');
		Route::post('bannerupdate/{banner_id}','BannerController@bannerupdate')->name('bannerupdate');
		Route::get('bannerdelete/{society_id}','BannerController@bannerdelete')->name('bannerdelete');
		 
		// for coupon
	    Route::get('couponlist','CouponController@couponlist')->name('couponlist');
		Route::get('coupon','CouponController@coupon')->name('coupon');
		Route::post('addcoupon','CouponController@addcoupon')->name('addcoupon');
		Route::get('editcoupon/{coupon_id}','CouponController@editcoupon')->name('editcoupon');
		Route::post('updatecoupon','CouponController@updatecoupon')->name('updatecoupon');
		Route::get('deletecoupon/{coupon_id}','CouponController@deletecoupon')->name('deletecoupon');
		// for minimum order
		// 	 Route::get('bannerlist','SocietyController@societylist')->name('societylist');
	    //for order value edit
		Route::get('orderedit','Minimum_Max_OrderController@orderedit')->name('orderedit');
		Route::post('amountupdate','Minimum_Max_OrderController@amountupdate')->name('amountupdate');
		// for delivery time
		Route::get('timeslot','TimeSlotController@timeslot')->name('timeslot');
		Route::post('timeslotupdate','TimeSlotController@timeslotupdate')->name('timeslotupdate');
		Route::get('closehour','ClosehourController@closehour')->name('closehour');
		Route::post('closehrsupdate','ClosehourController@closehrsupdate')->name('closehrsupdate');
		 // for store
		Route::get('admin/store/list','StoreController@storeclist')->name('storeclist');
		Route::get('admin/store/add','StoreController@store')->name('store');
		Route::post('admin/store/added','StoreController@storeadd')->name('storeadd');
		Route::get('admin/store/edit/{store_id}','StoreController@storedit')->name('storedit');
		Route::post('admin/store/update/{store_id}','StoreController@storeupdate')->name('storeupdate');
		Route::get('admin/store/delete/{store_id}','StoreController@storedelete')->name('storedelete');
		//store orders//
		 
		Route::get('admin/store/orders/{id}','AdminorderController@admin_store_orders')->name('admin_store_orders');
		Route::get('admin/store/alldorders','AdminorderController@AllOrder')->name('store_cancelled');
		 
	    //assign store//
	    Route::post('admin/store/assign/{id}','AdminorderController@assignstore')->name('store_assign');
	    Route::get('finance','FinanceController@finance')->name('finance');
	    Route::post('store_pay/{store_id}','FinanceController@store_pay')->name('store_pay');
	      
		/////pages////////
	    Route::get('about_us','PagesController@about_us')->name('about_us');
	    Route::post('about_us/update','PagesController@updateabout_us')->name('updateabout_us');
	    Route::get('terms','PagesController@terms')->name('terms');
	    Route::post('terms/update','PagesController@updateterms')->name('updateterms');
	    #pages data show on the basis of id in ck editor
	    Route::get('/page/{id}','PagesController@pagefront')->name('pagefront');
	    #update page content on the basis of id
	     Route::post('/update-page/{id}','PagesController@updatepage')->name('updatepage');
	       
	    Route::get('prv','SettingsController@prv')->name('prv');
	    Route::post('prv/update','SettingsController@updateprv')->name('updateprv');
	      
	    // for reward
		Route::get('RewardList','RewardController@RewardList')->name('RewardList');
		Route::get('reward','RewardController@reward')->name('reward');
		Route::post('rewardadd','RewardController@rewardadd')->name('rewardadd');
		Route::get('rewardedit/{reward_id}','RewardController@rewardedit')->name('rewardedit');
		Route::post('rewardupate','RewardController@rewardupate')->name('rewardupate');
		Route::get('rewarddelete/{reward_id}','RewardController@rewarddelete')->name('rewarddelete');
		 
		// for reedem
		Route::get('reedem','ReedemController@reedem')->name('reedem');
		Route::post('reedemupdate','ReedemController@reedemupdate')->name('reedemupdate');
		  
		////store payout////
		Route::get('payout_req','PayoutController@pay_req')->name('pay_req');
	    Route::post('payout_req/{req_id}','PayoutController@store_pay')->name('com_payout');

	    Route::post('payment-inititate-request/{storeId}', 'PayoutController@initiate')->name('initiate');
	    Route::post('/payment-complete', 'PayoutController@complete')->name('complete'); 
	      
	    // for  Secondary banner
		Route::get('secbannerlist','SecondaryBannerController@secbannerlist')->name('secbannerlist');
		Route::get('secbanner','SecondaryBannerController@secbanner')->name('secbanner');
		Route::post('secbanneradd','SecondaryBannerController@secbanneradd')->name('secbanneradd');
		Route::get('secbanneredit/{sec_banner_id}','SecondaryBannerController@secbanneredit')->name('secbanneredit');
		Route::post('secbannerupdate/{sec_banner_id}','SecondaryBannerController@secbannerupdate')->name('secbannerupdate');
		Route::get('secbannerdelete/{sec_banner_id}','SecondaryBannerController@secbannerdelete')->name('secbannerdelete');
		
		Route::get('admin/d_boy/orders/{id}','AdminorderController@admin_dboy_orders')->name('admin_dboy_orders');
	    //assign delivery boy//
	    Route::post('admin/d_boy/assign/{id}','AdminorderController@assigndboy')->name('dboy_assign');
	    ////completed orders/////
	    Route::get('admin/completed_orders','AdminorderController@admin_com_orders')->name('admin_com_orders');
        ////sub completed orders/////
	    Route::get('admin/completed_sub_orders/{cart_id}','AdminorderController@admin_sub_com_orders')->name('admin_sub_com_orders');
	    
	    //// out of orders/////
	    Route::get('admin/out_of_orders','AdminorderController@admin_out_orders')->name('admin_out_orders');
	    Route::post('admin/complete/{cart_id}','AdminorderController@complete_order')->name('admin_complete_order');
	    Route::post('admin/picked_order/{cart_id}','AdminorderController@picked_order_by_delivery_boy')->name('picked_order_by_delivery_boy');

	     ////Pending orders/////
	    Route::get('admin/pending_orders','AdminorderController@admin_pen_orders')->name('admin_pen_orders');

	    Route::get('admin/sub_pending_orders/{cart_id}','AdminorderController@admin_sub_pen_orders')->name('admin_sub_pen_orders');

	    Route::post('admin/confirm/{cart_id}','AdminorderController@confirm_order')->name('store_confirm_order1');
	    Route::get('admin/reject-modal/{cart_id}','AdminorderController@rejectOrderModal')->name('store_reject_order_modal1');

	    Route::get('secretlogin/{id}','SecretloginController@secretlogin')->name('secret-login');
	    //Route::post('admin/reject/order/{id}','AdminorderController@rejectorder1')->name('admin_reject_order1');
	    Route::post('admin/reject/order/{id}','AdminorderController@rejectorder')->name('admin_reject_order');
	    Route::get('admin/cancelled_orders','AdminorderController@admin_can_orders')->name('admin_can_orders');

	    Route::get('admin/cancelled_sub_orders/{cart_id}','AdminorderController@admin_sub_can_orders')->name('admin_sub_can_orders');
	    
	    ////order  rating & review/////
	    Route::get('admin/orders_rating_review','AdminorderController@admin_order_ratings')->name('admin_order_ratings');
	});
});

Route::group(['prefix'=>'api','namespace'=>'Api'],function(){
  	Route::post('forgot_password1/{id}','forgotpasswordController@forgot_password1')->name('forgot_password1');
    Route::get('change_pass/{id}','forgotpasswordController@change_pass')->name('change_pass');
});

Route::group(['prefix'=>'store', ['middleware' => ['XSS']], 'namespace'=>'Store'], function(){

	// for login
	Route::get('/', 'LoginController@storeLogin')->name('storeLogin');
	Route::post('loginCheck','LoginController@storeLoginCheck')->name('storeLoginCheck');

	Route::group(['middleware'=>'bamaStore'], function(){
		Route::get('home', 'HomeController@storeHome')->name('storeHome');
		Route::get('product/add', 'ProductController@sel_product')->name('sel_product');
		Route::post('product/added', 'ProductController@added_product')->name('added_product');
		Route::get('product/delete/{id}','ProductController@delete_product')->name('delete_product');
		Route::post('product/stock/{id}','ProductController@stock_update')->name('stock_update');
		Route::get('logout', 'LoginController@logout')->name('storelogout');
		Route::get('orders/unassigned', 'AssignorderController@orders')->name('storeOrders');
		Route::get('orders/assigned', 'AssignorderController@assignedorders')->name('storeassignedorders');
		Route::get('print-order-details/{id}', 'AssignorderController@printOrders')->name('print_order_details');

		Route::get('orders/not-delivered-reason', 'AssignorderController@notDeliveredOrdersReason')->name('notDeliveredOrdersReason');

		Route::post('orders/confirm/{sub_order_id}','AssignorderController@confirm_order')->name('store_confirm_order');
		Route::get('orders/reject-modal/{cart_id}','OrderController@rejectOrderModal')->name('store_reject_order_modal');
		Route::post('orders/reject/{cart_id}','OrderController@reject_order')->name('store_reject_order');
		Route::get('orders/products/cancel/{store_order_id}','OrderController@cancel_products')->name('store_cancel_product');

		//Store Based Products
		Route::get('productlist', 'ProductController@st_product_list')->name('st_product_list');
		Route::get('store_product/add','ProductController@st_addProduct')->name('st_addProduct');
		Route::post('store_product/add/new','ProductController@st_addNewProduct')->name('st_addNewProduct');
	    Route::get('store_product/unblock/{id}','ProductController@st_productUnblock')->name('st_productUnblock');
	    Route::get('store_product/block/{id}','ProductController@st_productBlock')->name('st_productBlock');
	    Route::post('store_product/import-products/store', 'ProductController@st_addBulkProduct')->name('st_addBulkProduct');
		Route::get('store_product/edit/{product_id}','ProductController@st_editProduct')->name('st_editProduct');
	 	Route::post('store_product/update/{product_id}','ProductController@st_updateProduct')->name('st_updateProduct');
	 	Route::get('store_product/delete/{product_id}','ProductController@st_deleteProduct')->name('st_deleteProduct');

	 	//////Product Varient//////////
	    Route::get('store_varient/{id}','VarientController@st_varient')->name('st_varient');
	    Route::get('store_varient/add/{id}','VarientController@st_vAddproduct')->name('st_vadd-varient');
	    Route::post('store_varient/add/new','VarientController@st_vAddNewproduct')->name('st_vAddNewvarient');
	    Route::get('store_varient/edit/{id}','VarientController@st_vEditproduct')->name('st_vedit-varient');
	    Route::post('store_varient/update/{id}','VarientController@st_vUpdateproduct')->name('st_vupdate-varient');
	    Route::get('store_varient/delete/{id}','VarientController@st_vDeleteproduct')->name('st_vdelete-varient');



		Route::get('products', 'ProductController@st_product')->name('st_product');
		Route::get('payout/request', 'PayoutController@payout_req')->name('payout_req');
		Route::post('payout/request/sent', 'PayoutController@req_sent')->name('payout_req_sent');
		
		/////////invoice
		Route::get('store/invoice/{cart_id}','InvoiceController@invoice')->name('invoice');
		 
		/////////invoice
		Route::get('store/pdf/invoice/{cart_id}','InvoiceController@pdfinvoice')->name('pdfinvoice');

		// Delivery Boy Controller.
		Route::get('store/delivery-boys', 'DeliveryBoyController@deliveryBoyList')->name('delivery_boy_list');
	});
});