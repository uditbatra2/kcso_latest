<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
	}
	
	//Method to generate a unique api key every time
    private function generateApiKey(){
        return md5(uniqid(rand(), true));
    }
	
	public function index()
	{
		$criteria=array();
		$data['cartItemList'] = $this->product_model->getCartProductItem($session_id=$this->session->session_id);
		$where_in_data=array();
		foreach($data['cartItemList'] as $cartItemList){
			$where_in_data[]=$cartItemList->category_id;
		}
		$data['relatedProductList']=array();
		if(count($where_in_data) > 0 && !empty($where_in_data)){
			$where_condition=array("status" => 1);
			$where_in_cloumn='category_id';
			$data['relatedProductList'] = $this->base_model->getAllRowsWithLimit("brij_products",$where_condition, $order_by='rand()', $limit=15,$where_in_data,$where_in_cloumn);
		}
		$data['priceType']=$this->config->item('priceType');		
		$data['title'] = "Cart View";
        $data['site_description'] = getSiteSettingValue(3);
        $data['site_keyword'] = getSiteSettingValue(4);		
	    $this->load->view('include/header',$data);
        $this->load->view('include/menu',$data);
	    $this->load->view('Cart/index',$data);
	    $this->load->view('include/footer',$data);
	}
	
    public function add_cart_item()
	{
		if($this->input->method() === 'post'){
			//echo "<pre>";print_r($_POST);
			//die;
			if($this->input->post('product', TRUE) && !empty($this->input->post('product', TRUE)) && $this->input->post('product', TRUE) != '') {
				    $where=array("id"=> $this->input->post('product', TRUE), "status"=> 1);
					$productDetails = $this->base_model->getOneRecordWithWhere("brij_products",$where ,"*");
					if(empty($productDetails) && count($productDetails) == 0){
						$this->session->set_flashdata('product_cart_error', 'Product does not available. Please try again!');
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("/");
						}
					}
					$where=array("product_id"=> $this->input->post('product', TRUE), "order_session_id"=> $this->session->session_id,"order_item_status"=> 0);
					if ($this->session->userdata('logged_in_brijwasi_user_data')['ID'] && !empty($this->session->userdata('logged_in_brijwasi_user_data')['ID'])) {			
						$user_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];	
                        $where=array("product_id"=> $this->input->post('product', TRUE), "user_id"=> $user_id,"order_item_status"=> 0);						
					}
					$productCartItemsDetails = $this->base_model->getOneRecordWithWhere("brij_order_items",$where ,"*");
					if(count($productCartItemsDetails) > 0){
						$date = date("Y-m-d H:i:s");
						$totalPriceQuntity= ($productDetails->price * $this->input->post('qty', TRUE));
						$product_quantity= ($productCartItemsDetails->product_quantity + $this->input->post('qty', TRUE));
						$product_price= ($productCartItemsDetails->product_price + $totalPriceQuntity);
						$update_data = array(
								'product_quantity' => $product_quantity,
								'product_price'=>  $product_price,
								'date_updated'=>  $date,
							);
						if ($this->session->userdata('logged_in_brijwasi_user_data')['ID']) {						
							$update_data['user_id']= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];					
						}
						$where_conditions=array("id"=>$productCartItemsDetails->id,"order_item_status"=> 0);
						$last_inserted_id=$this->base_model->update_entry('brij_order_items',$update_data,$where_conditions);
					}else{						
						$date = date("Y-m-d H:i:s");
						$totalPriceQuntity= ($productDetails->price * $this->input->post('qty', TRUE));
						$insert_data = array(
								'product_id' => $productDetails->id,
								'product_quantity' => $this->input->post('qty', TRUE),
								'product_price'=>  $totalPriceQuntity,
								'order_session_id'=>  $this->session->session_id,
								'order_item_status'=>  0,
								'date_added'=>  $date,
							);
						if ($this->session->userdata('logged_in_brijwasi_user_data')['ID']) {						
							$insert_data['user_id']= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];					
						}
						$last_inserted_id=$this->base_model->insert_entry('brij_order_items',$insert_data);
					}
					if ($last_inserted_id){
						//check switch condition
						switch($this->input->post('action', TRUE)){
							case "productAddToCartFromWishlist":							
							$where_conditions_a=array('id'=>$this->input->post('wishlist_id', TRUE));
							if ($this->session->userdata('logged_in_brijwasi_user_data')['ID']) {						
								$where_conditions_a['user_id']= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];					
							}
							$deleted=$this->base_model->deleteWithWhereConditions('brij_wishlist',$where_conditions_a);
							break;
						}
						$this->session->set_flashdata('product_cart_success', 'Product has been added to your cart.');
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("cart");
						}
					}
			}else{				
				$this->session->set_flashdata('product_cart_error', 'Please provide complete information for product.');
				if ($this->agent->referrer()){
					//redirect to some function
					redirect($this->agent->referrer());
				}else{
					redirect("product/product_details/?pro_id=".$this->input->post('product', TRUE));
				}
			}
		}  
	}
	
	public function cart_update()
	{
		if($this->input->method() === 'post'){
			$cart_action=$this->input->post('update_cart_action', TRUE);
			$cartItemId=$this->input->post('cart', TRUE);
			$date = date("Y-m-d H:i:s");
			switch($cart_action)
			{
				case "update_qty":
				  $update=0;
				  foreach($cartItemId as $cartID=>$qty)
				  {
					$cartItemDetails= $this->base_model->getOneRecord("brij_order_items","id", $cartID, "product_id");
					$productDetails= $this->base_model->getOneRecord("brij_products","id", $cartItemDetails->product_id, "price");
					foreach($qty as $valueQty)
					{
						if(!empty($valueQty) && $valueQty > 0)
						{
							$totalValueQty = ($productDetails->price * $valueQty);
							$update_date[]=array("id"=>$cartID,'product_quantity'=>$valueQty,'product_price'=>$totalValueQty,"date_updated"=>$date);
							$update++;
						}else{
							$where_conditions_a=array('id'=>$cartID);
							$deleted=$this->base_model->deleteWithWhereConditions('brij_order_items',$where_conditions_a);
						}
					}					
				  }
				  if($update > 0){
				  $last_inserted_id=$this->base_model->update_multiple_entry('brij_order_items', $update_date, 'id');
					$this->session->set_flashdata('product_cart_success', 'Cart items has been updated successfully.');
				  }else{
					//$this->session->set_flashdata('product_cart_error', 'Cart items does not update. Please make changes.');
				  }
                  if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("cart");
					}  				  
				break;
				case "empty_cart":
				  $deleted=0;
				  foreach($cartItemId as $cartID=>$qty)
				  {
					$where_conditions_a=array('id'=>$cartID);
					$deleted=$this->base_model->deleteWithWhereConditions('brij_order_items',$where_conditions_a);
                    $deleted++;					
				  }
				  //die;
				   if($deleted > 0){
						$this->session->set_flashdata('product_cart_success', 'Cart items has been deleted successfully.');
					  }else{
						$this->session->set_flashdata('product_cart_error', 'Cart items does not deleted. Please try again!');
					  }
                  if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("cart");
					} 
				break;
				default:
				$this->session->set_flashdata('product_cart_error', 'Cart items does not update. Please try again!');
				if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("cart");
					}
				break;
			}
			//echo "<pre>";print_r($_POST);
			//print_r($_POST["cart"]);
			//die;
		}
	}
	
	public function delete_cart_items()
	{
		$data=array();
		$cart_id=trim($this->input->get('cart_id', TRUE));
		if(isset($cart_id) && !empty($cart_id)){
			$where_conditions_a=array('id'=>$cart_id);
			$deleted=$this->base_model->deleteWithWhereConditions('brij_order_items',$where_conditions_a);
			$this->session->set_flashdata('product_cart_success', 'Cart items has been deleted successfully.');
			if ($this->agent->referrer()){
				//redirect to some function
				redirect($this->agent->referrer());
			}else{
				redirect("cart");
			}
		}
	}
}
