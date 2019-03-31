  <script type="text/javascript">

  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });

  $( "#form_permission" ).submit(function( event ) {
    $.ajax({
      url : "<?php echo base_url().'admin/priv/permission_aksi'; ?>",
      type: "POST",
      data: $('#form_permission').serialize(),
      dataType: "JSON",
      success: function(data)
      {
        notify(data.status, data.status, data.ket);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notify('error', 'error', 'update data error');
      }
    });
    event.preventDefault();
    });

  </script>