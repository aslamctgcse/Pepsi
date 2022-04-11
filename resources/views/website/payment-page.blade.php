@extends('website.layouts.app')
@section('content')
<!-- lets click this page automatically when this page load using js -->
<button id="rzp-button1" hidden>Pay</button>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "{{$response['razorpayId'] ?? '' }}", // Enter the Key ID generated from the Dashboard
    "amount": "{{$response['amount'] ?? '' }}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "{{$response['currency'] ?? '' }}",
    "name": "{{$response['name']  ?? '' }}",
    "description": "{{$response['description'] ?? '' }}",
    "image":  "{{url('/assets/website/img/sailogo2.png')}}",// {{url('/assets/website/img/logo.png')}}
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
        alert(response.error.code);
        alert(response.error.description);
        alert(response.error.source);
        alert(response.error.step);
        alert(response.error.reason);
        alert(response.error.metadata);
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
	<input type="text" class="form-control" id="" name="ufname" value="{{$response['name'] ?? '' }}">
	<input type="text" class="form-control" id="" name="uphone" value="{{$response['contactnumber'] ?? '' }}">
	<input type="text" class="form-control" id="" name="uemail" value="{{$response['email'] ?? ''}}">
	<input type="text" class="form-control" id="" name="uaddress" value="{{$response['address'] ?? '' }}">
    <input type="text" class="form-control" id="" name="amount" value="{{$response['amount'] ?? ''}}">
    <input type="text" class="form-control" id="" name="deliveydate" value="{{$response['deliveydate'] ?? '' }}">
    <input type="text" class="form-control" id="" name="timeslot" value="{{$response['timeslot'] ?? '' }}">
    <input type="text" class="form-control" id="" name="delcharge" value="{{$response['delcharge'] ?? '' }}">

    <input type="text" class="form-control" id="" name="bulkOrderDiscount" value="{{$response['bulkOrderDiscount'] ?? '' }}">
    <input type="text" class="form-control" id="" name="storeWiseTotal" value="{{$response['storeWiseTotal'] ?? '' }}">
    <input type="text" class="form-control" id="" name="ordertotal" value="{{$response['ordertotal'] ?? '' }}">
    <input type="text" class="form-control" id="" name="ordertotalmrp" value="{{$response['ordertotalmrp'] ?? '' }}">
    <input type="text" class="form-control" id="" name="uphone_whatsapp" value="{{$response['uphone_whatsapp'] ?? '' }}">
    <input type="text" class="form-control" id="" name="ucity" value="{{$response['ucity'] ?? '' }}">
    <input type="text" class="form-control" id="" name="landmark" value="{{$response['landmark'] ?? '' }}">
    <input type="text" class="form-control" id="" name="deliveryType" value="{{$response['deliveryType'] ?? '' }}">
    <input type="text" class="form-control" id="" name="paymenttype" value="{{$response['paymenttype'] ?? '' }}">
    <input type="text" class="form-control" id="" name="special_instruction" value="{{$response['special_instruction'] ?? '' }}">
    <input type="text" class="form-control" id="" name="onlineOrderDiscount" value="{{$response['onlineOrderDiscount'] ?? '' }}">
    <input type="text" class="form-control" id="" name="lastpaymentAmount" value="{{$response['lastpaymentAmount'] ?? '' }}">
    <input type="text" class="form-control" id="" name="couponCode" value="{{$response['couponCode'] ?? '' }}">
    <input type="text" class="form-control" id="" name="couponAmount" value="{{$response['couponAmount'] ?? '' }}">


	<!--added to take user name,phone,email end-->
	<button type="submit" id="rzp-paymentresponse" class="btn btn-primary">Submit</button>
	
</form>
@stop