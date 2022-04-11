
@extends('website.layouts.app')
@section('content')


@if(session()->has('msg'))
    <div class="alert alert-success" style="text-align:center;">
        {{ session()->get('msg') }}
    </div>
    @endif
	<!-- Slider -->
	<section class="carousel-slider-main text-center border-top border-bottom bg-white">
        <div class="owl-carousel owl-carousel-slider">
        	@foreach($banner as $banners)
        	<div class="item">
               	<a href="{{ route('all-products') }}">
               		<img class="img-fluid" src="{{ url($banners->banner_image) }}" alt="First slide">
               	</a>
            </div>
            @endforeach

          
        </div>
  	</section>
	<!-- End Slider -->
	<?php //dd($topproductarraydetail);?> 
	@if(isset($topproductarraydetail))
   @foreach($topproductarraydetail as $k1=>$v1)
  	<section class="product-items-slider section-padding">
        <div class="container">
            <div class="section-header">
               <h5 class="heading-design-h5">{{ucfirst($k1)}}<span class="badge badge-primary"></span>
                  <a class="float-right text-secondary" href="{{ route('all-products') }}">View All</a>
               </h5>
            </div>
            <?php $i=0;
                 // dd($topproductarraydetail);
            	?>
         
            <div class="owl-carousel owl-carousel-featured">
            	{{--@foreach($products as $product)--}}
            	<?php $i=0;
                  
            	?>
            	
            	<!--  <h2 class="catext_name">{{$k1}}</h2> -->
            	@foreach($v1 as $k2=>$v2)

            	<?php $i++;
            	
                // if($i < 6){

            	?>
            	                
            	              		<div class="item">

                  	<div class="product pext">
	                    <a href="{{url('/product-detail/'.$v2->pid)}}">
	                        <div class="product-header">
	                           	<span class="badge badge-success">{{ @if(isset($v2->discount_percentage)) round($v2->discount_percentage)  @endif }} % OFF</span>
	                           	<img class="img-fluid" src="{{ url($v2->pimg) }}" alt="">
	                           	<span class="veg text-success mdi mdi-circle"></span>
	                        </div>

	                        <div class="product-body">
	                           <h5>{{$v2->product_name}}</h5>
	                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - {{$v2->quantity}} {{$v2->unit}}</h6>
	                        </div>
	                    </a>

	                        <div class="product-footer">
	                           	<button type="button" class="btn btn-secondary btn-sm float-right" onclick="addtocart('<?php echo $v2->pid; ?>')">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>
	                           	<?php 
                                 $price_after_discount=$v2->mrp*((100 -$v2->discount_percentage)/100);
                                 $discountprice=$v2->mrp*$v2->discount_percentage/100;

                                 ?>
	                           	<p class="offer-price mb-0">${{$price_after_discount}} 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price">${{$discountprice}}</span>
	                           	</p>
	                        </div>
	                    
                  	</div>
               	</div>
               <?php //} ?>

               	@endforeach
               	@endif
               
               
            </div>
        </div>
  	</section>
  		@endforeach

  	<section class="offer-product">
        <div class="container">
            <div class="row no-gutters">
               	<div class="col-md-6">
                  	<a href="#">
                  		<img class="img-fluid" src="{{ url('assets/website/img/ad/1.jpg')}}" alt="">
                  	</a>
               	</div>
               	<div class="col-md-6">
                  	<a href="#">
                  		<img class="img-fluid" src="{{ url('assets/website/img/ad/2.jpg')}}" alt="">
                  	</a>
               	</div>
            </div>
        </div>
  	</section>

  	<section class="product-items-slider section-padding">
        <div class="container">
            <div class="section-header">
               	<h5 class="heading-design-h5">Best Offers View 
               		<span class="badge badge-info">20% OFF</span>
                  	<a class="float-right text-secondary" href="{{ route('all-products') }}">View All</a>
               	</h5>
            </div>

            <div class="owl-carousel owl-carousel-featured">
            	@foreach($products as $product)
               	<div class="item">

                  	<div class="product extt">
	                    <a href="{{url('/product-detail/'.$product->product_id)}}">
	                        <div class="product-header">
	                           	<span class="badge badge-success">{{round($product->discount_percentage)}}% OFF</span>
	                           	<img class="img-fluid" src="{{ url($product->product_image) }}" alt="">
	                           	<span class="veg text-success mdi mdi-circle"></span>
	                        </div>

	                        <div class="product-body">
	                           	<h5>{{$product->product_name}}</h5>
	                           	<h6>
	                           		<strong>
	                           			<span class="mdi mdi-approval"></span> Available in
	                           		</strong> - {{$product->quantity}} {{$product->unit}}
	                           	</h6>
	                        </div>
                           </a>
	                        <div class="product-footer">
	                           	<button type="button" class="btn btn-secondary btn-sm float-right"  onclick="addtocart('<?php echo $product->product_id; ?>')">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>
                                 <?php 
                                 $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
                                 $discountprice=$product->mrp*$product->discount_percentage/100;

                                 ?>
	                           	<p class="offer-price mb-0">${{$price_after_discount}} 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price">${{$product->mrp}}</span>
	                           	</p>
	                        </div>
	                    
                  	</div>
               	</div>
               	@endforeach

               	<!-- <div class="item">
                  	<div class="product">
	                    <a href="single-product.php">
	                        <div class="product-header">
	                           	<span class="badge badge-success">50% OFF</span>
	                           	<img class="img-fluid" src="{{ url('assets/website/img/item/7.jpg') }}" alt="">
	                           	<span class="veg text-success mdi mdi-circle"></span>
	                        </div>

	                        <div class="product-body">
	                           	<h5>Product Title Here</h5>
	                           	<h6>
	                           		<strong>
	                           			<span class="mdi mdi-approval"></span> Available in
	                           		</strong> - 500 gm
	                           	</h6>
	                        </div>

	                        <div class="product-footer">
	                           	<button type="button" class="btn btn-secondary btn-sm float-right">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>

	                           	<p class="offer-price mb-0">$450.99 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price">$800.99</span>
	                           	</p>
	                        </div>
	                    </a>
                  	</div>
               	</div>   

               	<div class="item">
                  	<div class="product">
	                    <a href="single-product.php">
	                        <div class="product-header">
	                           	<span class="badge badge-success">50% OFF</span>
	                           	<img class="img-fluid" src="{{ url('assets/website/img/item/7.jpg') }}" alt="">
	                           	<span class="veg text-success mdi mdi-circle"></span>
	                        </div>

	                        <div class="product-body">
	                           	<h5>Product Title Here</h5>
	                           	<h6>
	                           		<strong>
	                           			<span class="mdi mdi-approval"></span> Available in
	                           		</strong> - 500 gm
	                           	</h6>
	                        </div>

	                        <div class="product-footer">
	                           	<button type="button" class="btn btn-secondary btn-sm float-right">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>

	                           	<p class="offer-price mb-0">$450.99 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price">$800.99</span>
	                           	</p>
	                        </div>
	                    </a>
                  	</div>
               	</div>               	

               	<div class="item">
                  	<div class="product">
	                    <a href="single-product.php">
	                        <div class="product-header">
	                           	<span class="badge badge-success">50% OFF</span>
	                           	<img class="img-fluid" src="{{ url('assets/website/img/item/7.jpg') }}" alt="">
	                           	<span class="veg text-success mdi mdi-circle"></span>
	                        </div>

	                        <div class="product-body">
	                           	<h5>Product Title Here</h5>
	                           	<h6>
	                           		<strong>
	                           			<span class="mdi mdi-approval"></span> Available in
	                           		</strong> - 500 gm
	                           	</h6>
	                        </div>

	                        <div class="product-footer">
	                           	<button type="button" class="btn btn-secondary btn-sm float-right">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>

	                           	<p class="offer-price mb-0">$450.99 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price">$800.99</span>
	                           	</p>
	                        </div>
	                    </a>
                  	</div>
               	</div>

               	<div class="item">
                  	<div class="product">
	                    <a href="single-product.php">
	                        <div class="product-header">
	                           	<span class="badge badge-success">50% OFF</span>
	                           	<img class="img-fluid" src="{{ url('assets/website/img/item/7.jpg') }}" alt="">
	                           	<span class="veg text-success mdi mdi-circle"></span>
	                        </div>

	                        <div class="product-body">
	                           	<h5>Product Title Here</h5>
	                           	<h6>
	                           		<strong>
	                           			<span class="mdi mdi-approval"></span> Available in
	                           		</strong> - 500 gm
	                           	</h6>
	                        </div>

	                        <div class="product-footer">
	                           	<button type="button" class="btn btn-secondary btn-sm float-right">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>

	                           	<p class="offer-price mb-0">$450.99 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price">$800.99</span>
	                           	</p>
	                        </div>
	                    </a>
                  	</div>
               	</div>

               	<div class="item">
                  	<div class="product">
	                    <a href="single-product.php">
	                        <div class="product-header">
	                           	<span class="badge badge-success">50% OFF</span>
	                           	<img class="img-fluid" src="{{ url('assets/website/img/item/7.jpg') }}" alt="">
	                           	<span class="veg text-success mdi mdi-circle"></span>
	                        </div>

	                        <div class="product-body">
	                           	<h5>Product Title Here</h5>
	                           	<h6>
	                           		<strong>
	                           			<span class="mdi mdi-approval"></span> Available in
	                           		</strong> - 500 gm
	                           	</h6>
	                        </div>

	                        <div class="product-footer">
	                           	<button type="button" class="btn btn-secondary btn-sm float-right">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>

	                           	<p class="offer-price mb-0">$450.99 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price">$800.99</span>
	                           	</p>
	                        </div>
	                    </a>
                  	</div>
               	</div> -->
            </div>
        </div>
  	</section>

  	<section class="shop-list pt-3">
        <div class="container">
            <div class="section-header">
               	<h5 class="heading-design-h5">Categories <span class="badge badge-primary"></span>
                  	<a class="float-right text-secondary" href="{{url('/all-products')}}">View All</a>
               	</h5>
            </div>

            <div class="row">
               	<div class="col-md-12">
	               	<div class="category catext">
		                <div id="accordion">

		                	@foreach($categories as $cat) 
		                	
		                   	<div class="card">
		                     	<div class="card-header p-0" id="headingOne">
			                        <h5 class="mb-0">
			                          	<button class="btn btn-link p-0 w-100" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				                           	<div class="row no-gutters align-items-center">
				                              	<div class="col-md-2 col-3 p-2 text-left">
				                                 	<img src="{{ url($cat->image) }}" class="img-fluid max-width-100">
				                              	</div>

				                              	<div class="col-7 pl-1 text-left">
				                                 	<span class="category-offer">Upto 80% Off</span>
				                                 	<h6 class="category-name">{{$cat->title}}</h6>
				                                 	<!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> -->
				                              	</div>

				                              	<div class="col-2 pr-1 text-right">
				                                 	<span class="mdi mdi-chevron-down"></span>
				                              	</div>
				                           	</div>
			                          	</button>
			                        </h5>
		                     	</div>

			                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
			                        <div class="card-body p-2">
			                         	<div class="row no-gutters">

			                         	@if(isset($catproductdetails[$cat->cat_id]))
			                         		@foreach($catproductdetails[$cat->cat_id] as $k1=>$v1)

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url($v1->product_image) }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto {{round($v1->discount_percentage,2)}}% Off</p>
				                              		<h6 class="category-name">{{ucfirst($v1->product_name)}}</h6>
				                               	</a>
				                           	</div>
				                           	@endforeach
				                          @endif

				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div> -->

				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div> -->

				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>
 -->
				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div> -->

				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div> -->

				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div> -->

				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div> -->

				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div> -->

				                           	<!-- <div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div> -->
			                         	</div>
			                        </div>
			                    </div>
		                   	</div>
		                   	@endforeach

		                   <!-- 	<div class="card">
			                    <div class="card-header p-0" id="headingTwo">
			                        <h5 class="mb-0">
			                          	<button class="btn btn-link p-0 w-100 collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
				                          	<div class="row no-gutters align-items-center">
				                              	<div class="col-md-2 col-3 p-2 text-left">
				                                 	<img src="{{ url('assets/website/img/item/2.jpg') }}" class="img-fluid max-width-100">
				                              	</div>

				                              	<div class="col-7 pl-1 text-left">
				                                 	<span class="category-offer">Upto 80% Off</span>
				                                 	<h6 class="category-name">Daily Veggies</h6>
				                                 	<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
				                              	</div>

				                              	<div class="col-2 pr-1 text-right">
				                                 	<span class="mdi mdi-chevron-down"></span>
				                              	</div>
				                           	</div>
			                          	</button>
			                        </h5>
			                    </div>

			                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
			                        <div class="card-body p-2">
			                         	<div class="row no-gutters">
				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>
			                         
				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>
			                         	</div>
			                        </div>
			                    </div>
		                   	</div>

		                    <div class="card">
			                    <div class="card-header p-0" id="headingThree">
			                        <h5 class="mb-0">
			                          	<button class="btn btn-link p-0 w-100 collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
				                          	<div class="row no-gutters align-items-center">
				                          		<div class="col-md-2 col-3 p-2 text-left">
				                             		<img src="{{ url('assets/website/img/item/3.jpg') }}" class="img-fluid max-width-100">
				                          		</div>

				                              	<div class="col-7 pl-1 text-left">
				                                 	<span class="category-offer">Upto 80% Off</span>
				                                 	<h6 class="category-name">DAILY Fruit</h6>
				                                 	<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
				                              	</div> 

				                              	<div class="col-2 pr-1 text-right">
				                                 	<span class="mdi mdi-chevron-down"></span>
				                              	</div>
				                           	</div>
			                          	</button>
			                        </h5>
			                    </div>

			                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
			                        <div class="card-body p-2">
				                        <div class="row no-gutters">
				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>
				                        </div>
				                    </div>
			                    </div>
		                   	</div>

		                    <div class="card">
			                    <div class="card-header p-0" id="headingThree">
			                        <h5 class="mb-0">
			                          	<button class="btn btn-link p-0 w-100 collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
				                          	<div class="row no-gutters align-items-center">
				                          		<div class="col-md-2 col-3 p-2 text-left">
				                             		<img src="{{ url('assets/website/img/item/3.jpg') }}" class="img-fluid max-width-100">
				                          		</div>

				                              	<div class="col-7 pl-1 text-left">
				                                 	<span class="category-offer">Upto 80% Off</span>
				                                 	<h6 class="category-name">DAILY Fruit</h6>
				                                 	<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
				                              	</div>

				                              	<div class="col-2 pr-1 text-right">
				                                 	<span class="mdi mdi-chevron-down"></span>
				                              	</div>
				                           	</div>
			                          	</button>
			                        </h5>
			                    </div>

			                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
			                        <div class="card-body p-2">
				                        <div class="row no-gutters">
				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>

				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href="{{ route('all-products') }}">
				                              		<img src="{{ url('assets/website/img/item/1.jpg') }}" class="img-fluid max-width-100 m-auto">
				                              		<p class="category-offer">Upto 80% Off</p>
				                              		<h6 class="category-name">Daily Morning</h6>
				                               	</a>
				                           	</div>
				                        </div>
				                    </div>
			                    </div>
		                   	</div> -->
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