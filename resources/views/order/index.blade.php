@extends('layouts.admin', ['activePage' => 'order', 'titlePage' => __('Pesanan')])

@push('css')
<style>
  div.dt-buttons {
    float: none;
  }
</style>
@endpush

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12" style="overflow-x:auto !important">        
        <div class="card">          
          <div class="card-body">
            <form class="searchForm">                  
              <div class="form-group">
                <label class="col-sm-6 col-form-label">{{ __('Order No.*') }}</label>
                <div class="col-sm-12">
                  <div class="form-group{{ $errors->has('search_orderNo') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('search_orderNo') ? ' is-invalid' : '' }}" name="search_orderNo" id="search_orderNo" type="text" placeholder="{{ __('Search Order No.') }}" value="{{ old('search_orderNo', isset($data) ? $data['search_orderNo'] : '') }}" required />
                  </div>
                </div>
              </div>
            </form>                      
          </div>                   
          <div class="card-footer" style="justify-content: end !important;">
            <button class="btn btn-success" onClick="search()" >Search</button>
            <button class="btn btn-warning" onClick="reset()" >Reset</button>
          </div>   
        </div>    

        <table id="dynamic-table" class="table yajra-datatable" delete-url="{{url('order/delete')}}" edit-url="{{url('order/edit')}}" data-modal="orderModal" data-checkbox="orders">
            <thead>
                <tr>
                <th class="text-center r-sort"><input type="checkbox" id="selectAll" value="selectAll" onClick="toggle(this)"/></th>
                  <th>Order No</th>                 
                  <th>Date</th>                 
                  <th>Name</th>  
                  <th>Phone</th>   
                  <th>Total Amount</th> 
                  <th>Payment Method</th>
                  <th>Status</th> 
                  <th>Status Kirim</th>
                  <th>Status Pembayaran</th>
                  <th></th>
                </tr>      
            </thead>
            <tbody>
            </tbody>
        </table>

      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
  var table;
  var role = "{{Auth::user()->getRole()}}"
  $(document).ready(function(){    
    table = $('.yajra-datatable').DataTable({
        processing: true,        
        serverSide: true,
        ajax: {
            url: "{{ route('order.list') }}",
            data: function ( d ) {                         
                d.order_no = $('input[name=search_orderNo]').val();
            }, 
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            {data:null,render:function(data,type,full,meta){
                return '<input type="checkbox" name="orders" value="'+data.order_no+'" onClick="singleToggle()"/>';                    
              }
            },
            {data: 'order_no', name: 'order_no'},                  
            {data: 'transaction_date', name: 'transaction_date'},
            {data: 'fullname', name: 'fullname'},
            {data: 'phone', name: 'phone'},
            {data: 'charge_amount', render:function(data,type,full,meta){
                if(data)return formatCurrency(data)
                  return '-'
              }
            },
            {data: 'payment_type', name: 'payment_type'},
            {data: 'status', render:function(data,type,full,meta){
                var html = '-'
                if(data == '0'){
                  html = '<span class="alert alert-warning" style="padding:5px 7px" role="alert">Waiting</span>'
                }
                if(data == '1'){
                  html = '<span class="alert alert-success" style="padding:5px 7px" role="alert">Done</span>'
                }
                if(data == '2'){
                  html = '<span class="alert alert-danger" style="padding:5px 7px" role="alert">Cancel</span>'
                }
                  
                return html
              }
            },
            {data: 'status_kirim', render:function(data,type,full,meta){
                var html = '-'

                if(data == '0'){
                  html = 'Proses'
                }
                if(data == '1'){
                  html = 'Kirim'
                }
                if(data == '2'){
                  html = 'Diterima'
                }

                return html
              }
            },
            {data: 'payment_status', render:function(data,type,full,meta){
                var html = '-'

                if(data == '1'){
                  html = '<span class="alert alert-success" style="padding:5px 7px" role="alert">Lunas</span>'
                }
                if(data == '0'){
                  html = '<span class="alert alert-danger" style="padding:5px 7px" role="alert">Belum Bayar</span>'
                }
                    
                return html
              }
            },
            {data:null,render:function(data,type,full,meta){
                html = '<a class="btn btn-sm btn-info" href="{{ route("order.detail") }}?order_no='+data.order_no+'">View</a>';
                return html
              }
            
            },
        ],
        columnDefs: [
          {"targets": 0, "orderable": false, "className": 'text-center'},
        ],
        // buttons: [
        //   {
        //     extend: 'excel',
        //     exportOptions: {
        //       columns: [ 1, 2, 3, 4, 5, 6, 7, 9],
        //       total_index: [4]
        //     },
        //     title: 'Pesanan_'+getDate()
        //   }
        // ],
        // dom: 'B<"toolbar">lfrtip',
        dom: '<"toolbar">lfrtip',
    });

    var btn = `<button class="btn btn-primary" onClick="act('lunas')">Lunas</button>`
    btn += `<button class="btn btn-info" onClick="act('kirim')">Kirim</button>`
    btn += `<button class="btn btn-tertiary" onClick="act('terima')">Terima</button>`
    $("div.toolbar").html(btn);

  }); 

  function getDate(){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '-' + dd + '-' + yyyy + ' '+today.getHours()+':'+today.getMinutes()+':'+today.getSeconds();
    return today;
  } 

  function search(){
    table.ajax.reload() 
  }

  function reset(){
    $('.searchForm').trigger("reset")
    table.ajax.reload()
  }

  function act(type){  
    var arr = []
    var table = $('#dynamic-table').DataTable();
    var rowcollection =  table.$("input[type=checkbox]", {"page": "all"});
    rowcollection.each(function(index,elem){      
      if($(elem).prop("checked")){
        var checkbox_value = $(elem).val();
        arr.push(checkbox_value)
      }        
    });
    if(arr.length == 0){
      showNotification('Silahkan pilih salah satu', 'danger');
    }else{
      $.confirm({
        title: 'Confirmation!',
        content: 'Are you sure??',
        buttons: {
            confirm: function () {
              var url;
              var data;
              switch (type) {
                case "lunas":
                  url = "{{ route('order.updatePayment') }}";
                  data = {data: arr}
                  break;
                case "kirim":
                  url = "{{ route('order.updateKirim') }}";
                  data = {data: arr,status:1}
                  break;
                case "terima":
                  url = "{{ route('order.updateKirim') }}";
                  data = {data: arr,status:2}
                  break;
                default:
                  break;
              }

              $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              }); 
              $.ajax({
                url : url,
                type: 'POST',
                data : data,
                beforeSend: function() {
                  showNotification('Loading..','warning',1000)
                },
                success: function(data) {
                  var jsonResponse = JSON.parse(data);
                  if(jsonResponse.status === 200){
                    $.notifyClose();
                    showNotification(jsonResponse.message,'success');
                    table.rows().invalidate('data').draw(false);
                  }else{
                    showNotification(jsonResponse.message, 'danger');
                  }
                },
                error: function(xhr) { // if error occured
                  var msg = xhr.responseJSON.message
                  showNotification(msg,'danger')
                },
              })
            },
            cancel: function () {
              return;
            },
        }
      }); 
    }
  }

</script>
@endpush