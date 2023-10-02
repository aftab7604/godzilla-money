<?php $admin = $this->session->userdata("admin-login");?>
<style type="text/css">
  .aforlogintype{
    height: 50px;
    line-height: 40px !important;
    color: #fff !important;
    background: #00a65a !important;
  }
  .aforlogintype:hover{
    height: 50px;
    line-height: 40px !important;
    color: #fff !important;
    background: #009551 !important;
  }
  span.select2-selection.select2-selection--single {
    border-radius: 0px;
    height: 33px;
}
</style>
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>GM</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><?php echo 'Godzilla Money'; ?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle Navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo base_url();?>assets/images/logo.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $admin['name'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url();?>assets/images/logo.jpg" class="img-circle" alt="User Image">
                <p>Admin | <?php echo $admin['name'];?></p>
              </li>
              <!-- Menu Body -->
          
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url('admin/change-password'); ?>" class="btn btn-default btn-flat">Password</a>
                </div>
                <div class="pull-left">
                  <a href="<?php echo base_url('admin/profile'); ?>" class="btn btn-default btn-flat">Profile</a>
                </div>


                
                <div class="pull-right">
                  <a href="<?php echo base_url("admin/logout");?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
  </header>