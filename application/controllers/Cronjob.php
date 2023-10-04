<?php
class Cronjob extends CI_Controller {    
    
	public function __Construct(){
		parent::__construct();
		$this->load->model('Cronjob_Model');
        date_default_timezone_set("Asia/Karachi");
    }

    public function wallet_transaction($type,$user_id,$amount,$description,$ref,$txn_for){
        $user = $this->Cronjob_Model->get_user_by_id($user_id);
        if(!empty($user)){
            $op_bal = $user['wallet'];
            if($type == 'credit'){
                $c_bal = $op_bal + $amount;
                $credit = $amount;
                $debit = 0;
                $txn = $this->Cronjob_Model->insert_user_wallet_transaction($user_id,$credit,$debit,$op_bal,$c_bal,$description,$ref,$txn_for);
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
                $txn = $this->Cronjob_Model->insert_user_wallet_transaction($user_id,$credit,$debit,$op_bal,$c_bal,$description,$ref,$txn_for);
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

    public function expire_package(){
        $expired_subscriptions = $this->Cronjob_Model->get_all_expired_active_subscriptions();
        if(!empty($expired_subscriptions)): foreach($expired_subscriptions as $esk => $esv):
            $this->db->where("id",$esv['id'])->update("user_package",['status'=>0]);

            $description = 'Returned Principal amount on package expiry against Transaction Id '.$esv['txn_id'];
            $ref = $esv['id'];
            $txn_for = "package-subscription-expired";
            $txn = $this->wallet_transaction('credit',$esv['user_id'],$esv['amount'],$description,$ref,$txn_for);
        endforeach; endif;

        return "expire_package_done";
    }
    public function update_user_level() {

        $data = array();

        $user_ids_in_package_subscription = $this->db->distinct("user_id")->select('user_id')->get('user_package')->result_array();
        if(!empty($user_ids_in_package_subscription)): foreach($user_ids_in_package_subscription as $urk=> $user_id):
            $user_id = $user_id['user_id'];
            $user = $this->Cronjob_Model->get_user_by_id($user_id);
            if($user['status'] != 1){
                continue;
            }
            
            $data[$urk]['user_id'] = $user_id;
            $data[$urk]['downline'] = array();
            $data[$urk]['downline_count'] = 0;
            $level_1_downline = $this->Cronjob_Model->get_downline_by_referral_id($user['referral_id']);
            if(!empty($level_1_downline)): foreach($level_1_downline as $k=>$v):
                $has = $this->Cronjob_Model->check_if_user_has_subscription($v['id']);
                if($has){
                    $data[$urk]['downline'][] = $v;
                }
                
            endforeach; endif;
            
            if(!empty($data[$urk]['downline'])): foreach($data[$urk]['downline'] as $urk2=>$user_id_2):
                $usr = $this->Cronjob_Model->get_user_by_id($user_id_2['id']);
                $level_2_downline = $this->Cronjob_Model->get_downline_by_referral_id($usr['referral_id']);
                if(!empty($level_2_downline)): foreach($level_2_downline as $k=>$v):
                    $has = $this->Cronjob_Model->check_if_user_has_subscription($v['id']);
                    if($has){
                        $data[$urk]['downline'][] = $v;
                    }
                    
                endforeach; endif;
            endforeach; endif;

            $total_downline = count($data[$urk]['downline']);
            $data[$urk]['downline_count'] = $total_downline;


            $level = $this->db->where(["member_from <="=>$total_downline,"member >="=>$total_downline])->order_by("id","desc")->limit(1)->get("level")->row_array(); 
            $data[$urk]['level'] = $level['id'];
            $this->db->where("id",$user_id)->update("user",['level_id'=>$level['id']]);
        endforeach; endif;

        return $data;
    }

    public function every_minute(){
    //    $this->expire_package();
       $respo = $this->update_user_level();
    }

}
?>