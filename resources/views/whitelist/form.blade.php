<form method="post" action="{{ route('whitelist.crud' ) }}" data-redirect="{{ route('whitelist') }}" autocomplete="off" class="form-horizontal form-admin">
  @csrf
  <input type="hidden" name="id" id="id" value="{{isset($data)?$data['id']:''}}">
  <div class="card" style="margin-top: 0 !important;">
    <div class="card-body ">

      <div class="form-group">
        <label class="col-sm-6 col-form-label">{{ __('No HP*') }}</label>
        <div class="col-sm-12">
          <div class="form-group{{ $errors->has('ref_value') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('ref_value') ? ' is-invalid' : '' }}" name="ref_value" id="ref_value" type="text" placeholder="{{ __('No HP') }}" value="{{ old('ref_value', isset($data) ? $data['ref_value'] : '') }}" required="true" aria-required="true"/>
          </div>
        </div>
      </div>

      <div class="form-group mt-4 mb-4" style="display: flex;">
        <label class="col-sm-2 col-form-label">{{ __('Is Active?*') }}</label>
        <div class="col-sm-2" style="display: inline;">
          <select class="form-control{{ $errors->has('is_active') ? ' is-invalid' : '' }}" name="is_active" id="is_active" placeholder="{{ __('Golongan') }}" value="{{ old('is_active', isset($data) ? $data['is_active'] : '') }}" required>
            <option @if((isset($data) && $data['is_active']=="Y") OR !isset($data)) selected @endif value="Y">Y</option>
            <option @if(isset($data) && $data['is_active']=="N") selected @endif value="N">N</option>
          </select>
        </div>
      </div>
    </div>
    
    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn button-link">{{ __('Save') }}</button>
      <button id="btn-vedit" class="btn btn-info btn-to-edit" style="display: none;">{{ __('Edit') }}</button>
    </div>
  </div>
</form>
