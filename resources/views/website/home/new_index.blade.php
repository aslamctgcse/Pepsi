
@extends('website.layouts.app')
@section('content')
@php
//dd('new index');
@endphp
<style>
span.select2-search.select2-search--dropdown {
    display: none !important;
 
}
.stock {
    margin-left: 22px;
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
               	<a href="{{ route('all-products') }}">
               		<img class="img-fluid" src="@if(file_exists($banners->banner_image)){{ url($banners->banner_image) }} @else {{url('/assets/website/img/logo.png')}} @endif" alt="First slide">
               	</a>
            </div>
            @endforeach

          
        </div>
  	</section>
	<!-- End Slider -->
	
	<!--adde code for combo anf offers beg new chnges in id-->
	@if(isset($cat_offer))
	@foreach($cat_offer as $cats) 
     @if(isset($cats->subcategory) && count($cats->subcategory) > 0)
  	<section class="product-items-slider py-2">
  		
        <div class="card px-5">
            <div class="section-header">
             
               <h5 class="heading-design-h5"><a href="@if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif" class="text-secondary">{{$cats->title}}<span class="badge badge-primary"></span></a>
               	 @if(count($cats->subcategory) > 5)
                  <a class="float-right text-secondary" href=" @if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif">View All</a>
               </h5>
               @endif
            </div>
            
         
            <div class="owl-carousel owl-carousel-featured">
              <!--old offers beg-->
             @foreach($cats->subcategory as $subcategory)
            	  <div class="item" style="display:none;">

                  	<div class="product  pext" id="productid">
	                    <a href="@if(isset($subcategory->cat_id)) {{url('/all-products?id='.$subcategory->cat_id)}} @endif">
	                        <div class="product-header">
	                        	
	                           	<span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                           	<img class="img-fluid" src=" {{ url('/admin-panel/'.$subcategory->image)}}">
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
               <!--old offers end-->
               <!-- new adde code to show variants on home page beg line no-256-->
                
                  <!--  <div class="row no-gutters paginateaz 1az" id="cat_prouct_list"> -->
                  <!--  <div class="product pext no-gutters paginateaz 1az" id="cat_prouct_list"> -->
                                          

                            <?php //@if(!empty($productarray)) ?>
                              @if(isset($catproductarrays[$cats->cat_id]))


                            <?php   //@foreach($productarray as $k=>$v) //old ?>
                              @foreach($catproductarrays[$cats->cat_id] as $k=>$v)

                              {{-- @foreach($v as $k1=>$v1) --}}  
                              <?php 

                              $visibile=1;
                              $click_event='';
                              $stock_text='';
                              if(isset($v[0]->stock) && $v[0]->stock == 0 || empty($v[0]->stock)){
                                $visibile=0.5;
                                $stock_text='Out of Stock';
                                $click_event='none';
                                $btndisabled='disabled';

                              }
                              $qtyaddedtocart='';
                              $btndisabled='';
                              if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$v[0]->varient_id])){

                                $qtyaddedtocart=session()->get('footer_cart_qty')[$v[0]->varient_id];
                                if(!empty($qtyaddedtocart) && $qtyaddedtocart == $v[0]->stock){
                                  //dd('here');
                                  $visibile=0.5;
                                  $stock_text='Out of Stock';
                                  $click_event='none';
                                  $btndisabled='disabled';

                                }
                              }


                                                       #added new code to show mrp,price as it is n calculate discount from mrp n price beg
                              $price_after_discount=$v[0]->price;

                              $discount=(($v[0]->mrp - $v[0]->price)/$v[0]->mrp)*100;
                                                       #added new code to show mrp,price as it is n calculate discount from mrp n price end


                              ?>
                              <div class="item"><!--added to repeat item-->

                              <div class="col-md-4not all cpid{{$v[0]->cid}} itemsaz stock{{$v[0]->varient_id}}" id="cpid{{$v[0]->cid}}">

                                {{-- hidden fields to get out of stock beg --}}
                                <input type="hidden" name="qtyadded" class="qtyadded{{$v[0]->product_id}}" value="0">
                                <input type="hidden" name="productstock" value="{{$v[0]->stock ?? 0}}" class="productstock{{$v[0]->product_id}}">
                                {{--  hidden fields to get out of stock beg --}}

                                <div class="product" id="productid{{$v[0]->product_id}}">
                                  <p class="stock" id="stockmessage{{$v[0]->product_id}}" style="color:red; text-align: center;font-weight: bold;">{{$stock_text}}</p>
                                  <a href="{{url('/product-detail/'.$v[0]->varient_id)}}">
                                    <div class="product-header" style="opacity:{{$visibile}}; pointer-events:{{$click_event}}">
                                      @if($discount > 0)
                                      <span class="badge badge-success" id="product_discount{{$v[0]->product_id}}">{{round($discount,2)}}% OFF</span>
                                      @endif
                                      <img class="img-fluid" src=" {{url('/admin-panel/'.$v[0]->product_image)}}" alt=""  id="varient_img{{$v[0]->product_id}}">
                                      <!-- <span class="veg text-success mdi mdi-circle"></span> -->
                                    </div>
                                  </a>

                                  <div class="product-body">

                                    <h5>{{$v[0]->product_name}}</h5>
                                    <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="availability{{$v[0]->product_id}}">{{$v[0]->quantity}} {{$v[0]->unit}}</span></h6>
                                  </div>

                                  <div class="product-footer">
                                    <!--added extra cod for variant beg-->

                                    <!--added for varient beg-->
                                    <div class="varient_product" style="">
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


                                          <!--added code to change span to dropdown end-->
                                          @endforeach
                                          @endif
                                        </select>
                                      </div><!--selct div end-->
                                    </div>

                                    <!--added for varient end -->

                                    <!--added extra cod for variant end-->



                                    <button type="button" class="btn btn-secondary btn-sm float-right btn-cart btnidentity{{$v[0]->varient_id}}" onclick="addtocart('{{$v[0]->varient_id}}','{{$v[0]->product_id}}')" id="addtocart{{$v[0]->varient_id}}" {{$btndisabled}}>
                                      <i class="mdi mdi-cart-outline"></i>

                                      Add To Cart

                                    </button>
                                    <p class="offer-price mb-0">₹<span id="variant_price{{$v[0]->product_id}}">{{$price_after_discount}} </span>
                                      <i class="mdi mdi-tag-outline"></i><br>
                                      <span class="regular-price" id="product_mrp{{$v[0]->product_id}}">@if($discount > 0) ₹{{$v[0]->mrp}} @endif</span>
                                    </p>
                                  </div>

                                </div>
                              </div>
                              <?php //} //for one time iteration to stop variant duplicacy.i.e all variant should show in single product in dropdowny ?>

                              {{-- @endforeach --}} 



                              <!--pager extra end-->
                                </div><!--added to repeat-->
                              @endforeach
                              @else
                              <div class="noResult">No Product found</div>
                              @endif
                            <!-- </div> -->
               
               <!-- new adde code to show variants on home page end-->




               
              
               
               
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
  	<section class="product-items-slider py-2">
  		
        <div class="card px-5">
            <div class="section-header">
            	
               <h5 class="heading-design-h5"><a href="@if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif" class="text-secondary">{{$cats->title}}<span class="badge badge-primary"></span></a>
               	@if(count($cats->subcategory) > 5)
                  <a class="float-right text-secondary" href="@if(isset($cats->cat_id)) {{ url('all-products?id='.$cats->cat_id) }} @endif">View All</a>
               </h5>
               @endif
            </div>
            
         
            <div class="owl-carousel owl-carousel-featured">
              <!--combo old subcat beg-->
             @foreach($cats->subcategory as $subcategory)
            	  <div class="item" style="display:none;">

                  	<div class="product pext" id="productid">
	                    <a href=" @if(isset($subcategory->cat_id))  {{url('/all-products?id='.$subcategory->cat_id)}} @endif">
	                        <div class="product-header">
	                        	
	                           	<span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                           	<img class="img-fluid" src=" {{ url('/admin-panel/'.$subcategory->image)}}">
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
               <!-- combo cat end-->
               <!-- new adde code to show variants on home page beg line no-256-->
                
                  <!--  <div class="row no-gutters paginateaz 1az" id="cat_prouct_list"> -->
                  <!--  <div class="product pext no-gutters paginateaz 1az" id="cat_prouct_list"> -->
                                          

                            <?php //@if(!empty($productarray)) ?>
                              @if(isset($catproductarrays[$cats->cat_id]))


                            <?php   //@foreach($productarray as $k=>$v) //old ?>
                              @foreach($catproductarrays[$cats->cat_id] as $k=>$v)

                              {{-- @foreach($v as $k1=>$v1) --}}  
                              <?php 

                              $visibile=1;
                              $click_event='';
                              $stock_text='';
                              if(isset($v[0]->stock) && $v[0]->stock == 0 || empty($v[0]->stock)){
                                $visibile=0.5;
                                $stock_text='Out of Stock';
                                $click_event='none';
                                $btndisabled='disabled';

                              }
                              $qtyaddedtocart='';
                              $btndisabled='';
                              if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$v[0]->varient_id])){

                                $qtyaddedtocart=session()->get('footer_cart_qty')[$v[0]->varient_id];
                                if(!empty($qtyaddedtocart) && $qtyaddedtocart == $v[0]->stock){
                                  //dd('here');
                                  $visibile=0.5;
                                  $stock_text='Out of Stock';
                                  $click_event='none';
                                  $btndisabled='disabled';

                                }
                              }


                                                       #added new code to show mrp,price as it is n calculate discount from mrp n price beg
                              $price_after_discount=$v[0]->price;

                              $discount=(($v[0]->mrp - $v[0]->price)/$v[0]->mrp)*100;
                                                       #added new code to show mrp,price as it is n calculate discount from mrp n price end


                              ?>
                              <div class="item"><!--added to repeat item-->

                              <div class="col-md-4not all cpid{{$v[0]->cid}} itemsaz stock{{$v[0]->varient_id}}" id="cpid{{$v[0]->cid}}">

                                {{-- hidden fields to get out of stock beg --}}
                                <input type="hidden" name="qtyadded" class="qtyadded{{$v[0]->product_id}}" value="0">
                                <input type="hidden" name="productstock" value="{{$v[0]->stock ?? 0}}" class="productstock{{$v[0]->product_id}}">
                                {{--  hidden fields to get out of stock beg --}}

                                <div class="product" id="productid{{$v[0]->product_id}}">
                                  <p class="stock" id="stockmessage{{$v[0]->product_id}}" style="color:red; text-align: center;font-weight: bold;">{{$stock_text}}</p>
                                  <a href="{{url('/product-detail/'.$v[0]->varient_id)}}">
                                    <div class="product-header" style="opacity:{{$visibile}}; pointer-events:{{$click_event}}">
                                      @if($discount > 0)
                                      <span class="badge badge-success" id="product_discount{{$v[0]->product_id}}">{{round($discount,2)}}% OFF</span>
                                      @endif
                                      <img class="img-fluid" src=" {{url('/admin-panel/'.$v[0]->product_image)}}" alt=""  id="varient_img{{$v[0]->product_id}}">
                                      <!-- <span class="veg text-success mdi mdi-circle"></span> -->
                                    </div>
                                  </a>

                                  <div class="product-body">

                                    <h5>{{$v[0]->product_name}}</h5>
                                    <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="availability{{$v[0]->product_id}}">{{$v[0]->quantity}} {{$v[0]->unit}}</span></h6>
                                  </div>

                                  <div class="product-footer">
                                    <!--added extra cod for variant beg-->

                                    <!--added for varient beg-->
                                    <div class="varient_product" style="">
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


                                          <!--added code to change span to dropdown end-->
                                          @endforeach
                                          @endif
                                        </select>
                                      </div><!--selct div end-->
                                    </div>

                                    <!--added for varient end -->

                                    <!--added extra cod for variant end-->



                                    <button type="button" class="btn btn-secondary btn-sm float-right btn-cart btnidentity{{$v[0]->varient_id}}" onclick="addtocart('{{$v[0]->varient_id}}','{{$v[0]->product_id}}')" id="addtocart{{$v[0]->varient_id}}" {{$btndisabled}}>
                                      <i class="mdi mdi-cart-outline"></i>

                                      Add To Cart

                                    </button>
                                    <p class="offer-price mb-0">₹<span id="variant_price{{$v[0]->product_id}}">{{$price_after_discount}} </span>
                                      <i class="mdi mdi-tag-outline"></i><br>
                                      <span class="regular-price" id="product_mrp{{$v[0]->product_id}}">@if($discount > 0) ₹{{$v[0]->mrp}} @endif</span>
                                    </p>
                                  </div>

                                </div>
                              </div>
                              <?php //} //for one time iteration to stop variant duplicacy.i.e all variant should show in single product in dropdowny ?>

                              {{-- @endforeach --}} 



                              <!--pager extra end-->
                                </div><!--added to repeat-->
                              @endforeach
                              @else
                              <div class="noResult">No Product found</div>
                              @endif
                            <!-- </div> -->
               
               <!-- new adde code to show variants on home page end-->



               
              
               
               
            </div>
        </div>
      
  	</section>
  	
  	@endforeach
  	@endif
	<!--added code for combo end-->
	
  @foreach($cat as $cats) 
 @if(isset($cats->subcategory) && count($cats->subcategory) > 0)
 
  	<section class="product-items-slider py-2">
  		@if(isset($cats->subcategory) && count($cats->subcategory) > 0 && $cats->title != 'offers' && $cats->title != 'combo' && $cats->title != 'Combo' && $cats->title != 'Offers')
        <div class="card px-5">
            <div class="section-header">
              
               <h5 class="heading-design-h5"><a href="@if(isset($cats->cat_id)){{ url('all-products?id='.$cats->cat_id) }} @endif" class="text-secondary">{{$cats->title}}<span class="badge badge-primary"></span></a>
               	 @if(count($cats->subcategory) > 5)
                  <a class="float-right text-secondary" href=" @if(isset($cats->cat_id)) {{ url('all-products?id='.$cats->cat_id) }} @endif ">View All</a>
               </h5>
               @endif
            </div>
            
          
            <div class="owl-carousel owl-carousel-featured">
              <!--old subcategory beg-->
             @foreach($cats->subcategory as $subcategory)
            	  <div class="item">

                  	<div class="product pext main_parent_cat" id="productid" style="display:none;">
	                    <a href=" @if(isset($subcategory->cat_id))  {{url('/all-products?id='.$subcategory->cat_id)}} @endif">
	                        <div class="product-header">
	                        	
	                           	<span class="badge badge-success" id="product_discoun" style="display: none;"> % OFF</span>
	                           
	                   <img class="img-fluid" src="{{url('/admin-panel/'.$subcategory->image)}} ">
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
               <!--old subcategory end-->
               <!-- new adde code to show variants on home page beg line no-256-->
               	
               		<!--  <div class="row no-gutters paginateaz 1az" id="cat_prouct_list"> -->
               		<!--  <div class="product pext no-gutters paginateaz 1az" id="cat_prouct_list"> -->
               		                        

               							<?php	//@if(!empty($productarray)) ?>
                              @if(isset($catproductarrays[$cats->cat_id]))


               							<?php 	//@foreach($productarray as $k=>$v) //old ?>
                              @foreach($catproductarrays[$cats->cat_id] as $k=>$v)

               								{{-- @foreach($v as $k1=>$v1) --}}	
               								<?php 

               								$visibile=1;
               								$click_event='';
               								$stock_text='';
               								if(isset($v[0]->stock) && $v[0]->stock == 0 || empty($v[0]->stock)){
               									$visibile=0.5;
               									$stock_text='Out of Stock';
               									$click_event='none';
               									$btndisabled='disabled';

               								}
               								$qtyaddedtocart='';
               								$btndisabled='';
               								if(session()->has('footer_cart_qty') && isset(session()->get('footer_cart_qty')[$v[0]->varient_id])){

               									$qtyaddedtocart=session()->get('footer_cart_qty')[$v[0]->varient_id];
               									if(!empty($qtyaddedtocart) && $qtyaddedtocart == $v[0]->stock){
               										//dd('here');
               										$visibile=0.5;
               										$stock_text='Out of Stock';
               										$click_event='none';
               										$btndisabled='disabled';

               									}
               								}


               				                                 #added new code to show mrp,price as it is n calculate discount from mrp n price beg
               								$price_after_discount=$v[0]->price;

               								$discount=(($v[0]->mrp - $v[0]->price)/$v[0]->mrp)*100;
               				                                 #added new code to show mrp,price as it is n calculate discount from mrp n price end


               								?>
               								<div class="item"><!--added to repeat item-->

               								<div class="col-md-4not all cpid{{$v[0]->cid}} itemsaz stock{{$v[0]->varient_id}}" id="cpid{{$v[0]->cid}}">

               									{{-- hidden fields to get out of stock beg --}}
               									<input type="hidden" name="qtyadded" class="qtyadded{{$v[0]->product_id}}" value="0">
               									<input type="hidden" name="productstock" value="{{$v[0]->stock ?? 0}}" class="productstock{{$v[0]->product_id}}">
               									{{--  hidden fields to get out of stock beg --}}

               									<div class="product" id="productid{{$v[0]->product_id}}">
               										<p class="stock" id="stockmessage{{$v[0]->product_id}}" style="color:red; text-align: center;font-weight: bold;">{{$stock_text}}</p>
               										<a href="{{url('/product-detail/'.$v[0]->varient_id)}}">
               											<div class="product-header" style="opacity:{{$visibile}}; pointer-events:{{$click_event}}">
               												@if($discount > 0)
               												<span class="badge badge-success" id="product_discount{{$v[0]->product_id}}">{{round($discount,2)}}% OFF</span>
               												@endif
               												<img class="img-fluid" src=" {{url('/admin-panel/'.$v[0]->product_image)}}" alt=""  id="varient_img{{$v[0]->product_id}}">
               												<!-- <span class="veg text-success mdi mdi-circle"></span> -->
               											</div>
               										</a>

               										<div class="product-body">

               											<h5>{{$v[0]->product_name}}</h5>
               											<h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - <span id="availability{{$v[0]->product_id}}">{{$v[0]->quantity}} {{$v[0]->unit}}</span></h6>
               										</div>

               										<div class="product-footer">
               											<!--added extra cod for variant beg-->

               											<!--added for varient beg-->
               											<div class="varient_product" style="">
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


               														<!--added code to change span to dropdown end-->
               														@endforeach
               														@endif
               													</select>
               												</div><!--selct div end-->
               											</div>

               											<!--added for varient end -->

               											<!--added extra cod for variant end-->



               											<button type="button" class="btn btn-secondary btn-sm float-right btn-cart btnidentity{{$v[0]->varient_id}}" onclick="addtocart('{{$v[0]->varient_id}}','{{$v[0]->product_id}}')" id="addtocart{{$v[0]->varient_id}}" {{$btndisabled}}>
               												<i class="mdi mdi-cart-outline"></i>

               												Add To Cart

               											</button>
               											<p class="offer-price mb-0">₹<span id="variant_price{{$v[0]->product_id}}">{{$price_after_discount}} </span>
               												<i class="mdi mdi-tag-outline"></i><br>
               												<span class="regular-price" id="product_mrp{{$v[0]->product_id}}">@if($discount > 0) ₹{{$v[0]->mrp}} @endif</span>
               											</p>
               										</div>

               									</div>
               								</div>
               								<?php //} //for one time iteration to stop variant duplicacy.i.e all variant should show in single product in dropdowny ?>

               								{{-- @endforeach --}} 



               								<!--pager extra end-->
               									</div><!--added to repeat-->
               								@endforeach
               								@else
               								<div class="noResult">No Product found</div>
               								@endif
               							<!-- </div> -->
               
               <!-- new adde code to show variants on home page end-->

               
              
               
               
            </div>
        </div>
        @endif
  	</section>
  	@endif
  	@endforeach
  		

    	<section class="product-items-slider py-2">
        <div class="card px-5">
            <div class="section-header">
               	<h5 class="heading-design-h5">Best Offers View
               		<!-- <span class="badge badge-info">20% OFF</span> -->
                  	<a class="float-right text-secondary" href="{{ url('/all-products?sortorder=discounthl') }}">View All</a>
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
                                       
		                                 //$price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
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
	                        	@if(isset($discount) && $discount > 0)
	                           	<span class="badge badge-success" id="product_discount{{$product->product_id}}">{{round($product->discount_percentage)}}% OFF</span>
	                           	@endif
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
								    
								    	<select class="form-control variantchange selpid{{$product->product_id}}" onchange="getvarientdata('{{$product->varient_id}}','{{$product->product_id}}')">
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
	                          <button type="button" class="btn btn-secondary btn-sm float-right"  onclick="addtocart('<?php echo $product->varient_id; ?>','<?php echo $product->product_id; ?>')" id="addtocart{{$product->product_id}}" ssss>
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

  	<section class="shop-list pt-3">
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
				                                 	<img src=" @if(file_exists($cats->image)) {{ url($cats->image) }} @else {{url('/assets/website/img/logo.png')}} @endif" class="img-fluid max-width-100">
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
				                              		<img src=" @if(file_exists($subcategory->image)) {{ url($subcategory->image) }} @else {{url('/assets/website/img/logo.png')}} @endif" class="img-fluid max-width-100 m-auto">
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
        <div class="card px-5">
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