<?php $this->load->view('admin/components/header_css.php'); ?>
<div class="wrapper">
    <?php $this->load->view('admin/components/header.php'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php $this->load->view('admin/components/sidebar.php'); ?>
    <!-- Content Wrapper. Contains page content -->
    <style type="text/css">
    .dataTables_filter{
      text-align: right;
    }
    .paging_simple_numbers {
      text-align: right;
    }
    </style>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Dashboard</h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active"> Dashboard</li>
        </ol>
      </section>
      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-xs-12">
            <div class="info-box bg-red">
              <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Users</span>
                <span class="info-box-number"><?php echo $total_users;?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <!-- <span class="progress-description class-for-list"><a href="">Go List</a></span> -->
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-lg-4 col-xs-12">
            <div class="info-box bg-red">
              <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Recharge</span>
                <span class="info-box-number"><?php echo round($total_recharge,2); ?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <!-- <span class="progress-description class-for-list"><a href="">Go List</a></span> -->
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-lg-4 col-xs-12">
            <div class="info-box bg-red">
              <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Withdraw</span>
                <span class="info-box-number"><?php echo round($total_withdraw,2); ?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <!-- <span class="progress-description class-for-list"><a href="">Go List</a></span> -->
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>
        </div><!-- /.content-wrapper -->
        <!--  -->
        <div class="row">
          <div class="col-lg-4 col-xs-12">
            <div class="info-box bg-red">
              <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Today Total Users</span>
                <span class="info-box-number"><?php echo $today_total_users;?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <!-- <span class="progress-description class-for-list"><a href="">Go List</a></span> -->
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-lg-4 col-xs-12">
            <div class="info-box bg-red">
              <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Today Total Recharge</span>
                <span class="info-box-number"><?php echo round($today_total_recharge,2); ?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <!-- <span class="progress-description class-for-list"><a href="">Go List</a></span> -->
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-lg-4 col-xs-12">
            <div class="info-box bg-red">
              <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Today Total Withdraw</span>
                <span class="info-box-number"><?php echo round($today_total_withdraw,2); ?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <!-- <span class="progress-description class-for-list"><a href="">Go List</a></span> -->
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>
        </div><!-- /.content-wrapper -->
      </section>
    </div>
</div>
<script type="text/javascript">
  $(function () {
    CKEDITOR.replace('editor1');
    CKEDITOR.replace('editor2');
    $('.textarea').wysihtml5()
  })
</script>
<?php $this->load->view('admin/components/footer.php'); ?>
<?php $this->load->view('admin/components/footer_js.php'); ?>