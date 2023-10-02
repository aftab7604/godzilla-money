<?php $this->load->view("user/components/header");?>
<!----------------------------- Main -----------------------------------> 
<main>
    <!------------------------ SECTION 1 ------------------------------->
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class="gradient text-white pt-4 pb-4 ps-0 pe-0" style="border-radius:10px">
        <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center">Change Password</h1>    
                    </div>
                </div>
            </div>
        </div> 
    </section>
    <!------------------------ SECTION 1 ------------------------------->
   
    <!------------------------ SECTION 2 ------------------------------->
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class="gradient text-white pt-5 pb-5 ps-0 pe-0" style="border-radius:10px">
        <div class="container">
                <div class="row">
                    <div class="col-12">
                    <form action="<?php echo base_url("my/password-change")?>" method="POST">
                        <?php
                        if($this->session->userdata("message")){
                            $msg = $this->session->userdata("message")['msg'];
                            $class = $this->session->userdata("message")['class'];
                            $this->session->unset_userdata("message");
                        ?>
                        <div class="mb-4">
                            <div class="text-danger" style="font-size:18px;font-style: italic;margin-top:5px;margin-bottom:20px;"><?php echo $msg; ?></div>
                        </div>
                        <?php } ?>
                        <div class="mb-4">
                            <label for="old_password" class="form-label">Old Password</label>
                            <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Enter Old Password">              
                            <?php echo form_error('old_password'); ?>
                        </div>
                        <div class="mb-4">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter New Password">              
                            <?php echo form_error('new_password'); ?>
                        </div>
                        <div class="mb-4">
                            <label for="c_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="c_password" id="c_password" placeholder="Enter Confirm Password">              
                            <?php echo form_error('c_password'); ?>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-secondary" type="submit">Change Password</button>   
                        </div>
                    </form>    
                    </div>
                </div>
            </div>
        </div> 
    </section>
    

</main>    
<!------------------------ End Main ------------------------------->
<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>  
<?php $this->load->view('user/components/footer');?>    
    