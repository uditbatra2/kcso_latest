<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
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
	
    public function getSubCategories($where_column=array(),$order_by='',$where_colum_or=array()){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('subcategory.*,category.name as cat_name,subcategory.name as subcat_name');
		$this->db->from('brij_product_categories as category');
		$this->db->join('brij_product_categories as subcategory', 'category.id = subcategory.parent_id');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}
	
	public function getProducts($where_column=array(),$order_by='',$where_colum_or=array(),$limit='',$start=0){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('brij_products.*,category.name as cat_name,subcategory.name as subcat_name');
		$this->db->from('brij_products');
		$this->db->join('brij_product_categories as category', 'category.id = brij_products.category_id','left');
		$this->db->join('brij_product_categories as subcategory', 'subcategory.id = brij_products.sub_category_id','left');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}
		if(isset($limit) && !empty($limit)){
			//$this->db->limit($limit);// only apply if you have more than same id in your table othre wise comment this line
            $this->db->limit($limit, $start);			
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
		$this->db->select('order_items.*,products.product_name,products.price,product_images.images');
		$this->db->from('brij_order_items as order_items');
		$this->db->join('brij_products as products', 'products.id = order_items.product_id','left');
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
	
	public function getBestSellerProduct(){
		// Turn caching on
		$this->db->cache_on();
		$dt_week_start_date = date('Y-m-d 00:00:00',strtotime("last Saturday"));
		$dt_week_end_date = date('Y-m-d 23:59:59',strtotime("next Saturday"));
		$query = $this->db->query("SELECT p.product_name,p.description,p.stock_availability,p.sku,p.product_code,p.price,order_items.product_id, SUM(product_quantity) AS TotalQuantity,SUM(price*product_quantity) AS TotalQuantityPrice FROM `brij_products` as p inner join brij_order_items as order_items on p.id=order_items.product_id where p.status=1 and ( order_items.date_added >='$dt_week_start_date' AND order_items.date_added <='$dt_week_end_date' ) and order_items.order_item_status !=0 GROUP BY product_id ORDER BY SUM(product_quantity) DESC LIMIT 4");
		 //echo $this->db->last_query();
        return $query->result();
	}
	
	public function getMostViewedProducts($where_column=array(),$order_by='',$where_colum_or=array(),$limit=5){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('products.*,category.name as cat_name,subcategory.name as subcat_name');
		$this->db->from('brij_product_recently_views as product_recently_views');
		$this->db->join('brij_products as products', 'products.id = product_recently_views.product_id','left');
		$this->db->join('brij_product_categories as category', 'category.id = products.category_id','left');
		$this->db->join('brij_product_categories as subcategory', 'subcategory.id = products.sub_category_id','left');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		 $this->db->order_by($order_by);
		}
        $this->db->limit($limit);// only apply if you have more than same id in your table othre wise comment this line		
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}
	
	public function getCartProductItem($session_id=null){
		// Turn caching on
		$this->db->cache_on();
		$conditions=' OrderItems.order_session_id="'.$session_id.'"';
		if ($this->session->userdata('logged_in_brijwasi_user_data')['ID']) {						
			$conditions = ' OrderItems.user_id="'.$this->session->userdata('logged_in_brijwasi_user_data')['ID'].'"';					
		}
		$query = $this->db->query("SELECT Products.sku,Products.product_code,Products.product_name,Products.price,Products.category_id,Products.qty_type,Products.price_type,Products.quantity,Products.stock_availability,Products.delivered_in_days,ProductCategories.name,OrderItems.* FROM `brij_products` as Products inner join brij_order_items as OrderItems on Products.id=OrderItems.product_id inner join brij_product_categories as ProductCategories on Products.category_id=ProductCategories.id where Products.status=1 and OrderItems.order_item_status=0 and".$conditions." order by OrderItems.id asc");
        $new_cart_details = $query->result();
		  //echo $this->db->last_query();
		return $new_cart_details;
	}
}