<?php $this->load->view("user/components/header");?>
<!----------------------------- Main ----------------------------------->
<main>
    <!------------------------ Slider Section ------------------------------->
    <section class="ps-3 pe-3 pt-3 pb-2" >
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" >
            <div class="carousel-indicators">
                <?php if(!empty($slider_images)): foreach($slider_images as $k=>$v):?>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?php echo $k;?>" class="<?php echo ($k == 0) ? 'active' : ''; ?>" <?php echo ($k == 0) ? 'aria-current="true"' : ''; ?>  aria-label="Slide <?php echo $k+1; ?>"></button>
                <?php endforeach; endif; ?>
            </div>
            <div class="carousel-inner" style="border-radius:20px">
                <?php if(!empty($slider_images)): foreach($slider_images as $k=>$v):?>
                <div class="carousel-item <?php echo ($k == 0) ? 'active' : ''; ?>">
                    <img src="<?php echo base_url("uploads/admin/home_slider/".$v['image']); ?>" class="d-block w-100" alt="..." width="1400px" height="250px">
                </div>
                <?php endforeach; endif; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div> 
    </section>
    <!------------------------ Slider Section End------------------------------->
    <!------------------------ Links Section Start------------------------------------>
    <section>
        <div class=" text-black ps-3 pe-3 pt-3 pb-2">
            <div class="container">
                <div class="row text-center text-md-left">
                    <div class="col-3">
                        <a href="<?php echo base_url("my/recharge");?>"  >    
                            <img src="<?php echo base_url('assets/images/recharge.png');?>" alt="" width="32px" height="25px">
                            <h1 class="small pt-1">Recharge</h1>
                        </a>  
                    </div>
                    <div class="col-3">
                        <a href="https://wa.me/<?php echo $support_whatsapp;?>">    
                            <img src="<?php echo base_url('assets/images/our-service.png');?>" alt="" width="32px" height="25px">
                            <h1 class="small pt-1">Service</h1>
                        </a>  
                    </div>
                    <div class="col-3">
                        <a href="<?php echo base_url('invest');?>" class="<?php echo ($this->uri->segment(1) == 'invest') ? 'link-success' : ''; ?>"  >    
                            <img src="<?php echo base_url('assets/images/invest-plan.png');?>" alt="" width="32px" height="25px" >
                            <h1 class="small pt-1">Invest plan</h1>
                        </a>
                    </div>    
                    <div class="col-3">
                        <a href="<?php echo base_url('my/withdraw');?>"  >    
                            <img src="<?php echo base_url('assets/images/withdraw.png');?>" alt="" width="32px" height="25px">
                            <h1 class="small pt-1">Withdraw</h1>
                        </a>  
                    </div>
                </div>
            </div>
        </div> 
       
    </section>
    <!------------------------ Links Section End --------------------------------->
    <!------------------------ Video Section Start ------------------------------->
    <section class="ps-3 pe-3 pt-3 pb-2">
        <div class="col-12">
            <h5>Godzilla Money Trading Process</h5>
            <hr>
            <iframe width="100%" height="220px" src="https://www.youtube.com/embed/A0ZUoW9tmzg?si=96-p5T5acs92ZQS8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            <!-- <video width="100%" height="220px" autoplay muted controls>
                <source src="assets/videos/gzm_video.mp4" type="video/mp4">
            </video> -->
        </div>
    </section>
    <!------------------------ Video Section End ---------------------------------->
    <!------------------------ Content Section Start ------------------------------>
    <section class="ps-3 pe-3 pt-3 pb-2">
            <h5>We provide Handsome Bonus on your Investment.</h5>
            <hr>
            <img src="assets/images/home_banner_prosper.jpeg" alt="" width="100%" height="280px">   
    </section>
    <!------------------------ Content Section End --------------------------------->
    <!------------------------ Content Section Start 2 ------------------------------>
    <section class="ps-3 pe-3 pt-3 pb-2">
            <h5>
                Make money easily with us.<br/><br/>
                You can even make money by just keeping your assets.<br/><br/>
                Plus, invite members to join, and We'll help you all get richer together! <br/><br/> 
                Start now and unlock your path to wealth.
            </h5>
            <hr>
            <!-- <img src="assets/images/slide6.jpeg" alt="" width="100%" height="280px">    -->
    </section>
    <!------------------------ Content Section 2 End --------------------------------->
</main>
<!------------------------ End Main ------------------------------->
<!-- home popup modal -->
<?php if($home_popup['status'] == 'on'):?>
<div class="modal fade" id="homePopupModal" tabindex="-1" role="dialog" aria-labelledby="homePopupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php if(!empty($home_popup['title'])):?>
            <div class="modal-header">
                <h5 class="modal-title" id="homePopupModalLabel"><?php echo $home_popup['title']; ?></h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <?php endif; ?>
            <div class="modal-body">
                <?php echo $home_popup['content']; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary home_popup_close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- end home popup modal -->

<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>
<script>
$(document).ready(function(){
    <?php if($home_popup['status'] == 'on'):?>
    $("#homePopupModal").modal("show");
    $(".home_popup_close").click(function(){
        $("#homePopupModal").modal("hide");
    })
    <?php endif;?>
});
</script>
<?php $this->load->view('user/components/footer');?>      
    