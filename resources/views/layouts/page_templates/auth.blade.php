
<div class="wrapper ">
  @if(Auth::user()->getRole() != '3')
    @include('layouts.navbars.sidebar')
  @endif
  <div class="main-panel {{ Auth::user()->getRole() == '3' ? 'main-panel-viewer' : '' }}">
    @include('layouts.navbars.navs.auth')
    @yield('content')
    @include('layouts.footers.auth')
  </div>
</div>