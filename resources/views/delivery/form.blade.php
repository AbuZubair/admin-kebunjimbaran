<form method="post" action="{{ route('delivery.crud' ) }}" data-redirect="{{ route('delivery') }}" autocomplete="off" class="form-horizontal form-admin">
  @csrf
  <input type="hidden" name="id" id="id" value="{{isset($data)?$data['id']:''}}">
  <div class="card" style="margin-top: 0 !important;">
    <div class="card-body ">

      <div class="form-group" style="display: none;">
        <label class="col-sm-6 col-form-label">{{ __('Value*') }}</label>
        <div class="col-sm-12">
          <div class="form-group{{ $errors->has('ref_value') ? ' has-danger' : '' }}">
            <select class="form-control{{ $errors->has('ref_value') ? ' is-invalid' : '' }}" name="ref_value" id="ref_value" placeholder="{{ __('Golongan') }}" value="{{ old('ref_value', isset($data) ? $data['ref_value'] : '') }}" required>
              <option @if((isset($data) && $data['ref_value']=="0") OR !isset($data)) selected @endif value="0">Ahad</option>
              <option @if(isset($data) && $data['ref_value']=="1") selected @endif value="1">Senin</option>
              <option @if(isset($data) && $data['ref_value']=="2") selected @endif value="2">Selasa</option>
              <option @if(isset($data) && $data['ref_value']=="3") selected @endif value="3">Rabu</option>
              <option @if(isset($data) && $data['ref_value']=="4") selected @endif value="4">Kamis</option>
              <option @if(isset($data) && $data['ref_value']=="5") selected @endif value="5">Jumat</option>
              <option @if(isset($data) && $data['ref_value']=="6") selected @endif value="6">Sabtu</option>
            </select>
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
