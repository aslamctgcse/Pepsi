@extends('layouts.app1')



@section('content')

<?php //dd($product);?>

<style>

#collapseTwo{

   height: :350px !important;

   display:block !important;

}

/*.container-fluid{

   display:none !important;

}*/

section.section-padding.footer.bg-white.border-top{

   display:none !important;

}

nav.navbar.navbar-light.navbar-expand-lg.bg-faded.osahan-menu {

    display: none !important;

}

</style>

@if(session()->has('msg'))

    <div class="alert alert-success" style="text-align:center">

        {{ session()->get('msg') }}

    </div>

@endif



<!--added code for form beg--->

<div class="row addproduct">

      <div class="col-md-2"></div>

      <div class="col-md-8">

      <form method="post" action="{{route('edit-product', [$product->id])}}" enctype="multipart/form-data">

         {{csrf_field()}}

     <div class="form-group">

       <label for="exampleInputEmail1">Product Name:</label>

       <input type="text" name="productname" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter product name" value="{{ $product->pname ?? '' }}">

       

     </div>

     <div class="form-group">

       <label for="exampleInputPassword1">Price</label>

       <input type="text" class="form-control" id="exampleInputPassword1" placeholder="price" name="productprice" value="{{ $product->pprice ?? '' }}">

       

     </div>
     <div class="form-group">

    <label for="exampleInputPassword1">Mrp</label>

    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="mrp" name="mrp" value="{{ $product->pdesc}}">

</div>
     <!--unit-->
                    <div class="form-group">
                      <label for="unit">Unit:</label>
                      
                        
                        <select class="form-control" id="sel1" name="unit"> 
                        <option value="">select unit</option>
                        <option value="Kg" <?php if($product->unit == 'Kg' ) echo 'selected="selected"' ?>>Kg</option>
                        <option value="GM" <?php if($product->unit == 'GM' ) echo 'selected="selected"' ?>>GM</option>
                        <option value="PCS" <?php if($product->unit == 'PCS' ) echo 'selected="selected"' ?>>PCS</option>
                        <option value="PKT" <?php if($product->unit == 'PKT' ) echo 'selected="selected"' ?>>PKT</option>
                        <option value="Lt" <?php if($product->unit == 'Lt' ) echo 'selected="selected"' ?>>Lt</option>
                        
                        
                      </select>
                      
      
                        </div>
                    <!--unit-->
                    <!--qty-->
                    <div class="form-group">

                        <label for="quantity">Quantity</label>

                        <input type="number" class="form-control" id="quantity" placeholder="quantity" name="quantity"value="{{ $product->quantity}}">

                    </div>
                    <!--qty-->
     

     <div class="form-group">

       <label for="exampleInputPassword1">Product Image</label>

       

       <input type="file" class="form-control" name="productimage">

       <img src="{{ url($product->pimage ?? '') }}" width="75px" height="75px">

     </div>
      <!--added-->
                   
                      <div class="form-group"> 
                      <label for="sel1">Category:</label>
                      <select class="form-control" id="sel1" name="combo">
                        <!-- <option value="combo"  <?php //if($product->combo == 'combo' ) echo 'selected="selected"' ?>>combo</option>
                        <option value="offer" <?php //if($product->combo == 'offer' ) echo 'selected="selected"' ?>>offer</option>
                        <option value="others" <?php //if($product->combo == 'others' ) echo 'selected="selected"' ?>>others</option> -->
                      
                        <option value="combo" <?php if($product->combo == 'combo' ) echo 'selected="selected"' ?>>combo</option>
                        <option value="offer" <?php if($product->combo == 'offer' ) echo 'selected="selected"' ?>>offer</option>  
                        <option value="daily_breakfast" <?php if($product->combo == 'daily_breakfast' ) echo 'selected="selected"' ?>>Daily Breakfast</option>
                        <option value="daily_fresh_fruit" <?php if($product->combo == 'daily_fresh_fruit' ) echo 'selected="selected"' ?>>Daily Fresh Fruits</option>
                        <option value="daily_vegg" <?php if($product->combo == 'daily_vegg' ) echo 'selected="selected"' ?>>Daily Vegg</option>
                        <option value="daily_poltry_form" <?php if($product->combo == 'daily_poltry_form' ) echo 'selected="selected"' ?>>Daily Poultry farm</option>
                        <option value="others" <?php if($product->combo == 'others' ) echo 'selected="selected"' ?>> Others</option>
                        
                      </select>
      
                        </div>
                    <!--added-->
    

     <!-- <button type="submit" class="btn btn-primary">Submit</button> -->

     <input type="submit"  name="productsubmit" class="btn btn-primary"/>

   </form>

   </div>

   <div class="col-md-2"></div>

</div>



<!--added code for form end--->





	@endsection