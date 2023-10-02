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
    <?php if(!empty($packages)): foreach($packages as $pk=>$pv): ?>
    <section class="ps-3 pe-3 pt-3 pb-1">
        <div class=" gradient text-white pt-3 pb-3" style="border-radius:10px">
            <div class="container">
                <div class="row">
                    <!-- <div class="col-3 text-center">
                    <img src="https://assets.newatlas.com/dims4/default/cf03008/2147483647/strip/true/crop/1620x1080+2+0/resize/1200x800!/quality/90/?url=http%3A%2F%2Fnewatlas-brightspot.s3.amazonaws.com%2Farchive%2Fhitachi-turbine-7.jpg"  alt="..." width="50px" height="50px">    
                    </div> -->

                    <div class="col-12">
                        <h4><?php echo $pv['name']; ?></h4>
                        <p>The piece of package is Rs.<?php echo $pv['amount']; ?></p>   
                    </div>
                </div>
                <div class="row text-center text-md-left">
                    <div class="col-4">
                        <h1><?php echo round(((($pv['amount'] / 100) * $pv['profit']) * $pv['duration']),2); ?></h1>
                        <p>Earnings (Rs)</p>
                    </div>
                    <div class="col-4">
                        <h1><?php echo $pv['amount']; ?></h1>
                        <p>Invest(Rs)</p>
                    </div>
                    <div class="col-4">
                        <h1><?php echo $pv['duration']; ?></h1>
                        <p>Online Days</p>
                    </div>
                    <?php if($pv['level_id'] <= $user['level_id']):?>
                    <div class="d-grid gap-2 col-12 mx-auto">
                        <button type="button"
                        data-id="<?php echo $pv['id']; ?>"
                        data-name="<?php echo $pv['name']; ?>"
                        data-earning="<?php echo ((($pv['amount'] / 100) * $pv['profit']) * $pv['duration']) ?>" 
                        data-invest="<?php echo $pv['amount']; ?>"
                        data-days="<?php echo $pv['duration'];?>"
                        
                        class="btn btn-block btn-secondary btn-invest">Trade</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div> 
    </section>
    <?php endforeach; endif; ?>
    <section>
        <div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="subscriptionModalLabel">Package Subscription</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="modal-err-msg"></span>
                <div class="mb-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Your Wallet Balance
                                </th>
                                <th><?php echo $user['wallet'];?></th>                            
                            </tr>    
                        </thead>
                        <tbody>
                            <tr>
                                <th>Package</th>
                                <td id="modal-pkg-name">Package Name</td>
                            </tr>
                            <tr>
                                <th>Earnings (Rs)</th>
                                <td id="modal-pkg-earning">5600</td>
                            </tr>
                            <tr>
                                <th>Invest (Rs)</th>
                                <td id="modal-pkg-invest">8000</td>
                            </tr>
                            <tr>
                                <th>Online Days</th>
                                <td id="modal-pkg-days">7</td>
                            </tr>
                        </tbody>
                        
                    </table>
                </div>
                <div class="mb-3">
                    <label for="package_count" class="col-form-label">How many packages you want to buy?:</label>
                    <select name="package_count" id="package_count" class="form-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary" id="btn-purchase">Purchase</button>
            </div>
            </div>
        </div>
        </div>
    </section>
</main>
<!------------------------ End Main ------------------------------->
<?php $this->load->view('user/components/navigation');?>  
<?php $this->load->view('user/components/footer_js');?>  
<script>
$(document).ready(function(){
    $(document).on("click",".btn-invest",function(e){
        e.preventDefault();
        var package_name = $(this).attr("data-name");
        var package_id = $(this).attr("data-id");
        var earning = $(this).attr("data-earning");
        var invest = $(this).attr("data-invest");
        var days = $(this).attr("data-days");

        $("#modal-pkg-name").html(package_name);
        $("#modal-pkg-earning").html(earning);
        $("#modal-pkg-invest").html(invest);
        $("#modal-pkg-days").html(days);

        $("#package_count").val("1");
        $("#btn-purchase").show();
        $("#modal-err-msg").html('');

        $("#subscriptionModal").modal("show");

        $(document).on("change","#package_count",function(){
            var count = $(this).val();
            $("#modal-pkg-earning").html(earning * count);
            $("#modal-pkg-invest").html(invest * count);
        });

        $(document).on("click","#btn-purchase",function(){
            var btn = $(this);
            btn.hide();
            var package_count = $("#package_count").val();
            $.ajax({
                url:"<?php echo base_url("User/subscribe_pakcage_ajax");?>",
                type:"post",
                data:{
                    packageId:package_id,
                    count:package_count
                },
                success:function(response){
                    var respo = JSON.parse(response);
                    if(respo.success){
                        window.location.reload(true);
                    }else{
                        $("#modal-err-msg").html(respo.msg);
                        btn.show();
                    }
                }
            })
        })


    })
});
</script>
<?php $this->load->view('user/components/footer');?>    
    