  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Permission
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">setting</a></li>
        <li class="active">permission</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">

        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <div>
              </div>
              <h3 class="box-title"></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <form action="#" id="form_permission" method="post" accept-charset="utf-8" class="form-horizontal">
            <div class="box-body" id="box_body_content">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="box-body">
                    <table no-border width="100%" class="table">
                      <thead>
                        <tr>
                          <th width="25%"></th>
                          <th width="15%">Read</th>
                          <th width="15%">Create</th>
                          <th width="15%">Update</th>
                          <th width="15%">Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($menu as $key_menu => $value_menu): ?><tr>
                          <td>
                            <input type="hidden" name="menu_content[]" value="<?php echo $value_menu->id_nav_content; ?>">
                            <input type="hidden" name="id" value="<?php echo $priv; ?>">
                            <label class="control-label"><?php echo $value_menu->desc_nav_content;?></label>
                          </td>
                          <td>
                            <label>
                              <input type="checkbox" class="flat-red" name="read[<?php echo $value_menu->id_nav_content; ?>]" <?php echo isset($value_menu->read_check) ? $value_menu->read_check:''; ?> value="1">
                            </label>
                          </td>
                          <td>
                            <label>
                              <input type="checkbox" class="flat-red" name="create[<?php echo $value_menu->id_nav_content; ?>]" <?php echo isset($value_menu->create_check) ? $value_menu->create_check:''; ?> value="1">
                            </label>
                          </td>
                          <td>
                            <label>
                              <input type="checkbox" class="flat-red" name="update[<?php echo $value_menu->id_nav_content; ?>]" <?php echo isset($value_menu->update_check) ? $value_menu->update_check:''; ?> value="1">
                            </label>
                          </td>
                          <td>
                            <label>
                              <input type="checkbox" class="flat-red" name="delete[<?php echo $value_menu->id_nav_content; ?>]" <?php echo isset($value_menu->delete_check) ? $value_menu->delete_check:''; ?> value="1">
                            </label>
                          </td>
                        </tr>
                      <?php endforeach ?></tbody>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer" id="box_footer_content">
              <button class="btn btn-primary btn-sm btn-flat" type="submit">Save</button>
            </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Bootstrap modal -->
  <div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-purple">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Book Form</h3>
        </div>
        <div class="modal-body form">
          <div class="row" id="row_fa">
            <div class="col-md-12 col-sm-12 col-xs-12" id="modal_form">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnSave" onclick="save()" class="btn btn-primary btn-sm btn-flat">Save</button>
          <button type="button" class="btn btn-danger btn-sm btn-flat" data-dismiss="modal">Cancel</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End Bootstrap modal -->