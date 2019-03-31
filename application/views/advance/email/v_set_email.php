  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Setting Email
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">setting</a></li>
        <li class="active">email</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
          <!-- SELECT2 EXAMPLE -->
          <div id="box_user" class="box box-info">
            <div class="box-header with-border">
              <div>
                <button class="btn btn-sm btn-default btn-flat" onclick="reload_table()" data-toggle="tooltip" data-placement="top" title="refresh"><i class="fa fa-refresh"></i></button>
                <button class="btn btn-sm btn-info btn-flat" onclick="add()" data-toggle="tooltip" data-placement="top" title="add"><i class="fa fa-plus"></i></button>
                <button class="btn btn-sm btn-primary btn-flat" onclick="setting_status()" data-toggle="tooltip" data-placement="top" title="setting status user"><i class="fa fa-gear"></i></button>
              </div>
              <h3 class="box-title"></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form action="#" id="form_set_email" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="form-body">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-3">Allow use email</label>
                    <div class="col-md-7 col-sm-7 col-xs-7">
                      <label>
                        <input type="checkbox" id="checkbox_allow" onclick="check()" class="flat-red" name="allow" <?php echo $cek->allow_use == 'Y'? 'checked':'' ?> value="Y">
                      </label>
                    </div>
                  </div>
                </div>
                <br/>
                <div id="form_email">
                  <div class="form-body">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-3">protocol</label>
                      <div class="col-md-7 col-sm-7 col-xs-7">
                        <input name="protocol" placeholder="smtp" class="form-control" type="text" value="<?php echo isset($result['protocol'])? $result['protocol']:''; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-body">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-3">host</label>
                      <div class="col-md-7 col-sm-7 col-xs-7">
                        <input name="host" placeholder="example = ssl://smtp.googlemail.com" class="form-control" type="text" value="<?php echo isset($result['smtp_host'])? $result['smtp_host']:''; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-body">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-3">port</label>
                      <div class="col-md-7 col-sm-7 col-xs-7">
                        <input name="port" placeholder="example = 465" class="form-control" type="number" value="<?php echo isset($result['smtp_port'])? $result['smtp_port']:''; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-body">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-3">user</label>
                      <div class="col-md-7 col-sm-7 col-xs-7">
                        <input name="account" placeholder="example = your_account@gmail.com" class="form-control" type="text" value="<?php echo isset($result['smtp_user'])? $result['smtp_user']:''; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-body">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-3">password</label>
                      <div class="col-md-7 col-sm-7 col-xs-7">
                        <input name="password" placeholder="example = your password" class="form-control" type="password" value="<?php echo isset($result['smtp_pass'])? $result['smtp_pass']:''; ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-body">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-3"></label>
                    <div class="col-md-7 col-sm-7 col-xs-7">
                      <input type="submit" class="btn btn-sm btn-info pull-right" value="save">
                    </div>
                  </div>
                </div>

              </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <h4><b>Attention</b></h4>
              <p>For this version simple template email only support SMTP</p>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>

      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->