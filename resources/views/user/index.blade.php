@extends('layouts.admin', ['activePage' => 'user', 'titlePage' => __('User')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12" style="overflow-x:auto !important">
        <table id="dynamic-table" class="table yajra-datatable" delete-url="{{url('user/delete')}}" edit-url="{{url('user/edit')}}" data-modal="userModal" data-checkbox="users">
            <thead>
                <tr>
                    <th class="text-center r-sort"><input type="checkbox" id="selectAll" value="selectAll" onClick="toggle(this)"/></th>
                    <th>User</th>
                    <th>Phone Number</th>                    
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
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">      
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalTitle">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include('user.form')
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
        ajax: "{{ route('user.list') }}",
        columns: [
            {data:null,render:function(data,type,full,meta){
                return '<input type="checkbox" name="users" value="'+data.rowID+'" onClick="singleToggle()"/>';                    
              }
            },
            {data: 'username', name: 'username'},
            {data: 'phoneNumber', name: 'phoneNumber'},
            {data:null,render:function(data,type,full,meta){
                return '<button class="btn btn-sm btn-success" onClick="edit('+data.rowID+')">Edit</button>';                    
              }
            },
        ],
        columnDefs: [
          {"targets": 0, "orderable": false, "className": 'text-center'}
        ],
        dom: '<"toolbar">frtip'
    });

    var btn = '<button class="btn btn-primary" onClick="add()"><i class="material-icons">add</i></button>'
    btn += '<button class="btn btn-danger" onClick="deleteRow()"><i class="material-icons">delete</i></button>'    
    $("div.toolbar").html(btn);
        
  });  
  
</script>
@endpush