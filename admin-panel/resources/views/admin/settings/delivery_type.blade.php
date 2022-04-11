@extends('admin.layout.app')
<style>
    .btn{
        height:27px !important;
    }
    .material-icons{
        margin-top:0px !important;
        margin-bottom:0px !important;
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
     <a href="{{route('AddDeliveryType')}}" class="btn btn-primary ml-auto" style="width:16%;float:right;padding: 3px 0px 3px 0px;"><i class="material-icons">add</i>Add Delivery Type</a>
   </div>
<div class="col-lg-12">
<div class="card">    
  <div class="card-header card-header-primary">
    <h4 class="card-title ">Delivery Type</h4>
  </div>
  <table class="table">
      <thead>
          <tr>
              <th class="text-center">#</th>
              <th>Delivery Type Name</th>
              <!-- <th>Time</th> -->
              <th>Fee</th>
              <th class="text-center">Status</th>
              <th class="text-right">Action</th>
          </tr>
      </thead>
      <tbody>
             @if(count($delivery_types)>0)
            @php $i=1; @endphp
            @foreach($delivery_types as $deliveryType )
          <tr>
              <td class="text-center">{{$i}}</td>
              <td>{{$deliveryType->delivery_type}}</td>
              <!-- <td>{{$deliveryType->time}}</td> -->
              <td>{{$deliveryType->fee}}</td>
              <td class="td-actions text-center">
                @if($deliveryType->status == 0)
                  <a href="{{ route('deliveryTypeBlock',$deliveryType->id) }}" rel="tooltip" class="btn btn-danger">
                      <i class="material-icons">block</i>Blocked
                  </a>
                @else
                  <a href="{{ route('deliveryTypeUnblock',$deliveryType->id) }}" rel="tooltip" class="btn btn-primary">
                    <i class="material-icons">check</i>Active
                  </a>
                @endif
            </td>
              <td class="td-actions text-right">
                <a href="{{route('EditDeliveryType',$deliveryType->id)}}" rel="tooltip" class="btn btn-success">
                      <i class="material-icons">edit</i>
                  </a>
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
<div class="pagination justify-content-end" align="right" style="width:100%;float:right !important">{{-- $queries->links() --}}</div>
</div>
</div>
</div>
</div>
<div>
    </div>
    @endsection
</div>