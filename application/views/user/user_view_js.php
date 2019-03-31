
  <script type="text/javascript">
   
  var table;
  var save_method;
  var table_user_status;

  $('.my-colorpicker1').colorpicker();
    //color picker with addon
  $('.my-colorpicker2').colorpicker();
   
  $(document).ready(function() {
   
      //datatables
      table = $('#table').DataTable({ 
   
          "processing": false, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.
   
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": "<?php echo base_url('admin/user/ajax_list')?>",
              "type": "POST"
          },
   
          //Set column definition initialisation properties.
          "columnDefs": [
          { 
              "targets": [ 0 ], //first column / numbering column
              "orderable": false, //set not orderable
          },
          ],
   
      });

      table_user_status = $('#table_user_status').DataTable({ 
 
        "processing": false, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('admin/user/status_ajax_list')?>",
            "type": "POST"
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
 
    });
   
  });

  function reload_table()
  {
    table.ajax.reload(null, false);
  }

  function add()
  {
    $.ajax({
      url : "<?php echo base_url('admin/user/ajax_privilages')?>",
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        save_method = 'add';
        $('[name="password"]').attr("disabled",false);
        $('#form_add')[0].reset();
        $('select[name="priv"]').empty();
        $('select[name="priv"]').append('<option value="">-</option>');
        $.each(data.data, function(key, value) {
          $('select[name="priv"]').append('<option value="'+ value.id_priv +'">'+ value.desc_priv +'</option>');
        });
        $('select[name="status"]').empty();
        $('select[name="status"]').append('<option value="">-</option>');
        $.each(data.status, function(key, value) {
          $('select[name="status"]').append('<option value="'+ value.id_user_status +'">'+ value.desc_user_status +'</option>');          
        });
        $('#modal_add .modal-title').text('Add User');
        $('#modal_add').modal('show'); // show bootstrap modal
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          notification('Error loading from ajax !!!');
      }
    });
  }

  function reset(id, name)
  {
    $('#form_reset')[0].reset();
    $('#modal_reset .modal-title').text('Reset Password '+name);
    $('#modal_reset').modal('show'); // show bootstrap modal
    $('[name="id_user"]').val(id);
  }

  function edit(id)
  {
    save_method = 'edit';
    $('#form_add')[0].reset();
    $.ajax({
      url : "<?php echo base_url('admin/user/ajax_edit/')?>"+id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        $('[name="id_user"]').val(data.result.id_user);
        $('[name="username"]').val(data.result.username);
        $('[name="username2"]').val(data.result.username);
        $('[name="password"]').attr("disabled",'disabled');
        $('select[name="priv"]').empty();
        $('select[name="priv"]').append('<option value="">-</option>');
        $.each(data.data, function(key, value) {
          if (value.id_priv == data.result.privilages_user) {
            $('select[name="priv"]').append('<option value="'+ value.id_priv +'" selected="selected">'+ value.desc_priv +'</option>');
          }
          else{
            $('select[name="priv"]').append('<option value="'+ value.id_priv +'">'+ value.desc_priv +'</option>');
          }          
        });
        $('select[name="status"]').empty();
        $('select[name="status"]').append('<option value="">-</option>');
        $.each(data.status, function(key, value) {
          if (value.id_user_status == data.result.flag) {
            $('select[name="status"]').append('<option value="'+ value.id_user_status +'" selected="selected">'+ value.desc_user_status +'</option>');
          }
          else{
            $('select[name="status"]').append('<option value="'+ value.id_user_status +'">'+ value.desc_user_status +'</option>');
          }          
        });
        $('#modal_add .modal-title').text('Edit User');
        $('#modal_add').modal('show'); // show bootstrap modal
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          notify('error', 'error', 'Error loading from ajax');
      }
    });
  }

  function save()
  {
    var url;
    var form_data;
    if(save_method == 'add')
    {
      url = "<?php echo base_url('admin/user/add_user')?>";
      form_data = $('#form_add').serialize();
    }
    else if(save_method == 'edit')
    {
      url = "<?php echo base_url('admin/user/update')?>";
      form_data = $('#form_add').serialize();
    }
    else if(save_method == 'add_status')
    {
      url = "<?php echo base_url('admin/user/add_status')?>";
      form_data = $('#form_status').serialize();
    }
    else if(save_method == 'edit_status')
    {
      url = "<?php echo base_url('admin/user/update_status')?>";
      form_data = $('#form_status').serialize();
    }
     // ajax adding data to database
    $.ajax({
      url : url,
      type: "POST",
      data: form_data,
      dataType: "JSON",
      success: function(data)
      {
        // if add success
        if (data.status == 0) {
          notify('warning', 'WARNING !!!', data.ket);
        }else{
          $('#modal_add').modal('hide');
          $('#modal_user_status').modal('hide');
          notify('success', 'Success', 'saving data success');
          reload_table();
          reload_table_status();
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notify('error', 'error', 'saving data error');
        // $.notify("Saving Error", "warn");
      }
    });
  }

  function save_reset()
  {
    $.ajax({
      url : "<?php echo base_url('admin/user/reset')?>",
      type: "POST",
      data: $('#form_reset').serialize(),
      dataType: "JSON",
      success: function(data)
      {
        // if add success
        if (data.key == 0) {
          // $('#modal_reset').modal('hide');
          notify('warning', 'Notify', data.status);
        }
        else{
          $('#modal_reset').modal('hide');
          notify('success', 'Success', 'Update data success');
          reload_table();
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        $('#modal_reset').modal('hide');
        notify('error', 'error', 'saving data error');
        // $.notify("Saving Error", "warn");
      }
    });
  }

  function setting_status() {
    // body...
    $('#box_user').hide('slow/400/fast', function() {});
    $('#box_user_status').removeClass('fade');
    table_user_status.ajax.reload(null, false);
  }

  function back() {
    $('#box_user').show('slow/400/fast', function() {
      
    });
    $('#box_user_status').addClass('fade')
  }

  function reload_table_status()
  {
    table_user_status.ajax.reload(null, false);
  }

  function add_status() {
    // body...
    save_method = 'add_status';
    $('#form_status')[0].reset();
    $('#color_review').removeAttr('style');
    $('[name="id_status"]').attr('disabled', 'disabled');
    $('select[name="alto"]').empty();
    $('select[name="alto"]').append('<option value="">-</option>');
    $('select[name="alto"]').append('<option value="0">Yes</option>');
    $('select[name="alto"]').append('<option value="1">No</option>');
    $('#modal_user_status').modal('show');
    $('#modal_user_status .modal-title').text('Add New User Status');
  }

  function edit_status(id) {
    // body...
    save_method = 'edit_status';
    $('#form_status')[0].reset();
    $('[name="id_status"]').removeAttr('disabled');
    $('#color_review').removeAttr('style');
    $.ajax({
      url : "<?php echo base_url('admin/user/status_ajax_edit/')?>"+id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        $('#color_review').attr('style', 'background-color:'+data.result.color_user_status);
        $('[name="id_user_status"]').val(data.result.id_user_status);
        $('[name="id_status"]').val(data.result.id_user_status);
        $('[name="desc_status"]').val(data.result.desc_user_status);
        $('[name="color_status"]').val(data.result.color_user_status);
        $('select[name="alto"]').empty();
        $('select[name="alto"]').append('<option value="">-</option>');
        if (data.result.allow_to_login == 0) {
          $('select[name="alto"]').append('<option value="0" selected>Yes</option>');
        }
        else{
          $('select[name="alto"]').append('<option value="0">Yes</option>');
        }
        if (data.result.allow_to_login == 1) {
          $('select[name="alto"]').append('<option value="1" selected>No</option>');
        }
        else{
          $('select[name="alto"]').append('<option value="1">No</option>');
        }
        $('#modal_user_status').modal('show');
        $('#modal_user_status .modal-title').text('Edit User Status');
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          notify('error','error','Error loading from ajax !!!');
      }
    });
  }

  </script>