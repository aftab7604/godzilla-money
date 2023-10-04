<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob_Model extends CI_Model{

	public function __construct(){
		// Call the Model constructor
		parent::__construct();
		date_default_timezone_set("Asia/Karachi");
	}

	public function get_user_by_id($id){
		return $this->db->where(['id'=>$id])->get("user")->row_array();
	}

	public function get_user_by_referral_id($referral_id){
		return $this->db->where(['referral_id'=>$referral_id])->get("user")->row_array();
	}

	public function get_downline_by_referral_id($referral_id){
		return $this->db->where(['upline'=>$referral_id,'status'=>1])->get("user")->result_array();
	}

	public function check_if_user_has_subscription($user_id){
		$exist = $this->db->where(['user_id'=>$user_id])->get("user_package")->row_array();
		if(!empty($exist)){
			return true;
		}else{
			return false;
		}
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

	public function get_all_expired_active_subscriptions(){
		$this->db->select("s.*,txn.txn_id,txn.debit as amount");
		$this->db->from("user_package s");
		$this->db->join("wallet_transaction txn","txn.reference = s.id");
		$this->db->where("txn.txn_for","package-subscription");
		$this->db->where("s.expire <",date("Y-m-d H:i:s"));
		$this->db->where("s.status",1);
		return $this->db->get()->result_array();
	}
}
