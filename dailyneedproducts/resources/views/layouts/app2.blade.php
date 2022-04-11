
<!DOCTYPE html>
<php lang="en">
<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Daily Needs Product</title>
      <!-- Favicon Icon -->
      <link rel="icon" type="image/png" href="">
      <!-- Bootstrap core CSS -->
      <link href="{{ url('extracss/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
      <!-- Material Design Icons -->
      <link href="{{ url('extracss/vendor/icons/css/materialdesignicons.min.css') }}" media="all" rel="stylesheet" type="text/css" />
      <!-- Select2 CSS -->
      <link href="{{ url('extracss/vendor/select2/select2-bootstrap.css') }}" />
      <link href="{{ url('extracss/vendor/select2/select2.min.css') }}" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="{{ url('extracss/css/osahan.css') }}" rel="stylesheet">
      <link href="{{ url('extracss/css/custom.css') }}" rel="stylesheet">
      <link href="{{ url('extracss/fonts/maven.css') }}" rel="stylesheet">
      <link href="{{ url('extracss/css/font-awesome.css') }}" rel="stylesheet">
      <!-- Owl Carousel -->
      <link rel="stylesheet" href="{{ url('extracss/vendor/owl-carousel/owl.carousel.css') }}">
      <link rel="stylesheet" href="{{ url('extracss/vendor/owl-carousel/owl.theme.css') }}">

      <!-- Owl Carousel -->
      <link rel="stylesheet" href="{{ url('extracss/vendor/datatables/datatables.min.css') }}">
   </head>
   <body>
    <div class="modal fade login-modal-main" id="bd-example-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-body">
                  <div class="login-modal">
                     <div class="row no-gutters">
                        <div class="col-lg-6 login-bg">
                           <div class="login-modal-left">
                              <!-- <a class="" href="index.php"> <img src="img/logo.png" alt="logo"> </a> -->
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                           <span class="sr-only">Close</span>
                           </button>
                           <form>
                              <div class="login-modal-right">
                                 <!-- Tab panes -->
                                 <div class="tab-content">
                                    <div class="tab-pane active" id="login" role="tabpanel">
                                       <h5 class="heading-design-h5">Login to your account</h5>
                                       <fieldset class="form-group">
                                          <label>Enter Email/Mobile number</label>
                                          <input type="text" class="form-control" placeholder="+91 123 456 7890">
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <label>Enter Password</label>
                                          <input type="password" class="form-control" placeholder="********">
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <button type="submit" class="btn btn-lg btn-secondary btn-block">Enter to your account</button>
                                       </fieldset>
                                       <div class="login-with-sites text-center">
                                          <p>or Login with your social profile:</p>
                                          <button class="btn-facebook login-icons btn-lg"><i class="mdi mdi-facebook"></i> Facebook</button>
                                          <button class="btn-google login-icons btn-lg"><i class="mdi mdi-google"></i> Google</button>
                                          <button class="btn-twitter login-icons btn-lg"><i class="mdi mdi-twitter"></i> Twitter</button>
                                       </div>
                                       <div class="custom-control custom-checkbox">
                                          <input type="checkbox" class="custom-control-input" id="customCheck1">
                                          <label class="custom-control-label" for="customCheck1">Remember me</label>
                                       </div>
                                    </div>
                                    <div class="tab-pane" id="register" role="tabpanel">
                                       <h5 class="heading-design-h5">Register Now!</h5>
                                       <fieldset class="form-group">
                                          <label>Enter Email/Mobile number</label>
                                          <input type="text" class="form-control" placeholder="+91 123 456 7890">
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <label>Enter Password</label>
                                          <input type="password" class="form-control" placeholder="********">
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <label>Enter Confirm Password </label>
                                          <input type="password" class="form-control" placeholder="********">
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <button type="submit" class="btn btn-lg btn-secondary btn-block">Create Your Account</button>
                                       </fieldset>
                                       <div class="custom-control custom-checkbox">
                                          <input type="checkbox" class="custom-control-input" id="customCheck2">
                                          <label class="custom-control-label" for="customCheck2">I Agree with <a href="#">Term and Conditions</a></label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="clearfix"></div>
                                 <div class="text-center login-footer-tab">
                                    <ul class="nav nav-tabs" role="tablist">
                                       <li class="nav-item">
                                          <a class="nav-link active" data-toggle="tab" href="#login" role="tab"><i class="mdi mdi-lock"></i> LOGIN</a>
                                       </li>
                                       <li class="nav-item">
                                          <a class="nav-link" data-toggle="tab" href="#register" role="tab"><i class="mdi mdi-pencil"></i> REGISTER</a>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="clearfix"></div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--<div class="navbar-top bg-success pt-2 pb-2">-->
      <!--   <div class="container-fluid">-->
      <!--      <div class="row">-->
      <!--         <div class="col-lg-12 text-center">-->
      <!--            <a href="shop.php" class="mb-0 text-white">-->
      <!--            20% cashback for new users | Code: <strong><span class="text-light">DAILY20 <span class="mdi mdi-tag-faces"></span></span> </strong>  -->
      <!--            </a>-->
      <!--         </div>-->
      <!--      </div>-->
      <!--   </div>-->
      <!--</div>-->
    <nav class="navbar navbar-light navbar-expand-lg bg-faded osahan-menu">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a class="navbar-brand" href="{{ route('products') }}"> <img src="/img/logo.png" alt="logo"> </a>
                <!-- <a href="{{url('/products')}}/" data-toggle="offcanvas" class="btn btn-success cart-btn border-none"> Home </a> -->
                 @if(session()->has('totalcartqty'))
                           
                            <?php $ct= session()->get('totalcartqty'); ?>
                        @else
                          <?php   $ct=0;  ?>
                        @endif
                <a href="{{url('/cart')}}/" data-toggle="offcanvas" class="btn btn-link cart-btn border-none"><i class="mdi mdi-cart"></i> My Cart 
                    <small class="cart-value">{{$ct}}</small>
                </a>
            </div>
            <!--<a class="navbar-brand" href="index.php"> <img src="img/logo.png" alt="logo"> </a>-->
            
            <!--<button class="navbar-toggler navbar-toggler-white d-lg-none d-block" type="button">-->
            <!--<span class="navbar-toggler-icon"></span>-->
            <!--</button>-->
            <!--<div class="navbar-collapse p-lg-0 p-2" id="navbarNavDropdown">-->
            <!--   <div class="col-lg-12 float-right my-2 my-lg-0">-->
            <!--      <ul class="list-inline main-nav-right">-->
            <!--         <li class="list-inline-item cart-btn float-right">-->
            <!--            <a href="{{url('/cart')}}/" data-toggle="offcanvas" class="btn btn-link border-none"><i class="mdi mdi-cart"></i> My Cart <small class="cart-value">0</small></a>-->
            <!--         </li>-->
            <!--      </ul>-->
            <!--   </div>-->
            <!--</div>-->
        </div>
    </nav>
    
 <nav class="navbar navbar-expand-lg navbar-light osahan-menu-2 pad-none-mobile d-lg-none d-block">
      <div class="">
         <div class="" id="navbarText">
            <span class="float-right pt-2 pr-2 close-sidebar"><i class="fa fa-times"></i></span>
            <div class="">
               <img src="/img/logo.png" class="img-fluid">
            </div>
            <ul class="navbar-nav mt-2 mt-lg-0">
               <li class="welcome"> 
                  <span>Welcome</span>
               </li>
               <li class="nav-item">
                  <a href="#" class="nav-link" data-target="#bd-example-modal" data-toggle="modal">
                     <span class="fa fa-user mr-3"></span>Login
                  </a>
               </li>
               <li class="nav-item">
                  <a href="my-address.php" class="nav-link">
                     <span class="fa fa-map-marker mr-3"></span>My Address
                  </a>
               </li>
               <li class="nav-item">
                  <a href="orderlist.php" class="nav-link">
                     <span class="fa fa-list-alt mr-3"></span>My Orders
                  </a>
               </li>
               <li class="nav-item">
                  <a href="cart.php" class="nav-link">
                     <span class="fa fa-shopping-cart mr-3"></span>My Cart
                     <span class="badge badge-primary">5</span>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="#" class="nav-link">
                     <span class="fa fa-inr mr-3"></span>My Wallet
                  </a>
               </li>
               <li class="nav-item">
                  <a href="#" class="nav-link">
                     <span class="fa fa-gift mr-3"></span>Offers
                  </a>
               </li> 
             </ul>

               <hr>
               <p class="pl-2">Others</p>
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
            </ul>
         </div>
      </div>
   </nav> 

<!--<section class="top-category section-padding d-lg-block d-none">-->
<!--         <div class="container">-->
<!--            <div class="owl-carousel owl-carousel-category">-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/1.jpg" alt="">-->
<!--                        <h6>Daily Morning</h6>-->
<!--                        <p>150 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/2.jpg" alt="">-->
<!--                        <h6>Daily Veggies</h6>-->
<!--                        <p>95 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/3.jpg" alt="">-->
<!--                        <h6>DAILY Fruit</h6>-->
<!--                        <p>65 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/4.jpg" alt="">-->
<!--                        <h6>DAILY  Exotic  Fruit</h6>-->
<!--                        <p>965 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/5.jpg" alt="">-->
<!--                        <h6>POLITRY FARM</h6>-->
<!--                        <p>125 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/6.jpg" alt="">-->
<!--                        <h6>DAILY RASAN</h6>-->
<!--                        <p>325 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/7.jpg" alt="">-->
<!--                        <h6>DAILY HEALTH N WELLNESS</h6>-->
<!--                        <p>156 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/8.jpg" alt="">-->
<!--                        <h6>DAILY SIPPERS</h6>-->
<!--                        <p>857 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/9.jpg" alt="">-->
<!--                        <h6>Daily INSTANT FOOD</h6>-->
<!--                        <p>48 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/10.jpg" alt="">-->
<!--                        <h6>DAILY NUTS</h6>-->
<!--                        <p>156 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/11.jpg" alt="">-->
<!--                        <h6>DAILY SWEET</h6>-->
<!--                        <p>568 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--               <div class="item">-->
<!--                  <div class="category-item">-->
<!--                     <a href="shop.php">-->
<!--                        <img class="img-fluid" src="img/small/12.jpg" alt="">-->
<!--                        <h6>DAILY  MUNCHING</h6>-->
<!--                        <p>156 Items</p>-->
<!--                     </a>-->
<!--                  </div>-->
<!--               </div>-->
<!--            </div>-->
<!--         </div>-->
<!--      </section>-->

        @yield('content')
    </div>
<!-- Footer -->
    <!--<section class="section-padding footer bg-white border-top">-->
    <!--     <div class="container">-->
    <!--        <div class="row">-->
    <!--           <div class="col-lg-3 col-md-3 col-7 order-md-1 order-4">-->
    <!--              <h4 class="mb-2 mt-md-0 mt-4"><a class="logo" href="index.php"><img src="img/logo.jpeg" width="70" alt="Groci"></a></h4>-->
    <!--              <p class="mb-0"><a class="text-dark" href="#"><i class="mdi mdi-phone"></i> +61 525 240 310</a></p>-->
    <!--              <p class="mb-0"><a class="text-dark" href="#"><i class="mdi mdi-cellphone-iphone"></i> 12345 67890, 56847-98562</a></p>-->
    <!--              <p class="mb-0"><a class="text-success" href="#"><i class="mdi mdi-email"></i> dailyneeds@gmail.com</a></p>-->
    <!--              <p class="mb-0"><a class="text-primary" href="#"><i class="mdi mdi-web"></i> www.dailyneeds.com</a></p>-->
    <!--           </div>-->
    <!--           <div class="col-lg-2 col-md-2 col-7 order-md-2 order-2">-->
    <!--              <h6 class="mb-4">TOP CITIES </h6>-->
    <!--              <ul>-->
    <!--              <li><a href="#">New Delhi</a></li>-->
    <!--              <li><a href="#">Bengaluru</a></li>-->
    <!--              <li><a href="#">Hyderabad</a></li>-->
    <!--              <li><a href="#">Kolkata</a></li>-->
    <!--              <li><a href="#">Gurugram</a></li>-->
    <!--              <ul>-->
    <!--           </div>-->
    <!--           <div class="col-lg-2 col-md-2 col-5 order-md-3 order-1">-->
    <!--              <h6 class="mb-4">CATEGORIES</h6>-->
    <!--              <ul>-->
    <!--              <li><a href="products.php">All Products</a></li>-->
    <!--              <li><a href="shop.php">Vegetables</a></li>-->
    <!--              <li><a href="shop.php">Grocery & Staples</a></li>-->
    <!--              <li><a href="shop.php">Breakfast & Dairy</a></li>-->
    <!--              <li><a href="shop.php">Soft Drinks</a></li>-->
    <!--              <li><a href="shop.php">Biscuits & Cookies</a></li>-->
    <!--              <ul>-->
    <!--           </div>-->
    <!--           <div class="col-lg-2 col-md-2 col-5 order-md-4 order-3">-->
    <!--              <h6 class="mb-4">QUICK LINKS</h6>-->
    <!--              <ul>-->
    <!--              <li><a href="profile.php">My Account</a></li>-->
    <!--              <li><a href="about.php">About Us</a></li>-->
    <!--              <li><a href="blog.php">Blog</a></li>-->
    <!--              <li><a href="faq.php">Faq</a></li>-->
    <!--              <li><a href="contact.php">Contact</a></li>-->
    <!--              <li><a href="privacy.php">Privacy Policy</a></li>-->
    <!--              <ul>-->
    <!--           </div>-->
    <!--           <div class="col-lg-3 col-md-3 col-sm-12 text-md-left text-center order-md-5 order-5">-->
    <!--              <h6 class="mb-4">Download App</h6>-->
    <!--              <div class="app">-->
    <!--                 <a href="#"><img src="img/google.png" alt=""></a>-->
    <!--                 <a href="#"><img src="img/apple.png" alt=""></a>-->
    <!--              </div>-->
    <!--              <h6 class="mb-3 mt-4">GET IN TOUCH</h6>-->
    <!--              <div class="footer-social">-->
    <!--                 <a class="btn-facebook" href="#"><i class="mdi mdi-facebook"></i></a>-->
    <!--                 <a class="btn-twitter" href="#"><i class="mdi mdi-twitter"></i></a>-->
    <!--                 <a class="btn-instagram" href="#"><i class="mdi mdi-instagram"></i></a>-->
    <!--                 <a class="btn-whatsapp" href="#"><i class="mdi mdi-whatsapp"></i></a>-->
    <!--                 <a class="btn-messenger" href="#"><i class="mdi mdi-facebook-messenger"></i></a>-->
    <!--                 <a class="btn-google" href="#"><i class="mdi mdi-google"></i></a>-->
    <!--              </div>-->
    <!--           </div>-->
    <!--        </div>-->
    <!--     </div>-->
    <!--  </section>-->
      <!-- End Footer -->
      <!-- Copyright -->
      <section class="pt-4 pb-4 footer-bottom">
         <div class="container">
            <div class="row no-gutters">
               <div class="col-lg-6 col-sm-6">
                  <p class="mt-1 mb-0">&copy; Copyright 2020 <strong class="text-dark">Daily Needs</strong>. All Rights Reserved<br>
                  </p>
               </div>
               <div class="col-lg-6 col-sm-6 text-sm-right">
                  <img alt="osahan logo" src="/img/payment_methods.png">
               </div>
            </div>
         </div>
      </section>
      <!-- End Copyright -->
      <div class="cart-sidebar">
         <div class="cart-sidebar-header">
            <h5>
               My Cart <span class="text-success">(5 item)</span> <a data-toggle="offcanvas" class="float-right" href="#"><i class="mdi mdi-close"></i>
               </a>
            </h5>
         </div>
         <div class="cart-sidebar-body">
            <div class="cart-list-product">
               <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>
               <img class="img-fluid" src="img/item/11.jpg" alt="">
               <span class="badge badge-success">50% OFF</span>
               <h5><a href="#">Product Title Here</a></h5>
               <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
               <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>
            </div>
            <div class="cart-list-product">
               <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>
               <img class="img-fluid" src="/img/item/7.jpg" alt="">
               <span class="badge badge-success">50% OFF</span>
               <h5><a href="#">Product Title Here</a></h5>
               <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
               <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>
            </div>
            <div class="cart-list-product">
               <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>
               <img class="img-fluid" src="/img/item/9.jpg" alt="">
               <span class="badge badge-success">50% OFF</span>
               <h5><a href="#">Product Title Here</a></h5>
               <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
               <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>
            </div>
            <div class="cart-list-product">
               <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>
               <img class="img-fluid" src="img/item/1.jpg" alt="">
               <span class="badge badge-success">50% OFF</span>
               <h5><a href="#">Product Title Here</a></h5>
               <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
               <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>
            </div>
            <div class="cart-list-product">
               <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>
               <img class="img-fluid" src="/img/item/2.jpg" alt="">
               <span class="badge badge-success">50% OFF</span>
               <h5><a href="#">Product Title Here</a></h5>
               <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
               <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>
            </div>
         </div>
         <div class="cart-sidebar-footer">
            <div class="cart-store-details">
               <p>Sub Total <strong class="float-right">$900.69</strong></p>
               <p>Delivery Charges <strong class="float-right text-danger">+ $29.69</strong></p>
               <h6>Your total savings <strong class="float-right text-danger">$55 (42.31%)</strong></h6>
            </div>
            <a href="#" data-toggle="modal" data-target="#checkoutmodal"><button class="btn btn-secondary btn-lg btn-block text-left" type="button"><span class="float-left"><i class="mdi mdi-cart-outline"></i> Proceed to Checkout </span><span class="float-right"><strong>$1200.69</strong> <span class="mdi mdi-chevron-right"></span></span></button></a>
         </div>
      </div>

      <!-- Modal -->
<div class="modal fade" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Checkout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="checkout-step">
                     <div class="accordion" id="accordionExample">
                        <div class="card checkout-step-one">
                           <div class="card-header" id="headingOne">
                              <h5 class="mb-0">
                                 <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                 <span class="number">1</span> Phone Number Verification
                                 </button>
                              </h5>
                           </div>
                           <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample" style="">
                              <div class="card-body">
                                 <p>We need your phone number so that we can update you about your order.</p>
                                 <form>
                                    <div class="form-row align-items-center">
                                       <div class="col-auto">
                                          <label class="sr-only">phone number</label>
                                          <div class="input-group mb-2">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text"><span class="mdi mdi-cellphone-iphone"></span></div>
                                             </div>
                                             <input type="text" class="form-control" placeholder="Enter phone number">
                                          </div>
                                       </div>
                                       <div class="col-auto">
                                          <button type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="btn btn-secondary mb-2 btn-lg collapsed">NEXT</button>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                        <div class="card checkout-step-two">
                           <div class="card-header" id="headingTwo">
                              <h5 class="mb-0">
                                 <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                 <span class="number">2</span> Delivery Address
                                 </button>
                              </h5>
                           </div>
                           <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample" style="">
                              <div class="card-body">
                                 <form>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">First Name <span class="required">*</span></label>
                                             <input class="form-control border-form-control" value="" placeholder="Gurdeep" type="text">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Last Name <span class="required">*</span></label>
                                             <input class="form-control border-form-control" value="" placeholder="Osahan" type="text">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Phone <span class="required">*</span></label>
                                             <input class="form-control border-form-control" value="" placeholder="123 456 7890" type="number">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Email Address <span class="required">*</span></label>
                                             <input class="form-control border-form-control " value="" placeholder="iamosahan@gmail.com" disabled="" type="email">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Country <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control select2-hidden-accessible" data-select2-id="7" tabindex="-1" aria-hidden="true">
                                                <option value="" data-select2-id="9">Select Country</option>
                                                <option value="AF">Afghanistan</option>
                                                <option value="AX">Åland Islands</option>
                                                <option value="AL">Albania</option>
                                                <option value="DZ">Algeria</option>
                                                <option value="AS">American Samoa</option>
                                                
                                             </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="8" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-pcia-container"><span class="select2-selection__rendered" id="select2-pcia-container" role="textbox" aria-readonly="true" title="Select Country">Select Country</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">City <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control select2-hidden-accessible" data-select2-id="10" tabindex="-1" aria-hidden="true">
                                                <option value="" data-select2-id="12">Select City</option>
                                                <option value="AF">Alaska</option>
                                                <option value="AX">New Hampshire</option>
                                                <option value="AL">Oregon</option>
                                                <option value="DZ">Toronto</option>
                                             </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="11" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-nu1y-container"><span class="select2-selection__rendered" id="select2-nu1y-container" role="textbox" aria-readonly="true" title="Select City">Select City</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Zip Code <span class="required">*</span></label>
                                             <input class="form-control border-form-control" value="" placeholder="123456" type="number">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">State <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control select2-hidden-accessible" data-select2-id="13" tabindex="-1" aria-hidden="true">
                                                <option value="" data-select2-id="15">Select State</option>
                                                <option value="AF">California</option>
                                                <option value="AX">Florida</option>
                                                <option value="AL">Georgia</option>
                                                <option value="DZ">Idaho</option>
                                             </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="14" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-xyu5-container"><span class="select2-selection__rendered" id="select2-xyu5-container" role="textbox" aria-readonly="true" title="Select State">Select State</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label class="control-label">Shipping Address <span class="required">*</span></label>
                                             <textarea class="form-control border-form-control"></textarea>
                                             <small class="text-danger">Please provide the number and street.</small>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="heading-part">
                                       <h3 class="sub-heading">Billing Address</h3>
                                    </div>
                                    <hr>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">First Name <span class="required">*</span></label>
                                             <input class="form-control border-form-control" value="" placeholder="Gurdeep" type="text">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Last Name <span class="required">*</span></label>
                                             <input class="form-control border-form-control" value="" placeholder="Osahan" type="text">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Phone <span class="required">*</span></label>
                                             <input class="form-control border-form-control" value="" placeholder="123 456 7890" type="number">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Email Address <span class="required">*</span></label>
                                             <input class="form-control border-form-control " value="" placeholder="dailyneeds@gmail.com" disabled="" type="email">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Country <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control select2-hidden-accessible" data-select2-id="16" tabindex="-1" aria-hidden="true">
                                                <option value="" data-select2-id="18">Select Country</option>
                                                <option value="AF">Afghanistan</option>
                                                <option value="AX">Åland Islands</option>
                                                <option value="AL">Albania</option>
                                                <option value="DZ">Algeria</option>
                                                <option value="AS">American Samoa</option>
                                                <option value="AD">Andorra</option>
                                             </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="17" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-ytx9-container"><span class="select2-selection__rendered" id="select2-ytx9-container" role="textbox" aria-readonly="true" title="Select Country">Select Country</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">City <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control select2-hidden-accessible" data-select2-id="19" tabindex="-1" aria-hidden="true">
                                                <option value="" data-select2-id="21">Select City</option>
                                                <option value="AF">Alaska</option>
                                                <option value="AX">New Hampshire</option>
                                                <option value="AL">Oregon</option>
                                                <option value="DZ">Toronto</option>
                                             </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="20" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-67r6-container"><span class="select2-selection__rendered" id="select2-67r6-container" role="textbox" aria-readonly="true" title="Select City">Select City</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Zip Code <span class="required">*</span></label>
                                             <input class="form-control border-form-control" value="" placeholder="123456" type="number">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">State <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control select2-hidden-accessible" data-select2-id="22" tabindex="-1" aria-hidden="true">
                                                <option value="" data-select2-id="24">Select State</option>
                                                <option value="AF">California</option>
                                                <option value="AX">Florida</option>
                                                <option value="AL">Georgia</option>
                                                <option value="DZ">Idaho</option>
                                             </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="23" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-35d9-container"><span class="select2-selection__rendered" id="select2-35d9-container" role="textbox" aria-readonly="true" title="Select State">Select State</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label class="control-label">Billing Landmark <span class="required">*</span></label>
                                             <textarea class="form-control border-form-control"></textarea>
                                             <small class="text-danger">
                                             Please include landmark (e.g : Opposite Bank) as the carrier service may find it easier to locate your address.
                                             </small>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-3">
                                       <input type="checkbox" class="custom-control-input" id="customCheckbill">
                                       <label class="custom-control-label" for="customCheckbill">Use my delivery address as my billing address</label>
                                    </div>
                                    <button type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg">NEXT</button>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

      <div id="overlay"></div>
      <!-- Bootstrap core JavaScript -->
     

      
   


    <!-- Scripts -->
    
    <script src="{{ url('/js/custom.js') }}"></script>
    <script src="{{ url('/js/owl.carousel.js') }}"></script>
</body>
</html>
