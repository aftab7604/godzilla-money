<?php $this->load->view("user/components/header");?>
<!----------------------------- Main ----------------------------------->
<main>
    <!-- <section class="ps-0 pe-0 pt-0 pb-1">
        <div class=" text-white pt-5 pb-5 ps-0 pe-0 background_image_trade" >
            <div class="container">
            </div>
        </div> 
    </section> -->
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

    <?php if(!empty($packages)): foreach($packages as $pk=>$pv): ?>
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class=" gradient text-white" style="border-radius:10px">
            <div class="container text-md-left">
                <div class="row ps-0 pe-0 pt-2 pb-4 text-md-left">
                    <div class="col-7 ">
                        <h6  class="text-wihte"><?php echo $pv['expire']; ?></h6>
                    </div>

                    <div class="col-5">
                        <div style="border-radius: 15px 0px 0px 50px" > 2 hours settlement</div>
                    </div>
                </div>    

                <div class="row ps-0 pe-0 pt-2 pb-4 text-center text-md-left">
                    <div class="col-4">
                        <h3 ><?php echo round($pv['accumulated'],2); ?></h3>
                        <h1 class="small">Accumulated (Rs)</h1>
                    </div>

                    <div class="col-4">
                        <h3><?php echo round($pv['estimated'],2); ?></h3>
                        <h1 class="small">Estimated (Rs)</h1>
                    </div>

                    <div class="col-4">
                        <h3><?php echo $pv['package_price']; ?> x <?php echo $pv['count']?></h3>
                        <h1 class="small">Rent (Rs)</h1>
                    </div>
                </div>

                <div class="row ps-0 pe-0 pt-0 pb-1 ">
                    
                    <div class="col-8">
                        <h1 class="small" style="line-height: 6pt;"><?php echo $pv['name']; ?></h1>
                        <p style="line-height: 9pt;">work <?php echo round($pv['total_hours']);?> hours</p>
                    </div>

                    <div class="col-4 ">
                        <?php if($pv['enable_receive']):?>
                        <form id="form-<?php echo $pv['id'];?>" action="<?php echo base_url("trade");?>" method="post">
                            <input type="hidden" name="user_package_id" value="<?php echo $pv['id'];?>"> 
                            <button type="submit" data-id="<?php echo $pv['id'];?>" class="btn btn-secondary btn-sm btn-receive-income" style="border-radius: 6px;"> Receive Income</button>
                        </form>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary btn-sm" style="border-radius: 6px;"> At Work</button>
                        <?php endif; ?>
                        
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
<script>
var submitCount = 0;
$(document).on("click",".btn-receive-income",function(e){
    e.preventDefault();
    var id = $(this).attr("data-id");
    $(this).remove();
    submitCount++;
    if(submitCount == 1){
        $("#form-"+id).submit();
    }
})
</script>
<?php $this->load->view('user/components/footer');?>    
    