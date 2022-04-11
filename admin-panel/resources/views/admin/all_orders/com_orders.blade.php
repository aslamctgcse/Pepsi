@extends('admin.layout.app')
<style>
   .rating1 {

    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    max-width: 138px;
}

.rating1>input {
    display: none
}

.rating1>label {
    position: relative;
    width: 28px;
    font-size: 30px;
    color: #FFD600;
    cursor: pointer
}

.rating1>label::before {
    content: "\2605";
    position: absolute;
    opacity: 0
}

.rating1>label:hover:before,
.rating1>label:hover~label:before {
    /*opacity: 1 !important*/
}

.rating1>input:checked~label:before {
    opacity: 1
}

.rating1:hover>input:checked~label:before {
    /*opacity: 0.4*/
}
.ratingHeader{
      border-bottom: 1px solid grey;
    padding-bottom: 5px;
    margin-bottom: 20px;
}
.ratingTitleR{
  padding-top: 10px;
}
.ratingTitle,.ratingTitleR{
  font-weight: 500;
}
.ratingTime{
  margin-bottom: 0px;
    padding: 10px;
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
      <h4 class="card-title ">Completed Orders</h4>
    </div>
<table class="table">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Order Id</th>
            <th>Price</th>
            <th>User</th>
            <th>Delivery Date</th>
            <th>Order Details</th>
            <!-- <th>Order Reviews</th> -->
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
             <td>{{$ords->delivery_date}}</td>
            <!-- <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1{{$ords->cart_id}}">Details</button></td> -->
            <td>
              <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1{{$ords->cart_id}}">Details</button>
              <a href="https://alobhaitsolution.com/pickdash/admin-panel/city" class="btn btn-primary ml-auto" style="width:10%;float:right;padding: 3px 0px 3px 0px;"><i class="material-icons">add</i>Add City<div class="ripple-container"></div></a> -->
              <a href="{{route('admin_sub_com_orders',$ords->cart_id)}}" rel="tooltip" class="btn btn-success">
                    View Detail
                </a>
            </td>
            <!-- <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal3{{$ords->cart_id}}">View Review</button></td> -->
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
<div class="pagination justify-content-end" align="right" style="width:100%;float:right !important">{{--$ord->links()--}}</div>
</div>
</div>
</div>
</div>
<div>
</div>


<!--/////////details model//////////-->
@foreach($ord as $ords)
        <div class="modal fade" id="exampleModal1{{$ords->cart_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        </tr>
                      </thead>
                      
                      <tbody>
                      @if(count($details)>0)
                        @php $i=1; @endphp
                                      
                          <tr>             
                        @foreach($details as $detailss)
                          @if($detailss->cart_id==$ords->cart_id && $detailss->cancel_status==0)
                            <td><p><img style="width:25px;height:25px; border-radius:50%" src="{{url($detailss->varient_image)}}" alt="$detailss->product_name">  {{$detailss->product_name}}({{$detailss->quantity}}{{$detailss->unit}})</p>
                            </td>
                            <td>{{$detailss->qty}}</td>
                            <td> 
                            <p><span style="color:grey">{{$detailss->price}}</span></p>
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


 <!--/////////details model//////////-->
@foreach($ord as $ords)
        <div class="modal fade" id="exampleModal3{{$ords->cart_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="text-align:center;width:100%;font-weight: 500">Order Rating & Reviews</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">

                      
                      <div class="row ">


                          @if(count($comments)>0)

                        //$checkkk=0;
                        @foreach($comments as $comment)
                          @if($comment->order_cart_id==$ords->cart_id)
                            <div class="col-md-3">
                               <p class="ratingTitleR">Rating :</p>
                            </div>
                            <div class="col-md-9">
                             <div class="rating1" id="ratingIdValue"> 
                              <input type="radio" name="rating" value="5" id="5" @if($comment->order_rating=='5') checked="true" @endif disabled><label for="5">☆</label> 
                              <input type="radio" name="rating" value="4" id="4" @if($comment->order_rating=='4') checked="true" @endif disabled><label for="4">☆</label> 
                              <input type="radio" name="rating" value="3" id="3" @if($comment->order_rating=='3') checked="true" @endif disabled><label for="3">☆</label> 
                              <input type="radio" name="rating" value="2" id="2" @if($comment->order_rating=='2') checked="true" @endif disabled><label for="2">☆</label> 
                              <input type="radio" name="rating" value="1" id="1" @if($comment->order_rating=='1') checked="true" @endif disabled><label for="1">☆</label>
                            </div>
                            </div>
                            <div class="col-md-3">
                               <p class="ratingTitle">Review :</p>
                            </div>
                            <div class="col-md-9" >
                               <p >{{$comment->order_comment}}</p>
                            </div>

                           @else
                            <!-- <div class="col-md-12">
                              <p style="text-align:center;">No review found</p>
                            </div> -->
                            @endif
                            @endforeach
                          @else
                            <div class="col-md-12">
                              <p>No review found</p>
                            </div>
                          @endif
                        </div>


              </div>
            </div>
          </div>
        </div>
 @endforeach


    @endsection
</div>