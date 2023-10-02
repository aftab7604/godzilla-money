<?php $this->load->view("user/components/header");?>
<!----------------------------- Main ----------------------------------->
<style>

        #copy-btn{
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }

        #link{
            
            padding-right: 40px;
            border: 3px solid #212121;
            background-color: green;
            border:none;
            color:white;
            text-align:center;
            border-radius: 5px 0px 0px 5px;
            font-size: 13px;
            word-wrap:break-word;
        }
        
        #copy-btn{
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            text-align:center;
            width:80px;
            border-radius:0px 5px 5px 0px;
        }

    </style>   
<h1 class="text-center bg-danger py-3 text-white fw-bold">
    Recharge Request
</h1>
<div class="container p-4">
    <form action="<?php echo base_url('my/recharge');?>" method="POST" enctype="multipart/form-data">
        <?php
        if($this->session->userdata("message")){
            $msg = $this->session->userdata("message")['msg'];
            $class = $this->session->userdata("message")['class'];
            $this->session->unset_userdata("message");
        ?>
        <div class="mb-4">
            <div class="alert alert-<?php echo $class; ?>" ><?php echo $msg; ?></div>
        </div>
        <?php } ?>
    
        <div class="mb-4">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount">              
            <?php echo form_error("amount");?>
        </div>
        <div class="mb-4">
            <label for="usdt" class="form-label">USDT</label>
            <input type="number" disabled class="form-control" id="usdt_placeholder" placeholder="Enter USDT">              
            <?php echo form_error("usdt");?>
            <input type="hidden" name="usdt" value="" id="usdt">
        </div>
        <div class="mb-4">
            <div class="container p-4">
            <img class="img img-fluid" src="<?php echo base_url("uploads/admin/cypto_qr_image/".$crypto_address_image)?>" alt="qr-code-image">
            </div>
            <div class="input-group copy-field mb-3">
                <input type="hidden" value="<?php echo $crypto_address;?>"  id="copy-text">
                <span   id="link" class="form-control p-3 gradient"><?php echo $crypto_address;?></span>
                <button class="btn btn-secondary" type="button" id="copy-btn">Copy</button>
            </div>
        </div>
        <div class="mb-4">
            <label for="binance_txn_id" class="form-label">Transaction Id</label>
            <input type="text" class="form-control" id="binance_txn_id" name="binance_txn_id">     
            <?php echo form_error("binance_txn_id");?>
        </div>
        <div class="mb-4">
            <label for="proof" class="form-label">Add Proof</label>
            <input type="file" class="form-control" id="proof" name="proof">     
            <?php echo form_error("proof");?>
            <?php
            if($this->session->userdata("img_err")){
                $error = $this->session->userdata("img_err");
                $this->session->unset_userdata("img_err");
                echo '<div class="text-danger" style="font-size:14px;font-style: italic;margin-top:5px;margin-bottom:20px;">'.$error.'</div>';
            }
            ?>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-secondary" type="submit">Submit</button>   
        </div>
    </form>
</div>     
<!------------------------ End Main ------------------------------->
<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>  
<script>
$(document).ready(function(){
    var  exchange_rate = "<?php echo $usdt_pkr_rate; ?>";
    $("#amount").keyup(function(){
        var amt = $(this).val();
        var usdt = (parseFloat(amt) / parseFloat(exchange_rate));
        $("#usdt,#usdt_placeholder").val(usdt);
    })
});
</script>

<script>
$(document).ready(function() {
    $('#copy-btn').click(function() {
        var btn = $(this);
        var text = $('#copy-text').val();
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(text).select();
        document.execCommand('copy');
        tempInput.remove();
        btn.html("Copied");
        setTimeout(function (){
            btn.html("Copy");
        }, 2000)
    });
});
</script>
<?php $this->load->view('user/components/footer');?>    
    