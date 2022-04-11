@extends('admin.layout.app')
<style>
    .rejectBtnCss{
            height: 41px;
            font-size: 15px!important;
    }
    .textareaCss{
      width: 100%;
      min-height: 80px;
    }
</style>

@section ('content')
<div class="container-fluid">
 <div class="row">
<div class="col-lg-12">
    @if (session()->has('success'))
   <div class="alert alert-success">
    @if(is_array(session()->get('success')))
            <ul>
                @foreach (session()->get('success') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
            @else
                {{ session()->get('success') }}
            @endif
        </div>
    @endif
     @if (count($errors) > 0)
      @if($errors->any())
        <div class="alert alert-danger" role="alert">
          {{$errors->first()}}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
      @endif
    @endif
</div>
<div class="col-lg-12">
<div class="card">    
<div class="card-header card-header-primary">
      <h4 class="card-title ">Pending Orders</h4>
    </div>
<table class="table">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Order Id</th>
            <th>Price</th>
            <th>User Name(Phone)</th>
            <th>Delivery Date</th>
            <th>Order Details</th>
            <!-- <th>Confirmation</th> -->
        </tr>
    </thead>
    <tbody>
           @if(count($ord)>0)
          @php $i=1; @endphp
          @foreach($ord as $ords)
        <tr>
            <td class="text-center">{{$i}}</td>
            <td>{{$ords->cart_id}}</td>
            <td>{{$ords->total_price}}</td>
            <td>{{$ords->user_name}}<p style="font-size:14px">({{$ords->user_phone}})</p></td>
             <td>
              {{$ords->delivery_date}}
              <br>
              
              @if($ords->delivery_type_id=='2')
               <!-- {{$ords->delivery_type}} -->
               (Express Delivery)
              @else
                <!-- {{$ords->time_slot}} -->
              @endif
              
             </td>
            <td>
              <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalD{{$ords->cart_id}}">Details</button> -->
              <a href="{{route('admin_sub_pen_orders',$ords->cart_id)}}" rel="tooltip" class="btn btn-success">
                    View Detail
                </a>
            </td>
            <!-- <td>
              @if($ords->order_status == 'Pending'||$ords->order_status == 'pending')
                <a href="{{route('store_confirm_order1', $ords->cart_id)}}" data-toggle="modal" data-target="#exampleModal1{{$ords->order_id}}" rel="tooltip" class="btn btn-success">
                  Confirm
                </a>
                <button data-href="{{ route('store_reject_order_modal1', $ords->cart_id) }}" 
                        class="btn btn-danger btn-sm btn-modal rejectBtnCss"
                        data-container=".reject-modal{{$ords->order_id}}">Reject</button>

                @endif
                @if($ords->order_status == 'Confirmed'||$ords->order_status == 'confirmed'||$ords->order_status == 'Confirm'||$ords->order_status == 'confirm')
                <p>Confirmed</p>
                @endif
              
            </td> -->
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
<div class="pagination justify-content-end" align="right" style="width:100%;float:right !important">{{-- $ord->links() --}}</div>
</div>
</div>
</div>
</div>
<div>
</div>


<!--/////////details model//////////-->
@foreach($ord as $ords)
        <div class="modal fade" id="exampleModalD{{$ords->cart_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        	<div class="modal-dialog" role="document">
        		<div class="modal-content">
        			<div class="modal-header">
        				<h5 class="modal-title" id="exampleModalLabel">Order Details (<b>{{$ords->cart_id}}</b>)</h5>
        					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        						<span aria-hidden="true">&times;</span>
        					</button>
        			</div>
        			<!--//form-->
        			<table class="table table-bordered" id="example2" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                        <th>Product Details</th>
                        <th>Order Qty</th>
                        <th>Price</th>
                        <th>status</th>
                        </tr>
                      </thead>
                      
                      <tbody>
                      @if(count($details)>0)
                                      @php $i=1; @endphp
                                      
                          <tr>             
                        @foreach($details as $detailss)
                          @if($detailss->cart_id==$ords->cart_id)
                            <td><p><img style="width:25px;height:25px; border-radius:50%" src="{{url($detailss->varient_image)}}" alt="$detailss->product_name">  {{$detailss->product_name}}({{$detailss->quantity}}{{$detailss->unit}})</p>
                            </td>
                            <td>{{$detailss->qty}}</td>
                            <td> 
                            <p><span style="color:grey">{{$detailss->price}}</span></p>
                           </td>
                           <td> 
                            <p>
                              
                                 @if($detailss->cancel_status==1)
                                 <span style="color:grey">
                                  Canceled
                                 </span> 
                                 @else 
                                  <span style="color:grey">
                                  <!-- Confirmed -->
                                </span>
                              @endif
                            </p>
                           </td>
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

        			<!--//form-->
        		</div>
        	</div>
        </div>
 @endforeach

  <!--/////////dboy assign model//////////-->
@foreach($ord as $ords)
        <div class="modal fade" id="exampleModal1{{$ords->order_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delivery Boy Assign (<b>{{$ords->cart_id}}</b>)</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <!--//form-->
              <form class="forms-sample" action="{{route('store_confirm_order1', $ords->cart_id)}}" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}
              <div class="row">
                <div class="col-md-3" align="center"></div>  
                      <div class="col-md-6" align="center">
                        <div class="form-group">
                  <select name="dboy_id" class="form-control" required>
                  <option disabled selected value=''>Select Delivery boy</option>
                  @foreach($nearbydboy as $nearbydboys)
                   <option value="{{$nearbydboys->dboy_id}}">{{$nearbydboys->boy_name}}({{$nearbydboys->distance}} KM away)</option>
                  @endforeach
              </select>
              </div>
              <button type="submit" class="btn btn-primary pull-center">Submit</button>
              </div>
              </div>
                
                    <div class="clearfix"></div>
              </form>
              <!--//form-->
            </div>
          </div>
        </div>
         <div class="modal fade reject-modal{{$ords->order_id}}" data-keyboard="false" tabindex="-1" role="dialog" 
      aria-labelledby="RejectModalLabel">
  </div>
 @endforeach

 


    @endsection
</div>