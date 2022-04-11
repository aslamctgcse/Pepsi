<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="RejectModalLabel">Order Details (<b>{{ $cart_id }}</b>)</h5>
      <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!--//form-->
    <form class="forms-sample" action="{{route('store_reject_order', $cart_id)}}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-md-2" align="center"></div>  
        <div class="col-md-8" align="left">
          <br>
          <div class="form-group">
            <label>Send Rejection Reason to User</label>    
            <textarea class="textareaCss" name="cause" row="5" required></textarea>
          </div>
        </div>
        <div class="col-md-2" align="center"></div> 
        <div class="col-md-12" align="center">
          <button type="submit" class="btn btn-primary pull-center">Submit</button>
        </div>
      </div>
        
      <div class="clearfix"></div>
    </form>
  </div>
</div>