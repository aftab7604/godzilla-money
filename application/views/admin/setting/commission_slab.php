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
      <h1>
       Commission Slab
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Commission Slab Form</li>
      </ol>
    </section>
        <!-- Main content -->
        <section class="content">
        <div class="row">
              <div class="col-md-12">
               <div id="listGroup" class="box box-info">
                <div class="box-header with-border">
           
            <!-- <div class="col-lg-4 btn-class">-->
            <!--  <a href="<?php echo base_url(); ?>Admin/addproduct" class="btn btn-flat margin" style="background-color: #605ca8; color: #fff;"><span class="fa fa-plus-circle" ></span> Add Product </a>&nbsp;-->
            <!--  <a href="<?php echo base_url(); ?>Admin/product" class="btn btn-flat margin" style="background-color: #605ca8; color: #fff;"><span class="fa fa-list"></span> Product List</a>&nbsp;-->
            <!--</div> -->
            <div class="col-lg-12">
            <?php 
              if($this->session->userdata('message')){
              $class = $this->session->userdata("message")['class'];
              $msg = $this->session->userdata("message")['msg'];
              $this->session->unset_userdata("message");
              ?>
                <p class="alert alert-<?php echo $class;?>"><button type="button" class="close" data-dismiss="alert">x</button><?php echo $msg;?></p>
              <?php	
                }
              ?>
            </div>
                   
          <div class="box-body">
          <div class="col-md-12">
            <div class="col-md-12">
              
            </div>  
            <form name="elementForm" id="leadAddForm" role="form" action="<?php echo base_url('admin/commission-slab');?>" method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                        <h1 class="text-center"> New user Recharge Commission</h1>
                        <hr/> 
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="referral_level_0_commission">Self Commission</label>
                                <input type="text" id="referral_level_0_commission" name="referral_level_0_commission"  class="form-control" value="<?php echo $referral['self']['commission']; ?>">
                            </div>
                            <?php echo form_error("referral_level_0_commission"); ?>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="referral_level_0_status">Status</label>
                                <select id="referral_level_0_status" name="referral_level_0_status"  class="form-control">
                                    <option <?php echo $referral['self']['status'] == 1 ? 'selected' : ''; ?> value="1">On</option>
                                    <option <?php echo $referral['self']['status'] == 0 ? 'selected' : ''; ?> value="0">Off</option>
                                </select>
                            </div>
                            <?php echo form_error("referral_level_0_status"); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="referral_level_1_commission">Upline 1 Commission</label>
                                <input type="text" id="referral_level_1_commission" name="referral_level_1_commission"  class="form-control" value="<?php echo $referral['level_1']['commission']; ?>">
                            </div>
                            <?php echo form_error("referral_level_1_commission"); ?>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="referral_level_1_status">Status</label>
                                <select id="referral_level_1_status" name="referral_level_1_status"  class="form-control">
                                    <option <?php echo $referral['level_1']['status'] == 1 ? 'selected' : ''; ?> value="1">On</option>
                                    <option <?php echo $referral['level_1']['status'] == 0 ? 'selected' : ''; ?> value="0">Off</option>
                                </select>
                            </div>
                            <?php echo form_error("referral_level_1_status"); ?>
                        </div>     
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="referral_level_2_commission">Upline 2 Commission</label>
                                <input type="text" id="referral_level_2_commission" name="referral_level_2_commission"  class="form-control" value="<?php echo $referral['level_2']['commission']; ?>">
                            </div>
                            <?php echo form_error("referral_level_2_commission"); ?>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="referral_level_2_status">Status</label>
                                <select id="referral_level_2_status" name="referral_level_2_status"  class="form-control">
                                    <option <?php echo $referral['level_2']['status'] == 1 ? 'selected' : ''; ?> value="1">On</option>
                                    <option <?php echo $referral['level_2']['status'] == 0 ? 'selected' : ''; ?> value="0">Off</option>
                                </select>
                            </div>
                            <?php echo form_error("referral_level_2_status"); ?>
                        </div>
                       
                        <h1 class="text-center">Users income Recive Commission</h1>
                        <hr/>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="package_level_1_commission">Upline 1 Commission</label>
                                <input type="text" id="package_level_1_commission" name="package_level_1_commission"  class="form-control" value="<?php echo $package['level_1']['commission']; ?>">
                            </div>
                            <?php echo form_error("package_level_1_commission"); ?>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="package_level_1_status">Status</label>
                                <select id="package_level_1_status" name="package_level_1_status"  class="form-control">
                                    <option <?php echo $package['level_1']['status'] == 1 ? 'selected' : ''; ?> value="1">On</option>
                                    <option <?php echo $package['level_1']['status'] == 0 ? 'selected' : ''; ?> value="0">Off</option>
                                </select>
                            </div>
                            <?php echo form_error("package_level_1_status"); ?>
                        </div>     
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="package_level_2_commission">Upline 2 Commission</label>
                                <input type="text" id="package_level_2_commission" name="package_level_2_commission"  class="form-control" value="<?php echo $package['level_2']['commission']; ?>">
                            </div>
                            <?php echo form_error("package_level_2_commission"); ?>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="package_level_2_status">Status</label>
                                <select id="package_level_2_status" name="package_level_2_status"  class="form-control">
                                    <option <?php echo $package['level_2']['status'] == 1 ? 'selected' : ''; ?> value="1">On</option>
                                    <option <?php echo $package['level_2']['status'] == 0 ? 'selected' : ''; ?> value="0">Off</option>
                                </select>
                            </div>
                            <?php echo form_error("package_level_2_status"); ?>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
                </div>
                </div>
               </div>
              </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
    </div><!-- ./wrapper -->

<script type="text/javascript">
  $(function () {
    CKEDITOR.replace('editor1');
    CKEDITOR.replace('editor2');
    $('.textarea').wysihtml5()
  })
</script>
<?php $this->load->view('admin/components/footer.php'); ?>
<?php $this->load->view('admin/components/footer_js.php'); ?>