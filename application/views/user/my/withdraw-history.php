<?php $this->load->view("user/components/header");?>
<!----------------------------- Main ----------------------------------->
<main>
    <!------------------------ SECTION 1 ------------------------------->
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class="gradient text-white ps-1 pe-0 pt-3 pb-2" style="border-radius:10px">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <p>Withdraw Successfully</p> 
                    </div>
                </div>
                <div class="row text-center pb-4">
                    <div class="col-12">
                        <h1><b><?php echo round($total_withdraw_success,2);?></b></h1> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p>Pending Review:<?php echo round($total_withdraw_pending,2);?></p> 
                    </div>
                </div>
            </div>
        </div> 
    </section>

    <section class="ps-3 pe-3 pt-2 pb-1">
        <div class="text-black pt-3 pb-2" style="border-radius:30px">
            <?php if(!empty($txn_history)): foreach($txn_history as $k=>$v):?>
            <div class="container">
                <div class="row">
                        <?php
                        if($v['debit'] == 0){
                            $amount = "+".round($v['credit'],2);
                        }else{
                            $amount = "-".round($v['debit'],2);
                        }
                        ?>
                    <div class="col-9">
                        <b><p><?php echo $v['description']; ?></p></b> 
                    </div>
                    <div class="col-3">
                        <b><p style="float:right;"><?php echo $amount;?></p></b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p>Transaction ID: <?php echo $v['txn_id'];?></p> 
                    </div>
                    <div class="col-6">
                        <p style="float:right;"><?php echo date("d-m-y",strtotime($v['created']));?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; endif;?>
        </div> 
    </section>
    
</main>
<!------------------------ End Main ------------------------------->
<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>  
<?php $this->load->view('user/components/footer');?>    
    