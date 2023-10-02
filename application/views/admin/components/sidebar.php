<?php $admin = $this->session->userdata("admin-login");?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url();?>assets/images/logo.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $admin['name']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
        
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li><a href="<?php echo base_url('admin'); ?>"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
      
        <?php if($admin['role_id'] == 1) : ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>User</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="<?php echo base_url("admin/user-list");?>">
                <i class="fa fa-dot-circle-o"></i>User List
              </a>
            </li>
            
          </ul>
        </li>
        <?php endif; ?>
        
        <?php if($admin['role_id'] == 1 || $admin['role_id'] == 2) : ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>Recharge</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <?php if($admin['role_id'] == 1 || $admin['role_id'] == 2) : ?>
            <li>
              <a href="<?php echo base_url("admin/recharge-requests");?>">
                <i class="fa fa-dot-circle-o"></i>Requests
              </a>
            </li>
            <?php endif; ?>

            <?php if($admin['role_id'] == 1 || $admin['role_id'] == 2) : ?>
            <li>
              <a href="<?php echo base_url("admin/recharge-history");?>">
                <i class="fa fa-dot-circle-o"></i>History
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>

        <?php if($admin['role_id'] == 1 || $admin['role_id'] == 3) : ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>Withdraw</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <?php if($admin['role_id'] == 1 || $admin['role_id'] == 3) : ?>
            <li>
              <a href="<?php echo base_url("admin/withdraw-requests");?>">
                <i class="fa fa-dot-circle-o"></i>Requests
              </a>
            </li>
            <?php endif; ?>

            <?php if($admin['role_id'] == 1 || $admin['role_id'] == 3) : ?>
            <li>
              <a href="<?php echo base_url("admin/withdraw-history");?>">
                <i class="fa fa-dot-circle-o"></i>History
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        
        <?php if($admin['role_id'] == 1) : ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>Package</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="<?php echo base_url("admin/package-list");?>">
                <i class="fa fa-dot-circle-o"></i>Package List
              </a>
            </li>
            <li>
              <a href="<?php echo base_url("admin/add-package");?>">
                <i class="fa fa-dot-circle-o"></i>Add Package
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>
        
        <?php if($admin['role_id'] == 1) : ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>Setting</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="<?php echo base_url("admin/setting/home-slider");?>">
                <i class="fa fa-dot-circle-o"></i>Home Slider
              </a>
            </li>
            <li>
              <a href="<?php echo base_url("admin/site-setting");?>">
                <i class="fa fa-dot-circle-o"></i>Site Setting
              </a>
            </li>
            <li>
              <a href="<?php echo base_url("admin/commission-slab");?>">
                <i class="fa fa-dot-circle-o"></i>Commission Slab
              </a>
            </li>
            <li>
              <a href="<?php echo base_url("admin/level-setting");?>">
                <i class="fa fa-dot-circle-o"></i>Level Setting
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>
        </ul>
    </section>
    <!-- /.sidebar -->
  </aside>