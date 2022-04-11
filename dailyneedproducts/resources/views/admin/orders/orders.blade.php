@extends('layouts.app1')

@section('content')
<?php //dd($products);?>
<style>
#collapseTwo{
   height: :350px !important;
   display:block !important;
}
section.section-padding.footer.bg-white.border-top {
    display: none !important;
}
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
<div class="container">
    <h2><a href="{{ url('/add-product') }}" class="btn btn-success">Add New Product </a></h2>
    <h2><a href="{{ route('orders-export') }}" class="btn btn-success">Export In Excel </a></h2>
<div class="row">
  <!-- <div class="col-md-1"></div> -->
  <div class="col-md-12">
    <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Address</th>
      <th scope="col">Order details</th>
      <th scope="col">Total</th>
    </tr>
  </thead>
  <tbody>
    <?php 
     $i=1;
    foreach($userdetail as $userdetails){ 
     
        //dd($userdetails);
      ?>
    <tr>
      <th scope="row">{{$i}}</th>
      
      <td>{{$userdetails->customer_name}}</td>
      <td>{{$userdetails->customer_email}}</td>
      <td>{{$userdetails->customer_address}}</td>
      <td>
         <?php foreach($orderdetail[$userdetails->oid] as $k=>$v){ ?>
            <p><span>product name:<br> {{$v->product_name}}</span></p>
            <p><span>price: {{$v->product_price}}</span><span>qty: {{$v->product_qty}}</span></p>
         <?php } ?>



      </td>
      <td>{{$userdetails->total}}</td>
    </tr>
    <?php
     $i++;
     }
    ?>
   
  </tbody>
</table>
  </div>
  <!-- <div class="col-md-1"></div> -->
</div>
</div>
<!--added code for form end--->

<?php //include( 'footer.php') ?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">-->
<script>
	
 //        $(document).ready(function(){
 // alert('hifdvfd');
 //  });
 function addtocart(pid){
 	var pidd=pid;
 	//alert(pidd);
 	$.ajax({

          type: 'get',

         // url: 'http://localhost/blog/public/addtocart',
           url:"{{url('/addtocart')}}/"+pidd,

          //data: {'pid':pidd},  

          success : function(data){

            // $('#category'+value).remove()

             //console.log(data);
             //alert(data);

          }

        });
 }
	</script>
	@endsection