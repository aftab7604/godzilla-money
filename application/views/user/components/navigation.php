<!------------------------ Footer ------------------------------->
<div class="sticky-bottom">
    <footer class=" bg-white text-black pt-2 pb-2 ">

        <div class="container text-center text-md-left">
    
            <div class="row text-center text-md-left">
                <div class="col">
                    <a href="<?php echo base_url();?>" class="<?php echo ($this->uri->segment(1) == '') ? 'link-success' : ''; ?>">
                    <img src="<?php echo base_url('assets/images/home.png');?>" alt="" width="22px" height="22px">
                    <br>Home
                    </a>
                </div>
    
                <div class="col">
                    <a href="<?php echo base_url("share")?>" class="<?php echo ($this->uri->segment(1) == 'share') ? 'link-success' : ''; ?>">
                    <img src="<?php echo base_url('assets/images/share.png');?>" alt="" width="22px" height="22px">
                    <br>
                    Share
                    </a>
                </div>

                <div class="col">
                    <a href="<?php echo base_url('invest');?>" class="<?php echo ($this->uri->segment(1) == 'invest') ? 'link-success' : ''; ?>">
                    <img src="<?php echo base_url('assets/images/invest.png');?>" alt="" width="22px" height="22px">
                    <br>
                    Invest
                    </a>
                </div>
    
                <div class="col">
                    <a href="<?php echo base_url('trade');?>" class="<?php echo ($this->uri->segment(1) == 'trade') ? 'link-success' : ''; ?>">    
                    <img src="<?php echo base_url('assets/images/trade.png');?>" alt="" width="22px" height="22px">
                    <br>
                    Trade
                    </a>
                </div>
                
                <div class="col">
                    <a href="<?php echo base_url('my');?>" class="<?php echo ($this->uri->segment(1) == 'my') ? 'link-success' : ''; ?>">
                    <img src="<?php echo base_url('assets/images/my.png');?>" alt="" width="22px" height="22px">
                    <br>
                    Profile
                    </a>
                </div>
            </div>
        </div>
    </footer> 
        <!------------------------ End Footer ------------------------------->
</div>