<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_Model extends CI_Model{

	public function __construct(){
		// Call the Model constructor
		parent::__construct();
		date_default_timezone_set("Asia/Karachi");
	}

    public function get_user_login($data){
        return $this->db->where(['phone_number'=>$data['phone_number'],'password'=>md5($data['password'])])->get("user")->row_array();
    }

	public function get_user_by_referral_id($referral_id){
		return $this->db->where(['referral_id'=>$referral_id])->get("user")->row_array();
	}

	public function store_user($post_data,$referral_id){
		$self_ref_id = '';
		$data = [
			'referral_id'=>$self_ref_id,
			'name'=>$post_data['name'],
			'phone_number'=>$post_data['phone_number'],
			'wallet'=>0,
			'crypto_address'=>null,
			'upline'=>$referral_id,
			'level_id'=>1,
			'password'=>md5($post_data['password']),
			'password_text'=>$post_data['password'],
			'created'=>date('Y-m-d H:i:S',time()),
			'status'=>0			
		];

		$inserted = $this->db->insert('user',$data);
		if($inserted){
			$id = $this->db->insert_id();
			$self_ref_id = time().$id;
			$this->db->where("id",$id)->update("user",["referral_id"=>$self_ref_id]);
			$user = $this->db->where(['id'=>$id])->get("user")->row_array();
			return array("success"=>true,'user'=>$user);
		}else{
			return array('success'=>false,'user'=>null);
		}

	}

	public function get_admin_login($data){
        return $this->db->where(['email'=>$data['email'],'password'=>md5($data['password'])])->get("admin")->row_array();
    }

	public function get_setting_value_by_key($key){
		return $this->db->where("setting_key",$key)->get("setting")->row_array()['setting_value'];
  	}

}
