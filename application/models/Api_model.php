<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
	}
	
	/*
     * Fetch user login data
     */
    function userLogin($userData){
		// Turn caching on
		$this->db->cache_on();
        if(!empty($userData)){
			$this->db->select('users.id,users.unique_code,users.full_name,users.email,users.phone_no,users.address,states.name as state_name,cities.name as city_name, users.is_active, user_types.title');
			$this->db->from('users');
			$this->db->join('states', 'users.state_id = states.id');
			$this->db->join('cities', 'users.city_id = cities.id');
			$this->db->join('user_types', 'users.user_type = user_types.id');
			$this->db->where(array('users.email' => $userData['user_email'], 'users.password'=>md5($userData['user_password']))); 
			$query = $this->db->get();
            return $query->row_array();
        }else{
            return false;
        }
    }
	
	/*
     * Fetch user shop data
     */
	public function getUserShops($user_id,$where_column){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('*');
		$this->db->from('shops');
		$this->db->join('user_shops', 'user_shops.shop_id = shops.id');
		$this->db->where($where_column); 
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	
	/*
     * Fetch data
     */
    function getRows($id = "",$table_name=""){
		// Turn caching on
		$this->db->cache_on();
        if(!empty($id) && !empty($table_name)){
            $query = $this->db->get_where($table_name, array('id' => $id));
            return $query->row_array();
        }else{
            $query = $this->db->get($table_name);
            return $query->result_array();
        }
    }
	
	/*
     * Insert user leave form data
     */
    public function insertLeaveForm($data = array()) {
        if(!array_key_exists('sent_date', $data)){
            $data['sent_date'] = date("Y-m-d H:i:s");
        }
		
        $insert = $this->db->insert('user_leave_request', $data);
        if($insert){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }
	
    /*
     * Insert user data
     */
    public function insert($data = array()) {
        if(!array_key_exists('created', $data)){
            $data['created'] = date("Y-m-d H:i:s");
        }
        if(!array_key_exists('modified', $data)){
            $data['modified'] = date("Y-m-d H:i:s");
        }
        $insert = $this->db->insert('users', $data);
        if($insert){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }
    
    /*
     * Update user data
     */
    public function update($data, $id) {
        if(!empty($data) && !empty($id)){
            if(!array_key_exists('modified', $data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            $update = $this->db->update('users', $data, array('id'=>$id));
            return $update?true:false;
        }else{
            return false;
        }
    }
    
    /*
     * Delete user data
     */
    public function delete($id){
        $delete = $this->db->delete('users',array('id'=>$id));
        return $delete?true:false;
    }
	
	public function adminLogin($data){
		// Turn caching on
		$this->db->cache_on();
		 if(!empty($data)){		
			$condition = "(user_mail =" . "'" . $data['username'] . "' OR "." user_name =" . "'" . $data['username'] . "') AND " . "user_pass =" . "'" . $data['password'] . "'";
			$this->db->select('*');
			$this->db->from('admin');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() == 1) {
				return  $query->row_object();
			}else{
			  return false;		
			}
		 }else{
			 return false;
		 }
	}
	
	public function getSearch($table=null,$order_by='id DESC',$search_criteria=array(),$where_in_data=array(),$where_in_cloumn='',$select_column='*'){
	  // Turn caching on
	  $this->db->cache_on();
	  $this->db->select($select_column);
	  if(isset($where_in_data) && !empty($where_in_data)){
			$this->db->where_in($where_in_cloumn, $where_in_data);
		}
	  if(!empty($search_criteria) && isset($search_criteria)){
		  $whereCondition = $search_criteria;
		  $this->db->where($whereCondition);
	  }
	  $this->db->from($table);
	  if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}
	  $query = $this->db->get();
	   //echo $this->db->last_query();
	  return $query->result();
	}
	
	public function getStateProduct($where_column=array()){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('*');
		$this->db->from('states');
		$this->db->join('state_products', 'state_products.state_id = states.id');
		$this->db->where($where_column); 
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	
	public function getNotification($where_column){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('notifications.*,notification_users.id as n_s_id,notification_users.is_read');
		$this->db->from('notifications');
		$this->db->join('notification_users', 'notification_users.notification_id = notifications.id');
		//$this->db->join('users', 'notification_users.user_id = users.id');
		$this->db->where($where_column); 
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
}