@extends('layouts.app1')



@section('content')

<?php //dd($products);?>

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

    <div class="container">

        <h2><a href="{{ route('products') }}" class="btn btn-success">Home Website</a></h2>

        <h2><a href="{{ url('/orders') }}" class="btn btn-success">Orders</a></h2>

        <!--added code for form beg--->

        <div class="row addproduct">

            <div class="col-md-2"></div>

            <div class="col-md-8">

                <form method="post" action="{{url('/add-product')}}" enctype="multipart/form-data">

                    {{csrf_field()}}

                    <div class="form-group">

                        <label for="exampleInputEmail1">Product Name:</label>

                        <input type="text" name="productname" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter product name" required>

                    </div>

                    <div class="form-group">

                        <label for="exampleInputPassword1">Price</label>

                        <input type="text" class="form-control" id="exampleInputPassword1" placeholder="price" name="productprice" required>

                    </div>
                    <div class="form-group">

                        <label for="exampleInputPassword1">Mrp</label>

                        <input type="text" class="form-control" id="exampleInputPassword1" placeholder="mrp" name="mrp" required>

                    </div>
                    <!--unit-->
                    <div class="form-group">
                      <label for="unit">Unit:</label>
                      <select class="form-control" id="sel1" name="unit"> 
                        <option value="">select unit</option>
                        <option value="Kg">Kg</option>
                        <option value="GM">GM</option>
                        <option value="PCS">PCS</option>
                        <option value="PKT">PKT</option>
                        <option value="Lt">Lt</option>
                        
                        
                      </select>
      
                        </div>
                    <!--unit-->
                    <!--qty-->
                    <div class="form-group">

                        <label for="quantity">Quantity</label>

                        <input type="number" class="form-control" id="quantity" placeholder="quantity" name="quantity" required>

                    </div>
                    <!--qty-->

                    

                    <div class="form-group">

                        <label for="exampleInputPassword1">Product Image</label>

                        <input type="file" class="form-control" name="productimage" required>

                    </div>
                    <!--added-->
                   
                      <div class="form-group">
                      <label for="sel1">Category:</label>
                      <select class="form-control" id="sel1" name="combo"> 
                        <option value="combo">combo</option>
                        <option value="offer">offer</option>
                        <option value="daily_breakfast">Daily Breakfast</option>
                        <option value="daily_fresh_fruit">Daily Fresh Fruits</option>
                        <option value="daily_vegg">Daily Vegg</option>
                        <option value="daily_poltry_form">Daily Poultry farm</option>
                        <option value="others">Others</option>
                        
                      </select>
      
                        </div>
                    <!--added-->

            

                    <!-- <button type="submit" class="btn btn-primary">Submit</button> -->

                    <input type="submit"  name="productsubmit" class="btn btn-primary"/>

                </form>

            </div>

            <div class="col-md-2"></div>

        </div>

    </div>

    

<section>

    <div class="container">

        <h2>Product List </h2>

        <table class="table table-strip">

            <tr>

                <th>Product Name</th>
                <th>Combo/Offer</th>

                <th>Product Price</th>
                <th>Unit</th>
                <th>Mrp</th>

                <th>Product Image</th>
                <th>Mark out of Stock</th> 

                <th>Action</th>

            </tr>

            @foreach($productlist as $productlistValue)
            <?php 
            if($productlistValue->combo == 'offer'){
                    $combo='offer';
                }
                if($productlistValue->combo == 'combo'){
                    $combo='combo';
                }
                if($productlistValue->combo == 'others'){
                    $combo='others';
                }
                 if($productlistValue->combo == ''){
                    $combo='d';
                }
                if($productlistValue->combo == 'daily_breakfast'){
                    $combo='daily breakfast';
                }
                if($productlistValue->combo == 'daily_fresh_fruit'){
                    $combo='daily fresh fruit';
                }
                if($productlistValue->combo == 'daily_vegg'){
                    $combo='daily vegg';
                }
                if($productlistValue->combo == 'daily_poltry_form'){
                    $combo='daily poltry form';
                }
                 if($productlistValue->combo == ''){
                    $combo='others';
                }
            ?>

                <tr>

                    <td>{{ $productlistValue->pname ?? '' }}</td>
                    <td>{{ $combo ?? '' }}</td>

                    <td>{{ $productlistValue->pprice ?? '' }}</td>
                     <td>{{ $productlistValue->quantity}}{{$productlistValue->unit}}</td>
                    <td>{{ $productlistValue->pdesc ?? '' }}</td>
                   

                    <td><img src="{{ url($productlistValue->pimage ?? '') }}" width="75px" height="75px"></td>
                    <td>
                        <form method="post" action="{{url('/managestock')}}">
                            {{csrf_field()}}
                            <input type="hidden" name="productid" value="{{$productlistValue->id}}"  />
                            
                            <input type="checkbox" id="vehicle1" name="managestock" value="{{$productlistValue->stock}}" onclick="this.form.submit()" <?php  if($productlistValue->stock == 0) { echo 'checked="checked"';}?>>
                            
                        </form>


                    </td>

                    <td><a href="{{ route('edit-product-form', [$productlistValue->id]) }}" class="btn btn-primary">Edit</a></td>

                    <td><a href="{{ route('delete-product', [$productlistValue->id]) }}" class="btn btn-danger">Delete </a></td>

                </tr>

            @endforeach

        </table>

    </div>

</section>



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