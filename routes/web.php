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
//Clear Route cache:
Route::get('/clear', function ()
{
    //Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return '<h1>All Cleared</h1>';
});

Route::get('/clear-cache', function() {
    Artisan::call('config:cache');
    return "Cache is cleared";
});

Route::group(['namespace' => 'Website'], function() {
    Route::get('/login-register', 'SignupController@showLoginRegisterModal')->name('login-register');
    Route::post('/login-register', 'SignupController@loginRegister')->name('login-register');
    Route::any('forgot-password', 'SignupController@forgotPassword'); //forget password
    Route::post('verify-otp', 'SignupController@verifyotp'); //register phone verify otp check
    Route::any('resend-otp', 'SignupController@resendotp'); //resend otp
    Route::any('resend-otp-forget-pass', 'SignupController@resendotpforgotpass'); //resend otp forget pass
    Route::post('forget_otp_veify', 'SignupController@forgetOtpVeify'); //forget password verify otp check
    Route::post('new-password', 'SignupController@newpassword'); //new pass set after forget

   Route::group(['middleware' => 'UserMiddleware'], function() { 
      Route::get('user-logout', 'SignupController@logout')->name('user-logout');
      Route::get('my-profile', 'ProfileController@myProfile')->name('my-profile');
      Route::any('my-address/{id}', 'ProfileController@myaddress')->name('my-address');
      #show address n update address page
      Route::post('my-profile-update/{id}', 'ProfileController@myupdateprofile')->name('my-profile-update');
      Route::get('/orders', 'ProfileController@orderlistprofile')->name('orderlist');

      //to get sub order 
      Route::get('/sub_orders/{id}', 'ProfileController@suborderlistprofile')->name('suborderlist');

      //to get order detail
      Route::get('/order-detail/{id}', 'ProfileController@orderdetail')->name('orderdetail');

      //to get sub order detail
      Route::get('/sub-order-detail/{id}', 'ProfileController@suborderdetail')->name('suborderdetail');

      //delete order 
      Route::any('/delete_order/{cartid}', 'ProfileController@deleteorder')->name('deleteorder');
      //delete order 
      Route::any('/order_review/{cartid}', 'ProfileController@orderReviewSubmit')->name('orderReviewSubmit');
      //cancel-item

      //delete sub order 
      Route::any('/delete_sub_order/{cartId}/{subcartid}', 'ProfileController@deletesuborder')->name('deletesuborder');

      Route::any('/cancel-item', 'ProfileController@cancelitem')->name('cancelitem');
      //reorder
      //Route::any('/reorder/{cartid}', 'ProfileController@reorder')->name('reorder');

      Route::any('/reorder/{cartid}/{suborderid}', 'ProfileController@reorder')->name('reorder');
      //rewards
      Route::any('rewards', 'ProfileController@rewards')->name('rewards');
      #redeem reward
      Route::post('rewards_redeem', 'ProfileController@rewardsredeem')->name('rewardsredeem');
      # to show wallet amount of user profile
      Route::any('wallet', 'ProfileController@wallet')->name('wallet');
      //Route::post('recharge-wallet', 'ProfileController@rechargewallet')->name('recharge-wallet'); //old
      #wallet recharge with razorpay beg
      Route::post('wallet-recharge-initiate', 'ProfileController@walletinitiate')->name('wallet-recharge-initiate');
      Route::post('/wallet-payment-complete', 'ProfileController@walletcomplete')->name('walletcomplete'); 
      #wallet recharge with razorpay end


    });

    Route::any('/', 'HomeController@index')->name('/');  
    Route::any('/category-product', 'HomeController@categoryproduct'); //added to show product categorywise 2nov 2020 
    //razor pay beg
    Route::any('/payment-inititate-request', 'ProductController@initiate')->name('initiate'); 
    Route::post('/payment-complete', 'ProductController@complete')->name('complete'); 
    //razorpay end
    //Route::any('/all-products', 'ProductController@index')->name('all-products');
   // Route::any('/all-products/{id?}', 'ProductController@index')->name('all-products'); //old
    Route::any('/all-products', 'ProductController@index')->name('all-products');
    //replica of all-products
    Route::any('/all-products-replica/{id?}', 'ProductController@indexreplica')->name('all-products-replica');
    Route::get('/product-detail/{id}', 'ProductController@productDetail')->name('product-detail');

    #added code to get user current location beg
    Route::get('/get-current-location','ProductController@getCurrentLocation');
    Route::any('/change-pincode', 'ProductController@changePincode')->name('change-pincode');
    #added code to get user current location end

    Route::get('/addtocart/{id}/{type?}', 'ProductController@addtocart')->name('addtocart');
    #update cart qty inc or dec
    Route::get('/updatecart/{id}/{type?}', 'ProductController@updatecart')->name('updatecart');
    Route::any('/checkout', 'ProductController@checkout')->name('checkout');//->middleware('UserMiddleware');
    Route::get('/cart', 'ProductController@cart')->name('cart');
    Route::get('/incordec/{id}/{type?}', 'ProductController@incordec')->name('cart');
    Route::get('/incordec/{id}/{type?}', 'ProductController@incordec');
    Route::any('/remove-product/{id}', 'ProductController@removeproduct');
    Route::any('/varient-product/{productid}/{varientid}', 'ProductController@getvarientproduct'); //to get data for varient product
    //added route for razorpay beg
    Route::get('payment-razorpay', 'ProductController@creater')->name('paywithrazorpay'); 
    Route::post('payment', 'ProductController@payment')->name('payment');
    Route::any('about', 'ProductController@about')->name('about');
    Route::any('blog', 'ProductController@blog')->name('blog');
    Route::any('faq', 'ProductController@faq')->name('faq');
    Route::any('privacy', 'ProductController@privacy')->name('privacy');
    Route::any('return_refund', 'ProductController@returnRefund')->name('return_refund');
    Route::any('shipping_policy', 'ProductController@shippingPolicy')->name('shipping_policy');

    Route::any('term-and-condition', 'ProductController@terms')->name('terms');
    Route::any('contact', 'ProductController@contact')->name('contact');
    #to apply coupon
    Route::any('set_coupon', 'ProductController@set_coupon')->name('set_coupon');

    //Get Online discount by ajax
    Route::any('check_online_discount', 'ProductController@checkOnlineDiscount')->name('checkOnlineDiscount');

    //Timeslot ajax
    Route::any('get_timeslot', 'ProductController@get_timeslot')->name('get_timeslot');

    //added route for razorpay end
    #place order with wallet 
   Route::post('/payment-by-wallet', 'ProductController@paymentbywallet')->name('paymentbywallet');
   #contact us form for user
   Route::any('/contact-us', 'ProductController@contactus')->name('contactus');
});

Route::group(['prefix' => '', ['middleware' => ['XSS']], 'namespace'=>'Admin'], function(){

   // for login
   Route::get('/admin', 'LoginController@adminLogin')->name('adminLogin');
   Route::post('loginCheck','LoginController@adminLoginCheck')->name('adminLoginCheck');

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

      Route::get('Notification','NotificationController@adminNotification')->name('adminNotification');
      Route::post('Notification/send','NotificationController@adminNotificationSend')->name('adminNotificationSend');

      Route::get('currency','SettingsController@currency')->name('currency');
      Route::post('currency/update','SettingsController@updatecurrency')->name('updatecurrency');

      Route::get('Notification_to_store','NotificationController@Notification_to_store')->name('Notification_to_store');
      Route::post('Notification_to_store/send','NotificationController@Notification_to_store_Send')->name('adminNotificationSendtostore');
       ///////category////////
       Route::get('category/list', 'CategoryController@list')->name('catlist');
       Route::get('category/add','CategoryController@AddCategory')->name('AddCategory');
      Route::post('category/add/new','CategoryController@AddNewCategory')->name('AddNewCategory');
      Route::get('category/edit/{category_id}','CategoryController@EditCategory')->name('EditCategory');
      Route::post('category/update/{category_id}','CategoryController@UpdateCategory')->name('UpdateCategory');
      Route::get('category/delete/{category_id}','CategoryController@DeleteCategory')->name('DeleteCategory');
       Route::get('category/unblock/{id}','CategoryController@categoryUnblock')->name('categoryUnblock');
       Route::get('category/block/{id}','CategoryController@categoryBlock')->name('categoryBlock');
      
      ///////Product////////
       Route::get('product/list', 'ProductController@list')->name('productlist');
       Route::get('product/add','ProductController@AddProduct')->name('AddProduct');
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
      
          ///////Deal Product////////
       Route::get('deal/list', 'DealController@list')->name('deallist');
       Route::get('deal/add','DealController@AddDeal')->name('AddDeal');
      Route::post('deal/add/new','DealController@AddNewDeal')->name('AddNewDeal');
      Route::get('deal/edit/{id}','DealController@EditDeal')->name('EditDeal');
      Route::post('deal/update/{id}','DealController@UpdateDeal')->name('UpdateDeal');
      Route::get('deal/delete/{id}','DealController@DeleteDeal')->name('DeleteDeal');
         
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
      //     Route::get('bannerlist','SocietyController@societylist')->name('societylist');
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
      Route::get('admin/store/cancelledorders','AdminorderController@store_cancelled')->name('store_cancelled');
       
       //assign store//
       Route::post('admin/store/assign/{id}','AdminorderController@assignstore')->name('store_assign');
       Route::get('finance','FinanceController@finance')->name('finance');
       Route::post('store_pay/{store_id}','FinanceController@store_pay')->name('store_pay');
         
      /////pages////////
       Route::get('about_us','PagesController@about_us')->name('about_us');
       Route::post('about_us/update','PagesController@updateabout_us')->name('updateabout_us');
       Route::get('terms','PagesController@terms')->name('terms');
       Route::post('terms/update','PagesController@updateterms')->name('updateterms');
          
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
        ////Pending orders/////
       Route::get('admin/pending_orders','AdminorderController@admin_pen_orders')->name('admin_pen_orders');
       Route::get('secretlogin/{id}','SecretloginController@secretlogin')->name('secret-login');
       Route::post('admin/reject/order/{id}','AdminorderController@rejectorder')->name('admin_reject_order');
       Route::get('admin/cancelled_orders','AdminorderController@admin_can_orders')->name('admin_can_orders');
       
       ////Pending orders/////
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

      Route::get('orders/not-delivered-reason', 'AssignorderController@notDeliveredOrdersReason')->name('notDeliveredOrdersReason');

      Route::post('orders/confirm/{sub_order_id}','AssignorderController@confirm_order')->name('store_confirm_order');
      Route::get('orders/reject-modal/{cart_id}','OrderController@rejectOrderModal')->name('store_reject_order_modal');
      Route::post('orders/reject/{cart_id}','OrderController@reject_order')->name('store_reject_order');
      Route::get('orders/products/cancel/{store_order_id}','OrderController@cancel_products')->name('store_cancel_product');

      //Store Based Products
      Route::get('productlist', 'ProductController@st_product_list')->name('st_product_list');
      
      // discount
      Route::get('discount_product', 'ProductController@discount_product')->name('discount_product');
      Route::get('product/discount/{id}','ProductController@single_discount_product')->name('single_discount_product');      
      Route::post('add/discount','ProductController@addDiscount')->name('addDiscount');
      Route::get('delete/discount/{id}','ProductController@deleteDiscount')->name('deleteDiscount');

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

      // Delivery Boy Controller.
      Route::get('store/delivery-boy_orders/{id}', 'DeliveryBoyController@delivery_boy_orders')->name('delivery_boy_orders');
   });
});