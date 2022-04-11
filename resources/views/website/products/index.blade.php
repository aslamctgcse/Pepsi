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

<?php 
//dd(dd(session()->get('footer_cart_qty')));
//dd(session()->get('footer_cart_qty'));
 $cart_product_list=Session::get('cart_product_list');
 //dd($cart_product_list);
 //seesion qty

$categorysearch='';
$pricerange='';
$sortorder='';
$pricecheck='';
$id='';
$storesearch='';

if(isset($_GET['category'])){
	$categorysearch=$_GET['category'];

}

//id also use as category id
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$categorysearch=$_GET['id'];
}

if(isset($_GET['storeId'])){
	$storesearch=$_GET['storeId'];
}

if(isset($_GET['pricerange'])){
	$pricerange=$_GET['pricerange'];
}
if(isset($_GET['sortorder'])){
	$sortorder=$_GET['sortorder'];
}
?>

<style>
	span.select2-search.select2-search--dropdown {
		display: none !important;
	}
	.sext{
		cursor: pointer;
	}
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
	.input-group-addon-extra{
		border-right: 0;
		padding: 4px 4px;
		height: auto;
		font-size: 12px;
		color: #fff;
    background: #ba0002;
    font: 12px/16px ProximaNovaA-Regular;
    border-color: #ba0002;
	}
	.stockbtn{
		background: #778be0 none repeat scroll 0 0;
		border: 1px solid #778be0;
		margin: 2px;
		height: 24px;
		font-size:14px;
		min-width: 95px;
	}
</style>

<?php //dd($catproductdetails); ?>
<!-- white background -->

<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
	<div class="container-fluid">
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
	<div class="container-fluid">
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
											<input type="text" name="catsearch" class="form-control w60-percent" placeholder="Search By Category" value="@if(isset($categoryFilterValue)) <?php echo $categoryFilterValue ?> @endif" style="border-top-right-radius: 0;    border-bottom-right-radius: 0;">

											<button type="submit" class="pl-2 pr-2 btn btn-secondary btn-lg" style="border-bottom-left-radius: 0 !important;    border-top-left-radius: 0 !important;margin-left: -1px;"><i class="mdi mdi-file-find" onclick="this.form.submit()"></i></button>
										</div>
									</form>
									@foreach($categories as $category)
									@if($category->parent == 0)
									<div class="custom-control custom-checkbox">

										<input type="checkbox" class="custom-control-input" id="cb{{$category->cat_id}}"  name="catcheck" onclick="set_category_checkbox('{{$category->cat_id}}',this)" value="{{$category->cat_id}}" <?php if(isset($categorysearch) && $category->cat_id == $categorysearch) {  echo 'checked="checked"'; } ?>>
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

										<input type="checkbox" class="custom-control-input" id="1" name="pricecheck" value="1" <?php if(isset($pricerange) && $pricerange == 1) { echo 'checked="checked"'; } ?> onclick="set_price_checkbox('1',this)"  >




										<label class="custom-control-label" for="1">₹68 to ₹659 <span class="badge badge-warning" style="display:none;">50% OFF</span></label>

										<!-- added code for price range filter end-->
									</div>
									<div class="custom-control custom-checkbox">
										<!--  <input type="checkbox" class="custom-control-input" id="2"> -->
										<!--added code for price range filter beg-->

										<input type="checkbox" class="custom-control-input" id="2"  name="pricecheck" value="2" <?php if(isset($pricerange) && $pricerange == 2) { echo 'checked="checked"';} ?>  onclick="set_price_checkbox('2',this)" >


										<label class="custom-control-label" for="2">₹660 to ₹1014</label>

										<!-- added code for price range filter end-->
									</div>
									<div class="custom-control custom-checkbox">
										<!-- <input type="checkbox" class="custom-control-input" id="3"> -->
										<!--added code for price range filter beg-->

										<input type="checkbox" class="custom-control-input" id="3" name="pricecheck" value="3" <?php if(isset($pricerange) && $pricerange == 3){ echo 'checked="checked"'; } ?>  onclick="set_price_checkbox('3',this)" >

										<label class="custom-control-label" for="3">₹1015 to ₹1679</label>

										<!-- added code for price range filter end-->

									</div>
									<div class="custom-control custom-checkbox">
										<!--  <input type="checkbox" class="custom-control-input" id="4"> -->

										<input type="checkbox" class="custom-control-input" id="4" name="pricecheck" value="4"  <?php if(isset($pricerange) && $pricerange == 4){ echo 'checked="checked"';} ?> onclick="set_price_checkbox('4',this)" >


										<label class="custom-control-label" for="4">₹1680 to ₹1856</label>

										<!-- added code for price range filter end-->
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
						{{-- id to ?id  --}}
						@if(!empty($id)  && $id != 'best-offer' || !empty($categorysearch))

						   @if($category_id_and_name && $category_id_and_name->title)
						   <a href="{{url('/all-products?id='.$category_id_and_name->cat_id)}}"> {{$category_id_and_name->title}}</a>
						   @else
						    <a href="{{url('/all-products?id=')}}">Shop</a> 
						   @endif  
						

						
						<span class="mdi mdi-chevron-right"></span> 
						@else
						<a href="{{url('/all-products')}}"> Shop</a> 
						<span class="mdi mdi-chevron-right"></span> 
						@endif
						<!-- <a href="#">Fruits</a> -->
						<div class="btn-group float-right mt-0">
							<button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Sort by Products &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</button>
							<div class="dropdown-menu dropdown-menu-right">

								{{-- added for sort type beg --}}

								<li class="dropdown-item sext" onclick="sortorder('lh')">Price (Low to High)</li>
								<li class="dropdown-item sext" onclick="sortorder('hl')">Price (High to Low)</li>
								<li class="dropdown-item sext" onclick="sortorder('discounthl')">Discount (High to Low)</li>
								<li class="dropdown-item sext" onclick="sortorder('atoz')">Name (A to Z)</li> 
								{{-- added for sort type end --}}

							</div>
						</div>
						<h5 class="mb-3 fruitext"></h5> 
					</div>


					<div class="row no-gutters paginateaz 1az w-100" id="cat_prouct_list">
                        

						@if(!empty($productarray))


						@foreach($productarray as $k=>$v)

						{{-- @foreach($v as $k1=>$v1) --}}	
						<?php 

						$visibile=1;
						$click_event='';
						$stock_text='';
						$addtocartdisp="block";
						$qtyblockdisp='block';
						$btndisabledNew='none';
						if(isset($v[0]->stock) && $v[0]->stock == 0 || empty($v[0]->stock) || $v[0]->status==0){
							$visibile=0.5;
							$stock_text='Out of Stock';
							$addtocartdisp="none";
							$qtyblockdisp='none';
							$btndisabledNew='block';
							$click_event='none';
							$btndisabled='disabled';

						}
						$qtyaddedtocart='';
						$btndisabled='';
						
						$defaultqtydisp='block';
						if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$v[0]->varient_id])){
						$addtocartdisp="none";
						$defaultqtydisp='none';

							$qtyaddedtocart=session()->get('footer_cart_qty')[$v[0]->varient_id];
							if(!empty($qtyaddedtocart) && $qtyaddedtocart == $v[0]->stock){
								//dd('here');
								$qtyblockdisp='none';
								$visibile=0.5;
								$stock_text='Out of Stock';
								$btndisabledNew='block';
								$click_event='none';
								$btndisabled='disabled';
								

							}
						}


		                                 #added new code to show mrp,price as it is n calculate discount from mrp n price beg
						$price_after_discount=$v[0]->price;
                        #added code to calculate discount if price is greater than zero
                        if($v[0]->mrp > 0 || $v[0]->price > 0){
						$discount=(($v[0]->mrp - $v[0]->price)/$v[0]->mrp)*100;
						}else{
							$discount=0;
						}
                     	#added new code to show mrp,price as it is n calculate discount from mrp n price end


						?>

						<div class="col-md-4 p-2 all cpid{{$v[0]->cid}} itemsaz stock{{$v[0]->varient_id}}" id="cpid{{$v[0]->cid}}">

							{{-- hidden fields to get out of stock beg --}}
							<input type="hidden" name="qtyadded" class="qtyadded{{$v[0]->product_id}}" value="0">
							<input type="hidden" name="productstock" value="{{$v[0]->stock ?? 0}}" class="productstock{{$v[0]->product_id}}">
							{{--  hidden fields to get out of stock beg --}}

							<div class="product" id="productid{{$v[0]->varient_id}}">
								<!-- <p class="stock" id="stockmessage{{$v[0]->product_id}}" style="color:red; text-align: center;font-weight: bold;">{{$stock_text}}</p> -->
								<a href="{{url('/product-detail/'.$v[0]->product_id)}}">
									<div class="product-header" style="opacity:{{$visibile}}; pointer-events:{{$click_event}}">
										<!-- @if($discount > 0)
										<span class="badge badge-success" id="product_discount{{$v[0]->product_id}}">{{round($discount,2)}}% OFF</span>
										@endif -->
										@if($sortorder=='discounthl')
										@if($discount > 0)
										<span class="badge badge-success" id="product_discount{{$v[0]->product_id}}">{{round($discount,2)}}% OFF</span>
										@endif
										@endif
										<img class="img-fluid" src=" {{url('/admin-panel/'.$v[0]->product_image)}}" alt=""  id="varient_img{{$v[0]->product_id}}">
										<!-- <span class="veg text-success mdi mdi-circle"></span> -->
									</div>
								</a>

								<div class="product-body">

									<h5>{{$v[0]->product_name}} </h5>
									<!-- <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="availability{{$v[0]->product_id}}">{{$v[0]->quantity}} {{$v[0]->unit}}</span></h6> -->
									<!-- <p>Sold by {{$v[0]->store_name}} </p> -->
								</div>

								<div class="product-footer">
									<!--added extra cod for variant beg-->

									<!--added for varient beg-->
									<!-- <div class="varient_product" style="">
										<div class="form-group">
											<input type="hidden" name="current_varient" id="current_varient_{{$v[0]->product_id}}" value="{{$v[0]->varient_id}}">
											<select class="form-control variantchange selpid{{$v[0]->product_id}}" onchange="getvarientdata('{{$v[0]->product_id}}')">
												@if(isset($variantproductarary[$v[0]->product_id]))
												@foreach($variantproductarary[$v[0]->product_id] as $varkey=>$vardata)
												<?php
												$bgcolor="btn btn-light";
												if($vardata->varient_id == $v[0]->varient_id){
													$bgcolor="btn btn-success";
												}

												?>


												<option value="{{$vardata->varient_id}}" id="varient{{$v[0]->product_id}}{{$vardata->varient_id}}">{{$vardata->quantity}}{{$vardata->unit}}</option>


												@endforeach
												@endif
											</select>
										</div>
									</div> -->

									<!--added for varient end -->

									<!--added extra cod for variant end here az-->

                                    
									<button type="button" class="btn btn-secondary btn-sm float-right btn-cart btnidentity{{$v[0]->varient_id}}" onclick="addtocart('{{$v[0]->varient_id}}','{{$v[0]->product_id}}')" id="addtocart{{$v[0]->varient_id}}" {{$btndisabled}} style="display:{{$addtocartdisp}};">
										<i class="mdi mdi-cart-outline"></i>


										Add To Cart
									</button>
									
									<p class="float-right"><span class="badge badge-success stockbtn stockbtn{{$v[0]->varient_id}}" style="color:red;display:{{$btndisabledNew}}">Out of Stock</span></p>

									

									<!--added code to add a container to append cart qty of individual peoduct from json response beg-->
									<span class="append_qty_block{{$v[0]->varient_id}}"  style="display:{{$qtyblockdisp}};">
									<!--added code to show qty block if item is added to cart beg-->
									<?php if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$v[0]->varient_id])){
										//dd('jkhj');
									/*$inc='inc';
									$dec='dec'; */
									?>
										<p class="offer-price mb-0 quantity" style="float:right;">
											<span class="input-group-btn">
												<button disabled="disabled" class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;">
													<span onclick="cartupdate('{{$v[0]->varient_id}}','dec')" id="dec{{$v[0]->varient_id}}" class="dec{{$v[0]->varient_id}}" style="font-size:28px">
												@if(session()->get('footer_cart_qty')[$v[0]->varient_id]=='1')
												 <?php echo 'x';?>
												@else
												  <?php echo '-';?>
												@endif		
													
												    </span>
												</button>
											</span>
											<span>
												<input type="text" max="" min="" value="{{session()->get('footer_cart_qty')[$v[0]->varient_id]}}"  name="quant[1]" class="quant{{$v[0]->varient_id}}" style="width:30px;" readonly>
											</span>
											<span class="input-group-btn">
												<button class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;">
													<span onclick="cartupdate('{{$v[0]->varient_id}}','inc')" id="'inc{{$v[0]->varient_id}}" class="inc{{$v[0]->varient_id}}">+</span>
												</button>

											</span>
										</p>
										<?php } ?>
									<!--added code to show qty block if item is added to cart end-->
									</span>
									<!--added code to add a container to append cart qty of individual peoduct from json response end-->
									<p class="offer-price mb-0 pt-1">₹<span id="variant_price{{$v[0]->product_id}}">{{$price_after_discount}} </span>
										<i class="mdi mdi-tag-outline"></i>
										<!-- added code to show qty beg--> 
										<?php if(!isset(session()->get('footer_cart_qty')[$v[0]->varient_id])){
										//dd('jkhj');
									/*$inc='inc';
									$dec='dec'; */
									?>
										<!-- <span class="dblock defaut_qty_block{{$v[0]->product_id}}" style="margin-left:6px;">
										<span class="default_qty{{$v[0]->product_id}} input-group-addon-extra">Qty</span>
										<span><input type="text" name="default_qty_input{{$v[0]->product_id}}" value="1" style="width:20px;"></span> 
										</span> -->
									<?php } ?>
										<!-- added code to show qty end-->
										<br>

										<!-- <span class="regular-price" id="product_mrp{{$v[0]->product_id}}">@if($discount > 0) ₹{{$v[0]->mrp}} @endif</span> -->
										
									</p>
								</div>

							</div>
						</div>
						<?php //} //for one time iteration to stop variant duplicacy.i.e all variant should show in single product in dropdowny ?>

						{{-- @endforeach --}} 



						<!--pager extra end-->
						@endforeach
						@else
						<div class="noResult">We are sorry, No result found.</div>
						@endif
					</div>


					<nav>
						<ul class="pagination justify-content-center mt-4">
							<li class="page-item overflow-x-auto">

								{!! $catproductrels->appends(request()->input())->links() !!}
							</li>
						</ul>

					</nav>


				</div>
			</div>
		</div>
	</section>

	<section class="product-items-slider section-padding bg-white border-top"  style="display:none;">
		<div class="container">
			<div class="section-header">
				<h5 class="heading-design-h5">Best Offers View<span class="badge badge-primary"></span>
					<a class="float-right text-secondary" href="{{url('/all-products?sortorder=discounthl')}}">View All</a>
				</h5>
			</div>
			<div class="owl-carousel owl-carousel-featured">

				@foreach($products as $product)
				<?php 
		                     		//if($v1->product_id == $v1->varient_id) {
				$visibile=1;
				$click_event='';
				$stock_text='';
				if(isset($product->stock) && $product->stock == 0 || empty($product->stock)){
					$visibile=0.5;
					$stock_text='Out of Stock';
					$click_event='none';

				}
				$qtyaddedtocart='';
				if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$product->varient_id])){
					$qtyaddedtocart=session()->get('footer_cart_qty')[$product->varient_id];
					if(!empty($qtyaddedtocart) && $qtyaddedtocart == $product->stock){
						$visibile=0.5;
						$stock_text='Out of Stock';
						$click_event='none';

					}
				}

				$price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
		                                 //$discountprice=$v[0]->mrp*$v[0]->discount_percentage/100;

		                                 #added new code to show mrp,price as it is n calculate discount from mrp n price beg
				$price_after_discount=$product->price;
                                 		//$discountprice=$catproductrels_single->mrp*$catproductrels_single->discount_percentage/100;
				$discount=(($product->mrp - $product->price)/$product->mrp)*100;
		                                 #added new code to show mrp,price as it is n calculate discount from mrp n price end


				?>

				<div class="item">
					{{-- hidden fields to get out of stock beg --}}
					<input type="hidden" name="qtyadded" class="qtyadded{{$product->product_id}}" value="0">
					<input type="hidden" name="productstock" value="{{$product->stock ?? 0}}" class="productstock{{$product->product_id}}">
					{{--  hidden fields to get out of stock beg --}}

					<div class="product extt stock{{$product->varient_id}}" id="productid{{$product->product_id}}"  style="opacity:{{$visibile}}; pointer-events:{{$click_event}}">
						<p class="stock" style="color:red; text-align: center;font-weight: bold;margin-left: 30px;">{{$stock_text}}</p> 
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
							<button type="button" class="btn btn-secondary btn-sm float-right"  onclick="addtocart('<?php echo $product->varient_id; ?>')" id="addtocart{{$product->varient_id}}" ssss>
								<i class="mdi mdi-cart-outline"></i> Add To Cart
							</button>
							<?php 
							$price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
							$discountprice=$product->mrp*$product->discount_percentage/100;

							?>
							<p class="offer-price mb-0">₹<span id="variant_price{{$product->varient_id}}">{{$price_after_discount}} </span>
								<i class="mdi mdi-tag-outline"></i><br>
								<span class="regular-price" id="product_mrp{{$product->varient_id}}">₹{{$product->mrp}}</span>
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
		<input type="hidden" name="id" id="category" value='<?php echo $categorysearch?>'/>
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
			function set_category_checkbox(id,event){
				console.log(event.checked);
				if(event.checked==false){
				  jQuery('#category').val('');
				  jQuery('#frmfilter')[0].submit();
				}else{
				  jQuery('#category').val(id);
				  jQuery('#frmfilter')[0].submit();	
				}
				
			}

			function set_price_checkbox(id,event){
				console.log(event.checked);
				if(event.checked==false){
				  jQuery('#pricerange').val('');
				  jQuery('#frmfilter')[0].submit();
				}else{
				  jQuery('#pricerange').val(id);
				  jQuery('#frmfilter')[0].submit();	
				}
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