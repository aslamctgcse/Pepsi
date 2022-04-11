@extends('website.layouts.app')



@section('content')

<?php //dd('ghghj'); ?>

<section class="cart-page section-padding">

	<!-- <input type="hidden" name="cartcount" id="cartcount" value="Session::get('cartpids')" /> -->


         <div class="container">
          <a href="{{url('/products')}}" class="btn btn-success"><i class="fa fa-chevron-left">&nbsp;&nbsp;</i>Home</a>
          <br>
          <br>

            <div class="row">

               <div class="col-md-12">

                  <div class="card card-body cart-table">

                     <div class="table-responsive">

                        <table class="table cart_summary">

                           <thead>

                              <tr>

                                 <th class="cart_product">Product</th>

                                 <!-- <th>Description</th> -->

                                 <th>Avail.</th>

                                 <th>Unit price</th>

                                 <th>Qty</th>

                                 <th>Total</th>

                                 <th class="action"><i class="mdi mdi-delete-forever"></i></th>

                              </tr>

                           </thead>

                           <tbody>

                           	<?php 

                             $ordertotals=0;

                             $totalqty=0;

                           	foreach($products as $product){

                                //dd($product);
                              $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
                              $discountprice=$product->mrp*$product->discount_percentage/100;
                              ?>

                              <tr id="rp{{$product->pid}}">

                                 <td class="cart_product"><a href="#"><img class="img-fluid" src="{{$product->product_image}}" alt=""></a></td>

                                <!--  <td class="cart_description">

                                    <h5 class="product-name"><a href="#">{{--$product->pnmae--}}</a></h5>

                                   

                                 </td> -->

                                 <td class="availability in-stock"><span class="badge badge-success">In stock</span></td>

                                 <td class="price">₹ <span id="uprice{{$product->pid}}">{{$price_after_discount}}</span></td>

                                 <td class="qty">

                                    <div class="input-group">

                                       <span class="input-group-btn"><button disabled="disabled" class="btn btn-theme-round btn-number" type="button"><span onclick="manageqty('{{$product->pid}}','dec')">-</span></button></span>

                                       <input type="text" max="10" min="1" value="{{ $qty[$product->pid]}}" class="form-control border-form-control form-control-sm input-number" name="quant[1]" id="quant{{$product->pid}}">

                                       <span class="input-group-btn"><button class="btn btn-theme-round btn-number" type="button"><span onclick="manageqty('{{$product->pid}}','inc')">+</span></button>

                                       </span>

                                    </div>

                                 </td>

                                 <td class="price">₹<span id="pp{{$product->pid}}"> {{$price_after_discount * $qty[$product->pid] }}</span></td>

                                 <td class="action">

                                    <a class="btn btn-sm btn-danger rmv" data-original-title="Remove" href="javascript:void(0);" title="" data-placement="top" data-toggle="tooltip" onclick="removeproduct('{{$product->pid}}')"><i class="mdi mdi-close-circle-outline"></i></a>

                                 </td>

                              </tr>

                              

                          <?php 



                        $ordertotals+=$price_after_discount * $qty[$product->pid];

                        $totalqty+=$qty[$product->pid];

                      } ?>

                              

                           </tbody>

                           <tfoot>

                              <!-- <tr>

                                 <td colspan="1"></td>

                                 <td colspan="4">

                                    <form class="form-inline float-right">

                                       <div class="form-group"> -->

                                          <!-- <input type="text" placeholder="Enter discount code" class="form-control border-form-control form-control-sm"> -->

<!--                                        </div>

                                       &nbsp;
 -->
                                       <!-- <button class="btn btn-success float-left btn-sm" type="submit">Apply</button> -->

                                    <!-- </form>

                                 </td> -->

                                 <!-- <td colspan="2">Discount : $237.88 </td> -->

                              <!-- </tr> -->

                              <tr>

                                 <td colspan="2"></td>

                                 <td class="text-right" colspan="3">Total products (tax incl.)</td>

                                 <!-- <td colspan="2" class="tp" id="tp1">{{$ordertotals}} </td> -->

                                 <td colspan="2" >₹<span class="tp" id="tp1">{{$ordertotals}} </span></td>

                              </tr>

                              <tr>

                                 <td class="text-left" colspan="3"><strong style="color:red;">Order upto Rs.300 more free delivery</strong></td>
                                 <td class="text-right" colspan="2"><strong>Total</strong></td>

                                 <td class="text-danger" colspan="2"><strong>₹<span class="tp" id="tp2">{{$ordertotals}} </span></strong></td>

                              </tr>
                              <!--aded tr for instruction-->
                               <tr>

                                 <td class="text-center" colspan="7"><strong style="color:red;">The replacement will be done at the same time! as 'delivery. After that, no replacement will be done. So while taking your deliver kindly check the product and Quality*.</strong></td>

                                 

                              </tr>
                              <!--aded tr for instruction-->

                           </tfoot>

                        </table>

                     </div>

                     <a href="{{url('/checkout')}}"><button class="btn btn-secondary btn-lg btn-block text-left" type="button"><span class="float-left"><i class="mdi mdi-cart-outline"></i> Proceed to Checkout </span><span class="float-right"><strong>₹<span class="tp" id="tp3">{{$ordertotals}}</span></strong> <span class="mdi mdi-chevron-right"></span></span></button></a>

                  </div>

                  <div class="card mt-2" style="display:none;">

                     <h5 class="card-header">My Cart (Design Two)<span class="text-secondary float-right">(5 item)</span></h5>

                     <div class="card-body pt-0 pr-0 pl-0 pb-0">

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

                           <img class="img-fluid" src="img/item/1.jpg" alt="">

                           <span class="badge badge-success">50% OFF</span>

                           <h5><a href="#">Product Title Here</a></h5>

                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>

                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>

                        </div>

                        <div class="cart-list-product">

                           <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>

                           <img class="img-fluid" src="img/item/2.jpg" alt="">

                           <span class="badge badge-success">50% OFF</span>

                           <h5><a href="#">Product Title Here</a></h5>

                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>

                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>

                        </div>

                     </div>

                     <div class="card-footer cart-sidebar-footer">

                        <div class="cart-store-details">

                           <p>Sub Total <strong class="float-right">$900.69</strong></p>

                           <p>Delivery Charges <strong class="float-right text-danger">+ $29.69</strong></p>

                           <h6>Your total savings <strong class="float-right text-danger">$55 (42.31%)</strong></h6>

                        </div>

                        <a href="{{url('/cart')}}"><button class="btn btn-secondary btn-lg btn-block text-left" type="button"><span class="float-left"><i class="mdi mdi-cart-outline"></i> Proceed to Checkout </span><span class="float-right"><strong>$1200.69</strong> <span class="mdi mdi-chevron-right"></span></span></button></a>

                     </div>

                  </div>

               </div>

            </div>

         </div>

      </section>

<section class="section-padding bg-white border-top">

  <div class="container">

    <div class="row">

      <div class="col-lg-4 col-sm-6">

        <div class="feature-box"> <i class="mdi mdi-truck-fast"></i>

          <h6>Free & Next Day Delivery</h6>

          <p>Lorem ipsum dolor sit amet, cons...</p>

        </div>

      </div>

      <div class="col-lg-4 col-sm-6">

        <div class="feature-box"> <i class="mdi mdi-basket"></i>

          <h6>100% Satisfaction Guarantee</h6>

          <p>Rorem Ipsum Dolor sit amet, cons...</p>

        </div>

      </div>

      <div class="col-lg-4 col-sm-6">

        <div class="feature-box"> <i class="mdi mdi-tag-heart"></i>

          <h6>Great Daily Deals Discount</h6>

          <p>Sorem Ipsum Dolor sit amet, Cons...</p>

        </div>

      </div>

    </div>

  </div>

</section>



<?php //include( 'footer.php') ?>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">-->
  

<script>

	// jQuery.noConflict();

 //        jQuery(document).ready(function(){

 // alert('hifdvfd');

 // jQuery('.cart-value').text('{{$totalcartqty}}');

 //  });



 function removeproduct(rpid){

   

   //alert(rpid);



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

     //alert();

     $('.cart-value').text(parseInt(cartcurrent) - 1);



   //alert(pidd);

   $.ajax({



          type: 'get',



         // url: 'http://localhost/blog/public/addtocart',remove-product

           url:"{{url('/remove-product')}}/"+rpidd,



          //data: {'pid':pidd},  



          success : function(data){



            // $('#category'+value).remove()

             $('#rp'+rpidd).remove();
             var tp= $('#tp1').text();
             if(parseInt(tp) == 0 ){
              //alert('yes');
            $('.cart-table').remove();
            location.reload(true); //reload

            }



             console.log(data);

             //alert(data);

            // alert('Product has been removed to cart');



          }



        });

   //added code to remove product end



 }



 //added code to manage inc/dec qty beg

 function manageqty(id,type){

   //alert(type);
   var quant=$('#quant'+id).val();
   //alert(quant);


   var pidd=id;

   var type=type;

  var prevqty=$('#quant'+id).val();

   //alert(prevqty);

   if(type == 'inc') {

  var quant= $('#quant'+id).val(parseInt(prevqty) + 1);

 var getquant=$('#quant'+id).val();

  var uprice = $('#uprice'+id).text();
  //alert(uprice);

  //alert(quant);

  var newsubtotal= parseFloat(getquant) * parseFloat(uprice);

 // alert(parseInt(newsubtotal))

  var subtotal = $('#pp'+id).text(newsubtotal);

   var prevtotal=$('#tp1').text();

   //alert(parseInt(prevtotal));

$('.tp').text(parseFloat(parseFloat(prevtotal) + parseFloat(uprice)));

}

 if(type == 'dec' && quant >= 2) {

  var quant= $('#quant'+id).val(parseInt(prevqty) - 1);

  var getquant=$('#quant'+id).val();

  var uprice = $('#uprice'+id).text();

  //alert(quant);

  var newsubtotal= parseFloat(getquant) * parseFloat(uprice);

  //alert(parseInt(newsubtotal));

  var subtotal = $('#pp'+id).text(newsubtotal);

  var prevtotal=$('#tp1').text();

$('.tp').text(parseFloat(parseFloat(prevtotal) - parseFloat(uprice)));

}



   /////////////////////////////////inc dec beg

   $.ajax({



          type: 'get',



         // url: 'http://localhost/blog/public/addtocart',

           url:"{{url('/addtocart')}}/"+pidd+'/'+type,



          //data: {'pid':pidd},  



          success : function(data){



            // $('#category'+value).remove()



             //console.log(data);

             //alert(data);

             //alert('Product qty has been increased cart');



          }



        });



   ///////////////////////////////////// inc dec end

 }

 //added code to manage inc/dec qty end

 

	</script>

	@endsection