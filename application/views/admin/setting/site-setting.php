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
            <h1>Site Setting</h1>
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
                                    <form role="form" action="<?php echo base_url('admin/site-setting');?>" method="post" enctype="multipart/form-data">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="support_whatsapp">Support Whatsapp</label>
                                                    <input type="text" class="form-control" name="support_whatsapp" id="support_whatsapp" value="<?php echo $support_whatsapp;?>" >
                                                </div>
                                                <?php echo form_error("support_whatsapp"); ?>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="crypto_address">Crypto Address</label>
                                                    <textarea class="form-control" style="height: 70px;" name="crypto_address" id="crypto_address" placeholder="Crypto Address"><?php echo $crypto_address;?></textarea>
                                                </div>
                                                <?php echo form_error("crypto_address"); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <img style="width:70px; height:70px; margin-top:20px;" src="<?php echo base_url('uploads/admin/cypto_qr_image/'.$crypto_address_image);?>" class="img img-fluid">
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="crypto_address_image">Crypto Address Image</label>
                                                    <input type="file" class="form-control" name="crypto_address_image" id="crypto_address_image" value="<?php echo $crypto_address_image;?>" >
                                                </div>
                                                <?php echo form_error("crypto_address_image"); ?>
                                                <?php 
                                                if($this->session->userdata('crypto_address_image_err')){
                                                $err = $this->session->userdata("crypto_address_image_err");
                                                $this->session->unset_userdata("crypto_address_image_err");
                                                ?>
                                                <div class="text-danger" style="font-size:14px;font-style: italic;margin-top:5px;margin-bottom:20px;"><?php echo $err;?></div>
                                                <?php	
                                                }
                                                ?>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="recharge_min_amount">Minimum Recharge</label>
                                                    <input type="text" class="form-control" name="recharge_min_amount" id="recharge_min_amount" value="<?php echo $recharge_min_amount;?>" >
                                                </div>
                                                <?php echo form_error("recharge_min_amount"); ?>
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="usdt_pkr_rate">USDT PKR Exchange Rate</label>
                                                    <input type="text" class="form-control" name="usdt_pkr_rate" id="usdt_pkr_rate" value="<?php echo $usdt_pkr_rate;?>" placeholder="USDT PKR Exchange Rate">
                                                </div>
                                                <?php echo form_error("usdt_pkr_rate"); ?>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdraw_min_amount">Minimum Withdraw</label>
                                                    <input type="text" class="form-control" name="withdraw_min_amount" id="withdraw_min_amount" value="<?php echo $withdraw_min_amount;?>" >
                                                </div>
                                                <?php echo form_error("withdraw_min_amount"); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdraw_charges">Withdraw Charges</label>
                                                    <input type="text" class="form-control" name="withdraw_charges" id="withdraw_charges" value="<?php echo $withdraw_charges;?>" >
                                                </div>
                                                <?php echo form_error("withdraw_charges"); ?>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdraw_from">Withdraw From</label>
                                                    <input type="time" class="form-control" name="withdraw_from" id="withdraw_from" value="<?php echo $withdraw_from;?>" >
                                                </div>
                                                <?php echo form_error("withdraw_from"); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdraw_to">Withdraw To</label>
                                                    <input type="time" class="form-control" name="withdraw_to" id="withdraw_to" value="<?php echo $withdraw_to;?>" >
                                                </div>
                                                <?php echo form_error("withdraw_to"); ?>
                                            </div>
                                            

                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="home_popup_title">Home Popup Title</label>
                                                    <input type="text" class="form-control" name="home_popup_title" id="home_popup_title" value="<?php echo $home_popup_title;?>" >
                                                </div>
                                                <?php echo form_error("home_popup_title"); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="home_popup_status">Home Popup Status</label>
                                                    <select name="home_popup_status" id="home_popup_status" class="form-control">
                                                        <option value="off" <?php echo $home_popup_status == 'off' ? 'selected' : ''; ?>>Off</option>  
                                                        <option value="on" <?php echo $home_popup_status == 'on' ? 'selected' : ''; ?>>On</option>
                                                    </select>
                                                </div>
                                                <?php echo form_error("home_popup_status"); ?>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="editor1">Home Popup Content</label>
                                                    <textarea class="form-control" name="home_popup_content" id="editor1" rows="20"><?php echo $home_popup_content;?></textarea>
                                                </div>
                                                <?php echo form_error("home_popup_content"); ?>
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