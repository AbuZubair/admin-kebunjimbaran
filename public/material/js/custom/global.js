$(document).ready(function(){
  $("input[data-type='currency']").on({
      keyup: function() {
        formatingCurrency($(this));
      },
  });

    $('.form-register').on('submit',function(){
        $('#register-btn').prop('disable',true);
        $('#register-btn').html('')
        var html = '';
        html += 'Loading..'
        $('#register-btn').html(html)
    });

    $('#btn-vedit').click(function(e){
      e.preventDefault()
      var form = $('.form-admin')
      form.each(function(){
        var input = $(this).find(':input')          
        input.each(function(){
          var that = this
          if($(that).attr('id')){
            $(that).prop("disabled", false)            
          }
          if($(that).attr('type')=='submit')$(that).show()                    
        })
        $('#btn-vedit').hide()
      });
    });

    $('.form-admin').submit(function(e) {
      e.preventDefault();
      
      var post_url = $(this).attr("action");
      var request_method = $(this).attr("method");
      var form_data = appendForm(this);
      var redirect = $(this).attr("data-redirect")
                   
      $.ajax({
        url : post_url,
        type: request_method,
        data : form_data,
        processData: false,
        contentType: false,
        beforeSend: function() {
          showNotification('Loading..','warning')
        },
        success: function(data) {
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.notifyClose();
            showNotification(jsonResponse.message,'success')
            setTimeout(function() { 
              window.location.replace(redirect);
            }, 1000);
          }else{
            $.notifyClose();
            if(typeof jsonResponse.message === 'object' && jsonResponse.message.constructor === Object){
              var html = '';
              var msg = jsonResponse.message[Object.keys(jsonResponse.message)[0]];
              msg.forEach(element => {
                html += element + '<br>'
              });
              showNotification(html, 'danger',0);
            }else{
              showNotification(jsonResponse.message, 'danger',0);
            }            
          }
        },
        error: function(xhr) { // if error occured
          $.notifyClose();
          if(xhr.responseJSON.errors){            
            var html = '';
            var err = xhr.responseJSON.errors
            for (var key in err) {
                if (err.hasOwnProperty(key)) {
                    html += err[key] + '<br>'
                }
            }       
            showNotification(html, 'danger');
          }else{
            var msg = xhr.responseJSON.message
            showNotification(msg,'danger')
          }            
        },
      })
  });

    // $('.form-admin').submit(function(e) {
    //     e.preventDefault();
        
    //     var post_url = $(this).attr("action");
    //     var request_method = $(this).attr("method");
    //     var form_data = $(this).serialize();
    //     var redirect = $(this).attr("data-redirect")
    //     var wording = ($(this).attr("data-wording")!=undefined)?$(this).attr("data-wording"):'';
    //     var data_wording;
    //     if(wording!=''){
    //       data_wording = {
    //         id: $('input[name=id]').val(),
    //         wording: CKEDITOR.instances['konten'].getData()
    //       }

    //       $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //       }); 
    //     }
              
    //     $.ajax({
    //       url : post_url,
    //       type: request_method,
    //       data : (wording!='')?data_wording: form_data,
    //       beforeSend: function() {
    //         showNotification('Loading..','warning')
    //       },
    //       success: function(data) {
    //         var jsonResponse = JSON.parse(data);
    //         if(jsonResponse.status === 200){
    //           $.notifyClose();
    //           showNotification(jsonResponse.message,'success')
    //           setTimeout(function() { 
    //             window.location.replace(redirect);
    //           }, 1000);
    //         }else{
    //           $.notifyClose();
    //           if(typeof jsonResponse.message === 'object' && jsonResponse.message.constructor === Object){
    //             var html = '';
    //             var msg = jsonResponse.message[Object.keys(jsonResponse.message)[0]];
    //             msg.forEach(element => {
    //               html += element + '<br>'
    //             });
    //             showNotification(html, 'danger',0);
    //           }else{
    //             showNotification(jsonResponse.message, 'danger',0);
    //           }            
    //         }
    //       },
    //       error: function(xhr) { // if error occured
    //         $.notifyClose();
    //         if(xhr.responseJSON.errors){            
    //           var html = '';
    //           var err = xhr.responseJSON.errors
    //           for (var key in err) {
    //               if (err.hasOwnProperty(key)) {
    //                   html += err[key] + '<br>'
    //               }
    //           }       
    //           showNotification(html, 'danger');
    //         }else{
    //           var msg = xhr.responseJSON.message
    //           showNotification(msg,'danger')
    //         }            
    //       },
    //     })
    // });
  
})

function appendForm(myForm){
  return new FormData(myForm);
}

function deleteData(url,id) {
  var $button = $(this);
  var table = $('.dataTable').DataTable();
  $.confirm({
    title: 'Konfirmasi!',
    content: 'Yakin hapus data?',
    buttons: {
        confirm: function () {          
          $.ajax({
            url : url,
            type: 'GET',
            data : {id:id},
            beforeSend: function() {
              showNotification('Loading..','warning',1000)
            },
            success: function(data) {
              var jsonResponse = JSON.parse(data);
              if(jsonResponse.status === 200){
                $.notifyClose();
                showNotification(jsonResponse.message,'success');
                table.row( $button.parents('tr') ).remove().draw();
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

function deleteRow(){  
  var url = $('#dynamic-table').attr('delete-url')
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
            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            }); 
            $.ajax({
              url : url,
              type: 'POST',
              data : {data: arr},
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

function toggle(source) {
  var url = $('#dynamic-table').attr('data-checkbox')
  checkboxes = document.getElementsByName(url);
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}

function singleToggle(){
  document.getElementById('selectAll').checked = false
}

function add() {
  $('.form-admin').trigger("reset")
  $('input[name=id]').val('')
  $('.fileinput-preview').find('img').remove()
  var modal = $('#dynamic-table').attr('data-modal')
  $('.modal-title').html('Add')
  $('#'+modal).modal({
    focus: true,    
  })

  var input = $('.form-admin').find(':input')          
  input.each(function(){
    var that = this
    $(that).prop("disabled", false)   
  })
}

function edit(id,req = null) {
  var url = $('#dynamic-table').attr('edit-url')
  var modal = $('#dynamic-table').attr('data-modal')
  var form = $('.form-admin')
  $.ajax({
    url : url,
    type: 'GET',
    data : {id:id},
    success: function(data) {
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        form.each(function(){
          var input = $(this).find(':input')          
          input.each(function(){
            var that = this
            if($(that).attr('id') && !$(that).attr('id').includes('btn-vedit')){
              if(req!=null){
                $(that).prop("disabled", true)
              }else{
                $(that).prop("disabled", false)
              }
              if($(that).attr('id').includes('harga')){
                $(that).val(formatCurrency(jsonResponse.data[$(that).attr('id')]))
              }else{
                $(that).val(jsonResponse.data[$(that).attr('id')])
              }
              
              if($(that).attr('id') == 'photo'){
                if(jsonResponse.data['path_photo']){
                  $('.fileinput-preview').find('img').remove()
                  $('.fileinput-preview').append('<img src="'+jsonResponse.data['path_photo']+'">')
                  $('input[name="uploaded"]').val(1)
                }

                if(jsonResponse.data['ref_value']){
                  $('.fileinput-preview').find('img').remove()
                  $('.fileinput-preview').append('<img src="'+jsonResponse.data['ref_value']+'">')
                  $('input[name="uploaded"]').val(1)
                }
              }
            }
            if(req!=null && $(that).attr('type')=='submit'){              
              $(that).hide()
            } else{  
              if($(that).attr('class') && $(that).attr('class').includes('btn-ament')){
                $(that).hide()
              }else{
                $(that).show()
              }                          
            }           
          })
          $('.btn-to-edit').hide()
          $('.modal-title').html('Edit')
          if(req!=null){
            $('.btn-to-edit').show()
            $('.modal-title').html('View')
          }
          $('#'+modal).modal()
        });
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

function getData(params) {
  
}

function showNotification(msg, type, delay=''){

  $.notify({
      message: msg

  },{
      type: type,
      timer: 1000,
      placement: {
          from: 'top',
          align: 'right'
      },
      delay: (delay)?delay:3000,
      z_index:9999999999
  });
}

function formatCurrency(val) {
  return val.toString().replace('.00','').replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

function formatingCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  
  // get input value
  var input_val = input.val();
  
  // don't validate empty input
  if (input_val === "") { return; }
  
  // original length
  var original_len = input_val.length;

  // initial caret position 
  var caret_pos = input.prop("selectionStart");
    
  // check for decimal
  if (input_val.indexOf(",") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(",");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);
        
    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;
  
  }
  
  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}