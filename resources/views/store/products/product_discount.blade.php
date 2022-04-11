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
      <div class="col-md-4">
         <div class="card">
            <div class="card-header card-header-primary">
               <h4 class="card-title">List Products</h4> 
            </div>
            <style type="text/css">ul li {list-style: none;} ul li p{display: inline-block;}</style>
            <ul>
               @foreach($storeproducts as $product)
                  <li>
                     <a href="{{route('single_discount_product', $product->product_id)}}">
                        <p>
                        <img src="{{url('/admin-panel/'.$product->product_image)}}" alt="image"  style="width:50px;height:50px; border-radius:50%"/></p>
                        <p class="ml-4">{{$product->product_name}}</p>
                     </a>
                  </li>
               @endforeach
             </ul>
         </div>
      </div>

      @if(isset($singleDiscount))
         <div class="col-md-7">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title">Selected Products : {{$productName->product_name ?? ''}}</h4>
               </div>
               <table class="table table-bordered text-center">
                  <thead class="bg-info">
                     <th>No</th>
                     <th>Min</th>
                     <th>Max</th>
                     <th>Discount(%)</th>
                     <th>Actions</th>
                  </thead>
                  <tbody>
                     @if(count($singleDiscount)>0)               
                        @foreach($singleDiscount as $discount)
                           <tr>
                              <td class="bg-info">{{$loop->iteration}}</td>
                              <td>{{$discount->min}}</td>
                              <td>{{$discount->max}}</td>
                              <td>{{$discount->discount}}%</td>

                              @if(count($singleDiscount)==$loop->iteration)
                                 @php                            
                                    $store_id = $discount->store_id;
                                    $product_id = $discount->product_id;
                                    $lastMax = $discount->max+1;
                                 @endphp
                                 <td width="10">
                                    <div class="btn-group">
                                       <button class="btn btn-sm btn-primary text-light" data-toggle="modal" data-original-title="test" data-target="#addMore">Add more</button>
                                       <a class="btn btn-sm btn-danger" onclick="return confirm('Are you want to delete this?')" href="{{ Route('deleteDiscount', $discount->id)}}">Delete</a>
                                    </div>
                                 </td>
                              @endif
                           </tr>                
                        @endforeach
                     @else
                        <tr>
                           <td colspan="5" class="bg-warning">
                              No discount found
                           </td>
                        </tr>
                        <tr>
                           <td colspan="5" class="bg-info">
                              <button class="btn btn-primary text-light" data-toggle="modal" data-original-title="test" data-target="#addMore">Add discount now</button>
                           </td>
                        </tr>
                     @endif

                     <div class="modal fade" id="addMore" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                           <div class="modal-content">
                              <div class="modal-header bg-info">
                                 <h6 class="modal-title text-center" id="exampleModalLabel">Add Discount : {{$productName->product_name ?? ''}}</h6>
                                 <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                              </div>
                              <div class="modal-body">
                                 <form action="{{ Route('addDiscount') }}" method="post" enctype="multipart/form-data" class="needs-validation">
                                 @csrf
                                   <div class="form-row">
                                    <input type="hidden" name="store_id" value="{{$store_id ?? $productName->product_store_id}}">
                                    <input type="hidden" name="product_id" value="{{$product_id ?? $productName->product_id}}">
                               
                                       <div class="form-group col-md-4">
                                          <label class="ml-1" for="inputEmail4">Min</label>
                                          <input type="number" class="form-control" id="inputEmail4" name="min" placeholder="min number" value="{{$lastMax ?? '2'}}" {{isset($lastMax) ? 'readonly' : ''}} required>
                                       </div>
                                       <div class="form-group col-md-4">
                                          <label class="ml-1" for="inputEmail5">Max</label>
                                          <input type="number" class="form-control" id="inputEmail5" name="max" placeholder="max number" required> 
                                       </div>
                                       <div class="form-group col-md-4">
                                          <label class="ml-1" for="inputEmail6">Discount(%)</label>
                                          <input type="number" class="form-control" id="inputEmail6" name="discount" placeholder="discount number" required>
                                       </div>                                 
                                   </div>
                                    <div class="modal-footer p-0">
                                       <div class="btn-group">
                                          <button class="btn btn-sm btn-primary">Save</button>
                                          <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal">Close</button>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>

                  </tbody>
               </table>
            </div>
         </div>
      @else
         <div class="col-md-7">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title">No products selected</h4>
               </div>
            </div>
         </div>
      @endif
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