<?php
class Auth extends CI_Controller {
    
    
	public function __Construct()
	{
		parent::__construct();
		$this->load->model('Auth_Model');
		$this->form_validation->set_error_delimiters('<div class="text-danger" style="font-size:14px;font-style: italic;margin-top:5px;margin-bottom:20px;">', '</div>');
        date_default_timezone_set("Asia/Karachi");
    }

    public function page_not_found(){
        $this->load->view('page_not_found');
    }

    public function check_login(){
        if($this->session->userdata("user-login")){
            redirect(base_url());
        }
    }

    public function check_admin_login(){
        if($this->session->userdata("admin-login")){
            redirect(base_url('admin'));
        }
    }

    public function login(){
        $this->check_login();
        $this->form_validation->set_rules('phone_number','Phone Number','trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('password','Password','trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
            $data['default_referral_id'] = $this->Auth_Model->get_setting_value_by_key('default_referral_id');
			$this->load->view('login',$data);
		}else{
            $controls = $this->input->post();
            $user =  $this->Auth_Model->get_user_login($controls);
            if(!empty($user)){
                if($user['status'] != 2){
                    $ip = $this->input->ip_address();
                    $this->db->where("id",$user['id'])->update("user",["ip_address"=>$ip]);
                    $this->session->set_userdata("user-login",$user);
                    redirect(base_url());
                }else{
                    $this->session->set_userdata("message",['class'=>'error','msg'=>'You are account is suspended.']);
                    redirect(base_url("login"));
                }
            }else{
                $this->session->set_userdata("message",['class'=>'error','msg'=>'Invalid phone number or password']);
                redirect(base_url("login"));
            }
		}
    }

    public function logout(){
        $this->session->unset_userdata("user-login");
        redirect(base_url('login'));
    }

    public function join($referral){
        $this->check_login();
        $this->form_validation->set_rules('referral_id','Referral ID','trim|required|xss_clean');
        $this->form_validation->set_rules('name','Name','trim|required|xss_clean');
        $this->form_validation->set_rules('phone_number','Phone Number','trim|is_unique[user.phone_number]|required|numeric|xss_clean');
		$this->form_validation->set_rules('password','Password','trim|required|xss_clean');
        $this->form_validation->set_rules('c_password','Confirm Password','trim|required|matches[password]|xss_clean');
		if ($this->form_validation->run() == FALSE) {
            $data['referral'] = $referral;
			$this->load->view('register',$data);
		}else{
            $upline_user = $this->Auth_Model->get_user_by_referral_id($referral);
            if(!empty($upline_user)){
                $controls = $this->input->post();
                $inserted =  $this->Auth_Model->store_user($controls,$referral);
                if($inserted['success']){
                    $user = $inserted['user'];
                    $this->session->set_userdata("user-login",$user);
                    redirect(base_url());
                }else{
                    $this->session->set_userdata("message",['class'=>'error','msg'=>'Something went wrong..']);
                    redirect(base_url("join/".$referral));
                }
            }else{
                $this->session->set_userdata("message",['class'=>'error','msg'=>'Ivalid Referral ID provided']);
                redirect(base_url('join/'.$referral));
            }
		}
    }


    public function admin_logout(){
        $this->session->unset_userdata("admin-login");
        redirect(base_url('admin/login'));
    }

    public function admin_login(){
        $this->check_admin_login();
        $this->form_validation->set_rules('email','email','trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password','Password','trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('admin/login');
		}else{
            $controls = $this->input->post();
            $user =  $this->Auth_Model->get_admin_login($controls);
            if(!empty($user)){
                $this->session->set_userdata("admin-login",$user);
                redirect(base_url("admin"));
            }else{
                $this->session->set_userdata("message",['class'=>'error','msg'=>'Invalid email or password']);
                redirect(base_url("admin/login"));
            }
		}
    }

    public function download($filename = NULL) {
        // load download helder
        $this->load->helper('download');
        // read file contents
        $data = file_get_contents(base_url('/assets/apk/app-release.apk'));
        force_download($filename, $data);
    }

    public function download_apk(){
        $this->download('GodzillaMoney.apk');
    }
}
?>