<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{

	//Method to generate a unique api key every time
	private function _generateApiKey()
	{
		return md5(uniqid(rand(), true));
	}

	//Method to generate a unique customer id number every time
	private function _generateCustomerDentificationNumber($last_insert_user_id, $state_id = '')
	{
		$unique_code = 'CUST-00000' . $last_insert_user_id;
		if (isset($state_id) && !empty($state_id)) {
			$stateData = $this->base_model->getOneRecord("brij_states", "id", $state_id, "short_name");
			$unique_code = 'CUST-00000' . $last_insert_user_id . '-' . $stateData->short_name;
		}
		$where_conditions = array("id" => $last_insert_user_id);
		$update_data = array('unique_code' => $unique_code);
		$last_inserted_id = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
	}

	//ajax admin login
	public function login()
	{
		$this->db->cache_delete_all();
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');
			if ($this->form_validation->run() === FALSE) {
				//Either you can print value or you can send value to database
				$response = array(
					'message' => "Please Enter Email and Password.",
					'status' => 0,
				);
			} else {
				//Execute Your Code
				$data = array(
					'username' => $this->input->post('email', TRUE),
					'password' => $this->input->post('password', TRUE)
				);
				$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => API_URL.'api/login',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS => array('password' => $data['password'],'email' => $data['username']),
					  CURLOPT_HTTPHEADER => array(
						'Accept: application/json'
					  ),
					));

					$curl_response = curl_exec($curl);

					curl_close($curl);
					$res = json_decode($curl_response);
					
					
					$base_url = base_url() . 'admin/dashboard';
					if(strtolower($res->status)!='error'){
					if (strtolower($res->status) == 'success') {
						$session_data = array(
							'logged_in_id' => $res->data->user->crn,
							'screen_name' => $data['username'],
							'user_mail' => $data['username'],
							'user_role' => 'so',
							'user_data' => $res->data,
						);
						// Add user data in session
						$this->session->set_userdata('logged_in_brijwasi_data', $session_data);
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "The system has validated your login credentials. Redirecting<span class='loader__dot'>.</span><span class='loader__dot'>.</span><span class='loader__dot'>.</span>",
							'redirect_url' => $base_url,
							'status' => 1,
						);
					} else {
						$response = array(
							'message' => "Your account does not active.Please try again later!",
							'status' => 0,
						);
					}
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Invalid Username and Password. Please try again!",
						'status' => 0,
					);
				}
			}
			$this->output->set_header('Content-type: application/json');
			$this->output->set_output(json_encode($response));
			//echo json_encode($response);
		}
	}


	//ajax user login
	public function userlogin()
	{
		$this->db->cache_delete_all();
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');
			if ($this->form_validation->run() === FALSE) {
				//Either you can print value or you can send value to database
				$response = array(
					'message' => "Please Enter Username and Password.",
					'status' => 0,
				);
			} else {
				//Execute Your Code
				$data = array(
					'username' => $this->input->post('username', TRUE),
					'password' => md5($this->input->post('password', TRUE))
				);
				$users = $this->user_model->userLogin($data);
				$base_url = base_url() . 'user/account';
				if ($users) {
					if ($users->status == 1) {
						$session_data = array(
							'ID' => $users->id,
							'USERNAME' => $users->name,
							'EMAIL' => $users->email_id,
							'PHONENUMBER' => $users->phone_no,
						);
						// Add user data in session
						$this->session->set_userdata('logged_in_brijwasi_user_data', $session_data);
						//print_r($this->session->userdata('brijwasi_user_session_data'));
						//die;
						$update_data["user_id"] = $users->id;
						$where_conditions_ois['order_session_id'] = $this->session->session_id;
						$where_conditions_ois['order_item_status'] = 0;
						$update = $this->base_model->update_entry('brij_order_items', $update_data, $where_conditions_ois);
						if ($this->session->userdata('brijwasi_user_session_data') && !empty($this->session->userdata('brijwasi_user_session_data'))) {
							$do = $this->session->userdata('brijwasi_user_session_data')['ACTION'];
							$logged_in_id = $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
							$date = date("Y-m-d H:i:s");
							switch ($do) {
								case 'add-wishlist':
									$wishlist_id = $this->session->userdata('brijwasi_user_session_data')['WISHLIST_ID'];
									$where = array("user_id" => $logged_in_id, 'product_id' => $wishlist_id);
									$userWishlistData = $this->base_model->getOneRecordWithWhere("brij_wishlist", $where, "id");
									if (empty($userWishlistData) && count($userWishlistData) <= 0) {
										$insert_data = array(
											'user_id' =>  $logged_in_id,
											'product_id' => $wishlist_id,
											'date_added' =>  $date,
										);
										$last_inserted_id = $this->base_model->insert_entry('brij_wishlist', $insert_data);
										$this->session->set_flashdata('wishlist_success', 'Wish List has been added successfully.');
									} else {
										$this->session->set_flashdata('wishlist_error', 'This product already in the Wish List.');
									}
									$session_data = array(
										'ACTION' => '',
										'WISHLIST_ID' => ''
									);
									$this->session->unset_userdata('brijwasi_user_session_data', $session_data);
									$base_url = base_url() . 'user/wishlist';
									break;
								case "checkout-page":
									$update_data["user_id"] = $users->id;
									$where_conditions_ois['order_session_id'] = $this->session->session_id;
									$where_conditions_ois['order_item_status'] = 0;
									$update = $this->base_model->update_entry('brij_order_items', $update_data, $where_conditions_ois);
									break;
							}
						}

						if ($this->input->post('do', TRUE) && !empty($this->input->post('do', TRUE))) {
							$do = $this->input->post('do', TRUE);
							switch ($do) {
								case "checkout-page":
									$update_data["user_id"] = $users->id;
									$where_conditions_ois['order_session_id'] = $this->session->session_id;
									$where_conditions_ois['order_item_status'] = 0;
									$update = $this->base_model->update_entry('brij_order_items', $update_data, $where_conditions_ois);
									break;
							}
						}
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "You are logged in successfully. Please wait to redirecting page...",
							'redirect_url' => $base_url,
							'status' => 1,
						);
					} else {
						$response = array(
							'message' => "Your account does not active.Please try again later!",
							'status' => 0,
						);
					}
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Invalid Username and Password. Please try again!",
						'status' => 0,
					);
				}
			}
			$this->output->set_header('Content-type: application/json');
			$this->output->set_output(json_encode($response));
			//echo json_encode($response);
		}
	}
	//user registration page
	public function registration()
	{
		$response = array();
		if ($this->input->is_ajax_request()) {
			if ($this->input->method() === 'post') {
				//echo "<pre>";print_r($_POST);
				//die;
				$logged_in_id = $this->session->userdata('logged_in_brijwasi_user_data')['ID'];
				$date = date("Y-m-d H:i:s");
				$user_api_key = $this->_generateApiKey();
				$is_subscribed = $this->input->post('is_subscribed', TRUE);
				$is_subscribed = (isset($is_subscribed) && !empty($is_subscribed)) ? $is_subscribed : 0;
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$uDetails = $this->base_model->getOneRecord("brij_users", "id", $this->input->post('id', TRUE), "*");
					$update_data = array(
						'name' => $this->input->post('firstname', TRUE),
						'email_id' => $this->input->post('email', TRUE),
						'address' => $this->input->post('address', TRUE),
						'phone_no' =>  $this->input->post('mobile_no', TRUE),
						'is_subscribe_newletters' =>  $is_subscribed,
						'pin_code' =>  $this->input->post('postcode', TRUE),
						'city_id' => $this->input->post('city', TRUE),
						'state_id' => $this->input->post('region_id', TRUE),
						'country_id' => 101,
						'date_updated' =>  $date,
					);
					if ($this->input->post('password', TRUE) && !empty($this->input->post('password', TRUE))) {
						$update_data['password'] = md5($this->input->post('password', TRUE));
					}
					$where_conditions = array("id" => $this->input->post('id', TRUE));
					$last_inserted_id = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
					//$this->_generateCustomerDentificationNumber($this->input->post('id', TRUE));
				} else {
					$uDetails = $this->base_model->getOneRecord("brij_users", "email_id", $this->input->post('email', TRUE), "*");
					if (empty($uDetails) && count($uDetails) <= 0) {
						$uPhoneDetails = $this->base_model->getOneRecord("brij_users", "phone_no", $this->input->post('mobile_no', TRUE), "*");
						if (empty($uPhoneDetails) && count($uPhoneDetails) <= 0) {
							$insert_data = array(
								'name' => $this->input->post('firstname', TRUE),
								'email_id' => $this->input->post('email', TRUE),
								'address' => $this->input->post('address', TRUE),
								'phone_no' =>  $this->input->post('mobile_no', TRUE),
								'is_subscribe_newletters' =>  $is_subscribed,
								'country_id' => 101,
								'pin_code' =>  $this->input->post('postcode', TRUE),
								'city_id' => $this->input->post('city', TRUE),
								'state_id' => $this->input->post('region_id', TRUE),
								'status' =>  1,
								'password' => md5($this->input->post('password', TRUE)),
								'api_key' => $user_api_key,
								'date_added' =>  $date,
							);
							$last_inserted_id = $this->base_model->insert_entry('brij_users', $insert_data);
							if ($last_inserted_id) {
								$this->_generateCustomerDentificationNumber($last_inserted_id);
								$redirectUrl = base_url("user/account");
								$users = $this->base_model->getOneRecord("brij_users", "id", $last_inserted_id, "*");
								$session_data = array(
									'ID' => $users->id,
									'USERNAME' => $users->name,
									'EMAIL' => $users->email_id,
									'PHONENUMBER' => $users->phone_no,
								);
								// Add user data in session
								$this->session->set_userdata('logged_in_brijwasi_user_data', $session_data);
								$update_data["user_id"] = $users->id;
								$where_conditions_ois['order_session_id'] = $this->session->session_id;
								$where_conditions_ois['order_item_status'] = 0;
								$update = $this->base_model->update_entry('brij_order_items', $update_data, $where_conditions_ois);
								if ($this->input->post('do', TRUE) && !empty($this->input->post('do', TRUE))) {
									$do = $this->input->post('do', TRUE);
									switch ($do) {
										case "checkout-page":
											$redirectUrl = base_url("checkout");
											break;
									}
								}
								$response = array(
									'redirect_url' => $redirectUrl,
									'message' => "Your registration has been done successfully. Please wait to redirecting page...",
									'status' => 1,
								);
							} else {
								$response = array(
									'message' => "Your registration does not work.Please try again later!",
									'status_msg' => 1,
									'status' => 0,
								);
							}
						} else {
							$response = array(
								'message' => "This Mobile No is already taken! Try another.",
								'status_msg' => 3,
								'status' => 0,
							);
						}
					} else {
						$response = array(
							'message' => "This Email ID is already taken! Try another.",
							'status_msg' => 2,
							'status' => 0,
						);
					}
				}
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
		//echo json_encode($response);
	}
	//reset password
	public function reset_password()
	{
		$response = array();
		$dataContent = '';
		if ($this->input->is_ajax_request()) {
			if ($this->input->method() === 'post') {
				if ($this->input->post('token', TRUE) && !empty($this->input->post('token', TRUE))) {
					$token = $this->input->post('token', TRUE);
					$userDetails = $this->base_model->getOneRecord('brij_users', 'token', $token, 'id,token');
					if (!empty($userDetails) && count($userDetails) > 0) {
						$new_pass = $this->input->post('new_password', TRUE);
						$confirm_pass = $this->input->post('confirm_password', TRUE);
						if (isset($new_pass) && isset($confirm_pass) && !empty($new_pass) && !empty($confirm_pass)) {
							if ($new_pass == $confirm_pass) {
								$update_data = array("password" => md5($new_pass), 'token' => '');
								$user_id = $userDetails->id;
								$where_conditions = array("id" => $user_id);
								$res = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
								$token = $userDetails->token;
								if ($res) {
									$response = array(
										'message' => "Your password has been reset successfully. Please wait to redirecting login page...",
										'token' => $token,
										'redirect_url' => base_url("user/login"),
										'status' => 1,
									);
								} else {
									$response = array(
										'message' => "Password does not reset. Please try again!",
										'status' => 0,
									);
								}
							} else {
								$response = array(
									'message' => "New password and Confirm password does not match. Please try again!",
									'status' => 0,
								);
							}
						} else {
							$response = array(
								'message' => "Please enter new password and confirm password.",
								'status' => 0,
							);
						}
					} else {
						$response = array(
							'message' => "Token does not found. Please try again!",
							'status' => 0,
						);
					}
				} else {
					$response = array(
						'message' => "Token is empty.Please try again!",
						'status' => 0,
					);
				}
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
		//echo json_encode($response);
	}

	function humanTiming($time)
	{
		$time = time() - $time; // to get the time since that moment
		$time = ($time < 1) ? 1 : $time;
		$tokens = array(
			31536000 => 'year ago',
			2592000 => 'month ago',
			604800 => 'week ago',
			86400 => 'day ago',
			3600 => 'hour ago',
			60 => 'minute ago',
			1 => 'second ago'
		);

		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
		}
	}
	//ajax process
	public function ajaxProcess()
	{
		$response = array();
		if ($this->input->is_ajax_request()) {
			$request = $this->input->post('request', TRUE);
			$date_at = date("Y-m-d H:i:s");
			$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];

			if (!empty($request) && $request === 'data_bar_chart') {

				$chartData = $this->admin_model->getOrderMonthlyIncome($admin_id = $logged_in_id);
				$myurl = array();
				$months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July ', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
				//$background_colors = array('#282E33', '#25373A', '#164852', '#495E67', '#FF3838');
				$background_colors = array(1 => 'rgba(255,99,132,.5)', 2 => 'rgba(255,159,64,.5)', 3 => 'rgba(255,205,86)', 4 => 'rgba(75,192,192,.5)', 5 => 'rgba(54,162,235,.5)', 6 => 'rgba(153,102,255,.5)', 7 => 'rgba(201,203,207,.5)', 8 => 'rgba(239,41,41,.5)', 9 => 'rgba(245,121,0,.5)', 10 => 'rgba(115,210,22,.5)', 11 => 'rgba(114,159,207,.5)', 12 => 'rgba(92,53,102,.5)');
				$border_colors = array(1 => 'rgb(255,99,132)', 2 => 'rgb(255,159,64)', 3 => 'rgb(255,205,86)', 4 => 'rgb(75,192,192)', 5 => 'rgb(54,162,235)', 6 => 'rgb(153,102,255)', 7 => 'rgb(201,203,207)', 8 => 'rgb(239,41,41)', 9 => 'rgb(245,121,0)', 10 => 'rgb(115,210,22)', 11 => 'rgb(114,159,207)', 12 => 'rgb(92,53,102)');
				$count = count($background_colors) - 1;

				$i = rand(0, $count);

				$rand_background = $background_colors[$i];
				$i = 0;
				$chaet = array();
				foreach ($months as $key => $months1) {
					$chaet[$i]["month"] = $months1;
					$chaet[$i]["year"] = date("Y"); //(!empty($chartDatas->year))?$chartDatas->year:date("Y");
					$chaet[$i]['amount_month'] = 0;
					$chaet[$i]["color"] = $background_colors[$key];
					$chaet[$i]["bordercolor"] = $border_colors[$key];
					foreach ($chartData as $chartDatas) {
						$chaet[$i]["year"] = (!empty($chartDatas->year)) ? $chartDatas->year : date("Y");
						if ($chartDatas->month == $key) {
							$chaet[$i]['amount_month'] = $chartDatas->order_total;
							$chaet[$i]["color"] = $background_colors[$key];
							$chaet[$i]["bordercolor"] = $border_colors[$key];
							$chaet[$i]["year"] = (!empty($chartDatas->year)) ? $chartDatas->year : date("Y");
						}
					}
					$i++;
				}

				//print_r($chaet);
				//die;
				if (!empty($chaet)) {
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $chaet,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $html,
						'dataStatus' => '0'
					);
				}
			}

			//get edit category data
			if (!empty($request) && $request === 'edit_cat_data') {
				$cat_id = $this->input->post('cat_id', TRUE);
				//$type=$this->input->post('type', TRUE);
				$catDetails = $this->base_model->getOneRecord('categories', 'id', $cat_id, '*');
				if (isset($catDetails) && !empty($catDetails)) {
					$where_condition = array('categories_id' => $cat_id);
					$totalPosts = $this->base_model->getNumRows('post_categories', $where_condition);
					$isDisabled = 'no';
					if ($totalPosts > 0) {
						$isDisabled = 'yes';
					}
					$response = array(
						'id' => $cat_id,
						'dataContent' => $catDetails,
						'isDisabled' => $isDisabled,
						'dataCount' => $totalPosts
					);
				} else {
					$response = array(
						'id' => $cat_id,
						'dataContent' => '',
					);
				}
			}

			//check category name available yes or no
			if (!empty($request) && $request === 'check-category-name') {
				$cat_name = trim($this->input->post('cat_name', TRUE));
				$action = $this->input->post('action', TRUE);
				$type = $this->input->post('type', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-category') {
					$where_con = array("name" => $cat_name, "type" => $type);
					$catDetails = $this->base_model->getOneRecordWithWhere('categories', $where_con, 'id');
					$slug = url_title($cat_name, 'dash', true);
					if (!empty($catDetails) && isset($catDetails)) {
						$statusLink = 1;
						$slug = '';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink,
						'slug' => $slug
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-category') {
					$cat_id = $this->input->post('cat_id', TRUE);
					$where_con = array("name" => $cat_name, "id !=" => $cat_id, "type" => $type);
					$catDetails = $this->base_model->getOneRecordWithWhere("categories", $where_con, 'id');
					$slug = url_title($cat_name, 'dash', true);
					if (!empty($catDetails) && isset($catDetails)) {
						$statusLink = 1;
						$slug = '';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink,
						'slug' => $slug
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Category available does not working. Please try again!",
						'id' => $cat_name,
						'dataContent' => ''
					);
				}
			}

			//get edit location data
			if (!empty($request) && $request === 'edit_location_data') {
				$loc_id = $this->input->post('loc_id', TRUE);
				$locDetails = $this->base_model->getOneRecord('brij_locations', 'id', $loc_id, '*');
				if (isset($locDetails) && !empty($locDetails)) {
					$response = array(
						'id' => $loc_id,
						'dataContent' => $locDetails,
					);
				} else {
					$response = array(
						'id' => $loc_id,
						'dataContent' => '',
					);
				}
			}

			//get location name is available or not
			if (!empty($request) && $request === 'check-location-name') {
				$location_name = trim($this->input->post('location_name', TRUE));
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-location') {
					$locDetails = $this->base_model->getOneRecord('brij_locations', 'location_name', $location_name, 'id');
					if (!empty($locDetails) && isset($locDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-location') {
					$loc_id = $this->input->post('loc_id', TRUE);
					$where_con = array("location_name" => $location_name, "id !=" => $loc_id);
					$locDetails = $this->base_model->getOneRecordWithWhere("brij_locations", $where_con, 'id');
					if (!empty($locDetails) && isset($locDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Location Name available does not working. Please try again!",
						'id' => $location_name,
						'dataContent' => ''
					);
				}
			}

			//get edit product data
			if (!empty($request) && $request === 'edit_product_data') {
				$pro_id = $this->input->post('pro_id', TRUE);
				$proDetails = $this->base_model->getOneRecord('brij_products', 'id', $pro_id, '*');
				if (isset($proDetails) && !empty($proDetails)) {
					$product_image = '';
					$productImage = getProductImage($pro_id, $limit = '');
					if (count($productImage) > 0 && !empty($productImage)) {
						foreach ($productImage as $productImage) {
							$profilename = 'uploads/product_images/' . $productImage->images;
							$pro_file = '../uploads/no-image100x100.jpg';
							$pro_original_file = '../uploads/no-image400x400.jpg';
							if (file_exists($profilename) && !empty($productImage->images)) {
								$pro_file = '../uploads/product_images/small/' . $productImage->images;
								$pro_original_file = '../uploads/product_images/' . $productImage->images;
							}
							$product_image .= ' <div class="col-md-3 col-sm-3 col-4 col-lg-3 col-xl-2" id="product-image-' . $productImage->id . '">
								<div class="product-thumbnail">
								   <div class="lightgallery">
									<a href="' . $pro_original_file . '">
									<img src="' . $pro_file . '" class="img-thumbnail img-fluid" alt=""></a>
									</div>
									<span class="product-remove product-image-remove" title="remove" id="' . $productImage->id . '"><i class="fa fa-close"></i></span>
								</div>
							</div><script>$(".lightgallery").lightGallery();</script>';
						}
					}
					$response = array(
						'id' => $pro_id,
						'dataContent' => $proDetails,
						'productImage' => $product_image
					);
				} else {
					$response = array(
						'id' => $pro_id,
						'dataContent' => '',
						'productImage' => ''
					);
				}
			}

			//check product name in products table available yes or no
			if (!empty($request) && $request === 'check-product-name') {
				$product_name = $this->input->post('product_name', TRUE);
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-product') {
					$proDetails = $this->base_model->getOneRecord('brij_products', 'product_name', $product_name, 'id');
					if (!empty($proDetails) && isset($proDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'add-product-code') {
					$product_code = $this->input->post('product_code', TRUE);
					$proDetails = $this->base_model->getOneRecord('brij_products', 'product_code', $product_code, 'id');
					if (!empty($proDetails) && isset($proDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-product') {
					$product_id = $this->input->post('product_id', TRUE);
					$where_con = array("product_name" => $product_name, "id !=" => $product_id);
					$proDetails = $this->base_model->getOneRecordWithWhere("brij_products", $where_con, 'id');
					if (!empty($proDetails) && isset($proDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Product name available does not working. Please try again!",
						'id' => $product_name,
						'dataContent' => ''
					);
				}
			}

			if (!empty($request) && $request === 'delete_product_image_data') {
				$pro_image_id = $this->input->post('pro_image_id', TRUE);
				$proImageDetails = $this->base_model->getOneRecord('brij_product_images', 'id', $pro_image_id, 'images');
				if (isset($proImageDetails) && !empty($proImageDetails)) {
					$profilename = 'uploads/product_images/' . $proImageDetails->images;
					if (file_exists($profilename) && !empty($proImageDetails->images) && isset($proImageDetails->images)) {
						$_image = $proImageDetails->images;
						unlink(realpath('uploads/product_images/' . $_image));
						unlink(realpath('uploads/product_images/large/' . $_image));
						unlink(realpath('uploads/product_images/medium/' . $_image));
						unlink(realpath('uploads/product_images/small/' . $_image));
					}
					$where_conditions_h = array('id' => $pro_image_id);
					$deleted = $this->base_model->deleteWithWhereConditions('brij_product_images', $where_conditions_h);
					$response = array(
						'id' => $pro_image_id,
						'dataContent' => 1,
						'message' => 'Product Image has been deleted successfully.'
					);
				} else {
					$response = array(
						'id' => $pro_image_id,
						'dataContent' => 0,
						'message' => 'Product Image does not deleted. Please try again!'
					);
				}
			}

			//get sub category list by cat id
			if (!empty($request) && $request === 'get_sub_category') {
				$cat_id = $this->input->post('cat_id', TRUE);
				$conditions['status ='] = 1;
				$conditions['name !='] = '';
				$conditions['parent_id ='] = $cat_id;
				$subCatData = $this->base_model->getAllRows('brij_product_categories', 'name ASC', $conditions);
				//echo $location;
				//die;
				$options = '<option value="">Select Sub Category</option>';
				if ($subCatData) {
					foreach ($subCatData as $subCatData) {
						$options .= '<option value="' . $subCatData->id . '">' . $subCatData->name . '</option>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'subCatList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'subCatList' => $options,
						'dataStatus' => '0'
					);
				}
			}

			//get edit slider data
			if (!empty($request) && $request === 'edit_slider_data') {
				$slider_id = $this->input->post('slider_id', TRUE);
				$sliderDetails = $this->base_model->getOneRecord('brij_sliders', 'id', $slider_id, '*');
				if (isset($sliderDetails) && !empty($sliderDetails)) {
					$response = array(
						'id' => $slider_id,
						'dataContent' => $sliderDetails,
					);
				} else {
					$response = array(
						'id' => $slider_id,
						'dataContent' => '',
					);
				}
			}


			if (!empty($request) && $request === 'edit_guest_post_repeater_data') {
				$slider_id = $this->input->post('slider_id', TRUE);
				$sliderDetails = $this->base_model->getOneRecord('repeaters', 'id', $slider_id, '*');

				if (isset($sliderDetails) && !empty($sliderDetails)) {
					$response = array(
						'id' => $slider_id,
						'dataContent' => $sliderDetails,
					);
				} else {
					$response = array(
						'id' => $slider_id,
						'dataContent' => '',
					);
				}
			}

			//get edit banner data
			if (!empty($request) && $request === 'edit_banner_data') {
				$banner_id = $this->input->post('banner_id', TRUE);
				$bannerDetails = $this->base_model->getOneRecord('brij_banners', 'id', $banner_id, '*');
				if (isset($bannerDetails) && !empty($bannerDetails)) {
					$response = array(
						'id' => $banner_id,
						'dataContent' => $bannerDetails,
					);
				} else {
					$response = array(
						'id' => $banner_id,
						'dataContent' => '',
					);
				}
			}
		//get edit user data
		if(!empty($request) && $request==='edit_user_data'){
		$user_id=$this->input->post('user_id', TRUE);
		$uDetails=$this->base_model->getOneRecord('brij_users','id', $user_id, '*');
		if(isset($uDetails) && !empty($uDetails)){
		$response = array(
			'id'=>$user_id,
			'dataContent'=>$uDetails,
			);				
		}else{					
		$response = array(
			'id'=>$user_id,
			'dataContent'=>'',
		);
		}
		}
		
		//get edit page data
		if(!empty($request) && $request==='edit_post_data'){
			$post_id=$this->input->post('post_id', TRUE);
			$postDetails=$this->base_model->getOneRecord('posts','id', $post_id, '*');
			if(isset($postDetails) && !empty($postDetails)){
			$response = array(
				'id'=>$post_id,
				'dataContent'=>$postDetails,
				);				
			}else{					
			$response = array(
				'id'=>$post_id,
				'dataContent'=>'',
			);
		  }
		}
		
		if(!empty($request) && $request==='edit_career_data'){
			$post_id=$this->input->post('post_id', TRUE);
			$postDetails=$this->base_model->getOneRecord('career','id', $post_id, '*');
			if(isset($postDetails) && !empty($postDetails)){
			$response = array(
				'id'=>$post_id,
				'dataContent'=>$postDetails,
				);				
			}else{					
			$response = array(
				'id'=>$post_id,
				'dataContent'=>'',
			);
		  }
		}
		
		//get edit page data
		if(!empty($request) && $request==='edit_testimonial_data'){
			$testimonial_id=$this->input->post('testimonial_id', TRUE);
			$postDetails=$this->base_model->getOneRecord('testimonials','id', $testimonial_id, '*');
			if(isset($postDetails) && !empty($postDetails)){
			$response = array(
				'id'=>$testimonial_id,
				'dataContent'=>$postDetails,
				);				
			}else{					
			$response = array(
				'id'=>$testimonial_id,
				'dataContent'=>'',
			);
		  }
		}
		
		if(!empty($request) && $request==='edit_team_data'){
			$testimonial_id=$this->input->post('team_id', TRUE);
			$postDetails=$this->base_model->getOneRecord('team','id', $testimonial_id, '*');
			if(isset($postDetails) && !empty($postDetails)){
			$response = array(
				'id'=>$testimonial_id,
				'dataContent'=>$postDetails,
				);				
			}else{					
			$response = array(
				'id'=>$testimonial_id,
				'dataContent'=>'',
			);
		  }
		}
		
		if(!empty($request) && $request==='edit_client_data'){
			$testimonial_id=$this->input->post('team_id', TRUE);
			$postDetails=$this->base_model->getOneRecord('client','id', $testimonial_id, '*');
			if(isset($postDetails) && !empty($postDetails)){
			$response = array(
				'id'=>$testimonial_id,
				'dataContent'=>$postDetails,
				);				
			}else{					
			$response = array(
				'id'=>$testimonial_id,
				'dataContent'=>'',
			);
		  }
		}
		//get edit page data
		if(!empty($request) && $request==='edit_cs_data'){
			$cs_id=$this->input->post('cs_id', TRUE);
			$postDetails=$this->base_model->getOneRecord('case_studies','id', $cs_id, '*');
			if(isset($postDetails) && !empty($postDetails)){
			$response = array(
				'id'=>$cs_id,
				'dataContent'=>$postDetails,
				);				
			}else{					
			$response = array(
				'id'=>$cs_id,
				'dataContent'=>'',
			);
		  }
		}
			//get edit page data
			if (!empty($request) && $request === 'edit_page_data') {
				$page_id = $this->input->post('page_id', TRUE);
				$pageDetails = $this->base_model->getOneRecord('brij_pages', 'id', $page_id, '*');
				if (isset($pageDetails) && !empty($pageDetails)) {
					$response = array(
						'id' => $page_id,
						'dataContent' => $pageDetails,
					);
				} else {
					$response = array(
						'id' => $page_id,
						'dataContent' => '',
					);
				}
			}

			//get page name is available or not
			if (!empty($request) && $request === 'check-page-name') {
				$page_name = trim($this->input->post('page_name', TRUE));
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-page') {
					$pageDetails = $this->base_model->getOneRecord('brij_pages', 'page_title', $page_name, 'id');
					$slug = url_title($page_name, 'dash', true);
					if (!empty($pageDetails) && isset($pageDetails)) {
						$statusLink = 1;
						$slug = '';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink,
						'slug' => $slug
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-page') {
					$page_id = $this->input->post('page_id', TRUE);
					$where_con = array("page_title" => $page_name, "id !=" => $page_id);
					$pageDetails = $this->base_model->getOneRecordWithWhere("brij_pages", $where_con, 'id');
					// Use dashes to separate words;
					// third param is true to change all letters to lowercase
					$slug = url_title($page_name, 'dash', true);
					if (!empty($pageDetails) && isset($pageDetails)) {
						$statusLink = 1;
						$slug = '';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink,
						'slug' => $slug
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Page Name available does not working. Please try again!",
						'id' => $page_name,
						'dataContent' => ''
					);
				}
			}

			//get edit user data
			if (!empty($request) && $request === 'edit_user_data') {
				$user_id = $this->input->post('user_id', TRUE);
				$uDetails = $this->base_model->getOneRecord('brij_users', 'id', $user_id, '*');
				if (isset($uDetails) && !empty($uDetails)) {
					$response = array(
						'id' => $user_id,
						'dataContent' => $uDetails,
					);
				} else {
					$response = array(
						'id' => $user_id,
						'dataContent' => '',
					);
				}
			}

			//get edit page data
			if (!empty($request) && $request === 'edit_post_data') {
				$post_id = $this->input->post('post_id', TRUE);
				$postDetails = $this->base_model->getOneRecord('posts', 'id', $post_id, '*');
				if (isset($postDetails) && !empty($postDetails)) {
					$response = array(
						'id' => $post_id,
						'dataContent' => $postDetails,
					);
				} else {
					$response = array(
						'id' => $post_id,
						'dataContent' => '',
					);
				}
			}

			if (!empty($request) && $request === 'edit_career_data') {
				$post_id = $this->input->post('post_id', TRUE);
				$postDetails = $this->base_model->getOneRecord('career', 'id', $post_id, '*');
				if (isset($postDetails) && !empty($postDetails)) {
					$response = array(
						'id' => $post_id,
						'dataContent' => $postDetails,
					);
				} else {
					$response = array(
						'id' => $post_id,
						'dataContent' => '',
					);
				}
			}

			//get edit page data
			if (!empty($request) && $request === 'edit_testimonial_data') {
				$testimonial_id = $this->input->post('testimonial_id', TRUE);
				$postDetails = $this->base_model->getOneRecord('testimonials', 'id', $testimonial_id, '*');
				if (isset($postDetails) && !empty($postDetails)) {
					$response = array(
						'id' => $testimonial_id,
						'dataContent' => $postDetails,
					);
				} else {
					$response = array(
						'id' => $testimonial_id,
						'dataContent' => '',
					);
				}
			}

			if (!empty($request) && $request === 'edit_team_data') {
				$testimonial_id = $this->input->post('team_id', TRUE);
				$postDetails = $this->base_model->getOneRecord('team', 'id', $testimonial_id, '*');
				if (isset($postDetails) && !empty($postDetails)) {
					$response = array(
						'id' => $testimonial_id,
						'dataContent' => $postDetails,
					);
				} else {
					$response = array(
						'id' => $testimonial_id,
						'dataContent' => '',
					);
				}
			}
			//get edit page data
			if (!empty($request) && $request === 'edit_cs_data') {
				$cs_id = $this->input->post('cs_id', TRUE);
				$postDetails = $this->base_model->getOneRecord('case_studies', 'id', $cs_id, '*');
				if (isset($postDetails) && !empty($postDetails)) {
					$response = array(
						'id' => $cs_id,
						'dataContent' => $postDetails,
					);
				} else {
					$response = array(
						'id' => $cs_id,
						'dataContent' => '',
					);
				}
			}
			//get post name is available or not
			if (!empty($request) && $request === 'check-post-name') {
				$post_name = trim($this->input->post('post_name', TRUE));
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-post') {
					$postDetails = $this->base_model->getOneRecord('posts', 'post_title', $post_name, 'id');
					$slug = url_title($post_name, 'dash', true);
					if (!empty($postDetails) && isset($postDetails)) {
						$statusLink = 1;
						$slug = '';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink,
						'slug' => $slug
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-post') {
					$post_id = $this->input->post('post_id', TRUE);
					$where_con = array("post_title" => $post_name, "id !=" => $post_id);
					$postDetails = $this->base_model->getOneRecordWithWhere("posts", $where_con, 'id');
					// Use dashes to separate words;
					// third param is true to change all letters to lowercase
					$slug = url_title($post_name, 'dash', true);
					if (!empty($postDetails) && isset($postDetails)) {
						$statusLink = 1;
						$slug = '';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink,
						'slug' => $slug
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Page Title available does not working. Please try again!",
						'id' => $post_name,
						'dataContent' => ''
					);
				}
			}

			//get post name is available or not
			if (!empty($request) && $request === 'get-all-categories') {
				$id = $this->input->post('id', TRUE);
				$type = $this->input->post('type', TRUE);
				$search_criteria = array();
				$search_criteria["parent_id ="] = 0;
				$search_criteria["status ="] = 1;
				$search_criteria["type ="] = $type;
				$AllCatDetails = $this->base_model->getAllRows('categories', 'id ASC', $search_criteria, 'id,name');
				$search_criteria1["posts_id ="] = $id;
				$search_criteria1["p_c_type ="] = $type;
				$AllPostCatDetails = $this->base_model->getAllRows('post_categories', 'id ASC', $search_criteria1, 'posts_id,categories_id');
				$table = '<table class="table table-striped custom-table">
			<thead>
				<tr>
					<td>Select Categories <span class="text-danger">*</span> <a href="' . base_url('admin/categories_list?type=' . $type) . '" class="">Add Category</a></td>
					<td class="text-center" id="selectAll"><a href="javascript:void(0);">Check All</a></td>
				</tr>
			</thead>
			<tbody>';
				if (!empty($AllCatDetails)) {
					foreach ($AllCatDetails as $AllCatDetailsV) {
						$checked = "";
						if (!empty($AllPostCatDetails)) {
							foreach ($AllPostCatDetails as $AllPostCatDetailsV) {
								if ($AllCatDetailsV->id == $AllPostCatDetailsV->categories_id) {
									$checked = "checked = 'checked'";
								}
							}
						}
						$table .=	'<tr>
					<td>' . $AllCatDetailsV->name . '</td>
					<td class="text-center">
						<input ' . $checked . ' type="checkbox" class="required" name="category_id[]" id="chkFileds" value="' . $AllCatDetailsV->id . '">
					</td>
				</tr>';
					}
				} else {

					$table .=	'<tr>
					<td class="text-center">
						<a href="categories_list" class="btn btn-info btn-rounded pull-right"><i class="fa fa-plus"></i> Add Category</a>
						<span style="opacity:0;"><input type="checkbox" class="required" name="category_id[]" id="chkFileds" value=""></span>
					</td>
				</tr>';
				}
				$table .= '</tbody>
		</table>';
				$response = array(
					'dataContent' => $table,
					'msg' => 'Found'
				);
			}

			//get post name is available or not
			if (!empty($request) && $request === 'get-all-pages') {
				$id = $this->input->post('id', TRUE);
				$type = $this->input->post('type', TRUE);
				$search_criteria = array();
				$search_criteria["parent_id ="] = 0;
				$search_criteria["status ="] = 1;
				$AllPagesDetails = $this->base_model->getAllRows('pages', 'page_title ASC', $search_criteria, 'id,page_title');
				$where_con = array("id =" => $id);
				switch ($type) {
					case 'P':
						$typeS = 'Posts';
						$postDetails = $this->base_model->getOneRecordWithWhere("posts", $where_con, 'id,post_type,post_display_order');
						$TypeArray = (!empty($postDetails->post_type)) ? explode(',', $postDetails->post_type) : array();
						$OrderArray = (!empty($postDetails->post_display_order)) ? explode(',', $postDetails->post_display_order) : array();
						break;
					case 'T':
						$typeS = 'Testimonials';
						$postDetails = $this->base_model->getOneRecordWithWhere("testimonials", $where_con, 'id,type,ordering');
						$TypeArray = (!empty($postDetails->type)) ? explode(',', $postDetails->type) : array();
						$OrderArray = (!empty($postDetails->ordering)) ? explode(',', $postDetails->ordering) : array();
						break;
					case 'CS':
						$typeS = 'Case Studies';
						$postDetails = $this->base_model->getOneRecordWithWhere("case_studies", $where_con, 'id,case_study_type,case_study_display_order');
						$TypeArray = (!empty($postDetails->case_study_type)) ? explode(',', $postDetails->case_study_type) : array();
						$OrderArray = (!empty($postDetails->case_study_display_order)) ? explode(',', $postDetails->case_study_display_order) : array();
						break;
				}
				$options = '';
				if (!empty($AllPagesDetails)) {
					foreach ($AllPagesDetails as $AllPagesDetailsV) {
						$selected = "";
						if (!empty($TypeArray)) {
							foreach ($TypeArray as $TypeArrayV) {
								if ($TypeArrayV == $AllPagesDetailsV->id) {
									$selected = "selected = 'selected'";
								}
							}
						}
						$options .=	'<option value="' . $AllPagesDetailsV->id . '" ' . $selected . '>' . $AllPagesDetailsV->page_title . '</option>';
					}

					$options1 = '';
					if (!empty($OrderArray)) {
						foreach ($OrderArray as $OrderArrayV) {
							$options1 .= '<option value="' . $OrderArrayV . '" selected>' . $OrderArrayV . '</option>';
						}
					}
				} else {
					$options = $options1 = '';
				}

				$response = array(
					'dataContent' => $options,
					'dataContent1' => $options1,
					'msg' => 'Found'
				);
			}



			if (!empty($request) && $request === 'get-all-categories-post-status') {
				$cat_id = $this->input->post('cat_id', TRUE);
				$type = $this->input->post('type', TRUE);
				$where_con = array("id =" => $cat_id);
				$catDetails = $this->base_model->getOneRecordWithWhere("categories", $where_con, 'name');
				$search_criteria1["parent_id ="] = 0;
				$search_criteria1["id !="] = $cat_id;
				$search_criteria1["type ="] = $type;
				$AllCatDetails = $this->base_model->getAllRows('categories', 'id ASC', $search_criteria1, 'id,name');
				if (isset($cat_id) && $cat_id != '') {
					$search_criteria2['p_category.categories_id ='] = $cat_id;
					$search_criteria2['category.type ='] = $type;
				}
				switch ($type) {
					case 'P':
						$typeS = 'Posts';
						$categoriesPostList = $this->admin_model->getPosts($search_criteria2, $order_by = 'posts.id DESC');
						break;

					case 'T':
						$typeS = 'Testimonials';
						$categoriesPostList = $this->admin_model->getTestimonials($search_criteria2, $order_by = 'testimonials.id DESC');
						break;

					case 'CS':
						$typeS = 'Case Studies';
						$categoriesPostList = $this->admin_model->getCaseStudies($search_criteria2, $order_by = 'case_studies.id DESC');
						break;
					case 'TM':
						$typeS = 'Teams';
						$categoriesPostList = $this->admin_model->getTeam($search_criteria2, $order_by = 'team.id DESC');
						break;
					case 'CA':
						$typeS = 'Careers';
						$categoriesPostList = $this->admin_model->getCareer($search_criteria2, $order_by = 'career.id DESC');
						break;
					case 'CL':
						$typeS = 'Clients';
						$categoriesPostList = $this->admin_model->getClient($search_criteria2, $order_by = 'client.id DESC');
						break;
				}
				$div = '<div class="col-md-12">
				<div class="form-group">
					<label>You have specified this category for deletion: </label> <span class="text-danger">' . $catDetails->name . '</span></div>';
				$attr_disabled = " ";
				if (!empty($categoriesPostList)) {
					$attr_disabled = " disabled";

					$div .= '<div class="form-group">
					<label>What should be done with '.$typeS.' linked by this category?</label>
					<div class="radio">
						<label>
							<input type="radio" name="delete_type" id="delete_type" value="delete-all-posts"> <span class="text-danger">Delete all ' . $typeS . '.</span>
						</label>
					</div>
				</div>';
					if (!empty($AllCatDetails)) {
						$div .= '<div class="form-group">
					<div class="radio">
						<label>
							<input type="radio" name="delete_type" id="delete_type" value="assign-to"> <span class="text-info">Attribute all ' . strtolower($typeS) . ' to</span>
						</label>
					</div>
					<select id="assign_to_cat" name="assign_to_cat" class="select col-md-5">';
						foreach ($AllCatDetails as $key => $value) {
							if ($value->id != $cat_id) {

								$div .= '<option value="' . $value->id . '">' . $value->name . '</option>';
							}
						}
						$div .= '</select>
				</div>';
					}
				}
				$div .= '</div>
			<div class="m-t-20"> <a href="javascript:void(0);" class="btn btn-white" data-dismiss="modal">Close</a>						            		<input type="hidden" name="type" id="type" value="' . $typeS . '">
							<button type="submit" class="btn btn-danger" id="confirm" ' . $attr_disabled . '>Delete</button>							
						</div>';
				$response = array(
					'dataContent' => $div,
					'msg' => 'Found'
				);
			}


			//get post name is available or not
			if (!empty($request) && $request === 'check-cs-name') {
				$cs_name = trim($this->input->post('cs_name', TRUE));
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-cs') {
					$postDetails = $this->base_model->getOneRecord('case_studies', 'case_study_title', $cs_name, 'id');
					$slug = url_title($cs_name, 'dash', true);
					if (!empty($postDetails) && isset($postDetails)) {
						$statusLink = 1;
						$slug = '';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink,
						'slug' => $slug
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-cs') {
					$cs_id = $this->input->post('cs_id', TRUE);
					$where_con = array("case_study_title" => $cs_name, "id !=" => $cs_id);
					$postDetails = $this->base_model->getOneRecordWithWhere("case_studies", $where_con, 'id');
					// Use dashes to separate words;
					// third param is true to change all letters to lowercase
					$slug = url_title($cs_name, 'dash', true);
					if (!empty($postDetails) && isset($postDetails)) {
						$statusLink = 1;
						$slug = '';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink,
						'slug' => $slug
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Page Title available does not working. Please try again!",
						'id' => $cs_name,
						'dataContent' => ''
					);
				}
			}

			//get user email id is available or not
			if (!empty($request) && $request === 'check-email-name') {
				$emailId = trim($this->input->post('emailId', TRUE));
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-user') {
					$uDetails = $this->base_model->getOneRecord('brij_users', 'email_id', $emailId, 'id');
					if (!empty($uDetails) && isset($uDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-user') {
					$user_id = $this->input->post('user_id', TRUE);
					$where_con = array("email_id" => $emailId, "id !=" => $user_id);
					$uDetails = $this->base_model->getOneRecordWithWhere("brij_users", $where_con, 'id');
					if (!empty($uDetails) && isset($uDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Email ID available does not working. Please try again!",
						'id' => $emailId,
						'dataContent' => ''
					);
				}
			}

			//get user mobile number is available or not
			if (!empty($request) && $request === 'check-phone-no-name') {
				$phone_no = trim($this->input->post('phone_no', TRUE));
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-user') {
					$uDetails = $this->base_model->getOneRecord('brij_users', 'phone_no', $phone_no, 'id');
					if (!empty($uDetails) && isset($uDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-user') {
					$user_id = $this->input->post('user_id', TRUE);
					$where_con = array("phone_no" => $phone_no, "id !=" => $user_id);
					$uDetails = $this->base_model->getOneRecordWithWhere("brij_users", $where_con, 'id');
					if (!empty($uDetails) && isset($uDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Mobile Number available does not working. Please try again!",
						'id' => $phone_no,
						'dataContent' => ''
					);
				}
			}

			//get sub category list by cat id
			if (!empty($request) && $request === 'get_city_category') {
				$state_id = $this->input->post('state_id', TRUE);
				$conditions['status ='] = 1;
				$conditions['city_name !='] = '';
				$conditions['state_id ='] = $state_id;
				$cData = $this->base_model->getAllRows('brij_cities', 'city_name ASC', $conditions);
				//echo $location;
				//die;
				$options = '<option value="">Select City</option>';
				if ($cData) {
					foreach ($cData as $cData) {
						$options .= '<option value="' . $cData->id . '">' . $cData->city_name . '</option>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'cityList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'cityList' => $options,
						'dataStatus' => '0'
					);
				}
			}


			//get admin user email id is available or not
			if (!empty($request) && $request === 'check-admin-email-name') {
				$emailId = trim($this->input->post('emailId', TRUE));
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-user-admin') {
					$uDetails = $this->base_model->getOneRecord('brij_admin', 'user_mail', $emailId, 'user_id');
					if (!empty($uDetails) && isset($uDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-user-admin') {
					$user_id = $this->input->post('user_id', TRUE);
					$where_con = array("user_mail" => $emailId, "user_id !=" => $user_id);
					$uDetails = $this->base_model->getOneRecordWithWhere("brij_admin", $where_con, 'user_id');
					if (!empty($uDetails) && isset($uDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Email ID available does not working. Please try again!",
						'id' => $emailId,
						'dataContent' => ''
					);
				}
			}

			//get admin user mobile number is available or not
			if (!empty($request) && $request === 'check-admin-phone-no-name') {
				$phone_no = trim($this->input->post('phone_no', TRUE));
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-user-admin') {
					$uDetails = $this->base_model->getOneRecord('brij_admin', 'user_phone_no', $phone_no, 'user_id');
					if (!empty($uDetails) && isset($uDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-user-admin') {
					$user_id = $this->input->post('user_id', TRUE);
					$where_con = array("user_phone_no" => $phone_no, "user_id !=" => $user_id);
					$uDetails = $this->base_model->getOneRecordWithWhere("brij_admin", $where_con, 'user_id');
					if (!empty($uDetails) && isset($uDetails)) {
						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Mobile Number available does not working. Please try again!",
						'id' => $phone_no,
						'dataContent' => ''
					);
				}
			}


			//get edit admin user data
			if (!empty($request) && $request === 'edit_admin_user_data') {
				$user_id = $this->input->post('user_id', TRUE);
				$uDetails = $this->base_model->getOneRecord('brij_admin', 'user_id', $user_id, '*');
				if (isset($uDetails) && !empty($uDetails)) {
					$conditions_des1['status ='] = 1;
					$arrPermissionsData = $this->base_model->getAllRows('brij_permissions', 'permission_name ASC', $conditions_des1);
					$conditions_des2['user_id ='] = $user_id;
					$arrUserPermissionsData = $this->base_model->getAllRows('brij_admin_permissions', 'id DESC', $conditions_des2);
					$permissionViews = '<table class="table table-striped custom-table">
						<thead>
							<tr>
								<th>Module Permission</th>
								<th class="text-center">Read</th>
								<th class="text-center">Write</th>
								<th class="text-center">Create</th>
								<th class="text-center">Delete</th>
								<!--<th class="text-center">Import</th>
								<th class="text-center">Export</th>-->
							</tr>
						</thead>
						<tbody>';
					//pr($arrPermissionsData);
					$id = 1;
					foreach ($arrPermissionsData as $cap) {
						$access_read = $access_write = $access_create = $access_delete = $access_import = $access_export = " ";
						if (isset($arrUserPermissionsData) && !empty($arrUserPermissionsData)) {
							foreach ($arrUserPermissionsData as $arrUserPermissionsDatas) {
								if ($cap->permission_shortname == $arrUserPermissionsDatas->module_name) {

									$access_read = ($arrUserPermissionsDatas->access_read == 'Yes') ? 'checked' : '';
									$access_write = ($arrUserPermissionsDatas->access_write == 'Yes') ? 'checked' : '';
									$access_create = ($arrUserPermissionsDatas->access_create == 'Yes') ? 'checked' : '';
									$access_delete = ($arrUserPermissionsDatas->access_delete == 'Yes') ? 'checked' : '';
									//$access_import= ($arrUserPermissionsDatas->access_import == 'Yes')?'checked':'';
									//$access_export= ($arrUserPermissionsDatas->access_export == 'Yes')?'checked':'';

								}
							}
						}
						$permissionViews .= '<tr>
								<td><input type="hidden"  name="permission_id[]" value="' . $cap->id . '"/>' . ucwords($cap->permission_name) . '</td>
								<td class="text-center">
									<input class="module_access_edit" type="checkbox" name="read_' . $cap->id . '" value="Yes" ' . $access_read . '>
								</td>
								<td class="text-center">
									<input class="module_access_edit" type="checkbox" name="write_' . $cap->id . '" value="Yes" ' . $access_write . '>
								</td>
								<td class="text-center">
									 <input class="module_access_edit" type="checkbox" name="create_' . $cap->id . '" value="Yes" ' . $access_create . '>
								</td>
								<td class="text-center">
									 <input class="module_access_edit" type="checkbox" name="delete_' . $cap->id . '" value="Yes" ' . $access_delete . '>
								</td>
								<!--<td class="text-center">
									<input class="" type="checkbox" name="import_' . $cap->id . '" value="Yes" ' . $access_import . '>
								</td>
								<td class="text-center">
									 <input class="" type="checkbox" name="export_' . $cap->id . '" value="Yes" ' . $access_export . '>
								</td>-->
							</tr>';
						$id = 1;
					}
					$permissionViews .= '</tbody>
					</table><input type="checkbox" class="checkAllEdit" /> <b>Check All</b>';
					$response = array(
						'id' => $user_id,
						'dataContent' => $uDetails,
						'permissionView' => $permissionViews
					);
				} else {
					$response = array(
						'id' => $user_id,
						'dataContent' => '',
						'permissionView' => ''
					);
				}
			}

			if (!empty($request) && $request === 'delete_template_image_data') {
				$temp_image_id = $this->input->post('temp_image_id', TRUE);
				$tempImageDetails = $this->base_model->getOneRecord('brij_newsletter_templates', 'id', $temp_image_id, 'template_image');
				if (isset($tempImageDetails) && !empty($tempImageDetails)) {
					$tempfilename = 'uploads/newsletter_template_images/' . $tempImageDetails->template_image;
					if (file_exists($tempfilename) && !empty($tempImageDetails->template_image) && isset($tempImageDetails->template_image)) {
						$_image = $tempImageDetails->template_image;
						unlink(realpath('uploads/newsletter_template_images/' . $_image));
						unlink(realpath('uploads/newsletter_template_images/large/' . $_image));
						unlink(realpath('uploads/newsletter_template_images/medium/' . $_image));
						unlink(realpath('uploads/newsletter_template_images/small/' . $_image));
					}
					$where_conditions_h = array('id' => $temp_image_id);
					$deleted = $this->base_model->deleteWithWhereConditions('brij_newsletter_templates', $where_conditions_h);
					$response = array(
						'id' => $temp_image_id,
						'dataContent' => 1,
						'message' => 'Template Image has been deleted successfully.'
					);
				} else {
					$response = array(
						'id' => $temp_image_id,
						'dataContent' => 0,
						'message' => 'Template Image does not deleted. Please try again!'
					);
				}
			}


			//get template data data
			if (!empty($request) && $request === 'get_template_image_data') {
				$temp_image_id = $this->input->post('temp_image_id', TRUE);
				$tempDetails = $this->base_model->getOneRecord('brij_newsletter_templates', 'id', $temp_image_id, '*');
				if (isset($tempDetails) && !empty($tempDetails)) {
					$response = array(
						'id' => $temp_image_id,
						'dataContent' => $tempDetails,
					);
				} else {
					$response = array(
						'id' => $temp_image_id,
						'dataContent' => '',
					);
				}
			}

			//user delete with related table data
			if (!empty($request) && $request === 'delete-user-list') {
				$user_id = $this->input->post('user_id', TRUE);
				$userDetails = $this->base_model->getOneRecord('users', 'id', $user_id, 'id');
				if (isset($userDetails) && !empty($userDetails)) {
					$user_id = $userDetails->id;
					$where_conditions_a = array('user_id' => $user_id);
					$query1 = $this->base_model->deleteWithWhereConditions('assign_users', $where_conditions_a);
					$where_conditions_b = array('user_id' => $user_id);
					$query2 = $this->base_model->deleteWithWhereConditions('notification_users', $where_conditions_b);
					$where_conditions_c = array('user_id' => $user_id);
					$query3 = $this->base_model->deleteWithWhereConditions('sales_mis_report', $where_conditions_c);
					//$loanData=$this->base_model->getRowWhere('user_loans', 'user_id', $user_id, $order_by='id DESC');
					//$ids_exp=array();
					//if (count($loanData) > 0 && !empty($loanData)){
					//foreach ($loanData as $key=>$value){
					//$ids_exp[]=$value->id;
					//}
					//}
					$where_conditions_d = array('user_id' => $user_id);
					$query4 = $this->base_model->deleteWithWhereConditions('shop_location_by_sales', $where_conditions_d);
					$where_conditions_e = array('user_id' => $user_id);
					$query5 = $this->base_model->deleteWithWhereConditions('user_day_reporting', $where_conditions_e);
					$where_conditions_f = array('user_id' => $user_id);
					$query6 = $this->base_model->deleteWithWhereConditions('user_department_shop', $where_conditions_f);
					$where_conditions_g = array('user_id' => $user_id);
					$query7 = $this->base_model->deleteWithWhereConditions('user_leave_request', $where_conditions_g);
					$where_conditions_h = array('user_id' => $user_id);
					$query8 = $this->base_model->deleteWithWhereConditions('user_location_track', $where_conditions_h);
					$where_conditions_i = array('user_id' => $user_id);
					$query9 = $this->base_model->deleteWithWhereConditions('user_sales_mis_report', $where_conditions_i);
					$where_conditions_j = array('user_id' => $user_id);
					$query10 = $this->base_model->deleteWithWhereConditions('user_shops', $where_conditions_j);
					$where_conditions_k = array('id' => $user_id);
					$query11 = $this->base_model->deleteWithWhereConditions('users', $where_conditions_k);
					if ($query1 || $query2 || $query3 || $query4 || $query5 || $query6 || $query7 || $query8 || $query9 || $query10 || $query11) {
						//if($query6){
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "User has been removed successfully.",
							'id' => $user_id,
							'dataContent' => '1',
						);
					} else {
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "User does not removed. Please try again!",
							'id' => $user_id,
							'dataContent' => '',
						);
					}
				}
			}
			//re-assign shops to new users
			if (!empty($request) && $request === 'shop_reassign_users') {
				$shop_id = $this->input->post('shop_id', TRUE);
				$user_id = $this->input->post('user_id', TRUE);
				$conditions['is_active ='] = 1;
				$conditions['full_name !='] = '';
				$usersData = $this->base_model->getAllRows("users", $order_by = 'full_name ASC', $conditions);
				//print_r($usersData);
				//die;
				//echo $location;
				//die;

				$options = '<option value="">Select Users</option>';
				if ($usersData) {
					$currentUserDetails = $this->base_model->getOneRecord('users', 'id', $user_id, 'full_name');
					$currentShopDetails = $this->base_model->getOneRecord('shops', 'id', $shop_id, 'shop_name');
					foreach ($usersData as $usersData) {
						if ($usersData->id != $user_id) {
							$options .= '<option value="' . $usersData->id . '">' . $usersData->full_name . '</option>';
						}
					}
					$html = "<form method='POST' id='assign-shops-users' action='reassign_shop_users'>
		<input type='hidden' name='request' id='request' value='reassign-new-users-shop'>
		<input type='hidden' name='current_usr_id' id='current_usr_id' value='" . $user_id . "'>
		<input type='hidden' name='shop_id' id='shop_id' value='" . $shop_id . "'>
		<div class='row'>
		<div class='col-md-12'>
		<div class='col-md-6' style='padding: 4px 7px 1px 7px;background-color: #c0933a;color: #fff;margin-right: 10px;width: 48%'><label class='control-label'>Shop Name:</label> " . $currentShopDetails->shop_name . "</div>
		<div class='col-md-6' style='padding: 4px 7px 1px 7px;background-color: #c0933a;color: #fff;margin-right: 10px;width: 48%'><label class='control-label'>Currently Assigned:</label> " . $currentUserDetails->full_name . "</div>
		<div class='clear'></div>

		<div class='col-md-6' style='padding-left: 0;margin-top: 10px;'><label class='control-label'>Select User<span class='astric_required'>*</span>:</label>
		<select class='required' id='user_id' name='user_id' required>
		" . $options . "
		</select></div>
		<!-- Preview-->
		<div id='preview'></div>
		</div>
		<div class='clear'></div>
		<div class='col-md-12' style='padding-left: 15px;'>
		<input type='submit' class='btn btn-success btn-sm' value='Assign' id='assign-users-shops' style='margin-top: 24px; margin-bottom: 0;'>
		<span class='astric_required pull-right' style='font-size:11px;'>(*) Indicates required field</span>
		</div>
		</div>
		</form>
		<script>
		$(function () {
			var count = $('table tr.row_selected').index();
			//alert(count);
			/* $('table').dataTable({
				stateSave: true
			});
			*/
			$('form#assign-shops-users select').select2();
		});
		</script>";

					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $html,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $options,
						'dataStatus' => '0'
					);
				}
			}

			//assign shops to users
			if (!empty($request) && $request === 'shop_assign_users') {
				$shop_id = $this->input->post('shop_id', TRUE);
				$conditions['is_active ='] = 1;
				$conditions['full_name !='] = '';
				$usersData = $this->base_model->getAllRows("users", $order_by = 'full_name ASC', $conditions);
				//print_r($usersData);
				//die;
				//echo $location;
				//die;

				$options = '<option value="">Select Users</option>';
				if ($usersData) {
					$currentShopDetails = $this->base_model->getOneRecord('shops', 'id', $shop_id, 'shop_name');
					foreach ($usersData as $usersData) {
						$options .= '<option value="' . $usersData->id . '">' . $usersData->full_name . '</option>';
					}
					$html = "<form method='POST' id='assign-shops-new-users' action='reassign_shop_users'>
			<input type='hidden' name='request' id='request' value='assign-new-users-shop'>
			<input type='hidden' name='shop_id' id='shop_id' value='" . $shop_id . "'>
			<div class='row'>
			<div class='col-md-12'>
			<div class='col-md-6' style='padding: 4px 7px 1px 7px;background-color: #c0933a;color: #fff;margin-right: 10px;width: 48%'><label class='control-label'>Shop Name:</label> " . $currentShopDetails->shop_name . "</div>
			<div class='col-md-6' style='padding: 4px 7px 1px 7px;background-color: #c0933a;color: #fff;margin-right: 10px;width: 48%'><label class='control-label'>Currently Assigned:</label> Not Assigned</div>
			<div class='clear'></div>

			<div class='col-md-6' style='padding-left: 0;margin-top: 10px;'><label class='control-label'>Area Name<span class='astric_required'>*</span>:</label>
			<input type='text' name='area_name'  class='form-control' placeholder='Enter area name' id='area_name' value='' required></div>
			<div class='clear'></div>
			<div class='col-md-6' style='padding-left: 0;margin-top: 10px;'><label class='control-label'>Select User<span class='astric_required'>*</span>:</label>
			<select class='required' id='user_id' name='user_id' required>
			" . $options . "
			</select></div>
			<!-- Preview-->
			<div id='preview'></div>
			</div>
			<div class='clear'></div>
			<div class='col-md-12' style='padding-left: 15px;'>
			<input type='submit' class='btn btn-success btn-sm' value='Assign' id='assign-users-shops' style='margin-top: 24px; margin-bottom: 0;'>
			<span class='astric_required pull-right' style='font-size:11px;'>(*) Indicates required field</span>
			</div>
			</div>
			</form>
			<script>
			$(function () {
				var count = $('table tr.row_selected').index();
				//alert(count);
				/* $('table').dataTable({
					stateSave: true
				});
				*/
				$('form#assign-shops-new-users select').select2();
			});
			</script>";

					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $html,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $options,
						'dataStatus' => '0'
					);
				}
			}
			//notify ajax counter
			if (!empty($request) && $request === 'admin_notify') {
				$user_id = $this->input->post('user_id', TRUE);
				$user_role = $this->input->post('user_role', TRUE);
				$where_conditions_a = array('is_read' => 0);
				if (isset($user_role) && !empty($user_role) && $user_role != 1) {
					$where_conditions_a = array('is_read' => 0);
				}
				$notifyMessage = $this->base_model->getAllRows("admin_notification", $order_by = 'id DESC', $where_conditions_a);
				$counter = count($notifyMessage);
				//print_r($loanDetils);
				$dataMessage = '';
				$dataMessages = '<li>
			<a href="javascript:void(0);">
			<div>
			Message Not Found
			</div>
			</a>
			</li>';
				if ($notifyMessage) {
					foreach ($notifyMessage as $notifyMessage) {
						$id = $notifyMessage->id;
						$sent_date = $this->humanTiming(strtotime($notifyMessage->sent_date));
						$onclick = "updateNotify(" . $id . ")";
						$template = $notifyMessage->text_message;
						$template = str_replace('{DATE}', $sent_date, $template);
						$template = str_replace('{UPDATEFUNCTION}', $onclick, $template);
						$dataMessage .= '<li>' . $template . '</li><li class="divider"></li>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'id' => $user_id,
						'dataContent' => $dataMessage,
						'counetr' => $counter,
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Data does not found. Please try again!",
						'id' => $user_id,
						'dataContent' => '',
						'dataContentMessagee' => $dataMessages,
						'counetr' => 0,
					);
				}
			}

			//notify ajax update counter
			if (!empty($request) && $request === 'admin_notify_update') {
				$notify_id = $this->input->post('notify_id', TRUE);
				$update_data = array("is_read" => 1);
				$where_conditions = array("id" => $notify_id);
				$res = $this->base_model->update_entry('admin_notification', $update_data, $where_conditions);
				if ($res) {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'id' => $notify_id,
						'dataContent' => 1,
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Data does not found. Please try again!",
						'id' => $user_id,
						'dataContent' => '',
					);
				}
			}
			//notify ajax update counter for location
			if (!empty($request) && $request === 'admin_notify_location') {
				$user_id = $this->input->post('user_id', TRUE);
				$user_role = $this->input->post('user_role', TRUE);
				$where_conditions_a = array('is_status' => 3);
				if (isset($user_role) && !empty($user_role) && $user_role != 1) {
					$where_conditions_a = array('is_status' => 3);
				}
				$notifyLocationMessage = $this->base_model->getAllRows("shop_location_by_sales", $order_by = 'DATE(created_at) ASC', $where_conditions_a);
				$counter = count($notifyLocationMessage);
				if ($notifyLocationMessage) {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'id' => $user_role,
						'dataContent' => 1,
						'counetr' => $counter,
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Data does not found. Please try again!",
						'id' => $user_role,
						'dataContent' => '',
						'counetr' => $counter,
					);
				}
			}

			//shop delete with related table data
			if (!empty($request) && $request === 'delete-shops-list') {
				$shop_id = $this->input->post('shop_id', TRUE);
				$shopDetails = $this->base_model->getOneRecord('shops', 'id', $shop_id, 'id');
				if (count($shopDetails) > 0 && !empty($shopDetails)) {
					$shop_id = $shopDetails->id;
					$where_conditions_a = array('shop_id' => $shop_id);
					$query1 = $this->base_model->deleteWithWhereConditions('sales_mis_report', $where_conditions_a);
					$where_conditions_b = array('shop_id' => $shop_id);
					$query2 = $this->base_model->deleteWithWhereConditions('shop_location_by_sales', $where_conditions_b);
					$where_conditions_c = array('shop_id' => $shop_id);
					$query3 = $this->base_model->deleteWithWhereConditions('user_day_reporting', $where_conditions_c);
					//$loanData=$this->base_model->getRowWhere('user_loans', 'user_id', $user_id, $order_by='id DESC');
					//$ids_exp=array();
					//if (count($loanData) > 0 && !empty($loanData)){
					//foreach ($loanData as $key=>$value){
					//$ids_exp[]=$value->id;
					//}
					//}
					//$query4=$this->base_model->deleteWithWhereInConditions('user_loan_emi', 'loan_id', $ids_exp);
					//$query5=$this->base_model->deleteWithWhereInConditions('user_loans', 'id', $ids_exp);
					//$where_conditions_n=array('user_id'=>$user_id);
					//$query7=$this->base_model->deleteWithWhereConditions('notifications', $where_conditions_n);
					$where_conditions_d = array('shop_id' => $shop_id);
					$query4 = $this->base_model->deleteWithWhereConditions('user_department_shop', $where_conditions_d);
					$where_conditions_e = array('shop_id' => $shop_id);
					$query5 = $this->base_model->deleteWithWhereConditions('user_location_track', $where_conditions_e);
					$where_conditions_f = array('shop_id' => $shop_id);
					$query6 = $this->base_model->deleteWithWhereConditions('user_sales_mis_report', $where_conditions_f);
					$where_conditions_g = array('shop_id' => $shop_id);
					$query7 = $this->base_model->deleteWithWhereConditions('user_shops', $where_conditions_g);
					$where_conditions_h = array('id' => $shop_id);
					$query8 = $this->base_model->deleteWithWhereConditions('shops', $where_conditions_h);
					if ($query1 || $query2 || $query3 || $query4 || $query5 || $query6 || $query7 || $query8) {
						//if($query6){
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "Shop has been removed successfully.",
							'id' => $shop_id,
							'dataContent' => '1',
						);
					} else {
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "Shop does not removed. Please try again!",
							'id' => $shop_id,
							'dataContent' => '',
						);
					}
				}
			}
			//product delete with related table data
			if (!empty($request) && $request === 'delete-product-list') {
				$product_id = $this->input->post('product_id', TRUE);
				$productDetails = $this->base_model->getOneRecord('products', 'id', $product_id, 'id');
				if (count($productDetails) > 0 && !empty($productDetails)) {
					$product_id = $productDetails->id;
					$where_conditions_sp = array('product_id' => $product_id);
					$query1 = $this->base_model->deleteWithWhereConditions('state_products', $where_conditions_sp);
					$where_conditions_r = array('product_id' => $product_id);
					$query2 = $this->base_model->deleteWithWhereConditions('sales_mis_report', $where_conditions_r);
					$where_conditions_ra = array('product_id' => $product_id);
					$query3 = $this->base_model->deleteWithWhereConditions('user_day_reporting', $where_conditions_ra);
					//$loanData=$this->base_model->getRowWhere('user_loans', 'user_id', $user_id, $order_by='id DESC');
					//$ids_exp=array();
					//if (count($loanData) > 0 && !empty($loanData)){
					//foreach ($loanData as $key=>$value){
					//$ids_exp[]=$value->id;
					//}
					//}
					//$query4=$this->base_model->deleteWithWhereInConditions('user_loan_emi', 'loan_id', $ids_exp);
					//$query5=$this->base_model->deleteWithWhereInConditions('user_loans', 'id', $ids_exp);
					$where_conditions_n = array('product_id' => $product_id);
					$query4 = $this->base_model->deleteWithWhereConditions('user_sales_mis_report', $where_conditions_n);
					if (!empty($productDetails->image) && isset($productDetails->image)) {
						$_image = $productDetails->image;
						unlink(realpath('uploads/product_images/' . $_image));
						unlink(realpath('uploads/product_images/medium/' . $_image));
					}
					$where_conditions_u = array('id' => $product_id);
					$query5 = $this->base_model->deleteWithWhereConditions('products', $where_conditions_u);
					if ($query1 || $query2 || $query3 || $query4 || $query5) {
						//if($query1 || $query6){
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "Product has been removed successfully.",
							'id' => $product_id,
							'dataContent' => '1',
						);
					} else {
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "Product does not removed. Please try again!",
							'id' => $product_id,
							'dataContent' => '',
						);
					}
				}
			}
			//sub admin delete with related table data
			if (!empty($request) && $request === 'delete-admin-list') {
				$admin_id = $this->input->post('admin_id', TRUE);
				$subAdminDetails = $this->base_model->getOneRecord('admin', 'user_id', $admin_id, 'user_id,user_role');
				if (isset($subAdminDetails) && !empty($subAdminDetails)) {
					$admin_id = $subAdminDetails->user_id;
					$user_role = $subAdminDetails->user_role;
					/* switch($user_role){
				case 2:
				$where_conditions_sp=array('sales_area_manager_id'=>$admin_id);
				$query1=$this->base_model->deleteWithWhereConditions('assign_users',$where_conditions_sp);
				break;

				case 3:
				$where_conditions_r=array('state_head_id'=>$admin_id);
				$query2=$this->base_model->deleteWithWhereConditions('assign_users',$where_conditions_r);
				break;

				case 6:
				$where_conditions_ra=array('sale_executive_id'=>$admin_id);
				$query3=$this->base_model->deleteWithWhereConditions('assign_users',$where_conditions_ra);
				break;

			} */
					$where_conditions_sp = array('sales_area_manager_id' => $admin_id);
					$query1 = $this->base_model->deleteWithWhereConditions('assign_users', $where_conditions_sp);
					$where_conditions_r = array('state_head_id' => $admin_id);
					$query2 = $this->base_model->deleteWithWhereConditions('assign_users', $where_conditions_r);
					$where_conditions_ra = array('sale_executive_id' => $admin_id);
					$query3 = $this->base_model->deleteWithWhereConditions('assign_users', $where_conditions_ra);
					//$loanData=$this->base_model->getRowWhere('user_loans', 'user_id', $user_id, $order_by='id DESC');
					//$ids_exp=array();
					//if (count($loanData) > 0 && !empty($loanData)){
					//foreach ($loanData as $key=>$value){
					//$ids_exp[]=$value->id;
					//}
					//}
					//$query4=$this->base_model->deleteWithWhereInConditions('user_loan_emi', 'loan_id', $ids_exp);
					//$query5=$this->base_model->deleteWithWhereInConditions('user_loans', 'id', $ids_exp);
					//$where_conditions_n=array('user_id'=>$user_id);
					//$query7=$this->base_model->deleteWithWhereConditions('notifications', $where_conditions_n);
					$where_conditions_u = array('user_id' => $admin_id);
					$query4 = $this->base_model->deleteWithWhereConditions('admin', $where_conditions_u);
					if ($query1 || $query2 || $query3 || $query4) {
						//if($query6){
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "Sub Administrator has been removed successfully.",
							'id' => $admin_id,
							'dataContent' => '1',
						);
					} else {
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "Sub Administrator does not removed. Please try again!",
							'id' => $admin_id,
							'dataContent' => '',
						);
					}
				}
			}
			//relationship manager view
			if (!empty($request) && $request === 'relationship_view') {
				$relationship_id = $this->input->post('relationship_id', TRUE);
				$relationDetils = $this->base_model->getOneRecord('admin', 'user_id', $relationship_id, '*');
				//print_r($loanDetils);
				if ($relationDetils) {
					$relationDetils = '
			<div class="row">
			<div class="form-group">
			<div class="col-md-3 col-xs-12"><label class="control-label">Name:</label></div>
			<div class="col-md-9 col-xs-12">' . $relationDetils->screen_name . '</div>
			</div>
			</div>
			<div class="row">
			<div class="form-group">
			<div class="col-md-3 col-xs-12"><label class="control-label">Email:</label></div>
			<div class="col-md-9 col-xs-12">' . $relationDetils->user_mail . '</div>
			</div>
			</div>
			<div class="row">
			<div class="form-group">
			<div class="col-md-3 col-xs-12"><label class="control-label">Phone Number:</label></div>
			<div class="col-md-9 col-xs-12">' . $relationDetils->user_phone_no . '</div>
			</div>
			</div>';
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'id' => $relationship_id,
						'dataContent' => $relationDetils
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Loan details does not found. Please try again!",
						'id' => $relationship_id,
						'dataContent' => ''
					);
				}
			}
			//check sale executive email id available yes or no
			if (!empty($request) && $request === 'user-sale-executive-email') {
				$email = $this->input->post('email', TRUE);
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-email') {
					$UserDetails = $this->base_model->getOneRecord('users', 'email', $email, 'id');
					if (!empty($UserDetails) && count($UserDetails) > 0) {

						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-email') {
					$user_id = $this->input->post('user_id', TRUE);
					$where_con = array("email" => $email, "id !=" => $user_id);
					$UserDetails = $this->base_model->getOneRecordWithWhere("users", $where_con, 'id');
					if (!empty($UserDetails) && count($UserDetails) > 0) {

						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User email available does not working. Please try again!",
						'id' => $email,
						'dataContent' => ''
					);
				}
			}

			//check admin email id available yes or no
			if (!empty($request) && $request === 'user-sale-executive-email-admin') {
				$email = $this->input->post('email', TRUE);
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-email') {
					$UserDetails = $this->base_model->getOneRecord('admin', 'user_mail', $email, 'user_id');
					if (!empty($UserDetails) && count($UserDetails) > 0) {

						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-email') {
					$user_id = $this->input->post('user_id', TRUE);
					$where_con = array("user_mail" => $email, "user_id !=" => $user_id);
					$UserDetails = $this->base_model->getOneRecordWithWhere("admin", $where_con, 'user_id');
					if (!empty($UserDetails) && count($UserDetails) > 0) {

						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User email available does not working. Please try again!",
						'id' => $email,
						'dataContent' => ''
					);
				}
			}

			//get user list by state id
			if (!empty($request) && $request === 'view_user_data') {
				$state_id = $this->input->post('state_id', TRUE);
				$conditions['is_active ='] = 1;
				$conditions['full_name !='] = '';
				$conditions['state_id ='] = $state_id;
				$stateUserData = $this->base_model->getAllRows('users', 'full_name ASC', $conditions);
				$stateProductData = $this->admin_model->getStateAllProducts($where_column = array("state_products.state_id" => $state_id, "products.is_active" => 1), 'product_name ASC');
				//echo $location;
				//die;
				$options = '<option value="">Select User</option>';
				$options1 = '<option value="">Select Product</option>';
				if ($stateUserData || $stateProductData) {
					foreach ($stateUserData as $userData) {
						$options .= '<option value="' . $userData->id . '">' . $userData->full_name . '</option>';
					}

					foreach ($stateProductData as $stateProductData) {
						$options1 .= '<option value="' . $stateProductData->id . '">' . $stateProductData->product_name . '</option>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'userList' => $options,
						'productUserList' => $options1,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'userList' => $options,
						'productUserList' => $options1,
						'dataStatus' => '0'
					);
				}
			}

			//user departments list
			if (!empty($request) && $request === 'view_user_dept_data') {
				$user_id = $this->input->post('user_id', TRUE);
				$conditions['user_id ='] = $user_id;
				$deptList = $this->admin_model->getDeptAllShop($conditions, 'departments.dept_name ASC', 'user_department_shop.dept_id');
				//print_r($deptList);
				//die;
				//echo $location;
				//die;
				$options = '<option value="">Select Department</option>';
				if ($deptList || $deptList) {
					foreach ($deptList as $deptList) {
						$options .= '<option value="' . $deptList->dept_id . '">' . $deptList->dept_name . '</option>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'deptList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'deptList' => $options,
						'dataStatus' => '0'
					);
				}
			}
			//user departments shop list
			if (!empty($request) && $request === 'view_user_dept_shop_data') {
				$user_id = $this->input->post('user_id', TRUE);
				$dept_id = $this->input->post('dept_id', TRUE);
				$shopsList = $this->admin_model->getDeptShop($where = array('user_department_shop.dept_id' => $dept_id, 'user_department_shop.user_id' => $user_id), $order_by = 'shop_name ASC');
				//print_r($shopsList);
				//die;
				//echo $location;
				//die;
				$options = '<option value="">Select Shop</option>';
				if ($shopsList || $shopsList) {
					foreach ($shopsList as $shopsList) {
						$options .= '<option value="' . $shopsList->id . '">' . $shopsList->shop_name . '</option>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'shopList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'shopList' => $options,
						'dataStatus' => '0'
					);
				}
			}
			//get state product list
			if (!empty($request) && $request === 'view_user_shop_product_data') {
				$state_id = $this->input->post('state_id', TRUE);
				$conditions['is_active ='] = 1;
				$conditions['full_name !='] = '';
				$conditions['state_id ='] = $state_id;
				$stateProductData = $this->admin_model->getStateAllProducts($where_column = array("state_products.state_id" => $state_id, "products.is_active" => 1), 'product_name ASC');
				//echo $location;
				//die;
				$options = '<option value="">Select Product</option>';
				if ($stateProductData) {
					foreach ($stateProductData as $stateProductData) {
						$options .= '<option value="' . $stateProductData->id . '">' . $stateProductData->product_name . '</option>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'productUserList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'productUserList' => $options,
						'dataStatus' => '0'
					);
				}
			}

			//get user list by user type
			if (!empty($request) && $request === 'view_user_type') {
				$type_id = $this->input->post('type_id', TRUE);
				$conditions['is_active ='] = 1;
				$conditions['full_name !='] = '';
				$conditions['user_type ='] = $type_id;
				$userData = $this->base_model->getAllRows('users', 'full_name ASC', $conditions);
				//echo $location;
				//die;
				if ($userData) {
					$options = '';
					foreach ($userData as $userData) {
						$options .= '<option value="' . $userData->id . '">' . $userData->full_name . '</option>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'userList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}

			//get shop list by department id
			if (!empty($request) && $request === 'view_dept_shop-old') {
				$dept_id = $this->input->post('dept_id', TRUE);
				$search_query = $this->input->post('search_query', TRUE);
				$clicks = $this->input->post('clicks', TRUE);
				$conditions['is_active ='] = 1;
				$conditions['shop_name !='] = '';
				$conditions['dept_id ='] = $dept_id;
				if (isset($search_query) && !empty($search_query)) {
					$conditions['shop_name LIKE'] = '%' . trim($search_query) . '%';
				}
				$deptShopData = $this->base_model->getAllRows('shops', 'shop_name ASC', $conditions);
				//echo $this->db->last_query();
				//echo $location;
				//die;
				$array_click = explode(',', $clicks);
				if ($deptShopData) {
					$options = '<ul class="check_list notification_checkLst">';
					foreach ($deptShopData as $deptShopData) {
						//$options .='<option value="'.$deptShopData->id.'">'.$deptShopData->shop_name.'</option>';
						$checkedClass = '';
						$checkedClassSpan = '';
						if (count($array_click) > 0) {
							foreach ($array_click as $key => $value) {
								if ($value == $deptShopData->id) {
									$checkedClass = 'checked="checked"';
									$checkedClassSpan = 'class="checked"';
								}
							}
						}
						$options .= '<li>
								<div class="checkbox checkbox-success">
								<input id="checkbox' . $deptShopData->id . '" class="required shop_id" type="checkbox" id="shop_id" name="shop_id[]" value="' . $deptShopData->id . '" ' . $checkedClass . '>
								<label for="checkbox' . $deptShopData->id . '">
								' . $deptShopData->shop_name . '
								</label>
								</div>
								</li>';
					}
					$options .= '</ul>';
					//Either you can print value or you can send value to database
					$response = array(
						'userList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}

			//get shop list by department id
			if (!empty($request) && $request === 'view_dept_shop') {
				$dept_id = $this->input->post('dept_id', TRUE);
				$search_query = $this->input->post('search_query', TRUE);
				$clicks = $this->input->post('clicks', TRUE);
				$conditions['is_active ='] = 1;
				$conditions['shop_name !='] = '';
				$conditions['dept_id ='] = $dept_id;
				if (isset($search_query) && !empty($search_query)) {
					$conditions['shop_name LIKE'] = '%' . trim($search_query) . '%';
				}
				$deptShopData = $this->base_model->getAllRows('shops', 'shop_name ASC', $conditions);
				//echo $this->db->last_query();
				//echo $location;
				//die;
				$array_click = explode(',', $clicks);
				if ($deptShopData) {
					$options = '<select name="shop_id[]" id="shop_id" class="select_shops required" multiple required>';
					foreach ($deptShopData as $deptShopData) {
						$shops = $this->admin_model->getDeptShop($where = array('user_department_shop.dept_id' => $deptShopData->dept_id), $order_by = 'shop_name ASC');
						$checkedClass = '';
						$checkedClassSpan = '';
						if (count($shops) > 0) {
							foreach ($shops as $shops) {
								if ($shops->id == $deptShopData->id) {
									$checkedClass = 'selected="selected"';
									$checkedClassSpan = 'class="hide"';
								}
							}
						}

						$options .= '<option value="' . $deptShopData->id . '" ' . $checkedClassSpan . '>' . $deptShopData->shop_name . '</option>';
						/* $options .='<li>
								<div class="checkbox checkbox-success">
								<input id="checkbox'.$deptShopData->id.'" class="required shop_id" type="checkbox" id="shop_id" name="shop_id[]" value="'.$deptShopData->id.'" '.$checkedClass.'>
								<label for="checkbox'.$deptShopData->id.'">
								'.$deptShopData->shop_name.'
								</label>
								</div>
								</li>'; */
					}
					//$options .='</ul>';
					$options .= '</select>';
					$options .= "<script>
							$(document).ready(function () {
								$('.select_shops').select2({placeholder:'Select Shops'});

								$.validator.setDefaults({
									ignore: []
								});
							});
							</script>";
					//Either you can print value or you can send value to database
					$response = array(
						'userList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}

			//get shop list by department id
			if (!empty($request) && $request === 'view_assigned_dept_shop') {
				$dept_id = $this->input->post('dept_id', TRUE);
				$user_id = $this->input->post('user_id', TRUE);
				$search_query = $this->input->post('search_query', TRUE);
				$conditions['is_active ='] = 1;
				$conditions['shop_name !='] = '';
				$conditions['dept_id ='] = $dept_id;
				if (isset($search_query) && !empty($search_query)) {
					$conditions['shop_name LIKE'] = '%' . trim($search_query) . '%';
				}
				$deptShopData = $this->base_model->getAllRows('shops', 'shop_name ASC', $conditions);
				//echo $this->db->last_query();
				//echo $location;
				//die;
				if ($deptShopData) {
					$options = '<ul class="check_list notification_checkLst">';
					foreach ($deptShopData as $deptShopData) {
						// how you get $shop is similar to how you get $department
						$shops = $this->admin_model->getDeptShop($where = array('user_department_shop.dept_id' => $dept_id, 'user_department_shop.user_id' => $user_id), $order_by = 'shop_name ASC');
						$checkedClass = '';
						$checkedClassSpan = '';
						if (count($shops) > 0) {
							foreach ($shops as $shops) {
								if ($shops->id == $deptShopData->id) {
									$checkedClass = 'checked="checked"';
									$checkedClassSpan = 'class="checked"';
								}
							}
						}
						//$options .='<option value="'.$deptShopData->id.'">'.$deptShopData->shop_name.'</option>';
						$options .= '<li>
								<div class="checkbox checkbox-success">
								<input id="checkbox' . $deptShopData->id . '" class="requireds" type="checkbox" id="shop_id" name="shop_id[]" value="' . $deptShopData->id . '" ' . $checkedClass . '>
								<label for="checkbox' . $deptShopData->id . '">
								' . $deptShopData->shop_name . '
								</label>
								</div>
								</li>';
					}
					$options .= '</ul>';
					//Either you can print value or you can send value to database
					$response = array(
						'userList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}

			//get reassigned shop list for user
			if (!empty($request) && $request === 'view_reassigned_shop') {
				$user_id = $this->input->post('user_id', TRUE);
				$search_query = $this->input->post('search_query', TRUE);
				$conditions['user_department_shop.user_id ='] = $user_id;
				if (isset($search_query) && !empty($search_query)) {
					$conditions['shop_name LIKE'] = '%' . trim($search_query) . '%';
				}
				$deptShopData = $this->admin_model->getDeptShop($conditions, $order_by = 'shop_name ASC');
				//echo $location;
				//die;
				if ($deptShopData) {
					$options = '<ul class="check_list notification_checkLst">';
					foreach ($deptShopData as $deptShopData) {
						//$options .='<option value="'.$deptShopData->id.'">'.$deptShopData->shop_name.'</option>';
						$options .= '<li>
								<div class="checkbox checkbox-success">
								<input id="checkbox' . $deptShopData->id . '" class="required" type="checkbox" id="shop_id" name="shop_id[]" value="' . $deptShopData->id . '" required>
								<label for="checkbox' . $deptShopData->id . '">
								' . $deptShopData->shop_name . '
								</label>
								</div>
								</li>';
					}
					$options .= '</ul>';
					//Either you can print value or you can send value to database
					$response = array(
						'userList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}
			//get state city list for user
			if (!empty($request) && $request === 'get_user_city') {
				$state_id = $this->input->post('state_id', TRUE);
				$conditions_city['is_active ='] = 1;
				$conditions_city['name !='] = '';
				$conditions_city['state_id ='] = $state_id;
				$cityData = $this->base_model->getAllRows('cities', 'name ASC', $conditions_city);
				//echo $location;
				//die;
				if ($cityData) {
					$options = '<option value="">Select City</option>';
					foreach ($cityData as $cityData) {
						$options .= '<option value="' . $cityData->id . '">' . $cityData->name . '</option>';
					}
					//Either you can print value or you can send value to database
					$response = array(
						'cityList' => $options,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}

			//check shop name available yes or no
			if (!empty($request) && $request === 'shop-name') {
				$shop_name = $this->input->post('shop_name', TRUE);
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-shop') {
					$shopDetails = $this->base_model->getOneRecord('shops', 'shop_name', $shop_name, 'id');
					if (!empty($shopDetails) && isset($shopDetails)) {

						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-shop') {
					$shop_id = $this->input->post('shop_id', TRUE);
					$where_con = array("shop_name" => $shop_name, "id !=" => $shop_id);
					$shopDetails = $this->base_model->getOneRecordWithWhere("shops", $where_con, 'id');
					if (!empty($shopDetails) && isset($shopDetails)) {

						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Shop name available does not working. Please try again!",
						'id' => $shop_name,
						'dataContent' => ''
					);
				}
			}
			//shop get coordinate longitude and langitude
			if (!empty($request) && $request === 'get-coordinate') {
				$address = $this->input->post('address', TRUE);
				$latLong = getLatLong($address);
				if ($latLong) {
					$latitude = $latLong['latitude'] ? $latLong['latitude'] : 'Not found';
					$longitude = $latLong['longitude'] ? $latLong['longitude'] : 'Not found';
					//Either you can print value or you can send value to database
					$response = array(
						'latitude' => $latitude,
						'longitude' => $longitude,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}
			//shop get location of given longitude and langitude
			if (!empty($request) && $request === 'get-location') {
				$latitude = $this->input->post('latitude', TRUE);
				$longitude = $this->input->post('longitude', TRUE);
				$location = getLocation($latitude, $longitude);
				//echo $location;
				//die;
				if ($location) {
					//Either you can print value or you can send value to database
					$response = array(
						'location' => $location,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}


			//shop get location of given longitude and langitude
			if (!empty($request) && $request === 'current-location-user') {
				$user_id = $this->input->post('user_id', TRUE);
				$from = $this->input->get('from_date', TRUE);
				$to = $this->input->get('to_date', TRUE);
				$search_criteria1['user_id ='] = $user_id;
				$search_criteria1['travel_date ='] = date("Y-m-d");
				$userTrackList = $this->admin_model->getSearch('user_location_track', $order_by = 'id DESC', $search_criteria1, '', '', '', '1');
				$htlat = '';
				$htlon = '';
				$htloc = $htid = '';
				if (count($userTrackList) > 0) {
					foreach ($userTrackList as $userTrackList) {
						$latitude = $userTrackList->start_point;
						$longitude = $userTrackList->end_point;
						$locations = $userTrackList->address;
						if (empty($locations) && $locations == '') {
							$locations = getLocation($latitude, $longitude);
						}
						$userData = $this->base_model->getOneRecord("users", "id", $user_id, "full_name,id");
						$html = "<b>" . $userData->full_name . " is here</b><br/>" . $locations;
						$htlat .= $latitude;
						$htlon .= $longitude;
						$htloc .= $html;
						$htid .= $userTrackList->id;
					}
				}
				$search_criteria['user_id ='] = $user_id;
				if (empty($from) && empty($to)) {
					$search_criteria['travel_date ='] = date("Y-m-d");
				}
				$from = (isset($from) && $from != '') ? $from : "";
				$to = (isset($to) && $to != '') ? $to : "";
				if (isset($from) && !empty($from) && $from != '') {
					$from = date("Y-m-d", strtotime($from));
					$search_criteria['travel_date >='] = $from;
				}
				if (isset($to) && !empty($to) && $to != '') {
					$to = date("Y-m-d", strtotime($to));
					$search_criteria['travel_date <='] = $to;
				}
				/* if(isset($state_id) && $state_id != ''){
							$search_criteria['state_id =']= $state_id;
						} */

				$AlluserTrackList2 = $this->admin_model->getSearch('user_location_track', $order_by = 'id DESC', $search_criteria, '', '', 'DISTINCT(REPLACE(start_point, ".", "")),start_point,end_point,address,id', '');
				$result_location2 = '';
				foreach ($AlluserTrackList2 as $AlluserTrackList1) {
					$latitude1 = $AlluserTrackList1->start_point;
					$longitude1 = $AlluserTrackList1->end_point;
					$location1 = $AlluserTrackList1->address;
					if (empty($location1) && $location1 == '') {
						$location1 = getLocation($latitude1, $longitude1);
					}
					//$location = getLocation($latitude1, $longitude1);
					//$location1= $location;
					//$address=$AlluserTrackList1->address;
					//if(!empty($address)){
					//$location1=$AlluserTrackList1->address;
					//}
					$html = "<b>" . $userData->full_name . "</b>";
					$result_location2 .= '{"address":{"address":"' . $location1 . '","lat":"' . $latitude1 . '","lng":"' . $longitude1 . '"},"title":"' . $html . '"},';
				}
				$result_location3 = rtrim($result_location2, ',');
				//echo $location;
				//die;
				if ($userTrackList) {
					//Either you can print value or you can send value to database
					$response = array(
						'lat' => $htlat,
						'lon' => $htlon,
						'location' => $htloc,
						'id' => $htid,
						'result_location' => $result_location3,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}

			//live current address
			if (!empty($request) && $request === 'current-location-user-address') {
				$user_id = $this->input->post('user_id', TRUE);
				$from = $this->input->post('from_date', TRUE);
				$to = $this->input->post('to_date', TRUE);
				$search_criteria1['user_id ='] = $user_id;
				$search_criteria1['address !='] = '';
				//$search_criteria1['travel_date =']= date("Y-m-d");

				$search_criteria['user_shops.user_id ='] = $user_id;
				if (empty($from) && empty($to)) {
					$search_criteria['user_shops.shop_schedule_date ='] = date("Y-m-d");
					$search_criteria1['travel_date ='] = date("Y-m-d");
				}
				$from = (isset($from) && $from != '') ? $from : "";
				//$state_id=(isset($state_id) && $state_id != '')?$state_id:"";
				$to = (isset($to) && $to != '') ? $to : "";
				if (isset($from) && !empty($from) && $from != '') {
					$from = date("Y-m-d", strtotime($from));
					$search_criteria['user_shops.shop_schedule_date >='] = $from;
					$search_criteria1['travel_date >='] = $from;
				}
				if (isset($to) && !empty($to) && $to != '') {
					$to = date("Y-m-d", strtotime($to));
					$search_criteria['user_shops.shop_schedule_date <='] = $to;
					$search_criteria1['travel_date <='] = $to;
				}
				/* if(isset($state_id) && $state_id != ''){
						$search_criteria['state_id =']= $state_id;
					} */

				$userTrackList = $this->admin_model->getSearch('user_location_track', $order_by = 'id DESC', $search_criteria1, '', '', 'DISTINCT(REPLACE(start_point, ".", "")),start_point,end_point,address,id,time_spent', '');
				//$search_criteria['user_shops.shop_schedule_date =']= date("Y-m-d");
				//$search_criteria['user_shops.user_id =']= $user_id;
				$userTotalDistanceCurrent = $this->admin_model->getTotalDistanceUsers($search_criteria, 'user_shops.user_id');
				$totalDistance = (!empty($userTotalDistanceCurrent) && !empty($userTotalDistanceCurrent[0]->total_distance)) ? $userTotalDistanceCurrent[0]->total_distance : 0;
				$html = '';
				if (count($userTrackList) > 0) {
					foreach ($userTrackList as $AlluserTrackList) {
						$latitude = $AlluserTrackList->start_point;
						$longitude = $AlluserTrackList->end_point;
						$location = $AlluserTrackList->address;
						if (empty($location) && $location == '') {
							$location = getLocation($latitude, $longitude);
						}
						//$location = getLocation($latitude, $longitude);
						//if(!empty($address)){
						//$location=$AlluserTrackList->address;
						//}
						if (!empty($AlluserTrackList->time_spent)) {
							$ext_array = explode(":", $AlluserTrackList->time_spent);
							$AlluserTrackList->time_spent = $ext_array[0] . ' hours ' . $ext_array[1] . ' minutes';
						}

						$html .= '<div class="map_details_box alerts appendDeatails" id="removeDiv' . $AlluserTrackList->id . '">
							<span class="arrow_down">
							<i class="icon-arrow-up"></i>
							</span>
							<h3>Address:<span>' . $location . '</span></h3>';
						if (!empty($AlluserTrackList->time_spent)) {
							$html .= '<h3>Time Spent:<span>' . $AlluserTrackList->time_spent . '</span></h3>';
						}
						$html .= '</div>';
					}
				}
				//echo $location;
				//die;
				if ($userTrackList) {
					//Either you can print value or you can send value to database
					$response = array(
						'content' => $html,
						'totalDistance' => number_format($totalDistance, 2),
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}


			//live current address
			if (!empty($request) && $request === 'current-location-user-live') {
				$user_id = $this->input->post('user_id', TRUE);
				$search_criteria1['user_id ='] = $user_id;
				$search_criteria1['travel_date ='] = date("Y-m-d");
				$AlluserTrackList2 = $this->admin_model->getSearch('user_location_track', $order_by = 'id DESC', $search_criteria1, '', '', 'DISTINCT(REPLACE(start_point, ".", "")),start_point,end_point,address,id', '');
				$result_location2 = '';
				foreach ($AlluserTrackList2 as $AlluserTrackList1) {
					$latitude1 = $AlluserTrackList1->start_point;
					$longitude1 = $AlluserTrackList1->end_point;
					$location1 = $AlluserTrackList1->address;
					if (empty($location1) && $location1 == '') {
						$location1 = getLocation($latitude1, $longitude1);
					}
					//$location = getLocation($latitude1, $longitude1);
					//$location1= $location;
					//$address=$AlluserTrackList1->address;
					//if(!empty($address)){
					//$location1=$AlluserTrackList1->address;
					//}
					$html = "<b>" . $userData->full_name . "</b>";
					$result_location2 .= '{"address":{"address":"' . $location1 . '","lat":"' . $latitude1 . '","lng":"' . $longitude1 . '"},"title":"' . $html . '"},';
				}
				//echo $location;
				//die;
				if ($userTrackList) {
					//Either you can print value or you can send value to database
					$response = array(
						'content' => $result_location2,
						'dataStatus' => '1'
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array('dataStatus' => '0');
				}
			}

			//delete user shop list
			if (!empty($request) && $request === 'delete-shop-list') {
				$user_shop_dept_id = $this->input->post('user_shop_dept_id', TRUE);
				$user_id = $this->input->post('user_id', TRUE);
				$where_conditions_d = array('id' => $user_shop_dept_id, 'user_id' => $user_id);
				$res = $this->base_model->deleteWithWhereConditions('user_department_shop', $where_conditions_d);
				$shopDetails = $this->base_model->getOneRecord('user_department_shop', 'id', $user_shop_dept_id, 'shop_id');
				if (!empty($shopDetails)) {
					$where_conditions_day = array('shop_id' => $shopDetails->shop_id, 'user_id' => $user_id);
					$res = $this->base_model->deleteWithWhereConditions('user_day_reporting', $where_conditions_day);
				}
				if (count($res) > 0) {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Shop has been removed successfully.",
						'id' => $user_shop_dept_id,
						'dataContent' => '1',
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Shop does not removed. Please try again!",
						'id' => $user_shop_dept_id,
						'dataContent' => '',
					);
				}
			}

			//delete user shop department list
			if (!empty($request) && $request === 'delete-dept-list') {
				$user_shop_dept_id = $this->input->post('user_shop_dept_id', TRUE);
				$user_id = $this->input->post('user_id', TRUE);
				$where_conditions_d = array('dept_id' => $user_shop_dept_id, 'user_id' => $user_id);
				$res = $this->base_model->deleteWithWhereConditions('user_department_shop', $where_conditions_d);
				$where_conditions_day = array('dept_id' => $user_shop_dept_id, 'user_id' => $user_id);
				$res = $this->base_model->deleteWithWhereConditions('user_day_reporting', $where_conditions_day);
				if (count($res) > 0) {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User department has been removed successfully.",
						'id' => $user_shop_dept_id,
						'dataContent' => '1',
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User department does not removed. Please try again!",
						'id' => $user_shop_dept_id,
						'dataContent' => '',
					);
				}
			}

			//delete user shop department list
			if (!empty($request) && $request === 'delete-shop-dept-list') {
				$user_shop_dept_id = $this->input->post('user_shop_dept_id', TRUE);
				$user_id = $this->input->post('user_id', TRUE);
				$where_conditions_d = array('id' => $user_shop_dept_id, 'user_id' => $user_id);
				$res = $this->base_model->deleteWithWhereConditions('user_department_shop', $where_conditions_d);
				if (count($res) > 0) {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User shop has been removed successfully.",
						'id' => $user_shop_dept_id,
						'dataContent' => '1',
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User shop does not removed. Please try again!",
						'id' => $user_shop_dept_id,
						'dataContent' => '',
					);
				}
			}

			//delete user shop list
			if (!empty($request) && $request === 'delete-notify-list') {
				$user_notify_id = $this->input->post('user_notify_id', TRUE);
				$where_conditions_d = array('id' => $user_notify_id);
				$res = $this->base_model->deleteWithWhereConditions('notification_users', $where_conditions_d);
				if ($res) {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Notification has been removed successfully.",
						'id' => $user_notify_id,
						'dataContent' => '1',
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Notification does not removed. Please try again!",
						'id' => $user_notify_id,
						'dataContent' => '',
					);
				}
			}

			//delete user shop list
			if (!empty($request) && $request === 'delete-shop-user-list') {
				$user_shop_id = $this->input->post('user_shop_id', TRUE);
				$shop_id = $this->input->post('shop_id', TRUE);
				$where_conditions_d = array('id' => $user_shop_id, 'shop_id' => $shop_id);
				$res = $this->base_model->deleteWithWhereConditions('user_shops', $where_conditions_d);
				if ($res) {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User has been removed successfully.",
						'id' => $user_shop_id,
						'dataContent' => '1',
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User does not removed. Please try again!",
						'id' => $user_shop_id,
						'dataContent' => '',
					);
				}
			}
			//shop data import
			if (!empty($request) && $request === 'upload-shop-data-excel') {
				$this->load->model('Import_model', 'import');
				$this->load->library('excel');
				if (!empty($_FILES['shop_data_file']['name'])) {
					if (!empty($_FILES['shop_data_file']['name'])) {
						$path = 'uploads/excel_upload/shop_data/';
						$config['upload_path'] = $path;
						$config['allowed_types'] = 'xlsx|xls';
						$config['remove_spaces'] = TRUE;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if (!$this->upload->do_upload('shop_data_file')) {
							$error = array('error' => $this->upload->display_errors());
						} else {
							$data = array('upload_data' => $this->upload->data());
						}

						if (!empty($data['upload_data']['file_name'])) {
							$import_xls_file = $data['upload_data']['file_name'];
						} else {
							$import_xls_file = 0;
						}
						$inputFileName = $path . $import_xls_file;
						try {
							$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
							$objReader = PHPExcel_IOFactory::createReader($inputFileType);
							$objPHPExcel = $objReader->load($inputFileName);
						} catch (Exception $e) {
							die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
								. '": ' . $e->getMessage());
						}
						$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
						$arrayCount = count($allDataInSheet);
						$flag = 0;
						$createArray = array('ShopCode', 'ShopNameTP', 'ShopNameIndustry', 'ShopNameDepartment', 'ShopNameDistributor', 'ShopOwnerName', 'ShopDepartment', 'ShopOrganization', 'ShopAddress', 'ShopLatitude', 'ShopLongitude');
						$makeArray = array('ShopCode' => 'ShopCode', 'ShopNameTP' => 'ShopNameTP', 'ShopNameIndustry' => 'ShopNameIndustry', 'ShopNameDepartment' => 'ShopNameDepartment', 'ShopNameDistributor' => 'ShopNameDistributor', 'ShopOwnerName' => 'ShopOwnerName', 'ShopDepartment' => 'ShopDepartment', 'ShopOrganization' => 'ShopOrganization', 'ShopAddress' => 'ShopAddress', 'ShopLatitude' => 'ShopLatitude', 'ShopLongitude' => 'ShopLongitude');
						$SheetDataKey = array();
						foreach ($allDataInSheet as $dataInSheet) {
							foreach ($dataInSheet as $key => $value) {
								if (in_array(trim($value), $createArray)) {
									$value = preg_replace('/\s+/', '', $value);
									$SheetDataKey[trim($value)] = $key;
								} else {
								}
							}
						}
						//print_r($SheetDataKey);
						//die;
						$data = array_diff_key($makeArray, $SheetDataKey);
						if (empty($data)) {
							$flag = 1;
						}
						if ($flag == 1) {
							$fetchData = $updateFetchData = array();
							for ($i = 2; $i <= $arrayCount; $i++) {
								$addresses = array();
								$ShopCode = $SheetDataKey['ShopCode'];
								$ShopName = $SheetDataKey['ShopNameTP'];
								$ShopNameTwo = $SheetDataKey['ShopNameIndustry'];
								$ShopNameThree = $SheetDataKey['ShopNameDepartment'];
								$ShopNameFour = $SheetDataKey['ShopNameDistributor'];
								$ShopOwnerName = $SheetDataKey['ShopOwnerName'];
								$ShopDepartment = $SheetDataKey['ShopDepartment'];
								$ShopOrganization = $SheetDataKey['ShopOrganization'];
								$ShopLatitude = $SheetDataKey['ShopLatitude'];
								$ShopLongitude = $SheetDataKey['ShopLongitude'];
								$ShopAddress = $SheetDataKey['ShopAddress'];

								$ShopCode = filter_var(trim($allDataInSheet[$i][$ShopCode]), FILTER_SANITIZE_STRING);
								$ShopName = filter_var(trim($allDataInSheet[$i][$ShopName]), FILTER_SANITIZE_STRING);
								$ShopNameTwo = filter_var(trim($allDataInSheet[$i][$ShopNameTwo]), FILTER_SANITIZE_STRING);
								$ShopNameThree = filter_var(trim($allDataInSheet[$i][$ShopNameThree]), FILTER_SANITIZE_STRING);
								$ShopNameFour = filter_var(trim($allDataInSheet[$i][$ShopNameFour]), FILTER_SANITIZE_STRING);
								//$ShopNameFive = filter_var(trim($allDataInSheet[$i][$ShopNameFive]), FILTER_SANITIZE_STRING);
								$ShopOwnerName = filter_var(trim($allDataInSheet[$i][$ShopOwnerName]), FILTER_SANITIZE_STRING);
								$ShopDepartment = filter_var(trim($allDataInSheet[$i][$ShopDepartment]), FILTER_SANITIZE_STRING);
								$ShopOrganization = filter_var(trim($allDataInSheet[$i][$ShopOrganization]), FILTER_SANITIZE_STRING);
								$ShopAddress = filter_var(trim($allDataInSheet[$i][$ShopAddress]), FILTER_SANITIZE_STRING);
								$ShopLatitude = filter_var(trim($allDataInSheet[$i][$ShopLatitude]), FILTER_SANITIZE_STRING);
								$ShopLongitude = filter_var(trim($allDataInSheet[$i][$ShopLongitude]), FILTER_SANITIZE_STRING);
								$modified_by = $logged_in_id;

								$deptDetails = $this->base_model->getOneRecord('departments', 'dept_name', $ShopDepartment, 'id');
								$dept_id = 0;
								if (!empty($deptDetails) && isset($deptDetails)) {
									$dept_id = $deptDetails->id;
								}
								//$shopDetails=$this->base_model->getOneRecord('shops','shop_name',$ShopName,'id');
								$shopDetails = $this->base_model->getOneRecord('shops', 'shop_code', $ShopCode, 'id');
								if (empty($shopDetails)) {
									$fetchData[] = array('dept_id' => $dept_id, 'shop_code' => $ShopCode, 'shop_name' => $ShopName, 'shop_name_two' => $ShopNameTwo, 'shop_name_three' => $ShopNameThree, 'shop_name_four' => $ShopNameFour, 'shop_owner_name' => $ShopOwnerName, 'shop_department' => $ShopDepartment, 'shop_organization' => $ShopOrganization, 'shop_address' => $ShopAddress, 'shop_lat' => $ShopLatitude, 'shop_lng' => $ShopLongitude, 'created_at' => $date_at, 'modified_by' => $modified_by);
								} else {
									$updateFetchData[] = array('id' => $shopDetails->id, 'dept_id' => $dept_id, 'shop_code' => $ShopCode, 'shop_name' => $ShopName, 'shop_name_two' => $ShopNameTwo, 'shop_name_three' => $ShopNameThree, 'shop_name_four' => $ShopNameFour, 'shop_owner_name' => $ShopOwnerName, 'shop_department' => $ShopDepartment, 'shop_organization' => $ShopOrganization, 'shop_address' => $ShopAddress, 'shop_lat' => $ShopLatitude, 'shop_lng' => $ShopLongitude, 'updated_at' => $date_at, 'modified_by' => $modified_by);
								}
							}
							if (!empty($fetchData)) {
								$this->import->setBatchImport($fetchData);
								$this->import->importData("shops");
							}

							if (!empty($updateFetchData)) {
								$this->import->setUpdateBatchImport($updateFetchData);
								$this->import->updateImportData("shops");
							}
							$message = "<font color='red'>Shop Data has been Imported Successfully.</font>";
						} else {
							$message = "<font color='red'>Please import correct file.</font>";
						}
					}
					//Either you can print value or you can send value to database
					$response = array(
						'message' => $message,
						'id' => $logged_in_id,
						'dataContent' => $logged_in_id
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "<font color='red'>File does not found. Please try again!</font>",
						'id' => $logged_in_id,
						'dataContent' => ''
					);
				}
			}
			//check relationship manager username available yes or no
			if (!empty($request) && $request === 'user-relationship-manager-username') {
				$username = $this->input->post('username', TRUE);
				$action = $this->input->post('action', TRUE);
				$statusLink = 0;
				if (isset($action) && !empty($action) && $action == 'add-username') {
					$relationshipUserDetails = $this->base_model->getOneRecord('admin', 'user_name', $username, 'user_id');
					if (!empty($relationshipUserDetails) && count($relationshipUserDetails) > 0) {

						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else if (isset($action) && !empty($action) && $action == 'edit-username') {
					$user_id = $this->input->post('user_id', TRUE);
					$where_con = array("user_name" => $username, "user_id !=" => $user_id);
					$relationshipUserDetails = $this->base_model->getOneRecordWithWhere("admin", $where_con, 'user_id');
					if (!empty($relationshipUserDetails) && count($relationshipUserDetails) > 0) {

						$statusLink = 1;
					}
					//Either you can print value or you can send value to database
					$response = array(
						'dataContent' => $statusLink
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Relationship manager email available does not working. Please try again!",
						'id' => $email,
						'dataContent' => ''
					);
				}
			}
			//user admin view details
			if (!empty($request) && $request === 'user_view_admin') {
				$user_id = $this->input->post('user_id', TRUE);
				$userDetils = $this->base_model->getOneRecord('admin', 'user_id', $user_id, '*');
				//print_r($userDetils);
				//die;
				if ($userDetils) {
					$status = array("0" => 'Deactive', "1" => 'Active');
					$status_class = array("0" => 'danger', "1" => 'success');
					$class = $status_class[$userDetils->user_active];
					$stateData = $this->base_model->getOneRecord("states", "id", $userDetils->state_id, "name");
					$cityData = $this->base_model->getOneRecord("cities", "id", $userDetils->city_id, "name");
					$userTypeData = $this->base_model->getOneRecord("user_types", "id", $userDetils->user_role, "title");
					$userDetils = '
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Unique code:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->usr_code . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Name:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->screen_name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Email:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->user_mail . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Phone No.:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->user_phone_no . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Designation:</label></div>
							<div class="col-md-9 col-xs-12">' . $userTypeData->title . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">State:</label></div>
							<div class="col-md-9 col-xs-12">' . $stateData->name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">City:</label></div>
							<div class="col-md-9 col-xs-12">' . $cityData->name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Address:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->address . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Status:</label></div>
							<div class="col-md-9 col-xs-12"><span class="label label-' . $class . '">' . $status[$userDetils->user_active] . '</span></div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Created date:</label></div>
							<div class="col-md-9 col-xs-12">' . dateFormat('d-m-Y H:i:s', $userDetils->date_added) . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Updated date:</label></div>
							<div class="col-md-9 col-xs-12">' . dateFormat('d-m-Y H:i:s', $userDetils->modified_date) . '</div>
							</div>
							</div>';
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'id' => $user_id,
						'dataContent' => $userDetils
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User details does not found. Please try again!",
						'id' => $user_id,
						'dataContent' => ''
					);
				}
			}
			//user view details
			if (!empty($request) && $request === 'user_view') {
				$user_id = $this->input->post('user_id', TRUE);
				$userDetils = $this->base_model->getOneRecord('users', 'id', $user_id, '*');
				//print_r($userDetils);
				//die;
				if ($userDetils) {
					$status = array("0" => 'Deactive', "1" => 'Active');
					$status_class = array("0" => 'danger', "1" => 'success');
					$class = $status_class[$userDetils->is_active];
					$stateData = $this->base_model->getOneRecord("states", "id", $userDetils->state_id, "name");
					$cityData = $this->base_model->getOneRecord("cities", "id", $userDetils->city_id, "name");
					$userTypeData = $this->base_model->getOneRecord("user_types", "id", $userDetils->user_type, "title");
					$userDetils = '
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Unique code:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->unique_code . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Name:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->full_name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Email:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->email . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Phone No.:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->phone_no . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Designation:</label></div>
							<div class="col-md-9 col-xs-12">' . $userTypeData->title . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">State:</label></div>
							<div class="col-md-9 col-xs-12">' . $stateData->name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">City:</label></div>
							<div class="col-md-9 col-xs-12">' . $cityData->name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Address:</label></div>
							<div class="col-md-9 col-xs-12">' . $userDetils->address . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Status:</label></div>
							<div class="col-md-9 col-xs-12"><span class="label label-' . $class . '">' . $status[$userDetils->is_active] . '</span></div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Created date:</label></div>
							<div class="col-md-9 col-xs-12">' . dateFormat('d-m-Y H:i:s', $userDetils->created_at) . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Updated date:</label></div>
							<div class="col-md-9 col-xs-12">' . dateFormat('d-m-Y H:i:s', $userDetils->updated_at) . '</div>
							</div>
							</div>';
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'id' => $user_id,
						'dataContent' => $userDetils
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "User details does not found. Please try again!",
						'id' => $user_id,
						'dataContent' => ''
					);
				}
			}
			//leave application details
			if (!empty($request) && $request === 'leave_app_view') {
				$leave_id = $this->input->post('leave_id', TRUE);
				$leaveDetils = $this->base_model->getOneRecord('user_leave_request', 'id', $leave_id, '*');
				//print_r($shopDetils);
				//die;
				if ($leaveDetils) {
					$status = array("0" => 'Pending', "1" => 'Approved', "2" => 'Disapproved');
					$status_class = array("0" => 'warning', "1" => 'success', "2" => 'danger');
					$class = $status_class[$leaveDetils->leave_status];
					$R_AD = (isset($leaveDetils->reason_for_admin) && !empty($leaveDetils->reason_for_admin)) ? $leaveDetils->reason_for_admin : 'N/A';
					$R_US = (isset($leaveDetils->reason_for_user) && !empty($leaveDetils->reason_for_user)) ? $leaveDetils->reason_for_user : 'N/A';
					$R_rply = (isset($leaveDetils->reply_date) && !empty($leaveDetils->reply_date)) ? dateFormat('d-m-Y H:i:s', $leaveDetils->reply_date) : 'N/A';


					$leaveDetils = '
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Name:</label></div>
							<div class="col-md-9 col-xs-12">' . $leaveDetils->full_name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">From date:</label></div>
							<div class="col-md-9 col-xs-12">' . dateFormat('d-m-Y', $leaveDetils->from_date) . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">To Date:</label></div>
							<div class="col-md-9 col-xs-12">' . dateFormat('d-m-Y', $leaveDetils->to_date) . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">No. Of days:</label></div>
							<div class="col-md-9 col-xs-12">' . $leaveDetils->no_of_days . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Reason for Leave(Disapproved):</label></div>
							<div class="col-md-9 col-xs-12">' . $R_US . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Reason for Leave:</label></div>
							<div class="col-md-9 col-xs-12">' . $R_AD . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Status:</label></div>
							<div class="col-md-9 col-xs-12"><span class="label label-' . $class . '">' . $status[$leaveDetils->leave_status] . '</span></div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Received date:</label></div>
							<div class="col-md-9 col-xs-12">' . dateFormat('d-m-Y H:i:s', $leaveDetils->sent_date) . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Reply date:</label></div>
							<div class="col-md-9 col-xs-12">' . $R_rply . '</div>
							</div>
							</div>';
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'id' => $leave_id,
						'dataContent' => $leaveDetils
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Leave application details does not found. Please try again!",
						'id' => $leave_id,
						'dataContent' => ''
					);
				}
			}
			//delete user leave list
			if (!empty($request) && $request === 'delete-user-leave-list') {
				$leave_id = $this->input->post('leave_id', TRUE);
				$user_id = $this->input->post('user_id', TRUE);
				$leaveRDetails = $this->base_model->getOneRecord('user_leave_request', 'id', $leave_id, 'id,user_id');
				if (count($leaveRDetails) > 0 && !empty($leaveRDetails)) {
					$leave_id = $leaveRDetails->id;
					$user_id = $leaveRDetails->user_id;
					$where_conditions_sp = array('content_id' => $leave_id, 'sender_id' => $user_id, 'notification_type' => 'Leave Request');
					$query1 = $this->base_model->deleteWithWhereConditions('admin_notification', $where_conditions_sp);

					$where_conditions_u = array('id' => $leave_id, 'user_id' => $user_id);
					$query2 = $this->base_model->deleteWithWhereConditions('user_leave_request', $where_conditions_u);
					if ($query1 || $query2) {
						//if($query1 || $query6){
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "Leave request has been removed successfully.",
							'id' => $leave_id,
							'dataContent' => '1',
						);
					} else {
						//Either you can print value or you can send value to database
						$response = array(
							'message' => "Leave request does not removed. Please try again!",
							'id' => $leave_id,
							'dataContent' => '',
						);
					}
				}
			}

			//shop details
			if (!empty($request) && $request === 'shop_view') {
				$shop_id = $this->input->post('shop_id', TRUE);
				$shopDetils = $this->base_model->getOneRecord('shops', 'id', $shop_id, '*');
				//print_r($shopDetils);
				//die;
				//<div class="row">
				//<div class="form-group">
				//<div class="col-md-3 col-xs-12"><label class="control-label">Shop name five:</label></div>
				//<div class="col-md-9 col-xs-12">'.$shopDetils->shop_name_five.'</div>
				//</div>
				//</div>
				if ($shopDetils) {
					$status = array("0" => 'Deactive', "1" => 'Active');
					$status_class = array("0" => 'danger', "1" => 'success');
					$class = $status_class[$shopDetils->is_active];
					$shopDetils->shop_owner_name = (isset($shopDetils->shop_owner_name) && !empty($shopDetils->shop_owner_name)) ? $shopDetils->shop_owner_name : "N/A";
					$shopDetils->shop_organization = (isset($shopDetils->shop_organization) && !empty($shopDetils->shop_organization)) ? $shopDetils->shop_organization : "N/A";
					$update_date = (isset($shopDetils->updated_at) && !empty($shopDetils->updated_at)) ? dateFormat('d-m-Y H:i:s', $shopDetils->updated_at) : "Not Update";
					$shopDetils = '
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop code:</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_code . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop name(TP):</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop name(Industry):</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_name_two . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop name(Department):</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_name_three . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop name(Distributor):</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_name_four . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop owner name:</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_owner_name . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop department:</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_department . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop organization:</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_organization . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop Address:</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_address . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop Latitude:</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_lat . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Shop Longitude:</label></div>
							<div class="col-md-9 col-xs-12">' . $shopDetils->shop_lng . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Status:</label></div>
							<div class="col-md-9 col-xs-12"><span class="label label-' . $class . '">' . $status[$shopDetils->is_active] . '</span></div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Created date:</label></div>
							<div class="col-md-9 col-xs-12">' . dateFormat('d-m-Y H:i:s', $shopDetils->created_at) . '</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
							<div class="col-md-3 col-xs-12"><label class="control-label">Updated date:</label></div>
							<div class="col-md-9 col-xs-12">' . $update_date . '</div>
							</div>
							</div>';
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'id' => $shop_id,
						'dataContent' => $shopDetils
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Shop details does not found. Please try again!",
						'id' => $shop_id,
						'dataContent' => ''
					);
				}
			}

			//notifications details
			if (!empty($request) && $request === 'notify_view') {
				$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
				$notify_type = $this->input->post('notify_type', TRUE);
				$where = array();
				if (isset($notify_type) && $notify_type == 'Unread') {
					$where = array('status' => 0, 'new_flag' => 'New');
				} else if (isset($notify_type) && $notify_type == 'Read') {
					$where = array('status' => 1, 'new_flag' => 'Old');
				}
				if (isset($user_role) && !empty($user_role) && $user_role == 2) {
					$search_criteria = array();
					$search_criteria['relationship_manager_id ='] = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
					$search_criteria['status ='] = 1;
					$loanUserList = $this->base_model->getAllRows('users', 'id DESC', $search_criteria);
					$user_id_n = array();
					foreach ($loanUserList as $loanUserList) {
						$user_id_n[] = $loanUserList->id;
					}
					$notificationsData = $this->base_model->getAllRowsWithWhereIn($table = 'notifications', 'user_id', $user_id_n, $where, $order_by = 'id DESC');
				} else {
					$notificationsData = $this->base_model->getAllRows('notifications', 'id DESC', $where);
				}
				$notificationsDatas = '';
				//print_r($loanDetils);
				if ($notificationsData) {
					//print_r($referralAmtDetils);
					//die;
					$notificationsDatas = '<table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
							<thead>
							<tr>
							<th>To</th>
							<th>Title</th>
							<th>Message</th>
							<th>Sent Date</th>
							<th>Actions</th>
							</tr>
							</thead>
							<tbody>';
					foreach ($notificationsData as $notificationsData) {
						$user_id = $notificationsData->user_id;
						$userDetils = $this->base_model->getOneRecord('users', 'id', $user_id, 'name, cust_identification_number');
						$userDetils->name = (isset($userDetils->cust_identification_number) && !empty($userDetils->cust_identification_number)) ? $userDetils->name . '(' . $userDetils->cust_identification_number . ')' : $userDetils->name;
						$notificationsDatas .= '
								<tr id="removeTr' . $notificationsData->id . '">
								<td>' . $userDetils->name . '</td>
								<td>' . $notificationsData->title . '</td>
								<td>' . $notificationsData->message . '</td>
								<td>' . dateFormat('d-m-Y H:i:s', $notificationsData->created_at) . '</td>
								<td class="center">';
						if ($notificationsData->status == 0) {
							$notificationsDatas .= '<a class="btn btn-danger btn-xs delete-notification-list" href="javascript:void(0);" id="' . $notificationsData->id . '-singleunread">
									<i class="glyphicon glyphicon-trash icon-white"></i>
									Delete
									</a>';
						} else {
							$notificationsDatas .= '<a class="btn btn-danger btn-xs delete-notification-list" href="javascript:void(0);" id="' . $notificationsData->id . '-singleread">
									<i class="glyphicon glyphicon-trash icon-white"></i>
									Delete
									</a>';
						}
						$notificationsDatas .= '</td>
								</tr>
								';
					}
					$notificationsDatas .= '</tbody>
							</table>';
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "",
						'dataContent' => $notificationsDatas
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Notification does not found. Please try again!",
						'dataContent' => ''
					);
				}
			}
			//delete notification list
			if (!empty($request) && $request === 'delete-notification-list') {
				$del_type = $this->input->post('del_type', TRUE);
				$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
				if (isset($del_type) && ($del_type == 'singleread' || $del_type == 'singleunread')) {
					$notify_id = $this->input->post('notify_id', TRUE);
					$where_conditions_d = array('id' => $notify_id);
					if (isset($user_role) && !empty($user_role) && $user_role == 2) {
						$search_criteria = array();
						$search_criteria['relationship_manager_id ='] = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
						$search_criteria['status ='] = 1;
						$loanUserList = $this->base_model->getAllRows('users', 'id DESC', $search_criteria);
						$user_id_n = array();
						foreach ($loanUserList as $loanUserList) {
							$user_id_n[] = $loanUserList->id;
						}
						$res = $this->base_model->deleteWithWhereInConditions($table = 'notifications', 'user_id', $user_id_n, $where_conditions_d);
						$res = 1;
					} else {
						$res = $this->base_model->deleteWithWhereConditions('notifications', $where_conditions_d);
					}
				} else if (isset($del_type) && $del_type == 'allunread') {
					$where_conditions_d = array('status' => 0, 'new_flag' => 'New');
					if (isset($user_role) && !empty($user_role) && $user_role == 2) {
						$search_criteria = array();
						$search_criteria['relationship_manager_id ='] = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
						$search_criteria['status ='] = 1;
						$loanUserList = $this->base_model->getAllRows('users', 'id DESC', $search_criteria);
						$user_id_n = array();
						foreach ($loanUserList as $loanUserList) {
							$user_id_n[] = $loanUserList->id;
						}
						$res = $this->base_model->deleteWithWhereInConditions($table = 'notifications', 'user_id', $user_id_n, $where_conditions_d);
						$res = 1;
					} else {
						$res = $this->base_model->deleteWithWhereConditions('notifications', $where_conditions_d);
					}
				} else if (isset($del_type) && $del_type == 'allread') {
					$where_conditions_d = array('status' => 1, 'new_flag' => 'Old');
					if (isset($user_role) && !empty($user_role) && $user_role == 2) {
						$search_criteria = array();
						$search_criteria['relationship_manager_id ='] = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
						$search_criteria['status ='] = 1;
						$loanUserList = $this->base_model->getAllRows('users', 'id DESC', $search_criteria);
						$user_id_n = array();
						foreach ($loanUserList as $loanUserList) {
							$user_id_n[] = $loanUserList->id;
						}
						$res = $this->base_model->deleteWithWhereInConditions($table = 'notifications', 'user_id', $user_id_n, $where_conditions_d);
						$res = 1;
					} else {
						$res = $this->base_model->deleteWithWhereConditions('notifications', $where_conditions_d);
					}
				} else if (isset($del_type) && $del_type == 'all') {
					if (isset($user_role) && !empty($user_role) && $user_role == 2) {
						$search_criteria = array();
						$search_criteria['relationship_manager_id ='] = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
						$search_criteria['status ='] = 1;
						$loanUserList = $this->base_model->getAllRows('users', 'id DESC', $search_criteria);
						$user_id_n = array();
						foreach ($loanUserList as $loanUserList) {
							$user_id_n[] = $loanUserList->id;
						}
						$res = $this->base_model->deleteWithWhereInConditions($table = 'notifications', 'user_id', $user_id_n);
						$res = 1;
					} else {
						$res = $this->base_model->truncateWithTable('notifications');
						$res = 1;
					}
				}
				if ($res) {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Notification has been removed successfully.",
						'dataContent' => '1',
					);
				} else {
					//Either you can print value or you can send value to database
					$response = array(
						'message' => "Notification does not removed. Please try again!",
						'dataContent' => '',
					);
				}
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
		//echo json_encode($response);
	}

	//user sales day reports
	public function user_sales_report()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();
		$from = $this->input->get('from_date', TRUE);
		$to = $this->input->get('to_date', TRUE);
		$state_id = $this->input->get('state_id', TRUE);
		$user_id = $this->input->get('user_id', TRUE);
		$report_created_at = $this->input->get('date_sales', TRUE);
		$search_user_id = $this->input->get('search_user_id', TRUE);
		$search_criteria = array();

		$from = (isset($from) && $from != '') ? $from : "";
		$state_id = (isset($state_id) && $state_id != '') ? $state_id : "";
		$to = (isset($to) && $to != '') ? $to : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(created_at) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(created_at) <='] = $to;
		}

		if (isset($state_id) && $state_id != '') {
			$search_criteria['state_id ='] = $state_id;
		}

		if (isset($search_user_id) && $search_user_id != '') {
			$user_id = $search_user_id;
		}

		$select_column_name = "id,unique_code,full_name,email,phone_no,is_active,created_at";
		$data['userList'] = $this->admin_model->getSearch('users', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);
		$conditions_state['country_id ='] = 101;
		$conditions_state['is_active ='] = 1;
		$data['stateData'] = $this->base_model->getAllRows('states', 'name ASC', $conditions_state);
		$data['userDetails'] = $data['userDetails1'] = array();
		$usersStateAllProduct = array();
		if ($this->input->get('user_id', TRUE)) {
			$data['userDetails'] = $this->base_model->getOneRecord("users", "id", $this->input->get('user_id', TRUE), "*");
			$where_column['state_products.state_id ='] = $data['userDetails']->state_id;
			$where_column['products.is_active ='] = 1;
			$usersStateAllProduct = $this->admin_model->getStateAllProducts($where_column, 'products.product_name ASC');
			foreach ($usersStateAllProduct as $key => $usersStateAllProducts) {
				$where_column1['user_id ='] = $data['userDetails']->id;
				$where_column1['state_id ='] = $data['userDetails']->state_id;
				$where_column1['product_id ='] = $usersStateAllProducts->product_id;
				$where_column1['shop_id ='] = $this->input->get('shop_id', TRUE);
				$where_column1['dept_id ='] = $this->input->get('dept_id', TRUE);
				$where_column1['DATE(date_of_report) ='] = date("Y-m-d");
				if (isset($report_created_at) && !empty($report_created_at) && $report_created_at != '') {
					$report_created_at = date("Y-m-d", strtotime($report_created_at));
					$where_column1['DATE(date_of_report) ='] = $report_created_at;
				}

				$productListInfoReport = $this->base_model->getOneRecordWithWhere('user_day_reporting', $where_column1, '*');
				$usersStateAllProduct[$key]->mis_user_sale_report = $productListInfoReport;
			}
			$data['shopDetils'] = $this->base_model->getOneRecord('shops', 'id', $this->input->get('shop_id', TRUE), '*');
			$data['departmentsDetils'] = $this->base_model->getOneRecord('departments', 'id', $this->input->get('dept_id', TRUE), '*');
		} else if ($this->input->get('search_user_id', TRUE)) {
			$data['userDetails1'] = $this->base_model->getOneRecord("users", "id", $user_id, "*");
			$where_column['state_products.state_id ='] = $data['userDetails1']->state_id;
			$where_column['products.is_active ='] = 1;
			$usersStateAllProduct = $this->admin_model->getStateAllProducts($where_column, 'products.product_name ASC');
			foreach ($usersStateAllProduct as $key => $usersStateAllProducts) {
				$where_column1['user_id ='] = $data['userDetails1']->id;
				$where_column1['state_id ='] = $data['userDetails1']->state_id;
				$where_column1['product_id ='] = $usersStateAllProducts->product_id;
				$where_column1['DATE(date_of_report) ='] = date("Y-m-d");
				if (isset($report_created_at) && !empty($report_created_at) && $report_created_at != '') {
					$report_created_at = date("Y-m-d", strtotime($report_created_at));
					$where_column1['DATE(date_of_report) ='] = $report_created_at;
				}
				$productListInfoReport = $this->base_model->getOneRecordWithWhere('user_day_reporting', $where_column1, '*');
				$usersStateAllProduct[$key]->mis_user_sale_report = $productListInfoReport;
			}
		}
		//echo "<pre>";print_r($usersStateAllProduct);
		//die;
		$data['userMisReporte'] = $usersStateAllProduct;
		$data['title'] = 'Sales MIS Report';
		$data['fromKeyword'] = $from;
		$data['toKeyword'] = $to;
		$data['state_id'] = $state_id;
		$data['user_id'] = $user_id;
		$data['report_created_at'] = $report_created_at;
		$this->load->view('user_sales_report', $data);
	}
	public function products_list()
	{
		$data = array();
		$cat_id = trim($this->input->get('cat_id', TRUE));
		$sub_cat_id = trim($this->input->get('sub_cat_id', TRUE));
		if (isset($cat_id) && !empty($cat_id) || isset($sub_cat_id) && !empty($sub_cat_id)) {
			$search_criteria = $search_criteria_or = array();
			$cat_id = trim($this->input->get('cat_id', TRUE));
			$sub_cat_id = trim($this->input->get('sub_cat_id', TRUE));
			$product_list_order = trim($this->input->get('product_list_order', TRUE));
			$serach_query = trim($this->input->get('q', TRUE));
			$limiter = trim($this->input->get('limiter', TRUE));
			$offset = trim($this->input->get('getresult', TRUE));
			$price = trim($this->input->get('price', TRUE));
			if (!isset($cat_id) && !empty($cat_id)) {
			}
			$cat_id = (isset($cat_id) && $cat_id != '') ? $cat_id : "";
			$sub_cat_id = (isset($sub_cat_id) && $sub_cat_id != '') ? $sub_cat_id : "";
			$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
			$price = (isset($price) && $price != '') ? $price : "";
			$product_list_order = (isset($product_list_order) && $product_list_order != '') ? $product_list_order : "";
			$offset = (isset($offset) && $offset != '') ? $offset : 0;
			$limiter = (isset($limiter) && $limiter != '') ? $limiter : 12;

			if (isset($cat_id) && $cat_id != '' && $cat_id != 'all') {
				$search_criteria['category_id'] = $cat_id;
				$cat_id = $cat_id;
			}

			if (isset($sub_cat_id) && $sub_cat_id != '') {
				$search_criteria['sub_category_id'] = $sub_cat_id;
				$cat_id = $sub_cat_id;
			}

			if (isset($serach_query) && $serach_query != '') {
				$search_criteria['product_name LIKE'] = '%' . $serach_query . '%';
			}

			if (isset($price) && $price != '') {
				$price_e = explode('-', $price);
				if (isset($price_e[0]) && $price_e[0] != '') {
					$search_criteria['price >='] =  $price_e[0];
				}
				if (isset($price_e[1]) && $price_e[1] != '') {
					$search_criteria['price <='] =  $price_e[1];
				}
			}

			$order_by = 'brij_products.id DESC';
			switch ($product_list_order) {
				case "product-name";
					$order_by = 'brij_products.product_name ASC';
					break;
				case "price-asc";
					$order_by = 'brij_products.price ASC';
					break;
				case "price-desc";
					$order_by = 'brij_products.price DESC';
					break;
				default:
					$order_by = 'brij_products.id DESC';
					break;
			}

			$search_criteria['brij_products.status ='] = 1;
			$data['productList'] = $this->product_model->getProducts($search_criteria, $order_by, $search_criteria_or, $limiter, $offset);
			$this->load->view('Ajax/products-list', $data);
			//echo "<pre>";print_r($data['productList']);
		}
	}
	public function saveData()
	{
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$isExist = $this->input->post('isExist', TRUE);
			$requestType = $this->input->post('requestType', TRUE);
			$date = date("Y-m-d H:i:s");
			$where_column['content_type ='] = 'Timeline';
			$isDataExists = $this->base_model->getOneRecordWithWhere('home_page_content', $where_column, 'id, json_content');
			if (isset($isDataExists) && !empty($isDataExists)) {
				$where_conditions = array("content_type" => 'Timeline');
				/*$update_data_exists =  array('json_content' => '');
						 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
				$existDataArray = json_decode($isDataExists->json_content, TRUE);
				$isExists = 0;
				if (isset($existDataArray) && !empty($existDataArray)) {
					foreach ($existDataArray as $key1 => $value1) {
						if ($value1['year'] == $this->input->post('year', TRUE) && $value1['year'] != $isExist) {
							$isExists++;
						}
					}
				}
				if ($isExists === 0) {
					if ($requestType == 'edit') {
						foreach ($existDataArray as $key2 => $value2) {
							if ($value2['year'] == $isExist) {
								$existDataArray[$key2]['year'] = $this->input->post('year', TRUE);
								$existDataArray[$key2]['description'] = $this->input->post('description', TRUE);
							}
						}
						$response = array(
							'message' => "Data has been updated successfully.",
							'dataContent' => 1,
						);
					}
					if ($requestType == 'add') {
						$existDataArray[] = array('year' => $this->input->post('year', TRUE), 'description' => $this->input->post('description', TRUE));
						$response = array(
							'message' => "Data has been saved successfully.",
							'dataContent' => 1,
						);
					}
					$update_data['json_content'] =  json_encode($existDataArray);
					$update_data['updated_at'] =  $date;
					$last_update_id = $this->base_model->update_entry('home_page_content', $update_data, $where_conditions);
				} else {
					$response = array(
						'message' => "Year Data already exists in our system.",
						'dataContent' => 0,
					);
				}
			} else {
				$insert_data = array(
					'json_content' => json_encode(array($this->input->post())),
					'content_type' => 'Timeline',
					'created_at' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('home_page_content', $insert_data);
				if ($last_inserted_id) {

					$response = array(
						'message' => "Data has been saved successfully.",
						'dataContent' => 1,
					);
				}
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

	public function saveNumberData()
	{
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$date = date("Y-m-d H:i:s");
			$where_conditions = array("content_type" => 'Number');
			/*$update_data_exists =  array('json_content' => '');
						 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
			$existDataArray['keyword_num'] = $this->input->post('keyword_num', TRUE);
			$existDataArray['project_num'] = $this->input->post('project_num', TRUE);
			$existDataArray['traffic_num'] = $this->input->post('traffic_num', TRUE);
			$existDataArray['customer_num'] = $this->input->post('customer_num', TRUE);

			$response = array(
				'message' => "Data has been updated successfully.",
				'dataContent' => 1,
			);

			$update_data['json_content'] =  json_encode($existDataArray);
			$update_data['updated_at'] =  $date;
			$last_update_id = $this->base_model->update_entry('home_page_content', $update_data, $where_conditions);
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

	public function saveProvenData()
	{
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$date = date("Y-m-d H:i:s");
			$where_conditions = array("content_type" => 'Proven', 'page_id' => SEM_PAGE_ID);
			/*$update_data_exists =  array('json_content' => '');
						 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
			$existDataArray['cost_num_bef'] = $this->input->post('cost_num_bef', TRUE);
			$existDataArray['cost_num_after'] = $this->input->post('cost_num_after', TRUE);

			$existDataArray['return_num_bef'] = $this->input->post('return_num_bef', TRUE);
			$existDataArray['return_num_after'] = $this->input->post('return_num_after', TRUE);

			$existDataArray['conversion_num_bef'] = $this->input->post('conversion_num_bef', TRUE);
			$existDataArray['conversion_num_after'] = $this->input->post('conversion_num_after', TRUE);

			$existDataArray['cost_per_click_bef'] = $this->input->post('cost_per_click_bef', TRUE);
			$existDataArray['cost_per_click_after'] = $this->input->post('cost_per_click_after', TRUE);

			$response = array(
				'message' => "Data has been updated successfully.",
				'dataContent' => 1,
			);

			$update_data['json_content'] =  json_encode($existDataArray);
			$update_data['updated_at'] =  $date;
			$last_update_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

	public function saveHeadingData()
	{
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$date = date("Y-m-d H:i:s");
			$where_conditions = array("content_type" => 'Heading', 'page_id' => SEM_PAGE_ID);
			/*$update_data_exists =  array('json_content' => '');
						 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
			$existDataArray['heading'] = $this->input->post('heading', TRUE);
			$update_data['json_content'] =  json_encode($existDataArray);
			$update_data['updated_at'] =  $date;
			$last_update_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
			$response = array(
				'message' => "Data has been updated successfully.",
				'dataContent' => 1,
			);
			$this->output->set_header('Content-type: application/json');
		    $this->output->set_output(json_encode($response));

		}
	}

	public function saveHeadingGuestData()
	{
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$date = date("Y-m-d H:i:s");
			$where_conditions = array("content_type" => 'Heading', 'page_id' => GUEST_POSTING_PAGE_ID);
			/*$update_data_exists =  array('json_content' => '');
						 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
			$existDataArray['heading'] = $this->input->post('heading', TRUE);

			$response = array(
				'message' => "Data has been updated successfully.",
				'dataContent' => 1,
			);

			$update_data['json_content'] =  json_encode($existDataArray);
			$update_data['updated_at'] =  $date;
			$last_update_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

	public function saveVideoData()
	{
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$date = date("Y-m-d H:i:s");
			$where_conditions = array("content_type" => 'Video', 'page_id' => GUEST_POSTING_PAGE_ID);
			/*$update_data_exists =  array('json_content' => '');
						 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
			$existDataArray['title'] = $this->input->post('title', TRUE);
			$existDataArray['link'] = $this->input->post('link', TRUE);

			$response = array(
				'message' => "Data has been updated successfully.",
				'dataContent' => 1,
			);

			$update_data['json_content'] =  json_encode($existDataArray);
			$update_data['updated_at'] =  $date;
			$last_update_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

			
	public function saveGuestNumberData(){
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent'=>0,
		);					
		if ($this->input->is_ajax_request()){
			//print_r($this->input->post());
				$date = date("Y-m-d H:i:s");
					 $where_conditions = array("content_type"=>'Number','page_id'=>GUEST_POSTING_PAGE_ID);
					 /*$update_data_exists =  array('json_content' => '');
					 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
						$existDataArray['ncontent'] = $this->input->post('ncontent', TRUE);
						$existDataArray['number1'] = $this->input->post('number1', TRUE);
						$existDataArray['ntitle1'] = $this->input->post('ntitle1', TRUE);
						
						$existDataArray['number2'] = $this->input->post('number2', TRUE);
						$existDataArray['ntitle2'] = $this->input->post('ntitle2', TRUE);
						
						$existDataArray['number3'] = $this->input->post('number3', TRUE);
						$existDataArray['ntitle3'] = $this->input->post('ntitle3', TRUE);
						
						$existDataArray['number4'] = $this->input->post('number4', TRUE);
						$existDataArray['ntitle4'] = $this->input->post('ntitle4', TRUE);
						
						$existDataArray['number5'] = $this->input->post('number5', TRUE);
						$existDataArray['ntitle5'] = $this->input->post('ntitle5', TRUE);
						
						
					   $response = array(
							'message' => "Data has been updated successfully.",
							'dataContent' => 1,
						);
						
						 $update_data['json_content'] =  json_encode($existDataArray);
						 $update_data['updated_at'] =  $date;
						 $last_update_id = $this->base_model->update_entry('inner_page_content', $update_data ,$where_conditions);
					
				
					
		}
	  $this->output->set_header('Content-type: application/json');
	  $this->output->set_output(json_encode($response));
	}


	public function saveQualityData()
	{
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$date = date("Y-m-d H:i:s");
			$where_conditions = array("content_type" => 'Quality', 'page_id' => GUEST_POSTING_PAGE_ID);
			/*$update_data_exists =  array('json_content' => '');
						 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
			$existDataArray['title'] = $this->input->post('title', TRUE);
			$existDataArray['content'] = $this->input->post('content', TRUE);

			$response = array(
				'message' => "Data has been updated successfully.",
				'dataContent' => 1,
			);

			$update_data['json_content'] =  json_encode($existDataArray);
			$update_data['updated_at'] =  $date;
			$last_update_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}


	public function load_table_data()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$requestType = $this->input->post('requestType', TRUE);
			$where_column['content_type ='] = $requestType;
			$timelineData = $this->base_model->getOneRecordWithWhere('home_page_content', $where_column, 'id, json_content');
			if (isset($timelineData) && !empty($timelineData)) {
				$timelineData = (isset($timelineData->json_content) && $timelineData->json_content != '[]') ? json_decode($timelineData->json_content, TRUE) : '';
				if ($timelineData != '') {
					$response = array(
						'timelineData' => $timelineData,
						'dataContent' => 1,
					);
				} else {

					$response = array(
						'message' => "Data does not found.",
						'dataContent' => 0,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}


	public function load_inner_table_data()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$requestType = $this->input->post('requestType', TRUE);
			$pid = $this->input->post('pid', TRUE);
			$where_column['content_type ='] = $requestType;
			$where_column['page_id ='] = $pid;
			$timelineData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
			if (isset($timelineData) && !empty($timelineData)) {
				$timelineData = (isset($timelineData->json_content) && $timelineData->json_content != '[]') ? json_decode($timelineData->json_content, TRUE) : '';
				if ($timelineData != '') {
					$response = array(
						'timelineData' => $timelineData,
						'dataContent' => 1,
					);
				} else {

					$response = array(
						'message' => "Data does not found.",
						'dataContent' => 0,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}


	public function delete_table_data()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$requestType = $this->input->post('requestType', TRUE);
			$deleteId = $this->input->post('deleteId', TRUE);
			$where_column['content_type ='] = $requestType;
			$date = date("Y-m-d H:i:s");
			$timelineData = $this->base_model->getOneRecordWithWhere('home_page_content', $where_column, 'id, json_content');
			if (isset($timelineData) && !empty($timelineData)) {
				$timelineData = json_decode($timelineData->json_content, TRUE);
				// get array index to delete
				$arr_index = array();
				foreach ($timelineData as $key => $value) {
					if ($value['year'] == $deleteId) {
						$arr_index[] = $key;
					}
				}

				// delete data
				foreach ($arr_index as $i) {
					unset($timelineData[$i]);
				}

				// rebase array
				$timelineData = array_values($timelineData);
				$update_data['json_content'] =  json_encode($timelineData);
				$update_data['updated_at'] =  $date;
				$last_update_id = $this->base_model->update_entry('home_page_content', $update_data, $where_column);
				if ($last_update_id) {
					$response = array(
						'message' => 'Data has been deleted successfully.',
						'dataContent' => 1,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

	public function uploadTrustedFile()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			$isData = 1;
			$existDataArray = array();
			for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
				$target_path = "uploads/trusted_images/";
				$ext = explode('.', basename($_FILES['images']['name'][$i]));
				$target_path = $target_path . $ext[0] . '-' . time() . "." . $ext[count($ext) - 1];
				if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_path)) {
					$existDataArray[] = array('image' => $ext[0] . '-' . time() . "." . $ext[count($ext) - 1]);
					$isData = 1;
				} else {
					$isData = 0;
				}
			}
			//print_r($imageName);
			//die;
			$date = date("Y-m-d H:i:s");
			$where_column['content_type ='] = 'Trusted';
			$isDataExists = $this->base_model->getOneRecordWithWhere('home_page_content', $where_column, 'id, json_content');
			if (isset($isDataExists) && !empty($isDataExists) && $isDataExists != '') {
				$where_conditions = array("content_type" => 'Trusted');
				$existDataArray1 = json_decode($isDataExists->json_content, TRUE);
				$existDataArray = array_merge($existDataArray1, $existDataArray);
				$update_data['json_content'] =  json_encode($existDataArray);
				$update_data['updated_at'] =  $date;
				$last_update_id = $this->base_model->update_entry('home_page_content', $update_data, $where_conditions);
				$isData = 1;
			} else {
				$insert_data = array(
					'json_content' => json_encode($existDataArray),
					'content_type' => 'Trusted',
					'created_at' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('home_page_content', $insert_data);
				if ($last_inserted_id) {
					$isData = 1;
				}
			}
			if ($isData === 1) {
				$response = array(
					'message' => "The images has been uploaded successfully",
					'dataContent' => 1,
				);
			} else {

				$response = array(
					'message' => "There was an error uploading the images, please try again!",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}


	public function uploadInnerTrustedFile()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			$isData = 1;
			$existDataArray = array();
			for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
				$target_path = "uploads/trusted_images/";
				$ext = explode('.', basename($_FILES['images']['name'][$i]));
				$target_path = $target_path . $ext[0] . '-' . time() . "." . $ext[count($ext) - 1];
				if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_path)) {
					$existDataArray[] = array('image' => $ext[0] . '-' . time() . "." . $ext[count($ext) - 1]);
					$isData = 1;
				} else {
					$isData = 0;
				}
			}
			//print_r($imageName);
			//die;
			$date = date("Y-m-d H:i:s");
			$pid = $this->input->post('pid');
			$where_column['content_type ='] = 'Trusted';
			$where_column['page_id ='] = $pid;
			$isDataExists = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
			if (isset($isDataExists) && !empty($isDataExists) && $isDataExists != '') {
				$where_conditions = array("content_type" => 'Trusted', 'page_id' => $pid);
				$existDataArray1 = json_decode($isDataExists->json_content, TRUE);
				$existDataArray = array_merge($existDataArray1, $existDataArray);
				$update_data['json_content'] =  json_encode($existDataArray);
				$update_data['updated_at'] =  $date;
				$last_update_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
				$isData = 1;
			} else {
				$insert_data = array(
					'json_content' => json_encode($existDataArray),
					'content_type' => 'Trusted',
					'created_at' =>  $date,
					'page_id' => $pid
				);
				$last_inserted_id = $this->base_model->insert_entry('inner_page_content', $insert_data);
				if ($last_inserted_id) {
					$isData = 1;
				}
			}
			if ($isData === 1) {
				$response = array(
					'message' => "The images has been uploaded successfully",
					'dataContent' => 1,
				);
			} else {

				$response = array(
					'message' => "There was an error uploading the images, please try again!",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

	public function uploadMediaFile()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			$isData = 1;
			$existDataArray = array();
			for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
				$target_path = "uploads/media_images/";
				$ext = explode('.', basename($_FILES['images']['name'][$i]));
				$target_path = $target_path . $ext[0] . '-' . time() . "." . $ext[count($ext) - 1];
				if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_path)) {
					$existDataArray[] = array('image' => $ext[0] . '-' . time() . "." . $ext[count($ext) - 1]);
					$isData = 1;
				} else {
					$isData = 0;
				}
			}
			//print_r($imageName);
			//die;
			$date = date("Y-m-d H:i:s");
			$where_column['content_type ='] = 'Media';
			$isDataExists = $this->base_model->getOneRecordWithWhere('home_page_content', $where_column, 'id, json_content');
			if (isset($isDataExists) && !empty($isDataExists) && $isDataExists != '') {
				$where_conditions = array("content_type" => 'Media');
				$existDataArray1 = json_decode($isDataExists->json_content, TRUE);
				$existDataArray = array_merge($existDataArray1, $existDataArray);
				$update_data['json_content'] =  json_encode($existDataArray);
				$update_data['updated_at'] =  $date;
				$last_update_id = $this->base_model->update_entry('home_page_content', $update_data, $where_conditions);
				$isData = 1;
			} else {

				$insert_data = array(
					'json_content' => json_encode($existDataArray),
					'content_type' => 'Media',
					'created_at' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('home_page_content', $insert_data);
				if ($last_inserted_id) {
					$isData = 1;
				}
			}
			if ($isData === 1) {
				$response = array(
					'message' => "The images has been uploaded successfully",
					'dataContent' => 1,
				);
			} else {

				$response = array(
					'message' => "There was an error uploading the images, please try again!",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}


	public function getTrustedImageList()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$requestType = $this->input->post('requestType', TRUE);
			$where_column['content_type ='] = $requestType;
			$trustedData = $this->base_model->getOneRecordWithWhere('home_page_content', $where_column, 'id, json_content');
			if (isset($trustedData) && !empty($trustedData)) {
				$trustedData = (isset($trustedData->json_content) && $trustedData->json_content != '[]') ? json_decode($trustedData->json_content, TRUE) : '';
				if ($trustedData != '') {
					$response = array(
						'trustedData' => $trustedData,
						'dataContent' => 1,
					);
				} else {

					$response = array(
						'message' => "Data does not found.",
						'dataContent' => 0,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}


	public function getInnerTrustedImageList()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$requestType = $this->input->post('requestType', TRUE);
			$pid = $this->input->post('pid', TRUE);
			$where_column['content_type ='] = $requestType;
			$where_column['page_id ='] = $pid;
			$trustedData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
			if (isset($trustedData) && !empty($trustedData) &&  !empty($pid)) {
				$trustedData = (isset($trustedData->json_content) && $trustedData->json_content != '[]') ? json_decode($trustedData->json_content, TRUE) : '';
				if ($trustedData != '') {
					$response = array(
						'trustedData' => $trustedData,
						'dataContent' => 1,
					);
				} else {

					$response = array(
						'message' => "Data does not found.",
						'dataContent' => 0,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

	public function delete_trusted_data()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$requestType = $this->input->post('requestType', TRUE);
			$deleteId = $this->input->post('deleteId', TRUE);
			$where_column['content_type ='] = $requestType;
			$date = date("Y-m-d H:i:s");
			$trustedData = $this->base_model->getOneRecordWithWhere('home_page_content', $where_column, 'id, json_content');
			if (isset($trustedData) && !empty($trustedData)) {
				$trustedData = json_decode($trustedData->json_content, TRUE);
				// get array index to delete
				$arr_index = array();
				foreach ($trustedData as $key => $value) {
					if ($value['image'] == $deleteId) {
						$arr_index[] = $key;
					}
				}

				// delete data
				foreach ($arr_index as $i) {
					unlink(realpath('uploads/trusted_images/' . $deleteId));
					unset($trustedData[$i]);
				}

				// rebase array
				$trustedData = array_values($trustedData);
				$update_data['json_content'] =  json_encode($trustedData);
				$update_data['updated_at'] =  $date;
				$last_update_id = $this->base_model->update_entry('home_page_content', $update_data, $where_column);
				if ($last_update_id) {
					$response = array(
						'message' => 'Data has been deleted successfully.',
						'dataContent' => 1,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}


	public function delete_inner_trusted_data()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$requestType = $this->input->post('requestType', TRUE);
			$deleteId = $this->input->post('deleteId', TRUE);
			$pid = $this->input->post('pid', TRUE);
			$where_column['content_type ='] = $requestType;
			$where_column['page_id ='] = $pid;
			$date = date("Y-m-d H:i:s");
			$trustedData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
			if (isset($trustedData) && !empty($trustedData)) {
				$trustedData = json_decode($trustedData->json_content, TRUE);
				// get array index to delete
				$arr_index = array();
				foreach ($trustedData as $key => $value) {
					if ($value['image'] == $deleteId) {
						$arr_index[] = $key;
					}
				}

				// delete data
				foreach ($arr_index as $i) {
					unlink(realpath('uploads/trusted_images/' . $deleteId));
					unset($trustedData[$i]);
				}

				// rebase array
				$trustedData = array_values($trustedData);
				$update_data['json_content'] =  json_encode($trustedData);
				$update_data['updated_at'] =  $date;
				$last_update_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_column);
				if ($last_update_id) {
					$response = array(
						'message' => 'Data has been deleted successfully.',
						'dataContent' => 1,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}


	public function delete_media_data()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$requestType = $this->input->post('requestType', TRUE);
			$deleteId = $this->input->post('deleteId', TRUE);
			$where_column['content_type ='] = $requestType;
			$date = date("Y-m-d H:i:s");
			$trustedData = $this->base_model->getOneRecordWithWhere('home_page_content', $where_column, 'id, json_content');
			if (isset($trustedData) && !empty($trustedData)) {
				$trustedData = json_decode($trustedData->json_content, TRUE);
				// get array index to delete
				$arr_index = array();
				foreach ($trustedData as $key => $value) {
					if ($value['image'] == $deleteId) {
						$arr_index[] = $key;
					}
				}

				// delete data
				foreach ($arr_index as $i) {
					unlink(realpath('uploads/media_images/' . $deleteId));
					unset($trustedData[$i]);
				}

				// rebase array
				$trustedData = array_values($trustedData);
				$update_data['json_content'] =  json_encode($trustedData);
				$update_data['updated_at'] =  $date;
				$last_update_id = $this->base_model->update_entry('home_page_content', $update_data, $where_column);
				if ($last_update_id) {
					$response = array(
						'message' => 'Data has been deleted successfully.',
						'dataContent' => 1,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}

	public function saveInnerPageMultipleData(){
		$response = array(
			'message' => "Data does not save. Please try again!",
			'dataContent'=>0,
		);					
		if ($this->input->is_ajax_request()){
			//print_r($this->input->post());
				$isExist = $this->input->post('isExist', TRUE);
				$requestType = $this->input->post('requestType', TRUE);
				$contentType = $this->input->post('contentType', TRUE);
				$pageID = $this->input->post('pageID', TRUE);
				$date = date("Y-m-d H:i:s");
				$where_column['content_type ='] = $contentType;
				$where_column['page_id ='] = $pageID;
				$isDataExists = $this->base_model->getOneRecordWithWhere('inner_page_content',$where_column, 'id, json_content');
				if (isset($isDataExists) && !empty($isDataExists)){
					 $where_conditions = array("content_type"=> $contentType,"page_id" => $pageID);
					 /*$update_data_exists =  array('json_content' => '');
					 $this->base_model->update_entry('home_page_content', $update_data_exists ,$where_conditions);*/
					 $existDataArray = json_decode($isDataExists->json_content, TRUE);
					 $isExists = 0;
					 if(isset($existDataArray) && !empty($existDataArray)){
						 foreach ($existDataArray as $key1=>$value1){
							   if ($value1['title'] == $this->input->post('title', TRUE) && $value1['title'] != $isExist){
								   $isExists++;   
							   }
						 }
					 }
					 if ($isExists === 0){
						 if ($requestType == 'edit'){
							 foreach ($existDataArray as $key2 => $value2) {
								if ($value2['title'] == $isExist) {
									$existDataArray[$key2]['title'] = $this->input->post('title', TRUE);
								}
							  }
							   $response = array(
									'message' => "Data has been updated successfully.",
									'dataContent' => 1,
								);
						 } 
						 if ($requestType == 'add'){
							$existDataArray[] = array('title' => $this->input->post('title', TRUE)); 
							$response = array(
								'message' => "Data has been saved successfully.",
								'dataContent'=>1,
							);
						 }
						 $update_data['json_content'] =  json_encode($existDataArray);
						 $update_data['updated_at'] =  $date;
						 $last_update_id = $this->base_model->update_entry('inner_page_content', $update_data ,$where_conditions);
					 }else{
						$response = array(
								'message' => "Data already exists in our system.",
								'dataContent'=>0,
							); 
					 }
					 
				}else{
					$insert_data = array('json_content' => json_encode(array($this->input->post())),
						 'content_type' => $contentType,
						 'page_id' => $pageID,
						'created_at'=>  $date,
					);
					$last_inserted_id = $this->base_model->insert_entry('inner_page_content',$insert_data);
					if ($last_inserted_id){
						
						$response = array(
								'message' => "Data has been saved successfully.",
								'dataContent'=>1,
							);
					}
				}
					
		}
	  $this->output->set_header('Content-type: application/json');
	  $this->output->set_output(json_encode($response));
	}

	public function delete_inner_multiple_table_data()
	{
		$response = array(
			'message' => "Data does not found. Please try again!",
			'dataContent' => 0,
		);
		if ($this->input->is_ajax_request()) {
			//print_r($this->input->post());
			$contentType = $this->input->post('contentType', TRUE);
			$pageID = $this->input->post('pageID', TRUE);
			$deleteId = $this->input->post('deleteId', TRUE);
			$where_column['content_type ='] = $contentType;
			$where_column['page_id ='] = $pageID;
			$date = date("Y-m-d H:i:s");
			$isDataExists = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
			if (isset($isDataExists) && !empty($isDataExists)) {
				$isDataExists = json_decode($isDataExists->json_content, TRUE);
				// get array index to delete
				$arr_index = array();
				foreach ($isDataExists as $key => $value) {
					if ($value['title'] == $deleteId) {
						$arr_index[] = $key;
					}
				}

				// delete data
				foreach ($arr_index as $i) {
					unset($isDataExists[$i]);
				}

				// rebase array
				$isDataExists = array_values($isDataExists);
				$update_data['json_content'] =  json_encode($isDataExists);
				$update_data['updated_at'] =  $date;
				$last_update_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_column);
				if ($last_update_id) {
					$response = array(
						'message' => 'Data has been deleted successfully.',
						'dataContent' => 1,
					);
				}
			} else {
				$response = array(
					'message' => "Data does not found.",
					'dataContent' => 0,
				);
			}
		}
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output(json_encode($response));
	}
}
