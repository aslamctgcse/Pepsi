
@extends('website.layouts.app')
<style type="text/css">
   .singleProductCss{
      display: flex;
      padding-top: 5px;
      padding-bottom: 5px;
   }
   .singleProductCss>span{
      min-height: 45px;
   }
   .singleProductCss .btn{
      margin: auto!important;
   }
   .singleProductCss .quantity span:first .btn span{
      font-size: 10px!important;
   }
</style>
 <script type="text/javascript">    

        window.addEventListener( "pageshow", function ( event ) {
  var historyTraversal = event.persisted || 
                         ( typeof window.performance != "undefined" && 
                              window.performance.navigation.type === 2 );
  if ( historyTraversal ) {
    console.log('reggggggggg');
    // Handle page restore.
    location.reload();
  }
});
</script>
@section('content')
@php 

@endphp
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <a href="{{url('/')}}"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span> <a href="{{url('/all-products?id='.$catproductrels_single->cid)}}">{{$catproductrels_single->ctitle ?? ''}}</a> 
                <!--   <span class="mdi mdi-chevron-right"></span> <a href="">Fruits</a> -->
               </div>
            </div>
         </div>
      </section>
      <section class="shop-single section-padding pt-3">
         <div class="container">
            <div class="row">
               
                <?php            
                                 $price_after_discount=$catproductrels_single->price;
                                 #added code to check if product price and mrp is greater than zero
                                  if($catproductrels_single->mrp > 0 || $catproductrels_single->price > 0){
                                 $discountprice=$catproductrels_single->mrp*$catproductrels_single->discount_percentage/100;
                                 $discount=(($catproductrels_single->mrp - $catproductrels_single->price)/$catproductrels_single->mrp)*100;
                              }else{
                                 $discount=0;
                              }

                                 #new code to show price ,mrp ,calculate discount from mrp n price beg
                                   /* var mrp=data.product_varient_details.mrp;
                                    var price=data.product_varient_details.price;
                                    var discount=((mrp-price)/mrp)*100;*/
                                 #new code to show price ,mrp ,calculate discount from mrp n price end

                                 ?>
                                 <?php 
                            $visibile=1;
                            $click_event='';
                             $stock_color='';
                             $addtocartdisp="block";
                             $qtyblockdisp='block';
                            $stock_text='In Stock';
                             if(isset($catproductrels_single->stock) && $catproductrels_single->stock == 0 || empty($catproductrels_single->stock) || $catproductrels_single->status == 0){
                                $visibile=0.5;
                                $addtocartdisp="none";
                                $qtyblockdisp='none';
                                $stock_text='Out of Stock';
                                $stock_color='red';
                                $click_event='none';
                             }
                             #added to restrict user to add  more than stock beg
                             $qtyaddedtocart='';
                              $btndisabled='';
                             
                             $defaultqtydisp='block';
                                      if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$catproductrels_single->varient_id])){
                                       $addtocartdisp="none";
                                       $defaultqtydisp='none';

                                        $qtyaddedtocart=session()->get('footer_cart_qty')[$catproductrels_single->varient_id];
                                        if(!empty($qtyaddedtocart) && $qtyaddedtocart == $catproductrels_single->stock){
                                          $visibile=0.5;
                                          $qtyblockdisp='none';
                                          $stock_text='Out of Stock';
                                           $stock_color='red';
                                          $click_event='none';

                                        }
                                      }
                             #added to restrict user to add  more than stock end

                            ?>
               <div class="col-md-6 stock{{$catproductrels_single->varient_id}}">
                  <div class="shop-detail-left">
                     <div class="shop-detail-slider">
                        <!-- <div class="favourite-icon">
                           <a class="fav-btn" title="" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="59% OFF"><i class="mdi mdi-tag-outline"></i></a>
                        </div> -->
                        <div id="sync1" class="owl-carousel">
                           <div class="item product-header" style="opacity:{{$visibile}}; pointer-events:{{$click_event}}"><img alt="" src="
                              @if(!empty($catproductrels_single->varient_image)) {{url('/admin-panel/'.$catproductrels_single->varient_image)}} @else {{url('/admin-panel/'.$catproductrels_single->product_image)}} @endif" class="img-fluid img-center"></div>
                           
                        </div>
                        
                     </div>
                  </div>
               </div>             
               <div class="col-md-6">
                  {{-- hidden fields to get out of stock beg --}}
                   <input type="hidden" name="qtyadded" class="qtyadded{{$catproductrels_single->varient_id}}" value="0">
                   <input type="hidden" name="productstock" value="{{$catproductrels_single->stock ?? 0}}" class="productstock{{$catproductrels_single->varient_id}}">
                  {{--  hidden fields to get out of stock beg --}}
                  <div class="shop-detail-right">
                     @if($discount >0)
                     <span class="badge badge-success">{{round($discount,2)}}% OFF</span>
                     @endif
                     <h2>{{ucfirst($catproductrels_single->product_name)}}</h2>
                     <!-- <h6>
                      <strong><span class="mdi mdi-store"></span> Sold By</strong> {{ucfirst($catproductrels_single->store_name)}}
                     </h6> -->
                     <h6><strong><span class="mdi mdi-approval"></span> Available in</strong>  {{ $catproductrels_single->quantity}} {{$catproductrels_single->unit}}</h6>
                     @if($discount >0)
                     <p class="regular-price"><i class="mdi mdi-tag-outline"></i> MRP : ₹{{$catproductrels_single->mrp}}</p>
                     @endif
                    
                     <p class="offer-price mb-0">Price : <span class="text-success">₹
                     {{ $price_after_discount}}</span></p>

                     <!-- To hide add to cart when stocks not available -->

                     <button type="button" class="btn btn-secondary btn-lg" onclick="addtocart('{{$catproductrels_single->varient_id}}','{{$catproductrels_single->product_id}}')" id="addtocart{{$catproductrels_single->varient_id}}" style="display:{{$addtocartdisp}};"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>

                     <div class="singleProductCss">
                           <span class="append_qty_block{{$catproductrels_single->varient_id}}"  style="display:{{$qtyblockdisp}};">
                           <!--added code to show qty block if item is added to cart beg-->
                           <?php if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$catproductrels_single->varient_id])){
                              //dd('jkhj');
                           /*$inc='inc';
                           $dec='dec'; */
                           ?>
                              <p class="offer-price mb-0 quantity" style="float:right;">
                                 <span class="input-group-btn">
                                    <button disabled="disabled" class="btn btn-theme-round btn-number" type="button" style="font-size: 26px;line-height: 26px;">
                                       <span onclick="cartupdate('{{$catproductrels_single->varient_id}}','dec')" id="dec{{$catproductrels_single->varient_id}}" class="dec{{$catproductrels_single->varient_id}}" style="font-size:24px">
                                    @if(session()->get('footer_cart_qty')[$catproductrels_single->varient_id]=='1')
                                     <?php echo 'x';?>
                                    @else
                                      <?php echo '-';?>
                                    @endif      
                                       
                                        </span>
                                    </button>
                                 </span>
                                 <span>
                                    <input type="text" max="" min="" value="{{session()->get('footer_cart_qty')[$catproductrels_single->varient_id]}}"  name="quant[1]" class="quant{{$catproductrels_single->varient_id}}" style="width:30px;" readonly>
                                 </span>
                                 <span class="input-group-btn">
                                    <button class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;">
                                       <span onclick="cartupdate('{{$catproductrels_single->varient_id}}','inc')" id="'inc{{$catproductrels_single->varient_id}}" class="inc{{$catproductrels_single->varient_id}}">+5th</span>
                                    </button>

                                 </span>
                              </p>
                              <?php } ?>
                           <!--added code to show qty block if item is added to cart end-->
                           </span>
                       </div> 
  
    

                     <!-- <button type="button" class="btn btn-secondary btn-lg" onclick="addtocart('{{$catproductrels_single->varient_id}}','{{$catproductrels_single->product_id}}')" id="addtocart{{$catproductrels_single->product_id}}"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>  -->
 

                     <div class="short-description">
                        <h5>
                           Quick Overview  
                           <p class="float-right">Availability: <span class="badge badge-success stockbtn stockbtnNew{{$catproductrels_single->varient_id}}" style="color:{{$stock_color}}" >{{$stock_text}}</span></p>
                        </h5>
                       
                        <p>{{$catproductrels_single->description}}</p>
                     </div>
                     <h6 class="mb-3 mt-4" style="display:none;">Why shop from Groci?</h6>
                     <div class="row" style="display:none;">
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
      <section class="product-items-slider section-padding bg-white border-top" style="display:none;">
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
      <section class="section-padding bg-white border-top" style="display:none;">
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
      
