    <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <div class="logo"><a href="{{route('storeHome')}}" class="simple-text logo-normal">
          <!-- {{$logo->name}} Store -->
          <img src="{{url('/')}}/assets/img/dealwy-logo.png" alt="IMG" style="max-width: 248px;">
        </a></div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item ">
            <a class="nav-link" href="{{route('storeHome')}}">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
           <li class="nav-item ">
            <a class="nav-link" href="{{route('payout_req')}}">
              <i class="material-icons">layers</i>
              <p>Send Payout Request</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{route('st_product_list')}}">
              <i class="material-icons">content_paste</i>
              <p>Products</p>
            </a>
          </li>

           <li class="nav-item ">
            <a class="nav-link" href="{{route('st_product')}}">
              <i class="material-icons">layers</i>
              <p>Update Stock</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{route('sel_product')}}">
              <i class="material-icons">layers</i>
              <p>Products Add</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#setting-dropdown2" aria-expanded="{{ request()->is('store/orders/unassigned') || request()->is('store/orders/assigned') || request()->is('store/orders/not-delivered-reason') ? 'true' : 'false' }}" aria-controls="setting-dropdown">
              <i class="menu-icon fa fa-calendar"></i>
              <span class="menu-title">Orders<b class="caret"></b></span>
            </a>
            <div class="collapse {{ request()->is('store/orders/unassigned') || request()->is('store/orders/assigned') || request()->is('store/orders/not-delivered-reason') ? 'show' : '' }}" id="setting-dropdown2">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('storeOrders')}}">Unassigned Orders</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('storeassignedorders')}}">Assigned Orders</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('notDeliveredOrdersReason')}}">Not Delivered Orders Reason</a>
                </li>

                </ul>
                </div>
            </li>

            <li class="nav-item ">
              <a class="nav-link" href="{{ route('delivery_boy_list') }}">
                <i class="material-icons">android</i>
                <p>Delivery Boy</p>
              </a>
            </li>

        </ul>
      </div>
    </div>