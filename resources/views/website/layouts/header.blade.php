<?php
//dd($cat);
?>
@php
#curl to get user location beg
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"http://ip-api.com/json");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$result=curl_exec($ch);
$result=json_decode($result);


if(isset($result) && $result->status=='success'){
  $country= $result->country;
  $regionName=$result->regionName;
  $city=$result->city;
   if(isset($result->lat) && isset($result->lon)){
      $result->lat;
      $result->lon;
   }
$ip=$result->query;
   
}
#curl to get user location end
$urls= Request::segment(1);


#code to get ip address beg

#code to get ip address end

if(empty($urls)){
   $urls='/';
}
if(!empty($urls) && $urls == 'all-products'){
   $urls='all-products';
}

@endphp
  <style>
 /*Header text rolling css*/   
.scroll-right-slide {
 height: 30px;  
 overflow: hidden;
 position: relative;
}
.scroll-right-slide span {
 position: absolute;
 width: 100%;
 height: 100%;
 margin: 0;
 line-height: 25px;
 text-align: center;
 /* Starting position */
 -moz-transform:translateX(-90%);
 -webkit-transform:translateX(-90%); 
 transform:translateX(-90%);
 /* Apply animation to this element */  
 -moz-animation: scroll-right 18s linear infinite;
 -webkit-animation: scroll-right 18s linear infinite;
 animation: scroll-right 18s linear infinite;
}
/* Move it (define the animation) */
@-moz-keyframes scroll-right {
 10%   { -moz-transform: translateX(-100%); }
 90% { -moz-transform: translateX(100%); }
}
@-webkit-keyframes scroll-right {
 10%   { -webkit-transform: translateX(-100%); }
 90% { -webkit-transform: translateX(100%); }
}
@keyframes scroll-right {
 0%   { 
 -moz-transform: translateX(-100%); /* Browser bug fix */
 -webkit-transform: translateX(-100%); /* Browser bug fix */
 transform: translateX(-100%);    
 }
 100% { 
 -moz-transform: translateX(100%); /* Browser bug fix */
 -webkit-transform: translateX(100%); /* Browser bug fix */
 transform: translateX(100%); 
 }
}
/*.appDisableCss{
  cursor: none;
  opacity: 0.4;
}*/

 /*Footer app diaable css*/
.appDisablebalock{
  display: none;
}
.appDisableBelowlock{
  margin-top: 0px!important;
}

/*My Cart for mobile view*/
@media (min-width: 992px){
  .mobileCart{
    display: none;
  }
}
.mobileCart a{
  color: #fff;
}
.mobileCart .mdi{
  font-size: 16px;
  margin-left: 2px;
}
.mobileCart .cart-value{
  background: #220c02 none repeat scroll 0 0;
  right: -10px;
  top: 8px;
  left: initial;
}
.cart-sidebar{
  z-index: 9999;
}
.zindex60 {
  z-index:1055!important;
}

.navbar-nav.top-categories-search-main {
    display: flex;
    align-items: center;
    /* justify-content: space-between; */
}
@media only screen and (min-width:992px) and (max-width:1199px) {
  .osahan-menu .navbar-brand {
    max-width: 150px;
}
}

@media (max-width: 1240px){
.menu-cart{
  display: none;
}
}

</style>  
<div class=" sticky-top ">
   <div class="navbar-top bg-success pt-1 pb-0">
      <div class="container">
         <div class="row">
             <div class="col-md-12 top-head-left">
               <div class="scroll-right-slide">
               <span class="mb-0 text-light font-weight-bold ">
                 <!--  We deliver from 8AM to 9PM  -->
               </span>
             </div>
             </div>
            
         </div>
      </div>
   </div>

   <!-- My Location Popup -->
   <nav class="navbar navbar-light navbar-expand-lg bg-faded osahan-menu">
      <div class="container-fluid">
         <a class="navbar-brand" href="{{url('/')}}"> 
            <img src="{{ url('assets/website/img/dealwy-logo.png') }}" alt="logo"> 
         </a>
         <!-- <div class="dropdown location-dropdown">
            <button class="btn btn-location location-top dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <div><i class="mdi mdi-map-marker-circle" aria-hidden="true"></i> My Location</div>
            </button>
            <div class="dropdown-menu p-2 location-dropdown-menu" aria-labelledby="dropdownMenuButton">
               <form>
                  <div class="form-group">
                     <label>Select city</label>
                     <select class="form-control select2">
                        <option selected="">{{$city ?? ''}}</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Select store</label>
                     <select class="form-control select2">
                        <option selected="">{{$regionName ?? ''}}</option>
                        
                     </select>
                  </div>

                  <div class="form-group">
                     <button class="btn btn-secondary w-100" type="submit">Proceed</button>
                  </div>
               </form>
            </div>
         </div> -->
          <!--
         <button class="navbar-toggler navbar-toggler-white d-lg-none d-block new-bar-btn" type="button">
           <i class="mdi mdi-menu"></i>
         </button>
         <div class="navbar-collapse pt-0-sm p-lg-0 px-2" id="navbarNavDropdown">
            <div class="navbar-nav  top-categories-search-main">
               <div style="position: relative;" class="all-category mx-3 md-none">
             
                  <button class="navbar-toggler navbar-toggler-white d-block new-bar-btn "  type="button">
                    <i class="mdi mdi-menu"></i>
                 </button>
               </div>
               <div class="top-categories-search">
                 
                   
                      <form method="post" action="{{url('/all-products')}}" >
                 
                        {{csrf_field()}}
                  <div class="input-group">
                     
                  </select>
               </div>

               <div class="form-group">
                  <button class="btn btn-secondary w-100" type="submit">Proceed</button>
               </div>
            </form>
         </div>
      </div> -->

   
      <button class="navbar-toggler navbar-toggler-white d-lg-none d-block new-bar-btn" type="button">
        <i class="mdi mdi-menu"></i>
      </button>

      <div class="navbar-collapse pt-0-sm p-lg-0 p-2" id="navbarNavDropdown">
         <div class="navbar-nav  top-categories-search-main">
            <div style="position: relative;" class="all-category mx-1 md-none">
               <!-- <a class="btn btn-secondary d-block btn-category navbar-toggler" href="#" type="button">All Categories</a> -->
               <button class="navbar-toggler navbar-toggler-white d-block new-bar-btn "  type="button">
                 <i class="mdi mdi-menu"></i>
              </button>
            </div>
            <div style="position: relative;" class="all-category mx-1 dropdown location-dropdown">
              <button class="btn btn-location location-top dropdown-toggle navbar-toggler-white d-block map-bar-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div><i class="mdi mdi-map-marker-circle" aria-hidden="true"></i></div>
              </button>
              <div class="dropdown-menu p-2 location-dropdown-menu" aria-labelledby="dropdownMenuButton">
                <form>
                  <div class="form-group location-list-input">
                    <label>Search By Pincode</label>
                    @if(Session::get('visitor_pincode') && Session::get('visitor_pincode')!='')
                      <input class="form-control" placeholder="Enter Pincode" aria-label="Search products" type="text" value="{{Session::get('visitor_pincode') ?? ''}}" id ="pincodesearch" name="pincodesearch" minlength="6" maxlength="6" onkeypress="return numonly(event)"/>
                    @else
                      <input class="form-control" placeholder="Enter Pincode" aria-label="Search by products" type="text"  id ="pincodesearch" name="pincodesearch" minlength="6" maxlength="6" onkeypress="return numonly(event)"/>
                    @endif
                  </div>
                  <div class="row" style="margin-right: 0px;margin-left: 0px;">
                  <div class="col-sm-6 form-group" style="margin-bottom: 0px;">
                    <button class="btn btn-secondary w-100" type="button" onclick="submitPinCode()">Search</button>
                  </div>
                  <div class="col-sm-6 form-group" style="margin-bottom: 0px;">
                    <button class="btn btn-secondary w-100" type="button" onclick="clearPinCode()">Clear</button>
                  </div>
                </div>
                </form>
              </div>
            </div>
            

            <div class="top-categories-search">
              
              
                
                   <form method="post" action="{{url('/all-products')}}" >
              
                     {{csrf_field()}}
               <div class="input-group">

                  @if(isset($searchByName) && $searchByName!='')
                  <input class="form-control" placeholder="Search products in Your City" aria-label="Search products" type="text" value="@if(isset($searchByName)) <?php echo $searchByName ?> @endif" name="productsearch" />
                  @else
                  <input class="form-control" placeholder="Search products in Your City" aria-label="Search products" type="text"  name="productsearch" />
                  @endif
                  <span class="input-group-btn">
                     <button class="btn btn-secondary" type="button" onclick="this.form.submit()"><i class="mdi mdi-magnify"></i></button>
                  </span>
                  
                  </div>
                  </form>
               </div>
            </div>
                        
                           @if(session()->has('userData'))
                              
                               <?php $ct= session()->get('userData'); //->user_name;
                               $username=ucfirst($ct->user_name);
                                ?>
                           @else
                           <?php $username= 'Login/Sign Up'; ?>
                           @endif
            <div class="my-2 my-lg-0">
               <ul class="list-inline main-nav-right">
                 <div class="menu-text-link md-none">
                    <li class="list-inline-item ">
                       <a href="{{url('/')}}">
                          Home
                       </a>
                    </li>
                    <li class="list-inline-item ">
                       <a href="{{url('/about')}}">
                          About Us
                       </a>
                    </li>
                    <li class="list-inline-item ">
                       <a href="{{url('/all-products')}}">
                          Products
                       </a>
                    </li>
                    <li class="list-inline-item ">
                       <a href="{{url('/contact')}}">
                          Contact Us
                       </a>
                    </li>
                  </div>
                  <li class="list-inline-item menu-cart">
                     @if(session()->has('userData'))
                     <a href="{{ url('/my-profile') }}" class="btn btn-link " style="border-right: 0px solid #384042 !important;">
                        <i class="mdi mdi-account-circle"></i> {{$username}}</a>
                         @else
                         <a href="javascript::void(0);" data-href="{{ route('login-register') }}" data-container="login_register" class="btn btn-link link-modal az" style="border-right: 0px solid #384042 !important;">
                        <i class="mdi mdi-account-circle"></i>{{$username}}</a>
                         @endif
                  </li>
                   @if(session()->has('totalcartqty'))
                              
                               <?php $ct= session()->get('totalcartqty'); ?>
                           @else
                             <?php   $ct=0;  ?>
                           @endif
                  <li class="list-inline-item cart-btn">
                     <a href="#" data-toggle="offcanvas" class="btn btn-link border-none ">
                        <i class="mdi mdi-cart"></i> My Cart <small class="cart-value">{{$ct}}</small>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </nav>
</div>
<!-- End My Location Popup -->

<!-- Login/Register Popup On Mobile azwarsssas -->
<nav class="navbar navbar-expand-lg navbar-light osahan-menu-2 pad-none-mobile d-block">
   <div class="">
      <div class="" id="navbarText">
         <span class="float-right pt-2 pr-2 close-sidebar"><i class="fa fa-times text-dark"></i></span>
         <div class="p-2 text-center side-top-header">
            <img src="{{ url('assets/website/img/dealwy-logo.png') }}" class="img-fluid" style="max-width: 160px;">
         </div>

         <ul class="navbar-nav mt-lg-0">
            <li class="welcome"> 
               <span>Welcome</span>
            </li>
            <!-- <li class="nav-item">
               <a href="javascript::void(0);" data-href="{{ route('login-register') }}" data-container="login_register" class="nav-link link-modal" data-target="#bd-example-modal" data-toggle="modal">
                  <span class="fa fa-sign-in mr-3"></span>Login/Signup
               </a>
            </li> -->
          
            <li class="nav-item">
              @if(session()->has('userData'))
                <a href="{{ url('/my-profile') }}"  class="nav-link link-modal" style="">
                  <i class="mdi mdi-account-circle"></i> Welcome {{$username}}
                </a>
              @else
                <a href="javascript::void(0);" data-href="{{ route('login-register') }}" data-container="login_register" class="nav-link link-modal closeSideMenu" data-target="#bd-example-modal" data-toggle="modal">
                  <span class="fa fa-sign-in mr-3"></span>Login/Signup
               </a>
              @endif
            </li>
            <li>
              
            </li>

           @if(session()->has('userData'))
            <li class="nav-item">
               <a href="{{url('/my-profile')}}" class="nav-link">
                  <span class="fa fa-user mr-3"></span>My Profile
               </a>
            </li>
            @endif

            <!-- Add My Cart in mobile menu-->

             @if(session()->has('totalcartqty'))
                           
                            <?php $ct= session()->get('totalcartqty'); ?>
                        @else
                          <?php   $ct=0;  ?>
                        @endif
             <li class="nav-item cart-btn mobileCart">
               <a href="#" data-toggle="offcanvas" class="btn btn-link border-none closeSideMenu">
                     <i class="mdi mdi-cart"></i> My Cart <small class="cart-value">{{$ct}}</small>
                  </a>
            </li>  

            @if(session()->has('userData'))
            <li class="nav-item">
               <a href="{{url('/user-logout')}}" class="nav-link">
                  <span class="fa fa-sign-out mr-3"></span>Logout
               </a>
            </li>
            @endif

            

         
         </ul>
          <?php
           //dd($cat);
           ?>
         
      <!--    <ul class="navbar-nav mt-2 mt-lg-0">
          <li class="welcome"> 
               <span>All Categories</span>
            </li>

            <li class="nav-item">
               <a class="nav-link" href="#">
                  Daily Morning
               </a>
            </li>

            <li class="nav-item">
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="true" class="nav-link dropdown-toggle">Daily Fruits</a>
                <ul class="list-unstyled collapse" id="homeSubmenu" style="">
                    <li>
                        <a href="#" class="nav-link">Fruit 1</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link">Fruit 2</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link">Fruit 3</a>
                    </li>
                </ul>
            </li>
         </ul> -->
         <!--added code for category and subcategory beg-->
         <ul class="navbar-nav mt-2 mt-lg-0">
          <li class="welcome"> 
               <span>All Categories</span>
            </li>
            @foreach($cat as $cats)
            @if(isset($cats->cat_id))
          

            <li class="nav-item">
                @if(count($cats->subcategory)>0)
                  <a href="#homeSubmenu{{$cats->cat_id}}" data-toggle="collapse" aria-expanded="true" class="nav-link dropdown-toggle">{{$cats->title ?? ''}}</a>

                @else
                  <a href="{{url('all-products?id='.$cats->cat_id)}}" class="nav-link">{{$cats->title ?? ''}}</a>
                @endif
               <!--  <a href="#homeSubmenu{{$cats->cat_id}}" data-toggle="collapse" aria-expanded="true" class="nav-link dropdown-toggle">{{$cats->title ?? ''}}</a> -->
                @foreach($cats->subcategory as $subcategory)
                <ul class="list-unstyled collapse" id="homeSubmenu{{$cats->cat_id}}" style="">
                    <li>
                        <a href=" @if(isset($subcategory->cat_id))  {{url('all-products?id='.$subcategory->cat_id)}} @endif" class="nav-link">{{$subcategory->title ?? ''}}</a>
                    </li>

                </ul>

                @endforeach
            </li>
              @endif
            @endforeach
         </ul>
         <!--added code for category and subcategory end-->
         <!-- <p class="pl-2">Others</p>
         <ul class="navbar-nav mt-2 mt-lg-0">
            <li class="nav-item">
               <a class="nav-link" href="about.php">
                  <span class="fa fa-user mr-3"></span>About
               </a>
            </li>

            <li class="nav-item">
               <a class="nav-link" href="faq.php">
                  <span class="fa fa-question-circle mr-3"></span>Faq
               </a>
            </li>

            <li class="nav-item">
               <a class="nav-link" href="#">
                  <span class="fa fa-file mr-3"></span>Terms & Conditions
               </a>
            </li>

            <li class="nav-item">
               <a class="nav-link" href="blog.php">
                  <span class="fa fa-th mr-3"></span>Blog
               </a>
            </li>

            <li class="nav-item">
               <a class="nav-link" href="contact.php">
                  <span class="fa fa-compass mr-3"></span>Contact
               </a>
            </li>
         </ul> -->
      </div>
   </div>
</nav>
<!-- End Login/Register Popup On Mobile -->


<!-- category carousel  add ocde here-->
<section class="top-category section-padding d-lg-block d-none">
   <div class="container">
      <div class="owl-carousel owl-carousel-category">
         @if(isset($cat_crousel))
         @foreach($cat_crousel as $cat_crousels) 

         
         <div class="item">
            <div class="category-item cextt">
               <a href="{{url('/all-products?id='.$cat_crousels->cat_id)}}">
                  <img class="img-fluid" src=" @if(!empty($cat_crousels->image) && $cat_crousels->image != 'N/A') {{ url('/admin-panel/'.$cat_crousels->image) }} @else  {{url('/assets/website/img/logo.png')}} @endif" alt="">
                  <h6>{{strtoupper($cat_crousels->title)}}</h6>
                <!--   <p>{{-- count($cat_crousels->subcategory) --}} Items</p> -->
               </a>
            </div>
         </div>
     
         @endforeach
         @endif
     
       
       
         
      </div>
   </div>
</section>
<!-- End category carousel --->
