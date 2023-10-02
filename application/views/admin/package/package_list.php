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
            <h1>Package List<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"> Package List</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="listGroup" class="box box-info">
                        <div class="box-header with-border">
                            <div class="col-md-5">&nbsp;</div>
                            <div class="col-lg-7">
                                <?php
                                if($this->session->userdata("message")){
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
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Amount</th>
                                                <th>Profit</th>
                                                <th>Duration in Days</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($level_packages_list as $lvl_k => $lvl_v):?>
                                        <tr>
                                            <th colspan="6" style="background-color: lightgreen;"><h4>LEVEL <?php echo $lvl_k;?></h4></th>
                                        </tr>
                                        <?php $sr = 0; foreach($lvl_v as $k=>$v): $sr++;?>
                                        <tr>
                                            <td><?php echo $sr;?></td>
                                            <td><?php echo $v['name'];?></td>
                                            <td><?php echo $v['amount'];?></td>
                                            <td><?php echo $v['profit'];?></td>
                                            <td><?php echo $v['duration'];?></td>
                                            <td>
                                                <a href="<?php echo base_url("Admin/delete_package/".$v['id']); ?>" class="text-danger" onclick="return confirm('Are you sure? Once you delete package you will not be able to recover it.')">Delete</a>
                                                </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    
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