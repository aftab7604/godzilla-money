<?php $this->load->view("user/components/header");?>
<!----------------------------- Main -----------------------------------> 
<main>
    <!------------------------ SECTION 1 ------------------------------->
    <section class="ps-0 pe-0 pt-0 pb-1">
        <div class=" text-white pt-5 pb-5 ps-0 pe-0 background_image_my" >
        <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h5 class="text-start">ID: <?php echo $user['phone_number'];?></h5>    
                    </div>
                    <div class="col-12">
                        <h5 class="text-start">Balance: <?php echo round($user['wallet'],2);?></h5>    
                    </div>
                    <div class="col-12">
                        <h5 class="text-start">Level: <?php echo $user['level_id'];?></h5>    
                    </div>
                </div>
            </div>
        </div> 
    </section>
    <!------------------------ SECTION 1 ------------------------------->
    
    <!------------------------ SECTION 2 ------------------------------->
    <section class="ps-0 pe-0 pt-1 pb-1">
        <div class="text-black ">
            <div class="container">
                <div class="row text-md-left">
                    <div class="col-12">
                        <a href="<?php echo base_url('my/wallet-address')?>"><p>Wallet Address</p></a>   
                    </div>
                </div>
                <div class="row text-md-left">
                    <div class="col-12">
                        <a href="<?php echo base_url('my/password-change')?>"><p>Change Password</p></a>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                    <a href="<?php echo base_url('my/team');?>"><p>My Team</p></a>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href=<?php echo base_url('my/team-commission');?>><p>Team Commission</p></a>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href=<?php echo base_url("my/recharge");?>><p>Recharge</p></a>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="<?php echo base_url('my/withdraw');?>"><p>Withdraw</p></a>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="<?php echo base_url('my/balance-detail');?>"><p>Balance Details</p></a>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="<?php echo base_url("download-apk");?>"><p>Application Download</p></a>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="<?php echo base_url("logout");?>"><p>Logout</p></a>   
                    </div>
                </div>
            </div>
        </div> 
    </section>
    <!------------------------ SECTION 3 ------------------------------->
    <section>
        
    </section>
    

</main>    
<!------------------------ End Main ------------------------------->
<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>  
<?php $this->load->view('user/components/footer');?>    
    