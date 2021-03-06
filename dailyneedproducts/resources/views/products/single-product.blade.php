
@extends('layouts.app1')
@section('content')
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <a href="#"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span> <a href="#">Fruits & Vegetables</a> <span class="mdi mdi-chevron-right"></span> <a href="#">Fruits</a>
               </div>
            </div>
         </div>
      </section>
      <section class="shop-single section-padding pt-3">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <div class="shop-detail-left">
                     <div class="shop-detail-slider">
                        <div class="favourite-icon">
                           <a class="fav-btn" title="" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="59% OFF"><i class="mdi mdi-tag-outline"></i></a>
                        </div>
                        <div id="sync1" class="owl-carousel">
                           <div class="item"><img alt="" src="/img/item/1.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/2.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/3.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/4.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/5.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/6.jpg" class="img-fluid img-center"></div>
                        </div>
                        <div id="sync2" class="owl-carousel">
                           <div class="item"><img alt="" src="/img/item/1.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/2.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/3.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/4.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/5.jpg" class="img-fluid img-center"></div>
                           <div class="item"><img alt="" src="/img/item/6.jpg" class="img-fluid img-center"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="shop-detail-right">
                     <span class="badge badge-success">50% OFF</span>
                     <h2>SaveMore Corn Flakes (Pouch)</h2>
                     <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                     <p class="regular-price"><i class="mdi mdi-tag-outline"></i> MRP : $800.99</p>
                     <p class="offer-price mb-0">Discounted price : <span class="text-success">$450.99</span></p>
                     <a href="checkout.php"><button type="button" class="btn btn-secondary btn-lg"><i class="mdi mdi-cart-outline"></i> Add To Cart</button> </a>
                     <div class="short-description">
                        <h5>
                           Quick Overview  
                           <p class="float-right">Availability: <span class="badge badge-success">In Stock</span></p>
                        </h5>
                        <p><b>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</b> Nam fringilla augue nec est tristique auctor. Donec non est at libero vulputate rutrum.
                        </p>
                        <p class="mb-0"> Vivamus adipiscing nisl ut dolor dignissim semper. Nulla luctus malesuada tincidunt. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hiMenaeos. Integer enim purus, posuere at ultricies eu, placerat a felis. Suspendisse aliquet urna pretium eros convallis interdum.</p>
                     </div>
                     <h6 class="mb-3 mt-4">Why shop from Groci?</h6>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="feature-box">
                              <i class="mdi mdi-truck-fast"></i>
                              <h6 class="text-info">Free Delivery</h6>
                              <p>Lorem ipsum dolor...</p>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="feature-box">
                              <i class="mdi mdi-basket"></i>
                              <h6 class="text-info">100% Guarantee</h6>
                              <p>Rorem Ipsum Dolor sit...</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <section class="product-items-slider section-padding bg-white border-top">
         <div class="container">
            <div class="section-header">
               <h5 class="heading-design-h5">Best Offers View <span class="badge badge-primary">20% OFF</span>
                  <a class="float-right text-secondary" href="shop.php">View All</a>
               </h5>
            </div>
            <div class="owl-carousel owl-carousel-featured">
               <div class="item">
                  <div class="product">
                     <a href="shop.php">
                        <div class="product-header">
                           <span class="badge badge-success">50% OFF</span>
                           <img class="img-fluid" src="/img/item/7.jpg" alt="">
                           <span class="veg text-success mdi mdi-circle"></span>
                        </div>
                        <div class="product-body">
                           <h5>Product Title Here</h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                        </div>
                        <div class="product-footer">
                           <button type="button" class="btn btn-secondary btn-sm float-right"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i><br><span class="regular-price">$800.99</span></p>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="product">
                     <a href="shop.php">
                        <div class="product-header">
                           <span class="badge badge-success">50% OFF</span>
                           <img class="img-fluid" src="/img/item/8.jpg" alt="">
                           <span class="non-veg text-danger mdi mdi-circle"></span>
                        </div>
                        <div class="product-body">
                           <h5>Product Title Here</h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                        </div>
                        <div class="product-footer">
                           <button type="button" class="btn btn-secondary btn-sm float-right"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i><br><span class="regular-price">$800.99</span></p>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="product">
                     <a href="shop.php">
                        <div class="product-header">
                           <span class="badge badge-success">50% OFF</span>
                           <img class="img-fluid" src="/img/item/9.jpg" alt="">
                           <span class="non-veg text-danger mdi mdi-circle"></span>
                        </div>
                        <div class="product-body">
                           <h5>Product Title Here</h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                        </div>
                        <div class="product-footer">
                           <button type="button" class="btn btn-secondary btn-sm float-right"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i><br><span class="regular-price">$800.99</span></p>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="product">
                     <a href="shop.php">
                        <div class="product-header">
                           <span class="badge badge-success">50% OFF</span>
                           <img class="img-fluid" src="/img/item/10.jpg" alt="">
                           <span class="veg text-success mdi mdi-circle"></span>
                        </div>
                        <div class="product-body">
                           <h5>Product Title Here</h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                        </div>
                        <div class="product-footer">
                           <button type="button" class="btn btn-secondary btn-sm float-right"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i><br><span class="regular-price">$800.99</span></p>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="product">
                     <a href="shop.php">
                        <div class="product-header">
                           <span class="badge badge-success">50% OFF</span>
                           <img class="img-fluid" src="/img/item/11.jpg" alt="">
                           <span class="veg text-success mdi mdi-circle"></span>
                        </div>
                        <div class="product-body">
                           <h5>Product Title Here</h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                        </div>
                        <div class="product-footer">
                           <button type="button" class="btn btn-secondary btn-sm float-right"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i><br><span class="regular-price">$800.99</span></p>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="item">
                  <div class="product">
                     <a href="shop.php">
                        <div class="product-header">
                           <span class="badge badge-success">50% OFF</span>
                           <img class="img-fluid" src="/img/item/12.jpg" alt="">
                           <span class="veg text-success mdi mdi-circle"></span>
                        </div>
                        <div class="product-body">
                           <h5>Product Title Here</h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                        </div>
                        <div class="product-footer">
                           <button type="button" class="btn btn-secondary btn-sm float-right"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i><br><span class="regular-price">$800.99</span></p>
                        </div>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <section class="section-padding bg-white border-top">
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-truck-fast"></i>
                     <h6>Free & Next Day Delivery</h6>
                     <p>Lorem ipsum dolor sit amet, cons...</p>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-basket"></i>
                     <h6>100% Satisfaction Guarantee</h6>
                     <p>Rorem Ipsum Dolor sit amet, cons...</p>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-tag-heart"></i>
                     <h6>Great Daily Deals Discount</h6>
                     <p>Sorem Ipsum Dolor sit amet, Cons...</p>
                  </div>
               </div>
            </div>
         </div>
      </section>
      @endsection
      
