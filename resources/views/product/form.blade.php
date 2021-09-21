<form method="post" action="{{ route('product.crud') }}" data-redirect="{{ route('product') }}" autocomplete="off" class="form-horizontal form-admin">
  @csrf
  <input type="hidden" name="id" id="id" value="{{isset($data)?$data['id']:''}}">
  <div class="card ">
    <div class="card-body ">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Nama ID*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('name_id') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('name_id') ? ' is-invalid' : '' }}" name="name_id" id="name_id" type="text" placeholder="{{ __('Nama ID') }}" value="{{ old('name_id', isset($data) ? $data['name_id'] : '') }}" required="true" aria-required="true"/>
            </div>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Nama EN*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('name_en') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('name_en') ? ' is-invalid' : '' }}" name="name_en" id="name_en" type="text" placeholder="{{ __('Nama EN') }}" value="{{ old('name_en', isset($data) ? $data['name_en'] : '') }}" required="true" aria-required="true"/>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Harga*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('harga') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('harga') ? ' is-invalid' : '' }}" data-type="currency" name="harga" id="harga" type="text" placeholder="{{ __('Harga') }}" value="{{ old('harga', isset($data) ? $data['harga'] : '') }}" required />
            </div>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Harga Discount') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('harga_discount') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('harga_discount') ? ' is-invalid' : '' }}" data-type="currency" name="harga_discount" id="harga_discount" type="text" placeholder="{{ __('Harga Discount') }}" value="{{ old('harga_discount', isset($data) ? $data['harga_discount'] : '') }}" />
            </div>
          </div>
        </div>
      </div>   

      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Satuan*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('satuan') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('satuan') ? ' is-invalid' : '' }}" name="satuan" id="satuan" type="text" placeholder="{{ __('Satuan') }}" value="{{ old('satuan', isset($data) ? $data['satuan'] : '') }}" required="true" aria-required="true"/>
            </div>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Ukuran*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('ukuran') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('ukuran') ? ' is-invalid' : '' }}" name="ukuran" id="ukuran" type="text" placeholder="{{ __('Ukuran') }}" value="{{ old('ukuran', isset($data) ? $data['ukuran'] : '') }}" required="true" aria-required="true"/>
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Kategori*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('kategori') ? ' has-danger' : '' }}">                      
              <select class="form-control{{ $errors->has('kategori') ? ' is-invalid' : '' }}" name="kategori" id="kategori" placeholder="{{ __('Golongan') }}" value="{{ old('kategori', isset($data) ? $data['kategori'] : '') }}" required>
                <option value="" disabled selected>Select your option</option>
                <option @if(isset($data) && $data['kategori']=="sayur") selected @endif value="sayur">Sayur</option>
                <option @if(isset($data) && $data['kategori']=="buah") selected @endif value="buah">Buah</option>
                <option @if(isset($data) && $data['kategori']=="daging") selected @endif value="daging">Daging</option>
                <option @if(isset($data) && $data['kategori']=="makanan") selected @endif value="makanan">Makanan Siap Saji</option>
                <option @if(isset($data) && $data['kategori']=="bapok") selected @endif value="bapok">Bahan Pokok</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-sm-6 col-form-label">{{ __('Stock*') }}</label>
          <div class="col-sm-12">
            <div class="form-group{{ $errors->has('stock') ? ' has-danger' : '' }}">
              <input class="form-control{{ $errors->has('stock') ? ' is-invalid' : '' }}" name="stock" id="stock" type="text" placeholder="{{ __('Stock') }}" value="{{ old('stock', isset($data) ? $data['stock'] : '') }}" required="true" aria-required="true"/>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-6 col-form-label">{{ __('Deskripsi*') }}</label>
        <div class="col-sm-12">
          <div class="form-group{{ $errors->has('desc') ? ' has-danger' : '' }}">
            <textarea name="desc" id="desc" rows="3" class="form-control{{ $errors->has('desc') ? ' is-invalid' : '' }}"></textarea>
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

    </div>
    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn button-link">{{ __('Save') }}</button>
      <button id="btn-vedit" class="btn btn-info btn-to-edit" style="display: none;">{{ __('Edit') }}</button>
    </div>
  </div>
</form>  