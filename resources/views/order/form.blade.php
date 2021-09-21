@extends('layouts.admin', ['activePage' => 'order', 'titlePage' => __('Detail Pesanan')])

@push('css')
<style>
  .loader {
    border: 8px solid #f3f3f3; /* Light grey */
    border-top: 8px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
    margin-top: 20%;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
@endpush

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row pl-4">
      <a href="{{route('order')}}">
        <i class="material-icons">arrow_back</i>
        &nbsp; Back
      </a>
    </div>
    <div class="row">
      <div class="col-lg-12" style="overflow-x:auto !important">
        <div class="loader"></div>  
        <div class="card" id="data-sec" style="display:none">
          <div class="card-header card-header-primary">
            <h3 class="card-title" id="data-card-title"></h3>
            <p class="card-category" id="data-card-title2"></p>
          </div>
          <div class="card-body">
            <div id="typography">
              <div class="card-title">
                <h2>Data Pembeli</h2>
              </div>
              <div class="row" id="content-pembeli"></div>

              <div class="card-title">
                <h2>Data Pembayaran</h2>
              </div>
              <div class="row" id="content-payment"></div>

              <div class="card-title">
                <h2>Data Pesanan</h2>
              </div>
              <div class="row" id="content-order"></div>
              <div class="row mt-4" id="content-order-detail"></div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
<div class="modal fade ps-child" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">      
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderModalTitle">Bukti Transfer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center" id="bukti-trf"> 
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
  $(document).ready(function(){
    search()
  })
  function search(){
    let order_no = "{{$order_no}}";
    $.ajax({
      url : "{{ route('order.getOrder') }}",
      type: 'GET',
      data : {order_no:order_no},
      success: function(data) {
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status === 200){
          var data = jsonResponse.data['data'];
          if(data.bukti_trf){
            $('#bukti-trf').html(`<img src="${data.bukti_trf}" style="width:50%">`)
          }
          $('#data-card-title').html(`Order No: ${data.order_no}`);
          $('#data-card-title2').html(`Tanggal transaksi: ${data.transaction_date}`);
          var html = '<div class="col-md-6"><div class="tim-typo" style="margin-bottom: 20px;padding-left:30%"><p style="margin-bottom: unset;"><span class="tim-note">{key}</span>{value}</p></div></div>'
          var html2 = '<div class="col-md-12"><div class="tim-typo" style="margin-bottom: 20px;padding-left:20%"><p style="margin-bottom: unset;"><span class="tim-note">{key}</span>{value}</p></div></div>'
          var htmlDetail = ''
          htmlDetail += '<div class="col-md-3"><div class="card">'
          htmlDetail += '  <img class="card-img-top" src="{img}">'
          htmlDetail += '  <div class="card-body">'
          htmlDetail += '    <h6 class="card-category text-left text-gray">{type}</h6>'
          htmlDetail += '    <h4 class="card-title text-left">{product}</h4>'
          htmlDetail += '    <p class="card-description text-left" style="margin-bottom:5px">{harga}</p>'
          htmlDetail += '    <p class="card-description text-left" style="margin-bottom:5px">{harga_disc}</p>'
          htmlDetail += '    <p class="card-description text-left" style="margin-bottom:5px">{qty}</p>'
          htmlDetail += '    <p class="card-description text-left" style="margin-bottom:5px">{amount}</p>'
          htmlDetail += '  </div>'
          htmlDetail += '</div></div>'

          var contentPembeli = '';
          var contentOrder = '';
          var contentPayment = '';
          var contentDetail = '';
          for (const [key, value] of Object.entries(data)) {
            if(['fullname','phone','email','address'].includes(key)){
              contentPembeli += html.replace('{key}',toSentence(key)).replace('{value}',value)
            }

            if(['delivery_date','status','status_kirim','promo_name'].includes(key)){
              if(key == 'status'){
                var btn;
                switch (value.toString()) {
                  case "0":
                    btn = '<span class="text-warning h3">Menunggu</span>'
                    break;
                  case "1":
                    btn = '<span class="text-success h3">Selesai</span>'
                    break;
                  case "2":
                    btn = '<span class="text-danger h3">Batal / Dibatalkan</span>'
                    break;
                  default:
                    break;
                }
                
                contentOrder += html2.replace('{key}',toSentence(key)).replace('{value}',btn)
              }else{

                if(key == 'status_kirim'){
                  var btn;
                  switch (value.toString()) {
                    case "0":
                      var disabled = (data.payment_type == 'transfer' && data.payment_status=="0")?'disabled':'';
                      btn = `<span class="text-warning h3">Diproses</span><button class="btn btn-primary" style="padding: 10px;float:right" ${disabled} onClick=updatekirim(1,'${data.order_no}')>Kirim</button>`
                      break;
                    case "1":
                      btn = `<span class="text-info h3">DiKirim</span><button class="btn btn-primary" style="padding: 10px;float:right" onClick=updatekirim(2,'${data.order_no}')>Diterima</button>`
                      break;
                    case "2":
                      btn = '<span class="text-success h3">Diterima</span>'
                      break;
                    default:
                      break;
                  }
                  contentOrder += html2.replace('{key}',toSentence(key)).replace('{value}',btn)
                }else{
                  if(key == 'promo_name'){
                    if(value)contentOrder += html2.replace('{key}',toSentence(key)).replace('{value}',value)
                  }else{
                    contentOrder += html2.replace('{key}',toSentence(key)).replace('{value}',value)
                  }
                }
                
              }
            }

            if(['invoice_no','charge_amount','payment_type','bank','payment_status'].includes(key)){
              if(key == 'payment_status'){
                var btn;
                switch (value.toString()) {
                  case "0":
                    btn = '<span class="text-danger h3">Belum Lunas</span>'
                    break;
                  case "1":
                    btn = '<span class="text-success h3">Lunas</span>'
                    break;
                 
                  default:
                    break;
                }
                
                contentPayment += html.replace('{key}',toSentence(key)).replace('{value}',btn)
              }else{

                if(key == 'bank'){
                  if(data.payment_type == 'transfer')contentPayment += html.replace('{key}',toSentence(key)).replace('{value}',value)
                }else{
                  if(key=='charge_amount')contentPayment += html.replace('{key}',toSentence(key)).replace('{value}',formatCurrency(value))
                  else contentPayment += html.replace('{key}',toSentence(key)).replace('{value}',value)
                }

              }
            } 
          }

          if(data.payment_status=="0" && data.payment_type == 'transfer'){
            var disabled = (data.bukti_trf)?'<button class="btn btn-info ml-2" style="padding: 10px;" onClick="showModal()">Lihat Bukti Transfer</button>':''
            contentPayment += `<div style="margin-left: 30px;">
                                <button class="btn btn-primary" style="padding: 10px;" onClick="lunasi('${data.order_no}')">Lunas</button>
                                ${disabled}
                              </div>`;
          }
          
          jsonResponse.data['detail'].forEach(element => {
            var kategori;
            switch (element.kategori) {
              case 'bapok':
                kategori = 'Bahan Pokok'
                break;
              case 'sayur':
                kategori = 'Sayur'
                break;
              case 'buah':
                kategori = 'Buah'
                break;
              case 'daging':
                kategori = 'Daging'
                break;
              case 'makanan':
                kategori = 'Makanan Siap Saji'
                break;
              default:
                break;
            }
            let hd = (element.harga_discount)?formatCurrency(element.harga_discount):element.harga_discount
            contentDetail += htmlDetail.replace('{type}',kategori).replace('{product}',element.name_id)
            .replace('{harga}','Harga: Rp '+formatCurrency(element.harga))
            .replace('{harga_disc}','Harga Discount: Rp '+hd)
            .replace('{qty}','Quantity: '+element.quantity)
            .replace('{amount}','Amount: '+formatCurrency(element.amount))
            .replace('{img}',element.path_photo)
          });

          $('#content-pembeli').html(contentPembeli);
          $('#content-order').html(contentOrder);
          $('#content-payment').html(contentPayment);
          $('#content-order-detail').html(contentDetail);
          $('#data-sec').show()
          $('.loader').hide()

        }else{
          showNotification(jsonResponse.message, 'danger');
        }
      },
      error: function(xhr) { // if error occured
        var msg = xhr.responseJSON.message
        showNotification(msg,'danger')
      },
    })

  }

  function showModal(){
    $('#orderModal').modal()
  }

  function toKebab (string) {
    return string
      .replace(/[_\s]+/g, '-')
  }

  function toSentence (string) {
    const interim = toKebab(string)
      .replace(/-/g, ' ')
    return interim.slice(0, 1).toUpperCase() + interim.slice(1)
  }

  function lunasi(order_no){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }); 
    $.ajax({
      url : "{{ route('order.updatePayment') }}",
      type: 'POST',
      data : {order_no:order_no},
      success: function(data) {
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status === 200){
          search()
        }else{
          showNotification(jsonResponse.message, 'danger');
        }
      },
      error: function(xhr) { // if error occured
        var msg = xhr.responseJSON.message
        showNotification(msg,'danger')
      },
    })
  }

  function updatekirim(val,order_no){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }); 
    $.ajax({
      url : "{{ route('order.updateKirim') }}",
      type: 'POST',
      data : {status:val,order_no:order_no},
      success: function(data) {
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status === 200){
          search()
        }else{
          showNotification(jsonResponse.message, 'danger');
        }
      },
      error: function(xhr) { // if error occured
        var msg = xhr.responseJSON.message
        showNotification(msg,'danger')
      },
    })
  }

</script>
@endpush