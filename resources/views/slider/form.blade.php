<form method="post" action="{{ route('slider.crud' ) }}" data-redirect="{{ route('slider') }}" autocomplete="off" class="form-horizontal form-admin">
  @csrf
  <input type="hidden" name="id" id="id" value="{{isset($data)?$data['id']:''}}">
  <div class="card" style="margin-top: 0 !important;">
    <div class="card-body ">
      <div>Rekomendasi ukuran image: 480x200px</div>
      <div class="fileinput fileinput-new text-center mt-2" data-provides="fileinput">
        <div class="fileinput-preview fileinput-exists thumbnail img-raised"></div>
        <div>
            <span class="btn btn-raised btn-round btn-default btn-file">
                <span class="fileinput-new">Select image</span>
                <span class="fileinput-exists">Change</span>
                <input type="file" name="photo" id="photo" />
                <input type="hidden" name="uploaded" value="0">
            </span>
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
