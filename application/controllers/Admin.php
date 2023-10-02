<?php
class Admin extends CI_Controller {
    
	public function __Construct()
	{
		parent::__construct();
		$this->load->model('Admin_Model');
		$this->form_validation->set_error_delimiters('<div class="text-danger" style="font-size:14px;font-style: italic;margin-top:5px;margin-bottom:20px;">', '</div>');
        date_default_timezone_set("Asia/Karachi");
    }

    public function check_login(){
        if($this->session->userdata("admin-login") == null){
            redirect(base_url('admin/login'));
        }
    }

    public function get_admin(){
        $admin = $this->session->userdata("admin-login");
        return $this->db->where(['id'=>$admin['id']])->get("admin")->row_array();
    }

    public function check_perm($role_id){
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method();

        $route = $class . '/' . $method;

        $perm = $this->db->where(['role_id'=>$role_id,'permission'=>$route])->get("role_permission")->row_array();
        if(empty($perm)){
            redirect(base_url("admin"));
        }
    }

    public function index(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);
        $data['admin'] = $admin;

        if($admin['role_id'] == 1){
            $total_users = $this->db->select("count(id) as total")->where("status !=",2)->get("user")->row_array();
            $data['total_users'] = !empty($total_users['total']) ? $total_users['total'] : 0;

            $total_recharge = $this->db->select("sum(credit) as total")->where(["txn_for"=>'wallet-recharge'])->get("wallet_transaction")->row_array();
            $data['total_recharge'] = !empty($total_recharge['total']) ? $total_recharge['total'] : 0;

            $total_withdraw = $this->db->select("sum(debit) as total")->where(["txn_for"=>'withdraw-success'])->get("wallet_transaction")->row_array();
            $data['total_withdraw'] = !empty($total_withdraw['total']) ? $total_withdraw['total'] : 0;

            $today_total_users = $this->db->select("count(id) as total")->where(["status !="=>2,"created >="=>date("Y-m-d 00:00:01"),'created <='=>date("Y-m-d 23:59:59")])->get("user")->row_array();
            $data['today_total_users'] = !empty($today_total_users['total']) ? $today_total_users['total'] : 0;

            $today_total_recharge = $this->db->select("sum(credit) as total")->where(["txn_for"=>'wallet-recharge',"created >="=>date("Y-m-d 00:00:01"),'created <='=>date("Y-m-d 23:59:59")])->get("wallet_transaction")->row_array();
            $data['today_total_recharge'] = !empty($today_total_recharge['total']) ? $today_total_recharge['total'] : 0;

            $today_total_withdraw = $this->db->select("sum(debit) as total")->where(["txn_for"=>'withdraw-success',"created >="=>date("Y-m-d 00:00:01"),'created <='=>date("Y-m-d 23:59:59")])->get("wallet_transaction")->row_array();
            $data['today_total_withdraw'] = !empty($today_total_withdraw['total']) ? $today_total_withdraw['total'] : 0;
            // echo "<pre>"; print_r($data); die;
            $this->load->view("admin/dashboard",$data);
        }elseif($admin['role_id'] == 2){
            $this->load->view("admin/recharge_dashboard",$data);
        }elseif($admin['role_id'] == 3){
            $this->load->view("admin/withdraw_dashboard",$data);
        }
    }

    public function add_package(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $data['levels'] = $this->Admin_Model->get_levels();

        $this->form_validation->set_rules('level_id','Level','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('package_name','Package Name','trim|required|xss_clean');
        $this->form_validation->set_rules('amount','Amount','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('profit_ratio','Profit Ratio','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('duration_in_days','Duration In Days','trim|required|numeric|xss_clean');

        if($this->form_validation->run() == FALSE){
            $this->load->view("admin/package/add_package",$data);
        }else{
            $controls = $this->input->post();
            $inserted = $this->Admin_Model->add_package($controls);

            if($inserted){
                $this->session->set_userdata('message',['class'=>'success','msg'=>"Package addedd successfully"]);
            }else{
                $this->session->set_userdata('message',['class'=>'danger','msg'=>"Something went wrong..."]);
            }	
            redirect(base_url('admin/add-package'));
        }
    }

    public function package_list(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $data['level_packages_list'] = $this->Admin_Model->get_package_list_by_level();
        $this->load->view("admin/package/package_list",$data);
    }

    public function delete_package($id){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $updated = $this->db->where(['id'=>$id])->update("package",['is_deleted'=>1]);
        if($updated){
            $this->session->set_userdata("message",['class'=>'success','msg'=>'Package Deleted']);
        }else{
            $this->session->set_userdata("message",['class'=>'danger','msg'=>'Error in deleting package']);
        }

        redirect(base_url("admin/package-list"));
    }


    public function recharge_requests(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $data['recharge_requests'] = $this->Admin_Model->get_recharge_requests();
        $this->load->view("admin/recharge/requests",$data);
    }

    public function wallet_transaction($type,$user_id,$amount,$description,$ref,$txn_for){
        $user = $this->Admin_Model->get_user_by_id($user_id);
        if(!empty($user)){
            $op_bal = $user['wallet'];
            if($type == 'credit'){
                $c_bal = $op_bal + $amount;
                $credit = $amount;
                $debit = 0;
                $txn = $this->Admin_Model->insert_user_wallet_transaction($user_id,$credit,$debit,$op_bal,$c_bal,$description,$ref,$txn_for);
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
                $txn = $this->Admin_Model->insert_user_wallet_transaction($user_id,$credit,$debit,$op_bal,$c_bal,$description,$ref,$txn_for);
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

    public function payment_req_response($req_id,$status){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        if($status == 2){
            $updated = $this->Admin_Model->update_payment_req_status($req_id,$status);
            if($updated){
                $this->session->set_userdata("message",['class'=>'success','msg'=>'Request Rejected.']);
            }else{
                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Something went wrong.']);
            }
        }else{
            $request = $this->Admin_Model->get_payment_request_by_id($req_id);
            if(!empty($request)){
                $user_id = $request['user_id'];
                $amount = $request['amount'];
                $description = 'Wallet Recharge';
                $ref = $req_id;
                $txn_for = "wallet-recharge";
                $recharge = $this->wallet_transaction('credit',$user_id,$amount,$description,$ref,$txn_for);
                if($recharge['success']){
                    $this->Admin_Model->update_payment_req_status($req_id,$status);
                    $this->db->where("id",$user_id)->update("user",['status'=>1]); // activated user
                    $this->session->set_userdata("message",['class'=>'success','msg'=>'Request Accepted']);

                    $user = $this->Admin_Model->get_user_by_id($user_id);
                    $self_comm = $this->Admin_Model->get_upline_commission_slab_by_type_id_and_upline_level(1,0); // recharge,0 // self
                    $first_upline = $this->Admin_Model->get_upline_commission_slab_by_type_id_and_upline_level(1,1); // recharge,1
                    $second_upline = $this->Admin_Model->get_upline_commission_slab_by_type_id_and_upline_level(1,2); // recharge,2

                    if($self_comm['status'] == 1){
                        $self_commission = (($amount / 100) * $self_comm['commission']);
                        $description = "Recharge Self Commission";
                        $txn_for = "wallet-recharge-referral-commission-self";
                        $first_wallet = $this->wallet_transaction('credit',$user_id,$self_commission,$description,$ref,$txn_for);
                    }
                    
                    if($first_upline['status'] == 1){
                        $first = $this->Admin_Model->get_user_by_referral_id($user['upline']);
                        if($first['status'] == 1){
                            $first_commission = (($amount / 100) * $first_upline['commission']);
                            $description = "Recharge Commission from your direct Downline ".$user['phone_number'];
                            $txn_for = "wallet-recharge-referral-commission-upline-1";
                            $first_wallet = $this->wallet_transaction('credit',$first['id'],$first_commission,$description,$ref,$txn_for);
                        
                            // section for sencond upline commission
                            if($second_upline['status'] == 1){
                                $second = $this->Admin_Model->get_user_by_referral_id($first['upline']);
                                if($second['status'] == 1){
                                    $second_commission = (($amount / 100) * $second_upline['commission']);
                                    $description = "Recharge Commission from your Downline ".$first['phone_number'].' of Donline '.$user['phone_number'];
                                    $txn_for = "wallet-recharge-referral-commission-upline-2";
                                    $second_wallet = $this->wallet_transaction('credit',$second['id'],$second_commission,$description,$ref,$txn_for);
                                }
                            }
                        }
                    }
                }else{
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>$recharge['msg']]);    
                }
            }else{
                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Request not found']);
            }

        }

        redirect(base_url('admin/recharge-requests'));
    }

    public function admin_profile(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);
     
        $profile_data["profile"] = $admin;
        $profile_id = $admin['id'];
        
        $this->form_validation->set_rules('name','name','trim|required|xss_clean');
        $this->form_validation->set_rules('email','email','trim|required|valid_email|xss_clean');

        if($this->form_validation->run() == FALSE){
            $this->load->view("admin/admin_profile.php",$profile_data);
        }else{
            $control            = $this->input->post();
            $updated_profile    = $this->Admin_Model->admin_profile_update($control,$profile_id);
            
            if($updated_profile){
                $this->session->set_userdata('message',['class'=>'success','msg'=>'Update Profile Successfully']);
            }else{
                $this->session->set_userdata('message',['class'=>'danger','msg'=>'Something went Wrong..']);
            }
            redirect(base_url("admin/profile"));
        } 
    }

    public function change_password(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $admin_id = $admin['id'];
        
        $this->form_validation->set_rules('oldpass','Old Password','trim|required|xss_clean');
        $this->form_validation->set_rules('newpass','New Password','trim|required|xss_clean');
        $this->form_validation->set_rules('passconf','Confirm New Password','trim|required|matches[newpass]|xss_clean');

        if($this->form_validation->run() == FALSE){
            $this->load->view("admin/change_password");
        }else{
            $controls           = $this->input->post();
            if($admin['password'] == md5($controls['oldpass'])){

                $updated        = $this->Admin_Model->change_password($controls,$admin_id);
                if($updated){
                    $this->session->set_userdata('message',['class'=>'success','msg'=>'Password Changed']);
                }else{
                    $this->session->set_userdata('message',['class'=>'danger','msg'=>'Something went Wrong..']);
                }

            }else{
                $this->session->set_userdata('message',['class'=>'danger','msg'=>'Invalid Old Password']);
            }
            
            redirect(base_url("admin/change-password"));
        } 
    }

    public function commission_slab(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $data['referral']['self'] = $this->Admin_Model->get_commission_slab_by_commission_type_id_and_upline_level_id(1,0);
        $data['referral']['level_1'] = $this->Admin_Model->get_commission_slab_by_commission_type_id_and_upline_level_id(1,1);
        $data['referral']['level_2'] = $this->Admin_Model->get_commission_slab_by_commission_type_id_and_upline_level_id(1,2);

        $data['package']['level_1'] = $this->Admin_Model->get_commission_slab_by_commission_type_id_and_upline_level_id(2,1);
        $data['package']['level_2'] = $this->Admin_Model->get_commission_slab_by_commission_type_id_and_upline_level_id(2,2);
        
        $this->form_validation->set_rules('referral_level_0_commission','Self Commission','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('referral_level_1_commission','Upline 1 Commission','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('referral_level_2_commission','Upline 2 Commission','trim|required|numeric|xss_clean');

        $this->form_validation->set_rules('package_level_1_commission','Upline 1 Commission','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('package_level_2_commission','Upline 2 Commission','trim|required|numeric|xss_clean');

        if($this->form_validation->run() == FALSE){
            $this->load->view("admin/setting/commission_slab",$data);
        }else{
            $controls = $this->input->post();
            $this->db->where("id",5)->update("commission_slab",['commission'=>$controls['referral_level_0_commission'],'status'=>$controls['referral_level_0_status']]);
            $this->db->where("id",1)->update("commission_slab",['commission'=>$controls['referral_level_1_commission'],'status'=>$controls['referral_level_1_status']]);
            $this->db->where("id",2)->update("commission_slab",['commission'=>$controls['referral_level_2_commission'],'status'=>$controls['referral_level_2_status']]);

            $this->db->where("id",3)->update("commission_slab",['commission'=>$controls['package_level_1_commission'],'status'=>$controls['package_level_1_status']]);
            $this->db->where("id",4)->update("commission_slab",['commission'=>$controls['package_level_2_commission'],'status'=>$controls['package_level_2_status']]);
            
            $this->session->set_userdata("message",['class'=>'success','msg'=>'Commission Slab Updated']);

            redirect(base_url("admin/commission-slab"));
        }
        
    }

    public function site_setting(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $data['usdt_pkr_rate'] = $this->Admin_Model->get_setting_value_by_key("usdt_pkr_rate");
        $data['crypto_address'] = $this->Admin_Model->get_setting_value_by_key("crypto_address");
        $data['crypto_address_image'] = $this->Admin_Model->get_setting_value_by_key("crypto_address_image");
        $data['recharge_min_amount'] = $this->Admin_Model->get_setting_value_by_key("recharge_min_amount");
        $data['withdraw_min_amount'] = $this->Admin_Model->get_setting_value_by_key("withdraw_min_amount");
        $data['withdraw_charges'] = $this->Admin_Model->get_setting_value_by_key("withdraw_charges");
        $data['support_whatsapp'] = $this->Admin_Model->get_setting_value_by_key("support_whatsapp");
        
        $data['withdraw_from'] = $this->Admin_Model->get_setting_value_by_key("withdraw_from");
        $data['withdraw_to'] = $this->Admin_Model->get_setting_value_by_key("withdraw_to");

        $data['home_popup_title'] = $this->Admin_Model->get_setting_value_by_key("home_popup_title");
        $data['home_popup_content'] = $this->Admin_Model->get_setting_value_by_key("home_popup_content");
        $data['home_popup_status'] = $this->Admin_Model->get_setting_value_by_key("home_popup_status");
        

        $this->form_validation->set_rules('usdt_pkr_rate','USDT PKR Exchange Rate','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('crypto_address','Crypto Address','trim|required|xss_clean');
        $this->form_validation->set_rules('recharge_min_amount','Minimum Recharge','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('withdraw_min_amount','Minimum Withdraw','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('withdraw_charges','Withdraw Charges','trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('withdraw_from','Withdraw From','trim|required|xss_clean');
        $this->form_validation->set_rules('withdraw_to','Withdraw To','trim|required|xss_clean');
        $this->form_validation->set_rules('support_whatsapp','Support Whatsapp','trim|required|xss_clean');

        $this->form_validation->set_rules('home_popup_title','Home Popup Title','trim|required|xss_clean');
        $this->form_validation->set_rules('home_popup_content','Home Popup Content','trim|required|xss_clean');
        $this->form_validation->set_rules('home_popup_status','Home Popup Status','trim|required|xss_clean');
        if($this->form_validation->run() == FALSE){
            $this->load->view("admin/setting/site-setting",$data);
        }else{
            $controls = $this->input->post();
            if(!empty($_FILES['crypto_address_image']['name'])){
                $uploaded = $this->do_upload('crypto_address_image','admin/cypto_qr_image/','gif|jpg|jpeg|png|svg');
                if($uploaded['upload_data'] == ''){
                    $this->session->set_userdata("crypto_address_image_Err",$uploaded['error']);
                    redirect(base_url('admin/site-setting'));
                }

                $file_name = $uploaded['upload_data']['file_name'];
                $this->db->where(['setting_key'=>'crypto_address_image'])->update("setting",['setting_value'=>$file_name]);                
            }
            
            foreach ($controls as $k=>$v){
                $this->db->where(["setting_key"=>$k])->update("setting",["setting_value"=>$v]);
            }

            $this->session->set_userdata("message",["class"=>"success","msg"=>"Setting Updated"]);
            redirect(base_url('admin/site-setting'));
        }
        
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

    public function withdraw_requests(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $data['withdraw_requests'] = $this->Admin_Model->get_withdraw_requests();
        // echo "<pre>"; print_r($data); die;
        $this->load->view("admin/withdraw/requests",$data);
    }

    public function withdraw_req_response($req_id,$status){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $request = $this->db->where("id",$req_id)->get("withdraw_request")->row_array();
        if(!empty($request)){
            if($status == 1){
                $req_updated = $this->Admin_Model->update_withdraw_req_status($req_id,$status);
                if($req_updated){
                    $txn_updated = $this->db->where("id",$request['txn_id'])->update("wallet_transaction",["txn_for"=>'withdraw-success','description'=>'Withdraw (Success)']);
                    if($txn_updated){
                        $this->session->set_userdata("message",['class'=>'success','msg'=>'Request Accepted.']);
                    }else{
                        $this->session->set_userdata("message",['class'=>'danger','msg'=>'Something went wrong with updating txn.']);
                    }
                }else{
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>'Something went wrong with updating request.']);
                }
            }else{
                $txn = $this->db->where("id",$request['txn_id'])->get("wallet_transaction")->row_array();
                $user_id = $request['user_id'];
                $amount = $request['amount'];
                $description = 'Amount returned against transaction id '.$txn['txn_id'];
                $ref = $req_id;
                $txn_for = "withdraw-returned";
                $transaction = $this->wallet_transaction('credit',$user_id,$amount,$description,$ref,$txn_for);
                if($transaction['success']){
                    $this->Admin_Model->update_withdraw_req_status($req_id,$status);
                    $this->db->where("id",$txn['id'])->update("wallet_transaction",['txn_for'=>'withdraw-rejected','description'=>'Withdraw (Rejected)']); // activated user
                    $this->session->set_userdata("message",['class'=>'success','msg'=>'Request Rejected']);
                }else{
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>$transaction['msg']]);    
                }
                
            }
        }else{
            $this->session->set_userdata("message",['class'=>'danger','msg'=>'Request not found']);
        }
        
        redirect(base_url('admin/withdraw-requests'));
    }

    public function user_list(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        $users = $this->Admin_Model->get_users();
        $data['users'] = $users;

        $this->load->view("admin/users/user-list",$data);
    }

    public function user_detail($user_id){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);

        if(isset($_GET['action']) && $_GET['action'] == 'reset'){
            $id = $user_id;
            $this->db->where("id",$id)->update("user",['crypto_address'=>null]);
            $this->session->set_userdata("message",["class"=>"success","msg"=>"Address Updated Successfully"]);
            redirect(base_url("admin/user-detail/".$id));
        }

        if(isset($_GET['action']) && $_GET['action'] == 'change-status'){
            $id = $user_id;
            $status = $_GET['status'];
            $this->db->where("id",$id)->update("user",['status'=>$status]);
            $this->session->set_userdata("message",["class"=>"success","msg"=>"User Status Updated"]);
            redirect(base_url("admin/user-detail/".$id));
        }

        $data['user'] = $this->Admin_Model->get_user_by_id($user_id);
        $data['user']['withdraw'] = $this->Admin_Model->get_user_total_withdraw($user_id);
        $data['user']['recharge'] = $this->Admin_Model->get_user_total_recharge($user_id);
        $data['user']['total_income'] = $this->Admin_Model->count_user_total_income($user_id);
        $this->load->view("admin/users/user-detail",$data);
    }

    public function withdraw_history(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);
        $data['history'] = $this->Admin_Model->get_withdraw_history();
       
        $this->load->view("admin/withdraw/history",$data);
    }

    public function recharge_history(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);
        $data['history'] = $this->Admin_Model->get_recharge_history();
        // echo "<pre>"; print_r($data['history']);die;
        $this->load->view("admin/recharge/history",$data);
    }

    public function home_slider_setting(){
        $this->check_login();
        $admin = $this->get_admin();

        if(isset($_GET['delete']) && !empty($_GET['delete'])){
            $this->db->where("id",$_GET['delete'])->delete("home_slider_images");
            $this->session->set_userdata("message",['class'=>'success','msg'=>'Image deleted']);
            redirect(base_url("admin/setting/home-slider"));
        }

        $this->check_perm($admin['role_id']);
        $data['admin'] = $admin;    
        $data['images'] = $this->db->get("home_slider_images")->result_array();

        $this->form_validation->set_rules('submit','Submit','trim|required|xss_clean');
        
        if($this->form_validation->run() == FALSE){
            $this->load->view("admin/setting/home_slider",$data);
        }else{
            if(!empty($_FILES['image']['name'])){
                $controls = $this->input->post();

                $uploaded = $this->do_upload('image','admin/home_slider/','gif|jpg|jpeg|png|svg');
                if($uploaded['upload_data'] == ''){
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>$uploaded['error']]);
                    redirect(base_url("admin/setting/home-slider"));
                }

                $file_name = $uploaded['upload_data']['file_name'];
                $inserted = $this->db->insert("home_slider_images",["image"=>$file_name]);
                if($inserted){
                    $this->session->set_userdata("message",['class'=>'success','msg'=>'Image Uploaded']);
                }else{
                    $this->session->set_userdata("message",['class'=>'danger','msg'=>'Something went wrong with uploading image']);
                }    
            }else{
                $this->session->set_userdata("message",['class'=>'danger','msg'=>'Image is required']);
            }
            redirect(base_url("admin/setting/home-slider"));
        }
    }

    public function level_setting(){
        $this->check_login();
        $admin = $this->get_admin();
        $this->check_perm($admin['role_id']);
        $data['admin'] = $admin;
        $levels = $this->db->order_by("id","asc")->get("level")->result_array();
        $data['levels'] = $levels;
       
        if(!empty($levels)): foreach($levels as $k=>$v):
        $this->form_validation->set_rules('level_limit['.$v['id'].']','Level '.$v['id'].' limit' , 'trim|required|numeric|xss_clean');
        endforeach; endif;
        
        if($this->form_validation->run() == FALSE){
            $this->load->view("admin/setting/level-setting",$data);
        }else{
            $controls = $this->input->post();
            foreach($controls['level_limit'] as $k=>$v){
                
                $this->db->where("id",$k)->update("level",['package_limit'=>$v]);
            }
        
            $this->session->set_userdata("message",['class'=>'success','msg'=>'Level Setting Updated']);
            redirect(base_url("admin/level-setting"));
        }
    }

}
?>