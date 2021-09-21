@extends('layouts.admin', ['activePage' => 'delivery', 'titlePage' => __('Delivery Day')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12" style="overflow-x:auto !important">
        <table id="dynamic-table" class="table yajra-datatable" delete-url="{{url('delivery/delete')}}" edit-url="{{url('delivery/edit')}}" data-modal="deliveryModal" data-checkbox="deliverys">
            <thead>
                <tr>
                    <th class="text-center r-sort"><input type="checkbox" id="selectAll" value="selectAll" onClick="toggle(this)"/></th>
                    <th>Value</th>         
                    <th>Status</th>       
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="deliveryModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">      
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deliveryModalTitle">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include('delivery.form')
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
   $(document).ready(function(){
    
    var table = $('.yajra-datatable').DataTable({
        processing: true,        
        serverSide: true,
        ajax: "{{ route('delivery.list') }}",
        columns: [
            {data:null,render:function(data,type,full,meta){
                return '<input type="checkbox" name="deliverys" value="'+data.id+'" onClick="singleToggle()"/>';                    
              }
            },
            {data: 'ref_value', render:function(data,type,full,meta){
                $day = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu" ];
                return $day[data]
              }
            },
            {data: 'is_active', render:function(data,type,full,meta){
              var html = ''
                if(data == 'Y'){
                  html += '<span class="alert alert-warning" style="padding:5px 7px" role="alert">Aktif</span>'
                  return html
                }else{
                  html += '<span class="alert alert-danger" style="padding:5px 7px" role="alert">Non Aktif</span>'
                  return html
                }
              }
            },
            {data:null,render:function(data,type,full,meta){
              html = ''
                html += '<button class="btn btn-sm btn-success btn-edit-delivery" onClick="edit('+data.id+')">Edit</button>';
                html += '<button class="btn btn-sm btn-info" onClick="edit('+data.id+',1)">View</button>';
                return html
              }
            },
        ],
        columnDefs: [
          {"targets": 0, "orderable": false, "className": 'text-center'}
        ],
        dom: '<"toolbar">frtip'
    });

    // var btn = '<button class="btn btn-primary" onClick="add()"><i class="material-icons">add</i></button>'
    // var btn = '<button class="btn btn-danger" onClick="deleteRow()"><i class="material-icons">delete</i></button>'
    // $("div.toolbar").html(btn);
        
  });   
</script>
@endpush