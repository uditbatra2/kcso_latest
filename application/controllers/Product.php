<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	public function __construct(){
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
	}

	//Method to generate a unique api key every time
	private function _generateApiKey(){
		return md5(uniqid(rand(), true));
	}
	
	public function products_list()
	{
		$data=array();
		$cat_id=trim($this->input->get('cat_id', TRUE));
		$sub_cat_id=trim($this->input->get('sub_cat_id', TRUE));
		if(isset($cat_id) && !empty($cat_id) || isset($sub_cat_id) && !empty($sub_cat_id)){
			$search_criteria=$search_criteria_or=array();
			$cat_id=trim($this->input->get('cat_id', TRUE));
			$sub_cat_id=trim($this->input->get('sub_cat_id', TRUE));
			$product_list_order=trim($this->input->get('product_list_order', TRUE));
			$serach_query=trim($this->input->get('q', TRUE));
			$limiter=trim($this->input->get('limiter', TRUE));
			$price=trim($this->input->get('price', TRUE));
			if(!isset($cat_id) && !empty($cat_id)){	
			}
			$cat_id=(isset($cat_id) && $cat_id != '')?$cat_id:"";
			$sub_cat_id=(isset($sub_cat_id) && $sub_cat_id != '')?$sub_cat_id:"";
			$serach_query=(isset($serach_query) && $serach_query != '')?$serach_query:"";
			$price=(isset($price) && $price != '')?$price:"";
			$product_list_order=(isset($product_list_order) && $product_list_order != '')?$product_list_order:"";
			$limiter=(isset($limiter) && $limiter != '')?$limiter:12;
			
			if(isset($cat_id) && $cat_id != '' && $cat_id != 'all'){
				$search_criteria['category_id']= $cat_id;
				$cat_id=$cat_id;
			}
			
			if(isset($sub_cat_id) && $sub_cat_id != ''){
				$search_criteria['sub_category_id']= $sub_cat_id;
				$cat_id=$sub_cat_id;
			}
			
			if(isset($serach_query) && $serach_query != ''){
				$search_criteria['product_name LIKE']= '%'.$serach_query.'%';
			}
			
			if(isset($price) && $price != ''){
                $price_e=explode('-',$price);
                if(isset($price_e[0]) && $price_e[0] != '')
				{				
					$search_criteria['price >=']=  $price_e[0];
				}
				if(isset($price_e[1]) && $price_e[1] != '')
				{
					$search_criteria['price <=']=  $price_e[1];
				}
			}
			
			$order_by='brij_products.id DESC';
			switch($product_list_order){
				case "product-name";
				$order_by='brij_products.product_name ASC';
				break;
				case "price-asc";
				$order_by='brij_products.price ASC';
				break;
				case "price-desc";
				$order_by='brij_products.price DESC';
				break;
				default:
				$order_by='brij_products.id DESC';
				break;
			}
			
			$search_criteria['brij_products.status =']= 1;
			$data['productList'] = $this->product_model->getProducts($search_criteria,$order_by,$search_criteria_or,$limiter);
			$data['title'] = "Search results for: '".$serach_query."'";
			$data['site_description'] = getSiteSettingValue(3);
			$data['site_keyword'] = getSiteSettingValue(4);
			$data['catDetails']=array();
			if(!empty($cat_id) && isset( $cat_id) && $cat_id != 'all'){
				$data['catDetails'] = $this->base_model->getOneRecord("brij_product_categories","id", $cat_id, "*");
				$data['title'] = $data['catDetails']->name;
				$data['site_description'] = $data['catDetails']->description;
			}	
		}else{
			redirect(base_url());
		}
		$data['querykeyword'] = $serach_query;	
		$data['cat_idkeyword'] = $cat_id;
		$data['pricekeyword'] = $price;
		$data['limiterkeyword'] = $limiter;
		$data['productlistorderkeyword'] = $product_list_order;	
	    $this->load->view('include/header',$data);
        $this->load->view('include/menu',$data);
	    $this->load->view('Product/products-list',$data);
	    $this->load->view('include/footer',$data);
	}
	
	public function product_details()
	{
		$data=array();
		$pro_id=trim($this->input->get('pro_id', TRUE));
		if(isset($pro_id) && !empty($pro_id)){
			$search_criteria=array();
			
			$pro_id=trim($this->input->get('pro_id', TRUE));

			if(!isset($pro_id) && empty($pro_id)){
				
			}
			$pro_id=(isset($pro_id) && $pro_id != '')?$pro_id:"";
			
			$data['productDetails'] = $this->base_model->getOneRecord("brij_products","id", $pro_id, "*");
			
			if(!empty($data['productDetails']) && count($data['productDetails']) > 0){
				
				$session_data = array(
						'ACTION' => trim($this->input->post('action', TRUE)),
						'PRODUCT_PIN_CODE' => trim($this->input->post('product_pin_code', TRUE)),
					);
					
					if(isset($this->session->userdata('brijwasi_user_session_data')['ORDER_ID']) && !empty($this->session->userdata('brijwasi_user_session_data')['ORDER_ID'])){
						$session_data['ORDER_ID']=$this->session->userdata('brijwasi_user_session_data')['ORDER_ID'];							
					}
				
				//Checking Pincode Delivery
				if(isset($this->session->userdata('brijwasi_user_session_data')['PRODUCT_PIN_CODE']) && !empty($this->session->userdata('brijwasi_user_session_data')['PRODUCT_PIN_CODE'])){
					$where=array("id"=> $pro_id,'product_pincode'=> $this->session->userdata('brijwasi_user_session_data')['PRODUCT_PIN_CODE']);
					$uPincodeDetails = $this->base_model->getOneRecordWithWhere("brij_products",$where,"*");
					if(!empty($uPincodeDetails) && count($uPincodeDetails) > 0){
						if($uPincodeDetails->stock_availability == 1 || $uPincodeDetails->quantity > 0){
								$deliveryDays = $uPincodeDetails->delivered_in_days;
								$deliveryDaysText = $uPincodeDetails->delivered_in_days.' Day';
								if($deliveryDays > 1){
									$deliveryDaysText = $uPincodeDetails->delivered_in_days.' Days';
								}
								$message='Will be Deliver '.$deliveryDaysText;
								$this->session->set_flashdata('product_pin_success', $message);
							}else{
								$this->session->set_flashdata('product_pin_error', 'Currently out of stock in this area.');
							}
					}else{						
						$this->session->set_flashdata('product_pin_error', 'Sorry we are unable to deliver at this pincode.');					
					}
				}
				//Checking Pincode Delivery
				if(isset($pro_id) && $pro_id != ''){
					$search_criterias['category_id']= $data['productDetails']->category_id;
					//$search_criterias_or['sub_category_id']= $data['productDetails']->;
					$search_criterias_or=array();
				}
				$search_criterias['brij_products.status =']= 1;
				$search_criterias['brij_products.id !=']= $pro_id;
				$data['productList'] = $this->product_model->getProducts($search_criterias,$order_by='brij_products.id DESC',$search_criterias_or);
				
				$ip_add=$_SERVER['REMOTE_ADDR'];
				$date = date("Y-m-d");
				$totalProductsView=$this->base_model->getNumRows('brij_product_recently_views',$where_condition=array("ip_address" => $ip_add,"product_id"=>$pro_id));
				if ($totalProductsView == 0 ){
					 $insert_data = array(
						'ip_address' => $ip_add,
						'product_id'=>  $pro_id,
						'view_date'=>  $date,
					);
					$last_inserted_id=$this->base_model->insert_entry('brij_product_recently_views',$insert_data);
				 }
			}else{
				
				redirect(base_url());
			}			 
		}else{
			redirect(base_url());
		}
		
		$data['priceType']=$this->config->item('priceType');		
		$data['title'] = $data['productDetails']->product_name;
        $data['site_description'] = $data['productDetails']->description;
        $data['site_keyword'] = getSiteSettingValue(4);		
	    $this->load->view('include/header',$data);
        $this->load->view('include/menu',$data);
	    $this->load->view('Product/product-details',$data);
	    $this->load->view('include/footer',$data);
	}
	
	
	public function set_product_pin_code()
	{
		if($this->input->method() === 'post'){
			//echo "<pre>";print_r($_POST);
			//die;
			 // Removing session data
				if($this->input->post('action', TRUE) && $this->input->post('product_pin_code', TRUE) && $this->input->post('product_id', TRUE)){
					$session_data = array(
						'ACTION' => trim($this->input->post('action', TRUE)),
						'PRODUCT_PIN_CODE' => trim($this->input->post('product_pin_code', TRUE)),
					);
					if(isset($this->session->userdata('brijwasi_user_session_data')['ORDER_ID']) && !empty($this->session->userdata('brijwasi_user_session_data')['ORDER_ID'])){
						$session_data['ORDER_ID']=$this->session->userdata('brijwasi_user_session_data')['ORDER_ID'];							
					}
					// Add user data in session
					$this->session->set_userdata('brijwasi_user_session_data', $session_data);
					$where=array("id"=> $this->input->post('product_id', TRUE),'product_pincode'=> $this->input->post('product_pin_code', TRUE));
					$uPincodeDetails = $this->base_model->getOneRecordWithWhere("brij_products",$where,"*");
					if(!empty($uPincodeDetails) && count($uPincodeDetails) > 0){
						if($uPincodeDetails->stock_availability == 1 || $uPincodeDetails->quantity > 0){
						$deliveryDays = $uPincodeDetails->delivered_in_days;
						$deliveryDaysText = $uPincodeDetails->delivered_in_days.' Day';
						if($deliveryDays > 1){
							$deliveryDaysText = $uPincodeDetails->delivered_in_days.' Days';
						}
						$message='Will be Deliver '.$deliveryDaysText;
						$this->session->set_flashdata('product_pin_success', $message);
						}else{
							$this->session->set_flashdata('product_pin_error', 'Currently out of stock in this area.');
						}
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("/");
						}
					}else{						
						$this->session->set_flashdata('product_pin_error', 'Sorry we are unable to deliver at this pincode.');
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("/");
						}						
					}
			}
		}
	}
}