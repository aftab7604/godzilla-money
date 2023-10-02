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
            <h1>User List<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
                <li class="active"> User List</li>
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
                                <form action="<?php echo base_url("admin/user-list")?>" method="get">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="user-mobile">Mobile</label>
                                            <input class="form-control" type="text" name="user-mobile" id="user-mobile" value="<?php echo $this->input->get("user-mobile"); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button style="margin-top: 25px;" type="submit" class="btn btn-info" name="action" value="search">Serach</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User</th>
                                               
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 0; foreach($users as $k=>$v): $sr++;?>
                                            <tr>
                                                <td><?php echo $sr;?></td>
                                                <td>
                                                    <b>Name: </b><?php echo $v['name'];?><br>
                                                    <b>Number: </b><?php echo $v['phone_number'];?><br>
                                                    <b>Crypto Address: </b><?php echo $v['crypto_address'];?><br>
                                                </td>
                                             
                                                <td>
                                                    <a href="<?php echo base_url("admin/user-detail/".$v['id']); ?>" class="text-success">View</a>
                                                 </td>
                                            </tr>
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