<form method="post" action="{{ route('promo.crud' ) }}" data-redirect="{{ route('promo') }}" autocomplete="off" class="form-horizontal form-admin">
  @csrf
  <input type="hidden" name="id" id="id" value="{{isset($data)?$data['id']:''}}">
  <div class="card" style="margin-top: 0 !important;">
    <div class="card-body ">

      <div class="form-group">
        <label class="col-sm-6 col-form-label">{{ __('Nama Promo*') }}</label>
        <div class="col-sm-12">
          <div class="form-group{{ $errors->has('promo_name') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('promo_name') ? ' is-invalid' : '' }}" name="promo_name" id="promo_name" type="text" placeholder="{{ __('Nama Promo') }}" value="{{ old('promo_name', isset($data) ? $data['promo_name'] : '') }}" required="true" aria-required="true"/>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 col-form-label">{{ __('Kode Promo*') }}</label>
        <div class="col-sm-12">
          <div class="form-group{{ $errors->has('kode_promo') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('kode_promo') ? ' is-invalid' : '' }}" name="kode_promo" id="kode_promo" type="text" placeholder="{{ __('Kode Promo') }}" value="{{ old('kode_promo', isset($data) ? $data['kode_promo'] : '') }}" required/>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="col-sm-8 col-form-label">{{ __('Discount (Persentase)') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('discount') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}" name="discount" id="discount" type="text" placeholder="{{ __('Discount (Persentase)') }}" value="{{ old('discount', isset($data) ? $data['discount'] : '') }}" />
            </div>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Discount (Amount)') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('discount_amount') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('discount_amount') ? ' is-invalid' : '' }}" name="discount_amount" id="discount_amount" type="text" placeholder="{{ __('Discount (Amount)') }}" value="{{ old('discount_amount', isset($data) ? $data['discount_amount'] : '') }}" />
            </div>
          </div>
        </div>
      </div>   
      <div class="ml-2 pl-1 mb-4">Isi salah satu. Jika isi keduanya, diambil dari persentase</div>

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
