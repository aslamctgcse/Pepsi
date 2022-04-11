@extends('store.layout.app')

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
                  <h4 class="card-title">Add Store Product</h4>
                  
                </div>
                <div class="card-body" style="border-bottom: 2px solid grey;margin-bottom: 15px">
                  <div class="row">
                      <div class="col-md-12">
                        <h3>Bulk Products</h3>
                      </div>
                    </div>  
                  <form class="forms-sample" action="{{route('st_addBulkProduct')}}" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Product CSV to Upload*</label>
                          <input type="file"name="products_csv" onchange='triggerValidation(this)' class="form-control fmext file" required accept=".csv" >
                         <small id="csverror" style="display:none;color:red;">Please Upload only CSV file</small>
						</div>
                      </div>
                      <div class="col-md-2 mt-3">
                         <button type="submit" class="btn btn-primary pull-center">Submit</button>
                      </div>
                      
                      <div class="col-md-4 mt-3">
                        <a href="{{ asset('assets/uploads/import_products_template.csv') }}" class="btn btn-success pull-right" download><i class="fa fa-download"></i>&nbsp;&nbsp;Download CSV File Template</a>
                      </div>

                    </div>
                     <a href="{{route('st_product_list')}}" class="btn">Close</a>
                    <div class="clearfix"></div>
                  </form>
                </div> 

                <div class="card-body">
                  <form class="forms-sample" action="{{route('st_addNewProduct')}}" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}
                    <div class="row">
                      <div class="col-md-12">
                        <h3>Single Product</h3>
                      </div>
                    </div>    

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Category*</label>
                          <!-- <select name="cat_id" class="form-control" required> --><!-- old -->
                          <select name="cat_id" class="form-control catfilter" required>
                              <option disabled selected value="">Select Category</option>
                              @foreach($category as $categorys)
                              
        		          	<option value="{{$categorys->cat_id}}">@if($categorys->level==1)-@endif @if($categorys->level==2)--@endif {{$categorys->title}}</option>
        		              @endforeach
                              
                          </select>
                        </div>
                      </div>

                      <div class="col-md-6 mt-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Product Name*</label>
                          <input type="text" name="product_name" class="form-control" required>
                        </div>
                      </div>
                    </div>

                    <div class="row">

                      <div class="col-md-6">
                        <div class="form-group" style="margin-top: 0px;">
                          <label class="bmd-label-floating">Product Synonyms Name</label>
                          <select class="form-control select2tags" name="product_synonyms_name[]" multiple="multiple">
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6 mt-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Quantity*</label>
                          <input type="number" min="0" name="quantity" class="form-control" required step=".01">
                        </div>
                      </div>
                    
                      
                    </div>
                    
                    <div class="row">
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Unit (G/KG/Ltrs/Ml)*</label>
                          <input type="text" name="unit" class="form-control" pattern="[A-Za-z]{1-10}" title="KG/G/Ltrs/Ml etc" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">MRP*</label>
                          <input type="number" step="0.01" name="mrp" id="mrp" class="form-control" required>
                        </div>
                      </div>
                    
                      
                    </div>

                    <div class="row">
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Discount Percentage*</label>
                          <input type="number" step="0.01" id="discount_percentage" name="discount_percentage" class="form-control" required  max="100">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Price*</label>
                          <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                        </div>
                      </div>
                   
                      
                    </div>
                    
                     <div class="row">
                      <div class="col-md-6">
                        <div class="form">
                          <label class="bmd-label-floating">Product Image*</label>
                          <input type="file"name="product_image" class="form-control fmext file" required accept="image/jpg, image/jpeg, image/png" >
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Description*</label>
                          <textarea type="text" name="description" class="form-control" required></textarea>
                        </div>
                      </div>
                    </div>


                    <button type="submit" class="btn btn-primary pull-center">Submit</button>
                    <a href="{{route('st_product_list')}}" class="btn">Close</a>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
			</div>
          </div>
 <script src="{{ url('assets/js/core/jquery.min.js') }}"></script>
 <script>
  var regex = new RegExp("(.*?)\.(csv)$");

function triggerValidation(el) {
  if (!(regex.test(el.value.toLowerCase()))) {
    el.value = '';
	$("#csverror").css({
            "display": "block",
        });
    
  }else
  {
	  $("#csverror").css({
            "display": "none",
        });
  }
}
 </script>
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
  //image validation beg 
   function imgValidation(evt) { 

        var fileInput =  document.getElementsByClassName("file"); 
        //alert(fileInput);
          
        var filePath = fileInput.value; 
       
      
        // Allowing file type 
        var allowedExtensions = /(\.png|\.jpeg|\.jpg)$/i; 
          
        if (!allowedExtensions.exec(filePath)) { 
            alert('Invalid file type, please select file type of .png, .jpeg and .jpg'); 
            fileInput.value = ''; 
            return false; 
        }  
        if(fileInput.files[0].size > 2097152){
           alert("File is too big!");
           fileInput.value = "";
        };
    } 
  //image validation end
  //script for discount upto two decimal place beg 
$('#discount_percentage').blur(function(){
  //alert('hghg');
    this.value = parseFloat(this.value).toFixed(2);

});
$('#price').blur(function(){
  //alert('hghg');
    this.value = parseFloat(this.value).toFixed(2);

});
    
  </script>         
@endsection




