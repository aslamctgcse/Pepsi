<!DOCTYPE php>
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
      <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
      <!-- Material Design Icons -->
      <link href="vendor/icons/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css" />
      <!-- Select2 CSS -->
      <link href="vendor/select2/select2-bootstrap.css" />
      <link href="vendor/select2/select2.min.css" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="css/osahan.css" rel="stylesheet">
      <link href="css/custom.css" rel="stylesheet">
      <link href="fonts/maven.css" rel="stylesheet">
      <link href="css/font-awesome.css" rel="stylesheet">
      <!-- Owl Carousel -->
      <link rel="stylesheet" href="vendor/owl-carousel/owl.carousel.css">
      <link rel="stylesheet" href="vendor/owl-carousel/owl.theme.css">

      <!-- Owl Carousel -->
      <link rel="stylesheet" href="vendor/datatables/datatables.min.css">
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
      <div class="navbar-top bg-success pt-2 pb-2">
         <div class="container-fluid">
            <div class="row">
               <div class="col-lg-12 text-center">
                  <a href="shop.php" class="mb-0 text-white">
                  20% cashback for new users | Code: <strong><span class="text-light">DAILY20 <span class="mdi mdi-tag-faces"></span></span> </strong>  
                  </a>
               </div>
            </div>
         </div>
      </div>
      <nav class="navbar navbar-light navbar-expand-lg bg-faded osahan-menu">
         <div class="container-fluid">
            <a class="navbar-brand" href="index.php"> <img src="img/logo.png" alt="logo"> </a>
            <div class="dropdown location-dropdown">
               <button class="btn btn-location location-top dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <div><i class="mdi mdi-map-marker-circle" aria-hidden="true"></i> My Location</div>
            </button>
            <div class="dropdown-menu p-2 location-dropdown-menu" aria-labelledby="dropdownMenuButton">
                <form>
                   <div class="form-group">
                     <label>Select city</label>
                     <select class="form-control select2">
                        <option>Select City</option>
                        <option>Delhi</option>
                        <option selected="">Noida</option>
                     </select>
                   </div>
                   <div class="form-group">
                      <label>Select store</label>
                     <select class="form-control select2">
                        <option>Select Store</option>
                        <option selected="">Sec-63</option>
                        <option>Sec-18</option>
                     </select>
                   </div>
                   <div class="form-group">
                      <button class="btn btn-secondary w-100" type="submit">Proceed</button>
                   </div>
                </form>
              </div>
            </div>
			
            <button class="navbar-toggler navbar-toggler-white d-lg-none d-block" type="button">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse p-lg-0 p-2" id="navbarNavDropdown">
               <div class="navbar-nav mr-auto mt-2 mt-lg-0 margin-auto top-categories-search-main">
                  <a href="category.php" class="btn btn-secondary d-lg-none d-block btn-category">Category</a>
                  <div class="top-categories-search">
                     <div class="input-group">
                        <input class="form-control" placeholder="Search products in Your City" aria-label="Search products" type="text">
                        <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button"><i class="mdi mdi-magnify"></i> Search</button>
                        </span>
                     </div>
                  </div>
               </div>
               <div class="my-2 my-lg-0">
                  <ul class="list-inline main-nav-right">
                     <li class="list-inline-item">
                        <a href="#" data-target="#bd-example-modal" data-toggle="modal" class="btn btn-link"><i class="mdi mdi-account-circle"></i> Login/Sign Up</a>
                     </li>
                     <li class="list-inline-item cart-btn">
                        <a href="#" data-toggle="offcanvas" class="btn btn-link border-none"><i class="mdi mdi-cart"></i> My Cart <small class="cart-value">5</small></a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </nav>


 <nav class="navbar navbar-expand-lg navbar-light osahan-menu-2 pad-none-mobile d-lg-none d-block">
      <div class="">
         <div class="" id="navbarText">
            <span class="float-right pt-2 pr-2 close-sidebar"><i class="fa fa-times"></i></span>
            <div class="">
               <img src="img/logo.png" class="img-fluid">
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

<section class="top-category section-padding d-lg-block d-none">
         <div class="container">
            <div class="owl-carousel owl-carousel-category">
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/1.jpg" alt="">
                        <h6>Daily Morning</h6>
                        <p>150 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/2.jpg" alt="">
                        <h6>Daily Veggies</h6>
                        <p>95 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/3.jpg" alt="">
                        <h6>DAILY Fruit</h6>
                        <p>65 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/4.jpg" alt="">
                        <h6>DAILY  Exotic  Fruit</h6>
                        <p>965 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/5.jpg" alt="">
                        <h6>POLITRY FARM</h6>
                        <p>125 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/6.jpg" alt="">
                        <h6>DAILY RASAN</h6>
                        <p>325 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/7.jpg" alt="">
                        <h6>DAILY HEALTH N WELLNESS</h6>
                        <p>156 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/8.jpg" alt="">
                        <h6>DAILY SIPPERS</h6>
                        <p>857 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/9.jpg" alt="">
                        <h6>Daily INSTANT FOOD</h6>
                        <p>48 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/10.jpg" alt="">
                        <h6>DAILY NUTS</h6>
                        <p>156 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/11.jpg" alt="">
                        <h6>DAILY SWEET</h6>
                        <p>568 Items</p>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="category-item">
                     <a href="shop.php">
                        <img class="img-fluid" src="img/small/12.jpg" alt="">
                        <h6>DAILY  MUNCHING</h6>
                        <p>156 Items</p>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </section>