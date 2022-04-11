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

       <label for="exampleInputPassword1">Price per Kg</label>

       <input type="text" class="form-control" id="exampleInputPassword1" placeholder="price" name="productprice" value="{{ $product->pprice ?? '' }}">

       

     </div>

     <div class="form-group">

       <label for="exampleInputPassword1">Product Image</label>

       

       <input type="file" class="form-control" name="productimage">

       <img src="{{ url($product->pimage ?? '') }}" width="75px" height="75px">

     </div>
     
    

     <!-- <button type="submit" class="btn btn-primary">Submit</button> -->

     <input type="submit"  name="productsubmit" class="btn btn-primary"/>

   </form>

   </div>

   <div class="col-md-2"></div>

</div>



<!--added code for form end--->





	@endsection