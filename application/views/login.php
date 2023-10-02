<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
    <h1 class="text-center bg-danger py-3 text-white fw-bold">Login</h1>
    <div class="container  ps-4 pe-4 pt-4 pb-3">
        <form action="<?php echo base_url("login")?>" method="POST">
            <?php
            if($this->session->userdata("message")){
                $msg = $this->session->userdata("message")['msg'];
                $class = $this->session->userdata("message")['class'];
                $this->session->unset_userdata("message");
            ?>
            <div class="mb-4">
                <div class="text-danger" style="font-size:14px;font-style: italic;margin-top:5px;margin-bottom:20px;"><?php echo $msg; ?></div>
            </div>
            <?php } ?>
            <div class="mb-4">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Enter Phone Number">              
                <?php echo form_error('phone_number'); ?>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">              
                <?php echo form_error('password'); ?>
            </div>
            <div class="row">
                <div class="col-12 d-grid gap-2">
                    <button class="btn btn-primary" type="submit">Login</button>
                </div>
            </div>

            <div class="row">
                <div class="col-12 d-grid gap-2 pt-2">
                    <a href="<?php echo base_url('join/'.$default_referral_id);?>" class="btn btn-primary">Don't have an account? Register Now </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 pt-3 pb-1">
                    <a href="<?php echo base_url('download-apk'); ?>" class="text-primary"><img src="<?php echo base_url('assets/images/app_logo4.jpg');?>" alt="" width="270px" style="display: block;margin-left: auto;margin-right: auto;"></a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p class="text-center">If u have not downloaded App - <a href="<?php echo base_url('download-apk'); ?>" >Download Now</a>
                </div>
            </div>   
            <!-- <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Login</button>   
            </div> -->
            <!-- <div class="d-grid gap-2 mt-3">
                <p>Don't have account? <a href="<?php echo base_url('join/'.$default_referral_id);?>">Register Now</a></p>   
            </div> -->
        </form>
    </div>     
</body>
</html>