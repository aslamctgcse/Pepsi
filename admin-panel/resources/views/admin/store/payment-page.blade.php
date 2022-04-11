@extends('admin.layout.app')
@section('content')
<!-- lets click this page automatically when this page load using js -->
<button id="rzp-button1" hidden>Pay</button>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var getBase = '<?=url('');?>';
    var newRedirectPayfail = getBase+'/payout_req';
var options = {
    "key": "{{$response['razorpayId'] ?? '' }}", // Enter the Key ID generated from the Dashboard
    "amount": "{{$response['amount'] ?? '' }}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "{{$response['currency'] ?? '' }}",
    "name": "{{$response['name']  ?? '' }}",
    "description": "{{$response['description'] ?? '' }}",
    "image":  "{{url('/assets/img/dealwy-logo.png')}}",// {{url('/assets/website/img/logo.png')}}
    "order_id": "{{$response['orderId'] ?? ''}}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
    	//set the data in form
    	document.getElementById('rzp_paymentid').value =response.razorpay_payment_id ;
    	document.getElementById('rzp_orderid').value =response.razorpay_order_id ;
    	document.getElementById('rzp_signature').value =response.razorpay_signature ;

    	//lets submit the form automatically
    	document.getElementById('rzp-paymentresponse').click();
    	//set the data in form
        //alert(response.razorpay_payment_id);
       // alert(response.razorpay_order_id);
       // alert(response.razorpay_signature)
    },
    "modal": {
    "ondismiss": function(){
         window.location.replace(newRedirectPayfail);
     }
    },
    "prefill": {
        "name": "{{$response['name'] ?? '' }}",
        "email": "{{$response['email'] ?? '' }}",
        "contact": "{{$response['contactnumber'] ?? '' }}"
    },
    "notes": {
        "address": "{{$response['address'] ?? '' }}"
    },
    "theme": {
        "color": "#F37254"
    }
};
var rzp1 = new Razorpay(options);
//js to click btn
window.onload= function(){
	document.getElementById('rzp-button1').click();
}
//js to click btn
rzp1.on('payment.failed', function (response){
        //alert(response.error.code);
        //alert(response.error.description);
        //alert(response.error.source);
        //alert(response.error.step);
        alert(response.error.reason);
        //alert(response.error.metadata);
        var getBase = '<?=url('');?>';
        var newRedirectPayfail = getBase+'/payout_req';
        console.log(getBase);


        window.location.href=newRedirectPayfail;
});
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>
<form action="{{url('/payment-complete')}}" method="post" style="display:none;">
	<input type="hidden" value="{{csrf_token()}}" name="_token">
	<input type="text" class="form-control" id="rzp_paymentid" name="rzp_paymentid">
	<input type="text" class="form-control" id="rzp_orderid" name="rzp_orderid">
	<input type="text" class="form-control" id="rzp_signature" name="rzp_signature">
	<!--added to take user name,phone,email beg-->
	<input type="text" class="form-control" id="" name="store_name" value="{{$response['name'] ?? '' }}">
	<input type="text" class="form-control" id="" name="store_phone" value="{{$response['phone'] ?? '' }}">
	<input type="text" class="form-control" id="" name="store_email" value="{{$response['email'] ?? ''}}">
	<input type="text" class="form-control" id="" name="payment_req_id" value="{{$response['payment_req_id'] ?? '' }}">
    <input type="text" class="form-control" id="" name="store_ac_no" value="{{$response['store_ac_no'] ?? '' }}">
    <input type="text" class="form-control" id="" name="store_ifsc" value="{{$response['store_ifsc'] ?? '' }}">
    <input type="text" class="form-control" id="" name="store_holder_name" value="{{$response['store_holder_name'] ?? '' }}">
    <input type="text" class="form-control" id="" name="store_bank_name" value="{{$response['store_bank_name'] ?? '' }}">
    <input type="text" class="form-control" id="" name="store_upi" value="{{$response['store_upi'] ?? '' }}">
    <input type="text" class="form-control" id="" name="amount" value="{{$response['amount'] ?? ''}}">
  
    <input type="text" class="form-control" id="" name="paymenttype" value="{{$response['paymenttype'] ?? '' }}">
   


	<!--added to take user name,phone,email end-->
	<button type="submit" id="rzp-paymentresponse" class="btn btn-primary">Submit</button>
	
</form>
@stop