<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Model extends CI_Model{

	public function __construct(){
		// Call the Model constructor
		parent::__construct();
		date_default_timezone_set("Asia/Karachi");
	}

	public function get_levels(){
		return $this->db->order_by("id","ASC")->get("level")->result_array();
	}

	public function add_package($controls){
		$package_data = [
			'level_id'              => $controls['level_id'],
			'name'                  => $controls['package_name'],
			'amount'                => $controls['amount'],
			'profit'                => $controls['profit_ratio'],
			'duration'              => $controls['duration_in_days'],
			'is_deleted'            => 0,  
		];
		return $this->db->insert('package',$package_data);

	}


	public function get_recharge_requests(){
		$this->db->select("u.*,dr.id as request_id,dr.amount,dr.usdt,dr.proof_img,dr.binance_txn_id,dr.created as request_created, dr.status as request_status");
		$this->db->from("deposit_request dr");
		$this->db->join("user u","u.id = dr.user_id");
		$this->db->where(['dr.status'=>0]);
		return $this->db->get()->result_array();
	}

	public function update_payment_req_status($req_id,$status){
		return $this->db->where("id",$req_id)->update("deposit_request",['status'=>$status]);
	}

	public function get_payment_request_by_id($id){
		return $this->db->where("id",$id)->get("deposit_request")->row_array();
	}

	public function get_user_by_id($id){
		return $this->db->where(['id'=>$id])->get("user")->row_array();
	}


	public function insert_user_wallet_transaction($user_id,$credit,$debit,$op_bal,$c_bal,$description,$ref,$txn_for){
		$data = [
			"txn_id"=>"",
			"user_id"=>$user_id,
			"credit"=>$credit,
			"debit"=>$debit,
			"op_bal"=>$op_bal,
			"c_bal"=>$c_bal,
			"description"=>$description,
			"reference"=>$ref,
			"txn_for"=>$txn_for,
			"created"=>date("Y-m-d H:i:s")
		];
		$inserted = $this->db->insert("wallet_transaction",$data);
		if($inserted){
			$id = $this->db->insert_id();
			$txn_id = "GZM00".$id;
			$updated_txn = $this->db->where("id",$id)->update("wallet_transaction",['txn_id'=>$txn_id]);	
			if($updated_txn){
				$wallet_updated = $this->db->where("id",$user_id)->update("user",['wallet'=>$c_bal]);
				if($wallet_updated){
					$transaction = $this->db->where("id",$id)->get("wallet_transaction")->row_array();
					$response['success'] = true;
					$response['msg'] = 'Transaction Successful';
					$response['txn'] = $transaction;
				}else{
					$response['success'] = false;
					$response['msg'] = 'Error in updating user wallet';	
				}
			}else{
				$response['success'] = false;
				$response['msg'] = 'Error in updating transaction';	
			}	
		}else{
			$response['success'] = false;
			$response['msg'] = 'Error in inserting transaction';
		}

		return $response;

	}

	public function get_upline_commission_slab_by_type_id_and_upline_level($type_id,$level){
		return $this->db->where(["commission_type_id"=>$type_id,'upline_level'=>$level])->get("commission_slab")->row_array();
	}

	public function get_user_by_referral_id($refferal_id){
		return $this->db->where(["referral_id"=>$refferal_id])->get("user")->row_array();
	}

	public function get_package_list_by_level(){
		$response = array();
		$levels = $this->get_levels();
		if(!empty($levels)): foreach($levels as $k=>$v):
			$response[$v['id']] = $this->db->where(["is_deleted"=>0,'level_id'=>$v['id']])->get("package")->result_array();
		endforeach; endif;

		return $response;
		
	}

	public function admin_profile_update($control,$profile_id){
		$profile_data =	[
			"name" 			=>	$control['name'],
			"email"			=>	$control['email'],
		];
		$this->db->where("id",$profile_id);
		return 	$this->db->update("admin",$profile_data);
	}

	public function change_password($post_data,$admin_id){
		$data = [
			"password"		=> md5($post_data['newpass']),
			"password_text" => $post_data['newpass'],
		];

		$this->db->where('id',$admin_id);
		return $this->db->update("admin",$data);
	}

	public function get_commission_slab_by_commission_type_id_and_upline_level_id($commission_type_id,$upline_level_id){
		return $this->db->where(['commission_type_id'=>$commission_type_id,'upline_level'=>$upline_level_id])->get("commission_slab")->row_array();	
	}

	public function get_setting_value_by_key($key){
		return $this->db->where("setting_key",$key)->get("setting")->row_array()['setting_value'];
  	}

	public function get_withdraw_requests(){
		$this->db->select("u.*,wr.id as request_id,wr.txn_id,wr.amount,wr.charges,wr.transfer_amount,wr.created as request_created, wr.status as request_status");
		$this->db->from("withdraw_request wr");
		$this->db->join("user u","u.id = wr.user_id");
		$this->db->where(['wr.status'=>0]);
		return $this->db->get()->result_array();
	}

	public function update_withdraw_req_status($req_id,$status){
		return $this->db->where("id",$req_id)->update("withdraw_request",['updated'=>date("Y-m-d H:i:s"),'status'=>$status]);
	}

	public function get_users(){
		$phone_number = $this->input->get("user-mobile");
		$this->db->select("*");
		$this->db->from("user");
		if($phone_number){
			$this->db->where("phone_number",$phone_number);
		}
		$this->db->order_by("id","desc");
		return $this->db->get()->result_array();
	}

	public function get_user_total_withdraw($user_id){
		$amount =  $this->db->select("sum(debit) as amount")->where(["user_id"=>$user_id,'txn_for'=>'withdraw-success'])->get("wallet_transaction")->row_array()['amount'];
		return !empty($amount) && $amount > 0 ? $amount : 0;
	}

	public function get_user_total_recharge($user_id){
		$amount =  $this->db->select("sum(credit) as amount")->where(["user_id"=>$user_id,'txn_for'=>'wallet-recharge'])->get("wallet_transaction")->row_array()['amount'];
		return !empty($amount) && $amount > 0 ? $amount : 0;
	}

	public function count_user_total_income($user_id){
		$txn_for = [
			"wallet-recharge-referral-commission-upline-1",
			"wallet-recharge-referral-commission-upline-2",
			"reward-referral-commission-upline-1",
			"reward-referral-commission-upline-2",
			"daily-reward-collection"
		];
		$this->db->select("sum(credit) as income");
		$this->db->from("wallet_transaction");
		$this->db->where_in("txn_for",$txn_for);
		$this->db->where("user_id",$user_id);

		$result = $this->db->get()->row_array();

		$income = (!empty($result['income']) && $result['income'] > 0 ) ? $result['income'] : 0;

		return $income;
  	}

	public function get_withdraw_history(){
		$from_date = $this->input->get("from-date");
		$to_date = $this->input->get("to-date");
		$mobile = $this->input->get("mobile");
		$status = $this->input->get("status");

		$this->db->select("w.*,u.name,u.phone_number");
		$this->db->from("withdraw_request w");
		$this->db->join("user u","u.id = w.user_id");
		if($from_date != ''){
			$from = date("Y-m-d 00:00:00",strtotime($from_date));
			$this->db->where("w.created >=",$from);
		}
		if($to_date != ''){
			$to = date("Y-m-d 23:59:59",strtotime($to_date));
			$this->db->where("w.created <=",$to);
		}
		if($mobile != ''){
			$this->db->where("u.phone_number",$mobile);
		}
		if($status != '' && $status != 0){
			$status = $status - 1;
			$this->db->where("w.status",$status);
		}
		return $this->db->get()->result_array();
	}

	public function get_recharge_history(){
		$from_date = $this->input->get("from-date");
		$to_date = $this->input->get("to-date");
		$mobile = $this->input->get("mobile");
		$status = $this->input->get("status");

		$this->db->select("r.*,u.name,u.phone_number");
		$this->db->from("deposit_request r");
		$this->db->join("user u","u.id = r.user_id");
		if($from_date != ''){
			$from = date("Y-m-d 00:00:00",strtotime($from_date));
			$this->db->where("r.created >=",$from);
		}
		if($to_date != ''){
			$to = date("Y-m-d 23:59:59",strtotime($to_date));
			$this->db->where("r.created <=",$to);
		}
		if($mobile != ''){
			$this->db->where("u.phone_number",$mobile);
		}
		if($status != '' && $status != 0){
			$status = $status - 1;
			$this->db->where("r.status",$status);
		}
		return $this->db->get()->result_array();
	}

}
