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
            <h1>Withdraw History<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"> Withdraw History</li>
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
                            <div class="col-md-12">
                                <form action="<?php echo base_url("admin/withdraw-history");?>" method="get">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-grup">
                                                <label for="from-date">From Date</label>
                                                <input type="date" name="from-date" id="from-date" value="<?php echo $this->input->get('from-date');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-grup">
                                                <label for="to-date">To Date</label>
                                                <input type="date" name="to-date" id="to-date" value="<?php echo $this->input->get('to-date');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-grup">
                                                <label for="mobile">Mobile</label>
                                                <input type="text" name="mobile" id="mobile" value="<?php echo $this->input->get('mobile');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-grup">
                                                <label for="status">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="0" <?php echo $this->input->get('status') == 0 ? 'selected' : ''; ?>>All</option>
                                                    <option value="1" <?php echo $this->input->get('status') == 1 ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="2" <?php echo $this->input->get('status') == 2 ? 'selected' : ''; ?>>Success</option>
                                                    <option value="3" <?php echo $this->input->get('status') == 3 ? 'selected' : ''; ?>>Cancel</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-info" style="margin-top:25px;">Search</button>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="<?php echo base_url("admin/withdraw-history");?>" class="btn btn-warning" style="margin-top:25px;">Reset</a>
                                        </div>
                                    </div>                                  
                                </form>
                            </div>
                        </div>
                   
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatable" class="display table table-bordered table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User</th>
                                                <th>Phone</th>
                                                <th>Amount</th>
                                                <th>Charges</th>
                                                <th>Transfer Amount</th>
                                                <th>Created</th>
                                                <th>Status</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 0; foreach($history as $k=>$v): $sr++;?>
                                            <tr>
                                                <td><?php echo $sr;?></td>
                                                <td><?php echo $v['name'];?></td>
                                                <td><?php echo $v['phone_number'];?></td>
                                                <td><?php echo $v['amount'];?></td>
                                                <td><?php echo $v['charges'];?></td>
                                                <td><?php echo $v['transfer_amount'];?></td>
                                                <td><?php echo $v['created'];?></td>
                                                <td>
                                                <?php
                                                if($v['status'] == 0){
                                                    echo "Pending";
                                                }elseif($v['status'] == 1){
                                                    echo "Success";
                                                }elseif($v['status'] == 2){
                                                    echo "Cancel";
                                                }else{
                                                    echo "";
                                                }
                                                ?>
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

<?php $this->load->view('admin/components/footer_js.php'); ?>
<script>
$(document).ready(function() {
    var table = $('#datatable').DataTable({
        "dom": 'Bfrtip', // Include the buttons in the table layout
        "buttons": [
            'copy', 'excel', 'pdf' // Add the export buttons you need
        ],
        "bPaginate": false,
    });
});
</script>
<?php $this->load->view('admin/components/footer.php'); ?>