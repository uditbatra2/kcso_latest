<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
	}
	
	public function adminLogin($data){
		// Turn caching on
		$this->db->cache_on();
		 if(!empty($data)){		
			$condition = "(user_mail =" . "'" . $data['username'] . "' OR "." user_name =" . "'" . $data['username'] . "') AND " . "user_pass =" . "'" . $data['password'] . "'";
			$this->db->select('*');
			$this->db->from('brij_admin');
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
	
	public function check_logged(){
		return ($this->session->userdata('logged_in_brijwasi_data'))?TRUE:FALSE;
	}
	
	public function logged_id(){
		return ($this->check_logged())?$this->session->userdata('logged_in_brijwasi_data'):'';
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
	
	public function getProducts($where_column=array(),$order_by='',$where_colum_or=array()){
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
		$query = $this->db->query("SELECT p.product_name,p.stock_availability,p.sku,p.product_code,p.price,order_items.product_id, SUM(product_quantity) AS TotalQuantity,SUM(price*product_quantity) AS TotalQuantityPrice FROM `brij_products` as p inner join brij_order_items as order_items on p.id=order_items.product_id where p.status=1 and ( order_items.date_added >='$dt_week_start_date' AND order_items.date_added <='$dt_week_end_date' ) and order_items.order_item_status !=0 GROUP BY product_id ORDER BY SUM(product_quantity) DESC LIMIT 5");
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
	
	public function getPosts($where_column=array(),$order_by='',$where_colum_or=array()){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('posts.*,GROUP_CONCAT(category.name SEPARATOR ",")as cat_name,post_author.screen_name as author');
		$this->db->from('posts');
		$this->db->join('post_categories as p_category', 'p_category.posts_id = posts.id','left');
		$this->db->join('categories as category', 'category.id = p_category.categories_id','left');
		$this->db->join('brij_admin as post_author', 'post_author.user_id = posts.author_id','left');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		$this->db->group_by('posts.id');
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}

	public function getCareer($where_column=array(),$order_by='',$where_colum_or=array()){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('career.*,GROUP_CONCAT(category.name SEPARATOR ",")as cat_name,post_author.screen_name as author');
		$this->db->from('career');
		$this->db->join('post_categories as p_category', 'p_category.posts_id = career.id','left');
		$this->db->join('categories as category', 'category.id = p_category.categories_id','left');
		$this->db->join('brij_admin as post_author', 'post_author.user_id = career.author_id','left');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		$this->db->group_by('career.id');
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}


	public function getTestimonials($where_column=array(),$order_by='',$where_colum_or=array()){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('testimonials.*,GROUP_CONCAT(category.name SEPARATOR ",")as cat_name');
		$this->db->from('testimonials');
		$this->db->join('post_categories as p_category', 'p_category.posts_id = testimonials.id','left');
		$this->db->join('categories as category', 'category.id = p_category.categories_id','left');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		$this->db->group_by('testimonials.id');
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}

	public function getTeam($where_column=array(),$order_by='',$where_colum_or=array()){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('team.*,GROUP_CONCAT(category.name SEPARATOR ",")as cat_name');
		$this->db->from('team');
		$this->db->join('post_categories as p_category', 'p_category.posts_id = team.id','left');
		$this->db->join('categories as category', 'category.id = p_category.categories_id','left');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		$this->db->group_by('team.id');
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}

	public function getClient($where_column=array(),$order_by='',$where_colum_or=array()){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('client.*,GROUP_CONCAT(category.name SEPARATOR ",")as cat_name');
		$this->db->from('client');
		$this->db->join('post_categories as p_category', 'p_category.posts_id = client.id','left');
		$this->db->join('categories as category', 'category.id = p_category.categories_id','left');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		$this->db->group_by('client.id');
		 $this->db->order_by($order_by);
		}	
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}

	public function getCaseStudies($where_column=array(),$order_by='',$where_colum_or=array()){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('case_studies.*,GROUP_CONCAT(category.name SEPARATOR ",")as cat_name');
		$this->db->from('case_studies');
		$this->db->join('post_categories as p_category', 'p_category.posts_id = case_studies.id','left');
		$this->db->join('categories as category', 'category.id = p_category.categories_id','left');
		$this->db->where($where_column);
		if(isset($where_colum_or) && !empty($where_colum_or)){
			$this->db->or_where($where_colum_or);
		}
		if(isset($order_by) && !empty($order_by)){
		$this->db->group_by('case_studies.id');
		 $this->db->order_by($order_by);
		}
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();	
	}
		
   public function getPageById($id){
		// Turn caching on
		$this->db->cache_on();
		$this->db->select('*');
		$this->db->from('pages');
		$this->db->where('id',$id);
		
		$query = $this->db->get();
		  //echo $this->db->last_query();
		return $query->result();
	}
}