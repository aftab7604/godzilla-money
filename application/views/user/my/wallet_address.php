<?php $this->load->view("user/components/header");?>
<!----------------------------- Main -----------------------------------> 
<main>
    <!------------------------ SECTION 1 ------------------------------->
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class="gradient text-white pt-4 pb-4 ps-0 pe-0" style="border-radius:10px">
        <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center">Wallet Address</h1>    
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
                    <form action="<?php echo base_url("my/wallet-address")?>"  method="POST" enctype="multipart/form-data">
                        <?php
                        if($this->session->userdata("message")){
                            $msg = $this->session->userdata("message")['msg'];
                            $class = $this->session->userdata("message")['class'];
                            $this->session->unset_userdata("message");
                        ?>
                        <div class="mb-4">
                            <div class="text-info" style="font-size:18px;font-style: italic;margin-top:5px;margin-bottom:20px;"><?php echo $msg; ?></div>
                        </div>
                        <?php } ?>
                        <div class="mb-4">
                            <label for="wallet_address" class="form-label">Wallet Address</label>             
                            <textarea name="wallet_address" id="wallet_address" class="form-control"><?php echo $user['crypto_address']; ?></textarea>
                            <?php echo form_error('wallet_address'); ?>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-secondary" type="submit">Enter Wallet Address</button>   
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
    