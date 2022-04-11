@extends('website.layouts.app')
@section('content')
<?php //dd($pricecheck);?>
 <?php 
      	$categorysearch='';
      	$pricerange='';
      	$sortorder='';
      	if(isset($_GET['category'])){
	   		$categorysearch=$_GET['category'];

		}

		if(isset($_GET['pricerange'])){
			$pricerange=$_GET['pricerange'];
		}
		if(isset($_GET['sortorder'])){
			$sortorder=$_GET['sortorder'];
		}
      ?>
<style>
.noResult{
 	font-size: 20px;
    font-weight: bold;
    width: 80%;
    margin: auto;
    height: 400px;
    align-items: center;
    display: flex;
    justify-content: center;
    line-height: 40px;
    text-align: center;
}
	.pager div
			{
				float: left;
				border: 1px solid gray;
				margin: 5px;
				padding: 10px;
			}

			.pager div.disabled
			{
				opacity: 0.25;
			}

			.pager .pageNumbers a
			{
				display: inline-block;
				padding: 0 10px;
				color: gray;
			}

			.pager .pageNumbers a.active
			{
				color: orange;
			}

			.pager 
			{
				overflow: hidden;
			}

			.paginate-no-scroll .items div
			{
				height: 250px;
			}
</style>
	 <?php //dd($catproductdetails); ?>
	<!-- white background -->
	<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
     	<div class="container">
        	<div class="row">
           		<div class="col-md-12">
                  	<a href="{{url('/')}}">
	                  	<strong>
	                  		<span class="mdi mdi-home"></span> Home
	                  	</strong>
                  	</a> 
                  	<span class="mdi mdi-chevron-right"></span> 
                  	<a href="{{url('/all-products')}}">Shop</a>
           		</div>
        	</div>
     	</div>
  	</section>

    <section class="shop-list section-padding">
         <div class="container">
            <div class="row">
               <div class="col-lg-3 d-lg-block d-none">
				   <div class="shop-filters shopfilter_ext">
					  <div id="accordion">
						 <div class="card">
							<div class="card-header" id="headingOne">
							   <h5 class="mb-0">
								  <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								  Category <span class="mdi mdi-chevron-down float-right"></span>
								  </button>
							   </h5>
							</div>
							<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
							   <div class="card-body card-shop-filters">
								  <form class="form-inline mb-3" method="post" action="{{url('/all-products')}}">
								  	{{csrf_field()}}
									 <div class="form-group d-flex">
										<input type="text" name="catsearch" class="form-control w60-percent" placeholder="Search By Category">
									
									<button type="submit" class="pl-2 pr-2 btn btn-secondary btn-lg"><i class="mdi mdi-file-find" onclick="this.form.submit()"></i></button>
									 </div>
								  </form>
								  @foreach($categories as $category)
                                      @if($category->parent == 0)
								  <div class="custom-control custom-checkbox">
								  
									 <input type="checkbox" class="custom-control-input" id="cb{{$category->cat_id}}"  name="catcheck" onclick="set_category_checkbox('{{$category->cat_id}}')" value="{{$category->cat_id}}" <?php if(isset($categorysearch) && $category->cat_id == $categorysearch) {  echo 'checked="checked"'; } ?>>
									 <label class="custom-control-label" for="cb{{$category->cat_id}}">{{$category->title}} </label>
									
								  </div>
								  @endif
								  @endforeach
								 
							   </div>
							</div>
						 </div>
						 <div class="card">
							<div class="card-header" id="headingTwo">
							   <h5 class="mb-0">
								  <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								  Price <span class="mdi mdi-chevron-down float-right"></span>
								  </button>
							   </h5>
							</div>
							<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
							   <div class="card-body card-shop-filters">
								  <div class="custom-control custom-checkbox">
								  	<!--added code for price range filter beg-->
								  	
									 <input type="checkbox" class="custom-control-input" id="1" name="pricecheck" value="1" <?php if(isset($pricerange) && $pricecheck == 1) { echo 'checked="checked"'; } ?> onclick="set_price_checkbox('1')"  >
									
									


									 <label class="custom-control-label" for="1">₹68 to ₹659 <span class="badge badge-warning" style="display:none;">50% OFF</span></label>
									
									 <!-- added code for price range filter end-->
								  </div>
								  <div class="custom-control custom-checkbox">
									<!--  <input type="checkbox" class="custom-control-input" id="2"> -->
									 <!--added code for price range filter beg-->
								  	
									 <input type="checkbox" class="custom-control-input" id="2"  name="pricecheck" value="2" <?php if(isset($pricerange) && $pricecheck == 2) { echo 'checked="checked"';} ?>  onclick="set_price_checkbox('2')" >
									 
									
									 <label class="custom-control-label" for="2">₹660 to ₹1014</label>
									
									 <!-- added code for price range filter end-->
								  </div>
								  <div class="custom-control custom-checkbox">
									 <!-- <input type="checkbox" class="custom-control-input" id="3"> -->
									 <!--added code for price range filter beg-->
								  	
									 <input type="checkbox" class="custom-control-input" id="3" name="pricecheck" value="3" <?php if(isset($pricerange) && $pricecheck == 3){ echo 'checked="checked"'; } ?>  onclick="set_price_checkbox('3')" >
									
									 <label class="custom-control-label" for="3">₹1015 to ₹1679</label>
									
									 <!-- added code for price range filter end-->
									
								  </div>
								  <div class="custom-control custom-checkbox">
									<!--  <input type="checkbox" class="custom-control-input" id="4"> -->
									
									 <input type="checkbox" class="custom-control-input" id="4" name="pricecheck" value="4"  <?php if(isset($pricerange) && $pricecheck == 4){ echo 'checked="checked"';} ?> onclick="set_price_checkbox('4')" >
									 
									
									 <label class="custom-control-label" for="4">₹1680 to ₹1856</label>
									
									 <!-- added code for price range filter end-->
								  </div>
							   </div>
							</div>
						 </div>
						 <div class="card" style="display:none;">
							<div class="card-header" id="headingThree">
							   <h5 class="mb-0">
								  <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								  Brand <span class="mdi mdi-chevron-down float-right"></span>
								  </button>
							   </h5>
							</div>
							<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
							   <div class="card-body card-shop-filters">
								  <form class="form-inline mb-3">
									 <div class="form-group d-flex">
										<input type="text" class="form-control w60-percent" placeholder="Search By Brand">
										 <button type="submit" class="pl-2 pr-2 btn btn-secondary btn-lg"><i class="mdi mdi-file-find"></i></button>
									 </div>
									
								  </form>
								  <div class="custom-control custom-checkbox">
									 <input type="checkbox" class="custom-control-input" id="b1">
									 <label class="custom-control-label" for="b1">Imported Fruits <span class="badge badge-warning">50% OFF</span></label>
								  </div>
								  <div class="custom-control custom-checkbox">
									 <input type="checkbox" class="custom-control-input" id="b2">
									 <label class="custom-control-label" for="b2">Seasonal Fruits <span class="badge badge-secondary">NEW</span></label>
								  </div>
								  <div class="custom-control custom-checkbox">
									 <input type="checkbox" class="custom-control-input" id="b3">
									 <label class="custom-control-label" for="b3">Imported Fruits <span class="badge badge-danger">10% OFF</span></label>
								  </div>
								  <div class="custom-control custom-checkbox">
									 <input type="checkbox" class="custom-control-input" id="b4">
									 <label class="custom-control-label" for="b4">Citrus</label>
								  </div>
							   </div>
							</div>
						 </div>
						 <div class="card" style="display:none;">
							<div class="card-header" id="headingThree">
							    <h5 class="mb-0">
								    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
								  		Imported Fruits 
								  		<span class="mdi mdi-chevron-down float-right"></span>
								    </button>
							    </h5>
							</div>

							<div id="collapsefour" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
							    <div class="card-body">
									<div class="list-group">
										<a href="#" class="list-group-item list-group-item-action">All Fruits</a>
										<a href="#" class="list-group-item list-group-item-action">Imported Fruits</a>
										<a href="#" class="list-group-item list-group-item-action">Seasonal Fruits</a>
										<a href="#" class="list-group-item list-group-item-action">Citrus</a>
										<a href="#" class="list-group-item list-group-item-action disabled">Cut Fresh & Herbs</a>
									</div>
							    </div>
							</div>
						 </div>
					  </div>
				   </div>
				   <!--  <div class="left-ad mt-4">
					    <img class="img-fluid" src="http://via.placeholder.com/254x557" alt="">
				    </div> -->
				</div>

               	<div class="col-lg-9">
                  	<a href="#">
                  		<img class="img-fluid mb-3" src="{{ url('assets/website/img/shop.jpg') }}" alt="" style="display:none;">
                  	</a>
	                <div class="shop-head">
	                    <a href="{{url('/')}}">
	                    	<span class="mdi mdi-home"></span> Home
	                    </a> 
	                    	<span class="mdi mdi-chevron-right"></span> 
	                    <a href="{{url('/all-products')}}">Fruits & Vegetables</a> 
	                    	<span class="mdi mdi-chevron-right"></span> 
	                    <!-- <a href="#">Fruits</a> -->
	                    <div class="btn-group float-right mt-2">
	                        <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                        Sort by Products &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                        </button>
	                        <div class="dropdown-menu dropdown-menu-right">
	                           	<!-- <a class="dropdown-item" href="#">Relevance</a>
	                            <a class="dropdown-item" href="#">Price (Low to High)</a>
	                            <a class="dropdown-item" href="#">Price (High to Low)</a>
	                            <a class="dropdown-item" href="#">Discount (High to Low)</a>
	                            <a class="dropdown-item" href="#">Name (A to Z)</a> -->
	                            {{-- added for sort type beg --}}
	                           
	                            <li class="dropdown-item" onclick="sortorder('lh')">Price (Low to High)</li>
	                            <li class="dropdown-item" onclick="sortorder('hl')">Price (High to Low)</li>
	                            <li class="dropdown-item" onclick="sortorder('discounthl')">Discount (High to Low)</li>
	                            <li class="dropdown-item" onclick="sortorder('atoz')">Name (A to Z)</li> 
	                            {{-- added for sort type end --}}
	                            
	                        </div>
	                    </div>
	                    <h5 class="mb-3 fruitext"></h5> 
	                </div>
                  
                
                  	<div class="row no-gutters paginateaz 1az" id="cat_prouct_list">
                  <?php 
                  	//dd($catproductdetails);
                  ?>

                  	@if(!empty($catproductdetails))
		                  		
		                      @foreach($catproductdetails as $k=>$v)
		                      
		                         

		                        
					                         
		                     		@foreach($v as $k1=>$v1)
		                     		<?php 
		                     		$visibile=1;
		                     		$click_event='';
		                     		$stock_text='';
		                             if(isset($v1->stock) && $v1->stock == 0){
		                             	$visibile=0.5;
		                             	$stock_text='Out of Stock';
		                             	$click_event='none';
		                             }

		                     		?>

		                <div class="col-md-4 all cpid{{$v1->cid}} itemsaz" id="cpid{{$v1->cid}}" style="opacity:{{$visibile}}; pointer-events:{{$click_event}}">

			                        <div class="product" id="productid{{$v1->product_id}}">
			                        	<p class="stock" style="color:red; text-align: center;font-weight: bold;">{{$stock_text}}</p>
			                              <a href="{{url('/product-detail/'.$v1->varient_id)}}">
			                              	<div class="product-header">
			                              		@if($v1->discount_percentage > 0)
			                                 	<span class="badge badge-success"  id="product_discount{{$v1->product_id}}">{{round($v1->discount_percentage,2)}}% OFF</span>
			                                 	@endif
			                                 	<img class="img-fluid" src=" {{url('/admin-panel/'.$v1->product_image)}}" alt=""  id="varient_img{{$v1->varient_id}}">
			                                 	<!-- <span class="veg text-success mdi mdi-circle"></span> -->
			                              	</div>
			                              </a>

			                              	<div class="product-body">

			                                 	<h5>{{$v1->product_name}}</h5>
			                                 	<h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="availability{{$v1->product_id}}">{{$v1->quantity}} {{$v1->unit}}</span></h6>
			                              	</div>
		                                     <?php 
		                                 $price_after_discount=$v1->mrp*((100 -$v1->discount_percentage)/100);
		                                 $discountprice=$v1->mrp*$v1->discount_percentage/100;

		                                 ?>
			                              	<div class="product-footer">
			                              		<!--added extra cod for variant beg-->

	                        		 	<!--added for varient beg-->
	                           <div class="varient_product" style="">
	                           		<div class="form-group">
								    
								    	<select class="form-control variantchange selpid{{$v1->product_id}}" onchange="getvarientdata('{{$v1->product_id}}')">
				                           	@foreach($variantproductarary[$v1->product_id] as $varkey=>$vardata)
				                           	<?php
				                           	 $bgcolor="btn btn-light";
			                                 if($vardata->varient_id == $v1->varient_id){
			                                   $bgcolor="btn btn-success";
			                                 }

				                           	 ?>
	                           
	                           	
								     			 <option value="{{$vardata->varient_id}}" id="varient{{$v1->product_id}}{{$vardata->varient_id}}">{{$vardata->quantity}}{{$vardata->unit}}</option>
								     
								   
	                           	<!--added code to change span to dropdown end-->
	                           	 @endforeach
	                           	  </select>
							  	</div><!--selct div end-->
	                           </div>
	                          
	                           <!--added for varient end -->

	                        	<!--added extra cod for variant end-->

				                                <button type="button" class="btn btn-secondary btn-sm float-right" onclick="addtocart('{{$v1->varient_id}}')" id="addtocart{{$v1->product_id}}">
				                                	
				                                	<i class="mdi mdi-cart-outline"></i> Add To Cart
				                                </button>
				                                <p class="offer-price mb-0">₹<span id="variant_price{{$v1->varient_id}}">{{$price_after_discount}} </span>
				                                	<i class="mdi mdi-tag-outline"></i><br>
				                                	<span class="regular-price">@if($v1->discount_percentage > 0) ₹{{$v1->mrp}} @endif</span>
				                                </p>
			                              	</div>
			                           	
			                        </div>
			                    </div>

			                    @endforeach
			                
			                   

			                                      <!--pager extra end-->
			                     @endforeach
	                  @else
	                  <div class="noResult">We are sorry,No result found.Please try to search with different spelling.</div>
	                  @endif
                  	</div>
                  	

                  	<nav>
                  		 <ul class="pagination justify-content-center mt-4">
                  		 	<li class="page-item">
	                        	{{ $catproductrels->links() }}
	                        </li>
                  		 </ul>
                  		
                  	</nav>

                  	
               	</div>
            </div>
         </div>
    </section>

      <section class="product-items-slider section-padding bg-white border-top" >
         <div class="container">
            <div class="section-header">
               <h5 class="heading-design-h5">Best Offers View<span class="badge badge-primary"></span>
                  <a class="float-right text-secondary" href="{{url('/all-products/best-offer')}}">View All</a>
               </h5>
            </div>
            <div class="owl-carousel owl-carousel-featured">

            	@foreach($products as $product)
            
               	<div class="item">

                  	<div class="product extt" id="productid{{$product->product_id}}">
	                    <a href="{{url('/product-detail/'.$product->product_id)}}">
	                        <div class="product-header">
	                           	<span class="badge badge-success" id="product_discount{{$product->product_id}}">{{round($product->discount_percentage)}}% OFF</span>
	                           	<img class="img-fluid" src="@if(file_exists($product->product_image)) {{ url($product->varient_image) }} @else {{url('/assets/website/img/logo.png')}} @endif" alt=""  id="varient_img{{$product->varient_id}}">
	                           <!-- 	<span class="veg text-success mdi mdi-circle"></span> -->
	                        </div>

	                        <div class="product-body">
	                           	<h5>{{$product->product_name}}</h5>
	                           	<h6>
	                           		<strong>
	                           			<span class="mdi mdi-approval"></span> Available in
	                           		</strong> - <span id="availability{{$product->product_id}}">{{$product->quantity}} {{$product->unit}}</span>
	                           	</h6>
	                        </div>
                           </a>
	                        <div class="product-footer">
	                        	 	<!--added for varient beg-->
	                           <div class="varient_product" style="">
	                           		<div class="form-group">
								    
								    	<select class="form-control variantchange selpid{{$product->product_id}}" onchange="getvarientdata('{{$product->product_id}}')">
				                           	@foreach($variantproductarary[$product->product_id] as $varkey=>$vardata)
				                           	<?php
				                           	 $bgcolor="btn btn-light";
			                                 if($vardata->varient_id == $product->varient_id){
			                                   $bgcolor="btn btn-success";
			                                 }

				                           	 ?>
	                           
	                           	
								     			 <option value="{{$vardata->varient_id}}" id="varient{{$product->product_id}}{{$vardata->varient_id}}">{{$vardata->quantity}}{{$vardata->unit}}</option>
								     
								   
	                           	<!--added code to change span to dropdown end-->
	                           	 @endforeach
	                           	  </select>
							  	</div><!--selct div end-->
	                           </div>
	                          
	                           <!--added for varient end -->
	                          <button type="button" class="btn btn-secondary btn-sm float-right"  onclick="addtocart('<?php echo $product->varient_id; ?>')" id="addtocart{{$product->product_id}}">
	                           		<i class="mdi mdi-cart-outline"></i> Add To Cart
	                           	</button>
                                 <?php 
                                 $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
                                 $discountprice=$product->mrp*$product->discount_percentage/100;

                                 ?>
	                           	<p class="offer-price mb-0">₹<span id="variant_price{{$product->varient_id}}">{{$price_after_discount}} </span>
	                           		<i class="mdi mdi-tag-outline"></i><br>
	                           		<span class="regular-price" id="product_mrp">₹{{$product->mrp}}</span>
	                           	</p>
	                        </div>
	                    
                  	</div>
               	</div>
               	@endforeach

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
      {{-- form for filter --}}
     
      <form method="get" id="frmfilter">
			<input type="hidden" name="category" id="category" value='<?php echo $categorysearch?>'/>
			<input type="hidden" name="pricerange" id="pricerange" value='<?php echo $pricerange?>'/>
			<input type="hidden" name="sortorder" id="sortorder" value='<?php echo $sortorder?>'/>
			
		</form>
      {{-- form for filter --}}

@endsection
<!-- script for filter-->
<script>
			/*function set_category_checkbox(id){
				var category=jQuery('#category').val();
				var check=category.search(":"+id);
				if(check!='-1'){
					category=category.replace(":"+id,'');
				}else{
					category=category+":"+id;	
				}
				jQuery('#category').val(category);
				jQuery('#frmfilter')[0].submit();
			}*/

			/*function set_price_checkbox(id){
				var pricerange=jQuery('#pricerange').val();
				var check=pricerange.search(":"+id);
				if(check!='-1'){
					pricerange=pricerange.replace(":"+id,'');
				}else{
					pricerange=pricerange+":"+id;	
				}
				jQuery('#pricerange').val(pricerange);
				jQuery('#frmfilter')[0].submit();
			}*/
			 function set_category_checkbox(id){
				jQuery('#category').val(id);
				jQuery('#frmfilter')[0].submit();
			}
            
            function set_price_checkbox(id){
				jQuery('#pricerange').val(id);
				jQuery('#frmfilter')[0].submit();
			}
			
			function sortorder(type){
				jQuery('#sortorder').val(type);
				jQuery('#frmfilter')[0].submit();
			}

		</script>
<!-- script for filter-->

<!--added script for pagination beg-->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> original-->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="{{url('assets/paginga.jquery.js')}}"></script> -->
<!--added script for pagination end-->

<script>

	function showcatproducts(cid){
		//alert(cid);
		$('.all').hide();
		//$('#cpid'+cid).show();
		$('.cpid'+cid).show();
		//$('#cattitle'+cid).show();
		$('.cattitle'+cid).show();

	}
	
</script>