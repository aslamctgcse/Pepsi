@extends('admin.layout.app')

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
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Edit Product</h4>
                </div>
                <div class="card-body">
                  <form class="forms-sample" action="{{route('UpdateProduct', $product->product_id)}}" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Category*</label>
                          <select name="cat_id" class="form-control" required>
                              <option disabled selected>Select Category</option>
                              @foreach($category as $categorys)
                              
                              <option value="{{$categorys->cat_id}}" {{ $categorys->cat_id == $product->cat_id ? 'selected="selected"' : '' }}>@if($categorys->level==1)-@endif @if($categorys->level==2)--@endif {{$categorys->title}}</option>
                              @endforeach
                              
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Store*</label>
                          <!-- <select name="cat_id" class="form-control" required> --><!-- old -->
                          <select name="store_id" class="form-control catfilter" required>
                              <option disabled selected value="">Select Store</option>
                              @foreach($stores as $store)
                              
                        <option value="{{$store->store_id}}" {{ $store->store_id == $product->product_store_id ? 'selected="selected"' : '' }}>{{$store->store_name}}</option>
                          @endforeach
                              
                          </select>
                        </div>
                      </div>
                   
                      
                    </div>

                    <div class="row">
                      <div class="col-md-6 mt-5">
                        <div class="form-group">
                          <label class="bmd-label-floating">Product_name*</label>
                          <input type="text" value="{{$product->product_name}}" name="product_name" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-6  mt-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Product Synonyms Name</label>
                          <select class="form-control select2tags" name="product_synonyms_name[]" multiple="multiple">
                            @foreach($productSynonymsNames as $productSynonymsName)
                              <option value="{{ $productSynonymsName->id ?? '' }}" selected="selected">{{ $productSynonymsName->name ?? '' }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                   
                      
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 mt-2">
                        <div class="form-group">
                          <label class="bmd-label-floating">Quantity*</label>
                          <input type="number" name="quantity" class="form-control" value="{{$product->quantity}}" required>
                        </div>
                      </div>
                      <div class="col-md-6  mt-2">
                        <div class="form-group">
                          <label class="bmd-label-floating">Unit (G/KG/Ltrs/Ml)*</label>
                          <input type="text" name="unit" class="form-control" pattern="^[A-Za-z]{1-10}$" title="KG/G/Ltrs/Ml etc" value="{{$product->unit}}" required>
                        </div>
                      </div>
                   
                    </div>
                    <img src="{{url($product->product_image)}}" alt="image" name="old_image" style="width:100px;height:100px; border-radius:50%">

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form">
                          <label class="bmd-label-floating">Product Image*</label>
                          <input type="file"name="product_image" class="form-control" accept="image/jpg, image/jpeg, image/png">
                        </div>
                      </div>
                      <div class="col-md-6 mt-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">MRP*</label>
                          <input type="number" step="0.01" name="mrp" id="mrp" class="form-control" value="{{$product->mrp}}" required>
                        </div>
                      </div>
                      
                    
                    </div>

                    
                     <div class="row">
                      <div class="col-md-6 mt-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Discount Percentage*</label>
                          <input type="number" step="0.01" id="discount_percentage" name="discount_percentage" value="{{$product->discount_percentage}}" class="form-control" required max="100">
                        </div>
                      </div>
                      <div class="col-md-6 mt-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Price*</label>
                          <input type="number" step="0.01" name="price" id="price" value="{{$product->price}}" class="form-control" required>
                        </div>
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Description*</label>
                          <textarea type="text" name="description" class="form-control" required>{{ $product->description }}</textarea>
                        </div>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary pull-center">Submit</button>
                     <a href="{{route('productlist')}}" class="btn">Close</a>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
			</div>
          </div>
@endsection

@section('javascript')
  <script type="text/javascript">
    $(document).on('change', 'input#discount_percentage', function(e) {
        e.preventDefault();

        var discountPercentage = parseFloat($(this).val());
        var mrp = parseFloat($('input#mrp').val());

        const amount = (mrp * discountPercentage) / 100;
        const actualAmount = parseFloat(mrp) - parseFloat(amount);

        $('input#price').val(actualAmount.toFixed(2));

    });

    $(document).on('change', 'input#mrp', function(e) {
        e.preventDefault();

        var mrp = parseFloat($(this).val());
        var discountPercentage = parseFloat($('input#discount_percentage').val());

        const amount = (mrp * discountPercentage) / 100;
        const actualAmount = parseFloat(mrp) - parseFloat(amount);

        $('input#price').val(actualAmount.toFixed(2));

    });
    //script for discount upto two decimal place beg 
$('#discount_percentage').blur(function(){
  //alert('hghg');
    this.value = parseFloat(this.value).toFixed(2);

});
$('#price').blur(function(){
  //alert('hghg');
    this.value = parseFloat(this.value).toFixed(2);

});
    
  //script for discount upto two decimal place end
  //added code for select search
  // ===========Select2============
    $('select').select2();
  </script>
@stop




