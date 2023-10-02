<?php
class User extends CI_Controller {
    
	public function __Construct()
	{
		parent::__construct();
		$this->load->model('User_Model');
		$this->form_validation->set_error_delimiters('<div class="text-danger" style="font-size:14px;font-style: italic;margin-top:5px;margin-bottom:20px;">', '</div>');
        date_default_timezone_set("Asia/Karachi");
    }

    public function check_login(){
        if($this->session->userdata("user-login") == null){
            redirect(base_url('login'));
        }

        $ip = $this->input->ip_address();
        $user = $this->get_user();
        if($user['ip_address'] != $ip || $user['status'] == 2){
            redirect(base_url('logout'));
        }
    }

    public function get_user(){
        $user = $this->session->userdata("user-login");
        return $this->User_Model->get_user_by_id($user['id']); 
    }

    public function index(){
        $this->check_login();
        $user = $this->get_user();
        $data['user'] = $user;
        $data['slider_images'] = $this->db->get("home_slider_images")->result_array();
        $data['support_whatsapp'] = $this->User_Model->get_setting_value_by_key("support_whatsapp"); 
        
        $data['home_popup']['status'] = $this->User_Model->get_setting_value_by_key("home_popup_status");
        $data['home_popup']['title'] = $this->User_Model->get_setting_value_by_key("home_popup_title"); 
        $data['home_popup']['content'] = $this->User_Model->get_setting_value_by_key("home_popup_content"); 
        $this->load->view("user/dashboard",$data);
    }

    public function my(){
        $this->check_login();
        $user = $this->get_user();
        $data['user'] = $user;
        $this->load->view('user/my/links',$data);
    }

    public function do_upload($fileName,$path,$allowed_types = 'gif|jpg|jpeg|png|svg'){
		$config['upload_path'] = './uploads/'.$path;
		$config['allowed_types'] = $allowed_types;
		// $config['max_size']	= '20000000000000';
		$config['remove_spaces'] = TRUE;        
        $config['encrypt_name'] = TRUE;
		// $config['max_width']  = '160';
		// $config['max_height']  = '190';
		$this->load->library('upload', $config);
		
		if (!$this->upload->do_upload($fileName)){
			return array('error' => trim(trim($this->upload->display_errors(),"<p>"),"</p>"),'upload_data' => "");
		}else{
			$uploadedImage = $this->upload->data();
			return array('upload_data' => $uploadedImage,'error' =>"");
		}
	}

    public function recharge_request(){
        $this->check_login();
        $user = $this->get_user();
        $data['crypto_address_image'] = $this->User_Model->get_crypto_address_image();
        $data['crypto_address'] = $this->User_Model->get_crypto_address();
        $data['usdt_pkr_rate'] = $this->User_Model->get_usdt_pkr_rate();

        $this->form_validation->set_rules('amount','Amount','trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('usdt','USDT','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('binance_txn_id','Transaction Id','trim|required|is_unique[deposit_request.binance_txn_id]|xss_clean',['is_unique'=>'This Transaction ID is Already used, contact with Customer Support']);
        if(empty($_FILES['proof']['name'])){
            $this->form_validation->set_rules('proof','Proof','trim|required|xss_clean');
        }

		if ($this->form_validation->run() == FALSE) {
			$this->load->view("user/recharge/request",$data);
		}else{
             
            $user_id = $user['id'];
            $controls = $this->input->post();
            $min_recharge = $this->User_Model->get_setting_value_by_key("recharge_min_amount");
            if($controls['amount'] >= $min_recharge){
                $reqExist = $this->db->where(["user_id"=>$user_id,"status"=>0])->get("deposit_request")->row_array();
                if(empty($reqExist)){
                    $uploaded = $this->do_upload('proof','user/payment_proof/','gif|jpg|jpeg|png|svg');
                    if($uploaded['upload_data'] == ''){
                        $this->session->set_userdata("img_err",$uploaded['error']);
                        redirect(base_url('my/recharge'));
                    }

                    $file_name = $uploaded['upload_data']['file_name'];
                    
                    $inserted = $this->User_Model->submit_recharge_request($user_id,$controls,$file_name);
                    if($inserted){
                        $this->session->set_userdata("message",['class'=>'success','msg'=>'Recharge Request Submitted']);
                    }else{
                        $this->session->set_userdata("message",['class'=>'danger','msg'=>'Something went wrong...']);
                    }
                }else{
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>'A request is already pending']);
                }
            }else{
                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Minimum recharge  is Rs '. $min_recharge .'']);
            }
            redirect(base_url('my/recharge'));
        }
    }

    public function wallet_transaction($type,$user_id,$amount,$description,$ref,$txn_for){
        $user = $this->User_Model->get_user_by_id($user_id);
        if(!empty($user)){
            $op_bal = $user['wallet'];
            if($type == 'credit'){
                $c_bal = $op_bal + $amount;
                $credit = $amount;
                $debit = 0;
                $txn = $this->User_Model->insert_user_wallet_transaction($user_id,$credit,$debit,$op_bal,$c_bal,$description,$ref,$txn_for);
                if($txn){
                    $data['success'] = true;
                    $data['msg'] = $txn['msg'];
                    $data['txn'] = $txn['txn'];
                }else{
                    $data['success'] = false;
                    $data['msg'] = $txn['msg'];
                    $data['txn'] = $txn['txn'];
                }
            }elseif($type == 'debit'){
                $c_bal = $op_bal - $amount;
                $credit = 0;
                $debit = $amount;
                $txn = $this->User_Model->insert_user_wallet_transaction($user_id,$credit,$debit,$op_bal,$c_bal,$description,$ref,$txn_for);
                if($txn){
                    $data['success'] = true;
                    $data['msg'] = $txn['msg'];
                    $data['txn'] = $txn['txn'];
                }else{
                    $data['success'] = false;
                    $data['msg'] = $txn['msg'];
                    $data['txn'] = $txn['txn'];
                }
            }else{
                $data['success'] = false;
                $data['msg'] = 'Invalid Transaction Type';
                $data['txn'] = null;
            }
        }else{
            $data['success'] = false;
            $data['msg'] = 'Invalid User ID Provided';
            $data['txn'] = null;
        }

        return $data;
    }
  
    public function invest(){
        $this->check_login();
        $user = $this->get_user();
        $user_id = $user['id'];
        $data['packages'] = $this->User_Model->get_available_packages_for_user($user_id);
        $data['user'] = $user;
        $this->load->view("user/invest/invest",$data);
    }

    public function subscribe_pakcage_ajax(){
        $user = $this->get_user();
        $controls = $this->input->post();
        $package_id = $controls['packageId'];
        $count = $controls['count'];

        $package = $this->User_Model->get_package_by_id($package_id);
        if(!empty($package)){
            $subscriptions_count = $this->User_Model->count_user_subscribed_packages_by_level($user['id']);
            $package_count = 0;
            if(!empty($subscriptions_count)) : foreach($subscriptions_count as $k=>$v): if($package['level_id'] == $v['level_id']):
            $package_count = $v['count'];
            endif; endforeach; endif;

            if($package['level_id'] <= $user['level_id']){
                $level = $this->db->where("id",$package['level_id'])->get("level")->row_array();
                $allowed_count = $level['package_limit'];
                // if($package['level_id'] == 1){
                //     $allowed_count = 20;
                // }else{
                //     $allowed_count = 5;
                // }
                if($package_count  <= $allowed_count){
                    $remaining_count = $allowed_count - $package_count;
                    if($count  <= $remaining_count){
                        $invest = ($package['amount']  * $count);
                        if($user['wallet'] >= $invest){
                            $user_package = $this->User_Model->insert_user_package_purchase($user['id'],$package_id,$count);
                            if($user_package){
                                $description = 'You have purchased Package';
                                $ref = $user_package;
                                $txn_for = "package-subscription";
                                $txn = $this->wallet_transaction('debit',$user['id'],$invest,$description,$ref,$txn_for);
                                if($txn['success']){
                                    $this->session->set_userdata("message",['class'=>'success','msg'=>'Package Subscribed.']);
                                    $data = array("success"=>true,'msg'=>'Package Subscribed.'); 
                                }else{
                                    $this->db->where("id",$user_package)->update("user_package",['status'=>2]); // delete
                                    $data = array("success"=>false,'msg'=>$txn['msg']);   
                                }
                            }else{
                                $data = array("success"=>false,'msg'=>'Error in inserting package');    
                            }
                        }else{
                            $data = array("success"=>false,'msg'=>'Not enough balance in your wallet');
                        }
                    }else{
                        $data = array("success"=>false,'msg'=>'You have only '.$remaining_count.' Trades of level '.$package['level_id'].' left ');
                    }
                }else{
                    $data = array("success"=>false,'msg'=>'You can\'t keep active more than '.$level['package_limit'].' packages of level '.$package['level_id']);
                }
            }else{
                $data = array("success"=>false,'msg'=>'You are not eligible for Level '.$package['level_id'].' Packages');
            }
        }else{
            $data = array("success"=>false,'msg'=>'Package Not Found');
        }

        echo json_encode($data,JSON_PRETTY_PRINT);
    }

    public function my_team(){
        $this->check_login();
        $user = $this->get_user();
        $data['lvl_1'] = $this->User_Model->get_team_stats_by_referral_id(array($user['referral_id']));
        $data['lvl_2'] = $this->User_Model->get_team_stats_by_referral_id($data['lvl_1']['referral_ids']);
        $this->load->view("user/my/my_team",$data);
    }

    public function team_commission(){
        $this->check_login();
        $user = $this->get_user();
        $data['user'] = $user;
        $from = date("Y-m-d 00:00:00");
        $to = date("Y-m-d 23:59:59");
        $data['today_personal_commission'] = $this->User_Model->get_user_commission_by_from_to_date($user['id'],$from,$to,"personal");
        $data['today_team_commission'] = $this->User_Model->get_user_commission_by_from_to_date($user['id'],$from,$to,"team");

        $from = date("Y-m-d 00:00:00",strtotime("-1 day"));
        $to = date("Y-m-d 23:59:59",strtotime("-1 day"));
        $data['yesterday_personal_commission'] = $this->User_Model->get_user_commission_by_from_to_date($user['id'],$from,$to,"personal");
        $data['yesterday_team_commission'] = $this->User_Model->get_user_commission_by_from_to_date($user['id'],$from,$to,"team");
        
        $from = "";
        $to = "";
        $data['total_team_commission'] = $this->User_Model->get_user_commission_by_from_to_date($user['id'],$from,$to,"team");
     
        $this->load->view("user/my/team_commission",$data);
    }
  
    public function balance_detail(){
        $this->check_login();
        $user = $this->get_user();
        $user_id = $user['id'];
        $data['user'] = $user;
        $data['txn_history'] = $this->User_Model->get_user_txn_history($user_id);
        $data['total_income'] = $this->User_Model->count_user_total_income($user_id);
        $this->load->view("user/my/balance_detail",$data);
    }

    public function withdraw(){
        $this->check_login();
        $user = $this->get_user();
        $from = $this->User_Model->get_setting_value_by_key("withdraw_from");
        $to = $this->User_Model->get_setting_value_by_key("withdraw_to");
        $min_amount = $this->User_Model->get_setting_value_by_key("withdraw_min_amount");
        $charges = $this->User_Model->get_setting_value_by_key("withdraw_charges");
        $total_withdrawn = $this->db->select("sum(debit) as total")->where(["user_id"=>$user['id'],'txn_for'=>'withdraw-success'])->get("wallet_transaction")->row_array()['total'];
     
        
        $data['user'] = $user;
        $data['withdraw_from'] = $from;
        $data['withdraw_to'] = $to;
        $data['min_withdraw'] = $min_amount;
        $data['withdraw_charges'] = $charges;
        $data['total_withdrawn'] = !empty($total_withdrawn) && $total_withdrawn > 0 ? $total_withdrawn : 0;

        $this->form_validation->set_rules('amount','Amount','trim|required|numeric|xss_clean');
		if ($this->form_validation->run() == FALSE) {
            $this->load->view("user/my/withdraw",$data);
        }else{
            $controls = $this->input->post();
            $current = date("H:i");
            $amount = $controls['amount'];
            if (strtotime($current) >= strtotime($from) && strtotime($current) <= strtotime($to)) {
                if ($amount >= $min_amount) {
                    $pending_requests = $this->db->where(['user_id'=>$user['id'],'status'=>0])->get("withdraw_request")->result_array();
                    if(empty($pending_requests)){
                        if($user['wallet'] >=  $amount){
                            if(!is_null($user['crypto_address']) && !empty($user['crypto_address'])){
                                $service_charges = (($amount / 100) * $charges);
                                $debit_amount = $amount - $service_charges;
                                $description = "Withdraw (Pending)";
                                $ref = "";
                                $txn_for = "withdraw-pending"; 

                                $txn = $this->wallet_transaction("debit",$user['id'],$amount,$description,$ref,$txn_for);
                                if($txn['success']){
                                    $txn_id = $txn['txn']['id'];
                                    $inserted = $this->User_Model->insert_withdraw_request($user['id'],$txn_id,$amount,$service_charges,$debit_amount);
                                    if($inserted){
                                        $this->session->set_userdata("message",['class'=>'success','msg'=>"Your withdrawl request sent to admin."]);
                                    }else{
                                        $this->session->set_userdata("message",['class'=>'danger','msg'=>"Something went wrong with inserting withdraw request"]);
                                    }
                                }else{
                                    $this->session->set_userdata("message",['class'=>'danger','msg'=>$txn['msg']]);
                                }
                            }else{
                                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Update your Crypto Address']);
                            }
                        }else{
                            $this->session->set_userdata("message",['class'=>'danger','msg'=>'Insufficient Balance.']);
                        }
                    }else{
                        $this->session->set_userdata("message",['class'=>'danger','msg'=>'You have already requested a withdraw.']);    
                    }
                }else{
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>'Minimum withdraw is Rs '.$min_amount]);
                }    
            }else{
                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Withdraw is only allowed from '.$from.' to '.$to]);
            }

            redirect(base_url('my/withdraw'));
        }
    }

    public function trade(){
        $this->check_login();
        $user = $this->get_user();
        $user_id = $user['id'];
        $data['packages'] = $this->User_Model->get_user_actve_subscribed_packages($user_id);
        $data['user'] = $user;

        $this->form_validation->set_rules('user_package_id','Invalid Request','trim|required|numeric|xss_clean');
        if($this->form_validation->run() == FALSE){
            $this->load->view("user/trade/trade",$data);
        }else{
            $u_p_id = $_POST['user_package_id'];
            $user_package = $this->db->where("id",$u_p_id)->get("user_package")->row_array();
            if(!empty($user_package)){
                $subscription_details = $this->User_Model->get_user_package_rewards($u_p_id);
                $amount = $subscription_details['available_rewards']['income'];
                $enable_receive = $subscription_details['enable_receive'];
                if($enable_receive){
                    if($amount > 0){
                        $received_at = date("Y-m-d H:i:s");
                        $reward_ids = array_column($subscription_details['available_rewards']['records'], 'id');
                        if(count($reward_ids) > 0){
                            $marked = $this->User_Model->mark_rewards_as_received($reward_ids,$received_at);
                            if($marked){
                                $usr_id = $user_package['user_id'];
                                $ref = implode(",",$reward_ids);
                                $description = "Reward Income";
                                $txn_for="daily-reward-collection";
                                $usr_txn = $this->wallet_transaction("credit",$usr_id,$amount,$description,$ref,$txn_for);
                                if($usr_txn['success']){
                                    $this->session->set_userdata("message",['class'=>'success','msg'=>'Income Received']);
                                    
                                    $usr = $this->User_Model->get_user_by_id($usr_id);
                                    $first_upline = $this->User_Model->get_upline_commission_slab_by_type_id_and_upline_level(2,1); // package,1
                                    $second_upline = $this->User_Model->get_upline_commission_slab_by_type_id_and_upline_level(2,2); // package,2

                                    if($first_upline['status'] == 1){
                                        $first = $this->User_Model->get_user_by_referral_id($usr['upline']);
                                        if(!empty($first) && $first['status'] == 1){
                                            $first_commission = (($amount / 100) * $first_upline['commission']);
                                            $description = "Package Commission from your direct Downline ".$usr['phone_number'];
                                            $txn_for="reward-referral-commission-upline-1";
                                            $first_wallet = $this->wallet_transaction('credit',$first['id'],$first_commission,$description,$ref,$txn_for);
                                        
                                            // section for sencond upline commission
                                            if($second_upline['status'] == 1){
                                                $second = $this->User_Model->get_user_by_referral_id($first['upline']);
                                                if(!empty($second) && $second['status'] == 1){
                                                    $second_commission = (($amount / 100) * $second_upline['commission']);
                                                    $description = "Package Commission from your Downline ".$first['phone_number'].' of Downline '.$usr['phone_number'];
                                                    $txn_for="reward-referral-commission-upline-2";
                                                    $second_wallet = $this->wallet_transaction('credit',$second['id'],$second_commission,$description,$ref,$txn_for);
                                                }
                                            }
                                        }
                                    }
                                    
                                }else{
                                    $this->session->set_userdata("message",['class'=>'danger','msg'=>'Error in inserting amount in your wallet']);
                                }
                            }else{
                                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Error in marking rewards as received']);
                            }
                        }else{
                            $this->session->set_userdata("message",['class'=>'danger','msg'=>'No data found']);
                        }
                    }
                    // else{
                    //     $this->session->set_userdata("message",['class'=>'danger','msg'=>'Invalid Amount']);
                    // }

                    $expired = $this->User_Model->check_if_subscription_expired($user_package['id']);
                    if(!empty($expired)){
                        $this->db->where("id",$expired['id'])->update("user_package",['status'=>0]);
                        $description = 'Returned Principal amount on package expiry against Transaction Id '.$expired['txn_id'];
                        $ref = $expired['id'];
                        $txn_for = "package-subscription-expired";
                        $txn = $this->wallet_transaction('credit',$expired['user_id'],$expired['amount'],$description,$ref,$txn_for);
                    }
                }else{
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>'Wait for the Button Income Receive to be enabled']);
                }
            }else{
                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Subscription not found']);
            }

            redirect(base_url('trade'));
        }
        
    }

    public function password_change(){
        $this->check_login();
        $user = $this->get_user();
        $user_id=$user['id'];

        $this->form_validation->set_rules('old_password','Old Password','trim|required|xss_clean');
        $this->form_validation->set_rules('new_password','New Password','trim|required|xss_clean');
        $this->form_validation->set_rules('c_password','Confirm Password','trim|required|matches[new_password]|xss_clean');

        if($this->form_validation->run() == FALSE){
            $this->load->view("user/my/password_change");
        }else{
            $controls = $this->input->post();
            if($user['password'] == md5($controls['old_password'])){
                $updated = $this->User_Model->password_change($controls,$user_id);
                if($updated){
                    $this->session->set_userdata("message",['class'=>'success','msg'=>'Password Changed']);
                }else{
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>'Something Went Wrong..']);
                }
            }else{
                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Old Password Does Not Match']);
            }

            redirect(base_url('my/password-change'));
        }
    }

    public function wallet_address(){
        $this->check_login();
        $user = $this->get_user();
        $user_id = $user['id'];  
        $data['user'] = $user;     

        $this->form_validation->set_message('is_unique', 'This  %s already used must contain a unique address.');
        $this->form_validation->set_rules("wallet_address","Wallet Address","trim|is_unique[user.crypto_address]|required|xss_clean",'');
        

        if($this->form_validation->run() == FALSE){
            $this->load->view("user/my/wallet_address",$data);
        }else{
            if(is_null($user['crypto_address'])){
                $controls = $this->input->post();
                $update = $this->User_Model->update_wallet_address($controls,$user_id);
                if($update){
                    $this->session->set_userdata("message",['class'=>'success','msg'=>'Wallet Address Updated Successfully.']);
                }else{
                    $this->session->set_userdata('message',['class'=>'danger','msg'=>'Something Went Wrong..']);
                }
            }else{
                $this->session->set_userdata('message',['class'=>'danger','msg'=>'You cant update address again. Please Contact with support.']);
            }
            
            redirect(base_url("my/wallet-address"));
        }
    }

    public function share(){
        $this->check_login();
        $user = $this->get_user();
        $data['user'] = $user;
        $this->load->view("user/share/share",$data);
    }

    public function withdraw_history(){
        $this->check_login();
        $user = $this->get_user();
        $user_id = $user['id'];
        $data['user'] = $user;
        $data['txn_history'] = $this->User_Model->get_user_withdraw_history($user_id);
        $data['total_withdraw_success'] = $this->User_Model->count_user_total_withdraw_by_status($user_id,"withdraw-success");
        $data['total_withdraw_pending'] = $this->User_Model->count_user_total_withdraw_by_status($user_id,"withdraw-pending");
        $this->load->view("user/my/withdraw-history",$data);
    }
}
?>