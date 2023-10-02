<?php $this->load->view("user/components/header");?>
<!----------------------------- Main ----------------------------------->
<main>
    <!------------------------ SECTION 1 ------------------------------->
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class="gradient text-white pt-3" style="border-radius:10px; word-wrap:break-word;">
            <div class="container">
                <div class="row">
                    <div class="col-8">
                        <h1 class="small pb-2">My Balance:</h1> 
                    </div>
                    <div class="col-4">
                        <h1 class="small pb-2"><?php echo round($user['wallet'],2);?></h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <h1 class="small pb-2">Today Personal commission: </h1> 
                    </div>
                    <div class="col-4">
                    <h1 class="small pb-2"><?php echo round($today_personal_commission,2);?></h1>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-8">
                    <h1 class="small pb-2">Today Team commission:</h1> 
                    </div>
                    <div class="col-4">
                        <h1 class="small pb-2"><?php echo round($today_team_commission,2);?></h1>
                    </div>
                </div>   
                <div class="row">
                    <div class="col-8">
                    <h1 class="small pb-2">Yesterday Personal commission:</h1> 
                    </div>
                    <div class="col-4">
                        <h1 class="small pb-2"><?php echo round($yesterday_personal_commission,2);?></h1>
                    </div>
                </div>   
                <div class="row">
                    <div class="col-8">
                    <h1 class="small pb-2">Yesterday Team commission:</h1>  
                    </div>
                    <div class="col-4">
                        <h1 class="small pb-2"><?php echo round($yesterday_team_commission,2);?></h1>
                    </div>
                </div>   
                <div class="row">
                    <div class="col-8">
                    <h1 class="small pb-2">Total team commission:</h1> 
                    </div>
                    <div class="col-4">
                        <h1 class="small pb-2"><?php echo round($total_team_commission,2);?></h1>
                    </div>
                </div>   
            </div>
        </div> 
    </section>
    <!------------------------ SECTION 2 ------------------------------->
    <section class="ps-3 pe-3 pt-3 pb-1">
        
    </section>
    <!------------------------ SECTION 3 ------------------------------->
    <section class="ps-3 pe-3 pt-2 pb-1">
        
    </section>
  
    
</main>
<!------------------------ End Main ------------------------------->
<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>  
<?php $this->load->view('user/components/footer');?>    
    