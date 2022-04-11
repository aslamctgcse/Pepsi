<!-- Footer -->
<?php   //dd( session()->get('footer_cart_qty')); 
//  $cart_product_list=session()->get('cart_product_list');
// dd($cart_product_list);
//$cart_product_list=session()->get('cart_product_list');
      // dd($cart_product_list);

?>

<section class="section-padding footer border-top">
   <div class="container">
      <div class="row">
         <div class="col-lg-3 col-md-3 col-7 order-md-1 order-3 mb-30">
            <h4 class="mb-2 mt-md-0 mt-4">
               <a class="logo footer-logo" href="{{url('/')}}">
                  <img src="{{ url('assets/website/img/dealwy-logo.png') }}" style="max-width: 160px;" alt="Groci">
               </a>
            </h4>
            <!-- <p class="mb-0"><a class="text-dark" href="tel:+61 525 240 310"><i class="mdi mdi-phone"></i> +61 525 240 310</a></p> -->
            <p class="mb-0"><a class="" href="tel:9999999999"><i class="mdi mdi-cellphone-iphone"></i> +91 9999 999 999</a></p>
            <p class="mb-0"><a class="" href="mailto:abcd@gmail.com"><i class="mdi mdi-email"></i>   abcd@gmail.com</a></p>
            <!--adde to check mail beg-->
         
            <!--adde to check mail end-->
            <p class="mb-0"><a class="" href="{{url('/')}}"><i class="mdi mdi-web"></i> www.dealwy.com</a></p>
         </div>
         <div class="col-lg-4 col-md-4 col-12 order-md-2 order-1">
            <h6 class="mb-4">CATEGORIES</h6>
            <ul style="columns: 2">
              @foreach($cat as $cats)
            @if(isset($cats->cat_id))
          

            <li class="nav-item">
                <a href="{{url('all-products?id='.$cats->cat_id)}}"  >{{$cats->title ?? ''}}</a>
                <!-- @foreach($cats->subcategory as $subcategory)
                <ul class="list-unstyled collapse" id="homeSubmenu{{$cats->cat_id}}" style="">
                    <li>
                        <a href=" @if(isset($subcategory->cat_id))  {{url('all-products?id='.$subcategory->cat_id)}} @endif" class="nav-link">{{$subcategory->title ?? ''}}</a>
                    </li>

                </ul>

                @endforeach -->
            </li>
              @endif
            @endforeach

              <!-- <li><a href="#">Burger</a></li>
              <li><a href="#">Beverages</a></li>
              <li><a href="#">Pizza</a></li>
              <li><a href="#">Fried</a></li>
              <li><a href="#">Pasta</a></li>
              <li><a href="#">Sandwiches</a></li>
              <li><a href="#">Wrap Roll</a></li>
              <li><a href="#">Dessert</a></li>
              <li><a href="#">Snacks</a></li>
              <li><a href="#">Garlic Bread</a></li> -->
            <ul>        
         </div>
         <!-- <div class="col-lg-2 col-md-2 col-5 order-md-3 order-2">
            <h6 class="mb-4" style="height:20px;"></h6>
            
              </div> -->
         <div class="col-lg-2 col-md-2 col-5 order-md-4 order-2">
            <h6 class="mb-4">QUICK LINKS</h6>
            <ul>
            <!-- <li><a href="{{url('/my-profile')}}">My Account</a></li> -->

            @if(session()->has('userData'))
             <li><a href="{{url('/my-profile')}}">My Account</a></li>
             @else
             <li><a href="javascript::void(0);" data-href="{{ route('login-register') }}" data-container="login_register" id="clikctoOpenRegisteres" class="btn btn-link link-modal az" style="padding-left: 0px;">My Account</a></li>
               @endif

            <li><a href="{{url('/about')}}">About Us</a></li>
            <!-- <li><a href="{{-- url('/blog') --}}">Blog</a></li> -->
            <li><a href="{{url('/all-products')}}">Products</a></li>
            <li><a href="{{url('/contact')}}">Contact Us</a></li>
            <li><a href="{{url('/privacy')}}">Privacy Policy</a></li>
            <li><a href="{{url('/term-and-condition')}}">Terms</a></li>
            <li><a href="{{url('/shipping_policy')}}">Shipping Policy</a></li>
            <li><a href="{{url('/return_refund')}}">Return & Refund</a></li>
            <ul>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-12 text-md-left text-center order-md-5 order-4">
            <h6 class="mb-4 appDisablebalock">Download App</h6>
            <div class="app appDisablebalock">
               <a class="appDisableCss" href="JavaScript:Void(0);"><img src="{{ url('assets/website/img/google.png') }}" alt=""></a>
               <a class="appDisableCss" href="JavaScript:Void(0);"><img src="{{ url('assets/website/img/apple.png') }}" alt=""></a>
            </div>
            <h6 class="mb-4 mt-4 appDisableBelowlock">GET IN TOUCH</h6>
            <div class="footer-social">


               <a class="btn-facebook" href="https://www.facebook.com/" target="_blank">
                  <i class="mdi mdi-facebook"></i>
               </a>
               
                <a class="btn-twitter" href="https://twitter.com/" target="_blank">
                  <i class="mdi mdi-twitter"></i>
               </a>
               
               <a class="btn-instagram" href="https://www.instagram.com/">
                  <i class="mdi mdi-instagram" target="_blank"></i>
               </a>

               <a class="btn-whatsapp" href="https://in.pinterest.com/" target="_blank">
                  <i class="mdi mdi-pinterest"></i>
               </a>

               <a class="btn-whatsapp" href="https://www.youtube.com/" target="_blank">
                  <i class="mdi mdi-youtube-tv"></i>
               </a>

            </div>   
            <div class="media mt-3">
              <i class="fa fa-map-marker" style="color: #fff; margin-top: 8px;font-size: 16px;"></i>
              <div class="media-body pl-2">
                <p class="mt-1"><a href="javascript:void(0)">NICIA food and drinks Pvt ltd <br> Plot no 112, main ignou road, Neb sarai, New Delhi 68</a></p>
              </div>
            </div>
            
            
               
         </div>
      </div>
   </div>
</section>
<!-- End Footer -->

<!-- Copyright -->
<section class="p-1 footer-bottom">
   <div class="container">
      <div class="row no-gutters">
         <div class="col-lg-6 col-sm-6 t-center">
            <p class="mt-1 mb-0 ">&copy; Copyright <strong class="">Alobha Technologies</strong>. All Rights Reserved<br>
        </p>
         </div>
         <div class="col-lg-6 col-sm-6 text-right t-center">
            <img alt="osahan logo" src="{{url('/assets/img/payment_methods.png')}}">
         </div>
      </div>
   </div>
</section>
<!-- End Copyright -->

<!-- Add To cart side bar -->
<div class="cart-sidebar">
   <div class="cart-sidebar-header">
      <!--added code to out of stock error beg-->
      <div class="stockErrorcontainer" style="color:#FF0000;text-align-center;display:none;"></div>
      <!--added code to out of stock error end-->
      <?php
         $totalitem=0;
         if(session()->has('totalcartqty')){
            $totalitem=session()->get('totalcartqty');
         }
       ?>
      <h5>
         My Cart<span class="text-success"><span>(</span> <span id="totalquant" class="totalquant">{{$totalitem}}</span><span> item)</span></span> <a data-toggle="offcanvas" class="float-right" href="#"><i class="mdi mdi-close"></i>
         </a>
      </h5>
   </div>
   <div class="cart-sidebar-body">
      <?php 

       //echo '<pre>';
      //print_r($products);


      $ordertotals=0;
       $cart_product_list=session()->get('cart_product_list');
      
       if(!empty($cart_product_list)){

      foreach($cart_product_list as $product){

      // $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
       $price_after_discount=$product->price;
       //$discountprice=$product->mrp*$product->discount_percentage/100;
         if($product->mrp > 0  && $product->price >0) {
            $discountdata=(($product->mrp - $product->price)/$product->mrp)*100;
         }else{
            $discountdata=0;
         }

         if(session()->has('footer_cart_qty')){
            $qty= session()->get('footer_cart_qty');
            if(isset($qty[$product->varient_id])){
               $ordertotals+=$price_after_discount * $qty[$product->varient_id];
            }
         }
 
      ?>
      <input type="hidden" name="stockvalue" value="{{$product->stock ?? ''}}" id="stockvalue{{$product->varient_id}}">
       <input type="hidden" value="{{$qty[$product->varient_id]}}" id="popup_cart_qty{{$product->varient_id}}" >
       <div class="cart-list-product extrajs" id="singleitem{{$product->varient_id}}">
         <a class="float-right remove-cart" href="#"><i class="mdi mdi-close" onclick="removeproduct('{{$product->varient_id}}')"></i></a>
         <img class="img-fluid" src="@if(!empty($product->varient_image))  {{url('admin-panel/'.$product->varient_image)}} @else {{url('admin-panel/'.$product->product_image)}} @endif" alt="">
         <span class="badge badge-success">@if($discountdata > 0) {{round($discountdata,2)}}% OFF @endif</span>
         <h5><a href="#">{{$product->product_name}}</a></h5>
         <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - {{$product->quantity}} <!-- {{$product->unit}} --></h6>
         <p class="offer-price mb-0">₹<span id="priceafterdis{{$product->varient_id}}">{{$price_after_discount}}</span> <i class="mdi mdi-tag-outline"></i> <span class="regular-price">@if($product->discount_percentage > 0) ₹{{$product->mrp}} @endif</span></p>

         <!--added code for single product total price beg-->
         <p calss="price_qty_tab mt-0">
            <span class="price_dis">₹{{$price_after_discount}}X</span>
            <span class="quant_single{{$product->varient_id}}">{{$qty[$product->varient_id]}}</span>
            <span>=₹</span>
            <span class="single_product_total{{$product->varient_id}}">{{$price_after_discount * $qty[$product->varient_id] }}</span>         
            <br>

            <p class="discountShow"></p> 
           
         </p>



         <!--added code for single product total price end-->
         
         <p class="offer-price mb-0 quantity">
               <span class="input-group-btn">
                <button disabled="disabled" class="btn btn-theme-round btn-number" type="button">
                  <span onclick="cartupdate('{{$product->varient_id}}','dec')" id="dec{{$product->varient_id}}" class="dec{{$product->varient_id}}" style="font-size:28px">
                    @if($qty[$product->varient_id]==1)
                     x
                    @else
                     -
                    @endif
                    
                  </span>
                </button>
             </span>
               <span><input type="text" max="10" min="1" value="{{$qty[$product->varient_id]}}"  name="quant[1]" class="quant{{$product->varient_id}}" readonly></span>
               <span class="input-group-btn">
                  <button class="btn btn-theme-round btn-number" type="button">
                     <span onclick="cartupdate('{{$product->varient_id}}','inc')" id="inc{{$product->varient_id}}" class="inc{{$product->varient_id}}">+</span>
                  </button>
               </span>

               
      </p>
         <!--added to inc or dec end-->
      </div>
   <?php } 
}else{?>
   <!-- <div class="emptycartmsg">Your Cart is Empty</div> -->
   <!--added to show empty shopping cart-->
   <div class="container-fluid mt-100 mt-ext">
    <div class="row">
        <div class="col-md-12">
            <div class="carda">
                
                <div class="card-body cart">
                    <div class="col-sm-12 empty-cart-cls text-center"> <img src="https://i.imgur.com/dCdflKN.png" width="130" height="130" class="img-fluid mb-4 mr-3">
                        <h3><strong>Your Cart is Empty</strong></h3>
                         <a href="{{url('/')}}" class="btn btn-primary cart-btn-transform m-3" data-abc="true">continue shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   <!--added to show empty shopping cart-->
<?php }
   ?>
      
   </div>
  <?php 
$disp='block';
if(empty($cart_product_list)){
   $disp='none';
}
  ?>
   <div class="cart-sidebar-footer show_checkout" style="display: {{$disp}}">
      <div class="cart-store-details">
         <!-- <p>Sub Total <strong class="float-right subtotal_popup">$900.69</strong></p> -->
         <p>Sub Total 
            <strong class="float-right">
               ₹<span class="subtotal_popup">{{$ordertotals}}</span>
            </strong>
         </p>

             
        
        <!--  <p>Delivery Charges <strong class="float-right text-danger">+ $29.69</strong></p> -->
         <!-- <h6>Your total savings <strong class="float-right text-danger">$55 (42.31%)</strong></h6> -->
      </div>


      @if(session()->has('userData'))

      <a href="{{url('/checkout')}}" data-toggle="modalaz" data-target="#checkoutmodalaz "><button class="btn btn-secondary btn-lg btn-block text-left" type="button"><span class="float-left"><i class="mdi mdi-cart-outline"></i> Proceed to Checkout </span><span class="float-right"><strong class="">₹<span class="cart_total_popup">{{$ordertotals}}</span></strong> <span class="mdi mdi-chevron-right"></span></span></button></a>

      @else
      <!-- <input data-toggle="collapseaz" data-container="login_register" data-href="{{ route('login-register') }}" data-target="#collapseThreeaz" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg" value="BUY NOW"  id="clickTocheckRegistored"/> -->


      <a href="#" data-toggle="collapseaz" data-container="login_register" data-href="{{ route('login-register') }}" data-target="#collapseThreeaz" aria-expanded="false" aria-controls="collapseThree" id="clickTocheckRegistored"><button class="btn btn-secondary btn-lg btn-block text-left" type="button"><span class="float-left"><i class="mdi mdi-cart-outline"></i> Proceed to Checkout </span><span class="float-right"><strong class="">₹<span class="cart_total_popup">{{$ordertotals}}</span></strong> <span class="mdi mdi-chevron-right"></span></span></button></a>

      @endif

   </div>
   
</div>
<!-- End Add To cart side bar -->

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
                  <div class="card checkout-step-one" style="display:none;">
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
                           <span class="number">1</span> Delivery Address
                           </button>
                        </h5>
                     </div>
                     <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <form method="post" action="{{url('/checkout')}}">
                                    {{csrf_field()}}
                              <div class="row">
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label">First Name <span class="required">*</span></label>
                                       <!-- <input class="form-control border-form-control" value="" placeholder="Gurdeep" type="text"> -->
                                       <input class="form-control border-form-control" name="ufname" value="" placeholder="" type="text" required>
                                       <!--  <input type="hidden" name="ordertotal" id="ot" value="" /> -->
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label">Last Name <span class="required">*</span></label>
                                       <!-- <input class="form-control border-form-control" value="" placeholder="Osahan" type="text"> -->
                                       <input class="form-control border-form-control"  name="ulname" value="" placeholder="" type="text" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label">Phone <span class="required">*</span></label>
                                       <!-- <input class="form-control border-form-control" value="" placeholder="123 456 7890" type="number"> -->
                                       <input class="form-control border-form-control"  name="uphone" value="" placeholder="" type="text" required>
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label">Email Address <span class="required">*</span></label>
                                       <!-- <input class="form-control border-form-control " value="" placeholder="iamosahan@gmail.com" disabled="" type="email"> -->
                                       <input class="form-control border-form-control "  name="uemail" value="" placeholder=""  type="text" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label">City <span class="required">*</span></label>
                                       
                                       <select class="select2 form-control border-form-control" data-select2-id="16" tabindex="0" aria-hidden="false" name="ucity">
                                             <option value="" data-select2-id="18">Select City</option>
                                             <option value="Noida" data-select2-id="Noida">Noida</option>
                                             <option value="Greater Noida" data-select2-id="Greater Noida">Greater Noida</option>
                                             </select>
                                       
                                    </div>
                                 </div>
                              </div>
                            
                              <div class="row">
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label class="control-label">Shipping Address <span class="required">*</span></label>
                                      <textarea class="form-control border-form-control" name="ushippaddr" required></textarea>
                                       <small class="text-danger">Please provide the number and street.</small>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="row">
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <div class="custom-control custom-checkbox">
                                                <input type="checkbox" required class="custom-control-input" id="" style="z-index: 2;">
                                                <label class="custom-control-label" for="">I accept the  <a href="{{url('/terms')}}" style="color: #e96125; cursor: pointer;">terms and conditions.</a></label>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                              <!-- <button type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg">NEXT</button> -->
                              <input type="submit" name="submit" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg" value="NEXT" />
                              
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
<!-- End Modal -->

<div id="overlay"></div>
<!--added script for add to cart azwar--->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>


 

function addtocart(id,productid) {
 
 
  //$('#addtocart'+id+productid).attr('disabled','disabled'); 
  $('#addtocart'+id).attr('disabled','disabled');
  $('.addtocart'+id).attr('disabled','disabled'); 
  $('.coupon_details').hide();
  $('#coupon_code').val('');
  $('.coupon_msg').html('');
   
 
  //$('#addtocart'+id+productid+' i').removeClass('mdi mdi-cart-outline');
  $('#addtocart'+id+' i').removeClass('mdi mdi-cart-outline');
  $('.addtocart'+id+' i').removeClass('mdi mdi-cart-outline');
 
  //$('#addtocart'+id+productid+' i').addClass('fa fa-spinner fa-spin');
  $('#addtocart'+id+' i').addClass('fa fa-spinner fa-spin');
  $('.addtocart'+id+' i').addClass('fa fa-spinner fa-spin');

  $('.show_checkout').show(); //to show checkout total tab popup container addtocart
  var fs   = $('.cart-value').text();
  var pidd = id;
  $.ajax({
          type: 'get',
          url:"{{url('/addtocart')}}/"+pidd,
          success : function(data){
                   swal({
                         title: "",
                         text: "Product has been added to cart!",
                         timer: 1000,
                         buttons: false
                       });
              console.log(data);

              //added code to hide defaultqty block on click of add to cart button beg 
              $('.defaut_qty_block'+id).hide();
              //hide add to cart button
              $('#addtocart'+id).hide();
              $('.addtocart'+id).hide();
              //append qty block in place of add to cart button
              $('.append_qty_block'+id).html(data.qtyhtmlindividual);
              $('.append_qty_block'+id).show();

              //added code to hide defaultqty block on click of add to cart button end
             
              
              // $('#addtocart'+id+productid).removeAttr('disabled','disabled');
               $('#addtocart'+id).removeAttr('disabled','disabled');
               $('.addtocart'+id).removeAttr('disabled','disabled');
              //$('#addtocart'+id+productid+' i').addClass('mdi mdi-cart-outline');
              $('#addtocart'+id+' i').addClass('mdi mdi-cart-outline');
              $('.addtocart'+id+' i').addClass('mdi mdi-cart-outline');
            
              //$('#addtocart'+id+productid+' i').removeClass('fa fa-spinner fa-spin');
              $('#addtocart'+id+' i').removeClass('fa fa-spinner fa-spin');
              $('.addtocart'+id+' i').removeClass('fa fa-spinner fa-spin');


            $('.cart-value').text(data.totalitem);
            $('.cart-list-product').empty();
            $('.cart-sidebar-body').html(data.carthtml);
            $('.subtotal_popup').html(data.cartsubtotal);
            $('.cart_total_popup').html(data.cartsubtotal);
            $('#ot').val(data.cartsubtotal);
            $('#otm').val(data.cartMrpsubtotal);
            $('#storeWiseDetail').val(JSON.stringify(data.checkStoreArray));
            $('#bulkOrderDiscount').val(JSON.stringify(data.bulkOrderDiscount));
            $(".bulk_amt").html(data.bulkOrderDiscount);
            if(data.bulkOrderDiscount<=0){
              $(".bulk_details").css('display','none');      
            }else{
              $(".bulk_details").css('display','block');
            }
            
            
            $('#totalquant').text(data.totalitem);
            $('.totalquant').text(data.totalitem);
            //append delivey charge beg
            $('.delivery_amt').text(data.deliverycharge);

          
            $('#delcharge').val(data.deliverycharge); //checkout  hidden charge change 
            //append delivey charge end

           //added code to disable add to cart button n alert out of stock beg
          
           if(data.productstock == data.cartqty) { //new added on 30 nov 2020
            //debugger;

           // $('#addtocart'+id+productid).prop('disabled','true');
            // $('#addtocart'+id).prop('disabled','true'); //new code added to disable button

            $('.append_qty_block'+id).css('display','none'); //new code added to disable button
           
            $('.stock'+id+' .product-header').css('opacity','0.5');
             $('.stock'+id+' .product-header').css('pointer-events','none');
             $('#productid'+id+' .stock').css('color','red');
           
            $('.stock'+id+' .stock').text('Out of Stock');
        
            $('.stockbtn'+id).text('Out of Stock');
            $('.stockbtn'+id).css('color','red');
            $('.stockbtn'+id).css('display','block');

            $('.stockbtnNew'+id).text('Out of Stock');
            $('.stockbtnNew'+id).css('color','red');
             
          }
          
           //added code to disable add to cart button n alert out of stock nd
       

          }
  


        });

 }
 function removeproduct(rpid){
  //console.log(window.location.href);
  //added code to show confirm box before product remove beg
  $('.coupon_details').hide();
  $('#coupon_code').val('');
  $('.coupon_msg').html('');
  swal({
    title: "Are you sure you want to remove cart item?",
    //text: "Once deleted, you will not be able to recover this imaginary file!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {

     
      swal("Cart item has been removed", {

        icon: "success",
      });
      $('.append_qty_block'+rpid).hide();
      $('#addtocart'+rpid).show();
      $('#addtocart'+rpid).css('opacity',1);
      $('#addtocart'+rpid).attr('disabled', false);
      $('.stockbtn'+rpid).css('display','none');
      //$('.stockbtn'+rpid).css('color','#fff');
      $('#stockbtn'+rpid).css('display','block');
      $('#stockbtn'+rpid).text('In Stock');
      $('#stockbtn'+rpid).css('color','#fff');

 
    var ot=$('#ot').val();

    var topquant=$('#totalquant').text();
    //to get total quantity of a single item beg
    var totalcount_of_single_product=$('.quant'+rpid).val();
    //var totalcount_of_single_product=$('#popup_cart_qty'+rpid).val(); //add 10 nov
    //alert(totalcount_of_single_product);
    //to get total quantity of a single item end
    //
    var priceafterdis=$('#priceafterdis'+rpid).text();
    // alert(parseInt(priceafterdis));  
    var productqty=$('#popup_cart_qty'+rpid).val();
    var cart_total_popup=$('.cart_total_popup').text();
    var subtotal_popup=$('.subtotal_popup').text();
    //var singleproducttotal=parseFloat(priceafterdis) * parseInt(productqty); //previous
    var singleproducttotal=parseFloat(priceafterdis) * parseInt(totalcount_of_single_product);
    $('.cart_total_popup').text((parseFloat(cart_total_popup) - parseFloat(singleproducttotal)).toFixed(2));
    $('.subtotal_popup').text((parseFloat(subtotal_popup) - parseFloat(singleproducttotal)).toFixed(2));
    $('#singleitem'+rpid).remove();
    $('#totalquant').text(parseInt(topquant) - 1);
    $('.totalquant').text(parseInt(topquant) - 1);
    $('#ot').val((parseFloat(ot) - parseFloat(singleproducttotal)).toFixed(2));
    //checkout total
    $('.otsidebarTotal').text((parseFloat(ot) - parseFloat(singleproducttotal)).toFixed(2));
  
    //popup remove product code
    //added code to remove product beg pp

     /*var totalitems=$('.cart-value').text();

      var totalitems= parseInt(totalitems) + 1;

      $('.cart-value').text(totalitems);*/

      var rpidd=rpid;

      var ramount= $('#pp'+rpidd).text();

   //alert(ramount);  s

   var tp= $('#tp1').text();
   //alert(parseInt(tp));
   

   //alert(tp);

   var newtotal= parseInt(tp) - parseInt(ramount);

    $('.tp').text(parseInt(newtotal));

     var cartcurrent=$('.cart-value').text();

     //$('.cart-value').text(parseInt(cartcurrent) - 1);

      $.ajax({

          type: 'get',

         // url: 'http://localhost/blog/public/addtocart',remove-product
           url:"{{url('/remove-product')}}/"+rpidd,
          //data: {'pid':pidd},  
          success : function(data){
            console.log(data);
            //append delivey charge beg
            
            $('.delivery_amt').text(data.delivercharge);
            $('#otm').val(data.cartMrpsubtotal);
            $('#storeWiseDetail').val(JSON.stringify(data.checkStoreArray));
            $('#bulkOrderDiscount').val(JSON.stringify(data.bulkOrderDiscount));
            $(".bulk_amt").html(data.bulkOrderDiscount);
            if(data.bulkOrderDiscount<=0){
              $(".bulk_details").css('display','none');      
            }else{
              $(".bulk_details").css('display','block');
            }
            $('.otsidebarTotal').text(data.cartsubtotal);
            $('.otsidebarTotalgrand').text((data.cartsubtotal + data.delivercharge-data.bulkOrderDiscount).toFixed(2));
            //append delivey charge end
            // $('#category'+value).remove()
            $('.coup_amt').text(data.coupon_amount);
            $('.cart-value').text(data.totalitem);//added 9 jan 2021
            //added to show coupon amount end
            //append delivey charge end
            // $('#category'+value).remove()
            //added code for send on homepage if cart subtotal is zero beg
            if(data.cartsubtotal == 0){
              //window.location.href="{{url('/')}}";
              var hintPageName = location.href.split("/").slice(-1);
              var lastpageName = hintPageName[0];
              var getBase = '<?=url('');?>';
              if(lastpageName=='checkout' || lastpageName=='checkout#'){
                window.location.href=getBase;
              }else{
                setTimeout(function () { document.location.reload(true); }, 500);
              }
            }else{
              setTimeout(function () { document.location.reload(true); }, 500);
            }
            //setTimeout(function () { document.location.reload(true); }, 1100);

             $('#rp'+rpidd).remove();
             var tp= $('#tp1').text();
             if(parseInt(tp) == 0 ){
               //alert('yes');
                $('.cart-table').remove();
                //location.reload(true); //reload
             }
             console.log(data);

             var cart_total_popup=$('.cart_total_popup').text();
             $('.cart-sidebar-footer').show();

             if(parseFloat(cart_total_popup) == 0.00 || parseFloat(cart_total_popup) < 1 || data.cartsubtotal == 0){
               $('.cart-sidebar-footer').hide();
                  //added to do delivery amount zero if cart_total_popup is zro beg
                  $('.delivery_amt').text(0);
                  $('.totalquant').text(0);
                  $('.otsidebarTotal').text(0);
                  $('.otsidebarTotalgrand').text(0);

               //added to do delivery amount zero if cart_total_popup is zro end


              //
             swal({
             title: "",
             text: "Your Cart is Empty!",
             timer: 1000,
            
             buttons: false
           });
   //added to show cart empty msg  cart-sidebar-body,emptycartmsg

    $('.cart-sidebar-body').html('<div class="container-fluid mt-100 mt-ext">\
    <div class="row">\
        <div class="col-md-12">\
            <div class="carda">\
                \
                <div class="card-body cart">\
                    <div class="col-sm-12 empty-cart-cls text-center"> <img src="https://i.imgur.com/dCdflKN.png" width="130" height="130" class="img-fluid mb-4 mr-3">\
                        <h3><strong>Your Cart is Empty</strong></h3>\
                         <a href="{{url('/')}}" class="btn btn-primary cart-btn-transform m-3" data-abc="true">continue shopping</a>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>\
</div>');

   //added code to remove product end

  //location.reload(true); //to reload page total item in cart is zero
 //
}else{
   $('.cart-sidebar-footer').show();
}



          }  



        });
    $('#singleitem'+rpid).remove(); 
   
  } else {
      //swal("Your Product is safe!");
    }
  });

 }

//added code to manage inc/dec qty beg

 function manageqty(id,type){

   var quant=$('.quant'+id).val();
   var pidd=id;

   var type=type;

  var prevqty=$('.quant'+id).val();
  var stock= $('#stockvalue'+id).val();
   //get stock value end
     if(parseInt(stock) > parseInt(prevqty)) {

   if(type == 'inc') {
 //added code to enable disable + btn on the basis of stock end  
   var pricetoadd=$('#priceafterdis'+id).text();
   var subtotal_popup=$('.subtotal_popup').text();
   var cart_total_popup=$('.cart_total_popup').text();
 
   //added for single product total end
   $('.subtotal_popup').text((parseFloat(subtotal_popup) + parseFloat(pricetoadd)).toFixed(2));
   //alert(cart_total_popup);
   $('.cart_total_popup').text((parseFloat(cart_total_popup) + parseFloat(pricetoadd)).toFixed(2));
   $('.otsidebarTotal').text((parseFloat(cart_total_popup) + parseFloat(pricetoadd)).toFixed(2)); //mange chckout total on inc 
   //added code to change form ot val on dec beg
   var sidebartotal= $('.otsidebarTotal').text();
   $('#ot').val(parseFloat(sidebartotal));
   //added code to change form ot val on dec end
   
   //get price of product end

  var quant= $('.quant'+id).val(parseInt(prevqty) + 1);


 var getquant=$('.quant'+id).val();

  var uprice = $('#uprice'+id).text();

  var newsubtotal= parseInt(getquant) * parseInt(uprice);

  var subtotal = $('#pp'+id).text(newsubtotal);

   var prevtotal=$('#tp1').text();

$('.tp').text(parseInt(parseInt(prevtotal) + parseInt(uprice)));

}
 } // if block stock end
var quant= $('.quant'+id).val();

 if(type == 'dec' && parseInt(quant) >= 2) {
   //get price of product beg
   var pricetodec=$('#priceafterdis'+id).text();
   var subtotal_popup=$('.subtotal_popup').text();
   var cart_total_popup=$('.cart_total_popup').text();
   $('.subtotal_popup').text((parseFloat(subtotal_popup) - parseFloat(pricetodec)).toFixed(2));
   $('.cart_total_popup').text((parseFloat(cart_total_popup) - parseFloat(pricetodec)).toFixed(2));
   $('.otsidebarTotal').text((parseFloat(cart_total_popup) - parseFloat(pricetodec)).toFixed(2)); //added for checkout total manage on ec
   //added code to change form ot val on dec beg
   var sidebartotal= $('.otsidebarTotal').text();
   $('#ot').val(parseFloat(sidebartotal));
   //added code to change form ot val on dec end
   
   //get price of product end

  var quant= $('.quant'+id).val(parseInt(prevqty) - 1);

  var getquant=$('.quant'+id).val();

  var uprice = $('#uprice'+id).text();


  var newsubtotal= parseInt(getquant) * parseInt(uprice);

  var subtotal = $('#pp'+id).text(newsubtotal);

  var prevtotal=$('#tp1').text();

$('.tp').text(parseInt(parseInt(prevtotal) - parseInt(uprice)));

}



   // if( parseInt(stock) > parseInt(prevqty) && parseInt(quant) > 1){
    if( parseInt(stock) > parseInt(prevqty)){
   

   $.ajax({
      type: 'get',

           url:"{{url('/addtocart')}}/"+pidd+'/'+type,

          success : function(data){
                  console.log(data);
             //append delivey charge beg
            $('.delivery_amt').text(data.delivercharge);

            $('#delcharge').text(data.delivercharge); //checkout  hidden charge change 
            //append delivey charge end
          }

        });
} //end run ajax if qty greater than one



   ///////////////////////////////////// inc dec end

 }

 //added code to manage inc/dec qty end
 //ajax to get dat of varient product and show it in below banner category carousel beg
 function getvarient(productid, varientid)
 {
    event.preventDefault();
    var productid=productid;
    var varientid=varientid;
    var dd= $('#varient'+productid+varientid).parent().next('button').attr('onclick','addtocart("'+varientid+'")');

    //ajax call beg
    $.ajax({
          type: 'get',
          url:"{{url('/varient-product')}}/"+productid+'/'+varientid,
          success : function(data){
              var mrp=data.product_varient_details.mrp;
              var price=data.product_varient_details.price;
              var discount=((mrp-price)/mrp)*100;

             $("#varient_img"+productid).attr('src',data.variantimage);

             //change varient image end
             //availabilty beg
             $('#availability'+productid).text(data.product_varient_details.quantity+' '+data.product_varient_details.unit);
             //availabilty end
             //varient price after discount beg
             $('#variant_price'+productid).text(price.toFixed(2));
             //varient price after discount end
             //product varient discount beg
            // var product_discount=data.product_varient_details.discount_percentage;
            // alert(product_discount);
             $('#product_discount'+productid).text(discount+ '% OFF');
             //product varient discount end
             //for mrp beg
             $('#product_mrp'+productid).text(data.product_varient_details.mrp.toFixed(2));
             //for mrp end
             //to change color of selected variant green and other info beg
             $('#productid'+productid+' .varient button').removeClass('btn btn-success');
             $('#productid'+productid+' .varient button').addClass('btn btn-light');
            // $('#varient'+productid+varientid+'button').removeClass('btn btn-info');
             $('#varient'+productid+varientid+' button').removeClass('btn btn-light');
             $('#varient'+productid+varientid+' button').addClass('btn btn-success');
             
             //to change color of selected variant green and other info end

             //added code to change varient id in addtocart button end
             //$('#variant_price'+varientid).text(data.product_varient_details.price);
           
          }

        

        });
    //ajax call end
    
}
 //ajax to get dat of varient product and show it in below banner category carousel end


 function textonly(e){
  var key = e.keyCode;
  if (key >= 48 && key <= 57) {
      e.preventDefault();
  }
 }
  function numonly(e){
  var key = e.keyCode;
  if (key < 48 || key > 57) {
      e.preventDefault();
  }
 }

 //added code to get varient beg
function getvarientdata(productid)
{
 // alert(productid);
  let new_varientid = $(`.variantchange.selpid${productid} option:selected`).val();
  let current_varientid = $(`#current_varient_${productid}`).val();
  
  $(`#current_varient_${productid}`).val(new_varientid);

  //to change the container id 
  $(`.stock${current_varientid}`).addClass(`stock${new_varientid}`);
  $(`.stock${new_varientid}`).removeClass(`stock${current_varientid}`);

  $(`#addtocart${current_varientid}`).attr('id', `addtocart${new_varientid}`);
  $(`#addtocart${new_varientid}`).removeClass(`addtocart${current_varientid}`);
   
   //added code to change the class of parent div and addtocartbtn id end
  $(`#productid${productid} button`).attr('onclick',`addtocart("${new_varientid}","${productid}")`);
 
   //ajax call to get the data of varient price,unit,mrp,image beg
   //ajax call beg
    $.ajax({
          type: 'get',
          url:`{{url('/varient-product')}}/${productid}/${new_varientid}`,
          success : function(data)
          {
            console.log(data);
            var mrp      = data.product_varient_details.mrp;
            var price    = data.product_varient_details.price;
            var discount = ((mrp-price)/mrp)*100;

             //data related to varient product end
             $(`#varient_img${productid}`).attr('src', `admin-panel/${data.variantimage}`);

             //change varient image end
             $(`#availability${productid}`).text(data.product_varient_details.quantity+' '+data.product_varient_details.unit);
             //availabilty end

             //varient price after discount beg
             $(`#variant_price${productid}`).text(price.toFixed(2));
             
             $(`#product_discount${productid}`).text(discount.toFixed(0) + '% OFF');
             //product varient discount end
            
             //for mrp beg
             $(`#product_mrp${productid}`).text(data.product_varient_details.mrp.toFixed(2));
          
            //  //chnage stock n url value beg
             $(`.productstock${new_varientid}`).val(data.stock);

            let product_stock_value = $(`.quant${new_varientid}`).val();
            product_stock_value = product_stock_value == undefined ? 0 : parseInt(product_stock_value);

            let stock_value = data.stock;
            
            if(stock_value > product_stock_value)
            {
              $(`#stockmessage${productid}`).html('');
              $(`#addtocart${new_varientid}`).removeAttr('disabled','disabled');
              $(`#productid${productid}>a>.product-header`).css('opacity','1'); 
            }else
            {
              $(`#stockmessage${productid}`).text('Out of Stock');
              $(`#addtocart${new_varientid}`).attr('disabled','disabled');
              $(`#productid${productid}>a>.product-header`).css('opacity','0.5'); 
            }

             $('#productid'+productid+' a' ).attr('href',`product-detail/${new_varientid}`);
             //chnage stock n url value end
            
             // //to show out of stock beg
             // if(data.stock == 0){
             
             //  $(`.stock${new_varientid}`).css('opacity','0.5'); 
             
             // $(`.stock${new_varientid}`).css('pointer-events','none'); //amir sir code
             //  //$(`.stock${new_varientid} product-header`).css('pointer-events','none');
             //  $(`.stock${new_varientid} .stock`).text('Out of Stock');
             // }else if(data.stock > 0){
             //   $(`.stock${new_varientid}`).css('opacity','1');
             //  $(`.stock${new_varientid}`).css('pointer-events','');
             //  $(`.stock${new_varientid} .stock`).text(''); 
             // }
             //to show out of stock end
          }
        });
}

//added code to get varient end
/*added code to update cart beg*/
 function cartupdate(id,type){
  //console.log(id);
  var pid= id
  var stock=$('stockvalue'+pid).val();
  $('.coupon_details').hide();
  $('#coupon_code').val('');
  $("#paymenttype").val('');
  $("#showOnlineAmountBlock").css('display','none');
  
  $('.coupon_msg').html('');
  var qty=$('.quant'+id).val();
  // console.log(parseInt(qty));
  if(parseInt(qty) == 1 && type=='dec'){
   removeproduct(pid);
   
  }
   //ajax beg
   $.ajax({
      type: 'get',

           url:"{{url('/updatecart')}}/"+pid+'/'+type,

          success : function(data){
                  console.log(data);
                  console.log("------------------------");

                     $('#storeWiseDetail').val(JSON.stringify(data.checkStoreArray));
                     $('#bulkOrderDiscount').val(JSON.stringify(data.bulkOrderDiscount));
                     $(".bulk_amt").html(data.bulkOrderDiscount);
                     if(data.bulkOrderDiscount<=0){
                      $(".bulk_details").css('display','none');      
                    }else{
                      $(".bulk_details").css('display','block');
                    }
                      if(data.status == 1){
                        $('.quant'+pid).val(data.updatedQty);
                        // console.log(data.updatedQty);
                        if(parseInt(data.updatedQty) == 1){
                          $('.dec'+id).text('x');
                        }else{
                          $('.dec'+id).text('-');
                        }

                        //added code to update particular iem qty beg quant_single
                        $('.quant_single'+pid).text(data.updatedQty);
                        //added code to get single product deatial single_product_total2
                         $('.single_product_total'+pid).text((data.updatedQty * data.productprice).toFixed(2));
                         $('.single_product_discount'+pid).text((data.updatedQty * data.productprice).toFixed(2));

                        //added code to update particular iem qty end
                      }
                      //added code to disable product container of variant if stock=cartqty beg
                      if(data.updatedQty == data.varientstock){
                       // alert(id);
                       
                       // $('.stock'+data.oldproductid).css('opacity','0.5');
                        //$('.stock'+id).css('opacity','0.5');
                        $('.stock'+id+' .product-header').css('opacity','0.5');
                        
                        // $('.stock'+id).css('pointer-events','none');
                        // $('.stock'+id).css('pointer-events','none');
                        $('#productid'+data.oldproductid+' .stock').css('color','red');
                        $('#productid'+data.oldproductid+' .stock').text('out of Stock');
                       // $('#addtocart'+data.oldproductid).prop('disabled','true');
                        $('#addtocart'+data.oldproductid).prop('disabled','true');
                        //$('#addtocart'+id).prop('disabled','true');
                        $('#addtocart'+id+data.oldproductid).prop('disabled','true');
                        $('.append_qty_block'+id).hide();
                        $('.stockbtn').text('Out of Stock');
                        $('.stockbtn').css('color','red');
                        $('.stockbtn'+id).css('display','block');

                        $('.stockbtnNew'+id).text('Out of Stock');
                        $('.stockbtnNew'+id).css('color','red');
                        $('.stockbtnNew'+id).css('display','block');
                      }else{
                        $('.stock'+id+' .product-header').css('opacity','1');
                        $('.append_qty_block'+id).show();
                        $('.stockbtn'+id).css('display','none');
                        $('.stockbtnNew'+id).text('In Stock');
                        $('.stockbtnNew'+id).css('color','#fff');
                        $('.stockbtnNew'+id).css('display','block');
                      }
                      //added code to disable product container of variant end
                      if(data.type == 'inc'){
                          // alert(data.type); 

                            $('.subtotal_popup').text((data.cartsubtotal).toFixed(2));

                           if(data.discount != null) {
                              $('.discountShow').html('');
                              var discountNumber = data.discount;
                              var discountValue = data.cartsubtotal*(discountNumber/100);
                              var discountValue = discountValue.toFixed(2);
                              $('.discountShow').append('<p>Discount ('+discountNumber+'%)   <strong>₹<span class="subtotal_popup2">'+discountValue+'</span></strong></p>');
                           }else{
                              $('.discountShow').html('');
                              var discountValue = 0;
                           }
                            
                            $('.cart_total_popup').text((data.cartsubtotal-discountValue).toFixed(2));
                            $('.otsidebarTotal').text((data.cartsubtotal).toFixed(2));
                            $('.otsidebarTotalgrand').text((data.cartsubtotal + data.delivercharge-data.bulkOrderDiscount).toFixed(2));
                            //added to mange delivery charge beg
                            $('.delivery_amt').text(data.delivercharge);

                           // $('#delcharge').text(data.deliverycharge); //checkout  hidden charge change 
                            $('#delcharge').val(data.delivercharge); //checkout  hidden charge change 
                             $('#ot').val((data.cartsubtotal).toFixed(2));
                             $('#otm').val((data.cartMrpsubtotal).toFixed(2));
                            //added to mange delivery charge end
                      }
                      //if(data.type == 'dec' && data.updatedQty > 1){
                      if(data.type == 'dec'){
                        //alert(data.type);
                      
                          
                            $('.subtotal_popup').text((data.cartsubtotal).toFixed(2));

                           if(data.discount != null) {
                              $('.discountShow').html('');
                              var discountNumber = data.discount;
                              var discountValue = data.cartsubtotal*(discountNumber/100);
                              var discountValue = discountValue.toFixed(2);
                              $('.discountShow').append('<p>Discount ('+discountNumber+'%)<strong class="float-right">₹<span class="subtotal_popup2">'+discountValue+'</span></strong></p>');
                           }else{
                              $('.discountShow').html('');
                              var discountValue = 0;
                           }
                            

                            $('.cart_total_popup').text((data.cartsubtotal-discountValue).toFixed(2));
                            $('.otsidebarTotal').text((data.cartsubtotal).toFixed(2));
                            $('.otsidebarTotalgrand').text((data.cartsubtotal + data.delivercharge-data.bulkOrderDiscount).toFixed(2));
                            $('#ot').val((data.cartsubtotal).toFixed(2));
                            $('#otm').val((data.cartMrpsubtotal).toFixed(2));
                            //added to mange delivery charge beg
                            $('.delivery_amt').text(data.delivercharge);

                           // $('#delcharge').text(data.deliverycharge); //checkout  hidden charge change 
                            $('#delcharge').val(data.delivercharge); //checkout  hidden charge change 
                            //added to mange delivery charge end
                      }
                    
                  
            
          }

        });
   //ajax end
} 
/*added code to update cart end*/


   </script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script type="text/javascript">
     /*added code for email validation on profile page beg*/
$('#user_email').focusout(function(){
        email_validate();
      });

      function email_validate() {
       

        var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var email = $('#user_email').val();
        
        if(email !== '') {
          if(pattern.test(email)) {
          $('#lbl').css('color','green');
          $('#email').css('border','2px solid green'); 
          $('#success').css('display','block');
          $('#error').css('display','none');
          $('#span1').css('display','none');
          $('#span2').css('display','none');
          $('#warning').css('display','none');
          }
          else {
          $('#lbl').css('color','red');
          $('#email').css('border','2px solid red'); 
          $('#error').css('display','block');
          $('#success').css('display','none');
          $('#span1').css('display','block');
          $('#span1').css('color','red');
          $('#span2').css('display','none');
          $('#warning').css('display','none');
          return false; //not let submit form email format wrong
          }
        }
        else {
          $('#span2').css('display','block');
          $('#span2').css('color','red');
          $('#lbl').css('color','red');
          $('#email').css('border','2px solid red'); 
          $('#error').css('display','none');
          $('#success').css('display','none');
          $('#warning').css('display','block');
          $('#span1').css('display','none');
           return false; //not let submit form if email empty
        }
      }
  
/*added code for email validation on profile page end*/


/*Contact Page verification*/
  
   $('#contact_form_name').keyup(function (){

    var username=$('#contact_form_name').val();

    if(username.length < 3){
       $('#contactForm').prop('disabled', true);
       $('#contact_form_name_error').text('Full name should be of minimum 6 Characters');
    }else{
         $('#contactForm').prop('disabled', false);
         $('#contact_form_name_error').text('');
    }
   });

     $('#contact_form_phone').keyup(function (){

    var mobile=$('#contact_form_phone').val();

    if(mobile.length > 10){
       $('#contactForm').prop('disabled', true);
       $('#contact_form_phone_error').text('Mobile no. must not exceed 10 digit');
    }else if(mobile.length < 10 ){
         $('#contactForm').prop('disabled', true);
          $('#contact_form_phone_error').text('Mobile no. must be of 10 digit');
    }else{
         $('#contactForm').prop('disabled', false);
         $('#contact_form_phone_error').text('');
    }
   });

   
   $('#contact_form_email').keyup(function(){
        var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var email = $('#contact_form_email').val();
        
        if(email !== '') {
          if(pattern.test(email)) {
              $('#contact_form_email_error').text('');
              $('#contactForm').prop('disabled', false);
           }else{
              $('#contact_form_email_error').text('Please enter valid email');
              $('#contactForm').prop('disabled', true);
           }
        }    
      });
  /*End Contact Page verification*/


  $("#clickTocheckRegistored").click(function(){

      swal("Hi Guest, to continue the further process you need to first register or log in?", {
        buttons: {
          registered: {
            text: "Continue",
            value: "registered",
          },
          cancel:true
        },
      })
      .then((value) => {

        switch (value) {
         
          case "new":
          var container = $(this).data("container");

          $.ajax({
            url      : $(this).data("href"),
            dataType : "html",
            success  : function(result) {
              $('div.'+container).html(result).modal('show');
              $('body').removeClass('toggled');
              $('.modal.login_register').addClass('zindex60');
            }
          });
          
          
          break;
          
          case "registered":

          var container = $(this).data("container");


          $.ajax({
            url      : $(this).data("href"),
            dataType : "html",
            success  : function(result) {
             
              $('div.'+container).html(result).modal('show');
              $('body').removeClass('toggled');
              $('.modal.login_register').addClass('zindex60');
            }
          });
          break;

          case "cancel":
          break;

          
          default:
          break;
        }
      });
}); 

  $(function(){
      $('.closeSideMenu').on('click', function(){
         $('.osahan-menu-2').toggleClass('left-0');
         $('#overlay').css("display", "none");
      });
   });
   /*$(function(){
      $('.closeMyCart').on('click', function(){
         $('.osahan-menu-2').toggleClass('left-0');
         $('#overlay').css("display", "none");
      });
   });*/

       

        

   </script>
   <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZbvJlKvTEKfrZmu5xdXhNdA6rwzDL5E8&libraries=places"
            type="text/javascript"></script>
   <script type="text/javascript">
     $(document).ready(function(){ 
         if (navigator.geolocation) { 
             navigator.geolocation.getCurrentPosition(showLocation,showError); 
         } else {
             //alert('Geolocation is not supported by this browser.'); 
             $('#location').html('Geolocation is not supported by this browser.'); 
         }  
     });

     function showError(error) {
      switch(error.code) {
        case error.PERMISSION_DENIED:
        swal("You denied the request for Geolocation. Please allow geolocation to show products", {
        icon: "info",
        });
        break;
        case error.POSITION_UNAVAILABLE:
        swal("Location information is unavailable.", {
        icon: "info",
        });
        break;
        case error.TIMEOUT:
        swal("The request to get user location timed out.", {
        icon: "info",
        });
        break;
        case error.UNKNOWN_ERROR:
        swal("An unknown error occurred.", {
        icon: "info",
        });
        break;
      }
    }

//      function getgeolocatio(latitude,longitude){
//     var geocoder;
//     geocoder = new google.maps.Geocoder();
//     var latlng = new google.maps.LatLng(latitude, longitude);

//     geocoder.geocode(
//         {'latLng': latlng}, 
//         function(results, status) {
//           console.log(results);
//             if (status == google.maps.GeocoderStatus.OK) {
//                 if (results[0]) {
//                     var add= results[0].formatted_address ;
//                     var  value=add.split(",");

//                     count=value.length;
//                     country=value[count-1];
//                     state=value[count-2];
//                     city=value[count-3];
//                     console.log("city name is: " + city);
//                 }
//                 else  {
//                     console.log("address not found");
//                 }
//             }
//             else {
//                 console.log("Geocoder failed due to: " + status);
//             }
//         }
//     );
// } 

     function showLocation(position) { 

       var latitude = position.coords.latitude;
       var longitude = position.coords.longitude;
       // var latitude = '28.7041'; //Delhi lat
       // var longitude = '77.1025'; //Delhi lng

       // var latitude = '28.523313'; //Delhi lat
       // var longitude = '77.223716'; //Delhi lng

         

       //  var latitude = '29.472561'; //Muzaffarnagar lat
       //  var longitude = '77.707130'; //Muzaffarnagar lng

        // var latitude = '27.1766701'; //agra lat
        // var longitude = '78.0080745'; //Agra lng
       

       console.log(latitude,longitude);
       //getgeolocatio(latitude,longitude);

       // var geocoder = new google.maps.Geocoder();
       // var latlng = new google.maps.LatLng(lat, lng);
       // geocoder.geocode({ 'latLng': latlng }, function (results, status) {
       //   if (status == google.maps.GeocoderStatus.OK) {
       //    if (results[0]) {
       //      var add = results[0].formatted_address ;
       //      console.log(add);
       //    }
       //  }
       // });


       $.ajax({ 
        type:'get',  
        url: "{{url('/get-current-location')}}",
        data:'latitude='+latitude+'&longitude='+longitude, 

        success:function(msg){
        console.log(msg);
         if(msg){ 

                  }else{ 
                  } 
                },
                error:function(err){
                 console.log("errorroo ");
                 console.log(err);
               } 
             }); 
         } 
     /*new added code to get visitor location 3 jan 2021 end*/
     /*added code search form submit beg*/
     function submitPinCode(){
      var searchvalue= $('#pincodesearch').val();
      
        $.ajax({ 
        type:'get',  
        url: "{{url('/change-pincode')}}",
        data:'pincodesearch='+searchvalue, 

        success:function(msg){
        console.log(msg);
         if(msg){ 
                  var getBase = '<?=url('');?>'; 
                  
                  setTimeout(function () { window.location.href=getBase; }, 100);
                    
                  }else{ 
                    // $("#location").html('Not Available'); 
                  } 
                },
                error:function(err){
                 console.log("errorroo ");
                 console.log(err);
               } 
             });
      
   }
   function clearPinCode(){
      var searchvalue= '';
      
        $.ajax({ 
        type:'get',  
        url: "{{url('/change-pincode')}}",
        data:'pincodesearch='+searchvalue, 

        success:function(msg){
        console.log(msg);
         if(msg){ 
                  var getBase = '<?=url('');?>'; 
                  
                  setTimeout(function () { window.location.href=getBase; }, 100);
                    
                  }else{ 
                    // $("#location").html('Not Available'); 
                  } 
                },
                error:function(err){
                 console.log("errorroo ");
                 console.log(err);
               } 
             });
      
   }
     /*added code search form submit end*/


   </script>




