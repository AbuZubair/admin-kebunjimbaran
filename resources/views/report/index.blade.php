@extends('layouts.admin', ['activePage' => 'report', 'titlePage' => __('Report')])

@push('css')
<style>
  .tableList tbody tr.groupTR td.groupTitle {
      background-color: #9c27b0 !important;
      padding: 5px 10px !important;
      color: #fff;
  }
  .tableList tbody tr.groupTR td.groupTD {
      background-color: #9c27b0;
      font-size: 12px;
      white-space: normal;
      color: #fff;
  }
  div.dt-buttons {
    width: 100%;
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
            <div class="form-group">
              <label for="tgl_daftar">Type</label>
              <select class="form-control" name="searchType" id="searchType">    
                <option value="" disabled selected>Select your option</option>    
                <option value="penjualan">Penjualan</option>
                <option value="penjualanByProduct">Penjualan By Product</option>
                <option value="stock">Stock</option>
              </select>
            </div>
            <form class="searchForm" style="display: none;">                  
              <div class="row date-sec">
                <div class="col-md-6">              
                  <div class="form-group">
                    <label for="tgl_daftar">Year</label>
                    <select class="form-control" name="searchYear" id="searchYear">
                      <option value="" disabled selected>Select your option</option>
                    </select>
                  </div>                                 
                </div>   
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tgl_daftar">Month</label>
                    <select class="form-control" name="searchMonth" id="searchMonth">
                      <option value="" disabled selected>Select your option</option>
                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mei</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>  
                </div>            
              </div>

              <div class="row">
                <div class="col-md-6 product-sec">              
                  <div class="form-group">
                    <label for="tgl_daftar">Product</label>
                    <select class="form-control" name="searchProduct" id="searchProduct">
                    </select>
                  </div>                                 
                </div>   
              </div>

            </form>                        
          </div>                   
          <div class="card-footer" style="justify-content: normal !important;display:none">
            <button class="btn btn-success" onClick="search()" >Search</button>
            <button class="btn btn-warning" onClick="reset()" >Reset</button>
          </div>   
        </div>    

        <table id="penjualan-table" class="table yajra-datatable tableList" style="width:100%;display:none">
            <thead>
                <tr>
                  <th>No</th> 
                  <th>Order No</th>   
                  <th>Invoice No</th>                 
                  <th>Date</th>                 
                  <th>Payment Method</th>  
                  <th>Bank</th>    
                  <th>Nama</th>
                  <th>Phone</th>
                  <th>Link</th> 
                  <th>Link</th>
                  <th>Product</th> 
                  <th>Quantity</th>
                  <th>Harga</th>
                  <th>Harga Discount</th>
                  <th>Amount</th>
                </tr>      
            </thead>
            <tbody>
            </tbody>
        </table>

        <table id="stock-table" class="table yajra-datatable tableList" style="width:100%;display:none">
            <thead>
                <tr>
                  <th>No</th>                    
                  <th>Produk</th>                                
                  <th>Stock</th>
                  <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <table id="penjualanByPrd-table" class="table yajra-datatable tableList" style="width:100%;display:none">
            <thead>
                <tr>
                  <th>No</th> 
                  <th>Product</th>   
                  <th>Order No</th> 
                  <th>Date</th>  
                  <th>Harga</th>
                  <th>Harga Discount</th>
                  <th>Qty</th>              
                  <th>Amount</th>                 
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
  var table1;
  var table2; 
  var table3; 
  var listProject;
  var role = "{{Auth::user()->getRole()}}"
  $(document).ready(function(){    
    $('select[name=searchType]').on('change', function() {
      if(this.value == 'penjualan'){
        $('.searchForm').show()
        $('.card-footer').show()
        $('#penjualan-table').show()
        $('#stock-table').hide()            
        $('.product-sec').hide() 
        $('#penjualan-table_wrapper').show()
        $('#stock-table_wrapper').hide()
        $('#penjualanByPrd-table_wrapper').hide()
      }else if(this.value == 'penjualanByProduct'){
        $('.searchForm').show()
        $('.card-footer').show()
        $('#penjualan-table').hide() 
        $('#penjualanByPrd-table').show() 
        $('#stock-table').hide()            
        $('.product-sec').show()   
        $('#penjualanByPrd-table_wrapper').show()
        $('#penjualan-table_wrapper').hide()
        $('#stock-table_wrapper').hide()
      }else{
        $('.searchForm').hide()
        $('.card-footer').hide()
        $('#penjualan-table').hide()  
        $('#stock-table').show()
        $('.product-sec').hide() 
        $('#stock-table_wrapper').show()
        $('#penjualan-table_wrapper').hide()
        $('#penjualanByPrd-table_wrapper').hide()
      }      
    });    
    dtPenjualan()
    dtStock()
    dtPenjualanByPrd() 
    $('#penjualan-table_wrapper').hide()
    $('#stock-table_wrapper').hide()
    $('#penjualanByPrd-table_wrapper').hide()
    generateArrayOfYears()
    getDropdown()
  });  

  function dtPenjualan(){
    var groupCol = 1;
    table1 = $('#penjualan-table').DataTable({  
        serverSide: true,        
        dom: "Blf<'clear'><'H'r>t<'F'>p",
        retrieve: true,
        searchable: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        orderFixed: [
          [groupCol, 'asc']
        ],
        columnDefs: [
          {
            searchable: false,
            orderable: false,
            targets: 0,
            className: 'text-center'
          }, 
          {
            visible: false,
            targets: [9,1]
          }
        ],
        ajax: {
            url: "{{ route('report.list') }}",
            data: function ( d ) {                         
                d.searchYear = $('select[name=searchYear]').val();
                d.searchMonth = $('select[name=searchMonth]').val();
                d.type= "penjualan"
            }, 
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            {data:null,name:''},
            {data: 'order_no', name: 'order_no'},                  
            {data: 'invoice_no', name: 'invoice_no'},
            {data: 'transaction_date', name: 'transaction_date'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'bank', name: 'bank'},
            {data: 'fullname', name: 'fullname'},
            {data: 'phone', name: 'phone'},
            {data: 'link', render:function(data,type,full,meta){
                if(data)return `<a href="${data}" target="_blank">link</a>`
                  return '-'
              }
            },
            {data: 'link', name: 'link'},
            // {data: 'charge_amount', render:function(data,type,full,meta){
            //     if(data)return formatCurrency(data)
            //       return '-'
            //   }
            // },
            {data: 'name_id', name: 'name_id'},
            {data: 'quantity', name: 'quantity'},
            {data: 'harga', render:function(data,type,full,meta){
                if(data)return formatCurrency(data)
                  return '-'
              }
            },
            {data: 'harga_discount', render:function(data,type,full,meta){
                if(data)return formatCurrency(data)
                  return '-'
              }
            },
            {data: 'amount', render:function(data,type,full,meta){
                if(data)return formatCurrency(data)
                  return '-'
              }
            },
        ],
        buttons: [
          {
            extend: 'excel',
            exportOptions: {
              columns: [ 1, 2, 3, 4, 5, 6, 7, 9, 10,11,12,13,14],
              total_index: [12],
              grouped_array_index: [0],
              isGrand: true,
              total_grand_index: [12]
            },
            title: 'Penjualan_'+getDate()
          }
        ],
        drawCallback: function (settings) {
            var that = this;
            if (settings.bSorted || settings.bFiltered) {
                this.$('td:first-child', {
                    "filter": "applied"
                }).each(function (i) {
                    that.fnUpdate(i + 1, this.parentNode, 0, false, false);
                });
            }

            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var rowsData = api.rows({
                page: 'current'
            }).data();

            var last = null;
            var subTotal = new Array();
            var grandTotal = new Array();
            var groupID = -1;
            var length = api.column(groupCol, {page: 'current' }).data().length

            api.column(groupCol, {
                page: 'current'
            }).data().each(function (group, i) {
                $.each($(rows).eq(i), function (colIndex, colValue) {
                  $(this).find('td').eq(0).html(i+1)
                })     
                var last_ = (last)?last.toLowerCase().trim():last
                var group_ = (group)?group.toLowerCase().trim():group
                if (last_ !== group_) {      
                    groupID++;
                    if(last!==null)$(rows).eq(i).before("<tr class='groupTR'><td colspan='11' class='groupTitle'>Order No: " + last + "</td><td class='text-right' style='background-color:#9c27b0;color: #fff;'>Total</td></tr>");                    
                    last = group;                       
                }
                // if(i==length-1)$(rows).eq(i).after("<tr class='groupTR'><td colspan='11' class='groupTitle'>" + last + "</td><td class='text-right' style='background-color:#9c27b0;color: #fff;'>Total</td></tr>");                    

                if(i==length-1){
                  html_ = "<tr class='groupTR'><td colspan='11' class='groupTitle'>Order No: " + last + "</td><td class='text-right' style='background-color:#9c27b0;color: #fff;'>Total</td></tr>"
                  html_ += "<tr class='grandGT'><td colspan='12' class='groupTitle'><b>Grandtotal</b></td><td class='text-right></td></tr>"
                  $(rows).eq(i).after(html_);                    
                }

                //Sub-total of each column within the same grouping
                var val = api.row(api.row($(rows).eq(i)).index()).data(); //Current order index              
                $.each(val, function (colIndex, colValue) {
                    if (typeof subTotal[groupID] == 'undefined') {
                        subTotal[groupID] = new Array();
                    }
                    if (typeof subTotal[groupID][colIndex] == 'undefined') {
                        subTotal[groupID][colIndex] = 0;
                    }
                    if (typeof grandTotal[colIndex] == 'undefined') {
                        grandTotal[colIndex] = 0;
                    }

                    value = colValue ? parseFloat(colValue) : 0;
                    subTotal[groupID][colIndex] += value;
                    grandTotal[colIndex] += value;
                });

                // if(i==length-1)$(rows).eq(i).after("<tr class='groupTR'><td colspan='8' class='groupTitle'>Grand Total</td><td class='text-right' style='background-color:#9c27b0;color: #fff;'></td></tr>");                    
            });      
            
            $('#penjualan-table tbody').find('.groupTR').each(function (i, v) {                     
                var rowCount = $(this).nextUntil('.groupTR').length;
                var subTotalInfo = "";                          
                subTotalInfo += "<td class='groupTD'>" + subTotal[i].amount.toFixed(2).toString().replace('.00','').replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "</td>"; 
                $(this).append(subTotalInfo);
            });

            $('#penjualan-table tbody').find('.grandGT').each(function (i, v) {                          
                var gt = "";                          
                gt += "<td class='grandTD'>" + grandTotal.amount.toFixed(2).toString().replace(".00","").replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "</td>"; 
                $(this).append(gt);
            });
                                  
        }
    });

    $( '#pengeluaran-table thead .column_search' ).on( 'keyup change clear', function () {
        var col = $(this).attr('name').split('_')[1]
        table1.column(col).search(this.value).draw()       
    } );
     
  }

  function dtPenjualanByPrd(){
    var groupCol = 2;
    table3 = $('#penjualanByPrd-table').DataTable({  
        serverSide: true,        
        dom: "Blf<'clear'><'H'r>t<'F'>p",
        retrieve: true,
        searchable: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        columnDefs: [
          {
            searchable: false,
            orderable: false,
            targets: 0,
            className: 'text-center'
          }, 
        ],
        ajax: {
            url: "{{ route('report.list') }}",
            data: function ( d ) {                         
                d.searchYear = $('select[name=searchYear]').val();
                d.searchMonth = $('select[name=searchMonth]').val();
                d.product = $('select[name=searchProduct]').val();
                d.type= "penjualanByProduct"
            }, 
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            {data:null,name:''},
            {data: 'name_id', name: 'name_id'},                  
            {data: 'order_no', name: 'order_no'},
            {data: 'transaction_date', name: 'transaction_date'}, 
            {data: 'harga', render:function(data,type,full,meta){
                if(data)return formatCurrency(data)
                  return '-'
              }
            },
            {data: 'harga_discount', render:function(data,type,full,meta){
                if(data)return formatCurrency(data)
                  return '-'
              }
            },
            {data: 'quantity', name: 'quantity'},
            {data: 'amount', render:function(data,type,full,meta){
                if(data)return formatCurrency(data)
                  return '-'
              }
            },
        ],
        buttons: [
          {
            extend: 'excel',
            exportOptions: {
              columns: [ 1, 2, 3, 4, 5, 6, 7],
              total_index: [6]
            },
            title: 'PenjualanByProduct_'+getDate()
          }
        ],
        drawCallback: function (settings) {
            var that = this;
            if (settings.bSorted || settings.bFiltered) {
                this.$('td:first-child', {
                    "filter": "applied"
                }).each(function (i) {
                    that.fnUpdate(i + 1, this.parentNode, 0, false, false);
                });
            }

            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var rowsData = api.rows({
                page: 'current'
            }).data();

            var last = null;
            var subTotal = new Array();
            var grandTotal = new Array();
            var groupID = -1;
            var length = api.column(groupCol, {page: 'current' }).data().length

            api.column(groupCol, {
                page: 'current'
            }).data().each(function (group, i) {
                $.each($(rows).eq(i), function (colIndex, colValue) {
                  $(this).find('td').eq(0).html(i+1)
                })     
                var last_ = (last)?last.toLowerCase().trim():last
                var group_ = (group)?group.toLowerCase().trim():group
                if (last_ !== group_) {      
                    groupID++;
                    // if(last!==null)$(rows).eq(i).before("<tr class='groupTR'><td colspan='3' class='groupTitle'>" + last + "</td><td class='text-right' style='background-color:#9c27b0;color: #fff;'>Total</td></tr>");                    
                    last = group;                       
                }
                // if(i==length-1)$(rows).eq(i).after("<tr class='groupTR'><td colspan='3' class='groupTitle'>" + last + "</td><td class='text-right' style='background-color:#9c27b0;color: #fff;'>Total</td></tr>");                    

                //Sub-total of each column within the same grouping
                var val = api.row(api.row($(rows).eq(i)).index()).data(); //Current order index              
                $.each(val, function (colIndex, colValue) {
                    if (typeof subTotal[groupID] == 'undefined') {
                        subTotal[groupID] = new Array();
                    }
                    if (typeof subTotal[groupID][colIndex] == 'undefined') {
                        subTotal[groupID][colIndex] = 0;
                    }
                    if (typeof grandTotal[colIndex] == 'undefined') {
                        grandTotal[colIndex] = 0;
                    }

                    value = colValue ? parseFloat(colValue) : 0;
                    subTotal[groupID][colIndex] += value;
                    grandTotal[colIndex] += value;
                });

                if(i==length-1)$(rows).eq(i).after("<tr class='groupTR'><td colspan='6' class='groupTitle'>Grand Total</td><td class='text-right' style='background-color:#9c27b0;color: #fff;'></td></tr>");                    
            });      
            
            $('#penjualanByPrd-table tbody').find('.groupTR').each(function (i, v) {                          
                var rowCount = $(this).nextUntil('.groupTR').length;
                var subTotalInfo = "";                          
                subTotalInfo += "<td class='groupTD'>" + grandTotal.amount.toFixed(2).toString().replace('.00','').replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "</td>"; 
                $(this).append(subTotalInfo);
            });
                                  
        }
    });     
  }

  function dtStock(){
    table2 = $('#stock-table').DataTable({  
        serverSide: true,        
        dom: "Blf<'clear'><'H'r>t<'F'>p",
        retrieve: true,
        searchable: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        // orderFixed: [
        //   [groupCol, 'asc']
        // ],
        columnDefs: [
          {
            searchable: false,
            orderable: false,
            targets: 0,
            className: 'text-center'
          }, 
        ],
        ajax: {
            url: "{{ route('report.list') }}",
            data: function ( d ) {                         
                d.type= "stock"
            }, 
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            {data:null,name:''},
            {data: 'name_id', name: 'name_id'},           
            {data: 'stock', name: 'stock'},           
            {data: 'is_active',render:function(data,type,full,meta){
                switch (data) {
                  case "Y":
                    return 'Aktif'
                    break;
                  case "N":
                    return 'Non aktif'
                    break;
                  default:
                    break;
                }
              }
            },     
        ],
        buttons: [
          {
            extend: 'excel',
            exportOptions: {
              columns: [ 1, 2, 3],
              // total_index: [1]
            },
            title: 'Export_'+getDate()
          }
        ],
        drawCallback: function (settings) {
            var that = this;
            if (settings.bSorted || settings.bFiltered) {
                this.$('td:first-child', {
                    "filter": "applied"
                }).each(function (i) {
                    that.fnUpdate(i + 1, this.parentNode, 0, false, false);
                });
            }

            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
           
            api.column('', {
                page: 'current'
            }).data().each(function (group, i) {
                $.each($(rows).eq(i), function (colIndex, colValue) {
                  $(this).find('td').eq(0).html(i+1)
                })     

            });      
          
                                  
        }
    });
  }

  function getDropdown(){
    return new Promise((resolve,reject) => {
      $.ajax({
        url : '{{url("report/dropdown")}}',
        type: 'GET',
        success: function(data) {
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            var data = jsonResponse.data
            $('#searchProduct').find('option').remove()
            $('#searchProduct').append('<option value=" " selected>All Product</option>')
            data.product.forEach(element => {
              $('#searchProduct').append($('<option>', { 
                  value: element.id,
                  text : element.name_id 
              }));
            });
                  
            $('#searchProduct').select2({
              placeholder: "Please Select",
              allowClear: true
            });
                            
            resolve()
          }else{
            reject()
            showNotification(jsonResponse.message, 'danger');
          }
        },
        error: function(xhr) { // if error occured
          var msg = xhr.responseJSON.message
          showNotification(msg,'danger')
          reject()
        },
      })
    })   
  }

  function generateArrayOfYears() {
    var max = new Date().getFullYear()
    var min = max - 5

    $('#searchYear').find('option').remove()

    $('#searchYear').append('<option value="" disabled selected>Select your option</option>')

    for (var i = max; i >= min; i--) {
      $('#searchYear').append($('<option>', { 
          value: i,
          text : i 
      }));
    }

  }

  function search(){
    if($('select[name=searchType').val()=='penjualan'){      
      table1.ajax.reload()    
    }else{
      table3.ajax.reload()  
    }
    showHideTable()
  }

  function reset(){
    $('.searchForm').trigger("reset")
    $('#searchProject').val("").trigger('change')
    $('select[name=searchProject]').val("")
    table1.ajax.reload()
  }

  function getDate(){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '-' + dd + '-' + yyyy + ' '+today.getHours()+':'+today.getMinutes()+':'+today.getSeconds();
    return today;
  }

  function formatDate(date){
    var dts = new Date(date);
    var dd = String(dts.getDate()).padStart(2, '0');
    var mm = String(dts.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = dts.getFullYear();

    dts = dd + '-' + mm + '-' + yyyy;
    return dts;
  }

  function showHideTable(){
    if($('select[name=searchType').val()=='penjualan'){      
      $('#jurnal-table').show()
      $('button[aria-controls=jurnal-table]').show()
      $('#pengeluaran-table').hide()
      $('button[aria-controls=pengeluaran-table]').hide()          
      $('#jurnal-besar-table').hide()      
      $('button[aria-controls=jurnal-besar-table]').hide()  
    }else{
      $('#jurnal-table').hide()
      $('button[aria-controls=jurnal-table]').hide()

      if($('select[name=searchType').val()=='pengeluaran'){
        $('#pengeluaran-table').show()      
        $('button[aria-controls=pengeluaran-table]').show()         
        $('#jurnal-besar-table').hide()      
        $('button[aria-controls=jurnal-besar-table]').hide()
      }else{
        $('#pengeluaran-table').hide()
        $('button[aria-controls=pengeluaran-table]').hide()            
        $('#jurnal-besar-table').show()      
        $('button[aria-controls=jurnal-besar-table]').show()         
      }
      
    }
  }

  function formattingDate(month,year){
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];    
    let mth = parseInt(month) - 1
    return mth+' '+year
  }
</script>
@endpush