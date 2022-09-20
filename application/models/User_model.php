<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
	}
	
	public function userLogin($data){
		// Turn caching on
		$this->db->cache_on();
		 if(!empty($data)){		
			$condition = "(email_id =" . "'" . $data['username'] . "' OR "." email_id =" . "'" . $data['username'] . "') AND " . "password =" . "'" . $data['password'] . "'";
			$this->db->select('*');
			$this->db->from('brij_users');
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
	
	public function check_user_logged(){
		return ($this->session->userdata('logged_in_brijwasi_user_data'))?TRUE:FALSE;
	}
	
	public function logged_user_id(){
		return ($this->check_logged())?$this->session->userdata('logged_in_brijwasi_user_data'):'';
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
	
	public function getOrders($where_column=array(),$order_by=''){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('orders.*,users.name as username');
		$this->db->from('brij_orders as orders');
		$this->db->join('brij_users as users', 'users.id = orders.user_id','left');
		$this->db->where($where_column);
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}
	
	public function getProductReviews($where_column=array(),$order_by=''){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('product_reviews.*,users.name as username,products.product_name');
		$this->db->from('brij_product_reviews as product_reviews');
		$this->db->join('brij_users as users', 'users.id = product_reviews.user_id','left');
		$this->db->join('brij_products as products', 'products.id = product_reviews.product_id','left');
		$this->db->where($where_column);
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}
	
	public function getOrderItems($where_column=array(),$order_by=''){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('order_items.*,product_categories.name as cat_name,products.product_name,products.price,product_images.images,products.product_code');
		$this->db->from('brij_order_items as order_items');
		$this->db->join('brij_products as products', 'products.id = order_items.product_id','left');
		$this->db->join('brij_product_categories as product_categories', 'product_categories.id = products.category_id','left');
		$this->db->join('brij_product_images as product_images', 'products.id = product_images.product_id','left');
		$this->db->where($where_column);
		$this->db->group_by('product_id'); 
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
			
	}
	
	public function getOrderUsersDetails($where_column=array(),$order_by=''){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('users.*,states.state_name,cities.city_name,countries.country_name');
		$this->db->from('brij_orders as orders');
		$this->db->join('brij_users as users', 'users.id = orders.user_id','left');
		$this->db->join('brij_states as states', 'states.id = users.state_id','left');
		$this->db->join('brij_cities as cities', 'cities.id = users.city_id','left');
		$this->db->join('brij_countries as countries', 'countries.id = users.country_id','left');
		$this->db->where($where_column);
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->row_object();
			
	}
	
	public function getOrderMonthlyIncome(){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('YEAR(brij_orders.order_date) as year, MONTH(brij_orders.order_date) as month,SUM(brij_orders.order_total) as order_total FROM brij_orders WHERE brij_orders.order_status="Delivered" GROUP BY year,month ORDER BY year,month', FALSE);
		$query = $this->db->get();
           //echo $this->db->last_query();
		return $query->result();
	}
	
	public function getCartItems($where_column=array(),$order_by=''){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('order_items.*,products.product_name,products.price,users.name,users.email_id');
		$this->db->from('brij_order_items as order_items');
		$this->db->join('brij_products as products', 'products.id = order_items.product_id','left');
		$this->db->join('brij_users as users', 'users.id = order_items.user_id','left');
		$this->db->where($where_column);
		//$this->db->group_by('product_id'); 
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();	
	}
	
	public function getShippingAddress($user_id='',$address_id='')
	{
		$query = $this->db->query("SELECT userAddresses.*,Countries.country_name as country_name,States.state_name as state_name, Cities.city_name as city_name, CONCAT(a_fname,' ',a_lname) AS full_name FROM `brij_users` as Users inner join brij_user_shipping_addresses as userAddresses on Users.id=userAddresses.user_id left join brij_countries as Countries on Countries.id=userAddresses.a_country_id left join brij_states as States on States.id=userAddresses.a_state_id left join brij_cities as Cities on Cities.id=userAddresses.a_city_id where userAddresses.a_status=1 and userAddresses.user_id='$user_id' and userAddresses.id='$address_id' order by userAddresses.id desc");
		$new_address_details = $query->row_object();
		return $new_address_details;
	}
	
	public function getWishListItem($where_column=array(),$order_by=''){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('wishlist.*,products.product_name,products.price,products.stock_availability');
		$this->db->from('brij_wishlist as wishlist');
		$this->db->join('brij_products as products', 'products.id = wishlist.product_id','left');
		$this->db->where($where_column);
		//$this->db->group_by('product_id'); 
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();	
	}
	
	public function order_number_generator($order_id)
	{
		$query =  $this->db->query("SELECT id, CONCAT( 'BRIJ-', LPAD(id,7,'0') ) AS order_number FROM brij_orders where id='$order_id'");
		$results = $query->row_object();
		return $results->order_number;
	}
	
	public function getShippingAddressDefaultByUser($user_id='')
	{
		$query = $this->db->query("SELECT userAddresses.*,Countries.country_name as country_name,States.state_name as state_name, Cities.city_name as city_name, CONCAT(a_fname,' ',a_lname) AS full_name FROM `brij_users` as Users inner join brij_user_shipping_addresses as userAddresses on Users.id=userAddresses.user_id left join brij_countries as Countries on Countries.id=userAddresses.a_country_id left join brij_states as States on States.id=userAddresses.a_state_id left join brij_cities as Cities on Cities.id=userAddresses.a_city_id where userAddresses.a_status=1 and userAddresses.set_default=1 and userAddresses.user_id='$user_id' order by userAddresses.id desc");
		$new_address_details = $query->row_object();
		return $new_address_details;
	}
	
	
	public function getAllShippingAddress($user_id='')
	{
		$query = $this->db->query("SELECT userAddresses.*,Countries.country_name as country_name,States.state_name as state_name, Cities.city_name as city_name, CONCAT(a_fname,' ',a_lname) AS full_name FROM `brij_users` as Users inner join brij_user_shipping_addresses as userAddresses on Users.id=userAddresses.user_id left join brij_countries as Countries on Countries.id=userAddresses.a_country_id left join brij_states as States on States.id=userAddresses.a_state_id left join brij_cities as Cities on Cities.id=userAddresses.a_city_id where userAddresses.a_status=1 and userAddresses.set_default != 1 and userAddresses.user_id='$user_id' order by userAddresses.id desc");
		$new_address_details = $query->result();
		return $new_address_details;
	}
}