<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{$title}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body onload="myFunction()">

<div class="container">
               <div class="col-md-12">
                            <p><span>Name </span>: {{$ords->delivery_fname}}</p>
                            <p><span>Mobile No </span>: {{$ords->delivery_mobile}}</p>
                            <p><span>Delivery </span>: {{$ords->delivery_date}}</p>
                            <p><span>Address </span>: {{$ords->delivery_address}}</p>
                            @if($ords->order_special_instructions)
                            <p><span>Insctruction </span>: {{$ords->order_special_instructions}}</p>
                            @endif

                </div>         
  <table class="table table-bordered">
    <thead>
		<tr>
		<th>Product Details</th>
		<th>Order Qty</th>
		<th>Price</th>
		<th>Status</th>
		<!-- <th>I don't have</th> -->
		</tr>
	  </thead>
    <tbody>
                      @if(count($details)>0)
                                      @php $i=1; @endphp
                                      
                          <tr>             
                        @foreach($details as $detailss)
                          @if($detailss->cart_id==$ords->cart_id && $detailss->sub_order_cart_id==$ords->sub_order_cart_id)
    		          	   
                            <td><p><img style="width:25px;height:25px; border-radius:50%" src="{{url($detailss->varient_image)}}" alt="$detailss->product_name">  {{$detailss->product_name}}({{$detailss->quantity}}{{$detailss->unit}})</p>
                            </td>
                            <td><p><span style="color:grey">{{$detailss->qty}}</span></p></td>
                            <td> 
                            <p><span style="color:grey">{{$detailss->price}}</span></p>
                           </td>
                           <td> 
                            <p>
                                @if($detailss->cancel_status==0)
                                <span style="color:green">Confirmed</span>
                                @else
                                <span style="color:red">Removed</span>
                                @endif
                            </p>
                           </td>
                           <!-- <td align="center">
                           <a href="{{route('store_cancel_product', $detailss->store_order_id)}}" rel="tooltip">
                            <i class="material-icons" style="color:red">close</i>
                            </a>
                        </td> -->    
    		          	  @endif
                         </tr>
                            @php $i++; @endphp
                            @endforeach
                          @else
                            <tr>
                              <td>No data found</td>
                            </tr>
                                  @endif
                                   
                      </tbody>
  </table>
</div>
<script>
function myFunction() {
  window.print();
}
</script>
</body>
</html>













