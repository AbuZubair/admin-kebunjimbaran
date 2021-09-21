<form method="post" action="{{ route('user.crud',isset($data)?1:0 ) }}" data-redirect="{{ route('user') }}" autocomplete="off" class="form-horizontal form-admin">
  @csrf
  <input type="hidden" name="id" id="rowID" value="{{isset($data)?$data['rowID']:''}}">
  <div class="card" style="margin-top: 0 !important;">
    <div class="card-body ">

      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('First Name*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('firstName') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('firstName') ? ' is-invalid' : '' }}" name="firstName" id="firstName" type="text" placeholder="{{ __('First Name') }}" value="{{ old('firstName', isset($data) ? $data['firstName'] : '') }}" required="true" aria-required="true"/>
            </div>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Last Name') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('lastName') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('lastName') ? ' is-invalid' : '' }}" name="lastName" id="lastName" type="text" placeholder="{{ __('Last Name') }}" value="{{ old('lastName', isset($data) ? $data['lastName'] : '') }}" />
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Role*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('role') ? ' has-danger' : '' }}">                      
              <select class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" id="role" placeholder="{{ __('Role') }}" value="{{ old('role', isset($data) ? $data['role'] : '') }}" required>
                <option value="" disabled selected>Select your option</option>
                <option @if(isset($data) && $data['role']==0) selected @endif value="0">Admin</option>
                <option @if(isset($data) && $data['role']==1) selected @endif value="1">Sales / Customer Service</option>
                <!-- <option @if(isset($data) && $data['role']==3) selected @endif value="3">Viewer</option> -->
                <!-- <option @if(isset($data) && $data['role']==4) selected @endif value="1">Finance</option> -->
              </select>
            </div>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Phone Number*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('phoneNumber') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('phoneNumber') ? ' is-invalid' : '' }}" name="phoneNumber" id="phoneNumber" type="text" placeholder="{{ __('Phone Number') }}" value="{{ old('phoneNumber', isset($data) ? $data['phoneNumber'] : '') }}" required />
            </div>
          </div>
        </div>        
      </div>

      <div class="form-group">
        <label class="col-sm-6 col-form-label">{{ __('Email*') }}</label>
        <div class="col-sm-12">
          <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', isset($data) ? $data['email'] : '') }}" required />
          </div>
        </div>
      </div>
      
      <div class="form-row" id="password-section">
        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Password*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" type="password" placeholder="{{ __('Password') }}" value="{{ old('password') }}" />
            </div>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Re-Type Password*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('password_confirmation ') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('password_confirmation ') ? ' is-invalid' : '' }}" name="password_confirmation" id="password_confirmation" type="password" placeholder="{{ __('Re-type') }}" />
            </div>
          </div>
        </div>
      </div>                                                           

    </div>
    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn button-link">{{ __('Save') }}</button>
    </div>
  </div>
</form>
