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
            <h1>Recharge Requests<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"> Recharge Requests</li>
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
                                                <th>User</th>
                                                <th>Phone</th>
                                                <th>Amount</th>
                                                <th>USDT</th>
                                                <th>Proof</th>
                                                <th>Binance TXN ID</th>
                                                <th>Req Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 0; foreach($recharge_requests as $k=>$v): $sr++;?>
                                            <tr>
                                                <td><?php echo $sr;?></td>
                                                <td><?php echo $v['name'];?></td>
                                                <td><?php echo $v['phone_number'];?></td>
                                                <td><?php echo $v['amount'];?></td>
                                                <td><?php echo $v['usdt'];?></td>
                                                <td><a class="text-info" target="_blank" href="<?php echo base_url("uploads/user/payment_proof/".$v['proof_img']);?>">View</a></td>
                                                <td><?php echo $v['binance_txn_id'];?></td>
                                                <td><?php echo $v['request_created'];?></td>
                                                <td>
                                                    <a href="<?php echo base_url("Admin/payment_req_response/".$v['request_id']."/1"); ?>" class="text-success" onclick="return confirm('Are you sure? Once you accept the amount will be addred to the user wallet.')">Accept</a>
                                                 | 
                                                 <a href="<?php echo base_url("Admin/payment_req_response/".$v['request_id']."/2"); ?>" class="text-danger" onclick="return confirm('Are you sure? Once you reject you will not be able to recover this record.')">Reject</a>
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