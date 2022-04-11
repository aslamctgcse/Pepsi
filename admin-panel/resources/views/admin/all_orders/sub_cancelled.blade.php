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
     .modal-content .dataTables_filter, .modal-content .dataTables_info,.modal-content .dataTables_paginate,.modal-content .dataTables_length, .modal-content .btn-group { 
  display: none!important;
}
.modal-title{
  text-align: center;
  width: 100%;
  font-weight: bold;
}
.modalTableUser{
  margin-left: 0px!important;
  margin-right: 0px!important;
  margin-top: 5px;
}
.modalTableUser p {
  margin-bottom: 7px
} 
.modalTableUser p span{
  font-weight: 500;
  min-width: 93px;
    display: inline-block;
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
      <h4 class="card-title ">Cancelled Sub Orders</h4>
    </div>
<table class="table">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Order Id</th>
            <th>Sub Order Id</th>
            <th>Price</th>
            <th>User</th>
            <th>Store</th>
            <!-- <th>Delivery boy</th> -->
            <th>Delivery Date</th>
            <th>Cancelling Reason</th>
            <th>Cancelling By</th>
            <th>Cart Products</th>
        </tr>
    </thead>
    <tbody>
           @if(count($ord)>0)
          @php $i=1; @endphp
          @foreach($ord as $ords)
          <tr>
            <td class="text-center">{{$i}}</td>
            <td>{{$ords->cart_id}}</td>
            <td>{{$ords->sub_order_cart_id}}</td>
            <!-- <td>{{$ords->total_price}}</td> -->
            <td>{{$ords->total_price_without_delivery_discount}}</td>
            <td>{{$ords->user_name}}<p style="font-size:14px">({{$ords->user_phone}})</p></td>
            <td>{{$ords->store_name}}<p style="font-size:14px">({{$ords->phone_number}})</p></td>
            
            <td>{{$ords->delivery_date}}</td>
            <td>{{$ords->cancelling_reason}}</td>
            <td>
              @if($ords->cancel_by_store==0)
                User
            @else
               Store

             @endif
          </td>
            <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1{{$ords->sub_order_id}}">Details</button></td>
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
        <div class="modal fade"
             id="exampleModal1{{$ords->sub_order_id}}"
             tabindex="-1"
             role="dialog"
             aria-labelledby="exampleModalLabel" 
             aria-hidden="true">
         	  <div class="modal-dialog" role="document">
        		  <div class="modal-content">
        			  <div class="modal-header">
        				  <h5 class="modal-title" id="exampleModalLabel">Order Details (<b>{{$ords->cart_id}}</b>)</h5>
        					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        						<span aria-hidden="true">&times;</span>
        					</button>
        			  </div>
          			<!--//form-->
                <div class="row modalTableUser">
                <div class="col-md-12">
                            <p><span>Name </span>: {{$ords->delivery_fname}}</p>
                            <p><span>Mobile No </span>: {{$ords->delivery_mobile}}</p>
                            <p><span>Delivery </span>: {{$ords->delivery_date}}</p>
                            <p><span>Address </span>: {{$ords->delivery_address}}</p>
                            @if($ords->order_special_instructions)
                            <p><span>Insctruction </span>: {{$ords->order_special_instructions}}</p>
                            @endif

                </div>
              </div>
          			<table class="table table-bordered" id="example2" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    <th>Product Details</th>
                    <th>Order Qty</th>
                    <th>Price</th>
                    </tr>
                  </thead>
                  
                  <tbody>
                      @if(count($ords->pro_details)>0)
                        @php $i=1; @endphp
                                  
                        @foreach($ords->pro_details as $detailss)
                          <tr>             

                              <td><p><img style="width:25px;height:25px; border-radius:50%" src="{{url($detailss->varient_image)}}" alt="$detailss->product_name">  {{$detailss->product_name}}({{$detailss->quantity}}{{$detailss->unit}})</p>
                              </td>
                              <td><p>{{$detailss->qty}}</p></td>
                              <td> 
                              <p><span style="color:grey">{{$detailss->price}}</span></p>
                             </td>
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


@endsection
</div>