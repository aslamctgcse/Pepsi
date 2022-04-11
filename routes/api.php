<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix'=>'', ['middleware' => ['XSS']], 'namespace'=>'Api'], function(){
    //app logo and name
    Route::get('app_info', 'AppController@app');
    
    // for user
    Route::post('register', 'UserController@signUp');
    Route::post('verify_phone', 'UserController@verifyPhone');
    Route::any('forget_password', 'UserController@forgotPassword');
    Route::post('verify_otp', 'UserController@verifyOtp');
    Route::post('change_password', 'UserController@changePassword');
    Route::any('login', 'UserController@login');
    Route::post('checkotp', 'UserController@checkOTP');
    Route::post('myprofile', 'UserController@myprofile');
    Route::any('resend_otp', 'UserController@resendOtp');
    Route::any('login_verify_otp', 'UserController@loginVerifyOtp');
    
    //////address///////
    Route::post('add_address', 'AddressController@address');
    Route::get('city', 'AddressController@city');
    Route::post('society', 'AddressController@society');
    Route::post('show_address', 'AddressController@show_address');
    Route::post('select_address', 'AddressController@select_address');
    Route::post('edit_address', 'AddressController@edit_add');
    
    ////category product, product_varient///////
    Route::get('cat', 'CategoryController@cat');
    Route::post('varient', 'CategoryController@varient');
    Route::get('dealproduct', 'CategoryController@dealproduct');
    
    //orders//
    Route::any('make_an_order', 'OrderController@order');
    //Route::get('make_an_order', 'OrderController@order');
    Route::any('ongoing_orders', 'OrderController@ongoing');

    Route::any('ongoing_store_orders', 'OrderController@ongoingStoreOrders');

    Route::get('cancelling_reasons', 'OrderController@cancel_for');
    Route::post('delete_order', 'OrderController@delete_order');
    Route::get('top_selling', 'OrderController@top_selling');
    //Route::get('checkout', 'OrderController@checkout');
    Route::any('checkout', 'OrderController@checkout');
    Route::any('completed_orders', 'OrderController@completed_orders');
    Route::any('completed_store_orders', 'OrderController@completedStoreOrders');

    //Route::get('completed_orders', 'OrderController@completed_orders');

    Route::get('recentselling', 'OrderController@recentselling');
    Route::any('cancel-item', 'OrderController@cancelItem');

    //Route::get('cancel-item', 'OrderController@cancelItem');
    
    //coupon//
    Route::post('apply_coupon', 'CouponController@apply_coupon');

    Route::post('cancel_coupon', 'CouponController@cancel_coupon');

    Route::post('couponlist', 'CouponController@coupon_list');
    //Route::get('couponlist', 'CouponController@coupon_list');
    Route::post('walletamount', 'WalletController@walletamount');
    
    //search//
    Route::any('search', 'SearchController@search');
    //search//
    Route::any('search_pincode', 'SearchController@search_pincode');
    
    //currency//
    Route::get('currency', 'CurrencyController@currency');
    
    /////time slot////// 
    Route::post('timeslot', 'TimeslotController@timeslot'); 
  
    //////minimum/maximum order value///////
    Route::get('minmax', 'CartvalueController@minmax'); 
     
    /////rating/////
    Route::any('review_on_delivery', 'RatingController@review_on_delivery');

    Route::Post('review_on_order', 'RatingController@review_on_order');
    //Route::post('get_order_review', 'RatingController@get_order_review');
    
    ////pages//
    Route::get('appaboutus', 'PagesController@appaboutus');
    Route::get('appterms', 'PagesController@appterms');
     
    //banner//
    Route::get('banner', 'BannerController@bannerlist');
     
    Route::get('catee', 'CategoryController@cate');
    Route::any('cat_product', 'CategoryController@cat_product');
    //Route::get('cat_product', 'CategoryController@cat_product');

    //product list based on store Id
    Route::any('store_based_product', 'CategoryController@store_based_product');
    
    Route::post('stores', 'StoreController@store');
    
    Route::get('deliveryTypes', 'DeliveryTypeController@deliveryType');
     
    //wallet
    Route::post('recharge_wallet', 'WalletController@add_credit');
    Route::post('totalbill', 'WalletController@totalbill');
    Route::post('show_recharge_history', 'WalletController@show_recharge_history');
     
    //notification by///
    Route::post('notifyby', 'NotifybyController@notifyby');
    Route::post('updatenotifyby', 'NotifybyController@updatenotifyby');
     
    //secbanner//
    Route::get('secondary_banner', 'BannerController@secbannerlist');
     
    //redeem rewards//
    Route::post('redeem_rewards', 'RewardController@redeem');
    Route::get('rewardvalues', 'RewardController@rewardvalues');
      
    //notifications//
    Route::post('notificationlist', 'UsernotificationController@notificationlist');
    Route::post('read_by_user', 'UsernotificationController@read_by_user');
    Route::post('mark_all_as_read', 'UsernotificationController@mark_all_as_read');
    Route::post('delete_all_notification', 'UsernotificationController@delete_all');
      
    //////cancel order list////
    Route::post('can_orders', 'OrderController@can_orders');

    //////User submit query////
    Route::post('contact-us', 'UserController@contactUs');
    
    ///profile edit
    Route::post('profile_edit', 'UserController@profile_edit');
    ///////what's new//////
    Route::get('whatsnew', 'OrderController@whatsnew');
       
    ////rewardlines////
    Route::post('rewardlines', 'RewardController@rewardlines');
       
    //top six categories//
    Route::get('topsix', 'CategoryController@top_six');
        
    //Delivery fee info////
    Route::get('delivery_info', 'AppController@delivery_info');

    //Delivery fee info////
    Route::any('exact_delivery_info', 'AppController@exact_delivery_info');
        
    /////user_block_check////
    Route::post('user_block_check', 'UserController@user_block_check');
         
    Route::post('forgot_password','forgotpasswordController@forgot_password'); 
    Route::get('checkotponoff','forgotpasswordController@checkotponoff'); 
});

Route::group(['prefix'=>'store', ['middleware' => ['XSS']], 'namespace'=>'Storeapi'], function() {
    //////store login/////
    Route::post('store_login', 'StoreloginController@store_login');
    Route::post('store_profile', 'StoreloginController@storeprofile');
    
    
    Route::any('storeassigned', 'StoreorderController@storeassigned');
    
    Route::any('storeunassigned', 'StoreorderController@storeunassigned');

    Route::any('productcancelled', 'StoreorderController@productcancelled');
    Route::any('order_rejected', 'StoreorderController@order_rejected');

    Route::any('storeconfirm', 'AssignController@storeconfirm');
    
    Route::post('productselect', 'AddproductController@productselect');
    Route::post('storeproducts', 'AddproductController@store_products');
    Route::post('store_stock_update', 'AddproductController@stock_update');
    Route::post('store_delete_product', 'AddproductController@delete_product');
    Route::post('store_add_products', 'AddproductController@add_products');
    Route::any('earn', 'AmountController@earn');
    
    //notifications//
    Route::post('notificationlist', 'NotificationController@notificationlist');
    Route::post('read_by_store', 'NotificationController@read_by_store');
    Route::post('all_as_read', 'NotificationController@all_as_read');
    Route::post('delete_all_notification', 'NotificationController@delete_all');
    Route::post('nearbydboys','AssignController@delivery_boy_list');
});

Route::group(['prefix'=>'driver', ['middleware' => ['XSS']], 'namespace'=>'Driverapi'], function() {
    Route::post('driver_login', 'DriverloginController@driver_login');
    Route::post('driver_profile', 'DriverloginController@driverprofile');
    Route::any('driver_boy_currentloc', 'DriverloginController@driverBoyCurrentLoc');
    Route::any('ordersfortoday', 'DriverorderController@ordersfortoday');
    Route::any('ordersfornextday', 'DriverorderController@ordersfornextday');
    Route::any('out_for_delivery', 'DriverorderController@delivery_out');

    Route::any('delivery_completed', 'DriverorderController@delivery_completed');

    Route::any('not_delivered_reason', 'DriverorderController@notDeliveredReason');

    Route::post('driver-engaged-status', 'DriverorderController@driverEngagedStatus');
    Route::post('avg_rating', 'RatingController@avg_rating');
    Route::get('map_api', 'MapController@map_api_key');

    Route::any('completed_orders', 'DriverorderController@completed_orders');

    Route::post('update_status', 'DriverstatusController@status');
});

Route::group(['prefix'=>'', ['middleware' => ['XSS']], 'namespace'=>'Ios'], function() {
    // for user
    Route::post('ios_register', 'UserController@ios_signUp');
});
