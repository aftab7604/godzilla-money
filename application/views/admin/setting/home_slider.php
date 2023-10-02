<?php $this->load->view('admin/components/header_css.php'); ?>
<div class="wrapper">
    <?php $this->load->view('admin/components/header.php'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php $this->load->view('admin/components/sidebar.php'); ?>
    <!-- Content Wrapper. Contains page content -->
    <style type="text/css">
    .content-wrapper {
        min-height: 10px !important;
    }
    </style>
    <!-- ----------------------------Start Slider ---------------------------------->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> 
                Setting
                <small>Home Slider</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Setting</a></li>
                <li class="active">  Home Slider</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="listGroup" class="box box-info">
                        <div class="box-header with-border">
                            <div class="col-lg-5 btn-class">
                                Home Slider - height 250px , witdth 1400px - ideal 
                            </div>
                            <div class="col-lg-7">
                                <?php
                                if ($this->session->userdata("message")){
                                    $class = $this->session->userdata("message")['class'];
                                    $msg = $this->session->userdata("message")['msg'];
                                    $this->session->unset_userdata("message");
                                ?>
                                <div class='alert alert-<?php echo $class; ?> alert-dismissible pull-right' style="margin: 0px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="icon fa fa-check"></i><?php echo $msg ;?>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                   
                        <div class="box-body">
                            <div class="col-md-12">
                                <form action="<?php echo base_url("admin/setting/home-slider");?>" method="post" enctype="multipart/form-data" >
                                    <div class="col-md-12">
                                        <div class="form-group"><label for="image">Select Image</label>
                                            <input  type="file" name="image" id="image"/>
                                        </div>
                                        <?php echo form_error("image"); ?>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group"> 
                                            <input class="btn-success btn col-md-2" type="submit" name="submit" value="Upload Images" />
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="listGroup" class="box box-info">
                        <div class="box-header with-border">
                            <div class="col-lg-5 btn-class">
                                Home Slider
                            </div>
                            <div class="col-lg-7">
             
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="col-md-12">
                                <?php if(!empty($images)): foreach($images as $k=>$v):?>
                                <div class="col-md-2 bg-info" style="margin:15px;">
                                    <div class="form-group">
                                        <p>&nbsp;</p>
                                        <p><img src="<?php echo base_url("uploads/admin/home_slider/".$v['image']);?>" class="img-responsive" style="height:110px;"></p>
                                        <p align="center"><a href="<?php echo base_url('admin/setting/home-slider?delete='.$v['id']); ?>" title="Delete" class="btn btn-danger btn-block" onclick="return confirm('Are you sure to delete this slider image?')"><i class="glyphicon glyphicon-trash icon-white"></i></a></p>
                                    </div>
                                </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
</div><!-- ./wrapper -->
<!-- ----------------------------Slider End ---------------------------------->
<?php $this->load->view('admin/components/footer.php'); ?>
<?php $this->load->view('admin/components/footer_js.php'); ?>