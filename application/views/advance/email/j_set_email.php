  <script type="text/javascript">

  $(document).ready(function() {
   // $('#form_email').hide();
  });

  if ($('[name="allow"]').is(':checked')) {
        // alert("checked");
        $('#form_email').show('slow/400/fast', function() {
          
        });;
  }else{
    $('#form_email').hide('slow/400/fast', function() {
      
    });
  }

  $('input').on('ifChanged', function(event){
    // alert(event.type + ' callback');
    if ($('[name="allow"]').is(':checked')) {
      $('#form_email').show('slow/400/fast', function() {
        
      });;
    }
    else{
      $('#form_email').hide('slow/400/fast', function() {
        
      });;
    }
  });

  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-red',
    radioClass   : 'iradio_flat-green'
  });

  $( "#form_set_email" ).submit(function( event ) {
    $.ajax({
      url : "<?php echo base_url().'advance/set_email/set_aksi'; ?>",
      type: "POST",
      data: $('#form_set_email').serialize(),
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