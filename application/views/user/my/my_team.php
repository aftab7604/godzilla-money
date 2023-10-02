<?php $this->load->view("user/components/header");?>
<!----------------------------- Main ----------------------------------->
<main>
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class="gradient text-white pt-2 pb-2" style="border-radius:10px">
            <div class="container">
                <div class="row ">
                    <div class="col-12">
                        <h3>LV-1: <?php echo $lvl_1['total_verified_team']; ?></h3>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 pt-0">
                        <h3>LV-2: <?php echo $lvl_2['total_verified_team']; ?>    
                    </div>
                </div>
            </div>
        </div> 
    </section>

    <?php if(!empty($lvl_1['users'])): foreach($lvl_1['users'] as $k=>$v):?>
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class="gradient text-white pt-5 pb-5" style="border-radius:10px">
            <div class="container">
                <div class="row ">
                    <div class="col-7">
                        <h1 class="small">Balance : <?php echo round($v['wallet'],2); ?></h1>    
                    </div>
                    <div class="col-5 text-md-left">
                        <h1 class="small"><?php echo $v['phone_number']; ?></h1>    
                    </div>
                </div>
                <div class="row">
                    <div class="col pt-2">
                        <h1 class="small">Recharge : <?php echo round($v['recharge'],2); ?></h1>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-7 pt-2">
                        <h1 class="small">Withdraw : <?php echo round($v['withdraw'],2); ?></h1>    
                    </div>
                    <div class="col-5 text-md-left">
                        <h3>LV-1</h3>    
                    </div>
                </div>
            </div>
        </div> 
    </section>
    <?php endforeach; endif; ?>

    <?php if(!empty($lvl_2['users'])): foreach($lvl_2['users'] as $k=>$v):?>
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class=" gradient text-white pt-5 pb-5" style="border-radius:10px">
            <div class="container">
                <div class="row ">
                    <div class="col-7">
                        <h1 class="small">Balance : <?php echo round($v['wallet'],2); ?></h1>    
                    </div>
                    <div class="col-5 text-md-left">
                        <h1 class="small"><?php echo $v['phone_number']; ?></h1>    
                    </div>
                </div>
                <div class="row">
                    <div class="col pt-2">
                        <h1 class="small">Recharge : <?php echo round($v['recharge'],2); ?></h1>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-7 pt-2">
                        <h1 class="small">Withdraw : <?php echo round($v['withdraw'],2); ?></h1>    
                    </div>
                    <div class="col-5 text-md-left">
                        <h3>LV-2</h3>    
                    </div>
                </div>
            </div>
        </div> 
    </section>
    <?php endforeach; endif; ?>
</main>
<!------------------------ End Main ------------------------------->
<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>  
<?php $this->load->view('user/components/footer');?>    
    