
@extends('website.layouts.app')
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


<style>
span.select2-search.select2-search--dropdown {
    display: none !important;
}

.hm .owl-item {
    width: 305px !important;
}
.stockbtn{
		background: #778be0 none repeat scroll 0 0;
		border: 1px solid #778be0;
		margin: 2px;
		height: 24px;
		font-size:14px;
		min-width: 95px;
	}
.noResult {
    font-size: 20px;
    font-weight: bold;
    width: 80%;
    margin: auto;
    height: 100px;
    align-items: center;
    display: flex;
    justify-content: center;
    line-height: 40px;
    text-align: center;
  }  	
 </style>

@php 
//dd($cat);//title
@endphp
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
               	<!-- <a href="{{ route('all-products') }}"> -->
               	<a href="@if(isset($banners->category_id)){{ url('all-products?id='.$banners->category_id) }} @else {{route('all-products')}} @endif">	
               		<!-- <img class="img-fluid" src="@if(file_exists($banners->banner_image)){{ url($banners->banner_image) }} @else {{url('/assets/website/img/sailogo.png')}} @endif" alt="First slide"> -->
               		<img class="img-fluid" src="@if(!empty($banners->banner_image)){{ url('/admin-panel/'.$banners->banner_image) }} @else {{url('/assets/website/img/sailogo.png')}} @endif" alt="First slide">
               	</a>
            </div>
            @endforeach

          
        </div>
  	</section>
	<!-- End Slider  image not required-->
	
	<!--adde code for combo anf offers beg new chnges in -->
	@if(isset($cat_offer))
	@foreach($cat_offer as $cats) 
     @if(isset($cats->subcategory) && count($cats->subcategory) > 0)
  	<section class="product-items-slider py-2 hm ">
  		
        <div class="card px-sm-5 px-2">
            <div class="section-header">
             
               <h5 class="heading-design-h5"><a href="@if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif" class="text-secondary">{{$cats->title}}<span class="badge badge-primary"></span></a>
               	 @if(count($cats->subcategory) > 5)
                  <a class="float-right text-secondary" href=" @if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif">View All</a>
               </h5>
               @endif
            </div>
            
         
            <div class="owl-carousel owl-carousel-featured">
             @foreach($cats->subcategory as $subcategory)
            	  <div class="item">

                  	<div class="product pext" id="productid">
	                    <a href="@if(isset($subcategory->cat_id)) {{url('/all-products?id='.$subcategory->cat_id)}} @endif">
	                        <div class="product-header">
	                        	
	                           	<span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                           	<img class="img-fluid" src=" @if(!empty($subcategory->image) && $subcategory->image != 'N/A') {{ url('/admin-panel/'.$subcategory->image)}} @else {{url('/assets/website/img/sailogo.png')}} @endif">
	                           	<span class="veg text-success mdi mdi-circle" style="display:none;"></span>
	                        </div>

	                        <div class="product-body" style="">
	                           <h5 style="text-align: center;">{{$subcategory->title}}</h5>
	                           <h6 style="display: none;"><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="">qty unit</span></h6>
	                           
	                           
	                        </div>
	                    </a>

	                        <div class="product-footer" style="display:none;">
	                         
	                           	<!--added for varient beg-->
	                           <div class="varient_product" style="display:none;">
	                           
	                          
	                           	<span class="varient" id="varient"><button id="varient_id" class="btn btn-info" onclick="getvarient('','')"></button></span>
	                           
	                           </div>
	                          
	                           <!--added for varient end -->
	                           	<button type="button" class="btn btn-secondary btn-sm float-right" onclick="addtocart('')" id="addtocart">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>
	                           	
	                           	<p class="offer-price mb-0" >Rs<span  id="variant_price"></span> 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price" id="product_mrp">Rs</span>
	                           	</p>
	                        </div>
	                    
                  	</div>
               	</div>
               @endforeach

               
              
               
               
            </div>
        </div>
      
  	</section>
  	@endif
  	@endforeach
  	@endif
  		

	<!--adde code for combo anf offers end-->
	<!--added code for combo beg-->
	@if(isset($cat_combo))
	@foreach($cat_combo as $cats) 
     @if(isset($cats->subcategory) && count($cats->subcategory) > 0)
  	<section class="product-items-slider py-2 hm">
  		
        <div class="card px-sm-5 px-2">
            <div class="section-header">
            	
               <h5 class="heading-design-h5"><a href="@if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif" class="text-secondary">{{$cats->title}}<span class="badge badge-primary"></span></a>
               	@if(count($cats->subcategory) > 5)
                  <a class="float-right text-secondary" href="@if(isset($cats->cat_id)) {{ url('all-products?id='.$cats->cat_id) }} @endif">View All</a>
               </h5>
               @endif
            </div>
            
         
            <div class="owl-carousel owl-carousel-featured">
             @foreach($cats->subcategory as $subcategory)
            	  <div class="item">

                  	<div class="product pext" id="productid">
	                    <a href=" @if(isset($subcategory->cat_id))  {{url('/all-products?id='.$subcategory->cat_id)}} @endif">
	                        <div class="product-header">
	                        	
	                           	<span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                           	<img class="img-fluid" src=" @if(!empty($subcategory->image) && $subcategory->image != 'N/A') {{ url('/admin-panel/'.$subcategory->image)}} @else {{url('/assets/website/img/sailogo.png')}} @endif">
	                           	<span class="veg text-success mdi mdi-circle" style="display:none;"></span>
	                        </div>

	                        <div class="product-body" style="">
	                           <h5 style="text-align: center;">{{$subcategory->title}}</h5>
	                           <h6 style="display: none;"><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="">qty unit</span></h6>
	                           
	                           
	                        </div>
	                    </a>

	                        <div class="product-footer" style="display:none;">
	                         
	                           	<!--added for varient beg-->
	                           <div class="varient_product" style="display:none;">
	                           
	                          
	                           	<span class="varient" id="varient"><button id="varient_id" class="btn btn-info" onclick="getvarient('','')"></button></span>
	                           
	                           </div>
	                          
	                           <!--added for varient end -->
	                           	<button type="button" class="btn btn-secondary btn-sm float-right" onclick="addtocart('')" id="addtocart">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>
	                           	
	                           	<p class="offer-price mb-0" >Rs<span  id="variant_price"></span> 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price" id="product_mrp">Rs</span>
	                           	</p>
	                        </div>
	                    
                  	</div>
               	</div>
               @endforeach
               @endif

               
              
               
               
            </div>
        </div>
      
  	</section>
  	
  	@endforeach
  	@endif
	<!--added code for combo end-->
	<!-- added code to separate daily fruits and daily veggies beg -->

	@php
	$i=0;
	@endphp
	<div class="row no-gutters">
		<div class="col-md-6">
			<!--added code for daily fruit beg 18jan 2021-->
	@if(isset($cat_daily_fruit))
	@foreach($cat_daily_fruit as $cats) 
	  @if(isset($cats->subcategory) && count($cats->subcategory) > 0)
	<section class="product-items-slider py-2 hm">
	    
	     <div class="card px-sm-5 px-2 mr-2">
	         <div class="section-header">
	            
	            <h5 class="heading-design-h5"><a href="@if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif" class="text-secondary">{{$cats->title}}<span class="badge badge-primary"></span></a>
	                @if(count($cats->subcategory) > 5)
	               <a class="float-right text-secondary" href="@if(isset($cats->cat_id)) {{ url('all-products?id='.$cats->cat_id) }} @endif">View All</a>
	            </h5>
	            @endif
	         </div>
	         
	      
	         <div class="row">
	          @foreach($cats->subcategory as $subcategory)
	          	@if($i < 2)
	              <div class="item col-md-6">

	                <div class="product pext" id="productid">
	                    <a href=" @if(isset($subcategory->cat_id))  {{url('/all-products?id='.$subcategory->cat_id)}} @endif">
	                        <div class="product-header">
	                            
	                            <span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                            <img class="img-fluid" src=" @if(!empty($subcategory->image) && $subcategory->image != 'N/A') {{ url('/admin-panel/'.$subcategory->image)}} @else {{url('/assets/website/img/sailogo.png')}} @endif">
	                            <span class="veg text-success mdi mdi-circle" style="display:none;"></span>
	                        </div>

	                        <div class="product-body" style="">
	                           <h5 style="text-align: center;">{{$subcategory->title}}</h5>
	                           <h6 style="display: none;"><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="">qty unit</span></h6>
	                           
	                           
	                        </div>
	                    </a>

	                        <div class="product-footer" style="display:none;">
	                         
	                            <!--added for varient beg-->
	                           <div class="varient_product" style="display:none;">
	                           
	                          
	                            <span class="varient" id="varient"><button id="varient_id" class="btn btn-info" onclick="getvarient('','')"></button></span>
	                           
	                           </div>
	                          
	                           <!--added for varient end -->
	                            <button type="button" class="btn btn-secondary btn-sm float-right" onclick="addtocart('')" id="addtocart">
	                                <i class="mdi mdi-cart-outline"></i> Add To Cart
	                            </button>
	                            
	                            <p class="offer-price mb-0" >Rs<span  id="variant_price"></span> 
	                                <i class="mdi mdi-tag-outline"></i><br>
	                                <span class="regular-price" id="product_mrp">Rs</span>
	                            </p>
	                        </div>
	                    
	                </div>
	                </div>
	                @endif
	                @php
	                $i++;
	                @endphp
	            @endforeach
	            @endif

	            
	           
	            
	            
	         </div>
	     </div>
	   
	</section>
	
	@endforeach
	@endif
	<!--added code for daily fruit end-->
		</div>
		<div class="col-md-6">
			<!--added code for daily veggies beg-->
	@php
	$i=0;
	@endphp
	@if(isset($cat_daily_Veggies))
	@foreach($cat_daily_Veggies as $cats) 
	  @if(isset($cats->subcategory) && count($cats->subcategory) > 0)
	<section class="product-items-slider py-2 hm">
	    
	     <div class="card px-sm-5 px-2 ml-2">
	         <div class="section-header">
	            
	            <h5 class="heading-design-h5"><a href="@if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif" class="text-secondary">{{$cats->title}}<span class="badge badge-primary"></span></a>
	                @if(count($cats->subcategory) > 5)
	               <a class="float-right text-secondary" href="@if(isset($cats->cat_id)) {{ url('all-products?id='.$cats->cat_id) }} @endif">View All</a>
	            </h5>
	            @endif
	         </div>
	         
	      
	         <div class="row">
	          @foreach($cats->subcategory as $subcategory)
	              @if($i < 2)
	              <div class="item col-md-6">

	                <div class="product pext" id="productid">
	                    <a href=" @if(isset($subcategory->cat_id))  {{url('/all-products?id='.$subcategory->cat_id)}} @endif">
	                        <div class="product-header">
	                            
	                            <span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                            <img class="img-fluid" src=" @if(!empty($subcategory->image) && $subcategory->image != 'N/A') {{ url('/admin-panel/'.$subcategory->image)}} @else {{url('/assets/website/img/sailogo.png')}} @endif">
	                            <span class="veg text-success mdi mdi-circle" style="display:none;"></span>
	                        </div>

	                        <div class="product-body" style="">
	                           <h5 style="text-align: center;">{{$subcategory->title}}</h5>
	                           <h6 style="display: none;"><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="">qty unit</span></h6>
	                           
	                           
	                        </div>
	                    </a>

	                        <div class="product-footer" style="display:none;">
	                         
	                            <!--added for varient beg-->
	                           <div class="varient_product" style="display:none;">
	                           
	                          
	                            <span class="varient" id="varient"><button id="varient_id" class="btn btn-info" onclick="getvarient('','')"></button></span>
	                           
	                           </div>
	                          
	                           <!--added for varient end -->
	                            <button type="button" class="btn btn-secondary btn-sm float-right" onclick="addtocart('')" id="addtocart">
	                                <i class="mdi mdi-cart-outline"></i> Add To Cart
	                            </button>
	                            
	                            <p class="offer-price mb-0" >Rs<span  id="variant_price"></span> 
	                                <i class="mdi mdi-tag-outline"></i><br>
	                                <span class="regular-price" id="product_mrp">Rs</span>
	                            </p>
	                        </div>
	                    
	                </div>
	                </div>
	                @endif
	               @php
	               $i++;
	               @endphp
	            @endforeach
	            @endif

	            
	           
	            
	            
	         </div>
	     </div>
	   
	</section>
	
	@endforeach
	@endif
	<!--added code for daily veggies end-->
		</div>
	</div>

	   <!-- Store Listing -->  
  
 @if(isset($storeList) && count($storeList)>0)
 
  	<section class="product-items-slider py-2 hm" >

        <div class="card px-sm-5 px-2">
            <div class="section-header">
              
               <h5 class="heading-design-h5"><a href="url('all-products?storeId=all')" class="text-secondary">Stores<span class="badge badge-primary"></span></a>
               	 @if(count($storeList) > 5)
                  <a class="float-right text-secondary" href="url('all-products?storeId=all')">View All</a>
               @endif
               </h5>
            </div>
            
          
            <div class="owl-carousel owl-carousel-featured">
             @foreach($storeList as $storeLists)
            	  <div class="item">

                  	<div class="product pext" id="productid">
	                    <a href=" @if(isset($storeLists->store_id))  {{url('/all-products?storeId='.$storeLists->store_id)}} @endif">
	                        <div class="product-header">
	                        	
	                           	<span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                   <img class="img-fluid" src="@if(!empty($storeLists->store_image) && $storeLists->store_image != 'N/A') {{url('/admin-panel/'.$storeLists->store_image)}} @else {{url('/assets/website/img/sailogo.png')}} @endif ">
	                           	<span class="veg text-success mdi mdi-circle" style="display:none;"></span>
	                        </div>

	                        <div class="product-body product-body-category" style="">
	                           <h5 style="text-align: center;">{{$storeLists->store_name}}</h5>
	                           <h6 style="display: none;"><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="">qty unit</span></h6>
	                           
	                           
	                        </div>
	                    </a>
	                    
                  	</div>
               	</div>
               @endforeach
 
            </div>
        </div>
  	</section>
  	@endif

   <!-- End of Store listing -->
	

	<!-- added code to separate daily fruits and daily veggies end -->
	
  @foreach($cat as $cats) 
 
 
  	<section class="product-items-slider py-2 hm">
  		
        <div class="card px-sm-5 px-2">
            <div class="section-header">
              
               <h5 class="heading-design-h5"><a href="@if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif" class="text-secondary">{{$cats->title}}<span class="badge badge-primary"></span></a>
               	 @if(count($cats->subcategory) > 5)
                  <a class="float-right text-secondary" href=" @if(isset($cats->cat_id)) {{ url('all-products?id='.$cats->cat_id) }} @endif ">View All</a>
                 @endif 
               </h5>
               
            </div>
            
            @if(isset($cats->subcategory) && count($cats->subcategory) > 0)
            <div class="owl-carousel owl-carousel-featured">
             @foreach($cats->subcategory as $subcategory)
            	  <div class="item">

                  	<div class="product pext" id="productid">
	                    <a href=" @if(isset($subcategory->cat_id))  {{url('/all-products?id='.$subcategory->cat_id)}} @endif">
	                        <div class="product-header">
	                        	
	                           	<span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                   <img class="img-fluid" src="@if(!empty($subcategory->image) && $subcategory->image != 'N/A') {{url('/admin-panel/'.$subcategory->image)}} @else {{url('/assets/website/img/sailogo.png')}} @endif ">
	                           	<span class="veg text-success mdi mdi-circle" style="display:none;"></span>
	                        </div>

	                        <div class="product-body product-body-category" style="">
	                           <h5 style="text-align: center;">{{$subcategory->title}}</h5>
	                           <h6 style="display: none;"><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="">qty unit</span></h6>
	                           
	                           
	                        </div>
	                    </a>

	                        <div class="product-footer" style="display:none;">
	                         
	                           <div class="varient_product" style="display:none;">
	                           
	                          
	                           	<span class="varient" id="varient"><button id="varient_id" class="btn btn-info" onclick="getvarient('','')"></button></span>
	                           
	                           </div>
	                         
	                           	<button type="button" class="btn btn-secondary btn-sm float-right" onclick="addtocart('')" id="addtocart">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>
	                           	
	                           	<p class="offer-price mb-0" >Rs<span  id="variant_price"></span> 
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price" id="product_mrp">Rs</span>
	                           	</p>
	                        </div>
	                    
                  	</div>
               	</div>
               @endforeach
            </div>
            @else
            <div class="owl-carousel owl-carousel-featured">
            	  <div class="item">
                  	<div class="product pext" id="productid">
	                    <a href=" @if(isset($cats->cat_id))  {{url('/all-products?id='.$cats->cat_id)}} @endif">
	                        <div class="product-header">
	                        	
	                           	<span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                   <img class="img-fluid" src="@if(!empty($cats->image) && $cats->image != 'N/A') {{url('/admin-panel/'.$cats->image)}} @else {{url('/assets/website/img/sailogo.png')}} @endif ">
	                           	<span class="veg text-success mdi mdi-circle" style="display:none;"></span>
	                        </div>

	                        <div class="product-body product-body-category" style="">
	                           <h5 style="text-align: center;">{{$cats->title}}</h5>
	                           <h6 style="display: none;"><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="">qty unit</span></h6>
	                           
	                           
	                        </div>
	                    </a>	                    
                  	</div>
               	</div>
            </div>
            @endif

        </div>

  	</section>
  	
  	@endforeach
  		

    	<section class="product-items-slider py-2 hm">
        <div class="card px-sm-5 px-2">
            <div class="section-header">
               	<h5 class="heading-design-h5">Best Offers View
               		<!-- <span class="badge badge-info">20% OFF</span> -->
                  	<a class="float-right text-secondary" href="{{ url('/all-products?sortorder=discounthl') }}">View All</a>
               	</h5>
            </div>
            @if(count($products)<=0)
                <div>
                	<div class="noResult">No Best offer products found!</div>
                </div>


                @endif

            <div class="owl-carousel owl-carousel-featured">
            	
                
            	@foreach($products as $product)
            	<?php 
		                     		//if($v1->product_id == $v1->varient_id) {
		                     		$visibile=1;
		                     		$click_event='';
		                     		$stock_text='';
		                     		$qtyblockdisp='block';
		                     		$btndisabledNew='none';
		                             if(isset($product->stock) && $product->stock == 0 || empty($product->stock) || $product->status==0){
		                             	$visibile=0.5;
		                             	$stock_text='Out of Stock';
		                             	$qtyblockdisp='none';
		                             	$btndisabledNew='block';
		                             	$click_event='none';
		                             	$btndisabled='disabled';
		       
                                      }
                                      $qtyaddedtocart='';
                                      $btndisabled='';
                                      
                                      $addtocartdisp="block";
                                      $defaultqtydisp='block';
                                      if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$product->varient_id])){
                                      	$addtocartdisp="none";
						                $defaultqtydisp='none';

                                        $qtyaddedtocart=session()->get('footer_cart_qty')[$product->varient_id];
                                        if(!empty($qtyaddedtocart) && $qtyaddedtocart == $product->stock){
	                                    	$visibile=0.5;
	                                    	$qtyblockdisp='none';
			                             	$stock_text='Out of Stock';
			                             	$btndisabledNew='block';
			                             	$click_event='none';

                                        }
                                      }
                                       
		                                 //$price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
		                                 //$discountprice=$v[0]->mrp*$v[0]->discount_percentage/100;

		                                 #added new code to show mrp,price as it is n calculate discount from mrp n price beg
		                                 $price_after_discount=$product->price;
                                 		//$discountprice=$catproductrels_single->mrp*$catproductrels_single->discount_percentage/100;
                                 		$discount=(($product->mrp - $product->price)/$product->mrp)*100;
		                                 #added new code to show mrp,price as it is n calculate discount from mrp n price end

		                                
		                     		?>
                @if(isset($discount) && $discount > 0)
            
               	<div class="item">
               						{{-- hidden fields to get out of stock beg --}}
		                             	<input type="hidden" name="qtyadded" class="qtyadded{{$product->product_id}}" value="0">
		                             	<input type="hidden" name="productstock" value="{{$product->stock ?? 0}}" class="productstock{{$product->product_id}}">
		                             {{--  hidden fields to get out of stock beg --}}

                  	<div class="product extt stock{{$product->varient_id}}" id="productid{{$product->product_id}}"  style="opacity:{{$visibile}}; pointer-events:{{$click_event}}">
                  		<!-- <p class="stock" style="color:red; text-align: center;font-weight: bold;margin-left: 30px;">{{$stock_text}}</p>  -->
	                    <a href="{{url('/product-detail/'.$product->product_id)}}">
	                        <div class="product-header" style="opacity:{{$visibile}}; pointer-events:{{$click_event}}">
	                        	@if(isset($discount) && $discount > 0)
	                           	<span class="badge badge-success" id="product_discount{{$product->product_id}}">{{round($product->discount_percentage)}}% OFF</span>
	                           	@endif
                           	<img class="img-fluid" src="@if(!empty($product->product_image) && $product->product_image != 'N/A') {{ url('/admin-panel/'.$product->varient_image) }} @else {{url('/assets/website/img/sailogo.png')}} @endif" alt=""  id="varient_img{{$product->varient_id}}">
	                           <!-- 	<span class="veg text-success mdi mdi-circle"></span> -->
	                        </div>

	                        <div class="product-body">
	                           	<h5>{{$product->product_name}}</h5>
	                           <!-- 	<h6>
	                           		<strong>
	                           			<span class="mdi mdi-approval"></span> Available in
	                           		</strong> - <span id="availability{{$product->product_id}}">{{$product->quantity}} {{$product->unit}}</span>
	                           	</h6> -->
	                           	<!-- <p>Sold by {{$product->store_name}}</p>	 -->
	                        </div>
                           </a>
	                        <div class="product-footer">
	                        	 	<!--added for varient beg-->
	                           <!-- <div class="varient_product" style="">
	                           		<div class="form-group">
								    
								    	<select class="form-control variantchange selpid{{$product->product_id}}" onchange="getvarientdata('{{$product->varient_id}}','{{$product->product_id}}')">
				                           	@foreach($variantproductarary[$product->product_id] as $varkey=>$vardata)
				                           	<?php
				                           	 $bgcolor="btn btn-light";
			                                 if($vardata->varient_id == $product->varient_id){
			                                   $bgcolor="btn btn-success";
			                                 }

				                           	 ?>
	                           
	                           	
								     			 <option value="{{$vardata->varient_id}}" id="varient{{$product->product_id}}{{$vardata->varient_id}}">{{$vardata->quantity}}{{$vardata->unit}}</option>
								     
								   
	                   
	                           	 @endforeach
	                           	  </select>
							  	</div>
	                           </div> -->
	                          
	                           <!--added for varient end -->
	                       
	                          <button type="button" class="btn btn-secondary btn-sm float-right addtocart{{$product->varient_id}}"  onclick="addtocart('<?php echo $product->varient_id; ?>','<?php echo $product->product_id; ?>')" id="addtocart{{$product->varient_id}}" {{$btndisabled}} style="display:{{$addtocartdisp}};">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>
	                      
	                           	<p class="float-right"><span class="badge badge-success stockbtn stockbtn{{$product->varient_id}}" style="color:red;display:{{$btndisabledNew}}">Out of Stock</span></p>
				


								<!--added code to add a container to append cart qty of individual peoduct from json response beg-->
									<span class="append_qty_block{{$product->varient_id}}"  style="display:{{$qtyblockdisp}};">
									<!--added code to show qty block if item is added to cart beg-->
									<?php if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$product->varient_id])){
										//dd('jkhj');
									/*$inc='inc';
									$dec='dec'; */
									?>
										<p class="offer-price mb-0 quantity" style="float:right;">
											<span class="input-group-btn">
												<button disabled="disabled" class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;">
													<span onclick="cartupdate('{{$product->varient_id}}','dec')" id="dec{{$product->varient_id}}" class="dec{{$product->varient_id}}" style="font-size:28px">
												@if(session()->get('footer_cart_qty')[$product->varient_id]=='1')
												 <?php echo 'x';?>
												@else
												  <?php echo '-';?>
												@endif		
													
												    </span>
												</button>
											</span>
											<span>
												<input type="text" max="" min="" value="{{session()->get('footer_cart_qty')[$product->varient_id]}}"  name="quant[1]" class="quant{{$product->varient_id}}" style="width:30px;" readonly>
											</span>
											<span class="input-group-btn">
												<button class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;">
													<span onclick="cartupdate('{{$product->varient_id}}','inc')" id="'inc{{$product->varient_id}}" class="inc{{$product->varient_id}}">+</span>
												</button>

											</span>
										</p>
										<?php } ?>
									<!--added code to show qty block if item is added to cart end-->
									</span>


                                 <?php 
                                 //$price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
                                 $discountprice=$product->mrp*$product->discount_percentage/100;
                                 ?>
	                           	<p class="offer-price mb-0 pt-1">₹<span id="variant_price{{$product->varient_id}}">{{$price_after_discount}} </span>
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<!-- <span class="regular-price" id="product_mrp{{$product->varient_id}}">₹{{$product->mrp}}</span> -->
	                           	</p>
	                        </div>
	                    
                  	</div>
               	</div>
               	@else
               	
               	@endif


               	@endforeach

               	            </div>
        </div>
  	</section>

  	<section class="shop-list pt-3" style="display:none;">
        <div class="px-5">
            <div class="section-header">
               	<h5 class="heading-design-h5">Categories <span class="badge badge-primary"></span>
                  	<a class="float-right text-secondary" href="{{url('/all-products')}}">View All</a>
               	</h5>
            </div>

            <div class="row">
               	<div class="col-md-12">
	               	<div class="category catext">
		                <div id="accordion">
		                	<?php //dd($cat);?>
		                    @if(isset($cat))
		                	@foreach($cat as $cats) 

		                	@if(isset($cats->cat_id))
		                   	<div class="card">
		                     	<div class="card-header p-0" id="headingOne">
			                        <h5 class="mb-0">
			                          	<button class="btn btn-link p-0 w-100" data-toggle="collapse" data-target="#collapseOne{{$cats->cat_id}}" aria-expanded="true" aria-controls="collapseOne{{$cats->cat_id}}">
				                           	<div class="row no-gutters align-items-center">
				                              	<div class="col-md-2 col-3 p-2 text-left">
				                                 	<img src=" @if(!empty($cats->image) && $cats->image != 'N/A') {{ url('/admin-panel/'.$cats->image) }} @else {{url('/assets/website/img/sailogo.png')}} @endif" class="img-fluid max-width-100">
				                              	</div>

				                              	<div class="col-7 pl-1 text-left">
				                                 	<!-- <span class="category-offer">Upto 80% Off</span> -->
				                                 	<h6 class="category-name">{{$cats->title}}</h6>
				                                 	<!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> -->
				                              	</div>

				                              	<div class="col-2 pr-1 text-right">
				                                 	<span class="mdi mdi-chevron-down"></span>
				                              	</div>
				                           	</div>
			                          	</button>
			                        </h5>
		                     	</div>

			                    <div id="collapseOne{{$cats->cat_id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
			                        <div class="card-body p-2">
			                         	<div class="row no-gutters">
                                        <?php //dd($catproductdetails); ?>
			                         
			                         
			                         		@foreach($cats->subcategory as $subcategory)


				                           	<div class="col-lg-2 col-md-3 col-4 text-center p-2 border d-block">
				                              	<a href=" @if(isset($subcategory->cat_id))  {{url('all-products?id='.$subcategory->cat_id)}} @endif">
				                              		<img src=" @if(!empty($subcategory->image) && $subcategory->image != 'N/A') {{ url('/admin-panel/'.$subcategory->image) }} @else {{url('/assets/website/img/sailogo.png')}} @endif" class="img-fluid max-width-100 m-auto">
				                              		<!-- <p class="category-offer">Upto % Off</p> -->
				                              		<h6 class="category-name">{{ucfirst($subcategory->title)}}</h6>
				                               	</a>
				                           	</div>
				                           	@endforeach
				                         

				                           	
			                         	</div>
			                        </div>
			                    </div>
		                   	</div>
		                   	@endif
		                   	@endforeach
		                   	@endif

		                   
		                </div>
	               	</div>
            	</div>
            </div>
        </div>
  	</section>

    <section class="section-padding bg-white border-top" style="display:none;">
        <div class="card px-sm-5 px-2">
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