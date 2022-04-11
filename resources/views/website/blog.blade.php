@extends('website.layouts.app')



@section('content')
<style>
   
   .collapse_ext {position: fixed;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 50%;
z-index:99999;
display:block;
}
input.razorpay-payment-button{
   border: unset;background: #e96125 none repeat scroll 0 0 !important;color: #fff;padding: 5px !important;border-radius: 2px !important; display:none;
}
</style>
<?php 
$username='';
$useremail='';
$userphone='';


?>

@if(session()->has('userData'))
<?php
$userdata =session()->get('userData'); 
$username=$userdata->user_name;
$useremail=$userdata->user_email;
$userphone=$userdata->user_phone;


 ?>
@endif
<section class="blog-page section-padding">
         <div class="container">
            <div class="row">
               <div class="col-md-8">
                  <div class="card blog mb-4">
                     <div class="blog-header">  
                        <a href="#"><img class="card-img-top" src="img/blog/1.png" alt="Card image cap"></a>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title"><a href="#">Aliquam euismod libero eu enim. Nulla nec felis sed leo.</a></h5>
                        <div class="entry-meta">
                           <ul class="tag-info list-inline">
                              <li class="list-inline-item"><a href="#"><i class="mdi mdi-calendar"></i>  March 6, 2018</a></li>
                              <li class="list-inline-item"><i class="mdi mdi-folder"></i> <a rel="category tag" href="#">Image</a></li>
                              <li class="list-inline-item"><i class="mdi mdi-tag"></i> <a rel="tag" href="#">envato</a>, <a rel="tag" href="#">sale</a>, <a rel="tag" href="#">shop</a> </li>
                              <li class="list-inline-item"><i class="mdi mdi-comment-account-outline"></i> <a href="#">4 Comments</a></li>
                           </ul>
                        </div>
                        <p class="card-text">Aliquam convallis sollicitudin purus. Praesent aliquam, enim at fermentum mollis, ligula massa adipiscing nisl, ac euismod nibh nisl eu lectus. Fusce vulputate sem at sapien. Vivamus leo. Aliquam euismod libero eu enim. Nulla nec felis sed leo placerat imperdiet. Aenean suscipit nulla in justo. Suspendisse cursus rutrum augue. Nulla tincidunt tincidunt mi. Lorem ipsum dolor
                        </p>
                        <a href="blog-detail.php">READ MORE <span class="mdi mdi-chevron-right"></span></a>
                     </div>
                  </div>
                  <div class="card blog mb-4">
                     <div class="blog-header">  
                        <a href="#"><img class="card-img-top" src="img/blog/2.png" alt="Card image cap"></a>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title"><a href="#">Aliquam euismod libero eu enim. Nulla nec felis sed leo.</a></h5>
                        <div class="entry-meta">
                           <ul class="tag-info list-inline">
                              <li class="list-inline-item"><a href="#"><i class="mdi mdi-calendar"></i>  March 6, 2018</a></li>
                              <li class="list-inline-item"><i class="mdi mdi-folder"></i> <a rel="category tag" href="#">Image</a></li>
                              <li class="list-inline-item"><i class="mdi mdi-tag"></i> <a rel="tag" href="#">envato</a>, <a rel="tag" href="#">sale</a>, <a rel="tag" href="#">shop</a> </li>
                              <li class="list-inline-item"><i class="mdi mdi-comment-account-outline"></i> <a href="#">4 Comments</a></li>
                           </ul>
                        </div>
                        <p class="card-text">Aliquam convallis sollicitudin purus. Praesent aliquam, enim at fermentum mollis, ligula massa adipiscing nisl, ac euismod nibh nisl eu lectus. Fusce vulputate sem at sapien. Vivamus leo. Aliquam euismod libero eu enim. Nulla nec felis sed leo placerat imperdiet. Aenean suscipit nulla in justo. Suspendisse cursus rutrum augue. Nulla tincidunt tincidunt mi. Lorem ipsum dolor
                        </p>
                        <a href="blog-detail.php">READ MORE <span class="mdi mdi-chevron-right"></span></a>
                     </div>
                  </div>
                  <div class="card blog mb-4">
                     <div class="blog-header">  
                        <a href="#"><img class="card-img-top" src="img/blog/3.png" alt="Card image cap"></a>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title"><a href="#">Aliquam euismod libero eu enim. Nulla nec felis sed leo.</a></h5>
                        <div class="entry-meta">
                           <ul class="tag-info list-inline">
                              <li class="list-inline-item"><a href="#"><i class="mdi mdi-calendar"></i>  March 6, 2018</a></li>
                              <li class="list-inline-item"><i class="mdi mdi-folder"></i> <a rel="category tag" href="#">Image</a></li>
                              <li class="list-inline-item"><i class="mdi mdi-tag"></i> <a rel="tag" href="#">envato</a>, <a rel="tag" href="#">sale</a>, <a rel="tag" href="#">shop</a> </li>
                              <li class="list-inline-item"><i class="mdi mdi-comment-account-outline"></i> <a href="#">4 Comments</a></li>
                           </ul>
                        </div>
                        <p class="card-text">Aliquam convallis sollicitudin purus. Praesent aliquam, enim at fermentum mollis, ligula massa adipiscing nisl, ac euismod nibh nisl eu lectus. Fusce vulputate sem at sapien. Vivamus leo. Aliquam euismod libero eu enim. Nulla nec felis sed leo placerat imperdiet. Aenean suscipit nulla in justo. Suspendisse cursus rutrum augue. Nulla tincidunt tincidunt mi. Lorem ipsum dolor
                        </p>
                        <a href="blog-detail.php">READ MORE <span class="mdi mdi-chevron-right"></span></a>
                     </div>
                  </div>
                  <ul class="pagination justify-content-center mt-4">
                     <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                     </li>
                     <li class="page-item"><a href="#" class="page-link">1</a></li>
                     <li class="page-item active">
                        <span class="page-link">
                        2
                        <span class="sr-only">(current)</span>
                        </span>
                     </li>
                     <li class="page-item"><a href="#" class="page-link">3</a></li>
                     <li class="page-item">
                        <a href="#" class="page-link">Next</a>
                     </li>
                  </ul>
               </div>
               <div class="col-md-4">
                  <div class="card sidebar-card mb-4 mt-md-0 mt-4">
                     <div class="card-body">
                        <div class="input-group">
                           <input type="text" placeholder="Search For" class="form-control">
                           <div class="input-group-append">
                              <button type="button" class="btn btn-secondary">Search <i class="mdi mdi-arrow-right"></i></button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card sidebar-card mb-4">
                     <div class="card-body">
                        <h5 class="card-title mb-3">Categories</h5>
                        <ul class="sidebar-card-list">
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> Audio</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> Gallery</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> Image</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> Uncategorized</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> Video</a></li>
                        </ul>
                     </div>
                  </div>
                  <div class="card sidebar-card mb-4">
                     <div class="card-body">
                        <h5 class="card-title mb-3">Archives</h5>
                        <ul class="sidebar-card-list">
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> December, 2017</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> November, 2017</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> October, 2017</a></li>
                        </ul>
                     </div>
                  </div>
                  <div class="card sidebar-card mb-4">
                     <div class="card-body">
                        <h5 class="card-title mb-3">Tags</h5>
                        <div class="tagcloud">
                           <a class="tag-cloud-link" href="#">coupon</a>
                           <a class="tag-cloud-link" href="#">deals</a>
                           <a class="tag-cloud-link" href="#">discount</a>
                           <a class="tag-cloud-link" href="#">envato</a>
                           <a class="tag-cloud-link" href="#">gallery</a>
                           <a class="tag-cloud-link" href="#">sale</a>
                           <a class="tag-cloud-link" href="#">shop</a>
                           <a class="tag-cloud-link" href="#">stores</a>
                           <a class="tag-cloud-link" href="#">video</a>
                           <a class="tag-cloud-link" href="#">vimeo</a>
                           <a class="tag-cloud-link" href="#">youtube</a>
                        </div>
                     </div>
                  </div>
                  <div class="card sidebar-card mb-4">
                     <div class="card-body">
                        <h5 class="card-title mb-4">Newsletter</h5>
                        <div class="input-group">
                           <input type="text" placeholder="Your email address" class="form-control">
                           <div class="input-group-append">
                              <button type="button" class="btn btn-secondary">Sign up <i class="mdi mdi-arrow-right"></i></button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card sidebar-card mb-4">
                     <div class="card-body">
                        <h5 class="card-title mb-3">Meta</h5>
                        <ul class="sidebar-card-list">
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> Log in</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> Entries RSS</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> Comments RSS</a></li>
                           <li><a href="#"><i class="mdi mdi-chevron-right"></i> WordPress.org</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <section class="section-padding bg-white border-top">
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-truck-fast"></i>
                     <h6>Free & Next Day Delivery</h6>
                     <p>Lorem ipsum dolor sit amet, cons...</p>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-basket"></i>
                     <h6>100% Satisfaction Guarantee</h6>
                     <p>Rorem Ipsum Dolor sit amet, cons...</p>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-tag-heart"></i>
                     <h6>Great Daily Deals Discount</h6>
                     <p>Sorem Ipsum Dolor sit amet, Cons...</p>
                  </div>
               </div>
            </div>
         </div>
      </section>

<script>




 function removeproduct(rpid){

   

   //alert(rpid);



   //added code to remove product beg pp

   /*var totalitems=$('.cart-value').text();

   var totalitems= parseInt(totalitems) + 1;

   $('.cart-value').text(totalitems);*/

   var rpidd=rpid;

   var ramount= $('#pp'+rpidd).text();

   //alert(ramount);  s

   var tp= $('#tp1').text();
   //alert(parseInt(tp));
   

   //alert(tp);

   var newtotal= parseInt(tp) - parseInt(ramount);

    $('.tp').text(parseInt(newtotal));

     var cartcurrent=$('.cart-value').text();

     //alert();

     $('.cart-value').text(parseInt(cartcurrent) - 1);



   //alert(pidd);

   $.ajax({



          type: 'get',



         // url: 'http://localhost/blog/public/addtocart',remove-product

           url:"{{url('/remove-product')}}/"+rpidd,



          //data: {'pid':pidd},  



          success : function(data){



            // $('#category'+value).remove()

             $('#rp'+rpidd).remove();
             var tp= $('#tp1').text();
             if(parseInt(tp) == 0 ){
              //alert('yes');
            $('.cart-table').remove();
            location.reload(true); //reload

            }



             console.log(data);

             //alert(data);

            // alert('Product has been removed to cart');



          }



        });

   //added code to remove product end



 }



 //added code to manage inc/dec qty beg

 function manageqty(id,type){

   //alert(type);
   var quant=$('#quant'+id).val();
   //alert(quant);


   var pidd=id;

   var type=type;

  var prevqty=$('#quant'+id).val();

   //alert(prevqty);

   if(type == 'inc') {

  var quant= $('#quant'+id).val(parseInt(prevqty) + 1);

 var getquant=$('#quant'+id).val();

  var uprice = $('#uprice'+id).text();
  //alert(uprice);

  //alert(quant);

  var newsubtotal= parseFloat(getquant) * parseFloat(uprice);

 // alert(parseInt(newsubtotal))

  var subtotal = $('#pp'+id).text(newsubtotal);

   var prevtotal=$('#tp1').text();

   //alert(parseInt(prevtotal));

$('.tp').text(parseFloat(parseFloat(prevtotal) + parseFloat(uprice)));

}

 if(type == 'dec' && quant >= 2) {

  var quant= $('#quant'+id).val(parseInt(prevqty) - 1);

  var getquant=$('#quant'+id).val();

  var uprice = $('#uprice'+id).text();

  //alert(quant);

  var newsubtotal= parseFloat(getquant) * parseFloat(uprice);

  //alert(parseInt(newsubtotal));

  var subtotal = $('#pp'+id).text(newsubtotal);

  var prevtotal=$('#tp1').text();

$('.tp').text(parseFloat(parseFloat(prevtotal) - parseFloat(uprice)));

}



   /////////////////////////////////inc dec beg

   $.ajax({



          type: 'get',



         // url: 'http://localhost/blog/public/addtocart',

           url:"{{url('/addtocart')}}/"+pidd+'/'+type,



          //data: {'pid':pidd},  



          success : function(data){



            // $('#category'+value).remove()



             //console.log(data);

             //alert(data);

             //alert('Product qty has been increased cart');



          }



        });



   ///////////////////////////////////// inc dec end

 }

 //added code to manage inc/dec qty end
 //aadded code for onchnag payment option beg
 function payoption(){
   //alert('gh');
   var payment_type = $( "#paymenttype option:selected" ).val();
   //alert(payment_type);
   if(payment_type == 0){
      //alert(payment_type);
     
   // $('#cod').show();
   //$('input.razorpay-payment-button').hide();
   }
   if(payment_type == 1){
      // alert(payment_type);
  // $('#cod').hide();
   //$('input.razorpay-payment-button').show();
   $('#checkout_form').attr('action', "payment-inititate-request")

   }
 }
 //aadded code for onchnag payment option end

 //

 

   </script>

   @endsection