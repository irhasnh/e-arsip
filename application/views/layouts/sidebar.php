  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $photo; ?>" class="img-circle" alt="User Image" id="photo3">
        </div>
        <div class="pull-left info">
          <p><?php echo $fullname; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <?php foreach ($menu as $m): ?>
          <li class="treeview">
            <a href="#">
              <i class="fa <?php echo $m->fa; ?>"></i>
              <span><?php echo $m->desc_nav; ?></span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
            <?php foreach ($m->nav_content as $nvcon): ?>
              <li><a href="<?php echo base_url().$nvcon->url; ?>"><i class="fa fa-mail-forward"></i> <?php echo $nvcon->desc_nav_content; ?></a></li>
            <?php endforeach ?>
            </ul>
          </li>
        <?php endforeach ?>
        <?php if ($priv == 1): ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-gear"></i>
              <span>Setting</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo base_url().'admin/user'; ?>"> <i class="fa fa-mail-forward"></i> User</a></li>
              <li><a href="<?php echo base_url().'admin/priv'; ?>"> <i class="fa fa-mail-forward"></i> Privilages</a></li>
              <li><a href="<?php echo base_url().'admin/menu'; ?>"> <i class="fa fa-mail-forward"></i> Menu</a></li>
            </ul>
          </li>
        <?php endif ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
