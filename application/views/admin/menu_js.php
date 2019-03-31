
  <script>
  var nav;
  var save_method;
  var no_fa = 0;
  var fa;
  var list;
  var no = 0;
  var id_nav;
  var desc_nav;
  var order_nav;
  var form_nav = '<form action="#" id="form_nav_add" class="form-horizontal"><input type="hidden" value="" name="inputIdNav"/><div class="form-body"><div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-3">Desc Nav</label><div class="col-md-7 col-sm-7 col-xs-7"><input name="inputDescNav" placeholder="Desc Nav" class="form-control" type="text"></div></div></div><div class="form-body"><div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-3">Icon</label><div class="col-md-7 col-sm-7 col-xs-7"><input type="text" class="form-control" placeholder="Icon" name="inputFa"/></div><div class="col-md-1 col-sm-1 col-xs-1"><a href="#" class="btn btn-sm" data-toggle="tooltip" data-placement="top" title="avalaible icon" onclick="fa_show(0)"><strong>?</strong></a></div></div></div><div class="form-body"><div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-3">Order</label><div class="col-md-7 col-sm-7 col-xs-7"><input name="inputOrderNav" placeholder="Order Nav" class="form-control" type="number"></div></div></div></form>';
  var btn_sm_add = '<button type="button" id="btnSave" onclick="save_nav()" class="btn btn-primary btn-sm btn-flat">Save</button><button type="button" class="btn btn-danger btn-sm btn-flat" data-dismiss="modal">Cancel</button>';
  var btn_cancel = '<button type="button" class="btn btn-danger btn-sm btn-flat" data-dismiss="modal">Cancel</button>';
  var nav_content;
  var form_content = "<form action='#' id='form_nav_add' class='form-horizontal'><input type='hidden' value='' name='inputIdNavContent'/><div class='form-body'><div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-3'>Name</label><div class='col-md-7 col-sm-7 col-xs-7'><input name='inputDescNavContent' placeholder='Name' class='form-control' type='text'></div></div></div><div class='form-body'><div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-3'>Order</label><div class='col-md-7 col-sm-7 col-xs-7'><input name='inputOrderNavContent' placeholder='Order' class='form-control' type='number'></div></div></div><div class='form-body'><div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-3'>URL / Controller</label><div class='col-md-7 col-sm-7 col-xs-7'><input name='inputUrlContent' placeholder='URL' class='form-control' type='text'></div></div></div><div class='form-body'><div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-3'>Menu</label><div class='col-md-7 col-sm-7 col-xs-7'><select name='inputNavContent' class='form-control'><option value=''>-</option></select></div></div></div></form>";

  $(document).ready(function() {
   
    //datatables
    nav = $('#nav').DataTable({ 
 
        "processing": false, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('admin/menu/ajax_nav_list')?>",
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

    nav_content = $('#menu_content').DataTable({ 
 
        "processing": false, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('admin/menu/ajax_nav_list_content')?>",
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

  function fa_show(halaman)
  {
    $('.modal-dialog').addClass('modal-lg');
    $('#modal_nav_add .modal-title').text('Avalaible Icon');
    $.ajax({
      url: "<?php echo base_url('admin/menu/fa/')?>"+halaman,
      type: 'POST',
      dataType: 'JSON',
    })
    .done(function(data) {
      if (no === 1) {
        $('#list_fa').remove();
      };
      list = "<div class='col-md-12 col-sm-12 col-xs-12' id='list_fa'>";
      $.each(data.hasil, function(key, value){
        list += "<div id='fa_list' class='col-md-3 col-sm-4 col-xs-6' onclick=fa_dismiss('"+save_method+"','"+value.font+"')><a href='#' class='btn btn-default btn-sm btn-block'><i class='fa "+value.font+"'></i> "+value.font+"</a></div>";
      });
      list += '</div>';
      var bt_page;
      var back_page = halaman-1;
      var next_page = halaman+1;
      document.getElementById('row_fa').innerHTML = list;
      if (halaman === 0) {
        bt_page = '<button type="button" onclick="fa_show('+back_page+')" class="btn btn-info btn-sm btn-flat pull-left" disabled>Back</button><button type="button" onclick="fa_show('+next_page+')" class="btn btn-info btn-sm btn-flat pull-left">Next</button>';
      }
      else if (halaman === (data.total_page - 1)){
        bt_page = '<button type="button" onclick="fa_show('+back_page+')" class="btn btn-info btn-sm btn-flat pull-left">Back</button><button type="button" onclick="fa_show('+next_page+')" class="btn btn-info btn-sm btn-flat pull-left" disabled>Next</button>';
      }
      else {
        bt_page = '<button type="button" onclick="fa_show('+back_page+')" class="btn btn-info btn-sm btn-flat pull-left">Back</button><button type="button" onclick="fa_show('+next_page+')" class="btn btn-info btn-sm btn-flat pull-left">Next</button>';
      }
      $('#bt_fa_next_back').empty();
      document.getElementById('bt_fa_next_back').innerHTML = bt_page;
      $('#bt_fa_next_back').append(btn_cancel);
      no = 1;
    })
    .fail(function() {
      notify('error', 'error', 'Error loading from ajax !!!');
    });
  }

  function reload_nav()
  {
    nav.ajax.reload(null, false);
  }

  function reload_content()
  {
    nav_content.ajax.reload(null, false);
  }

  function fa_dismiss(method, fa)
  {
    if (method === 'add_nav') {
      add_nav(fa);
    };
    if (method === 'edit_nav') {
      // $('#modal_fa').modal('hide');
      document.getElementById('row_fa').innerHTML = form_nav;
      $('.modal-dialog').removeClass('modal-lg');
      $('[name="inputIdNav"]').val(id_nav);
      $('[name="inputDescNav"]').val(desc_nav);
      $('[name="inputOrderNav"]').val(order_nav);
      $('[name="inputFa"]').val(fa);
      $('#modal_nav_add .modal-title').text('Edit Menu');
      $('#bt_fa_next_back').empty();
      $('#bt_fa_next_back').append(btn_sm_add);
      // $('#modal_nav_add').modal('show'); // show bootstrap modal
    };
  }

  function add_nav(fawe)
  {
    $('#bt_fa_next_back').empty();
    $('#bt_fa_next_back').append(btn_sm_add);
    save_method = 'add_nav';
    $('.modal-dialog').removeClass('modal-lg');
    document.getElementById('row_fa').innerHTML = form_nav;
    $('[name="inputFa"]').val(fawe);
    // $('#form_nav_add')[0].reset();
    // $('#modal_fa').modal('hide');
    $('#modal_nav_add .modal-title').text('Add Menus');
    $('#modal_nav_add').modal('show'); // show bootstrap modal
  }

  function add_content()
  {
    save_method = 'add_nav_content';
    $('#bt_fa_next_back').empty();
    $('#bt_fa_next_back').append(btn_sm_add);
    $('.modal-dialog').removeClass('modal-lg');
    $('#modal_nav_add .modal-title').text('Add Content');
    $('#modal_nav_add').modal('show'); // show bootstrap modal
    document.getElementById('row_fa').innerHTML = form_content;
    $.ajax({
      url: "<?php echo base_url('admin/menu/ajax_menu/')?>",
      type: 'POST',
      dataType: 'JSON',
    })
    .done(function(data) {
      $('[name="inputNavContent"]').empty();
      $('[name="inputNavContent"]').append('<option value="">-</option>');
      $.each(data, function(key, val) {
         $('[name="inputNavContent"]').append('<option value="'+val.id_nav+'">'+val.desc_nav+'</option>');
      });
      console.log("success");
    })
    .fail(function() {
      console.log("error");
    });
    
  }

  function edit_nav(id)
  {
    save_method = 'edit_nav';
    $('.modal-dialog').removeClass('modal-lg');
    $('#bt_fa_next_back').empty();
    $('#bt_fa_next_back').append(btn_sm_add);
    // $('#form_nav_add')[0].reset();
    $.ajax({
      url : "<?php echo base_url('admin/menu/ajax_edit_nav/')?>"+id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        document.getElementById('row_fa').innerHTML = form_nav;
        id_nav = data.result.id_nav;
        $('[name="inputIdNav"]').val(id_nav);
        desc_nav = data.result.desc_nav;
        $('[name="inputDescNav"]').val(desc_nav);
        order_nav = data.result.order_nav;
        $('[name="inputOrderNav"]').val(order_nav);
        $('[name="inputFa"]').val(data.result.fa);
        $('#modal_nav_add .modal-title').text('Edit Menu');
        $('#modal_nav_add').modal('show'); // show bootstrap modal
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          notification('Error loading from ajax !!!');
      }
    });
  }

  function edit_nav_content(id)
  {
    save_method = 'edit_nav_content';
    // $('#form_nav_add')[0].reset();
    $.ajax({
      url : "<?php echo base_url('admin/menu/ajax_edit_nav_content/')?>"+id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        $('#bt_fa_next_back').empty();
        $('#bt_fa_next_back').append(btn_sm_add);
        document.getElementById('row_fa').innerHTML = form_content;
        $('[name="inputIdNavContent"]').val(data.result.id_nav_content);
        $('[name="inputDescNavContent"]').val(data.result.desc_nav_content);
        $('[name="inputOrderNavContent"]').val(data.result.order_nav);
        $('[name="inputUrlContent"]').val(data.result.url);
        $('[name="inputNavContent"]').empty();
        $('[name="inputNavContent"]').append('<option value="">-</option>');
        $.each(data.menu, function(key, val) {
          if (data.result.fk_id_nav === val.id_nav) {
            $('[name="inputNavContent"]').append('<option value="'+val.id_nav+'" selected>'+val.desc_nav+'</option>');
          }
          else{
            $('[name="inputNavContent"]').append('<option value="'+val.id_nav+'">'+val.desc_nav+'</option>');
          }
        });
        $('#modal_nav_add .modal-title').text('Edit Menu');
        $('#modal_nav_add').modal('show'); // show bootstrap modal
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          notification('Error loading from ajax !!!');
      }
    });
  }

  function save_nav()
  {
    var url;
    if(save_method === 'add_nav')
    {
      url = "<?php echo base_url('admin/menu/add_nav')?>";
    }
    else if(save_method === 'edit_nav')
    {
      url = "<?php echo base_url('admin/menu/update_nav')?>";
    }
    else if (save_method === 'add_nav_content') {
      url = "<?php echo base_url('admin/menu/add_content')?>";
    }
    else if (save_method === 'edit_nav_content') {
      url = "<?php echo base_url('admin/menu/update_content')?>";
    };
     // ajax adding data to database
     $.ajax({
      url : url,
      type: "POST",
      data: $('#form_nav_add').serialize(),
      dataType: "JSON",
      success: function(data)
      {
        // if add success
        $('#modal_nav_add').modal('hide');
        notify(data.status, data.status, data.ket);
        if (save_method === 'add_nav' || save_method === 'edit_nav') {
          reload_nav();
        }
        else{
          reload_content();
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notify('error', 'error', 'saving data error');
        // $.notify("Saving Error", "warn");
      }
    });
  }
  </script>