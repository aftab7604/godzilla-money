<?php $this->load->view("user/components/header");?>
<!----------------------------- Main ----------------------------------->
<main>
    <?php
    if($this->session->userdata("message")):
        $class = $this->session->userdata("message")['class'];
        $msg = $this->session->userdata("message")['msg'];
        $this->session->unset_userdata("message");
        ?>
        <section class="p-3">
            <div class="alert alert-<?php echo $class;?>"><?php echo $msg; ?></div>
        </section>
        <?php
    endif;
    ?>
  
    <form action="<?php echo base_url('my/withdraw'); ?>" method="post">  
        <section class="ps-3 pe-3 pt-3 pb-2">
            <div class="gradient text-white ps-0 pe-0 pt-3 pb-5" style="border-radius:10px">
                <div class="container">
                    <div class="row">
                        <div class="col-12 ps-2 pt-2">
                            <h1 class="small">Balance: <?php echo round($user['wallet'],2);?></h1>    
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 ps-2 pt-2">
                            <h1 class="small">Total Withdrawal: <?php echo round($total_withdrawn,2); ?></h1>    
                        </div>

                        <div class="col-5 ps-2 pt-2">
                            <!-- <h1 class="small">Withdrawal record</h1>     -->
                        </div>
                    </div>
                    <div class="row ps-4 pe-5 pt-3 pb-4">
                        <div class="col-1 ps-0 pe-0">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Rs" style="color:white; font-weight:bold">
                        </div>
                        <div class="col-11 input-bx">
                            <label for="amount" class="visually-hidden">Please enter the amount</label>
                            <input type="text" class="form-control text-center" id="amount" name="amount" placeholder="Please enter the amount">
                            <?php echo form_error("amount");?>
                        </div>
                    </div>
                    <div class="row ps-0 pe-0 pt-2">
                        <div class="col-12 d-grid gap-2">
                            <button class="btn btn-secondary" type="submit">Withdraw Now</button>   
                        </div>
                    </div>
                    <div class="row ps-0 pe-0 pt-2">
                        <div class="col-12 d-grid gap-2">
                            <a href="<?php echo base_url('my/withdraw-history'); ?>" class="btn btn-secondary">Withdraw History</a>
                        </div>
                    </div>
                </div> 
            </div>   
        </section>
        <!------------------------ SECTION 2 ------------------------------->
        <section class="ps-0 pe-0 pt-0 pb-0">
            <div class="text-black ps-0 pe-0 pt-3 pb-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 ps-3">

                            <h1 class="small"><b>1. The daily withdrawal time is <?php echo date("h:i-a",strtotime($withdraw_from)) .' to '.date("h:i-a",strtotime($withdraw_to)); ?></b></h1>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 ps-3 pt-2">
                            <h1 class="small"><b>2. Minimum withdrawal amount of Rs.<?php echo $min_withdraw; ?></b></h1>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 ps-3 pt-2">
                            <h1 class="small"><b>3. A service fee <?php echo $withdraw_charges;?>% will be charged for each withdrawal<br/><br/>withdrawals.</b></h1>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 ps-3 pt-2">
                            <h1 class="small"><b>4. Withdrawal funds will reach the account within <br/><br/> 48 hours</b></h1>    
                        </div>
                    </div>

                    

                </div> 
            </div>
        
        </section>
    </form>   
</main>
<!------------------------ End Main ------------------------------->
<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>  
<?php $this->load->view('user/components/footer');?>    
    