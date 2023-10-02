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
            <h1>User Detail<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
                <li>User List</li>
                <li class="active"> User Detail</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="listGroup" class="box box-info">
                        <div class="box-header with-border">
                            <div class="col-md-5">User Detail</div>
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
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <td><?php echo $user['name']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mobile</th>
                                                <td><?php echo $user['phone_number']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Level</th>
                                                <td><?php echo $user['level_id']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Wallet Balance</th>
                                                <td><?php echo round($user['wallet'],2); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Referral Id</th>
                                                <td><?php echo $user['referral_id']; ?></td>
                                            </tr>
                                            
                                            <tr>
                                                <th>Crypto Address</th>
                                                <td>
                                                    <?php echo $user['crypto_address']; ?>
                                                    <br>
                                                    <a href="<?php echo base_url("admin/user-detail/".$user['id']."?action=reset");?>" class="text-danger">Reset</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Upline</th>
                                                <td><?php echo $user['upline']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Withdraw</th>
                                                <td><?php echo $user['withdraw']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Recharge</th>
                                                <td><?php echo $user['recharge']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Income</th>
                                                <td><?php echo $user['total_income']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Password</th>
                                                <td><?php echo $user['password_text']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <?php
                                                    if($user['status'] == 0){
                                                    ?>
                                                    Inactive
                                                    |
                                                    <a class="text-link" href="<?php echo base_url("admin/user-detail/".$user['id']."?action=change-status&status=1");?>" onclick="return confirm('Are you sure you want to mark this user as Active?')">Mark as Active</a>
                                                    |
                                                    <a class="text-link" href="<?php echo base_url("admin/user-detail/".$user['id']."?action=change-status&status=2");?>" onclick="return confirm('Are you sure you want to mark this user as Deleted?')">Mark as Deleted</a>
                                                    <?php
                                                    }elseif($user['status'] == 1){
                                                    ?>
                                                    Active
                                                    |
                                                    <a class="text-link" href="<?php echo base_url("admin/user-detail/".$user['id']."?action=change-status&status=0");?>" onclick="return confirm('Are you sure you want to mark this user as Inactive?')">Mark as Inactive</a>
                                                    |
                                                    <a class="text-link" href="<?php echo base_url("admin/user-detail/".$user['id']."?action=change-status&status=2");?>" onclick="return confirm('Are you sure you want to mark this user as Deleted?')">Mark as Deleted</a>
                                                    <?php
                                                    }elseif($user['status'] == 2){
                                                    ?>
                                                    Deleted
                                                    |
                                                    <a class="text-link" href="<?php echo base_url("admin/user-detail/".$user['id']."?action=change-status&status=0");?>" onclick="return confirm('Are you sure you want to mark this user as Inactive?')">Mark as Inactive</a>
                                                    |
                                                    <a class="text-link" href="<?php echo base_url("admin/user-detail/".$user['id']."?action=change-status&status=1");?>" onclick="return confirm('Are you sure you want to mark this user as Active?')">Mark as Active</a>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created</th>
                                                <td><?php echo $user['created']; ?></td>
                                            </tr>
                                            
                                            
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