<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller{
	
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
		$isCartItem=0;
		foreach($data['cartItemList'] as $cartItemList){
			$isCartItem++;
		}
		if($isCartItem <= 0){
			redirect("cart");	
		}
		
		$condition_user_shipping['user_id']= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		$data['usersShippingAddress']=$this->base_model->getAllRows('brij_user_shipping_addresses','id DESC',$condition_user_shipping);
		$data['priceType']=$this->config->item('priceType');
		
        $condition_state['id']= 38;
		$condition_state['status']= 1;
		$data['statList']=$this->base_model->getAllRows('brij_states','id DESC',$condition_state);
		
		$condition_city['id']= 5022;
		$condition_city['status']= 1;
		$data['cityList']=$this->base_model->getAllRows('brij_cities','id DESC',$condition_city);
		
		$condition_user_shipping['set_default']= 1;
		$data['defaultUsersShippingAddress']=$this->base_model->getOneRecordWithWhere("brij_user_shipping_addresses",$condition_user_shipping ,"*");
		
		$data['title'] = "Checkout";
        $data['site_description'] = getSiteSettingValue(3);
        $data['site_keyword'] = getSiteSettingValue(4);		
	    $this->load->view('include/header',$data);
        $this->load->view('include/menu',$data);
	    $this->load->view('Checkout/index',$data);
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
					$where=array("product_id"=> $this->input->post('product', TRUE), "order_session_id"=> $this->session->session_id);
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
						$where_conditions=array("id"=>$productCartItemsDetails->id);
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
	
	public function payment()
	{
		$criteria=array();
		$data['cartItemList'] = $this->product_model->getCartProductItem($session_id=$this->session->session_id);
		$isCartItem=0;
		foreach($data['cartItemList'] as $cartItemList){
			$isCartItem++;
		}
		if($isCartItem <= 0){
			redirect("cart");	
		}
		
		//print_r($this->session->userdata('brijwasi_user_session_data'));
		
		$where=array("id"=> $this->session->userdata('logged_in_brijwasi_user_data')['ID']);
		$data['usersBilingAddress'] = $this->base_model->getOneRecordWithWhere("brij_users",$where ,"*");
		
		$where=array("user_id"=> $this->session->userdata('logged_in_brijwasi_user_data')['ID'], "id"=> $this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS']);
		$data['usersShippingAddress'] = $this->base_model->getOneRecordWithWhere("brij_user_shipping_addresses",$where ,"*");
		
		$data['priceType']=$this->config->item('priceType');
		
		//print_r($this->session->userdata('brijwasi_user_session_data'));
		
		$data['title'] = "Review & Payments";
        $data['site_description'] = getSiteSettingValue(3);
        $data['site_keyword'] = getSiteSettingValue(4);		
	    $this->load->view('include/header',$data);
        $this->load->view('include/menu',$data);
	    $this->load->view('Checkout/payment',$data);
	    $this->load->view('include/footer',$data);
	}
	
	public function place_order()
	{
		if($this->user_model->check_user_logged()=== false){
			redirect(base_url().'user/login');
		}
		if($this->input->method() === 'post'){
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
			switch($this->input->post('payment_method',true)){
				case "COD":
				    $cart_details=getTotalCartItems($session_id=$this->session->session_id);
					//echo "<pre>";print_r($cart_details);
					$address_id=$this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS'];					
					$new_address_details = $this->user_model->getShippingAddress($logged_in_id,$address_id);
					$payment_method=($this->input->post('payment_method',true) && !empty($this->input->post('payment_method',true)))?$this->input->post('payment_method',true):'COD';	
					$inserted_data=array(
						'user_id' => $logged_in_id,
						'address_id' => $address_id,
						'number_of_items'=>  $cart_details['cart_items'],
						'order_sub_total'=>  $cart_details['cart_total'],
						'tax_amount'=>  $cart_details['cart_items_tax'],
						'order_total'=>  $cart_details['grand_total_with_tax'],
						'shipping_name'=>  $new_address_details->full_name,
						'shipping_mobile_no'=>  $new_address_details->a_mobile_no,
						'shipping_address'=>  $new_address_details->a_address_one,
						'shipping_country'=>  $new_address_details->country_name,
						'shipping_state'=>  $new_address_details->state_name,
						'shipping_city'=>  $new_address_details->city_name,
						'shipping_post_code'=>  $new_address_details->a_post_code,
						'shipping_company_name' => $new_address_details->company_name,
						'order_status'=>  "Pending",
						'device_type'=> "Website",
						'user_order_remark' => $this->input->post('user_order_remark',true),
						'payment_method'=>  $payment_method,
						'payment_date'=>  date('Y-m-d H:i:s'),
						'payment_status'=>  'NOT PAID',
						'order_date'=>  date('Y-m-d H:i:s'),
					);
					
					if(isset($this->session->userdata('brijwasi_user_session_data')['ORDER_ID']) && !empty($this->session->userdata('brijwasi_user_session_data')['ORDER_ID'])){
						$where_conditions_o= array("id"=>$this->session->userdata('brijwasi_user_session_data')['ORDER_ID']);
						$last_inserted_id=$this->base_model->update_entry('brij_orders', $inserted_data ,$where_conditions_o);
						$order_id=$this->session->userdata('brijwasi_user_session_data')['ORDER_ID'];							
					}else{
						$order_id=$this->base_model->insert_entry('brij_orders',$inserted_data);
					}
					
					//$order_id=$this->base_model->insert_entry('brij_orders',$inserted_data);
				   if($order_id){
						//order number update
						$order_number["order_number"] = $this->user_model->order_number_generator($order_id);
						$where_conditions_o=array("id"=>$order_id);
						$last_inserted_id=$this->base_model->update_entry('brij_orders', $order_number ,$where_conditions_o);
						//order id update on order items
						$order_items["order_id"] = $order_id;						
						$where_conditions_ois= array("order_session_id"=>$this->session->session_id);
						if($this->session->userdata('logged_in_brijwasi_user_data')['ID'] && !empty($this->session->userdata('logged_in_brijwasi_user_data')['ID'])){
                            $where_conditions_ois= array("user_id"=>$this->session->userdata('logged_in_brijwasi_user_data')['ID']);							
						}
						$where_conditions_ois["order_item_status"] = 0;
						$last_inserted_id=$this->base_model->update_entry('brij_order_items', $order_items ,$where_conditions_ois);
						
						//order items status update
						$order_item["order_item_status"] = 1;
						$where_conditions_oi=array("order_id"=>$order_id);
						$last_inserted_id=$this->base_model->update_entry('brij_order_items', $order_item ,$where_conditions_oi);

                        $query = $this->db->query("SELECT Products.id as pro_id,OrderItems.* FROM `brij_products` as Products inner join brij_order_items as OrderItems on Products.id=OrderItems.product_id where Products.status=1 and OrderItems.order_item_status=1 and OrderItems.order_id='$order_id'");
						$cartItem = $query->result();
						foreach ($cartItem as $cart) {
							$productId = $cart->product_id;
							$qty =$cart->product_quantity;
							/*$stmt =$this->db->query("UPDATE `brij_products` SET `quantity` = `quantity` - $qty 
									WHERE `id` = $productId");*/
						}
						//$stmt = $this->db->query("UPDATE brij_products SET stock_availability=0 WHERE quantity=0 and stock_availability !=0");
						//$stmt = $this->db->query("UPDATE brij_products SET stock_availability=1 WHERE quantity>0 and stock_availability !=1");
						//$this->session->unset_userdata('brijwasi_user_session_data');
						$session_data = array(
								'ACTION' => "ORDER_ID_GET",
								'ORDER_ID' => trim($order_id),
							);
						// Add user data in session
						$this->session->set_userdata('brijwasi_user_session_data', $session_data);
						$SITE_EMAIL_NOTIFICATION=getSiteSettingValue(25);
						$custMail = $this->orderMailCustomer($order_id);
						$adminMail=1;
						if($SITE_EMAIL_NOTIFICATION == 1){
						 $adminMail = $this->orderMailAdmin($order_id);
						}
						if($custMail==1 || $adminMail==1){
							redirect("checkout/payment_success/?order_id=".$order_id);	
						}
					}
				break;
				case "PayUmoney":
					//pr($inserted_data);
					//die;
				    $cart_details=getTotalCartItems($session_id=$this->session->session_id);
					//echo "<pre>";print_r($cart_details);
					$address_id=$this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS'];					
					$new_address_details = $this->user_model->getShippingAddress($logged_in_id,$address_id);
					$payment_method=($this->input->post('payment_method',true) && !empty($this->input->post('payment_method',true)))?$this->input->post('payment_method',true):'COD';	
					$inserted_data=array(
						'user_id' => $logged_in_id,
						'address_id' => $address_id,
						'number_of_items'=>  $cart_details['cart_items'],
						'order_sub_total'=>  $cart_details['cart_total'],
						'tax_amount'=>  $cart_details['cart_items_tax'],
						'order_total'=>  $cart_details['grand_total_with_tax'],
						'shipping_name'=>  $new_address_details->full_name,
						'shipping_mobile_no'=>  $new_address_details->a_mobile_no,
						'shipping_address'=>  $new_address_details->a_address_one,
						'shipping_country'=>  $new_address_details->country_name,
						'shipping_state'=>  $new_address_details->state_name,
						'shipping_city'=>  $new_address_details->city_name,
						'shipping_post_code'=>  $new_address_details->a_post_code,
						'shipping_company_name' => $new_address_details->company_name,
						'order_status'=>  "Waiting",
						'device_type'=> "Website",
						'user_order_remark' => $this->input->post('user_order_remark',true),
						'payment_method'=>  $payment_method,
						//'payment_date'=>  date('Y-m-d H:i:s'),
						//'payment_status'=>  'NOT PAID',
						//'order_date'=>  date('Y-m-d H:i:s'),
					);
					if(isset($this->session->userdata('brijwasi_user_session_data')['ORDER_ID']) && !empty($this->session->userdata('brijwasi_user_session_data')['ORDER_ID'])){
						$where_conditions_o= array("id"=>$this->session->userdata('brijwasi_user_session_data')['ORDER_ID']);
						$last_inserted_id=$this->base_model->update_entry('brij_orders', $inserted_data ,$where_conditions_o);
						$order_id=$this->session->userdata('brijwasi_user_session_data')['ORDER_ID'];							
					}else{
						$order_id=$this->base_model->insert_entry('brij_orders',$inserted_data);
					}
				   if($order_id){
						 $session_data = array(
								'ACTION' => "ORDER_ID_GET",
								'ORDER_ID' => trim($order_id),
								'SHIPPING_ADDRESS' => trim($address_id),
							);
						// Add user data in session
						$this->session->set_userdata('brijwasi_user_session_data', $session_data);
						 $MERCHANT_ID=getSiteSettingValue(32);
						 $MERCHANT_KEY=getSiteSettingValue(33);
						 $MERCHANT_SALT=getSiteSettingValue(34);
						 $action=getSiteSettingValue(35);
						 $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
						 $where=array("id"=> $this->session->userdata('logged_in_brijwasi_user_data')['ID']);
						 $usersBilingAddress = $this->base_model->getOneRecordWithWhere("brij_users",$where ,"*");
						 $email     = $usersBilingAddress->email_id;
						 $mobile    = $usersBilingAddress->phone_no;
						 $firstName = $usersBilingAddress->name;
						 $lastName  = '';
						 $totalCost = $cart_details['grand_total_with_tax']; //FOR ACTUAL AMOUNT
						 //$totalCost = 1; //FOR TESTING AMOUNT
						 $hash      = '';
						 $productinfo = 'Order Number - '.$this->user_model->order_number_generator($order_id); 
						 $SUCCESS_URL=base_url("checkout/order_success/?order_id=".$order_id."&payment_type=".$payment_method);
						 $FAIL_URL=base_url("checkout/order_fail/?order_id=".$order_id."&payment_type=".$payment_method);
						 $CANCEL_URL=base_url("checkout/order_cancel/?order_id=".$order_id."&payment_type=".$payment_method);
						//Below is the required format need to hash it and send it across payumoney page. UDF means User Define Fields.
						$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
						$hash_string = $MERCHANT_KEY."|".$txnid."|".$totalCost."|".$productinfo."|".$firstName."|".$email."|||||||||||".$MERCHANT_SALT;
						$hash = strtolower(hash('sha512', $hash_string));
						$action = $action . '/_payment';				 
						 echo '<form action="'.$action.'" method="post" name="payuForm" id="payuForm" style="display: none">
						<input type="hidden" name="key" value="'. $MERCHANT_KEY.'" />
						<input type="hidden" name="hash" value="'.$hash.'"/>
						<input type="hidden" name="txnid" value="'.$txnid.'" />
						<input name="amount" type="hidden" value="'.$totalCost.'" />
						<input type="hidden" name="firstname" id="firstname" value="'.$firstName.'" />
						<input type="email" name="email" id="email" value="'.$email.'" />
						<input type="hidden" name="phone" value="'.$mobile.'" />
						<textarea name="productinfo">'.$productinfo.'</textarea>
						<input type="hidden" name="surl" value="'.$SUCCESS_URL.'" />
						<input type="hidden" name="furl" value="'.$FAIL_URL.'"/>
						<input type="hidden" name="curl" value="'.$CANCEL_URL.'"/>
						<input type="hidden" name="service_provider" value="payu_paisa"/>
						<input type="hidden" name="lastname" id="lastname" value="'.$lastName.'" />
						</form>
						<script type="text/javascript">
							var payuForm = document.forms.payuForm;
							payuForm.submit();
						</script>';
				 }
                 
				die("Payment Method Not Implemented. Please try COD!");
				break;					
				case "Debit Card":
					die("Payment Method Not Implemented. Please try COD!");
				break;
				case "NetBanking":
					//$bank_id=(isset($inserted_data['bank']))?$inserted_data['bank']:$inserted_data['other_bank'];
					die("Payment Method Not Implemented. Please try COD!");
				break;					
				default:
				die("Payment Method Does not select. Please try again!");
				break;
			}
		}
	}
	//order Mail for Customer
	public function orderMailCustomer($order_id=null)
	{
		$logged_in_id = $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		$query = $this->db->query("SELECT Users.name,Users.email_id,userAddresses.a_mobile_no,userAddresses.a_state_id, CONCAT(a_fname,' ',a_lname) AS full_name, CONCAT(a_address_one,',',States.state_name,',',Cities.city_name,'-',a_post_code) AS full_address,Orders.id,Orders.order_number,Orders.order_sub_total,Orders.tax_amount, Orders.number_of_items, Orders.order_total, Orders.order_date,Orders.payment_method FROM `brij_orders` as Orders inner join brij_user_shipping_addresses as userAddresses on Orders.address_id=userAddresses.id inner join brij_users as Users on Users.id=Orders.user_id left join brij_countries as Countries on Countries.id=userAddresses.a_country_id left join brij_states as States on States.id=userAddresses.a_state_id left join brij_cities as Cities on Cities.id=userAddresses.a_city_id where userAddresses.a_status=1 and Orders.id='$order_id' and Orders.order_status ='Pending'");
		$arrOrderShippingDetail = $query->row_object();
		//pr($arrOrderShippingDetail);
		$status=0;
		if(count($arrOrderShippingDetail)>0 && !empty($arrOrderShippingDetail)){
				$where_column['order_id =']=$order_id;
				$where_column['user_id =']=$logged_in_id;
				$where_column['order_item_status =']=1;
				$orderItems = $this->user_model->getOrderItems($where_column,$order_by='id DESC');			
				 //pr($orderItems);
				//die;
				  $base_url=base_url();
				  $LOGO_URL=$base_url.'uploads/site_images/medium/logo_2.png';
				  $orderItemsHtml="";
				  $total_item_price=0;			  
				  foreach($orderItems as $keys=>$items){
					$arrProducImagesData=getProductImage($items->product_id,$limit=1);
					//echo $sellerDetails->contact_state;
					 //pr($sellerDetails);
					$profilename = 'uploads/product_images/'.$arrProducImagesData[0]->images;
					$pro_file= '/uploads/no-image100x100.jpg';
					$pro_original_file= '/uploads/no-image400x400.jpg';
					if (file_exists($profilename) && !empty($arrProducImagesData[0]->images))
					{
						$pro_file=$base_url.'uploads/product_images/small/'.$arrProducImagesData[0]->images;
						$pro_original_file=$base_url.'uploads/product_images/'.$arrProducImagesData[0]->images;													
					}
					
					$PRODUCT_URL=$base_url.'products/product_details/?pro_id='.$items->product_id;				
									
					$orderItemsHtml .='<tr>
					<td style="border:solid 1px #cccccc; color:#333333;">
						<div><a href="'.$PRODUCT_URL.'"><img src="'.$pro_file.'" style="width:50px;" /></a></div>
					</td>
					<td style="border:solid 1px #cccccc;" ><p>'.$items->product_name.'('.$items->product_code.')</p><p class="seller_details" style="    font-size: 12px;
					margin-top: 4px;
					border-left: 2px solid #64ab23;
					padding-left: 5px;
					color: #64ab23;
					line-height: 17px;">
						<span><b>Category</b> '.$items->cat_name.'</span>
						</p></td>
					<td style="border:solid 1px #cccccc;"><img src="'.$base_url.'assets/img/rupees.png" alt="Rupees" style="float: left;margin-top: 2px;"> '.number_format($items->price).'</td>
					<td style="border:solid 1px #cccccc; color:#333333;"><p>'.$items->product_quantity.'</p></td>
					<td style="border:solid 1px #cccccc;"><strong><img src="'.$base_url.'assets/img/rupees.png" alt="Rupees" style="float: left;margin-top: 2px;"> '.number_format($items->product_price).'</strong></td>';
					$orderItemsHtml .='<td style="border:solid 1px #cccccc;">0%</td>';
					$orderItemsHtml .='</tr>'; 
				}
				$orderItemsHtml .='<tr>
					<td style="border:solid 1px #cccccc; color:#333333;" colspan="4" align="right">
						<strong>Sub Total</strong>
					</td>
					<td style="border:solid 1px #cccccc;" colspan="2"><strong><img src="'.$base_url.'assets/img/rupees.png" alt="Rupees" style="float: left;margin-top: 2px;"> '.number_format($arrOrderShippingDetail->order_sub_total).'</strong></td>
				</tr>
				<tr>
					<td style="border:solid 1px #cccccc; color:#333333;" colspan="4" align="right">
						<strong>Tax Amount</strong>
					</td>               
					<td style="border:solid 1px #cccccc;" colspan="2"><strong><img src="'.$base_url.'assets/img/rupees.png" alt="Rupees" style="float: left;margin-top: 2px;"> '.number_format($arrOrderShippingDetail->tax_amount).'</strong></td>
				</tr>';
			  
			$Email_From_Address=getSiteSettingValue(37);
			$Emails_From_Name=getSiteSettingValue(38); 
			 
			$order_date=date('m/d/Y',strtotime($arrOrderShippingDetail->order_date));
			//echo $orderItemsHtml;
			$template = file_get_contents(realpath(APPPATH)."/views/User/mail-templates/customer_order_template.html");
			$template = str_replace('{{LOGO_URL}}', $LOGO_URL, $template);
			$template = str_replace('{{NAME}}', $arrOrderShippingDetail->name, $template);
			$template = str_replace('{{FULL_NAME}}', $arrOrderShippingDetail->full_name, $template);
			$template = str_replace('{{FULL_ADDRESS}}', $arrOrderShippingDetail->full_address, $template);
			$template = str_replace('{{MOBILE}}', $arrOrderShippingDetail->a_mobile_no, $template);
			$template = str_replace('{{ORDER_NUMBER}}', $arrOrderShippingDetail->order_number, $template);
			$template = str_replace('{{ORDER_TOTAL}}', number_format($arrOrderShippingDetail->order_total), $template);
			$template = str_replace('{{NO_OF_ITEMS}}', $arrOrderShippingDetail->number_of_items, $template);
			$template = str_replace('{{ORDER_DATE}}', $order_date, $template);
			$template = str_replace('{{PAYMENT_METHOD}}', $arrOrderShippingDetail->payment_method, $template);
			$template = str_replace('{{ORDER_ITEMS}}', $orderItemsHtml, $template);
			$template = str_replace('{{ORDER_ITEMS_TOTALS}}', number_format($arrOrderShippingDetail->order_total), $template);
			$template = str_replace('{{BASE_URL}}',$base_url, $template);
			$template = str_replace('{{SUBJECT}}','Order Details for Order Number - '.$arrOrderShippingDetail->order_number, $template);
			//$template = str_replace('{{}}', $arrProData->id, $template);
			//echo $template;
			//die;
			$this->load->library('email');
			$config = Array(
				'mailtype' => 'html', // text
				'charset' => 'iso-8859-1',
				'newline' => '\r\n',
				'wordwrap' => TRUE
			);
			$res=false;
			try {
				$this->email->clear();
				$this->email->initialize($config);
				$this->email->from($Email_From_Address, $Emails_From_Name);
				$this->email->to(trim($arrOrderShippingDetail->email_id));
				$this->email->subject('Order Details for Order Number - '.$arrOrderShippingDetail->order_number);
				$this->email->message($template);
				if($this->email->send()){
                     $res=true;
                } else{					
					$res=false;
				}				
			} catch (Exception $e) {
				echo 'Exception : ',  $e->getMessage(), "\n";
			}						
			if($res){
				$status=1;
			}else{
				$status=0;
			}
        }			
		return $status;				
	}
	//order Mail For Admin
	public function orderMailAdmin($order_id=null)
	{
		$logged_in_id = $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		$query = $this->db->query("SELECT Users.name,Users.email_id,Users.phone_no,userAddresses.a_mobile_no,userAddresses.a_state_id, CONCAT(a_fname,' ',a_lname) AS full_name, CONCAT(a_address_one,',',States.state_name,',',Cities.city_name,'-',a_post_code) AS full_address,CONCAT(a_fname,' ',a_lname) AS full_name, CONCAT(address,',',States1.state_name,',',Cities1.city_name,'-',a_post_code,',',Countries1.country_name) AS customer_full_address,Orders.id,Orders.order_number, Orders.number_of_items, Orders.order_total, Orders.order_date,Orders.payment_method,Orders.order_sub_total,Orders.tax_amount FROM `brij_orders` as Orders inner join brij_user_shipping_addresses as userAddresses on Orders.address_id=userAddresses.id inner join brij_users as Users on Users.id=Orders.user_id left join brij_countries as Countries on Countries.id=userAddresses.a_country_id left join brij_states as States on States.id=userAddresses.a_state_id left join brij_cities as Cities on Cities.id=userAddresses.a_city_id left join brij_countries as Countries1 on Countries1.id=Users.country_id left join brij_states as States1 on States1.id=Users.state_id left join brij_cities as Cities1 on Cities1.id=Users.city_id where userAddresses.a_status=1 and Orders.id='$order_id' and Orders.order_status ='Pending'");
		$arrOrderShippingDetail = $query->row_object();
		//pr($arrOrderShippingDetail);
		//die;
		$status=0;
		if(count($arrOrderShippingDetail)>0 && !empty($arrOrderShippingDetail)){
				$where_column['order_id =']=$order_id;
				$where_column['user_id =']=$logged_in_id;
				$where_column['order_item_status =']=1;
				$orderItems = $this->user_model->getOrderItems($where_column,$order_by='id DESC');			
				 //pr($orderItems);
				//die;
				  $base_url=base_url();
				  $LOGO_URL=$base_url.'uploads/site_images/medium/logo_2.png';
				  $orderItemsHtml="";
				  $total_item_price=0;			  
				  foreach($orderItems as $keys=>$items){
					$arrProducImagesData=getProductImage($items->product_id,$limit=1);
					//echo $sellerDetails->contact_state;
					 //pr($sellerDetails);
					$profilename = 'uploads/product_images/'.$arrProducImagesData[0]->images;
					$pro_file= '/uploads/no-image100x100.jpg';
					$pro_original_file= '/uploads/no-image400x400.jpg';
					if (file_exists($profilename) && !empty($arrProducImagesData[0]->images))
					{
						$pro_file=$base_url.'uploads/product_images/small/'.$arrProducImagesData[0]->images;
						$pro_original_file=$base_url.'uploads/product_images/'.$arrProducImagesData[0]->images;													
					}
					
					$PRODUCT_URL=$base_url.'products/product_details/?pro_id='.$items->product_id;				
									
					$orderItemsHtml .='<tr>
					<td style="border:solid 1px #cccccc; color:#333333;">
						<div><a href="'.$PRODUCT_URL.'"><img src="'.$pro_file.'" style="width:50px;" /></a></div>
					</td>
					<td style="border:solid 1px #cccccc;" ><p>'.$items->product_name.'('.$items->product_code.')</p><p class="seller_details" style="    font-size: 12px;
					margin-top: 4px;
					border-left: 2px solid #64ab23;
					padding-left: 5px;
					color: #64ab23;
					line-height: 17px;">
						<span><b>Category</b> '.$items->cat_name.'</span>
						</p></td>
					<td style="border:solid 1px #cccccc;"><img src="'.$base_url.'assets/img/rupees.png" alt="Rupees" style="float: left;margin-top: 2px;"> '.number_format($items->price).'</td>
					<td style="border:solid 1px #cccccc; color:#333333;"><p>'.$items->product_quantity.'</p></td>
					<td style="border:solid 1px #cccccc;"><strong><img src="'.$base_url.'assets/img/rupees.png" alt="Rupees" style="float: left;margin-top: 2px;"> '.number_format($items->product_price).'</strong></td>';
					$orderItemsHtml .='<td style="border:solid 1px #cccccc;">0%</td>';
					$orderItemsHtml .='</tr>'; 
				}
				$orderItemsHtml .='<tr>
					<td style="border:solid 1px #cccccc; color:#333333;" colspan="4" align="right">
						<strong>Sub Total</strong>
					</td>
					<td style="border:solid 1px #cccccc;" colspan="2"><strong><img src="'.$base_url.'assets/img/rupees.png" alt="Rupees" style="float: left;margin-top: 2px;"> '.number_format($arrOrderShippingDetail->order_sub_total).'</strong></td>
				</tr>
				<tr>
					<td style="border:solid 1px #cccccc; color:#333333;" colspan="4" align="right">
						<strong>Tax Amount</strong>
					</td>               
					<td style="border:solid 1px #cccccc;" colspan="2"><strong><img src="'.$base_url.'assets/img/rupees.png" alt="Rupees" style="float: left;margin-top: 2px;"> '.number_format($arrOrderShippingDetail->tax_amount).'</strong></td>
				</tr>
				 ';			  
				$fromEmail = trim($arrOrderShippingDetail->email_id);
				$userName  = trim($arrOrderShippingDetail->name);
				
				$Email_From_Address=getSiteSettingValue(37);
				$Emails_From_Name=getSiteSettingValue(38);
				
				$Order_Email_Address=getSiteSettingValue(39);
				
				$order_date=date('m/d/Y',strtotime($arrOrderShippingDetail->order_date));
				//echo $orderItemsHtml;
				$template = file_get_contents(realpath(APPPATH)."/views/User/mail-templates/admin_order_template.html");
				$template = str_replace('{{LOGO_URL}}', $LOGO_URL, $template);
				$template = str_replace('{{NAME}}', $Emails_From_Name, $template);
				$template = str_replace('{{FULL_NAME}}', $arrOrderShippingDetail->full_name, $template);
				$template = str_replace('{{FULL_ADDRESS}}', $arrOrderShippingDetail->full_address, $template);
				$template = str_replace('{{MOBILE}}', $arrOrderShippingDetail->a_mobile_no, $template);
				$template = str_replace('{{C_FULL_NAME}}', $arrOrderShippingDetail->name, $template);
				$template = str_replace('{{C_FULL_ADDRESS}}', $arrOrderShippingDetail->customer_full_address, $template);
				$template = str_replace('{{C_EMAIL}}', $arrOrderShippingDetail->email_id, $template);
				$template = str_replace('{{C_MOBILE}}', $arrOrderShippingDetail->phone_no, $template);
				$template = str_replace('{{ORDER_NUMBER}}', $arrOrderShippingDetail->order_number, $template);
				$template = str_replace('{{ORDER_TOTAL}}', number_format($arrOrderShippingDetail->order_total), $template);
				$template = str_replace('{{NO_OF_ITEMS}}', $arrOrderShippingDetail->number_of_items, $template);
				$template = str_replace('{{ORDER_DATE}}', $order_date, $template);
				$template = str_replace('{{PAYMENT_METHOD}}', $arrOrderShippingDetail->payment_method, $template);
				$template = str_replace('{{ORDER_ITEMS}}', $orderItemsHtml, $template);
				$template = str_replace('{{ORDER_ITEMS_TOTALS}}', number_format($arrOrderShippingDetail->order_total), $template);
				$template = str_replace('{{BASE_URL}}',$base_url, $template);
				$template = str_replace('{{SUBJECT}}','Order Details for Order Number - '.$arrOrderShippingDetail->order_number, $template);
				//$template = str_replace('{{}}', $arrProData->id, $template);
				//echo $sellerDeatils->business_emailid."<br/>";
				//echo $template;
				//die;
				$this->load->library('email');
				$config = Array(
				'mailtype' => 'html', // text
				'charset' => 'iso-8859-1',
				'newline' => '\r\n',
				'wordwrap' => TRUE
				);
				$res=false;
				try {
					    $this->email->clear();
						$this->email->initialize($config);
						$this->email->from($fromEmail,  $userName);
						$this->email->to($Order_Email_Address);
						$this->email->subject('Order Details for Order Number - '.$arrOrderShippingDetail->order_number);
						$this->email->message($template);
						if($this->email->send()){
							 $res=true;
						} else{					
							$res=false;
						}
				} catch (Exception $e) {
					echo 'Exception : ',  $e->getMessage(), "\n";
				}						
				if($res){
					$status=1;
				}else{
					$status=0;
				}			  
			}			
		return $status;	
	}
	
	public function order_success() 
	{
		//print_r($this->input->post());
		//die;
		$status = $this->input->post("status");
		$firstname = $this->input->post("firstname");
		$amount = $this->input->post("amount");
		$txnid = $this->input->post("txnid");
		$posted_hash = $this->input->post("hash");
		$key = $this->input->post("key");
		$productinfo = $this->input->post("productinfo");
		$email = $this->input->post("email");
		$salt = getSiteSettingValue(34);
        $order_id = $this->input->get('order_id',true);
		if ($this->input->post("additionalCharges")) {
			$additionalCharges = $this->input->post("additionalCharges");
			$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		} else {

			$retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		}
		$hash = hash("sha512", $retHashSeq);

		if ($hash != $posted_hash) {
			$data['msg'] = "Invalid Transaction. Please try again";
		} else {
			$data['msg'] = "<h3>Thank You. Your order status is " . $status . ".</h3>";
			$data['msg'] .= "<h4>Your Transaction ID for this transaction is " . $txnid . ".</h4>";
			$data['msg'] .= "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";
			
			if(isset($order_id) && !empty($order_id)){
				$payment_type = $this->input->get('payment_type',true);
                $payment_method=(isset($payment_type))?$payment_type:'PayUmoney';
                $order_data=array();				
				$order_data["payment_method"] = $payment_method;
				$order_data["order_date"] = date('Y-m-d H:i:s'); 						
				$order_data["order_status"] = "Pending";
				$order_data["payment_id"]  = $this->input->post('payuMoneyId');
				$order_data["transaction_id"] = $this->input->post('txnid');
				$order_data["payment_status"] = strtoupper($this->input->post('field9'));
				$order_data["payment_date"] = ($this->input->post('addedon') && !empty($this->input->post('addedon')))?$this->input->post('addedon'):date('Y-m-d H:i:s');
				$order_data["response_status"] = $this->input->post('status');
				$order_data["response_string"] = json_encode($this->input->post());
				
				$where_conditions_o=array("id"=>$order_id);
				$updated=$this->base_model->update_entry('brij_orders', $order_data ,$where_conditions_o);
				if($updated){
					 //order number update
					$order_number["order_number"] = $this->user_model->order_number_generator($order_id);
					$where_conditions_o=array("id"=>$order_id);
					$last_inserted_id=$this->base_model->update_entry('brij_orders', $order_number ,$where_conditions_o);
					//order id update on order items
					$order_items["order_id"] = $order_id;						
					$where_conditions_ois= array("order_session_id"=>$this->session->session_id);
					if($this->session->userdata('logged_in_brijwasi_user_data')['ID'] && !empty($this->session->userdata('logged_in_brijwasi_user_data')['ID'])){
						$where_conditions_ois= array("user_id"=>$this->session->userdata('logged_in_brijwasi_user_data')['ID']);							
					}
					$where_conditions_ois["order_item_status"] = 0;
					$last_inserted_id=$this->base_model->update_entry('brij_order_items', $order_items ,$where_conditions_ois);
					//order items status update
					$order_item["order_item_status"] = 1;
					$where_conditions_oi=array("order_id"=>$order_id);
					$last_inserted_id=$this->base_model->update_entry('brij_order_items', $order_item ,$where_conditions_oi);

					$query = $this->db->query("SELECT Products.id as pro_id,OrderItems.* FROM `brij_products` as Products inner join brij_order_items as OrderItems on Products.id=OrderItems.product_id where Products.status=1 and OrderItems.order_item_status=1 and OrderItems.order_id='$order_id'");
					$cartItem = $query->result();
					foreach ($cartItem as $cart) {
						$productId = $cart->product_id;
						$qty =$cart->product_quantity;
						/*$stmt =$this->db->query("UPDATE `brij_products` SET `quantity` = `quantity` - $qty 
								WHERE `id` = $productId");*/
					}
					//$stmt = $this->db->query("UPDATE brij_products SET stock_availability=0 WHERE quantity=0 and stock_availability !=0");
					//$stmt = $this->db->query("UPDATE brij_products SET stock_availability=1 WHERE quantity>0 and stock_availability !=1");
					$SITE_EMAIL_NOTIFICATION=getSiteSettingValue(25);
					$custMail = $this->orderMailCustomer($order_id);
					$adminMail=1;
					if($SITE_EMAIL_NOTIFICATION == 1){
					 $adminMail = $this->orderMailAdmin($order_id);
					}
					//unset order data
                    $this->session->unset_userdata('brijwasi_user_session_data');					
				}
			}
		}
		
		$order_id = $this->input->get('order_id',true);
		
		$data['orderDetails']= $this->base_model->getOneRecord("brij_orders","id", $order_id, "id,order_number");
		if(empty($data['orderDetails']) && count($data['orderDetails']) <= 0){
			redirect("/");
		}
		
		$this->session->set_flashdata('payment_success', $data['msg']);
		
		$data['title'] = "Order Confirmation Message";
        $data['site_description'] = getSiteSettingValue(3);
        $data['site_keyword'] = getSiteSettingValue(4);			
	    $this->load->view('include/header',$data);
        $this->load->view('include/menu',$data);
	    $this->load->view('Checkout/payment-success',$data);
	    $this->load->view('include/footer',$data);
	}
	
	public function order_fail() 
	{
		//print_r($this->input->post());
		//die;
		$status = $this->input->post("status");
		$firstname = $this->input->post("firstname");
		$amount = $this->input->post("amount");
		$txnid = $this->input->post("txnid");
		$posted_hash = $this->input->post("hash");
		$key = $this->input->post("key");
		$productinfo = $this->input->post("productinfo");
		$email = $this->input->post("email");
		$salt = getSiteSettingValue(34);
		If ($this->input->post("additionalCharges")) {
			$additionalCharges = $this->input->post("additionalCharges");
			$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		} else {
			$retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		}
		$hash = hash("sha512", $retHashSeq);
		if ($hash != $posted_hash) {
			$data['msg'] = "Invalid Transaction. Please try again";
		} else {
			$data['msg'] = "<h3>Your order status is " . $status . ".</h3>";
			$data['msg'] .= "<h4>Your transaction id for this transaction is " . $txnid . ". You may try making the payment by clicking the link below.</h4>";
		}
		
		$order_id = $this->input->get('order_id',true);
		
		$data['orderDetails']= $this->base_model->getOneRecord("brij_orders","id", $order_id, "id,order_number");
		if(empty($data['orderDetails']) && count($data['orderDetails']) <= 0){
			redirect("/");
		}
		
		$data['msg'] .= '<p>Try Again</p>';
		$this->session->set_flashdata('payment_error', $data['msg']);
		redirect("checkout/payment");
	}
	
	public function order_cancel()
	{
		$order_id = $this->input->get('order_id',true);
		
		$data['orderDetails']= $this->base_model->getOneRecord("brij_orders","id", $order_id, "id,order_number");
		if(empty($data['orderDetails']) && count($data['orderDetails']) <= 0){
			redirect("/");
		}
		
		$data['msg']='Your Order could not be placed. Please try again!';
		
		$this->session->set_flashdata('payment_error', $data['msg']);
		redirect("checkout/payment");
	}
	
	public function payment_success()
	{
		$order_id = $this->input->get('order_id',true);
		
		$data['orderDetails']= $this->base_model->getOneRecord("brij_orders","id", $order_id, "id,order_number");
		if(empty($data['orderDetails']) && count($data['orderDetails']) <= 0){
			redirect("/");
		}
		
	    $data['title'] = "Order Confirmation Message";
        $data['site_description'] = getSiteSettingValue(3);
        $data['site_keyword'] = getSiteSettingValue(4);	
        $this->session->unset_userdata('brijwasi_user_session_data');		
	    $this->load->view('include/header',$data);
        $this->load->view('include/menu',$data);
	    $this->load->view('Checkout/payment-success',$data);
	    $this->load->view('include/footer',$data);	
	}
}