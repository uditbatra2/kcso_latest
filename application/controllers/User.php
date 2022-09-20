<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
	}

	//Method to generate a unique api key every time
	private function _generateApiKey(){
		return md5(uniqid(rand(), true));
	}
	
	//Method to generate a random number every time
	  private function _generateRandomNumber($length=10)
	  {
		$char = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$random_num = '';
		for ($i=0; $i<$length; $i++)
		{
		  $random_num .= $char[rand(0, strlen($char) - 1)];
		}
		return $random_num;
	  }
	
	//Method to generate a unique customer id number every time
	private function _generateCustomerDentificationNumber($last_insert_user_id,$state_id=''){
		$unique_code = 'CUST-00000'.$last_insert_user_id;
		if(isset($state_id) && !empty($state_id)){
			$stateData = $this->base_model->getOneRecord("brij_states","id", $state_id, "short_name");
			$where_conditions = array("id"=>$last_insert_user_id);
			$unique_code = 'CUST-00000'.$last_insert_user_id.'-'.$stateData->short_name;
		}
		$update_data = array('unique_code'=> $unique_code);
		$last_inserted_id=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions);

	}
	
	public function registration()
	{
		if($this->user_model->check_user_logged()=== true){
			redirect(base_url().'user/account');
		}
		
		if($this->input->method() === 'post'){
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
			$date = date("Y-m-d H:i:s");
			$user_api_key=$this->_generateApiKey();
			$is_subscribed = $this->input->post('is_subscribed', TRUE);
			$is_subscribed = (isset($is_subscribed) && !empty($is_subscribed))?$is_subscribed:0;
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))){
				/* $uDetails = $this->base_model->getOneRecord("brij_users","id", $this->input->post('id', TRUE), "*");
				$update_data = array(
					'name' => $this->input->post('firstname', TRUE),
					'email_id' => $this->input->post('email', TRUE),
					'address' => $this->input->post('address', TRUE),
					'phone_no'=>  $this->input->post('mobile_no', TRUE),
					'country_id'=> 101,
					'is_subscribe_newletters'=>  $is_subscribed,
					'date_updated'=>  $date,
				);
				if($this->input->post('password', TRUE) && !empty($this->input->post('password', TRUE))){
				$update_data['password']= md5($this->input->post('password', TRUE));
				}
				$where_conditions=array("id"=>$this->input->post('id', TRUE));
				$last_inserted_id=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions); */
				//$this->_generateCustomerDentificationNumber($this->input->post('id', TRUE));
			}else{
				$uDetails = $this->base_model->getOneRecord("brij_users","email_id", $this->input->post('email', TRUE), "*");
				if(empty($uDetails) && count($uDetails) <= 0){
					$uPhoneDetails = $this->base_model->getOneRecord("brij_users","phone_no", $this->input->post('mobile_no', TRUE), "*");
						if(empty($uPhoneDetails) && count($uPhoneDetails) <= 0){
								$insert_data = array(
									'name' => $this->input->post('firstname', TRUE),
									'email_id' => $this->input->post('email', TRUE),
									'address' => $this->input->post('address', TRUE),
									'phone_no'=>  $this->input->post('mobile_no', TRUE),
									'is_subscribe_newletters'=>  $is_subscribed,
									'country_id'=> 101,
									'pin_code'=>  $this->input->post('postcode', TRUE),
									'city_id'=> $this->input->post('city', TRUE),
									'state_id'=> $this->input->post('region_id', TRUE),
									'status'=>  1,
									'password' => md5($this->input->post('password', TRUE)),
									'api_key'=>$user_api_key,
									'date_added'=>  $date,
								);
								$last_inserted_id=$this->base_model->insert_entry('brij_users',$insert_data);
								if ($last_inserted_id){
									$this->_generateCustomerDentificationNumber($last_inserted_id);
									$accountUrl=base_url("user/account");
									$users = $this->base_model->getOneRecord("brij_users","id", $last_inserted_id, "*");
									$session_data = array(
										'ID' => $users->id,
										'USERNAME' => $users->name,
										'EMAIL' => $users->email_id,
										'PHONENUMBER' => $users->phone_no,
									);
									// Add user data in session
									$this->session->set_userdata('logged_in_brijwasi_user_data', $session_data);
									$response = array(
									'redirect_url' => $accountUrl,
									'message' => "Your registration has been done successfully. Please wait to redirecting page...",
									'status'=>1,
									);
                                    $this->session->set_flashdata('user_success', $response['message']);
									//user account url
									redirect($accountUrl);
								}else{
									$response = array(
									'message' => "Your registration does not work.Please try again later!",
									'status'=>0,
									);
                                    $this->session->set_flashdata('user_error', $response['message']);
									if ($this->agent->referrer()){
										//redirect to some function
										redirect($this->agent->referrer());
									}else{
										redirect("user/registration");
									}									
								}
						}else{	
						$response = array(
						'message' => "This Mobile No is already taken! Try another.",
						'status'=>0,
						);

                        $this->session->set_flashdata('user_error', $response['message']);
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("user/registration");
						}													
					}
				}else{
					$response = array(
						'message' => "This Email ID is already taken! Try another.",
						'status'=>0,
					);

					$this->session->set_flashdata('user_error', $response['message']);
					if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("user/registration");
					}
					
				}
			}
			$this->session->set_flashdata('user_error', 'User does not saved. Please try again!');
			if ($this->agent->referrer()){
				//redirect to some function
				redirect($this->agent->referrer());
			}else{
				redirect("user/registration");
			}
		}
		$page=$this->input->get('page', TRUE);
		$page = (isset($page) && !empty($page))?'checkout-page':'';
		$data['do'] = $page;
		
		$condition_state['id']= 38;
		$condition_state['status']= 1;
		$data['statList']=$this->base_model->getAllRows('brij_states','id DESC',$condition_state);
		
		$condition_city['id']= 5022;
		$condition_city['status']= 1;
		$data['cityList']=$this->base_model->getAllRows('brij_cities','id DESC',$condition_city);
		
		$data['title'] = "User Registration";
		$data['site_description'] = getSiteSettingValue(3);
		$data['site_keyword'] = getSiteSettingValue(4);
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);
		$this->load->view('User/registration',$data);
		$this->load->view('include/footer',$data);
	}
	
	public function login()
	{
		if($this->user_model->check_user_logged()=== true){
			redirect(base_url().'user/account');
		}
		$data['title'] = "User Login";
		$data['site_description'] = getSiteSettingValue(3);
		$data['site_keyword'] = getSiteSettingValue(4);
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);
		$this->load->view('User/login',$data);
		$this->load->view('include/footer',$data);	
	}
	//User Dashboard
	public function account()
	{
		if($this->user_model->check_user_logged()===false){
			redirect(base_url().'user/login');
		}
		$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		$where=array("id"=> $logged_in_id);
		$data['userDetails'] = $this->base_model->getOneRecordWithWhere("brij_users",$where,"*");
		
		$data['userDefaultShippingAddressDetails'] = $this->user_model->getShippingAddressDefaultByUser($logged_in_id);
		
		$data['title'] = "My Account";
		$data['site_description'] = getSiteSettingValue(3);
		$data['site_keyword'] = getSiteSettingValue(4);
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);
		$this->load->view('User/account',$data);
		$this->load->view('include/user-left-menu',$data);
		$this->load->view('include/footer',$data);	
	}
	public function account_edit()
	{		
		if($this->user_model->check_user_logged()===false){
			redirect(base_url().'user/login');
		}

		$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		$accountUrl='';
		if($this->input->method() === 'post'){
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
			$date = date("Y-m-d H:i:s");
			$user_api_key=$this->_generateApiKey();
			$change_password = $this->input->post('change_password', TRUE);
			$change_password = (isset($change_password) && !empty($change_password))?$change_password:0;
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))){
				$usDetails = $this->base_model->getOneRecord("brij_users","id", $this->input->post('id', TRUE), "*");				
				$where=array("id !="=> $logged_in_id,'email_id'=> $this->input->post('email', TRUE));
				$uDetails = $this->base_model->getOneRecordWithWhere("brij_users",$where,"*");
				if(empty($uDetails) && count($uDetails) <= 0){
					$where=array("id !="=> $logged_in_id,'phone_no'=> $this->input->post('phonenumber', TRUE));
					$uPhoneDetails = $this->base_model->getOneRecordWithWhere("brij_users",$where,"*");
				   if(empty($uPhoneDetails) && count($uPhoneDetails) <= 0){
					$update_data = array(
						'name' => $this->input->post('firstname', TRUE),
						'email_id' => $this->input->post('email', TRUE),
						'address' => $this->input->post('address', TRUE),
						'phone_no'=>  $this->input->post('phonenumber', TRUE),
						'pin_code'=>  $this->input->post('postcode', TRUE),
						'country_id'=> 101,
						'city_id'=> $this->input->post('city', TRUE),
						'state_id'=> $this->input->post('region_id', TRUE),
						'date_updated'=>  $date,
					);
					if($this->input->post('password', TRUE) && !empty($this->input->post('password', TRUE)) && $change_password== '1'){					 
					 $where=array("id ="=> $logged_in_id,'password'=> md5($this->input->post('current_password', TRUE)));
					 $uPDetails = $this->base_model->getOneRecordWithWhere("brij_users",$where,"*");
                     if(!empty($uPDetails) && count($uPDetails) > 0){					 
						$update_data['password']= md5($this->input->post('password', TRUE));
					 }else{
						 $response = array(
							'redirect_url' => $accountUrl,
							'message' => "Your Current Password does not match in our databse. Please try again!",
							'status'=>1,
							);
						$this->session->set_flashdata('user_error', $response['message']);
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("user/account");
						}
						exit();
					 }
					}
					$where_conditions=array("id"=>$this->input->post('id', TRUE));
					$last_inserted_id=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions);
					$this->_generateCustomerDentificationNumber($this->input->post('id', TRUE), $this->input->post('region_id', TRUE));
					$response = array(
							'redirect_url' => $accountUrl,
							'message' => "Your Account Information has been updated successfully.",
							'status'=>1,
							);
							$this->session->set_flashdata('user_success', $response['message']);
							if ($this->agent->referrer()){
								//redirect to some function
								redirect($this->agent->referrer());
							}else{
								redirect("user/account");
							}
					}else{
						
						$response = array(
						'message' => "This Mobile No is already taken! Try another.",
						'status'=>0,
						);

                        $this->session->set_flashdata('user_error', $response['message']);
						/* if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("user/account");
						}	 */					
					}				
				}else{
					$response = array(
						'message' => "This Email ID is already taken! Try another.",
						'status'=>0,
					);
					$this->session->set_flashdata('user_error', $response['message']);
					/* if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("user/account");
					} */
				}
				//$this->_generateCustomerDentificationNumber($this->input->post('id', TRUE));
			}
			$this->session->set_flashdata('user_error', 'User does not saved. Please try again!');
			if ($this->agent->referrer()){
				//redirect to some function
				redirect($this->agent->referrer());
			}else{
				redirect("user/account");
			}
		}
		
		$data['changepass']=trim($this->input->get('changepass', TRUE));
		
		$where=array("id"=> $logged_in_id);
		$data['userDetails'] = $this->base_model->getOneRecordWithWhere("brij_users",$where,"*");
		
		
		$condition_state['id']= 38;
		$condition_state['status']= 1;
		$data['statList']=$this->base_model->getAllRows('brij_states','id DESC',$condition_state);
		
		$condition_city['id']= 5022;
		$condition_city['status']= 1;
		$data['cityList']=$this->base_model->getAllRows('brij_cities','id DESC',$condition_city);
		
		$data['title'] = "Account Information";
		$data['site_description'] = getSiteSettingValue(3);
		$data['site_keyword'] = getSiteSettingValue(4);
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);
		$this->load->view('User/account-edit',$data);
		$this->load->view('include/user-left-menu',$data);
		$this->load->view('include/footer',$data);
	}
	//Address Book
	public function address()
	{
		
		if($this->user_model->check_user_logged()===false){
			redirect(base_url().'user/login');
		}

		$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];		
		$data['userDefaultShippingAddressDetails'] = $this->user_model->getShippingAddressDefaultByUser($logged_in_id);
		
		$data['allShippingAddressList']=$this->user_model->getAllShippingAddress($logged_in_id);
		
		$do=trim($this->input->get('do', TRUE));
		
		switch($do){			
			case "edit":
			$address_id=trim($this->input->get('address_id', TRUE));
			$where=array("id"=> $address_id,'user_id'=> $logged_in_id);
			$data['shippingAddressDetails'] = $this->base_model->getOneRecordWithWhere("brij_user_shipping_addresses",$where,"*");
			break;
			
			case "add":
			
			break;			
		}
		
		$condition_state['id']= 38;
		$condition_state['status']= 1;
		$data['statList']=$this->base_model->getAllRows('brij_states','id DESC',$condition_state);
		
		$condition_city['id']= 5022;
		$condition_city['status']= 1;
		$data['cityList']=$this->base_model->getAllRows('brij_cities','id DESC',$condition_city);
		
		$data['title'] = "Address Book";
		$data['site_description'] = getSiteSettingValue(3);
		$data['site_keyword'] = getSiteSettingValue(4);
		$data['do'] = $do;
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);
		$this->load->view('User/address',$data);
		$this->load->view('include/user-left-menu',$data);
		$this->load->view('include/footer',$data);
		
	}
	
	//My order list Section
	public function my_orders()
	{
		if($this->user_model->check_user_logged()===false){
			redirect(base_url().'user/login');
		}
		
		$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		$search_criteria=array();

		$serach_query=trim($this->input->get('serach-query', TRUE));
		$status=$this->input->get('status', TRUE);

		if(!isset($serach_query) && !isset($status) && empty($status) && empty($serach_query)){
			
		}
		$serach_query=(isset($serach_query) && $serach_query != '')?$serach_query:"";
		$status=(isset($status) && $status != '')?$status:"";
		
		if(isset($status) && $status != ''){
			$search_criteria['order_status =']= $status;
		}

		if(isset($serach_query) && $serach_query != ''){
			$search_criteria['order_number LIKE']= '%'.$serach_query.'%';
		}
        $search_criteria['order_status !=']= 'Waiting';
		$search_criteria['user_id =']= $logged_in_id;
		$select_column_name="id,order_number,shipping_name,order_total,order_status,order_date,payment_status";
		$data['orderList'] = $this->admin_model->getSearch('brij_orders',$order_by='id DESC',$search_criteria,'','',$select_column_name);
		
		$data['title'] = "My Orders";
		$data['site_description'] = getSiteSettingValue(3);
		$data['site_keyword'] = getSiteSettingValue(4);
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);
		$this->load->view('User/my-orders',$data);
		$this->load->view('include/user-left-menu',$data);
		$this->load->view('include/footer',$data);	
	}	
	//order view
	public function order_view()
	{
		
		if($this->user_model->check_user_logged()===false){
			redirect(base_url().'user/login');
		}
		
		$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		$search_criteria=array();
		$order_id=trim($this->input->get('order_id', TRUE));
		
		$where=array("user_id"=> $logged_in_id,'id'=> $order_id);
		$data['orderDetails'] = $this->base_model->getOneRecordWithWhere("brij_orders",$where,"*");
		
		$where=array("id"=> $logged_in_id);
		$data['usersBilingAddress'] = $this->base_model->getOneRecordWithWhere("brij_users",$where ,"*");
		
		$where_column['order_id =']=$order_id;
		$where_column['user_id =']=$logged_in_id;
		$where_column['order_item_status =']=1;
		$data['orderItems'] = $this->user_model->getOrderItems($where_column,$order_by='id DESC');
		
		$data['title'] = "Order # ".$data['orderDetails']->order_number;
		$data['site_description'] = getSiteSettingValue(3);
		$data['site_keyword'] = getSiteSettingValue(4);
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);
		$this->load->view('User/order-view',$data);
		$this->load->view('include/user-left-menu',$data);
		$this->load->view('include/footer',$data);	
	}
	
	//Wish List Section Start
	public function wishlist()
	{
		if($this->user_model->check_user_logged()===false){
			// Removing session data
			if($this->input->get('do', TRUE) && $this->input->get('wishlist_id', TRUE)){
				$session_data = array(
					'ACTION' => trim($this->input->get('do', TRUE)),
					'WISHLIST_ID' => trim($this->input->get('wishlist_id', TRUE)),
				);
			if(isset($this->session->userdata('brijwasi_user_session_data')['ORDER_ID']) && !empty($this->session->userdata('brijwasi_user_session_data')['ORDER_ID'])){
				$session_data['ORDER_ID']=$this->session->userdata('brijwasi_user_session_data')['ORDER_ID'];							
			}
			// Add user data in session
			$this->session->set_userdata('brijwasi_user_session_data', $session_data);
			}
			redirect(base_url().'user/login');
		}
        
		if($this->input->method() === 'get'){
			$do=trim($this->input->get('do', TRUE));
			$wishlist_id=trim($this->input->get('wishlist_id', TRUE));
            $logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
			$date = date("Y-m-d H:i:s");
			switch($do){
				case 'add-wishlist':
				$where=array("user_id"=> $logged_in_id,'product_id'=>$wishlist_id);
				$userWishlistData = $this->base_model->getOneRecordWithWhere("brij_wishlist",$where,"id");
				if(empty($userWishlistData) && count($userWishlistData) <=0 ) {
				$insert_data = array(
					'user_id' =>  $logged_in_id,
					'product_id' => $wishlist_id,
					'date_added'=>  $date,
				);
				$last_inserted_id=$this->base_model->insert_entry('brij_wishlist',$insert_data);
				$this->session->set_flashdata('wishlist_success','Wish List has been added successfully.');
				redirect("user/wishlist");
				}else{
					$this->session->set_flashdata('product_cart_error','This product already in the Wish List.');
					if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("user/wishlist");
					}
				}
				break;		
			}			
		}
		
		$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		$data["wishListData"]=$this->user_model->getWishListItem($where_column=array("user_id"=>$logged_in_id),$order_by='id desc');
		//echo "<pre>";print_r($data["wishListData"]);
		
		$data['title'] = "My Wish List";
		$data['site_description'] = getSiteSettingValue(3);
		$data['site_keyword'] = getSiteSettingValue(4);
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);
		$this->load->view('User/wish-list',$data);
		$this->load->view('include/user-left-menu',$data);
		$this->load->view('include/footer',$data);
	}
	
	public function delete_wishlist_item()
	{
	   if($this->user_model->check_user_logged()=== false){
			redirect(base_url().'user/login');
		}
		if($this->input->method() === 'get'){
			$where_conditions_a=array('id'=>$this->input->get('wishlist_id', TRUE));
			if ($this->session->userdata('logged_in_brijwasi_user_data')['ID']) {						
				$where_conditions_a['user_id']= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];					
			}
			$deleted=$this->base_model->deleteWithWhereConditions('brij_wishlist',$where_conditions_a);
			if($deleted){
				$this->session->set_flashdata('product_cart_success', 'Wish List Item has been deleted Successfully.');
				if ($this->agent->referrer()){
					//redirect to some function
					redirect($this->agent->referrer());
				}else{
					redirect("user/wishlist");
				}
			}
		}		
	}
	//Wish List Section End
	
	//User Shipping Address Section End
	public function set_shipping_address()
	{
		if($this->user_model->check_user_logged()=== false){
			redirect(base_url().'user/login');
		}
		if($this->input->method() === 'post'){
			 //echo "<pre>";print_r($_POST);
			 //die;
			 // Removing session data
			if($this->input->post('do', TRUE) && $this->input->post('shipping_address_id', TRUE)){
				$session_data = array(
					'ACTION' => trim($this->input->post('do', TRUE)),
					'SHIPPING_ADDRESS' => trim($this->input->post('shipping_address_id', TRUE)),
				);
			if(isset($this->session->userdata('brijwasi_user_session_data')['ORDER_ID']) && !empty($this->session->userdata('brijwasi_user_session_data')['ORDER_ID'])){
				$session_data['ORDER_ID']=$this->session->userdata('brijwasi_user_session_data')['ORDER_ID'];							
			}
			// Add user data in session
			$this->session->set_userdata('brijwasi_user_session_data', $session_data);
			$this->session->set_flashdata('user_success', 'Your Shipping Address has been added in this order.');
			$paymentUrl=base_url("checkout/payment");
			redirect($paymentUrl);
			}
		}
	}
	
	public function delete_shipping_address()
	{
		if($this->user_model->check_user_logged()=== false){
			redirect(base_url().'user/login');
		}
		if($this->input->method() === 'get'){
			$where_conditions_a=array('id'=>$this->input->get('address_id', TRUE));
			if ($this->session->userdata('logged_in_brijwasi_user_data')['ID']) {						
				$where_conditions_a['user_id']= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];					
			}
			$deleted=$this->base_model->deleteWithWhereConditions('brij_user_shipping_addresses',$where_conditions_a);
			if($deleted){
				$this->session->set_flashdata('user_success', 'Address has been deleted Successfully.');
				if ($this->agent->referrer()){
					//redirect to some function
					redirect($this->agent->referrer());
				}else{
					redirect("user/address");
				}
			}
		}	
	}
	
	public function add_shipping_address()
	{
		if($this->user_model->check_user_logged()=== false){
			redirect(base_url().'user/login');
		}
		
		if($this->input->method() === 'post'){
			/* echo "<pre>";print_r($_POST);
			die; */
			$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
			$date = date("Y-m-d H:i:s");
			$user_api_key=$this->_generateApiKey();
			$is_subscribed = $this->input->post('is_subscribed', TRUE);
			$is_subscribed = (isset($is_subscribed) && !empty($is_subscribed))?$is_subscribed:0;
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))){
				//$uSaDetails = $this->base_model->getOneRecord("brij_user_shipping_addresses","id", $this->input->post('id', TRUE), "*");
				$where=array("user_id"=> $logged_in_id,'a_post_code'=> $this->input->post('postcode', TRUE),"id !="=>$this->input->post('id', TRUE));
				$uPostDetails = $this->base_model->getOneRecordWithWhere("brij_user_shipping_addresses",$where,"*");
				//$uPostDetails = $this->base_model->getOneRecord("brij_user_shipping_addresses","a_post_code", $this->input->post('postcode', TRUE) , "*");
				if(empty($uPostDetails) && count($uPostDetails) <= 0){
					$where=array("user_id"=> $logged_in_id,'a_mobile_no'=> $this->input->post('phonenumber', TRUE),"id !="=>$this->input->post('id', TRUE));
					$uPhoneSDetails = $this->base_model->getOneRecordWithWhere("brij_user_shipping_addresses",$where,"*");
					//$uPhoneSDetails = $this->base_model->getOneRecord("brij_user_shipping_addresses","a_mobile_no", $this->input->post('phonenumber', TRUE), "*");
						if(empty($uPhoneSDetails) && count($uPhoneSDetails) <= 0){
							$update_data = array(
								'a_fname' => $this->input->post('firstname', TRUE),
								'a_lname' => $this->input->post('lastname', TRUE),
								'a_mobile_no' => $this->input->post('phonenumber', TRUE),
								'company_name' => $this->input->post('company', TRUE),
								'a_address_one' => $this->input->post('street', TRUE)[0],
								'a_country_id' => 101,
								'a_state_id'=> $this->input->post('region_id', TRUE),
								'a_city_id'=> $this->input->post('city', TRUE),
								'a_post_code'=>  $this->input->post('postcode', TRUE),
								'date_updated'=>  $date,
							);
							
							if($this->input->post('is_default', TRUE) && !empty($this->input->post('is_default', TRUE))){
								$where_conditionst=array('user_id' =>$logged_in_id);
								$update_data_default=array("set_default"=>0);
								$update=$this->base_model->update_entry('brij_user_shipping_addresses', $update_data_default ,$where_conditionst);
								$update_data["set_default"]=$this->input->post('is_default', TRUE);
							}
							
							$where_conditions=array("id"=>$this->input->post('id', TRUE));
							$last_inserted_id=$this->base_model->update_entry('brij_user_shipping_addresses', $update_data ,$where_conditions);
							if ($last_inserted_id){
									$action=$this->input->post('action', TRUE);
									switch($action){										
										case "shippingAddressFormNew";										
										$session_data = array(
											'ACTION' => trim($this->input->post('do', TRUE)),
											'SHIPPING_ADDRESS' => trim($this->input->post('id', TRUE)),
										);
										if(isset($this->session->userdata('brijwasi_user_session_data')['ORDER_ID']) && !empty($this->session->userdata('brijwasi_user_session_data')['ORDER_ID'])){
											$session_data['ORDER_ID']=$this->session->userdata('brijwasi_user_session_data')['ORDER_ID'];							
										}
										// Add user data in session
										$this->session->set_userdata('brijwasi_user_session_data', $session_data);
										$paymentUrl=base_url("checkout/payment");
										$response = array(
										'redirect_url' => $paymentUrl,
										'message' => "Your Shipping Address has been updated in this order.",
										'status'=>1,
										);
										$this->session->set_flashdata('user_success', $response['message']);
										//user account url
										redirect($paymentUrl);										
										break;
										
										case "addressForm":										
										$response = array(
										'message' => "Your Shipping Address has been updated successfully.",
										'status'=>1,
										);
										$this->session->set_flashdata('user_success', $response['message']);
										//user account url
										if ($this->agent->referrer()){
										//redirect to some function
										redirect($this->agent->referrer());
										}else{
											redirect("checkout");
										}										
										break;
									}
									
								}else{
									$response = array(
									'message' => "Your shipping address does not saved.Please try again later!",
									'status'=>0,
									);
                                    $this->session->set_flashdata('user_error', $response['message']);
									if ($this->agent->referrer()){
										//redirect to some function
										redirect($this->agent->referrer());
									}else{
										redirect("checkout");
									}									
								}
						}else{	
						$response = array(
						'message' => "This Mobile No is already taken! Try another.",
						'status'=>0,
						);

                        $this->session->set_flashdata('user_error', $response['message']);
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("checkout");
						}													
					}
				}else{
					$response = array(
						'message' => "This Postal Code is already taken! Try another.",
						'status'=>0,
					);

					$this->session->set_flashdata('user_error', $response['message']);
					if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("checkout");
					}
				}		
			}else{
				$where=array("user_id"=> $logged_in_id,'a_post_code'=> $this->input->post('postcode', TRUE));
				$uPostDetails = $this->base_model->getOneRecordWithWhere("brij_user_shipping_addresses",$where,"*");
				//$uPostDetails = $this->base_model->getOneRecord("brij_user_shipping_addresses","a_post_code", $this->input->post('postcode', TRUE) , "*");
				if(empty($uPostDetails) && count($uPostDetails) <= 0){
					$where=array("user_id"=> $logged_in_id,'a_mobile_no'=> $this->input->post('phonenumber', TRUE));
					$uPhoneSDetails = $this->base_model->getOneRecordWithWhere("brij_user_shipping_addresses",$where,"*");
					//$uPhoneSDetails = $this->base_model->getOneRecord("brij_user_shipping_addresses","a_mobile_no", $this->input->post('phonenumber', TRUE), "*");
						if(empty($uPhoneSDetails) && count($uPhoneSDetails) <= 0){
								$insert_data = array(
								    'user_id' =>$logged_in_id,
									'a_fname' => $this->input->post('firstname', TRUE),
									'a_lname' => $this->input->post('lastname', TRUE),
									'a_mobile_no' => $this->input->post('phonenumber', TRUE),
									'company_name' => $this->input->post('company', TRUE),
									'a_address_one' => $this->input->post('street', TRUE)[0],
									'a_country_id' => 101,
									'a_state_id'=> $this->input->post('region_id', TRUE),
									'a_city_id'=> $this->input->post('city', TRUE),
									'a_post_code'=>  $this->input->post('postcode', TRUE),
									'date_added'=>  $date,
								);
								
								if($this->input->post('is_default', TRUE) && !empty($this->input->post('is_default', TRUE))){
									$where_conditionst=array('user_id' =>$logged_in_id);
									$update_data_default=array("set_default"=>0);
									$update=$this->base_model->update_entry('brij_user_shipping_addresses', $update_data_default ,$where_conditionst);
									$insert_data["set_default"]=$this->input->post('is_default', TRUE);
								}
								$last_inserted_id=$this->base_model->insert_entry('brij_user_shipping_addresses',$insert_data);
								if ($last_inserted_id){
									$action=$this->input->post('action', TRUE);
									switch($action){										
										case "shippingAddressFormNew";										
										$session_data = array(
											'ACTION' => trim($this->input->post('do', TRUE)),
											'SHIPPING_ADDRESS' => trim($last_inserted_id),
										);
										if(isset($this->session->userdata('brijwasi_user_session_data')['ORDER_ID']) && !empty($this->session->userdata('brijwasi_user_session_data')['ORDER_ID'])){
											$session_data['ORDER_ID']=$this->session->userdata('brijwasi_user_session_data')['ORDER_ID'];							
										}
										// Add user data in session
										$this->session->set_userdata('brijwasi_user_session_data', $session_data);
										$paymentUrl=base_url("checkout/payment");
										$response = array(
										'redirect_url' => $paymentUrl,
										'message' => "Your Shipping Address has been added in this order.",
										'status'=>1,
										);
										$this->session->set_flashdata('user_success', $response['message']);
										//user account url
										redirect($paymentUrl);										
										break;
										
										case "addressForm":										
										$response = array(
										'message' => "Your Shipping Address has been added successfully.",
										'status'=>1,
										);
										$this->session->set_flashdata('user_success', $response['message']);
										//user account url
										if ($this->agent->referrer()){
										//redirect to some function
										redirect($this->agent->referrer());
										}else{
											redirect("checkout");
										}										
										break;
									}
									
								}else{
									$response = array(
									'message' => "Your shipping address does not saved.Please try again later!",
									'status'=>0,
									);
                                    $this->session->set_flashdata('user_error', $response['message']);
									if ($this->agent->referrer()){
										//redirect to some function
										redirect($this->agent->referrer());
									}else{
										redirect("checkout");
									}									
								}
						}else{	
						$response = array(
						'message' => "This Mobile No is already taken! Try another.",
						'status'=>0,
						);

                        $this->session->set_flashdata('user_error', $response['message']);
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("checkout");
						}													
					}
				}else{
					$response = array(
						'message' => "This Postal Code is already taken! Try another.",
						'status'=>0,
					);

					$this->session->set_flashdata('user_error', $response['message']);
					if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("checkout");
					}
				}
			}
			$this->session->set_flashdata('user_error', 'User does not saved. Please try again!');
			if ($this->agent->referrer()){
				//redirect to some function
				redirect($this->agent->referrer());
			}else{
				redirect("checkout");
			}
		}
	}
	//User Shipping Address Section End
	
	//User Newletter subscribe Section Start
	
	public function newsletter_manage()
	{
		
	if($this->user_model->check_user_logged()=== false){
			redirect(base_url().'user/login');
		}
		$logged_in_id= $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
		if($this->input->method() === 'post'){
			/* echo "<pre>";print_r($_POST);
			die; */
			$is_subscribed = $this->input->post('is_subscribed', TRUE);
			$is_subscribed = (isset($is_subscribed) && !empty($is_subscribed))?$is_subscribed:0;
			switch($is_subscribed){
				case 1:
				$update_data = array(
					'is_subscribe_newletters' => 1
				);
				$where_conditions=array("id"=>$logged_in_id);
				$last_inserted_id=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions);
				break;
				case 0:
				$update_data = array(
					'is_subscribe_newletters' => 0
				);
				$where_conditions=array("id"=>$logged_in_id);
				$last_inserted_id=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions);
				break;				
			}
            $this->session->set_flashdata('user_success','Newletter Subscription Status has been changed successfully.');
			if ($this->agent->referrer()){
				//redirect to some function
				redirect($this->agent->referrer());
			}else{
				redirect("user/newsletter_manage");
			}
		}
		
		
		$where=array("id"=> $logged_in_id);
		$data['userDetails'] = $this->base_model->getOneRecordWithWhere("brij_users",$where,"*");
		
		$data['title'] = 'Newsletter Subscription';
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);		
		$this->load->view('User/newsletter-manage',$data);
		$this->load->view('include/user-left-menu',$data);
		$this->load->view('include/footer');
	}
	public function subscriber_status()
	{
		if($this->input->method() === 'get'){
			$do=trim($this->input->get('do', TRUE));
			$user_id=trim($this->input->get('user_id', TRUE));
			switch($do){
				case 'subscribe':
				$update_data = array(
					'is_subscribe_newletters' => 1
				);
				$where_conditions=array("id"=>$user_id);
				$last_inserted_id=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions);
				break;
				case 'unsubscribe':
				$update_data = array(
					'is_subscribe_newletters' => 0
				);
				$where_conditions=array("id"=>$user_id);
				$last_inserted_id=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions);
				break;				
			}

            $this->session->set_flashdata('s_user_success','Newletter Status has been changed successfully.');
			if ($this->agent->referrer()){
				//redirect to some function
				redirect($this->agent->referrer());
			}else{
				redirect("/");
			}			
		}
	}
	//User Newletter Subscribe Section End
	//User Section Start
	//Forgot password
	public function forgotpassword()
	{
		if($this->user_model->check_user_logged()=== true){
			redirect(base_url().'user/account');
		}
		
		if($this->input->method() === 'post'){
			/* echo "<pre>";print_r($_POST);
			die; */
			$where=array("email_id"=> $this->input->post('email', TRUE));
		    $userDatas = $this->base_model->getOneRecordWithWhere("brij_users",$where,"*");
			
			if (count($userDatas) > 0 && !empty($userDatas)){
				  $token=$this->_generateRandomNumber();
				  $update_data=array("token"=>$token);
				  $user_id=$userDatas->id;
				  $where_conditions=array("id"=>$user_id);
				  $res=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions);
				  if($res){
					$email_id=$userDatas->email_id;
					$user_name=$userDatas->name;
					$BASE_URL= base_url();
					$RESETRLINK=$BASE_URL.'user/reset_password?token='.$token.'&type=user';
					$message="Dear,".$user_name."
					Your reset passsword Link:".$RESETRLINK;
					$this->load->library('email');
					$config = Array(
						'mailtype' => 'html', // text
						'charset' => 'iso-8859-1',
						'newline' => '\r\n',
						'wordwrap' => TRUE
					);
					$Email_From_Address=getSiteSettingValue(37);
			        $Emails_From_Name=getSiteSettingValue(38);
					$this->email->clear();
					$this->email->initialize($config);
					$this->email->from($Email_From_Address, $Emails_From_Name);
					$this->email->to($email_id);
					$this->email->subject('Your Reset Password Link');
					$this->email->message($message);
					if($this->email->send()){
					  //set the response and exit
					  $response=array(
						'status' => TRUE,
						'message' => "Your Reset Link has been sent on your email id provided by you. Please check your mailbox.");
					  
					   $this->session->set_flashdata('user_success',$response['message']);
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("user/forgotpassword");
						}
					}else{
					  //set the response and exit
					  $response=array(
						'status' => FALSE,
						'message' => "Mail does not working. Please try again!");
					  
					   $this->session->set_flashdata('user_error',$response['message']);
						if ($this->agent->referrer()){
							//redirect to some function
							redirect($this->agent->referrer());
						}else{
							redirect("user/forgotpassword");
						}
					}
				  }
			}else{
				
				//set the response and exit
				  $response=array(
					'status' => FALSE,
					'message' => "This email id does not exist. Please try again!");
				
				$this->session->set_flashdata('user_error',$response['message']);
				if ($this->agent->referrer()){
					//redirect to some function
					redirect($this->agent->referrer());
				}else{
					redirect("user/forgotpassword");
				}
			}
		}
		
		$data['title'] = 'Forgot Your Password';
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);		
		$this->load->view('User/forgotpassword',$data);
		$this->load->view('include/footer');
	}
	
	//Reset password
	public function reset_password()
	{
		$data['display_form'] = 0;
		$data['token'] = '';
		$data['type'] = '';
		if($this->input->get('token', TRUE) && !empty($this->input->get('token', TRUE))){
				$token=$this->input->get('token', TRUE);
				$type=$this->input->get('type', TRUE);
				if($this->checkToken($token,$type)===false){
				    $data['display_form'] = 0;
					$this->session->set_flashdata('reset_pass_error', 'Your reset password token does not match. Please try again!');
                    redirect("user/reset_password");					
				}else{
					$data['token'] = $token;
					$data['display_form'] = 1;
				}
				$data['type'] = $this->input->get('type', TRUE);
		}else{
			$data['display_form'] = 0;
			$this->session->set_flashdata('reset_pass_error', 'Invalid Request. Please try again!');		
		}
		
		$data['title'] = 'Reset Password';
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);	
		$this->load->view('User/reset-password',$data);
        $this->load->view('include/footer',$data);		
	}
	
	//change password
	public function change_password()
	{
		if($this->user_model->check_user_logged()===false){
			redirect(base_url().'user/login');
		}
		if($this->input->method() === 'post'){
			foreach($this->input->post() AS $key=>$value){
				if($value == ''){
					$this->session->set_flashdata('change_pass_error', 'Change Password doess not saved. Please try again!');
					if ($this->agent->referrer()){
						//redirect to some function
						redirect($this->agent->referrer());
					}else{
						redirect("user/change_password");
					}
				}
			}
			$current_password=$this->input->post('current_password', TRUE);
			$new_pass=$this->input->post('new_password', TRUE);
			$confirm_pass=$this->input->post('confirm_password', TRUE);
			if(isset($new_pass) && isset($confirm_pass) && !empty($new_pass) && !empty($confirm_pass) && isset($current_password) && !empty($current_password)){
				$where=array("password"=> md5($current_password),'id'=>$this->session->userdata('logged_in_brijwasi_user_data')['ID']);
				$userData = $this->base_model->getOneRecordWithWhere("brij_users",$where,"id,password");
				if (count($userData) && !empty($userData)){
					if ($userData->password != md5($new_pass)){
						$update_data=array("password"=>md5($new_pass));
						$user_id=$userData->user_id;
						$where_conditions=array("id"=>$user_id);
						$res=$this->base_model->update_entry('brij_users', $update_data ,$where_conditions);
						if ($res){
							$this->session->set_flashdata('change_pass_success', 'Password has been changed successfully.');
							if ($this->agent->referrer()){
								//redirect to some function
								redirect($this->agent->referrer());
							}else{
								redirect("user/change_password");
							}
						}
					}else{
						$this->session->set_flashdata('change_pass_error', 'Current password and New password doess not same. Please enter different password!');
					}
				}else{
					$this->session->set_flashdata('change_pass_error', 'Current password doess not match in our record. Please try again!');
				}
			}else{
				$this->session->set_flashdata('change_pass_error', 'Password doess not saved. Please try again!');
			}
		}
		$data['title'] = 'Change Password';
		$this->load->view('include/header',$data);
		$this->load->view('include/menu',$data);		
		$this->load->view('change_password',$data);
		$this->load->view('include/user-left-menu',$data);
		$this->load->view('include/footer');
	}
	//Logout from user page
	public function logout(){
		// Removing session data
		$this->session->unset_userdata('logged_in_brijwasi_user_data');
		$this->session->unset_userdata('brijwasi_user_session_data');
		//$this->session->sess_destroy();
		//$data['message_display'] = 'Successfully Logout';
		//$this->load->view('login',$data);
		 redirect("user/login",'refresh');
	}
	private function checkToken($token=null,$type=null){
		$userDetils=$this->base_model->getOneRecord('brij_users','token', $token, 'id,email_id,name,token');
         if(count($userDetils) > 0){			 
			 return $userDetils;
		 }
         return false;		 
	}
	//User Section End
}