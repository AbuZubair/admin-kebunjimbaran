@extends('layouts.app',['web' => false])

@push('css')
<style>
  body{
    /* background: #00548b !important; */
  }
</style>
@endpush

@section('content')
<div class="container w-100 h-100" style="height: auto;">
  <div class="center-fit">
    <div style="width: 50%;display:flex">
      <img src="{{url('/assets/hijau_trans@600x.jpg')}}" width="50%" style="margin-top:10%;margin-left:auto;margin-right:35%"> 
    </div>    
  </div>
 
  <div class="row h-100">
    <div class="col-lg-12 h-100">
      <div class="row h-100">
      <div class="col-lg-4 col-md-6 col-sm-6 pt-5" style="color:#FFF !important">
          <div class="pt-4"></div>         
        </div>

        <div class="shadow pt-4 pb-5 pl-5 pr-5 h-100" style="background-color:#FFF !important;">
                             
          <form class="form form-login" method="POST" action="{{ route('auth.on-sign-in') }}" style="margin:4em 4em">
            @csrf
        
            <div class="row">
              <div class="col-lg-12">
                <div class="card shadow ml-auto mr-auto mt-4">
                  <div class="card-body">
                    <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">perm_identity</i>
                          </span>
                        </div>
                        <input type="text" name="username" class="form-control" placeholder="{{ __('Username...') }}" value="{{ old('username') }}" required>
                      </div>
                      @if ($errors->has('username'))
                        <div id="username-error" class="error text-danger pl-3" for="username" style="display: block;">
                          <strong>{{$errors->first()}}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">lock_outline</i>
                          </span>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="{{ __('Password...') }}" required>
                      </div>
                      @if ($errors->has('password'))
                        <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                          <strong>{{ $errors->first() }}</strong>
                        </div>
                      @endif
                    </div>
                    <!-- <div class="form-check mr-auto ml-3 mt-3">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}
                        <span class="form-check-sign">
                          <span class="check"></span>
                        </span>
                      </label>
                    </div> -->

                  </div>
                  <div class="card-footer justify-content-center">
                    <button type="submit" class="btn btn-sm btn-block button-link">Masuk</button>
                  </div>
                </div>
              </div>
          </form>
        </div>
        <!-- <div class="row justify-content-center">
          <div class="col-6 text-center">
              @if (Route::has('auth.change-password'))
                  <a href="{{ route('auth.change-password') }}">
                      <small>{{ __('Forgot password?') }}</small>
                  </a>
              @endif
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
  $(document).ready(function(){
      
  });

  function checktnc() {
    if($('#tnc').is(":checked")){
      $('#register-btn').prop('disabled', false);
    }else{
      $('#register-btn').prop('disabled', true);
    }
  }
</script>
@endpush

