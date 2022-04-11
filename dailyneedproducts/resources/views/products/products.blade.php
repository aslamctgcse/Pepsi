@extends('layouts.app1')



@section('content')

<style>
	.blinking{
    animation:blinkingText 1.2s infinite;
    font-size:16px;
}
@keyframes blinkingText{
    0%{     color: red;    }
    49%{    color: red; }
    60%{    color: transparent; }
    99%{    color:transparent;  }
    100%{   color: red;    }
}
.hidebutton{
	display:none;
}
</style>

<section class="shop-list section-padding">


	<div class="container">
		<!-- <a href="{{url('/products')}}" class="btn btn-success"><i class="fa fa-chevron-left">&nbsp;&nbsp;</i>Home</a> -->
          
  <?php   
 

  foreach($comboarray as $k1=>$v1){
  	$dispimg='';
  	$ins='';
  	$offerclass='';
           if($k1 == 'a'){
           	$combo='Offers';
           	$offerclass='offer-body';
           	$dispimg='none';
           	$ins='OFFER IS NOT VALID DAIRY PRODUCT AND NON VEG.';
           }
            if($k1 == 'b'){
           	$combo='Combo';
           }
            if($k1 == 'c'){
           	$combo='Others';
           }
           if($k1 == 'd'){
           	$combo='';
           }
           if($k1 == 'e'){
           	$combo='Daily Breakfast';
           }
           if($k1 == 'f'){
           	$combo='Daily Fresh Fruit';
           	$ins='*Kindly order by 11 PM for the next day morning from 9.30am to 11 am fresh home delivery.
           	';
           }
           if($k1 == 'g'){
           	$combo='Daily Veg';
           }
           if($k1 == 'h'){
           	$combo='Daily Poultry Farm';
           	$ins='For non- veg items, no replacement will be done.';
           }
            if($k1 == 'i'){
           	$combo='Others';
           }



  	?>   
		<h5 class="mb-3 offer-label">{{ucfirst($combo)}}</h5>  
		<p style="color:red;text-align:center;display: none;" class="blinkingss"><?php echo $ins; ?></p>

		<div class="row mb-3">

			<!--<div class="col-md-3 col-sm-4 col-6 mb-3">

				<div class="product p-2 azwarsalal">

					<a href="#">

						<div class="product-header">

							<div class="product-body">

								<h5>हार्वेस्ट ब्रेड</h5>

								<h5>Harvest Bread az</h5>

								<p class="offer-price mb-0">Discount of 3% on MRP <span></span>

								</p>

							</div>

							<img class="img-fluid product-img" src="img/product/1.png" alt="">

						</div>

						<div class="product-footer text-center">

							<button type="button" class="btn btn-secondary btn-sm"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>

						</div>

					</a>

				</div>

			</div>-->

			<!--added dynamic code for products beg-->

			<!--added dynamic code for products beg-->

			<?php 

         
              foreach($v1 as $k2=>$v2){

              	$productimage='img/logo.png';

              	if(!empty($v2->pimage)){

              	$productimage=$v2->pimage;

              }

            //for stock beg
              $showbutton="";
              if($v2->stock == 1){
              	$showbutton="";
              }
              if($v2->stock == 0){
              	$showbutton="hidebutton";
              }
            //for stock end
             

			?>

			<div class="col-md-3 col-sm-4 col-6 mb-3">

				<div class="product p-2 azwar ">

					<!-- <a href="javascript:void(0);"> -->
					
						<!-- <a href="{{url('/product-detail/'.$v2->id)}}"> -->
						<div class="product-header">

							<div class="product-body {{$offerclass}}">

								<!-- <h5>हार्वेस्ट ब्रेड</h5> -->

								<h5><?php echo $v2->pname; ?></h5>

								<!-- <p class="offer-price mb-0"> MRP <?php echo $v2->pprice; ?> <span></span>

								</p> -->
								<?php 
                             $regularprice='';
                             $fontsize='';

								if(!empty($v2->pdesc)){ 
                                  $regularprice='regular-price';
                                  $fontsize='14px';
									?>
								<p class="offer-price mb-0"> MRP<span  class="{{$regularprice}}" style="font-size:{{$fontsize}};"> <?php echo $v2->pdesc; ?></span> 
									<?php } ?>
                                  
									<span class="pprice offer-price mb-0 ">Price <span ><?php echo $v2->pprice; ?></span></span>
								

								</p>
								<p><span class="pprice offer-price mb-0 ">{{$v2->quantity}} {{$v2->unit}}</span></p>

							</div>

							

							<img class="img-fluid product-img" src="<?php  echo $productimage;?>" alt="">

						</div>
					<!--</a>-->

						<div class="product-footer text-center">

							<button type="button" class="btn btn-secondary btn-sm {{$showbutton}}" onclick="addtocart('<?php echo $v2->id; ?>')" style="display: {{$dispimg}};"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>
							@if($v2->stock == 0)
							<p style="color:red;"><b>Out of Stock</b></p>
							@endif

						</div>

					<!--</a>-->

				</div>

			</div>

		<?php } ?>

			<!--added dynamic code for products end-->

			<!--added dynamic code for products end-->

			



	</div>
<?php } ?>

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







	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>

	

 

 function addtocart(id){

 var fs=$('.cart-value').text();
 if(parseInt(fs) == 0){
 	           swal({
			    title: "",
			    text: "Product has been added to cart!",
			    timer: 1000,
			   
			    buttons: false
			  });
 	           $('.cart-value').text(parseInt(fs) + 1);
 }

 	var pidd=id;
 	//alert(pid);

 	

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
             $('.cart-value').text(data);

           
       

          }
  


        });

 }
 $('input.btn.btn-secondary.mb-2.btn-lg').click(function(){
 	$(this).css({ 'color': 'red', 'font-size': '150%' });

 })

	</script>

	@endsection