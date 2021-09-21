<div class="sidebar" data-color="green" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="" class="simple-text logo-normal">
    <img src="{{url('/assets/hijau_trans@600x.jpg')}}" alt="" width="20%">
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">

      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('Dashboard') }}</p>
        </a>
      </li>

      @if(in_array(Auth::user()->getRole(), [0,1]))
      <li class="nav-item{{ $activePage == 'user' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('user') }}">
          <i class="material-icons">account_circle</i>
            <p>User</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'product' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('product') }}">
          <i class="material-icons">inventory_2</i>
            <p>Product</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'promo' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('promo') }}">
          <i class="material-icons">price_check</i>
            <p>Promo</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'whitelist' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('whitelist') }}">
          <i class="material-icons">checklist_rtl</i>
            <p>Whitelist Promo</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'slider' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('slider') }}">
          <i class="material-icons">image</i>
            <p>Header Slider</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'delivery' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('delivery') }}">
          <i class="material-icons">event</i>
            <p>Delivery Day</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'order' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('order') }}">
          <i class="material-icons">shopping_cart</i>
            <p>Pesanan</p>
        </a>
      </li>
      @endif

      <!--@if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'project' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('project') }}">
          <i class="material-icons">launch</i>
            <p>Projects</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0,1,4]))
      <li class="nav-item{{ $activePage == 'approval' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('approval') }}">
          <i class="material-icons">verified</i>
            <p>Approval</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'trans' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('trans') }}">
          <i class="material-icons">receipt_long</i>
            <p>Transaction</p>
        </a>
      </li>
      @endif -->

      @if(in_array(Auth::user()->getRole(), [0,1,2,4]))
      <li class="nav-item{{ $activePage == 'report' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('report') }}">
          <i class="material-icons">summarize</i>
            <p>Report</p>
        </a>
      </li>
      @endif

      @if(in_array(Auth::user()->getRole(), [0]))
      <li class="nav-item{{ $activePage == 'log' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('log') }}">
          <i class="material-icons">view_list</i>
            <p>Log</p>
        </a>
      </li>
      @endif
            
    </ul>
  </div>
</div>