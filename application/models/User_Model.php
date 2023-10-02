<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model{
	public $timeframe = "hour"; // this is for testing the time tasks, 
	// for test = minute
	// for production = hour

	public function __construct(){
		// Call the Model constructor
		parent::__construct();
		date_default_timezone_set("Asia/Karachi");
	}

	public function get_usdt_pkr_rate(){
		return $this->db->where(['setting_key'=>'usdt_pkr_rate'])->get('setting')->row_array()['setting_value'];
	}
	public function get_crypto_address_image(){
		return $this->db->where(['setting_key'=>'crypto_address_image'])->get('setting')->row_array()['setting_value'];
	}
	public function get_crypto_address(){
		return $this->db->where(['setting_key'=>'crypto_address'])->get('setting')->row_array()['setting_value'];
	}

	public function submit_recharge_request($user_id,$controls,$file_name){
		$data = [
			'user_id'=>$user_id,
			'amount'=>$controls['amount'],
			'usdt'=>$controls['usdt'],
			'proof_img'=>$file_name,
			'binance_txn_id'=>$controls['binance_txn_id'],
			'created'=>date("Y-m-d H:i:s"),
			'status'=>0,
		];

		return $this->db->insert("deposit_request",$data);
	}

	public function get_user_by_id($id){
		return $this->db->where(['id'=>$id])->get("user")->row_array();
	}

	public function get_available_packages_for_user($user_id){
		$user = $this->get_user_by_id($user_id);
		return $this->db->where(["is_deleted"=>0])->order_by("level_id","ASC")->get("package")->result_array();
	}

	public function get_package_by_id($id){
		return $this->db->where("id",$id)->get("package")->row_array();
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
    
	public function insert_user_package_purchase($user_id,$package_id,$count){
		$package = $this->db->where(["id"=>$package_id])->get("package")->row_array();
		$insert_time = date("Y-m-d H:i:s");
		$expire = date('Y-m-d H:i:s', strtotime($insert_time. ' +'.$package['duration'].' day'));
		$data = [
			"user_id"=>$user_id,
			"package_id"=>$package_id,
			"count"=>$count,
			"created"=>$insert_time,
			"expire"=>$expire,
			"status"=>1
		];

		$inserted = $this->db->insert("user_package",$data);
		if($inserted){
			$u_p_id = $this->db->insert_id();

			$days = $package['duration'];
			$profit_per_day = (($package['amount'] / 100) * $package['profit']);
			$profit_per_hour = $profit_per_day / 24;
			$hours = $days * 24;
			$daily_reward_data = [
				"user_package_id"=>$u_p_id,
				"amount"=>($profit_per_hour * $count),
				"due_at"=>$insert_time,
				"is_received"=>0,
				"received_at"=>null,
				"created"=>$insert_time
			];
			$this->db->insert("daily_reward",$daily_reward_data);

			for($i = 1; $i < $hours ; $i++){
				$time_each_hr = date("Y-m-d H:i:s",strtotime($insert_time." +".$i." ".$this->timeframe."")); 
				$daily_reward_data = [
					"user_package_id"=>$u_p_id,
					"amount"=>($profit_per_hour * $count),
					"due_at"=>$time_each_hr,
					"is_received"=>0,
					"received_at"=>null,
					"created"=>$insert_time
				];
				$this->db->insert("daily_reward",$daily_reward_data);
			}
			return $u_p_id;
		}else{
			return false;
		}
	}

	public function get_last_reward_recieved_by_user_package_id($up_id){
		return  $this->db->where(['user_package_id'=>$up_id,'is_received'=>1])->order_by("id","desc")->limit(1)->get("daily_reward")->row_array();
	}

	public function get_user_actve_subscribed_packages($user_id){
		$this->db->select("s.*,p.name,p.amount as 'package_price'");
		$this->db->from("user_package s");
		$this->db->join("package p","p.id = s.package_id");
		$this->db->join("user u","u.id = s.user_id");
		// $this->db->where("s.expire >=",date("Y-m-d H:i:s"));
		$this->db->where("s.status",1);
		$this->db->where("s.user_id",$user_id);
		$this->db->order_by("s.id","asc");
		$packages =  $this->db->get()->result_array();

		

		if(!empty($packages)): foreach($packages as $k=>$v):
			$last_reward = $this->get_last_reward_recieved_by_user_package_id($v['id']);
			if(!empty($last_reward)){
				$diff = $this->differenceInHours($last_reward['received_at'],date("Y-m-d H:i:s"));
				if($diff < 24){
					$from = date("Y-m-d H:i:s",strtotime($last_reward['due_at']." +1 ".$this->timeframe.""));
					$rewards = $this->get_available_rewards_by_user_package_id($v['id'],$from,date("Y-m-d H:i:s"));
				}else{
					$hrs = $diff % 24;
					$from = date("Y-m-d H:i:s",strtotime("-".$hrs." ".$this->timeframe.""));
					$rewards = $this->get_available_rewards_by_user_package_id($v['id'],$from,date("Y-m-d H:i:s"));
				}
			}else{
				$diff = $this->differenceInHours($v['created'],date("Y-m-d H:i:s"));
				if($diff < 24){
					$from = date("Y-m-d H:i:s",strtotime($v['created']));
					$rewards = $this->get_available_rewards_by_user_package_id($v['id'],$from,date("Y-m-d H:i:s"));
				}else{
					$hrs = $diff % 24;
					$from = date("Y-m-d H:i:s",strtotime("-".$hrs ." ".$this->timeframe.""));
					$rewards = $this->get_available_rewards_by_user_package_id($v['id'],$from,date("Y-m-d H:i:s"));
				}
				
			}

			$packages[$k]['available_rewards'] = $rewards;
			$accumulated= $this->db->select("sum(amount) as 'accumulated'")->from("daily_reward")->where(['user_package_id'=>$v['id'],'is_received'=>1])->get()->row_array()['accumulated'];
			$packages[$k]['accumulated'] = !empty($accumulated) ? $accumulated : 0;
			$estimated= $this->db->select("sum(amount) as 'estimated'")->from("daily_reward")->where(['user_package_id'=>$v['id']])->get()->row_array()['estimated'];
			$packages[$k]['estimated'] = !empty($estimated) ? $estimated : 0;
			$packages[$k]['total_hours'] = $this->differenceInHours($v['created'],date("Y-m-d H:i:s"));
			$last_received = $this->db->where(['user_package_id'=>$v['id'],'is_received'=>1])->order_by("id","desc")->limit(1)->get("daily_reward")->row_array();
			if(!empty($last_received)){
				$difference = $this->differenceInHours($last_received['received_at'],date("Y-m-d H:i:s"));
				if($difference >= 2){
					$packages[$k]['enable_receive'] = true;
				}else{
					$packages[$k]['enable_receive'] = false;
				}
			}else{
				$difference = $this->differenceInHours($v['created'],date("Y-m-d H:i:s"));
				if($difference >= 2){
					$packages[$k]['enable_receive'] = true;
				}else{
					$packages[$k]['enable_receive'] = false;
				}
			}
		endforeach; endif;

		return $packages;

	}

	public function get_available_rewards_by_user_package_id($up_id,$from,$to){
		$this->db->where(['user_package_id'=>$up_id,'due_at >='=>$from,'due_at <='=>$to,'is_received'=>0]);
		$rewards =  $this->db->get("daily_reward")->result_array();
		$this->db->select("sum(amount) as 'income'")->from("daily_reward");
		$this->db->where(['user_package_id'=>$up_id,'due_at >='=>$from,'due_at <='=>$to,'is_received'=>0]);
		$income = $this->db->get()->row_array()['income'];
		$data['records'] = $rewards;
		$data['income'] = $income;
		return $data;

	}

	function differenceInHours($startdate,$enddate){
		$starttimestamp = strtotime($startdate);
		$endtimestamp = strtotime($enddate);
		$difference = abs($endtimestamp - $starttimestamp)/3600;
		if($this->timeframe == 'minute'){
			return $difference * 60;
		}else{
			return $difference;
		}
		
	}

	public function get_user_package_rewards($up_id){
		$user_package = $this->db->where("id",$up_id)->get("user_package")->row_array();
		$last_reward = $this->get_last_reward_recieved_by_user_package_id($up_id);
		if(!empty($last_reward)){
			$diff = $this->differenceInHours($last_reward['received_at'],date("Y-m-d H:i:s"));
			if($diff < 24){
				$from = date("Y-m-d H:i:s",strtotime($last_reward['due_at']." +1 ".$this->timeframe.""));
				$rewards = $this->get_available_rewards_by_user_package_id($up_id,$from,date("Y-m-d H:i:s"));
			}else{
				$hrs = $diff % 24;
				$from = date("Y-m-d H:i:s",strtotime("-".$hrs." ".$this->timeframe.""));
				$rewards = $this->get_available_rewards_by_user_package_id($up_id,$from,date("Y-m-d H:i:s"));
			}
		}else{
			$diff = $this->differenceInHours($user_package['created'],date("Y-m-d H:i:s"));
			if($diff < 24){
				$from = date("Y-m-d H:i:s",strtotime($user_package['created']));
				$rewards = $this->get_available_rewards_by_user_package_id($up_id,$from,date("Y-m-d H:i:s"));
			}else{
				$hrs = $diff % 24;
				$from = date("Y-m-d H:i:s",strtotime("-".$hrs ." ".$this->timeframe.""));
				$rewards = $this->get_available_rewards_by_user_package_id($up_id,$from,date("Y-m-d H:i:s"));
			}
			
		}

		$data['available_rewards'] = $rewards;
		$accumulated= $this->db->select("sum(amount) as 'accumulated'")->from("daily_reward")->where(['user_package_id'=>$up_id,'is_received'=>1])->get()->row_array()['accumulated'];
		$data['accumulated'] = !empty($accumulated) ? $accumulated : 0;
		$estimated= $this->db->select("sum(amount) as 'estimated'")->from("daily_reward")->where(['user_package_id'=>$up_id])->get()->row_array()['estimated'];
		$data['estimated'] = !empty($estimated) ? $estimated : 0;
		$data['total_hours'] = $this->differenceInHours($user_package['created'],date("Y-m-d H:i:s"));
		$last_received = $this->db->where(['user_package_id'=>$up_id,'is_received'=>1])->order_by("id","desc")->limit(1)->get("daily_reward")->row_array();
		if(!empty($last_received)){
			$difference = $this->differenceInHours($last_received['received_at'],date("Y-m-d H:i:s"));
			if($difference >= 2){
				$data['enable_receive'] = true;
			}else{
				$data['enable_receive'] = false;
			}
		}else{
			$data['enable_receive'] = true;
		}

		return $data;
	}

	public function mark_rewards_as_received($reward_ids,$received_at){
		return $this->db->where_in("id",$reward_ids)->update("daily_reward",['is_received'=>1,"received_at"=>$received_at]);
	}

	public function get_upline_commission_slab_by_type_id_and_upline_level($type_id,$level){
		return $this->db->where(["commission_type_id"=>$type_id,'upline_level'=>$level])->get("commission_slab")->row_array();
	}

	public function get_user_by_referral_id($refferal_id){
		return $this->db->where(["referral_id"=>$refferal_id])->get("user")->row_array();
	}
  
  	public function password_change($controls,$user_id){
		$data =	[
			"password"	 	 =>		md5($controls['new_password']),
			"password_text"	 =>		$controls['new_password'],
		];

		$this->db->where('id',$user_id);
		return $this->db->update("user",$data);
	}
  
  	public function update_wallet_address($controls,$user_id){
		$data = [
			"crypto_address"	=>	$controls['wallet_address'],
		];
		$this->db->where("id",$user_id);
		return $this->db->update("user",$data);
  	}

	public function get_user_txn_history($user_id){
		return $this->db->where(["user_id"=>$user_id])->order_by("id","desc")->get("wallet_transaction")->result_array();
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

  	public function get_setting_value_by_key($key){
		return $this->db->where("setting_key",$key)->get("setting")->row_array()['setting_value'];
  	}

	public function get_team_stats_by_referral_id($ids){
		$referral_ids = array();
		$users = array();
		$verified_team = 0;
		
		if(!empty($ids)){
			$users = $this->db->where_in("upline",$ids)->get("user")->result_array();
			if(!empty($users)): foreach($users as $k=>$v):
			$recharge = $this->db->select("sum(credit) as recharge")->where(['user_id'=>$v['id'],'txn_for'=>'wallet-recharge'])->get("wallet_transaction")->row_array()['recharge'];
			$users[$k]['recharge'] = (!empty($recharge) && $recharge > 0 ) ? $recharge : 0 ;

			$withdraw = $this->db->select("sum(debit) as withdraw")->where(['user_id'=>$v['id'],'txn_for'=>'withdraw-success'])->get("wallet_transaction")->row_array()['withdraw'];
			$users[$k]['withdraw'] = (!empty($withdraw) && $withdraw > 0 ) ? $withdraw : 0 ;

			$referral_ids[] = $v['referral_id']; 
			$exist = $this->check_if_user_has_record_in_user_package_tbl($v['id']);
			if($exist){
				$verified_team++;
			}
			endforeach; endif;
		}
		
		$data['users'] = $users;
		$data['referral_ids'] = $referral_ids;
		$data['total_verified_team'] = $verified_team;
		return $data;
	}

	public function insert_withdraw_request($user_id,$txn_id,$amount,$service_charges,$debit_amount){
		$data = [
			"user_id"=>$user_id,
			"txn_id"=>$txn_id,
			"amount"=>$amount,
			"charges"=>$service_charges,
			"transfer_amount"=>$debit_amount,
			"updated"=>null,
			"created"=>date("Y-m-d H:i:s"),
			"status"=>0
		];

		$inserted = $this->db->insert("withdraw_request",$data);
		if($inserted){
			$req_id = $this->db->insert_id();
			$this->db->where("id",$txn_id)->update("wallet_transaction",['reference'=>$req_id]);

			return true;
		}else{
			return false;
		}

	}

	public function count_user_subscribed_packages_by_level($user_id){
		$this->db->select("p.level_id,sum(s.count) as count");
		$this->db->from("user_package s",);
		$this->db->join("package p","p.id = s.package_id");
		$this->db->group_by("p.level_id");
		$this->db->where("s.status",1);
		$this->db->where("s.user_id",$user_id);
		return $this->db->get()->result_array();
	}

	public function check_if_subscription_expired($id){
		$this->db->select("s.*,txn.txn_id,txn.debit as amount");
		$this->db->from("user_package s");
		$this->db->join("wallet_transaction txn","txn.reference = s.id");
		$this->db->where("txn.txn_for","package-subscription");
		$this->db->where("s.expire <=",date("Y-m-d H:i:s"));
		$this->db->where("s.id",$id);
		return $this->db->get()->row_array();
	}

	public function count_user_total_withdraw_by_status($user_id,$status){
		$this->db->select("sum(debit) as total");
		$this->db->from("wallet_transaction");
		$this->db->where("txn_for",$status);
		$this->db->where("user_id",$user_id);

		$result = $this->db->get()->row_array();

		$total = (!empty($result['total']) && $result['total'] > 0 ) ? $result['total'] : 0;

		return $total;
  	}

	public function get_user_withdraw_history($user_id){
		$txn_type = [
			'withdraw-success',
			'withdraw-pending',
			'withdraw-rejected',
			'withdraw-returned'
		];
		return $this->db->where(["user_id"=>$user_id])->where_in("txn_for",$txn_type)->order_by("id","desc")->get("wallet_transaction")->result_array();
	}

	public function check_if_user_has_record_in_user_package_tbl($user_id){
		$has = $this->db->where("user_id",$user_id)->get("user_package")->row_array();
		if(!empty($has)){
			return true;
		}else{
			return false;
		}
	}

	public function get_user_commission_by_from_to_date($user,$from,$to,$type){
		if($type == 'team'){
			$txn_for = [
				"wallet-recharge-referral-commission-upline-1",
				"wallet-recharge-referral-commission-upline-2",
				"reward-referral-commission-upline-1",
				"reward-referral-commission-upline-2",
			];
		}else{
			$txn_for = [
				"wallet-recharge-referral-commission-self",
				"daily-reward-collection"
			];
		}
		
		$this->db->select("SUM(credit) total");
		$this->db->where('user_id',$user);
		
		if($from != '' && $to != ''):
		$this->db->where(['created >='=>$from,'created <='=>$to]);
		endif;

		$this->db->where_in("txn_for",$txn_for);
		$total = $this->db->get("wallet_transaction")->row_array()['total'];
		if(!empty($total) && $total > 0){
			return $total;
		}else{
			return 0;
		}
	}
}
