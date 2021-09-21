@extends('layouts.admin', ['activePage' => 'whitelist', 'titlePage' => __('Whitelist Promo')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12" style="overflow-x:auto !important">
        <table id="dynamic-table" class="table yajra-datatable" delete-url="{{url('whitelist/delete')}}" edit-url="{{url('whitelist/edit')}}" data-modal="whitelistModal" data-checkbox="whitelists">
            <thead>
                <tr>
                    <th class="text-center r-sort"><input type="checkbox" id="selectAll" value="selectAll" onClick="toggle(this)"/></th>
                    <th>No HP</th>         
                    <th>
                      <select class="form-control" name="search_is_active" id="search_status">
                        <option value="" selected>Status</option>
                        <option value="Y">Aktif</option>
                        <option value="N">Nonaktif</option>
                      </select>
                    </th>       
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
<div class="modal fade" id="whitelistModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">      
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="whitelistModalTitle">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include('whitelist.form')
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
        ajax: "{{ route('whitelist.list') }}",
        columns: [
            {data:null,render:function(data,type,full,meta){
                return '<input type="checkbox" name="whitelists" value="'+data.id+'" onClick="singleToggle()"/>';                    
              }
            },
            {data: 'ref_value', name: 'ref_value'},
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
                html += '<button class="btn btn-sm btn-success btn-edit-whitelist" onClick="edit('+data.id+')">Edit</button>';
                html += '<button class="btn btn-sm btn-info" onClick="edit('+data.id+',1)">View</button>';
                return html
              }
            },
        ],
        columnDefs: [
          {"targets": 0, "orderable": false, "className": 'text-center'},
          {"targets": 2, "orderable": false},
        ],
        dom: '<"toolbar">frtip',
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'select', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
    });

    var btn = '<button class="btn btn-primary" onClick="add()"><i class="material-icons">add</i></button>'
    btn += '<button class="btn btn-danger" onClick="deleteRow()"><i class="material-icons">delete</i></button>'
    $("div.toolbar").html(btn);
        
  });   
</script>
@endpush