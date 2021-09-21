@extends('layouts.admin', ['activePage' => 'dashboard', 'titlePage' => (Auth::user()->getRole() == '3')?'Dashboard':''])

@section('content')
  @if(Auth::user()->getRole() != '3')
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col"><h1>Welcome, {{ Auth::user()->getUsername() }}</h1></div>
        </div>

        <div class="row">
          <table id="dynamic-table" class="table yajra-datatable">
              <thead>
                  <tr>
                    <th>No</th>
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

    <!-- <div class="modal fade ps-child" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">      
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="detailModalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12 ml-auto mr-auto">
                <table class="table" id="transaction" width="50%">        
                  <tr>
                    <td style="text-align: left;">Project</td>
                    <td width="10px">:</td>
                    <td id="projectCol"></td>
                  </tr>
                  <tr>
                    <td style="text-align: left;">Date</td>
                    <td width="10px">:</td>
                    <td id="dateCol"></td>
                  </tr>
                  <tr>
                    <td style="text-align: left;">Uraian</td>
                    <td width="10px">:</td>
                    <td id="uraianCol"></td>
                  </tr>
                  <tr>
                    <td style="text-align: left;">Akuntansi</td>
                    <td width="10px">:</td>
                    <td id="akunCol"></td>
                  </tr>
                  <tr>
                    <td style="text-align: left;">Client</td>
                    <td width="10px">:</td>
                    <td id="clientCol"></td>
                  </tr>
                  <tr>
                    <td style="text-align: left;">PIC</td>
                    <td width="10px">:</td>
                    <td id="picCol"></td>
                  </tr>
                  <tr>
                    <td style="text-align: left;">Value</td>
                    <td width="10px">:</td>
                    <td id="valueCol"></td>
                  </tr>
                  <tr>
                    <td style="text-align: left;">No. Rek</td>  
                    <td width="10px">:</td>
                    <td id="norekCol"></td>                
                  </tr>                     
                </table>
              </div>
            </div>
                        
          </div>
        </div>
      </div>
    </div> -->
  @endif

@endsection

@push('js')
  <script>
    var table;
    $(document).ready(function() {
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
              { data: null,"sortable": false, 
                render: function (data, type, row, meta) {
                          return meta.row + meta.settings._iDisplayStart + 1;
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
          dom: '<"toolbar">lfrtip',
      });

    });

   

    function refresh() {
      window.location.reload(true);
    }

     setTimeout(refresh, 300000);
  </script>
@endpush