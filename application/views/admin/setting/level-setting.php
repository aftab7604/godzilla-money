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
            <h1>Level Setting</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Setting</a></li>
                <li class="active"> Site Setting</li>
            </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="listGroup" class="box box-info">
                        <div class="box-header with-border">
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
                                    <form role="form" action="<?php echo base_url('admin/level-setting');?>" method="post" enctype="multipart/form-data">
                                        <div class="col-md-12">
                                            <?php if(!empty($levels)): foreach($levels as $k=>$v): ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="<?php echo 'level_'.$v['id'].'_limit'; ?>">Level <?php echo $v['id']; ?> Limit</label>
                                                    <input type="text" class="form-control" name="level_limit[<?php echo $v['id'];?>]" id="<?php echo 'level_'.$v['id'].'_limit'; ?>" value="<?php echo $v['package_limit']; ?>" >
                                                </div>
                                                <?php echo form_error('level_limit['.$v['id'].']'); ?>
                                            </div>
                                            <?php endforeach; endif; ?>
                                            
                                            
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