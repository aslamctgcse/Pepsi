@extends('store.layout.app')


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
     <a href="{{route('st_addProduct')}}" class="btn btn-primary ml-auto" style="float:right;padding-top: 3px;"><i class="material-icons">add</i>Add Product</a>
</div> 
<div class="col-lg-12">
<div class="card">    
<div class="card-header card-header-primary">
      <h4 class="card-title ">Product List</h4>
    </div>
<table class="table tableext">
    <thead>
        <tr>
            <th class="text-center">#</th>
             <th>Image</th>
            <th>Product</th>
            <!-- <th>Store name</th> -->
             <th>MRP</th>
              <th>Price</th>
               <th>Quantity</th>
                <th>Unit Type</th>
            <th>Category</th>
            <th class="text-right">Approved/Not Approved</th>
            <th class="text-right">Active/Blocked</th>
           
            <th class="text-center" style="min-width: 150px;">Actions</th>
        </tr>
    </thead>
    <tbody>
           @if(count($storeproducts)>0)
          @php $i=1; @endphp
          @foreach($storeproducts as $products)
        <tr>
            <td class="text-center">{{$i}}</td>
             <td><img src="{{url('/admin-panel/'.$products->product_image)}}" alt="image"  style="width:50px;height:50px; border-radius:50%"/></td>
            <td>{{$products->product_name}}</td>
            <!-- <td>{{$products->storeName}}</td> -->
             <td>{{$products->mrp}}</td>
              <td>{{$products->price}}</td>
               <td>{{$products->quantity}}</td>
                <td>{{$products->unit}}</td>
            <td> {{$products->title}}</td>
            <td class="td-actions text-right">
                @if($products->approved == 0)
                   <p> Not Approved</p>
                @else
                  <p>Approved</p>
                @endif
            </td>
            <td class="td-actions text-right">
                @if($products->status == 0)
                  <a href="{{ route('st_productBlock',$products->product_id ) }}" rel="tooltip" class="btn btn-danger">
                      <i class="material-icons">block</i> Blocked
                  </a>
                @else
                  <a href="{{ route('st_productUnblock',$products->product_id ) }}" rel="tooltip" class="btn btn-primary">
                    <i class="material-icons">check</i> Active
                  </a>
                @endif
            </td>
           
            <td class="td-actions text-right">
                <a href="{{route('st_editProduct',$products->product_id)}}" rel="tooltip" class="btn btn-success">
                    <i class="material-icons">edit</i>
                </a>
                <!-- <a href="{{route('st_varient',$products->product_id)}}" rel="tooltip" class="btn btn-primary">
                    <i class="material-icons">layers</i>
                </a> -->
                <a href="{{route('st_deleteProduct',$products->product_id)}}" rel="tooltip" data-name="product" class="btn btn-danger delete-confirm-event">
                    <i class="material-icons">close</i>
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
<div class="pagination justify-content-end" align="right" style="width:100%;float:right !important">{{-- $product->links() --}}</div>
</div>
</div>
</div>
</div>
<div>
    </div>
    @endsection
</div>