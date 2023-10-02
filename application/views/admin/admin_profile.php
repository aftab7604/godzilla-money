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
       Profile
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profile</li>
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
            <form name="elementForm" id="leadAddForm" role="form" action="<?php echo base_url('admin/profile');?>" method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $profile['name'];?>">
                            </div>
                            <?php echo form_error("name"); ?>
                        </div>  
                        <div class="col-md-12">
                            <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $profile['email'];?>">
                            </div>
                            <?php echo form_error("email"); ?>
                        </div>  
                        <div class="col-md-12">
                            <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update Profile</button>
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