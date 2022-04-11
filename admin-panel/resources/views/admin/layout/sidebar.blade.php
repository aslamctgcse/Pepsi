<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <div class="logo"><a href="{{route('adminHome')}}" class="simple-text logo-normal">
          <img src="{{url('/').'/assets/img/dealwy-logo.png'}}" alt="IMG" style="max-width: 248px;">
          <!--{{$logo->name}}-->
        </a></div>
      <div class="sidebar-wrapper">
        <ul class="nav">
     
          <li class="nav-item   ">
            <a class="nav-link active" href="{{route('adminHome')}}">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          
           <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#noti-dropdown" aria-expanded="{{ request()->is('Notification') || request()->is('Notification_to_store')  ? 'true' : 'false' }}" aria-controls="setting-dropdown">
             <i class="material-icons">notifications</i>
              <span class="menu-title">Send Notification<b class="caret"></b></span>
            </a>

            <div class="collapse {{ request()->is('Notification') || request()->is('Notification_to_store')  ? 'show' : '' }}" id="noti-dropdown">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('adminNotification')}}">To Users</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('Notification_to_store')}}">To Stores</a>
                </li>
                </ul>
                </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#setting-dropdown2" aria-expanded="{{ request()->is('msgby') || request()->is('map_api') || request()->is('app_details') || request()->is('fcm') || request()->is('del_charge') || request()->is('currency') || request()->is('delivery_type') || request()->is('timeslot') || request()->is('bulk_order_discount') || request()->is('online_payment_discount')  ? 'true' : 'false' }}" aria-controls="setting-dropdown">
              <i class="material-icons">settings</i>
              <span class="menu-title">Settings<b class="caret"></b></span>
            </a>
            <div class="collapse  {{ request()->is('msgby') || request()->is('map_api') || request()->is('app_details') || request()->is('fcm') || request()->is('del_charge') || request()->is('currency') || request()->is('delivery_type') || request()->is('timeslot') || request()->is('bulk_order_discount') || request()->is('online_payment_discount') ? 'show' : '' }}" id="setting-dropdown2">
              <ul class="nav flex-column sub-menu">
                   <li class="nav-item">
                        <a class="nav-link" href="{{route('msg91')}}">SMS/OTP Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('mapapi')}}"> Google Map API</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('app_details')}}"> App Logo/Name</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('fcm')}}"> FCM</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="{{route('del_charge')}}">Delivery Fee</a>
                    </li> -->
                     <li class="nav-item">
                        <a class="nav-link" href="{{route('currency')}}">Currency</a>
                    </li>
                      <li class="nav-item">
                     <a class="nav-link" href="{{route('timeslot')}}">Time Slot</a>
                     </li>
                     <li class="nav-item">
                     <a class="nav-link" href="{{route('deliveryType')}}">Delivery Type</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="{{route('bulk_order_discount')}}">Bulk Order Discount</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="{{route('online_payment_discount')}}">Online Payment Discount</a>
                     </li>
                </ul>
                </div>
          </li>
            <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#cat-dropdown" aria-expanded="{{ request()->is('parentcategory/list') || request()->is('subcategory/list') || request()->is('product/list') || request()->is('deal/list') || request()->is('deal/edit.*') || request()->is('category/add') || request()->is('product/add') || request()->is('deal/add') || Route::is('category/edit/*') ? 'true' : 'false' }}" aria-controls="setting-dropdown">
             <i class="material-icons">content_paste</i>
              <span class="menu-title">Category/products<b class="caret"></b></span>
            </a>
            <div class="collapse {{ request()->is('parentcategory/list') || request()->is('subcategory/list') || request()->is('product/list') || request()->is('deal/list') || request()->is('deal/edit.*') || request()->is('category/add') || request()->is('product/add') || request()->is('deal/add') || Route::is('category/edit/*') ? 'show' : '' }}" id="cat-dropdown">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                <!--   <a class="nav-link" href="{{route('catlist')}}">Categories</a> -->
                  <a class="nav-link" href="{{route('parentcategory')}}">Parent Categories</a>
                  
                </li>
                <li class="nav-item">
                
                  <a class="nav-link" href="{{route('subcategory')}}">Sub Categories</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('productlist')}}">Product</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="{{route('deallist')}}">Deal Products</a>
                </li> -->
                </ul>
                </div>
          </li>
         
          <li class="nav-item ">
            <a class="nav-link" href="{{route('userlist')}}">
              <i class="material-icons">person</i>
              <p>Users</p>
            </a>
          </li>
          
           <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#city-dropdown" aria-expanded="{{ request()->is('citylist') || request()->is('society') || request()->is('city') || request()->is('societylist') || Route::is('cityedit*')? 'true' : 'false'}}" aria-controls="setting-dropdown">
             <i class="material-icons">location_city</i>
              <span class="menu-title">City/Area<b class="caret"></b></span>
            </a>
            <div class="collapse {{ request()->is('citylist') || request()->is('society') || request()->is('city') || request()->is('societylist') || Route::is('cityedit*')? 'show' : ''}}" id="city-dropdown">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('citylist')}}">Delivery City</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="{{route('societylist')}}">Area</a>
                </li> -->

                </ul>
                </div>

          </li>
            <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#store-dropdown" aria-expanded="{{ request()->is('admin/store/list') || request()->is('finance') || Route::is('admin/store/edit.*') ? 'true' : 'false'}}" aria-controls="setting-dropdown">
             <i class="material-icons">house</i>
              <span class="menu-title">Store Management<b class="caret"></b></span>
            </a>
            <div class="collapse  {{ request()->is('admin/store/list') || request()->is('finance') || Route::is('admin/store/edit.*') ? 'show' : ''}}" id="store-dropdown">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('storeclist')}}">Store</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('finance')}}">Store Earnings/Payments</a>
                </li>

                </ul>
                </div>

          </li>
         <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ord-dropdown" aria-expanded="{{ request()->is('admin/store/cancelledorders') || request()->is('admin/completed_orders') || request()->is('admin/pending_orders') || request()->is('admin/cancelled_orders')? 'true' : 'false'}}" aria-controls="setting-dropdown">
             <i class="material-icons">layers</i>
              <span class="menu-title">Orders<b class="caret"></b></span>
            </a>
            <div class="collapse {{ request()->is('admin/store/cancelledorders') || request()->is('admin/store/alldorders') || request()->is('admin/completed_orders') || request()->is('admin/pending_orders') || request()->is('admin/cancelled_orders')? 'show' : ''}}" id="ord-dropdown">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('store_cancelled')}}">All Orders</a>
                </li> 
                <li class="nav-item">
                  <a class="nav-link" href="{{route('admin_com_orders')}}">Completed Orders</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="{{route('admin_out_orders')}}">Confirm Orders/Out for delivery Orders/</a>
                </li> -->
                  <li class="nav-item">
                  <a class="nav-link" href="{{route('admin_pen_orders')}}">Pending Orders</a>
                </li>
                  <li class="nav-item">
                  <a class="nav-link" href="{{route('admin_can_orders')}}">Cancelled Orders</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="{{route('admin_order_ratings')}}">Orders Rating & Review</a>
                </li> -->
                </ul>
                </div>
          </li>
            <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#payout-dropdown3" aria-expanded="{{ request()->is('payout_req') || request()->is('prv')? 'true' : 'false'}}" aria-controls="setting-dropdown2">
              <i class="menu-icon fa fa-rupee"></i>
              <span class="menu-title">Payout Request/Validation<b class="caret"></b></span>
            </a>
            <div class="collapse {{ request()->is('payout_req') || request()->is('prv')? 'show' : ''}}" id="payout-dropdown3">
              <ul class="nav flex-column sub-menu">
                   <li class="nav-item">
                        <a class="nav-link" href="{{route('pay_req')}}">Payout Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('prv')}}">Payout value validation</a>
                    </li>

                </ul>
                </div>
          </li>
            
            <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#setting-dropdown3" aria-expanded="{{ request()->is('RewardList') || request()->is('reedem') || Route::is('rewardedit*')? 'true' : 'false'}}" aria-controls="setting-dropdown2">
              <i class="menu-icon fa fa-trophy"></i>
              <span class="menu-title">Reward<b class="caret"></b></span>
            </a>
            <div class="collapse {{ request()->is('RewardList') || request()->is('reedem') || Route::is('rewardedit*')? 'show' : ''}}" id="setting-dropdown3">
              <ul class="nav flex-column sub-menu">
                   <li class="nav-item">
                        <a class="nav-link" href="{{route('RewardList')}}">Reward Value</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('reedem')}}">Reedem Value</a>
                    </li>

                </ul>
                </div>
          </li>
            
          </li>
          
          
           <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#banner-dropdown" aria-expanded="{{ request()->is('bannerlist') || request()->is('secbannerlist') || Route::is('banneredit*') || Route::is('secbanneredit*')? 'true' : 'false'}}" aria-controls="setting-dropdown">
             <i class="material-icons">image</i>
              <span class="menu-title">Banner<b class="caret"></b></span>
            </a>
            <div class="collapse {{ request()->is('bannerlist') || request()->is('secbannerlist') || Route::is('banneredit*') || Route::is('secbanneredit*')? 'show' : ''}}" id="banner-dropdown">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('bannerlist')}}">Main Banner</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="{{route('secbannerlist')}}">Secondary Banner</a>
                </li> -->

                </ul>
                </div>

          </li>
          
    
          <li class="nav-item ">
            <a class="nav-link" href="{{route('d_boylist')}}">
              <i class="material-icons">android</i>
              <p>Delivery Boy</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{route('querylist')}}">
              <i class="material-icons">Query</i>
              <p>Queries</p>
            </a>
          </li>
          <!-- <li class="nav-item ">
            <a class="nav-link" href="{{route('orderedit')}}">
              <i class="material-icons">bubble_chart</i>
              <p>Min/Max. Order Value</p>
            </a>
          </li> -->
          
          <li class="nav-item ">
            <a class="nav-link" href="{{route('couponlist')}}">
              <i class="material-icons">view_week</i>
              <p>Coupon</p>
            </a>
          </li>
         <!--  <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pages-dropdown" aria-expanded="false" aria-controls="setting-dropdown">
              <i class="menu-icon fa fa-calendar"></i>
              <span class="menu-title">Pages<b class="caret"></b></span>
            </a>
            <div class="collapse" id="pages-dropdown">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('about_us')}}">About Us</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('terms')}}">Terms & Condition</a>
                </li>

                </ul>
                </div>

          </li> -->
          {{--added code for frontend static page contents beg--}}

            <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pages-dropdown-static" aria-expanded="{{ Route::is('page*') || request()->is('terms') ? 'true' : 'false'}}" aria-controls="setting-dropdown">
              <i class="menu-icon fa fa-calendar"></i>
              <span class="menu-title">Front-end Pages<b class="caret"></b></span>
            </a>
            <div class="collapse {{ Route::is('page*') || request()->is('terms') ? 'show' : ''}}" id="pages-dropdown-static">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/page/1')}}">About Us</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="{{--url('/page/2') --}}">Blog</a>
                </li> -->
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/page/3')}}">FAQ</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/page/4')}}">Contact Us</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/page/5')}}">Privacy Policy</a>
                </li>
                 <li class="nav-item">
                  <a class="nav-link" href="{{route('terms')}}">Terms & Condition</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/page/6')}}">Shipping Policy</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/page/7')}}">Return & Refund</a>
                </li>

                </ul>
                </div>

          </li>

          {{--added code for frontend static page contents end--}}
        </ul>
      </div>
    </div>