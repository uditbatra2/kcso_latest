<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
	}

	//Method to generate a unique api key every time
	private function _generateApiKey()
	{
		return md5(uniqid(rand(), true));
	}

	public function index()
	{
		if ($this->admin_model->check_logged() === true) {
			redirect(base_url() . 'admin/dashboard');
		}
		$data['title'] = 'Login';
		$this->load->view('Admin/login', $data);
	}

	//Method to generate a unique customer id number every time
	private function _generateCustomerDentificationNumber($last_insert_user_id, $state_id)
	{
		$stateData = $this->base_model->getOneRecord("brij_states", "id", $state_id, "short_name");
		$where_conditions = array("id" => $last_insert_user_id);
		$unique_code = 'CUST-00000' . $last_insert_user_id . '-' . $stateData->short_name;
		$update_data = array('unique_code' => $unique_code);
		$last_inserted_id = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
	}

	//Method to generate a unique customer id number every time
	private function _generateCustomerDentificationNumberAdmin($last_insert_user_id, $state_id)
	{
		$stateData = $this->base_model->getOneRecord("brij_states", "id", $state_id, "short_name");
		$where_conditions = array("user_id" => $last_insert_user_id);
		$unique_code = 'ADMIN-00000' . $last_insert_user_id . '-' . $stateData->short_name;
		$update_data = array('usr_code' => $unique_code);
		$last_inserted_id = $this->base_model->update_entry('brij_admin', $update_data, $where_conditions);
	}

	public function dashboard()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$where_condition = $where_condition = array();
		$data['totalPosts'] = $this->base_model->getNumRows('posts', $where_condition);
		$data['totalTestimonials'] = $this->base_model->getNumRows('testimonials', $where_condition);
		$data['totalPages'] = $this->base_model->getNumRows('pages', $where_condition);
		$where_condition = array('user_role !=' => 1);
		$data['totalAdminUser'] = $this->base_model->getNumRows('brij_admin', $where_condition);
		//$where_condition=array('user_role !='=>1);
		$data['totalCaseStudy'] = $this->base_model->getNumRows('case_studies', $where_condition = array());
		$data['title'] = 'Dashboard';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/dashboard', $data);
		$this->load->view('Admin/include/footer');
	}

	//Sub Categories Coding Start
	public function sub_categories_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$parent_id = trim($this->input->get('parent_id', TRUE));
		$status = $this->input->get('status', TRUE);

		if (!isset($serach_query) && !isset($status) && !isset($parent_id) && empty($status) && empty($serach_query) && empty($parent_id)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$parent_id = (isset($parent_id) && $parent_id != '') ? $parent_id : "";
		$status = (isset($status) && $status != '') ? $status : "";

		if (isset($status) && $status != '') {
			$search_criteria['subcategory.status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['subcategory.name LIKE'] = '%' . $serach_query . '%';
		}

		if (isset($parent_id) && $parent_id != '') {
			$search_criteria['subcategory.parent_id ='] = $parent_id;
		}

		$data['subCategoryList'] = $this->admin_model->getSubCategories($search_criteria, $order_by = 'id DESC');
		//print_r($data['subCategoryList']);

		$conditions_cat['parent_id ='] = 0;
		$data['catData'] = $this->base_model->getAllRows('brij_product_categories', 'name ASC', $conditions_cat);

		$data['title'] = 'Sub Categories List';

		$data['searchcategoryKeyword'] = $serach_query;
		$data['searchsubcategoryKeyword'] = $parent_id;
		$data['statusKeyword'] = $status;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/sub-categories-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_sub_category()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				$catDetails = $this->base_model->getOneRecord("brij_product_categories", "id", $this->input->post('id', TRUE), "image");
				//Check whether user upload category image
				if (!empty($_FILES['cat_image']['name'])) {
					$config['upload_path'] = 'uploads/category_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['cat_image']['name'];
					$catsfilename = 'uploads/category_images/' . $catDetails->image;
					if (file_exists($catsfilename) && !empty($catDetails->image) && isset($catDetails->image)) {
						$_image = $catDetails->image;
						unlink(realpath('uploads/category_images/' . $_image));
						unlink(realpath('uploads/category_images/large/' . $_image));
						unlink(realpath('uploads/category_images/medium/' . $_image));
						unlink(realpath('uploads/category_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('cat_image')) {
						$uploadData = $this->upload->data();
						$cat_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 870;
						$configSize1['height']          = 180;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/category_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/category_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/category_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$cat_image = $catDetails->image;
					}
				} else {
					$cat_image = $catDetails->image;
				}
				$update_data = array(
					'parent_id' => $this->input->post('parent_id', TRUE),
					'name' => $this->input->post('name', TRUE),
					'image' =>  $cat_image,
					'description' =>  $this->input->post('description', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_product_categories', $update_data, $where_conditions);
			} else {
				//Check whether user upload category image
				if (!empty($_FILES['cat_image']['name'])) {
					$config['upload_path'] = 'uploads/category_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['cat_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('cat_image')) {
						$uploadData = $this->upload->data();
						$cat_image = $uploadData['file_name'];

						$this->load->library('image_lib');

						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 870;
						$configSize1['height']          = 180;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/category_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/category_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/category_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$cat_image = '';
					}
				} else {
					$cat_image = '';
				}
				$insert_data = array(
					'parent_id' => $this->input->post('parent_id', TRUE),
					'name' => $this->input->post('name', TRUE),
					'image' =>  $cat_image,
					'description' =>  $this->input->post('description', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_product_categories', $insert_data);
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('cat_success', 'Sub Category has been updated successfully.');
				} else {
					$this->session->set_flashdata('cat_success', 'Sub Category has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/sub_categories_list");
				}
			}
			$this->session->set_flashdata('cat_error', 'Sub Category does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/sub_categories_list");
			}
		}
	}

	public function sub_category_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$cat_id = trim($this->input->get('cat_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $cat_id);
					$last_inserted_id = $this->base_model->update_entry('brij_product_categories', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $cat_id);
					$last_inserted_id = $this->base_model->update_entry('brij_product_categories', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('cat_success', 'Sub Category Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/sub_categories_list");
			}
		}
	}

	public function delete_sub_category()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$sub_cat_id = $this->input->post('cat_id');
			$catDetails = $this->base_model->getOneRecord("brij_product_categories", "id", $sub_cat_id, "id,parent_id,image");
			$parent_id = $catDetails->parent_id;

			$search_criteria1["category_id ="] = $parent_id;
			$search_criteria1["sub_category_id ="] = $sub_cat_id;
			$productImageDetails = $this->base_model->getAllRows('brij_products', 'id DESC', $search_criteria1);
			if (!empty($productImageDetails) && count($productImageDetails) > 0) {
				foreach ($productImageDetails as $productImageDetails) {
					$productDetailsiMAGE = $this->base_model->getOneRecord("brij_product_images", "product_id", $productImageDetails->id, "id,images");
					$profilename = 'uploads/product_images/' . $productDetailsiMAGE->images;
					if (file_exists($profilename) && !empty($productDetailsiMAGE->images) && isset($productDetailsiMAGE->images)) {
						$_image = $productDetailsiMAGE->images;
						unlink(realpath('uploads/product_images/' . $_image));
						unlink(realpath('uploads/product_images/large/' . $_image));
						unlink(realpath('uploads/product_images/medium/' . $_image));
						unlink(realpath('uploads/product_images/small/' . $_image));
					}

					$where_conditions_h = array('product_id' => $productImageDetails->id);
					$deleted = $this->base_model->deleteWithWhereConditions('brij_product_images', $where_conditions_h);

					$where_conditions_c = array('product_id' => $productImageDetails->id);
					$deleted = $this->base_model->deleteWithWhereConditions('brij_order_items', $where_conditions_c);

					$where_conditions_e = array('product_id' => $productImageDetails->id);
					$deleted = $this->base_model->deleteWithWhereConditions('brij_product_recently_views', $where_conditions_e);

					$where_conditions_f = array('product_id' => $productImageDetails->id);
					$deleted = $this->base_model->deleteWithWhereConditions('brij_product_reviews', $where_conditions_f);

					$where_conditions_g = array('product_id' => $productImageDetails->id);
					$deleted = $this->base_model->deleteWithWhereConditions('brij_wishlist', $where_conditions_g);
				}
			}

			$where_conditions_b = array('category_id' => $parent_id, 'sub_category_id' => $sub_cat_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_products', $where_conditions_b);
			$catfilename = 'uploads/category_images/' . $catDetails->image;
			if (file_exists($catfilename) && !empty($catDetails->image) && isset($catDetails->image)) {
				$_image = $catDetails->image;
				unlink(realpath('uploads/category_images/' . $_image));
				unlink(realpath('uploads/category_images/large/' . $_image));
				unlink(realpath('uploads/category_images/medium/' . $_image));
				unlink(realpath('uploads/category_images/small/' . $_image));
			}

			$where_conditions_d = array('id' => $sub_cat_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_product_categories', $where_conditions_d);

			$this->session->set_flashdata('cat_success', 'Sub Category has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/sub_categories_list");
			}
		}
	}
	//Sub Categories Coding End

	//Location Coding Start
	public function locations_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);

		if (!isset($serach_query) && !isset($status) && empty($status) && empty($serach_query)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";

		if (isset($status) && $status != '') {
			$search_criteria['status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['location_name LIKE'] = '%' . $serach_query . '%';
		}

		$select_column_name = "*";
		$data['locationList'] = $this->admin_model->getSearch('brij_locations', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);
		//print_r($data['locationList']);

		$data['title'] = 'Locations List';

		$data['searchlocationKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/locations-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_location()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				$locationDetails = $this->base_model->getOneRecord("brij_locations", "id", $this->input->post('id', TRUE), "*");
				$update_data = array(
					'location_name' => $this->input->post('location_name', TRUE),
					'location_pin_code' => $this->input->post('location_pin_code', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_locations', $update_data, $where_conditions);
			} else {
				$insert_data = array(
					'location_name' => $this->input->post('location_name', TRUE),
					'location_pin_code' => $this->input->post('location_pin_code', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_locations', $insert_data);
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('loc_success', 'Location has been updated successfully.');
				} else {
					$this->session->set_flashdata('loc_success', 'Location has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/locations_list");
				}
			}
			$this->session->set_flashdata('loc_error', 'Location does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/locations_list");
			}
		}
	}

	public function location_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$loc_id = trim($this->input->get('loc_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $loc_id);
					$last_inserted_id = $this->base_model->update_entry('brij_locations', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $loc_id);
					$last_inserted_id = $this->base_model->update_entry('brij_locations', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('loc_success', 'Location Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/locations_list");
			}
		}
	}

	public function delete_location()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$loc_id = $this->input->post('loc_id');
			$locDetails = $this->base_model->getOneRecord("brij_locations", "id", $loc_id, "*");
			$locid = $locDetails->id;

			$where_conditions_d = array('id' => $loc_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_locations', $where_conditions_d);

			$this->session->set_flashdata('loc_success', 'Location has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/locations_list");
			}
		}
	}
	//Location Coding End

	//Product Coding Start
	public function products_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = $search_criteria_or = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$category_id = trim($this->input->get('category_id', TRUE));
		$sub_category_id = trim($this->input->get('sub_category_id', TRUE));
		$status = $this->input->get('status', TRUE);

		if (!isset($serach_query) && !isset($status) && !isset($category_id) && !isset($sub_category_id) && empty($status) && empty($serach_query) && empty($category_id) && empty($sub_category_id)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$category_id = (isset($category_id) && $category_id != '') ? $category_id : "";
		$sub_category_id = (isset($sub_category_id) && $sub_category_id != '') ? $sub_category_id : "";
		$status = (isset($status) && $status != '') ? $status : "";


		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['brij_products.product_name LIKE'] = '%' . $serach_query . '%';
			$search_criteria_or['brij_products.product_code LIKE'] = '%' . $serach_query . '%';
		}

		if (isset($category_id) && $category_id != '') {
			$search_criteria['brij_products.category_id ='] = $category_id;
		}

		if (isset($sub_category_id) && $sub_category_id != '') {
			$search_criteria['brij_products.sub_category_id ='] = $sub_category_id;
		}

		if (isset($status) && $status != '') {
			$search_criteria['brij_products.status ='] = $status;
		}

		$data['productList'] = $this->admin_model->getProducts($search_criteria, $order_by = 'id DESC', $search_criteria_or);
		//print_r($data['productList']);

		if ($this->input->get('do', TRUE) && $this->input->get('do', TRUE) == 'download-excel') {
			$this->load->library("excel");
			$object = new PHPExcel();
			$object->setActiveSheetIndex(0);

			$table_columns = array('Product Code', 'Category Name', 'Sub Category Name', 'Product Name', 'Quantity', 'Price', 'Description', 'Stock Availability', 'Status', 'Popular', 'Created At', 'Updated At');

			$column = 0;

			foreach ($table_columns as $field) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}
			$object->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);

			for ($i = 'A'; $i != $object->getActiveSheet()->getHighestColumn(); $i++) {
				$object->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}
			//echo "<pre>";print_r($data['productList']);
			//die;

			$excel_row = 2;

			foreach ($data['productList'] as $productListAll) {
				$status = (isset($productListAll->status) && $productListAll->status == 1) ? 'Active' : 'Inactive';
				$stock_availability = (isset($productListAll->stock_availability) && $productListAll->stock_availability == 1) ? 'Yes' : 'No';
				$is_popular = (isset($productListAll->is_popular) && $productListAll->is_popular == 1) ? 'Yes' : 'No';
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $productListAll->product_code);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $productListAll->cat_name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $productListAll->subcat_name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $productListAll->product_name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $productListAll->quantity);
				$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $productListAll->price);
				$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $productListAll->description);
				$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $stock_availability);
				$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $status);
				$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $is_popular);
				$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, dateFormat("d-m-Y H:i", $productListAll->date_added));
				$object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, dateFormat("d-m-Y H:i", $productListAll->date_updated));
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Product-List-Data-' . date('d-m-Y') . '.xls"');
			$object_writer->save('php://output');
		}

		$conditions_cat['parent_id ='] = 0;
		$data['catData'] = $this->base_model->getAllRows('brij_product_categories', 'name ASC', $conditions_cat);

		$data['title'] = 'Products List';

		$data['priceType'] = $this->config->item('priceType');

		$data['searchproKeyword'] = $serach_query;
		$data['searchcategoryKeyword'] = $category_id;
		$data['searchsubcategoryKeyword'] = $sub_category_id;
		$data['statusKeyword'] = $status;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/products-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function product_reviews()
	{
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$do = trim($this->input->get('do', TRUE));
		$user_id = trim($this->input->get('user_id', TRUE));
		$product_id = trim($this->input->get('pro_id', TRUE));
		$review_status = trim($this->input->get('review_status', TRUE));
		$review_date_from = trim($this->input->get('review_date_from', TRUE));
		$review_date_to = trim($this->input->get('review_date_to', TRUE));

		if (!isset($product_id) && !isset($review_date_from) && !isset($review_date_to) && !isset($user_id) && !isset($review_status) && empty($review_date_from) && empty($review_date_to) && empty($review_status) && empty($user_id) && empty($product_id)) {
		}
		$product_id = (isset($product_id) && $product_id != '') ? $product_id : "";
		$review_status = (isset($review_status) && $review_status != '') ? $review_status : "";
		$user_id = (isset($user_id) && $user_id != '') ? $user_id : "";
		$review_date_from = (isset($review_date_from) && $review_date_from != '') ? $review_date_from : "";
		$review_date_to = (isset($review_date_to) && $review_date_to != '') ? $review_date_to : "";
		$do = (isset($do) && $do != '') ? $do : "";

		if (isset($review_date_from) && !empty($review_date_from) && $review_date_from != '') {
			$review_date_from = date("Y-m-d", strtotime($review_date_from));
			$search_criterias['DATE(product_reviews.review_date) >='] = $review_date_from;
		}
		if (isset($review_date_to) && !empty($review_date_to) && $review_date_to != '') {
			$review_date_to = date("Y-m-d", strtotime($review_date_to));
			$search_criterias['DATE(product_reviews.review_date) <='] = $review_date_to;
		}

		if (isset($user_id) && $user_id != '') {
			$search_criteria['product_reviews.user_id ='] = $user_id;
		}

		if (isset($product_id) && $product_id != '') {
			$search_criteria['product_reviews.product_id ='] = $product_id;
		}

		if (isset($review_status) && $review_status != '') {
			$search_criteria['product_reviews.review_status ='] = $review_status;
		}

		$select_column_name = "*";
		$data['productReviewsList'] = $this->admin_model->getProductReviews($search_criteria, $order_by = 'id DESC');

		$conditions_u['name !='] = '';
		$data['usersData'] = $this->base_model->getAllRows('brij_users', 'name ASC', $conditions_u);

		$conditions_p['product_name !='] = '';
		$data['productsData'] = $this->base_model->getAllRows('brij_products', 'product_name ASC', $conditions_p);

		$data['title'] = 'Product Reviews';

		$data['statusKeyword'] = $review_status;
		$data['userIDKeyword'] = $user_id;
		$data['productIDKeyword'] = $product_id;
		$data['searchreviewFromKeyword'] = $review_date_from;
		$data['searchreviewToKeyword'] = $review_date_to;
		$data['doKeyword'] = $do;

		//echo "<pre>";print_r($data);
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/product-reviews', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_product()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				$productDetails = $this->base_model->getOneRecord("brij_products", "id", $this->input->post('id', TRUE), "*");
				$update_data = array(
					'product_code' => $this->input->post('product_code', TRUE),
					'category_id' => $this->input->post('category_id', TRUE),
					'sub_category_id' => $this->input->post('sub_category_id', TRUE),
					'product_name' =>  $this->input->post('product_name', TRUE),
					'price' => $this->input->post('price', TRUE),
					'price_type' => $this->input->post('price_type', TRUE),
					'delivered_in_days' => $this->input->post('delivered_in_days', TRUE),
					'product_pincode' => $this->input->post('product_pincode', TRUE),
					'description' => $this->input->post('description', TRUE),
					'additional_info' => $this->input->post('additional_info', TRUE),
					'quantity' =>  $this->input->post('quantity', TRUE),
					'stock_availability' => $this->input->post('stock_availability', TRUE),
					'status' => $this->input->post('status', TRUE),
					'is_popular' =>  $this->input->post('is_popular', TRUE),
					'is_new' =>  $this->input->post('is_new', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_products', $update_data, $where_conditions);
				$last_inserted_id = $this->input->post('id', TRUE);
				//Check whether user upload product image
				if (!empty($_FILES['product_images']['name'])) {
					$filesCount = count($_FILES['product_images']['name']);
					for ($i = 0; $i < $filesCount; $i++) {
						$_FILES['product_image']['name']      = $_FILES['product_images']['name'][$i];
						$_FILES['product_image']['type']      = $_FILES['product_images']['type'][$i];
						$_FILES['product_image']['tmp_name']  = $_FILES['product_images']['tmp_name'][$i];
						$_FILES['product_image']['error']     = $_FILES['product_images']['error'][$i];
						$_FILES['product_image']['size']      = $_FILES['product_images']['size'][$i];

						// File upload configuration
						$config['upload_path'] = 'uploads/product_images/';
						$config['allowed_types'] = 'jpg|jpeg|png|gif';
						$config['file_name'] = $_FILES['product_image']['name'];

						// Load and initialize upload library
						$this->load->library('upload', $config);
						$this->upload->initialize($config);

						// Upload file to server
						if ($this->upload->do_upload('product_image')) {
							// Uploaded file data
							$fileData = $this->upload->data();
							$uploadData[$i]['product_id'] = $last_inserted_id;
							$uploadData[$i]['images'] = $fileData['file_name'];
							$uploadData[$i]['date_added'] = date("Y-m-d H:i:s");
							$this->load->library('image_lib');

							/* First size */
							$configSize1['image_library']   = 'gd2';
							$configSize1['source_image']    = $fileData['full_path'];
							$configSize1['create_thumb']    = FALSE;
							$configSize1['maintain_ratio']  = TRUE;
							$configSize1['width']           = 800;
							$configSize1['height']          = 600;
							$configSize1['new_image']   = ROOT_PATH . '/uploads/product_images/large';

							$this->image_lib->initialize($configSize1);
							$this->image_lib->resize();
							$this->image_lib->clear();
							/* Second size */
							$configSize2['image_library']   = 'gd2';
							$configSize2['source_image']    = $fileData['full_path'];
							$configSize2['create_thumb']    = FALSE;
							$configSize2['maintain_ratio']  = TRUE;
							$configSize2['width']           = 300;
							$configSize2['height']          = 300;
							$configSize2['new_image']   = ROOT_PATH . '/uploads/product_images/medium';

							$this->image_lib->initialize($configSize2);
							$this->image_lib->resize();
							$this->image_lib->clear();
							/* Third size */
							$configSize3['image_library']   = 'gd2';
							$configSize3['source_image']    = $fileData['full_path'];
							$configSize3['create_thumb']    = FALSE;
							$configSize3['maintain_ratio']  = TRUE;
							$configSize3['width']           = 90;
							$configSize3['height']          = 90;
							$configSize3['new_image']   =  ROOT_PATH . '/uploads/product_images/small';

							$this->image_lib->initialize($configSize3);
							$this->image_lib->resize();
							$this->image_lib->clear();
						}
					}
					if (!empty($uploadData)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('brij_product_images', $uploadData);
					}
				}
			} else {
				//Check whether user upload product image				
				$insert_data = array(
					'product_code' => $this->input->post('product_code', TRUE),
					'category_id' => $this->input->post('category_id', TRUE),
					'sub_category_id' => $this->input->post('sub_category_id', TRUE),
					'product_name' =>  $this->input->post('product_name', TRUE),
					'price' => $this->input->post('price', TRUE),
					'price_type' => $this->input->post('price_type', TRUE),
					'delivered_in_days' => $this->input->post('delivered_in_days', TRUE),
					'product_pincode' => $this->input->post('product_pincode', TRUE),
					'description' => $this->input->post('description', TRUE),
					'additional_info' => $this->input->post('additional_info', TRUE),
					'quantity' =>  $this->input->post('quantity', TRUE),
					'stock_availability' => $this->input->post('stock_availability', TRUE),
					'status' => $this->input->post('status', TRUE),
					'is_popular' =>  $this->input->post('is_popular', TRUE),
					'is_new' =>  $this->input->post('is_new', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_products', $insert_data);
				if ($last_inserted_id) {
					if (!empty($_FILES['product_images']['name'])) {
						$filesCount = count($_FILES['product_images']['name']);
						for ($i = 0; $i < $filesCount; $i++) {
							$_FILES['product_image']['name']      = $_FILES['product_images']['name'][$i];
							$_FILES['product_image']['type']      = $_FILES['product_images']['type'][$i];
							$_FILES['product_image']['tmp_name']  = $_FILES['product_images']['tmp_name'][$i];
							$_FILES['product_image']['error']     = $_FILES['product_images']['error'][$i];
							$_FILES['product_image']['size']      = $_FILES['product_images']['size'][$i];

							// File upload configuration
							$config['upload_path'] = 'uploads/product_images/';
							$config['allowed_types'] = 'jpg|jpeg|png|gif';
							$config['file_name'] = $_FILES['product_image']['name'];

							// Load and initialize upload library
							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							// Upload file to server
							if ($this->upload->do_upload('product_image')) {
								// Uploaded file data
								$fileData = $this->upload->data();
								$uploadData[$i]['product_id'] = $last_inserted_id;
								$uploadData[$i]['images'] = $fileData['file_name'];
								$uploadData[$i]['date_added'] = date("Y-m-d H:i:s");
								$this->load->library('image_lib');

								/* First size */
								$configSize1['image_library']   = 'gd2';
								$configSize1['source_image']    = $fileData['full_path'];
								$configSize1['create_thumb']    = FALSE;
								$configSize1['maintain_ratio']  = TRUE;
								$configSize1['width']           = 800;
								$configSize1['height']          = 600;
								$configSize1['new_image']   = ROOT_PATH . '/uploads/product_images/large';

								$this->image_lib->initialize($configSize1);
								$this->image_lib->resize();
								$this->image_lib->clear();
								/* Second size */
								$configSize2['image_library']   = 'gd2';
								$configSize2['source_image']    = $fileData['full_path'];
								$configSize2['create_thumb']    = FALSE;
								$configSize2['maintain_ratio']  = TRUE;
								$configSize2['width']           = 300;
								$configSize2['height']          = 300;
								$configSize2['new_image']   = ROOT_PATH . '/uploads/product_images/medium';

								$this->image_lib->initialize($configSize2);
								$this->image_lib->resize();
								$this->image_lib->clear();
								/* Third size */
								$configSize3['image_library']   = 'gd2';
								$configSize3['source_image']    = $fileData['full_path'];
								$configSize3['create_thumb']    = FALSE;
								$configSize3['maintain_ratio']  = TRUE;
								$configSize3['width']           = 90;
								$configSize3['height']          = 90;
								$configSize3['new_image']   =  ROOT_PATH . '/uploads/product_images/small';

								$this->image_lib->initialize($configSize3);
								$this->image_lib->resize();
								$this->image_lib->clear();
							}
						}
						if (!empty($uploadData)) {
							// Insert files data into the database
							$insert = $this->base_model->insert_multiple_entry('brij_product_images', $uploadData);
						}
					}
				}
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('pro_success', 'Product has been updated successfully.');
				} else {
					$this->session->set_flashdata('pro_success', 'Product has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/products_list");
				}
			}
			$this->session->set_flashdata('pro_error', 'Product does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/products_list");
			}
		}
	}

	public function product_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$pro_id = trim($this->input->get('pro_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $pro_id);
					$last_inserted_id = $this->base_model->update_entry('brij_products', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $pro_id);
					$last_inserted_id = $this->base_model->update_entry('brij_products', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('pro_success', 'Product Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/products_list");
			}
		}
	}

	public function product_review_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$pro_r_id = trim($this->input->get('pro_r_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'review_status' => 1
					);
					$where_conditions = array("id" => $pro_r_id);
					$last_inserted_id = $this->base_model->update_entry('brij_product_reviews', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'review_status' => 0
					);
					$where_conditions = array("id" => $pro_r_id);
					$last_inserted_id = $this->base_model->update_entry('brij_product_reviews', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('pro_success', 'Product Review Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/product_reviews");
			}
		}
	}

	public function delete_product()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$pro_id = $this->input->post('pro_id');
			$proDetails = $this->base_model->getOneRecord("brij_products", "id", $pro_id, "*");
			$product_id = $proDetails->id;

			$search_criteria1["product_id ="] = $pro_id;
			$productImageDetails = $this->base_model->getAllRows('brij_product_images', 'id DESC', $search_criteria1);
			if (!empty($productImageDetails) && count($productImageDetails) > 0) {
				foreach ($productImageDetails as $productImageDetails) {
					$proImfilename = 'uploads/product_images/' . $productImageDetails->images;
					if (file_exists($proImfilename) && !empty($productImageDetails->images) && isset($productImageDetails->images)) {
						$_image = $productImageDetails->images;
						unlink(realpath('uploads/product_images/' . $_image));
						unlink(realpath('uploads/product_images/large/' . $_image));
						unlink(realpath('uploads/product_images/medium/' . $_image));
						unlink(realpath('uploads/product_images/small/' . $_image));
					}
				}
			}

			$where_conditions_h = array('product_id' => $pro_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_product_images', $where_conditions_h);

			$where_conditions_c = array('product_id' => $pro_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_order_items', $where_conditions_c);

			$where_conditions_e = array('product_id' => $pro_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_product_recently_views', $where_conditions_e);

			$where_conditions_f = array('product_id' => $pro_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_product_reviews', $where_conditions_f);

			$where_conditions_g = array('product_id' => $pro_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_wishlist', $where_conditions_g);

			$where_conditions_b = array('id' => $pro_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_products', $where_conditions_b);

			$this->session->set_flashdata('pro_success', 'Product has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/products_list");
			}
		}
	}

	public function delete_product_review()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$pro_r_id = $this->input->post('pro_r_id');
			$proRDetails = $this->base_model->getOneRecord("brij_product_reviews", "id", $pro_r_id, "*");
			$product_r_id = $proRDetails->id;

			$where_conditions_b = array('id' => $pro_r_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_product_reviews', $where_conditions_b);

			$this->session->set_flashdata('pro_success', 'Product Review has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/product_reviews");
			}
		}
	}
	//Product Coding End

	//Slider Image Coding Start
	public function sliders_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('sliders_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);

		if (!isset($serach_query) && !isset($status) && empty($status) && empty($serach_query)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";

		if (isset($status) && $status != '') {
			$search_criteria['status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['slider_name LIKE'] = '%' . $serach_query . '%';
		}
		$select_column_name = "*";
		$data['sliderList'] = $this->admin_model->getSearch('brij_sliders', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);

		$data['title'] = 'Sliders List';

		$data['searchsliderKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/sliders-list', $data);
		$this->load->view('Admin/include/footer');
	}
	
	
	public function guest_post_repeater()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('repeater_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);

		if (!isset($serach_query) && !isset($status) && empty($status) && empty($serach_query)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";

		if (isset($status) && $status != '') {
			$search_criteria['status ='] = $status;
		}
		$search_criteria['page_id ='] = GUEST_POSTING_PAGE_ID;
		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['title LIKE'] = '%' . $serach_query . '%';
		}
		$select_column_name = "*";
		$data['sliderList'] = $this->admin_model->getSearch('repeaters', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);

		$data['title'] = 'Guest Post Repeater List';

		$data['searchsliderKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/guest-post-repeater-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_slider()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('sliders_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$sliderDetails = $this->base_model->getOneRecord("brij_sliders", "id", $this->input->post('id', TRUE), "slider_image");
				//Check whether user upload slider image
				if (!empty($_FILES['slider_image']['name'])) {
					$config['upload_path'] = 'uploads/slider_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['slider_image']['name'];
					$catfilename = 'uploads/slider_images/' . $sliderDetails->slider_image;
					if (file_exists($catfilename) && !empty($sliderDetails->slider_image) && isset($sliderDetails->slider_image)) {
						$_image = $sliderDetails->slider_image;
						unlink(realpath('uploads/slider_images/' . $_image));
						unlink(realpath('uploads/slider_images/large/' . $_image));
						unlink(realpath('uploads/slider_images/medium/' . $_image));
						unlink(realpath('uploads/slider_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('slider_image')) {
						$uploadData = $this->upload->data();
						$slider_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/slider_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/slider_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/slider_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$slider_image = $sliderDetails->slider_image;
					}
				} else {
					$slider_image = $sliderDetails->slider_image;
				}
				$update_data = array(
					'slider_name' => $this->input->post('slider_name', TRUE),
					'slider_image' =>  $slider_image,
					'slider_content' =>  $this->input->post('slider_content', TRUE),
					'slider_url' =>  $this->input->post('slider_url', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_sliders', $update_data, $where_conditions);
			} else {
				if (!getUserCan('sliders_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload slider image
				if (!empty($_FILES['slider_image']['name'])) {
					$config['upload_path'] = 'uploads/slider_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['slider_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('slider_image')) {
						$uploadData = $this->upload->data();
						$slider_image = $uploadData['file_name'];

						$this->load->library('image_lib');

						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/slider_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/slider_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/slider_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$slider_image = '';
					}
				} else {
					$slider_image = '';
				}
				$insert_data = array(
					'slider_name' => $this->input->post('slider_name', TRUE),
					'slider_image' =>  $slider_image,
					'slider_content' =>  $this->input->post('slider_content', TRUE),
					'slider_url' =>  $this->input->post('slider_url', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_sliders', $insert_data);
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('slider_success', 'Slider has been updated successfully.');
				} else {
					$this->session->set_flashdata('slider_success', 'Slider has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/sliders_list");
				}
			}
			$this->session->set_flashdata('slider_error', 'Slider does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/sliders_list");
			}
		}
	}


	public function add_guest_post_repeater()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('repeater_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$sliderDetails = $this->base_model->getOneRecord("repeaters", "id", $this->input->post('id', TRUE), "image");
				//Check whether user upload slider image
				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = 'uploads/slider_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['image']['name'];
					$catfilename = 'uploads/slider_images/' . $sliderDetails->image;
					if (file_exists($catfilename) && !empty($sliderDetails->image) && isset($sliderDetails->image)) {
						$_image = $sliderDetails->image;
						unlink(realpath('uploads/slider_images/' . $_image));
						unlink(realpath('uploads/slider_images/large/' . $_image));
						unlink(realpath('uploads/slider_images/medium/' . $_image));
						unlink(realpath('uploads/slider_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('image')) {
						$uploadData = $this->upload->data();
						$slider_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/slider_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/slider_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/slider_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$slider_image = $sliderDetails->image;
					}
				} else {
					$slider_image = $sliderDetails->image;
				}
				$update_data = array(
					'title' => $this->input->post('title', TRUE),
					'image' =>  $slider_image,
					'content' =>  $this->input->post('content', TRUE),
					'des' =>  $this->input->post('des', TRUE),
					'display_order' =>  $this->input->post('display_order', TRUE),
					'page_id' =>  GUEST_POSTING_PAGE_ID,
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('repeaters', $update_data, $where_conditions);
			} else {
				if (!getUserCan('repeater_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload slider image
				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = 'uploads/slider_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('image')) {
						$uploadData = $this->upload->data();
						$slider_image = $uploadData['file_name'];

						$this->load->library('image_lib');

						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/slider_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/slider_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/slider_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$slider_image = '';
					}
				} else {
					$slider_image = '';
				}
				$insert_data = array(
					'title' => $this->input->post('title', TRUE),
					'image' =>  $slider_image,
					'content' =>  $this->input->post('content', TRUE),
					'des' =>  $this->input->post('des', TRUE),
					'display_order' =>  $this->input->post('display_order', TRUE),
					'page_id' =>  GUEST_POSTING_PAGE_ID,
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
					
				);
				$last_inserted_id = $this->base_model->insert_entry('repeaters', $insert_data);
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('slider_success', 'Repeater has been updated successfully.');
				} else {
					$this->session->set_flashdata('slider_success', 'Repeater has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/guest_post_repeater");
				}
			}
			$this->session->set_flashdata('slider_error', 'Repeater does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/guest_post_repeater");
			}
		}
	}

	
	public function slider_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('sliders_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$slider_id = trim($this->input->get('slider_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $slider_id);
					$last_inserted_id = $this->base_model->update_entry('brij_sliders', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $slider_id);
					$last_inserted_id = $this->base_model->update_entry('brij_sliders', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('slider_success', 'Slider Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/sliders_list");
			}
		}
	}
	
	
	public function guest_post_repeater_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('repeater_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$slider_id = trim($this->input->get('slider_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $slider_id);
					$last_inserted_id = $this->base_model->update_entry('repeaters', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $slider_id);
					$last_inserted_id = $this->base_model->update_entry('repeaters', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('slider_success', 'Repeater Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/guest_post_repeater");
			}
		}
	}

	public function delete_slider()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('sliders_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$slider_id = $this->input->post('slider_id');
			$sliderDetails = $this->base_model->getOneRecord("brij_sliders", "id", $slider_id, "id,slider_image");

			$sliderfilename = 'uploads/slider_images/' . $sliderDetails->slider_image;
			if (file_exists($sliderfilename) && !empty($sliderDetails->slider_image) && isset($sliderDetails->slider_image)) {
				$_image = $sliderDetails->slider_image;
				unlink(realpath('uploads/slider_images/' . $_image));
				unlink(realpath('uploads/slider_images/large/' . $_image));
				unlink(realpath('uploads/slider_images/medium/' . $_image));
				unlink(realpath('uploads/slider_images/small/' . $_image));
			}

			$where_conditions_d = array('id' => $slider_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_sliders', $where_conditions_d);

			$this->session->set_flashdata('slider_success', 'Slider has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/sliders_list");
			}
		}
	}
	
	public function delete_guest_post_repeater()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('repeater_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$slider_id = $this->input->post('slider_id');
			$sliderDetails = $this->base_model->getOneRecord("repeaters", "id", $slider_id, "id,image");

			$sliderfilename = 'uploads/slider_images/' . $sliderDetails->image;
			if (file_exists($sliderfilename) && !empty($sliderDetails->image) && isset($sliderDetails->image)) {
				$_image = $sliderDetails->image;
				unlink(realpath('uploads/slider_images/' . $_image));
				unlink(realpath('uploads/slider_images/large/' . $_image));
				unlink(realpath('uploads/slider_images/medium/' . $_image));
				unlink(realpath('uploads/slider_images/small/' . $_image));
			}

			$where_conditions_d = array('id' => $slider_id);
			$deleted = $this->base_model->deleteWithWhereConditions('repeaters', $where_conditions_d);

			$this->session->set_flashdata('slider_success', 'Repeater has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/guest_post_repeater");
			}
		}
	}
	
	
	//Slider Image Coding End

	//Banner Image Coding Start
	public function banners_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('sliders_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);

		if (!isset($serach_query) && !isset($status) && empty($status) && empty($serach_query)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";

		if (isset($status) && $status != '') {
			$search_criteria['status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['banner_title LIKE'] = '%' . $serach_query . '%';
		}
		$search_criteria['page_id !='] = 0;
		$select_column_name = "*";
		$data['bannersList'] = $this->admin_model->getSearch('brij_banners', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);

		$data['title'] = 'Banners List';

		$data['searchbannerKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/banners-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_banner()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('sliders_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$bannerDetails = $this->base_model->getOneRecord("brij_banners", "id", $this->input->post('id', TRUE), "id,banner_image");
				//Check whether user upload slider image
				if (!empty($_FILES['banner_image']['name'])) {
					$config['upload_path'] = 'uploads/banner_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['banner_image']['name'];
					$bannerfilename = 'uploads/banner_images/' . $bannerDetails->banner_image;
					if (file_exists($bannerfilename) && !empty($bannerDetails->banner_image) && isset($bannerDetails->banner_image)) {
						$_image = $bannerDetails->banner_image;
						unlink(realpath('uploads/banner_images/' . $_image));
						unlink(realpath('uploads/banner_images/large/' . $_image));
						unlink(realpath('uploads/banner_images/medium/' . $_image));
						unlink(realpath('uploads/banner_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('banner_image')) {
						$uploadData = $this->upload->data();
						$banner_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/banner_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/banner_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/banner_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$banner_image = $bannerDetails->banner_image;
					}
				} else {
					$banner_image = $bannerDetails->banner_image;
				}
				$update_data = array(
					'banner_title' => $this->input->post('banner_title', TRUE),
					'banner_image' =>  $banner_image,
					'banner_description' =>  $this->input->post('banner_description'),
					'banner_sub_description' =>  $this->input->post('banner_sub_description'),
					'meta_tag' =>  $this->input->post('meta_tag', TRUE),
					'meta_description' =>  $this->input->post('meta_description', TRUE),
				
					'banner_url' =>  $this->input->post('banner_url', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_banners', $update_data, $where_conditions);
			} else {
				if (!getUserCan('sliders_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload category image
				if (!empty($_FILES['banner_image']['name'])) {
					$config['upload_path'] = 'uploads/banner_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['banner_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('banner_image')) {
						$uploadData = $this->upload->data();
						$banner_image = $uploadData['file_name'];

						$this->load->library('image_lib');

						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/banner_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/banner_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/banner_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$banner_image = '';
					}
				} else {
					$banner_image = '';
				}
				$insert_data = array(
					'banner_title' => $this->input->post('banner_title', TRUE),
					'banner_image' =>  $banner_image,
					'banner_description' =>  $this->input->post('banner_description'),
					'banner_sub_description' =>  $this->input->post('banner_sub_description'),
					'banner_url' =>  $this->input->post('banner_url', TRUE),
					'meta_tag' =>  $this->input->post('meta_tag', TRUE),
					'meta_description' =>  $this->input->post('meta_description', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_banners', $insert_data);
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('banner_success', 'Banner has been updated successfully.');
				} else {
					$this->session->set_flashdata('banner_success', 'Banner has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/banners_list");
				}
			}
			$this->session->set_flashdata('banner_error', 'Banner does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/banners_list");
			}
		}
	}

	public function banner_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('sliders_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$banner_id = trim($this->input->get('banner_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $banner_id);
					$last_inserted_id = $this->base_model->update_entry('brij_banners', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $banner_id);
					$last_inserted_id = $this->base_model->update_entry('brij_banners', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('banner_success', 'Banner Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/banners_list");
			}
		}
	}

	public function delete_banner()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('sliders_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$banner_id = $this->input->post('banner_id');
			$bannerDetails = $this->base_model->getOneRecord("brij_banners", "id", $banner_id, "id,banner_image");

			$bannerfilename = 'uploads/banner_images/' . $bannerDetails->banner_image;
			if (file_exists($bannerfilename) && !empty($bannerDetails->banner_image) && isset($bannerDetails->banner_image)) {
				$_image = $bannerDetails->banner_image;
				unlink(realpath('uploads/banner_images/' . $_image));
				unlink(realpath('uploads/banner_images/large/' . $_image));
				unlink(realpath('uploads/banner_images/medium/' . $_image));
				unlink(realpath('uploads/banner_images/small/' . $_image));
			}

			$where_conditions_d = array('id' => $banner_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_banners', $where_conditions_d);

			$this->session->set_flashdata('banner_success', 'Banner has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/banners_list");
			}
		}
	}
	//Banner Image Coding End

	//Pages Coding Start
	public function pages_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);

		if (!isset($serach_query) && !isset($status) && empty($status) && empty($serach_query)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";

		if (isset($status) && $status != '') {
			$search_criteria['status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['page_title LIKE'] = '%' . $serach_query . '%';
		}
		$select_column_name = "*";
		$data['pagesList'] = $this->admin_model->getSearch('brij_pages', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);

		$data['title'] = 'Pages List';

		$data['searchpagesKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/pages-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				$pageDetails = $this->base_model->getOneRecord("brij_pages", "id", $this->input->post('id', TRUE), "id,page_image");
				//Check whether user upload slider image
				if (!empty($_FILES['page_image']['name'])) {
					$config['upload_path'] = 'uploads/page_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['page_image']['name'];
					$pagefilename = 'uploads/page_images/' . $pageDetails->page_image;
					if (file_exists($pagefilename) && !empty($pageDetails->page_image) && isset($pageDetails->page_image)) {
						$_image = $pageDetails->page_image;
						unlink(realpath('uploads/page_images/' . $_image));
						unlink(realpath('uploads/page_images/large/' . $_image));
						unlink(realpath('uploads/page_images/medium/' . $_image));
						unlink(realpath('uploads/page_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('page_image')) {
						$uploadData = $this->upload->data();
						$page_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/page_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/page_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/page_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$page_image = $pageDetails->page_image;
					}
				} else {
					$page_image = $pageDetails->page_image;
				}
				$update_data = array(
					'page_title' => $this->input->post('page_title', TRUE),
					'page_slug' => $this->input->post('page_slug', TRUE),
					'meta_tag' => $this->input->post('meta_tag', TRUE),
					'meta_keyword' => $this->input->post('meta_keyword', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'page_image' =>  $page_image,
					'page_long_content' =>  $this->input->post('page_long_content', TRUE),
					'page_type' => $this->input->post('page_type', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_pages', $update_data, $where_conditions);
			} else {
				//Check whether user upload category image
				if (!empty($_FILES['page_image']['name'])) {
					$config['upload_path'] = 'uploads/page_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['page_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('page_image')) {
						$uploadData = $this->upload->data();
						$page_image = $uploadData['file_name'];

						$this->load->library('image_lib');

						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/page_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/page_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/page_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$page_image = '';
					}
				} else {
					$page_image = '';
				}
				$insert_data = array(
					'page_title' => $this->input->post('page_title', TRUE),
					'page_slug' => $this->input->post('page_slug', TRUE),
					'meta_tag' => $this->input->post('meta_tag', TRUE),
					'meta_keyword' => $this->input->post('meta_keyword', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'page_image' =>  $page_image,
					'page_long_content' =>  $this->input->post('page_long_content', TRUE),
					'page_type' => $this->input->post('page_type', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_pages', $insert_data);
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('page_success', 'Page has been updated successfully.');
				} else {
					$this->session->set_flashdata('page_success', 'Page has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/pages_list");
				}
			}
			$this->session->set_flashdata('page_error', 'Page does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/pages_list");
			}
		}
	}

	public function page_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$page_id = trim($this->input->get('page_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $page_id);
					$last_inserted_id = $this->base_model->update_entry('brij_pages', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $page_id);
					$last_inserted_id = $this->base_model->update_entry('brij_pages', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('page_success', 'Page Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/pages_list");
			}
		}
	}

	public function delete_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$page_id = $this->input->post('page_id');
			$pageDetails = $this->base_model->getOneRecord("brij_pages", "id", $page_id, "id,page_image");

			$pagefilename = 'uploads/page_images/' . $pageDetails->page_image;
			if (file_exists($pagefilename) && !empty($pageDetails->page_image) && isset($pageDetails->page_image)) {
				$_image = $pageDetails->page_image;
				unlink(realpath('uploads/page_images/' . $_image));
				unlink(realpath('uploads/page_images/large/' . $_image));
				unlink(realpath('uploads/page_images/medium/' . $_image));
				unlink(realpath('uploads/page_images/small/' . $_image));
			}

			$where_conditions_d = array('id' => $page_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_pages', $where_conditions_d);

			$this->session->set_flashdata('page_success', 'Page has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/pages_list");
			}
		}
	}
	//Pages Coding End

	//Orders Coding Start
	public function orders_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$user_id = trim($this->input->get('user_id', TRUE));
		$order_status = trim($this->input->get('order_status', TRUE));
		$order_date_from = trim($this->input->get('order_date_from', TRUE));
		$order_date_to = trim($this->input->get('order_date_to', TRUE));

		if (!isset($serach_query) && !isset($order_date_from) && !isset($order_date_to) && !isset($user_id) && !isset($order_status) && empty($order_date_from) && empty($order_date_to) && empty($order_status) && empty($user_id) && empty($serach_query)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$order_status = (isset($order_status) && $order_status != '') ? $order_status : "";
		$user_id = (isset($user_id) && $user_id != '') ? $user_id : "";
		$order_date_from = (isset($order_date_from) && $order_date_from != '') ? $order_date_from : "";
		$order_date_to = (isset($order_date_to) && $order_date_to != '') ? $order_date_to : "";

		if (isset($order_date_from) && !empty($order_date_from) && $order_date_from != '') {
			$order_date_from = date("Y-m-d", strtotime($order_date_from));
			$search_criterias['DATE(orders.order_date) >='] = $order_date_from;
		}
		if (isset($order_date_to) && !empty($order_date_to) && $order_date_to != '') {
			$order_date_to = date("Y-m-d", strtotime($order_date_to));
			$search_criterias['DATE(orders.order_date) <='] = $order_date_to;
		}

		if (isset($user_id) && $user_id != '') {
			$search_criteria['orders.user_id ='] = $user_id;
		}

		if (isset($order_status) && $order_status != '') {
			$search_criteria['order_status ='] = $order_status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['order_number LIKE'] = '%' . $serach_query . '%';
		}

		$search_criteria['order_status !='] = 'Waiting';

		$select_column_name = "*";
		$data['ordersList'] = $this->admin_model->getOrders($search_criteria, $order_by = 'id DESC');

		if ($this->input->get('do', TRUE) && $this->input->get('do', TRUE) == 'download-excel') {
			$this->load->library("excel");
			$object = new PHPExcel();
			$object->setActiveSheetIndex(0);

			$table_columns = array('Order Id', 'Order Date', 'No Of Items', 'Total Amount', 'Order Status', 'Order Remark', 'Payment Mode', 'Payment Status', 'Payment Date', 'Customer', 'Shipping Name', 'Shipping Mobile No', 'Shipping Address', 'Shipping Country', 'Shipping State', 'Shipping City', 'Shipping Post Code');

			$column = 0;

			foreach ($table_columns as $field) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}
			$object->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);

			for ($i = 'A'; $i != $object->getActiveSheet()->getHighestColumn(); $i++) {
				$object->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}
			//echo "<pre>";print_r($data['ordersList']);
			//die;

			$excel_row = 2;

			foreach ($data['ordersList'] as $orderListAll) {
				/* $userName = $this->base_model->getOneRecord("brij_users","id", $orderListAll->user_id, "*");
				$userNames='N/A';
				if(!empty($userName) && count($userName)){					
					$userNames=$userName->name;
				} */
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $orderListAll->order_number);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, dateFormat("d-m-Y H:i", $orderListAll->order_date));
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $orderListAll->number_of_items);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $orderListAll->order_total);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $orderListAll->order_status);
				$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $orderListAll->order_remark);
				$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $orderListAll->payment_method);
				$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $orderListAll->payment_status);
				$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, dateFormat("d-m-Y H:i", $orderListAll->payment_date));
				$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $orderListAll->username);
				$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $orderListAll->shipping_name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $orderListAll->shipping_mobile_no);
				$object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $orderListAll->shipping_address);
				$object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $orderListAll->shipping_country);
				$object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $orderListAll->shipping_state);
				$object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $orderListAll->shipping_city);
				$object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $orderListAll->shipping_post_code);
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Order-List-Data-' . date('d-m-Y') . '.xls"');
			$object_writer->save('php://output');
		}

		$conditions_u['name !='] = '';
		$data['usersData'] = $this->base_model->getAllRows('brij_users', 'name ASC', $conditions_u);

		$data['title'] = 'Orders List';

		$data['orderStatus'] = $this->config->item('orderStatusStatus');
		$data['orderStatusClass'] = $this->config->item('orderStatusStatusClass');
		$data['searchordernumberKeyword'] = $serach_query;
		$data['statusKeyword'] = $order_status;
		$data['userIDKeyword'] = $user_id;
		$data['searchorderFromKeyword'] = $order_date_from;
		$data['searchorderToKeyword'] = $order_date_to;

		//echo "<pre>";print_r($data);
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/orders-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function order_view()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];

		if ($this->input->method() === 'post') {
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$order_status = $this->input->post('order_status', TRUE);
			$order_id = $this->input->post('order_id', TRUE);
			$order_remark = $this->input->post('order_remark', TRUE);
			if (isset($order_id) && !empty($order_id)) {
				$update_data = array(
					'order_remark' => $order_remark,
					'order_status' => $order_status
				);

				if ($this->input->post('payment_status', TRUE) && !empty($this->input->post('payment_status', TRUE))) {
					$update_data['payment_status'] = $this->input->post('payment_status', TRUE);
				}

				$where_conditions = array("id" => $order_id);
				$update = $this->base_model->update_entry('brij_orders', $update_data, $where_conditions);
				$this->session->set_flashdata('order_success', 'Order Status has been changed successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/order_view?do=order_view&order_id=" . $order_id);
				}
			}
		}

		$search_criteria = array();

		$do = trim($this->input->get('do', TRUE));
		$order_id = trim($this->input->get('order_id', TRUE));

		$data['orderData'] = $this->base_model->getOneRecord("brij_orders", "id", $order_id, "*");
		$search_criteria['order_id ='] = $order_id;
		$data['orderItemsData'] = $this->admin_model->getOrderItems($search_criteria);
		$search_criteria1['orders.id ='] = $order_id;
		$data['orderUsersData'] = $this->admin_model->getOrderUsersDetails($search_criteria1);

		$data['title'] = 'Order View';

		$data['orderStatus'] = $this->config->item('orderStatusStatus');
		$data['orderStatusClass'] = $this->config->item('orderStatusStatusClass');

		$data['order_id'] = $order_id;

		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/order-view', $data);
		$this->load->view('Admin/include/footer');
	}

	public function delete_order()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$order_id = $this->input->post('order_id');
			$orderDetails = $this->base_model->getOneRecord("brij_orders", "id", $order_id, "*");
			$orderId = $orderDetails->id;

			$where_conditions_i = array('order_id' => $order_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_order_items', $where_conditions_i);

			$where_conditions_d = array('id' => $order_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_orders', $where_conditions_d);

			$this->session->set_flashdata('order_success', 'Location has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/orders_list");
			}
		}
	}

	public function delete_product_cart_item()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$cart_id = $this->input->post('cart_id');
			$orderItemDetails = $this->base_model->getOneRecord("brij_order_items", "id", $cart_id, "*");
			$cartItemId = $orderItemDetails->id;

			$where_conditions_i = array('id' => $cartItemId);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_order_items', $where_conditions_i);

			$this->session->set_flashdata('cart_success', 'Cart items has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/dashboard");
			}
		}
	}
	//Order Coding Start

	//Users Coding Start
	public function users_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = trim($this->input->get('status', TRUE));
		$date_from = trim($this->input->get('date_from', TRUE));
		$date_to = trim($this->input->get('date_to', TRUE));

		if (!isset($serach_query) && !isset($date_from) && !isset($date_to) && !isset($status) && empty($date_from) && empty($date_to) && empty($status) && empty($serach_query)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$date_from = (isset($date_from) && $date_from != '') ? $date_from : "";
		$date_to = (isset($date_to) && $date_to != '') ? $date_to : "";

		if (isset($date_from) && !empty($date_from) && $date_from != '') {
			$date_from = date("Y-m-d", strtotime($date_from));
			$search_criterias['DATE(date_added) >='] = $date_from;
		}
		if (isset($date_to) && !empty($date_to) && $date_to != '') {
			$date_to = date("Y-m-d", strtotime($date_to));
			$search_criterias['DATE(date_added) <='] = $date_to;
		}

		if (isset($status) && $status != '') {
			$search_criteria['status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['name LIKE'] = '%' . $serach_query . '%';
		}

		$select_column_name = "*";
		$data['usersList'] = $this->admin_model->getSearch('brij_users', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);

		if ($this->input->get('do', TRUE) && $this->input->get('do', TRUE) == 'download-excel') {
			$this->load->library("excel");
			$object = new PHPExcel();
			$object->setActiveSheetIndex(0);

			$table_columns = array('Name', 'Email ID', 'Address', 'Mobile Number', 'Country Name', 'City Name', 'State Name', 'Pin Code', 'Status', 'Newletter Subscribe', 'Registered Date');

			$column = 0;

			foreach ($table_columns as $field) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}
			$object->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);

			for ($i = 'A'; $i != $object->getActiveSheet()->getHighestColumn(); $i++) {
				$object->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}
			//echo "<pre>";print_r($data['ordersList']);
			//die;

			$excel_row = 2;

			foreach ($data['usersList'] as $userListAll) {
				$status = (isset($userListAll->status) && $userListAll->status == 1) ? 'Active' : 'Inactive';
				$is_subscribe_newletters = (isset($userListAll->is_subscribe_newletters) && $userListAll->is_subscribe_newletters == 1) ? 'Yes' : 'No';
				$cities = $this->base_model->getOneRecord("brij_cities", "id", $userListAll->city_id, "city_name");
				$state = $this->base_model->getOneRecord("brij_states", "id", $userListAll->state_id, "state_name");
				$country = $this->base_model->getOneRecord("brij_countries", "id", $userListAll->country_id, "country_name");
				$cityName = (!empty($cities) && count($cities) > 0) ? $cities->city_name : 'N/A';
				$stateName = (!empty($state) && count($state) > 0) ? $state->state_name : 'N/A';
				$countryName = (!empty($country) && count($country) > 0) ? $country->country_name : 'N/A';
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $userListAll->name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $userListAll->email_id);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $userListAll->address);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $userListAll->phone_no);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $countryName);
				$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $cityName);
				$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $stateName);
				$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $userListAll->pin_code);
				$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $status);
				$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $is_subscribe_newletters);
				$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, dateFormat("d-m-Y H:i", $userListAll->date_added));
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="User-List-Data-' . date('d-m-Y') . '.xls"');
			$object_writer->save('php://output');
		}

		$data['title'] = 'Users List';

		$conditions_u['state_name !='] = '';
		$conditions_u['status ='] = 1;
		$conditions_u['country_id ='] = 101;
		$data['stateData'] = $this->base_model->getAllRows('brij_states', 'state_name ASC', $conditions_u);

		$data['searchuserKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['searchuserFromKeyword'] = $date_from;
		$data['searchuserToKeyword'] = $date_to;

		//echo "<pre>";print_r($data);
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/users-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_user()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			$user_api_key = $this->_generateApiKey();
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				$uDetails = $this->base_model->getOneRecord("brij_users", "id", $this->input->post('id', TRUE), "*");
				$update_data = array(
					'name' => $this->input->post('name', TRUE),
					'email_id' => $this->input->post('email_id', TRUE),
					'address' => $this->input->post('address', TRUE),
					'city_id' => $this->input->post('city_id', TRUE),
					'state_id' => $this->input->post('state_id', TRUE),
					'country_id' =>  101,
					'phone_no' =>  $this->input->post('phone_no', TRUE),
					'pin_code' => $this->input->post('pin_code', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				if ($this->input->post('password', TRUE) && !empty($this->input->post('password', TRUE))) {
					$update_data['password'] = md5($this->input->post('password', TRUE));
				}
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
				$this->_generateCustomerDentificationNumber($this->input->post('id', TRUE), $this->input->post('state_id', TRUE));
			} else {
				$insert_data = array(
					'name' => $this->input->post('name', TRUE),
					'email_id' => $this->input->post('email_id', TRUE),
					'address' => $this->input->post('address', TRUE),
					'city_id' => $this->input->post('city_id', TRUE),
					'state_id' => $this->input->post('state_id', TRUE),
					'country_id' =>  101,
					'phone_no' =>  $this->input->post('phone_no', TRUE),
					'pin_code' => $this->input->post('pin_code', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'password' => md5($this->input->post('password', TRUE)),
					'api_key' => $user_api_key,
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_users', $insert_data);
				$this->_generateCustomerDentificationNumber($last_inserted_id, $this->input->post('state_id', TRUE));
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('user_success', 'User has been updated successfully.');
				} else {
					$this->session->set_flashdata('user_success', 'User has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/users_list");
				}
			}
			$this->session->set_flashdata('user_error', 'User does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/users_list");
			}
		}
	}

	public function send_sms()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");

			$this->session->set_flashdata('user_error', 'User SMS feature does not working. Please provide SMS API!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/users_list");
			}
		}
	}

	public function send_email()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			$this->load->library('email');
			$Email_From_Address = getSiteSettingValue(37);
			$Emails_From_Name = getSiteSettingValue(38);

			$config = array(
				'mailtype' => 'html', // text
				'charset' => 'iso-8859-1',
				'newline' => '\r\n',
				'wordwrap' => TRUE
			);
			$this->email->initialize($config);
			$this->email->from($Email_From_Address, $Emails_From_Name);
			$this->email->to($this->input->post('to', TRUE));
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');
			$this->email->subject($this->input->post('subject', TRUE));
			$this->email->message($this->input->post('message', TRUE));
			if ($this->email->send()) {
				//Success email Sent
				//echo $this->email->print_debugger();
				//die;
				$this->session->set_flashdata('user_success', 'Mail has been Sent successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/users_list");
				}
			}
		}
	}

	public function user_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$user_id = trim($this->input->get('user_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $user_id);
					$last_inserted_id = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $user_id);
					$last_inserted_id = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('user_success', 'User Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/users_list");
			}
		}
	}

	public function delete_user()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$user_id = $this->input->post('user_id');
			$uDetails = $this->base_model->getOneRecord("brij_users", "id", $user_id, "*");
			$userId = $uDetails->id;

			$where_conditions_i = array('user_id' => $user_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_order_items', $where_conditions_i);

			$where_conditions_d = array('user_id' => $user_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_orders', $where_conditions_d);


			$where_conditions_r = array('user_id' => $user_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_product_reviews', $where_conditions_r);


			$where_conditions_w = array('user_id' => $user_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_wishlist', $where_conditions_w);


			$where_conditions_a = array('user_id' => $user_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_user_shipping_addresses', $where_conditions_a);


			$where_conditions_u = array('id' => $user_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_users', $where_conditions_u);

			$this->session->set_flashdata('user_success', 'User has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/users_list");
			}
		}
	}
	//Users Coding End
	//notification list
	public function add_notification()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		if ($this->input->method() === 'post') {
			foreach ($this->input->post() as $key => $value) {
				if ($value == '') {
					$this->session->set_flashdata('notification_error', 'Notification doess not saved. Please try again!');
					if ($this->agent->referrer()) {
						//redirect to some function
						redirect($this->agent->referrer());
					} else {
						redirect("admin/add_notification?do=add");
					}
				}
			}
			$date_at = date("Y-m-d H:i:s");
			$user_id = $this->input->post('user_id', TRUE);
			$message = $this->input->post('message', TRUE);
			$title = $this->input->post('title', TRUE);
			if (count($user_id) > 0) {
				$insert_data = array(
					'title' => $title,
					'content' =>  $message,
					'sent_date' =>  $date_at,
				);
				$last_inserted_id = $this->base_model->insert_entry('notifications', $insert_data);
				if ($last_inserted_id) {
					$insert_data_user = array();
					foreach ($user_id as $key => $value) {
						//$where_conditions_n=array('user_id'=>$value,'status'=>1,'new_flag'=>'Old');
						//$query1=$this->base_model->deleteWithWhereConditions('notifications',$where_conditions_n);
						$insert_data_user[] = array("notification_id" => $last_inserted_id, "user_id" => $value, 'is_sent' => 1);
					}
					$res = $this->base_model->insert_multiple_entry('notification_users', $insert_data_user);
					if (count($res) > 0) {
						$this->session->set_flashdata('notification_success', 'Notification has been saved successfully.');
						if ($this->agent->referrer()) {
							//redirect to some function
							redirect($this->agent->referrer());
						} else {
							redirect("admin/add_notification?do=add");
						}
					}
				}
				$this->session->set_flashdata('notification_error', 'Notification doess not saved. Please try again!');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/add_notification?do=add");
				}
			} else {
				$this->session->set_flashdata('notification_error', 'Please Select user. Please try again!');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/add_notification?do=add");
				}
			}
		}
		$from = $this->input->get('from_date', TRUE);
		$to = $this->input->get('to_date', TRUE);
		$status = $this->input->get('status', TRUE);
		$user_id = $this->input->get('user_id', TRUE);
		$user_type = $this->input->get('user_type', TRUE);

		if (!isset($from) && !isset($to) && !isset($status) && !isset($user_id) && empty($from) && empty($to) && empty($status) && empty($user_id)) {
			//$search_criteria['user_shops.shop_schedule_date =']= date("Y-m-d");
		}
		$from = (isset($from) && $from != '') ? $from : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$user_id = (isset($user_id) && $user_id != '') ? $user_id : "";
		$to = (isset($to) && $to != '') ? $to : "";
		$user_type = (isset($user_type) && $user_type != '') ? $user_type : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(created_at) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(created_at) <='] = $to;
		}
		if (isset($status) && $status != '') {
			$search_criteria['notification_users.is_read ='] = $status;
		}

		if (isset($user_id) && $user_id != '') {
			$search_criteria['notification_users.user_id ='] = $user_id;
		}
		$data['userList'] = $this->admin_model->getNotification($search_criteria);
		$conditions_des['is_active ='] = 1;
		$data['userTypeData'] = $this->base_model->getAllRows('user_types', 'title ASC', $conditions_des);
		$conditions_des1['is_active ='] = 1;
		$conditions_des1['user_type ='] = 1;
		$data['userData2'] = $this->base_model->getAllRows('users', 'full_name ASC', $conditions_des1);
		$search_query = '';
		if ($this->input->get('do', TRUE) && $this->input->get('do', TRUE) === 'add' && $this->input->get('search_query', TRUE)) {
			$conditions_des1['full_name Like'] = '%' . trim($this->input->get('search_query', TRUE)) . '%';
			$search_query = trim($this->input->get('search_query', TRUE));
		}
		$data['userData'] = $this->base_model->getAllRows('users', 'full_name ASC', $conditions_des1);
		$data['title'] = 'Notifications';
		$data['fromKeyword'] = $from;
		$data['toKeyword'] = $to;
		$data['statusKeyword'] = $status;
		$data['userTypeKeyword'] = $user_type;
		$data['userIDKeyword'] = $user_id;
		$data['search_query'] = $search_query;
		$this->load->view('include/header', $data);
		$this->load->view('include/left-menu', $data);
		$this->load->view('notification_list', $data);
		$this->load->view('include/footer');
	}
	//Admin User Coding Start
	public function admin_users_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$from = $this->input->get('date_from', TRUE);
		$to = $this->input->get('date_to', TRUE);
		$status = $this->input->get('status', TRUE);
		$serach_query = $this->input->get('serach-query', TRUE);

		if (!isset($from) && !isset($to) && !isset($status) && !isset($serach_query) && empty($from) && empty($to) && empty($status) && empty($serach_query)) {
			//$search_criteria['user_shops.shop_schedule_date =']= date("Y-m-d");
		}
		$from = (isset($from) && $from != '') ? $from : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$to = (isset($to) && $to != '') ? $to : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(date_added) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(date_added) <='] = $to;
		}
		if (isset($status) && $status != '') {
			$search_criteria['user_active ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['screen_name LIKE'] = '%' . $serach_query . '%';
		}

		$select_column_name = "user_id,screen_name,user_mail,user_phone_no,user_role,user_active,date_added";
		$search_criteria['user_role !='] = 1;
		$data['adminList'] = $this->admin_model->getSearch('brij_admin', $order_by = 'user_id DESC', $search_criteria, '', '', $select_column_name);


		if ($this->input->get('do', TRUE) && $this->input->get('do', TRUE) == 'download-excel') {
			$this->load->library("excel");
			$object = new PHPExcel();
			$object->setActiveSheetIndex(0);

			$table_columns = array('Name', 'Email ID', 'Mobile Number', 'Status', 'Created Date');

			$column = 0;

			foreach ($table_columns as $field) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}
			$object->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

			for ($i = 'A'; $i != $object->getActiveSheet()->getHighestColumn(); $i++) {
				$object->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}
			//echo "<pre>";print_r($data['adminList']);
			//die;

			$excel_row = 2;

			foreach ($data['adminList'] as $userListAll) {
				$status = (isset($userListAll->user_active) && $userListAll->user_active == 1) ? 'Active' : 'Inactive';
				/* $cities = $this->base_model->getOneRecord("brij_cities","id", $userListAll->city_id, "city_name");
				$state = $this->base_model->getOneRecord("brij_states","id", $userListAll->state_id, "state_name");
				$country = $this->base_model->getOneRecord("brij_countries","id", $userListAll->country_id, "country_name");
				$cityName= (!empty($cities) && count($cities) > 0)?$cities->city_name:'N/A';
				$stateName= (!empty($state) && count($state) > 0)?$state->state_name:'N/A';
				$countryName= (!empty($country) && count($country) > 0)?$country->country_name:'N/A'; */
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $userListAll->screen_name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $userListAll->user_mail);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $userListAll->user_phone_no);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $status);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, dateFormat("d-m-Y H:i", $userListAll->date_added));
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Admin-User-List-Data-' . date('d-m-Y') . '.xls"');
			$object_writer->save('php://output');
		}

		$conditions_des['status ='] = 1;
		$data['arrPermissionsData'] = $this->base_model->getAllRows('brij_permissions', 'permission_name ASC', $conditions_des);

		$data['title'] = 'Admin Users List';
		$data['searchuserFromKeyword'] = $from;
		$data['searchuserToKeyword'] = $to;
		$data['statusKeyword'] = $status;
		$data['searchuserKeyword'] = $serach_query;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/admin-users-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_admin_user()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'post') {
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d h:i:s");
			$user_api_key = $this->_generateApiKey();
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				$update_data = array(
					'screen_name' =>  $this->input->post('screen_name', TRUE),
					'user_mail' =>  $this->input->post('user_mail', TRUE),
					'user_phone_no' =>  $this->input->post('user_phone_no', TRUE),
					'user_active' =>  $this->input->post('user_active', TRUE),
					'modified_date' =>  $date,
				);
				if ($this->input->post('user_pass', TRUE) && !empty($this->input->post('user_pass', TRUE))) {
					$update_data['user_pass'] = md5($this->input->post('user_pass', TRUE));
				}
				$where_conditions = array("user_id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_admin', $update_data, $where_conditions);
				$user_id = $this->input->post('id', TRUE);
			} else {
				$insert_data = array(
					'screen_name' =>  $this->input->post('screen_name', TRUE),
					'user_mail' =>  $this->input->post('user_mail', TRUE),
					'user_phone_no' =>  $this->input->post('user_phone_no', TRUE),
					'user_pass' =>  md5($this->input->post('user_pass', TRUE)),
					'user_role' => 2,
					'api_key' =>  $user_api_key,
					'user_active' =>  $this->input->post('user_active', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_admin', $insert_data);
				$user_id = $last_inserted_id;
			}
			if ($last_inserted_id) {
				//echo "<pre>";print_r($_POST);
				//DIE;
				$conditions_des1['status ='] = 1;
				$arrPermissionsData = $this->base_model->getAllRows('brij_permissions', 'permission_name ASC', $conditions_des1);
				if (isset($user_id) && !empty($user_id)) {
					$where_conditions_i = array('user_id' => $user_id);
					$deleted = $this->base_model->deleteWithWhereConditions('brij_admin_permissions', $where_conditions_i);
				}
				$isAlready = 0;
				$permission_id = $this->input->post('permission_id', TRUE);
				foreach ($permission_id as $permission_id => $value) {
					$access_read = (!empty($this->input->post('read_' . $value))) ? $this->input->post('read_' . $value) : 'No';
					$access_write = (!empty($this->input->post('write_' . $value))) ? $this->input->post('write_' . $value) : 'No';
					$access_create = (!empty($this->input->post('create_' . $value))) ? $this->input->post('create_' . $value) : 'No';
					$access_delete = (!empty($this->input->post('delete_' . $value))) ? $this->input->post('delete_' . $value) : 'No';
					$access_import = (!empty($this->input->post('import_' . $value))) ? $this->input->post('import_' . $value) : 'No';
					$access_export = (!empty($this->input->post('export_' . $value))) ? $this->input->post('export_' . $value) : 'No';
					$per_name = $this->base_model->getOneRecord("brij_permissions", "id", $value, "*");
					$module_name = $per_name->permission_shortname;
					$insert_admin_permissions_data[] = array("user_id" => $user_id, 'role_id' => 2, 'permission_id' => $value, 'module_name' => $module_name, 'module_access' => '', "access_read" => $access_read, 'access_write' => $access_write, 'access_create' => $access_create, 'access_delete' => $access_delete, 'access_import' => $access_import, 'access_export' => $access_export);
				}
				$isAlready++;
				//die;
				if ($isAlready > 0) {
					$last_inserted_id = $this->base_model->insert_multiple_entry('brij_admin_permissions', $insert_admin_permissions_data);
				}

				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('user_admin_success', 'Admin user has been updated successfully.');
				} else {
					$this->session->set_flashdata('user_admin_success', 'Admin user has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/admin_users_list");
				}
			}
			$this->session->set_flashdata('user_error', 'Admin user does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/admin_users_list");
			}
		}

		$conditions_des['is_active ='] = 1;
		$data['userTypeData'] = $this->base_model->getAllRows('brij_user_types', 'title ASC', $conditions_des);
		$conditions_state['country_id ='] = 101;
		$conditions_state['status ='] = 1;
		$data['stateData'] = $this->base_model->getAllRows('brij_states', 'state_name ASC', $conditions_state);
		$conditions_city['status ='] = 1;
		$conditions_city['city_name !='] = '';
		$data['cityData'] = $this->base_model->getAllRows('cities', 'city_name ASC', $conditions_city);
	}

	public function admin_user_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$user_id = trim($this->input->get('user_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'user_active' => 1
					);
					$where_conditions = array("user_id" => $user_id);
					$last_inserted_id = $this->base_model->update_entry('brij_admin', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'user_active' => 0
					);
					$where_conditions = array("user_id" => $user_id);
					$last_inserted_id = $this->base_model->update_entry('brij_admin', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('user_admin_success', 'Admin User Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/admin_users_list");
			}
		}
	}

	public function delete_user_admin()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			$user_id = $this->input->post('user_id');
			$uDetails = $this->base_model->getOneRecord("brij_admin", "user_id", $user_id, "*");
			$userId = $uDetails->user_id;

			/* $where_conditions_i=array('user_id'=>$user_id);
			$deleted=$this->base_model->deleteWithWhereConditions('brij_order_items',$where_conditions_i);
			
			$where_conditions_d=array('user_id'=>$user_id);
			$deleted=$this->base_model->deleteWithWhereConditions('brij_orders',$where_conditions_d);
			
			
			$where_conditions_r=array('user_id'=>$user_id);
			$deleted=$this->base_model->deleteWithWhereConditions('brij_product_reviews',$where_conditions_r);
			
			
			$where_conditions_w=array('user_id'=>$user_id);
			$deleted=$this->base_model->deleteWithWhereConditions('brij_wishlist',$where_conditions_w); */


			$where_conditions_a = array('user_id' => $user_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_admin_permissions', $where_conditions_a);


			$where_conditions_u = array('user_id' => $user_id);
			$deleted = $this->base_model->deleteWithWhereConditions('brij_admin', $where_conditions_u);

			$this->session->set_flashdata('user_admin_success', 'Admin User has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/admin_users_list");
			}
		}
	}
	//Admin User Coding End

	//News Letter Coding Start
	public function news_letter_subscribers()
	{
		if ($this->admin_model->check_logged() === FALSE) {
			redirect(base_url() . 'admin');
		}

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = trim($this->input->get('status', TRUE));
		$date_from = trim($this->input->get('date_from', TRUE));
		$date_to = trim($this->input->get('date_to', TRUE));

		if (!isset($serach_query) && !isset($date_from) && !isset($date_to) && !isset($status) && empty($date_from) && empty($date_to) && empty($status) && empty($serach_query)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$date_from = (isset($date_from) && $date_from != '') ? $date_from : "";
		$date_to = (isset($date_to) && $date_to != '') ? $date_to : "";

		if (isset($date_from) && !empty($date_from) && $date_from != '') {
			$date_from = date("Y-m-d", strtotime($date_from));
			$search_criterias['DATE(date_added) >='] = $date_from;
		}
		if (isset($date_to) && !empty($date_to) && $date_to != '') {
			$date_to = date("Y-m-d", strtotime($date_to));
			$search_criterias['DATE(date_added) <='] = $date_to;
		}

		if (isset($status) && $status != '') {
			$search_criteria['is_subscribe_newletters ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['name LIKE'] = '%' . $serach_query . '%';
		}

		$search_criteria['status ='] = 1;

		$select_column_name = "*";
		$data['subscriberListData'] = $this->admin_model->getSearch('brij_users', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);

		if ($this->input->get('do', TRUE) && $this->input->get('do', TRUE) == 'download-excel') {
			$this->load->library("excel");
			$object = new PHPExcel();
			$object->setActiveSheetIndex(0);

			$table_columns = array('Name', 'Email ID', 'Address', 'Mobile Number', 'Country Name', 'City Name', 'State Name', 'Pin Code', 'Status', 'Newletter Subscribe', 'Registered Date');

			$column = 0;

			foreach ($table_columns as $field) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}
			$object->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);

			for ($i = 'A'; $i != $object->getActiveSheet()->getHighestColumn(); $i++) {
				$object->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}
			//echo "<pre>";print_r($data['ordersList']);
			//die;

			$excel_row = 2;

			foreach ($data['subscriberListData'] as $userListAll) {
				$status = (isset($userListAll->status) && $userListAll->status == 1) ? 'Active' : 'Inactive';
				$is_subscribe_newletters = (isset($userListAll->is_subscribe_newletters) && $userListAll->is_subscribe_newletters == 1) ? 'Yes' : 'No';
				$cities = $this->base_model->getOneRecord("brij_cities", "id", $userListAll->city_id, "city_name");
				$state = $this->base_model->getOneRecord("brij_states", "id", $userListAll->state_id, "state_name");
				$country = $this->base_model->getOneRecord("brij_countries", "id", $userListAll->country_id, "country_name");
				$cityName = (!empty($cities) && count($cities) > 0) ? $cities->city_name : 'N/A';
				$stateName = (!empty($state) && count($state) > 0) ? $state->state_name : 'N/A';
				$countryName = (!empty($country) && count($country) > 0) ? $country->country_name : 'N/A';
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $userListAll->name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $userListAll->email_id);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $userListAll->address);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $userListAll->phone_no);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $countryName);
				$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $cityName);
				$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $stateName);
				$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $userListAll->pin_code);
				$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $status);
				$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $is_subscribe_newletters);
				$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, dateFormat("d-m-Y H:i", $userListAll->date_added));
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Subscribers-List-Data-' . date('d-m-Y') . '.xls"');
			$object_writer->save('php://output');
		}

		$conditions_des['status ='] = 1;
		$data['templateListData'] = $this->base_model->getAllRows('brij_newsletter_templates', 'id DESC', $conditions_des);

		$data['searchuserKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['searchuserFromKeyword'] = $date_from;
		$data['searchuserToKeyword'] = $date_to;

		$data['title'] = 'Newsletter Subscribers';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/news-letter-subscribers', $data);
		$this->load->view('Admin/include/footer');
	}

	public function send_news_letter()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			$this->load->library('email');
			$Email_From_Address = getSiteSettingValue(37);
			$Emails_From_Name = getSiteSettingValue(38);

			$config = array(
				'mailtype' => 'html', // text
				'charset' => 'iso-8859-1',
				'newline' => '\r\n',
				'wordwrap' => TRUE
			);

			$title = $this->input->post('subject', TRUE);
			$message = $this->input->post('message', TRUE);
			$status = $this->input->post('status', TRUE);
			$conditions_des = array();
			if (isset($status) && $status == 'Subscribers') {
				$conditions_des['is_subscribe_newletters ='] = 1;
			}
			if (isset($status) && $status == 'Unsubscribers') {
				$conditions_des['is_subscribe_newletters ='] = 0;
			}

			$conditions_des['status ='] = 1;
			$userData = $this->base_model->getAllRows('brij_users', 'id DESC', $conditions_des);

			$sent = 0;
			foreach ($userData as $key => $value) {
				$this->email->clear();
				$this->email->initialize($config);
				$this->email->to($value->email_id);
				$this->email->from($Email_From_Address, $Emails_From_Name);
				$this->email->subject($title);
				$newTempDetails = $this->base_model->getOneRecord("brij_newsletter_templates", "id", $this->input->post('template_id', TRUE), "*");
				$bannerfilename = 'uploads/newsletter_template_images/' . $newTempDetails->template_image;
				$banner_file = base_url() . '/uploads/no-image100x100.jpg';
				if (file_exists($bannerfilename) && !empty($newTempDetails->template_image)) {
					$banner_file = base_url() . '/uploads/newsletter_template_images/large/' . $newTempDetails->template_image;
				}
				$banner_file = $banner_file;
				$is_subscribe_newletters = (isset($value->is_subscribe_newletters) && $value->is_subscribe_newletters == 1) ? 'Unsubscribe' : 'Subscribe';

				$LOGO_URL = base_url() . '/';
				if ($value->is_subscribe_newletters == 0) {
					$SUBRLINK = base_url() . '/user/subscriber_status?do=subscribe&user_id=' . $value->id;
				} else {
					$SUBRLINK = base_url() . '/user/subscriber_status?do=unsubscribe&user_id=' . $value->id;
				}
				$template = file_get_contents("./assets/mail-templates/newsletter.html");
				//echo $template;
				//die;
				$template = str_replace('{{URL}}', $LOGO_URL, $template);
				$template = str_replace('{{CONTENT-IMAGE}}', $banner_file, $template);
				$template = str_replace('{{CONTENT}}', $message, $template);
				$template = str_replace('{{URL-SUB}}', $SUBRLINK, $template);
				$template = str_replace('{{TEXT-UNSUB}}', $is_subscribe_newletters, $template);
				$template = str_replace('{{SUBJECT}}', $title, $template);
				$this->email->message($template);
				if ($this->email->send()) {
					$sent++;
				}
				//Success email Sent
				//echo $this->email->print_debugger();
				//die;	
			}

			if ($sent > 0) {
				//Success email Sent
				//echo $this->email->print_debugger();
				//die;
				$this->session->set_flashdata('s_user_success', 'Newletter has been Sent successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/news_letter_subscribers");
				}
			}
		}
	}

	public function subscriber_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$user_id = trim($this->input->get('user_id', TRUE));
			switch ($do) {
				case 'subscribe':
					$update_data = array(
						'is_subscribe_newletters' => 1
					);
					$where_conditions = array("id" => $user_id);
					$last_inserted_id = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
					break;
				case 'unsubscribe':
					$update_data = array(
						'is_subscribe_newletters' => 0
					);
					$where_conditions = array("id" => $user_id);
					$last_inserted_id = $this->base_model->update_entry('brij_users', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('s_user_success', 'Newletter Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/news_letter_subscribers");
			}
		}
	}


	public function add_newsletter_template()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				$newTempDetails = $this->base_model->getOneRecord("brij_newsletter_templates", "id", $this->input->post('id', TRUE), "template_image");
				//Check whether user upload category image
				if (!empty($_FILES['template_image']['name'])) {
					$config['upload_path'] = 'uploads/newsletter_template_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['template_image']['name'];
					$catfilename = 'uploads/newsletter_template_images/' . $newTempDetails->template_image;
					if (file_exists($catfilename) && !empty($newTempDetails->template_image) && isset($newTempDetails->template_image)) {
						$_image = $newTempDetails->image;
						unlink(realpath('uploads/newsletter_template_images/' . $_image));
						unlink(realpath('uploads/newsletter_template_images/large/' . $_image));
						unlink(realpath('uploads/newsletter_template_images/medium/' . $_image));
						unlink(realpath('uploads/newsletter_template_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('template_image')) {
						$uploadData = $this->upload->data();
						$template_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/newsletter_template_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/newsletter_template_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/newsletter_template_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$template_image = $newTempDetails->template_image;
					}
				} else {
					$template_image = $newTempDetails->template_image;
				}
				$update_data = array(
					'template_image' =>  $template_image,
					'template_content' =>  $this->input->post('template_content', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('brij_newsletter_templates', $update_data, $where_conditions);
			} else {
				//Check whether user upload category image
				if (!empty($_FILES['template_image']['name'])) {
					$config['upload_path'] = 'uploads/newsletter_template_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['file_name'] = $_FILES['template_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('template_image')) {
						$uploadData = $this->upload->data();
						$template_image = $uploadData['file_name'];

						$this->load->library('image_lib');

						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/newsletter_template_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/newsletter_template_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/newsletter_template_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$template_image = '';
					}
				} else {
					$template_image = '';
				}
				$insert_data = array(
					'template_image' =>  $template_image,
					'template_content' =>  $this->input->post('template_content', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('brij_newsletter_templates', $insert_data);
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('s_user_success', 'Template has been updated successfully.');
				} else {
					$this->session->set_flashdata('s_user_success', 'Template has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/categories_list");
				}
			}
			$this->session->set_flashdata('s_user_error', 'Template does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/news_letter_subscribers");
			}
		}
	}
	//News Letter Coding End

	//Email Management Coding Start
	public function emails_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			$Email_From_Address = getSiteSettingValue(37);
			$Emails_From_Name = getSiteSettingValue(38);

			$this->load->library('email');
			$config = array(
				'mailtype' => 'html', // text
				'charset' => 'iso-8859-1',
				'newline' => '\r\n',
				'wordwrap' => TRUE
			);

			$subject = $this->input->post('subject', TRUE);
			$message = $this->input->post('message', TRUE);
			$userData = $this->input->post('to', TRUE);

			$sent = 0;
			foreach ($userData as $key => $value) {
				$this->email->clear();
				$this->email->initialize($config);
				$this->email->to($value);
				$this->email->from($Email_From_Address, $Emails_From_Name);
				$this->email->subject($subject);
				$this->email->message($message);
				if ($this->email->send()) {
					$sent++;
				}
				//Success email Sent
				//echo $this->email->print_debugger();
				//die;	
			}

			if ($sent > 0) {
				//Success email Sent
				//echo $this->email->print_debugger();
				//die;
				$this->session->set_flashdata('s_user_success', 'Mail has been Sent successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/emails_list");
				}
			}
		}


		$conditions_des['status ='] = 1;
		$data['userListData'] = $this->base_model->getAllRows('brij_users', 'id DESC', $conditions_des);


		$data['title'] = 'Send Email';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/emails-list', $data);
		$this->load->view('Admin/include/footer');
	}
	//Email Management Coding End

	//SMS Management Coding Start
	public function sms_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");

			$message = $this->input->post('message', TRUE);
			$userData = $this->input->post('to', TRUE);

			$sent = 0;
			/* foreach($userData as $key=>$value){
					$sent++;
			} */

			if ($sent > 0) {

				$this->session->set_flashdata('s_user_success', 'SMS has been Sent successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/sms_list");
				}
			}
			$this->session->set_flashdata('s_user_error', 'User SMS feature does not working. Please provide SMS API!');
		}
		$conditions_des['status ='] = 1;
		$data['userListData'] = $this->base_model->getAllRows('brij_users', 'id DESC', $conditions_des);

		$data['title'] = 'Send SMS';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/sms-list', $data);
		$this->load->view('Admin/include/footer');
	}
	//SMS Management Coding End

	//General Settings Coding Start
	public function theme_settings()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('general_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//echo "<pre>";print_r($_FILES);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			$website_name = $this->input->post('website_name', TRUE);
			$site_url = $this->input->post('site_url', TRUE);
			$site_description = $this->input->post('site_description', TRUE);
			$site_keywords = $this->input->post('site_keywords', TRUE);
			$site_owner_name = $this->input->post('site_owner_name', TRUE);
			$site_address = $this->input->post('site_address', TRUE);
			$site_phone_no = $this->input->post('site_phone_no', TRUE);
			$facebook_url = $this->input->post('facebook_url', TRUE);
			$twitter_url = $this->input->post('twitter_url', TRUE);
			$instagram_url = $this->input->post('instagram_url', TRUE);
			$linkedin_url = $this->input->post('linkedin_url', TRUE);
			$google_analytics_code = $this->input->post('google_analytics_code');
			$site_email_address = $this->input->post('site_email_address', TRUE);

			$update_data_site_name = array(
				'conf_value' =>  $website_name,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 1);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_name, $where_conditions);

			$update_data_site_url = array(
				'conf_value' =>  $site_url,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 2);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_url, $where_conditions);


			$update_data_site_description = array(
				'conf_value' =>  $site_description,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 3);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_description, $where_conditions);


			$update_data_site_keywords = array(
				'conf_value' =>  $site_keywords,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 4);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_keywords, $where_conditions);


			$update_data_site_owner_name = array(
				'conf_value' =>  $site_owner_name,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 5);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_owner_name, $where_conditions);


			$update_data_site_address = array(
				'conf_value' =>  $site_address,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 7);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_address, $where_conditions);

			$update_data_site_phone_no = array(
				'conf_value' =>  $site_phone_no,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 13);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_phone_no, $where_conditions);
			$update_data_google_analytics_code = array(
				'conf_value' =>  $google_analytics_code,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 8);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_google_analytics_code, $where_conditions);

			$update_data_site_email_a = array(
				'conf_value' =>  $site_email_address,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 28);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_email_a, $where_conditions);

			$update_data_site_facebook_a = array(
				'conf_value' =>  $facebook_url,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 46);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_facebook_a, $where_conditions);

			$update_data_site_twitter_a = array(
				'conf_value' =>  $twitter_url,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 47);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_twitter_a, $where_conditions);

			$update_data_site_instagram_a = array(
				'conf_value' =>  $instagram_url,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 48);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_instagram_a, $where_conditions);


			$update_data_site_linkedin_a = array(
				'conf_value' =>  $linkedin_url,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 49);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_linkedin_a, $where_conditions);
			$newSiteDetails = $this->base_model->getOneRecord("brij_configuration", "id", '15', "conf_value");
			//Check whether user upload site logo image
			if (!empty($_FILES['site_logo']['name'])) {
				$config['upload_path'] = 'uploads/site_images/';
				$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
				$config['file_name'] = $_FILES['site_logo']['name'];
				$catfilename = 'uploads/site_images/' . $newSiteDetails->conf_value;
				if (file_exists($catfilename) && !empty($newSiteDetails->conf_value) && isset($newSiteDetails->conf_value)) {
					$_image = $newSiteDetails->conf_value;
					unlink(realpath('uploads/site_images/' . $_image));
					unlink(realpath('uploads/site_images/large/' . $_image));
					unlink(realpath('uploads/site_images/medium/' . $_image));
					unlink(realpath('uploads/site_images/small/' . $_image));
				}
				//Load upload library and initialize configuration
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('site_logo')) {
					$uploadData = $this->upload->data();
					$site_logo_image = $uploadData['file_name'];
					$this->load->library('image_lib');
					$dom = new DOMDocument('1.0', 'utf-8');
					$ext = pathinfo($site_logo_image, PATHINFO_EXTENSION);
					/* First size */
					$configSize1['image_library']   = 'gd2';
					$configSize1['source_image']    = $uploadData['full_path'];
					$configSize1['create_thumb']    = FALSE;
					$configSize1['maintain_ratio']  = TRUE;
					$configSize1['width']           = 800;
					$configSize1['height']          = 600;
					$configSize1['new_image']   = ROOT_PATH . '/uploads/site_images/large';

					$this->image_lib->initialize($configSize1);
					$this->image_lib->resize();
					if ($ext == 'svg') {
						$dom->load($uploadData['full_path']);
						$svg = $dom->documentElement;
						if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
							// userspace coordinates
							$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

							$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
								preg_match($pattern, $svg->getAttribute('height'), $height);

							if ($interpretable) {
								$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
								$svg->setAttribute('viewBox', $view_box);
							} else { // this gets sticky
								throw new Exception("viewBox is dependent on environment");
							}
						}

						$svg->setAttribute('width', '800');
						$svg->setAttribute('height', '600');
						$dom->save(ROOT_PATH . '/uploads/site_images/large/' . $site_logo_image);
					}
					$this->image_lib->clear();
					/* Second size */
					$configSize2['image_library']   = 'gd2';
					$configSize2['source_image']    = $uploadData['full_path'];
					$configSize2['create_thumb']    = FALSE;
					$configSize2['maintain_ratio']  = TRUE;
					$configSize2['width']           = 300;
					$configSize2['height']          = 300;
					$configSize2['new_image']   = ROOT_PATH . '/uploads/site_images/medium';

					$this->image_lib->initialize($configSize2);
					$this->image_lib->resize();
					if ($ext == 'svg') {
						$dom->load($uploadData['full_path']);
						$svg = $dom->documentElement;
						if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
							// userspace coordinates
							$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

							$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
								preg_match($pattern, $svg->getAttribute('height'), $height);

							if ($interpretable) {
								$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
								$svg->setAttribute('viewBox', $view_box);
							} else { // this gets sticky
								throw new Exception("viewBox is dependent on environment");
							}
						}

						$svg->setAttribute('width', '300');
						$svg->setAttribute('height', '300');
						$dom->save(ROOT_PATH . '/uploads/site_images/medium/' . $site_logo_image);
					}
					$this->image_lib->clear();
					/* Third size */
					$configSize3['image_library']   = 'gd2';
					$configSize3['source_image']    = $uploadData['full_path'];
					$configSize3['create_thumb']    = FALSE;
					$configSize3['maintain_ratio']  = TRUE;
					$configSize3['width']           = 90;
					$configSize3['height']          = 90;
					$configSize3['new_image']   =  ROOT_PATH . '/uploads/site_images/small';

					$this->image_lib->initialize($configSize3);
					$this->image_lib->resize();
					if ($ext == 'svg') {
						$dom->load($uploadData['full_path']);
						$svg = $dom->documentElement;
						if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
							// userspace coordinates
							$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

							$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
								preg_match($pattern, $svg->getAttribute('height'), $height);

							if ($interpretable) {
								$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
								$svg->setAttribute('viewBox', $view_box);
							} else { // this gets sticky
								throw new Exception("viewBox is dependent on environment");
							}
						}

						$svg->setAttribute('width', '90');
						$svg->setAttribute('height', '90');
						$dom->save(ROOT_PATH . '/uploads/site_images/small/' . $site_logo_image);
					}
					$this->image_lib->clear();
				} else {
					$site_logo_image = $newSiteDetails->conf_value;
				}
			} else {
				$site_logo_image = $newSiteDetails->conf_value;
			}
			$update_data_site_logo = array(
				'conf_value' =>  $site_logo_image,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 15);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_logo, $where_conditions);


			$newSiteDetails = $this->base_model->getOneRecord("brij_configuration", "id", '27', "conf_value");
			//Check whether user upload site logo image
			if (!empty($_FILES['favicon']['name'])) {
				$config['upload_path'] = 'uploads/site_images/';
				$config['allowed_types'] = 'jpg|jpeg|png|gif|ico';
				$config['file_name'] = $_FILES['favicon']['name'];
				$catfilename = 'uploads/site_images/' . $newSiteDetails->conf_value;
				if (file_exists($catfilename) && !empty($newSiteDetails->conf_value) && isset($newSiteDetails->conf_value)) {
					$_image = $newSiteDetails->conf_value;
					unlink(realpath('uploads/site_images/' . $_image));
					unlink(realpath('uploads/site_images/large/' . $_image));
					unlink(realpath('uploads/site_images/medium/' . $_image));
					unlink(realpath('uploads/site_images/small/' . $_image));
				}
				//Load upload library and initialize configuration
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('favicon')) {
					$uploadData = $this->upload->data();
					$favicon = $uploadData['file_name'];
					$this->load->library('image_lib');
					$dom = new DOMDocument('1.0', 'utf-8');
					$ext = pathinfo($favicon, PATHINFO_EXTENSION);
					/* First size */
					$configSize1['image_library']   = 'gd2';
					$configSize1['source_image']    = $uploadData['full_path'];
					$configSize1['create_thumb']    = FALSE;
					$configSize1['maintain_ratio']  = TRUE;
					$configSize1['width']           = 800;
					$configSize1['height']          = 600;
					$configSize1['new_image']   = ROOT_PATH . '/uploads/site_images/large';

					$this->image_lib->initialize($configSize1);
					$this->image_lib->resize();
					if ($ext == 'svg') {
						$dom->load($uploadData['full_path']);
						$svg = $dom->documentElement;
						if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
							// userspace coordinates
							$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

							$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
								preg_match($pattern, $svg->getAttribute('height'), $height);

							if ($interpretable) {
								$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
								$svg->setAttribute('viewBox', $view_box);
							} else { // this gets sticky
								throw new Exception("viewBox is dependent on environment");
							}
						}

						$svg->setAttribute('width', '800');
						$svg->setAttribute('height', '600');
						$dom->save(ROOT_PATH . '/uploads/site_images/large/' . $favicon);
					}
					$this->image_lib->clear();
					/* Second size */
					$configSize2['image_library']   = 'gd2';
					$configSize2['source_image']    = $uploadData['full_path'];
					$configSize2['create_thumb']    = FALSE;
					$configSize2['maintain_ratio']  = TRUE;
					$configSize2['width']           = 300;
					$configSize2['height']          = 300;
					$configSize2['new_image']   = ROOT_PATH . '/uploads/site_images/medium';

					$this->image_lib->initialize($configSize2);
					$this->image_lib->resize();
					if ($ext == 'svg') {
						$dom->load($uploadData['full_path']);
						$svg = $dom->documentElement;
						if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
							// userspace coordinates
							$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

							$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
								preg_match($pattern, $svg->getAttribute('height'), $height);

							if ($interpretable) {
								$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
								$svg->setAttribute('viewBox', $view_box);
							} else { // this gets sticky
								throw new Exception("viewBox is dependent on environment");
							}
						}

						$svg->setAttribute('width', '300');
						$svg->setAttribute('height', '300');
						$dom->save(ROOT_PATH . '/uploads/site_images/medium/' . $favicon);
					}
					$this->image_lib->clear();
					/* Third size */
					$configSize3['image_library']   = 'gd2';
					$configSize3['source_image']    = $uploadData['full_path'];
					$configSize3['create_thumb']    = FALSE;
					$configSize3['maintain_ratio']  = TRUE;
					$configSize3['width']           = 90;
					$configSize3['height']          = 90;
					$configSize3['new_image']   =  ROOT_PATH . '/uploads/site_images/small';

					$this->image_lib->initialize($configSize3);
					$this->image_lib->resize();
					if ($ext == 'svg') {
						$dom->load($uploadData['full_path']);
						$svg = $dom->documentElement;
						if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
							// userspace coordinates
							$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

							$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
								preg_match($pattern, $svg->getAttribute('height'), $height);

							if ($interpretable) {
								$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
								$svg->setAttribute('viewBox', $view_box);
							} else { // this gets sticky
								throw new Exception("viewBox is dependent on environment");
							}
						}

						$svg->setAttribute('width', '90');
						$svg->setAttribute('height', '90');
						$dom->save(ROOT_PATH . '/uploads/site_images/small/' . $favicon);
					}
					$this->image_lib->clear();
				} else {
					$favicon = $newSiteDetails->conf_value;
				}
			} else {
				$favicon = $newSiteDetails->conf_value;
			}
			$update_data_favicon_logo = array(
				'conf_value' =>  $favicon,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 27);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_favicon_logo, $where_conditions);
			//$sent=1;
			if ($last_inserted_id > 0) {
				$this->session->set_flashdata('site_success', 'Theme Setting has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/theme_settings");
				}
			}
			$this->session->set_flashdata('site_error', 'Theme Setting does not save. Please try again!');
			//die;
		}

		$data['title'] = 'Theme Settings';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/theme-settings', $data);
		$this->load->view('Admin/include/footer');
	}

	public function email_settings()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('general_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");

			$mailoption = $this->input->post('mailoption', TRUE);
			$Email_From_Address = $this->input->post('Email_From_Address', TRUE);
			$Emails_From_Name = $this->input->post('Emails_From_Name', TRUE);
			$Order_Email_Address = $this->input->post('Order_Email_Address', TRUE);
			$SMTP_HOST = $this->input->post('SMTP_HOST', TRUE);
			$SMTP_USER = $this->input->post('SMTP_USER', TRUE);
			$SMTP_PASSWORD = $this->input->post('SMTP_PASSWORD', TRUE);
			$SMTP_PORT = $this->input->post('SMTP_PORT', TRUE);
			$SMTP_Security = $this->input->post('SMTP_Security', TRUE);
			$SMTP_Authentication = $this->input->post('SMTP_Authentication', TRUE);

			$update_data_mailoption = array(
				'conf_value' =>  $mailoption,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 36);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_mailoption, $where_conditions);

			$update_data_Email_From_Address = array(
				'conf_value' =>  $Email_From_Address,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 37);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_Email_From_Address, $where_conditions);


			$update_data_Emails_From_Name = array(
				'conf_value' =>  $Emails_From_Name,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 38);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_Emails_From_Name, $where_conditions);

			$update_data_Order_Email_Address = array(
				'conf_value' =>  $Order_Email_Address,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 39);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_Order_Email_Address, $where_conditions);

			$update_data_SMTP_HOST = array(
				'conf_value' =>  $SMTP_HOST,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 40);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_SMTP_HOST, $where_conditions);

			$update_data_SMTP_USER = array(
				'conf_value' =>  $SMTP_USER,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 41);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_SMTP_USER, $where_conditions);


			$update_data_SMTP_PASSWORD = array(
				'conf_value' =>  $SMTP_PASSWORD,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 42);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_SMTP_PASSWORD, $where_conditions);


			$update_data_SMTP_PORT = array(
				'conf_value' =>  $SMTP_PORT,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 43);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_SMTP_PORT, $where_conditions);


			$update_data_SMTP_Security = array(
				'conf_value' =>  $SMTP_Security,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 44);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_SMTP_Security, $where_conditions);

			$update_data_SMTP_Authentication = array(
				'conf_value' =>  $SMTP_Authentication,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 45);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_SMTP_Authentication, $where_conditions);

			if ($last_inserted_id > 0) {

				$this->session->set_flashdata('site_success', 'Email Settings has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/email_settings");
				}
			}
			$this->session->set_flashdata('site_error', 'Email Settings does not saved. Please try again!');
		}

		$data['title'] = 'Email Settings';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/email-settings', $data);
		$this->load->view('Admin/include/footer');
	}

	public function sms_settings()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");

			$sms_users = $this->input->post('sms_users', TRUE);
			$sms_users_password = $this->input->post('sms_users_password', TRUE);
			$sms_sender_id = $this->input->post('sms_sender_id', TRUE);

			$update_data_sms_users = array(
				'conf_value' =>  $sms_users,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 29);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_sms_users, $where_conditions);

			$update_data_sms_users_password = array(
				'conf_value' =>  $sms_users_password,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 30);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_sms_users_password, $where_conditions);


			$update_data_sms_sender_id = array(
				'conf_value' =>  $sms_sender_id,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 31);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_sms_sender_id, $where_conditions);

			if ($last_inserted_id > 0) {
				$this->session->set_flashdata('site_success', 'SMS Settings has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/sms_settings");
				}
			}
			$this->session->set_flashdata('site_error', 'SMS Settings does not saved. Please try again!');
		}

		$data['title'] = 'SMS Settings';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/sms-settings', $data);
		$this->load->view('Admin/include/footer');
	}

	public function payment_settings()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");

			$merchant_id = $this->input->post('merchant_id', TRUE);
			$merchant_key = $this->input->post('merchant_key', TRUE);
			$merchant_salt = $this->input->post('merchant_salt', TRUE);
			$payu_base_url = $this->input->post('payu_base_url', TRUE);

			$update_data_merchant_id = array(
				'conf_value' =>  $merchant_id,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 32);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_merchant_id, $where_conditions);

			$update_data_merchant_key = array(
				'conf_value' =>  $merchant_key,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 33);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_merchant_key, $where_conditions);


			$update_data_merchant_salt = array(
				'conf_value' =>  $merchant_salt,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 34);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_merchant_salt, $where_conditions);

			$update_data_payu_base_url = array(
				'conf_value' =>  $payu_base_url,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 35);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_payu_base_url, $where_conditions);

			if ($last_inserted_id > 0) {
				$this->session->set_flashdata('site_success', 'Payment Settings has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/payment_settings");
				}
			}
			$this->session->set_flashdata('site_error', 'Payment Settings does not saved. Please try again!');
		}

		$data['title'] = 'Payment Settings';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/payment-settings', $data);
		$this->load->view('Admin/include/footer');
	}

	public function notifications()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");

			$SITE_EMAIL_NOTIFICATION = $this->input->post('SITE_EMAIL_NOTIFICATION', TRUE);
			$SITE_SMS_NOTIFICATION = $this->input->post('SITE_SMS_NOTIFICATION', TRUE);

			$update_data_site_email_n = array(
				'conf_value' =>  $SITE_EMAIL_NOTIFICATION,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 25);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_email_n, $where_conditions);

			$update_data_site_sms_n = array(
				'conf_value' =>  $SITE_SMS_NOTIFICATION,
				'date_modified' =>  $date,
			);
			$where_conditions = array("id" => 26);
			$last_inserted_id = $this->base_model->update_entry('brij_configuration', $update_data_site_sms_n, $where_conditions);

			if ($last_inserted_id > 0) {
				$this->session->set_flashdata('s_user_success', 'Notifications Settings has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/notifications");
				}
			}
			$this->session->set_flashdata('s_user_error', 'Notifications Settings does not saved. Please try again!');
		}


		$data['title'] = 'Notifications Settings';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/notifications-settings', $data);
		$this->load->view('Admin/include/footer');
	}
	//General Settings Coding End

	//application setting
	public function app_setting()
	{

		if ($this->admin_model->check_logged() === FALSE) {
			redirect(base_url() . 'admin');
		}

		if ($this->input->method() === 'post') {
			foreach ($this->input->post() as $key => $value) {
				if ($value == '') {
					$this->session->set_flashdata('app_error', 'Application version doess not updated. Please try again!');
					if ($this->agent->referrer()) {
						//redirect to some function
						redirect($this->agent->referrer());
					} else {
						redirect("admin/app_setting?do=edit_app_version");
					}
				}
			}
			if ($this->input->post('action', TRUE) && $this->input->post('action', TRUE) == 'updateAppVersion') {
				$id = $this->input->post('id', TRUE);
				$package_name = $this->input->post('package_name', TRUE);
				$old_version = $this->input->post('old_version', TRUE);
				$new_version = $this->input->post('new_version', TRUE);
				$message = $this->input->post('message', TRUE);
				$updated_at = date("Y-m-d H:i:s");
				if (isset($package_name) && isset($old_version) && isset($new_version) && isset($message) && !empty($package_name) && !empty($old_version) && isset($new_version) && !empty($message)) {
					$where = array("id" => $id);
					$app_version = $this->base_model->getOneRecordWithWhere("app_version", $where, "*");
					if (count($app_version) && !empty($app_version)) {
						if ($app_version->id == $id) {
							$update_data = array("package_name" => $package_name, "old_version" => $old_version, "new_version" => $new_version, "message" => $message, "updated_at" => $updated_at);
							$id = $app_version->id;
							$where_conditions = array("id" => $id);
							$res = $this->base_model->update_entry('app_version', $update_data, $where_conditions);
							if ($res) {
								$this->session->set_flashdata('app_success', 'Application version has been changed successfully.');
								if ($this->agent->referrer()) {
									//redirect to some function
									redirect($this->agent->referrer());
								} else {
									redirect("admin/app_setting?do=edit_app_version");
								}
							}
						} else {
							$this->session->set_flashdata('app_error', 'Application version id doess not match. Please try again!');
						}
					} else {
						$this->session->set_flashdata('app_error', 'Application version doess not match in our record. Please try again!');
					}
				} else {
					$this->session->set_flashdata('app_error', 'Application version doess not saved. Please try again!');
				}
			}
		}
		$do = $this->input->get('do', TRUE);
		if (isset($do) && $do == 'edit_app_version') {
			$where = array("id" => 1);
			$data['appVersion'] = $this->base_model->getOneRecordWithWhere("app_version", $where, "*");
		}
		$data['title'] = 'Application Setting';
		$this->load->view('include/header', $data);
		$this->load->view('include/left-menu', $data);
		$this->load->view('app_setting', $data);
		$this->load->view('include/footer');
	}

	//change password
	public function change_password()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			foreach ($this->input->post() as $key => $value) {
				if ($value == '') {
					$this->session->set_flashdata('change_pass_error', 'Change Password doess not saved. Please try again!');
					if ($this->agent->referrer()) {
						//redirect to some function
						redirect($this->agent->referrer());
					} else {
						redirect("admin/change_password");
					}
				}
			}
			$current_password = $this->input->post('current_password', TRUE);
			$new_pass = $this->input->post('new_password', TRUE);
			$confirm_pass = $this->input->post('confirm_password', TRUE);
			if (isset($new_pass) && isset($confirm_pass) && !empty($new_pass) && !empty($confirm_pass) && isset($current_password) && !empty($current_password)) {
				$where = array("user_pass" => md5($current_password), 'user_id' => $this->session->userdata('logged_in_brijwasi_data')['logged_in_id']);
				$admidData = $this->base_model->getOneRecordWithWhere("brij_admin", $where, "user_id,user_pass");
				if (isset($admidData) && !empty($admidData)) {
					if ($admidData->user_pass != md5($new_pass)) {
						$update_data = array("user_pass" => md5($new_pass));
						$user_id = $admidData->user_id;
						$where_conditions = array("user_id" => $user_id);
						$res = $this->base_model->update_entry('brij_admin', $update_data, $where_conditions);
						if ($res) {
							$this->session->set_flashdata('change_pass_success', 'Password has been changed successfully.');
							if ($this->agent->referrer()) {
								//redirect to some function
								redirect($this->agent->referrer());
							} else {
								redirect("admin/change_password");
							}
						}
					} else {
						$this->session->set_flashdata('change_pass_error', 'Current password and New password doess not same. Please enter different password!');
					}
				} else {
					$this->session->set_flashdata('change_pass_error', 'Current password doess not match in our record. Please try again!');
				}
			} else {
				$this->session->set_flashdata('change_pass_error', 'Password doess not saved. Please try again!');
			}
		}
		$data['title'] = 'Change Password';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/change_password', $data);
		$this->load->view('Admin/include/footer');
	}
	//New section for home
	public function home_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		
		$data = array();
		$data['title'] = 'Home Page';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/home-page', $data);
		$this->load->view('Admin/include/footer');
	}

	public function seo_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'SEO Page';
		$where_column['content_type ='] = 'Local';
		$where_column['page_id ='] = SEO_PAGE_ID;
		$localData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['localData'] = $localData;

		$where_column['content_type ='] = 'Global';
		$globalData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['globalData'] = $globalData;

		$where_column['content_type ='] = 'National';
		$nationalData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['nationalData'] = $nationalData;

		$where_column['content_type ='] = 'Left';
		$leftData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['leftData'] = $leftData;

		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/seo-page', $data);
		$this->load->view('Admin/include/footer');
	}
	
	public function pricing_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'Pricing Page';
		$where_column['content_type ='] = 'Box1';
		$where_column['page_id ='] = PRICING_PAGE_ID;
		$localData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['localData'] = $localData;

		$where_column['content_type ='] ='Box2';
		$globalData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['globalData'] = $globalData;

		$where_column['content_type ='] = 'Box3';
		$nationalData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['nationalData'] = $nationalData;

		
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/pricing-page', $data);
		$this->load->view('Admin/include/footer');
	}
	
	public function smo_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'SMO Page';
		$where_column['content_type ='] = 'Box';
		$where_column['page_id ='] = SMO_PAGE_ID;
		$localData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['localData'] = $localData;

		
		
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/smo-page', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_seo_content()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('content_type', TRUE) && !empty($this->input->post('content_type', TRUE))) {
				$where_column['content_type ='] = $this->input->post('content_type', TRUE);
				$where_column['page_id ='] = SEO_PAGE_ID;
				$bannerDetails = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
				$bannerdata = json_decode($bannerDetails->json_content);
				$img = $bannerdata->image;
				//Check whether user upload slider image
				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = 'uploads/seo_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['image']['name'];
					$bannerfilename = 'uploads/seo_images/' . $img;
					if (file_exists($bannerfilename) && !empty($img) && isset($img)) {
						$_image = $img;
						unlink(realpath('uploads/seo_images/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('image')) {
						$uploadData = $this->upload->data();
						$banner_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/seo_images';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$banner_image = $img;
					}
				} else {
					$banner_image = $img;
				}
				$existDataArray = array(
					'title' => $this->input->post('title', TRUE),
					'image' =>  $banner_image,
					'content' =>  $this->input->post('content', TRUE),
				);
				$update_data['json_content'] =  json_encode($existDataArray);
				$update_data['updated_at'] =  $date;
				$where_conditions = array("content_type" => $this->input->post('content_type', TRUE), "page_id" => SEO_PAGE_ID);
				$last_inserted_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
			}
			if ($last_inserted_id) {
				$this->session->set_flashdata('seo_success', 'Data has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/seo_page");
				}
			}
			$this->session->set_flashdata('seo_error', 'Data does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/seo_page");
			}
		}
	}
	
	public function add_pricing_content()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('content_type', TRUE) && !empty($this->input->post('content_type', TRUE))) {
				$where_column['content_type ='] = $this->input->post('content_type', TRUE);
				$where_column['page_id ='] = PRICING_PAGE_ID;
				$bannerDetails = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
				$bannerdata = json_decode($bannerDetails->json_content);
				$img = $bannerdata->image;
				//Check whether user upload slider image
				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = 'uploads/seo_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['image']['name'];
					$bannerfilename = 'uploads/seo_images/' . $img;
					if (file_exists($bannerfilename) && !empty($img) && isset($img)) {
						$_image = $img;
						unlink(realpath('uploads/seo_images/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('image')) {
						$uploadData = $this->upload->data();
						$banner_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/seo_images';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$banner_image = $img;
					}
				} else {
					$banner_image = $img;
				}
				$existDataArray = array(
					'title' => $this->input->post('title', TRUE),
					'image' =>  $banner_image,
					'content' =>  $this->input->post('content', TRUE),
				);
				$update_data['json_content'] =  json_encode($existDataArray);
				$update_data['updated_at'] =  $date;
				$where_conditions = array("content_type" => $this->input->post('content_type', TRUE), "page_id" => PRICING_PAGE_ID);
				$last_inserted_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
			}
			if ($last_inserted_id) {
				$this->session->set_flashdata('seo_success', 'Data has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/pricing_page");
				}
			}
			$this->session->set_flashdata('seo_error', 'Data does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/pricing_page");
			}
		}
	}
	
	public function add_sem_content()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('content_type', TRUE) && !empty($this->input->post('content_type', TRUE))) {
				$where_column['content_type ='] = $this->input->post('content_type', TRUE);
				$where_column['page_id ='] = SEM_PAGE_ID;
				$bannerDetails = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
				$bannerdata = json_decode($bannerDetails->json_content);
				$img = $bannerdata->image;
				//Check whether user upload slider image
				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = 'uploads/seo_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['image']['name'];
					$bannerfilename = 'uploads/seo_images/' . $img;
					if (file_exists($bannerfilename) && !empty($img) && isset($img)) {
						$_image = $img;
						unlink(realpath('uploads/seo_images/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('image')) {
						$uploadData = $this->upload->data();
						$banner_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/seo_images';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$banner_image = $img;
					}
				} else {
					$banner_image = $img;
				}
				$existDataArray = array(
					'image' =>  $banner_image,
				);
				$update_data['json_content'] =  json_encode($existDataArray);
				$update_data['updated_at'] =  $date;
				$where_conditions = array("content_type" => $this->input->post('content_type', TRUE), "page_id" => SEM_PAGE_ID);
				$last_inserted_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
			}
			if ($last_inserted_id) {
				$this->session->set_flashdata('seo_success', 'Data has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/seo_page");
				}
			}
			$this->session->set_flashdata('seo_error', 'Data does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/seo_page");
			}
		}
	}


	public function about_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'About Page';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/about-page', $data);
		$this->load->view('Admin/include/footer');
	}

	public function career_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'Career Page';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/career-page', $data);
		$this->load->view('Admin/include/footer');
	}

	public function sem_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'SEM Page';
		
		$where_column['content_type ='] = 'Core';
		$where_column['page_id ='] = SEM_PAGE_ID;
		$leftData = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['leftData'] = $leftData;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/sem-page', $data);
		$this->load->view('Admin/include/footer');
	}

	public function guest_posting_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'Guest Posting Page';
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/guest-posting-page', $data);
		$this->load->view('Admin/include/footer');
	}

	//Blogs Coding Start
	public function posts_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('posts_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = $search_criteria_or = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);
		$cat_id = $this->input->get('cat_id', TRUE);
		$from = $this->input->get('date_from', TRUE);
		$to = $this->input->get('date_to', TRUE);

		if (!isset($from) && !isset($to) && !isset($serach_query) && !isset($status) && empty($status) && empty($serach_query) && empty($from) && empty($to)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$cat_id = (isset($cat_id) && $cat_id != '') ? $cat_id : "";
		$from = (isset($from) && $from != '') ? $from : "";
		$to = (isset($to) && $to != '') ? $to : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(posts.date_added) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(posts.date_added) <='] = $to;
		}

		if (isset($status) && $status != '') {
			$search_criteria['posts.status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['posts.post_title LIKE'] = '%' . $serach_query . '%';
			//$search_criteria_or['posts.author_id']= '%'.$serach_query.'%';
		}
		if (isset($cat_id) && $cat_id != '') {
			$search_criteria['p_category.categories_id ='] = $cat_id;
		}
		$search_criteria['category.type ='] = 'P';
		$data['postsList'] = $this->admin_model->getPosts($search_criteria, $order_by = 'posts.id DESC');
		$search_criteria1 = array();
		$search_criteria1["parent_id ="] = 0;
		$search_criteria1["status ="] = 1;
		$search_criteria1['categories.type ='] = 'P';
		$data['AllCatDetails'] = $this->base_model->getAllRows('categories', 'name ASC', $search_criteria1, 'id,name', $search_criteria_or);

		$data['title'] = 'Posts List';

		$data['searchpagesKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['catIdKeyword'] = $cat_id;
		$data['searchuserFromKeyword'] = $from;
		$data['searchuserToKeyword'] = $to;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/posts-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_post()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('posts_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$postDetails = $this->base_model->getOneRecord("posts", "id", $this->input->post('id', TRUE), "id,post_image");
				//Check whether user upload slider image
				if (!empty($_FILES['post_image']['name'])) {
					$config['upload_path'] = 'uploads/post_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['post_image']['name'];
					$pagefilename = 'uploads/post_images/' . $postDetails->post_image;
					if (file_exists($pagefilename) && !empty($postDetails->post_image) && isset($postDetails->post_image)) {
						$_image = $postDetails->post_image;
						unlink(realpath('uploads/post_images/' . $_image));
						unlink(realpath('uploads/post_images/large/' . $_image));
						unlink(realpath('uploads/post_images/medium/' . $_image));
						unlink(realpath('uploads/post_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('post_image')) {
						$uploadData = $this->upload->data();
						$post_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($post_image, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/post_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/post_images/large/' . $post_image);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/post_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/post_images/medium/' . $post_image);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/post_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/post_images/small/' . $post_image);
						}
						$this->image_lib->clear();
					} else {
						$post_image = $postDetails->post_image;
					}
				} else {
					$post_image = $postDetails->post_image;
				}
				$feature=0;
				if(isset($_POST['feature'])){
					$feature=1;
				}
				$update_data = array(
					'post_title' => $this->input->post('post_title', TRUE),
					'post_slug' => $this->input->post('post_slug', TRUE),
					'meta_tag' => $this->input->post('meta_tag', TRUE),
					'meta_keyword' => $this->input->post('meta_keyword', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'post_image' =>  $post_image,
					'post_short_content' =>  $this->input->post('post_short_content'),
					'post_long_content' =>  $this->input->post('post_long_content'),
					'post_type' => implode(',', $this->input->post('post_type', TRUE)),
					'post_display_order' => implode(',', $this->input->post('post_display_order', TRUE)),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
					'feature' =>  $feature,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('posts', $update_data, $where_conditions);
				try {
					$search_criteria1["posts_id ="] = $this->input->post('id', TRUE);
					$search_criteria1["p_c_type ="] = 'P';
					$AllPostCatDetails = $this->base_model->getAllRows('post_categories', 'id ASC', $search_criteria1, 'posts_id,categories_id');
					//print_r($AllPostCatDetails);
					//die;
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$checked = 0;
							if (!empty($AllPostCatDetails)) {
								foreach ($AllPostCatDetails as $AllPostCatDetailsV) {
									if ($categoryIdArrayV == $AllPostCatDetailsV->categories_id) {
										$checked = 1;
									}
								}
							}
							if ($checked === 1) {
								$where_conditions_d = array('posts_id' => $this->input->post('id', TRUE), 'p_c_type' => 'P');
								$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_d);
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'P');
							} else {
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'P');
							}
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			} else {
				if (!getUserCan('posts_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload category image
				if (!empty($_FILES['post_image']['name'])) {
					$config['upload_path'] = 'uploads/post_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['post_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('post_image')) {
						$uploadData = $this->upload->data();
						$post_image = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($post_image, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/post_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/post_images/large/' . $post_image);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/post_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/post_images/medium/' . $post_image);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/post_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/post_images/small/' . $post_image);
						}
						$this->image_lib->clear();
					} else {
						$post_image = '';
					}
				} else {
					$post_image = '';
				}
				$insert_data = array(
					'author_id' => $logged_in_id,
					'post_title' => $this->input->post('post_title', TRUE),
					'post_slug' => $this->input->post('post_slug', TRUE),
					'meta_tag' => $this->input->post('meta_tag', TRUE),
					'meta_keyword' => $this->input->post('meta_keyword', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'post_image' =>  $post_image,
					'post_short_content' =>  $this->input->post('post_short_content'),
					'post_long_content' =>  $this->input->post('post_long_content'),
					'post_type' => implode(',', $this->input->post('post_type', TRUE)),
					'post_display_order' => implode(',', $this->input->post('post_display_order', TRUE)),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('posts', $insert_data);
				try {
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$postCategories[] = array('posts_id' => $last_inserted_id, 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'P');
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('post_success', 'Post has been updated successfully.');
				} else {
					$this->session->set_flashdata('post_success', 'Post has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/posts_list");
				}
			}
			$this->session->set_flashdata('post_error', 'Post does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/posts_list");
			}
		}
	}

	public function post_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('posts_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$post_id = trim($this->input->get('post_id', TRUE));
			switch ($do) {
				case 'pending':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $post_id);
					$last_inserted_id = $this->base_model->update_entry('posts', $update_data, $where_conditions);
					break;
				case 'publish':
					$update_data = array(
						'status' => 2
					);
					$where_conditions = array("id" => $post_id);
					$last_inserted_id = $this->base_model->update_entry('posts', $update_data, $where_conditions);
					break;
				case 'draft':
					$update_data = array(
						'status' => 3
					);
					$where_conditions = array("id" => $post_id);
					$last_inserted_id = $this->base_model->update_entry('posts', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('post_success', 'Post Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/posts_list");
			}
		}
	}
	public function post_type()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$post_id = trim($this->input->get('post_id', TRUE));
			switch ($do) {
				case 'none-featured':
					$update_data = array(
						'post_type' => 0
					);
					$where_conditions = array("id" => $post_id);
					$last_inserted_id = $this->base_model->update_entry('posts', $update_data, $where_conditions);
					break;
				case 'featured':
					$update_data = array(
						'post_type' => 1
					);
					$where_conditions = array("id" => $post_id);
					$last_inserted_id = $this->base_model->update_entry('posts', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('post_success', 'Post Type has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/posts_list");
			}
		}
	}
	public function delete_post()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('posts_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$post_id = $this->input->post('post_id');
			$postDetails = $this->base_model->getOneRecord("posts", "id", $post_id, "id,post_image");

			$postfilename = 'uploads/post_images/' . $postDetails->post_image;
			if (file_exists($postfilename) && !empty($postDetails->post_image) && isset($postDetails->post_image)) {
				$_image = $postDetails->post_image;
				unlink(realpath('uploads/post_images/' . $_image));
				unlink(realpath('uploads/post_images/large/' . $_image));
				unlink(realpath('uploads/post_images/medium/' . $_image));
				unlink(realpath('uploads/post_images/small/' . $_image));
			}
			$where_conditions_b = array('posts_id' => $post_id, 'p_c_type' => 'P');
			$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_b);
			$where_conditions_d = array('id' => $post_id);
			$deleted = $this->base_model->deleteWithWhereConditions('posts', $where_conditions_d);

			$this->session->set_flashdata('post_success', 'Post has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/posts_list");
			}
		}
	}
	//Blogs Coding End

	//Categories Coding Start
	public function categories_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('categories_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);
		$type = $this->input->get('type', TRUE);

		if (!isset($serach_query) && !isset($status) && !isset($type) && empty($status) && empty($serach_query) && empty($type)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$type = (isset($type) && $type != '') ? $type : "";

		if (isset($status) && $status != '') {
			$search_criteria['status ='] = $status;
		}

		if (isset($type) && $type != '') {
			$search_criteria['type ='] = $type;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['name LIKE'] = '%' . $serach_query . '%';
		}
		$search_criteria['parent_id ='] = 0;
		$select_column_name = "id,name,description,type,slug,status";
		$data['categoryList'] = $this->admin_model->getSearch('categories', $order_by = 'id DESC', $search_criteria, '', '', $select_column_name);

		$data['title'] = 'Categories List';

		$data['searchcategoryKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['typeKeyword'] = $type;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/categories-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_category()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('categories_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//$catDetails = $this->base_model->getOneRecord("categories","id", $this->input->post('id', TRUE), "image");
				//Check whether user upload category image
				$update_data = array(
					'name' => $this->input->post('name', TRUE),
					'description' =>  $this->input->post('description', TRUE),
					'slug' =>  $this->input->post('slug', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'reorder' =>  $this->input->post('reorder', TRUE),
					'date_updated' =>  $date,
				);
				if ($this->input->post('type') != false) {
					$update_data['type'] = $this->input->post('type', TRUE);
				}
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('categories', $update_data, $where_conditions);
			} else {
				if (!getUserCan('categories_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload category image
				$insert_data = array(
					'name' => $this->input->post('name', TRUE),
					'description' =>  $this->input->post('description', TRUE),
					'slug' =>  $this->input->post('slug', TRUE),
					'type' =>  $this->input->post('type', TRUE),
					'status' =>  $this->input->post('status', TRUE),
					'reorder' =>  $this->input->post('reorder', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('categories', $insert_data);
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('cat_success', 'Category has been updated successfully.');
				} else {
					$this->session->set_flashdata('cat_success', 'Category has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/categories_list");
				}
			}
			$this->session->set_flashdata('cat_error', 'Category does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/categories_list");
			}
		}
	}

	public function category_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('categories_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$cat_id = trim($this->input->get('cat_id', TRUE));
			switch ($do) {
				case 'active':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $cat_id);
					$last_inserted_id = $this->base_model->update_entry('categories', $update_data, $where_conditions);
					break;
				case 'inactive':
					$update_data = array(
						'status' => 0
					);
					$where_conditions = array("id" => $cat_id);
					$last_inserted_id = $this->base_model->update_entry('categories', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('cat_success', 'Category Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/categories_list");
			}
		}
	}

	public function delete_category()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('categories_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$cat_id = $this->input->post('cat_id', TRUE);
			$delete_type = $this->input->post('delete_type', TRUE);
			$type = $this->input->post('type', TRUE);

			//print_r($_POST);
			//die;
			$catDetails = $this->base_model->getOneRecord("categories", "id", $cat_id, "id");
			if (isset($delete_type) && !empty($delete_type)) {
				switch ($delete_type) {
					case "delete-all-posts":
						if (isset($cat_id) && $cat_id != '') {
							$search_criteria['p_category.categories_id ='] = $cat_id;
							$search_criteria['category.type ='] = $type;
						}
						if ($type === 'Posts') {
							$postImageDetails = $this->admin_model->getPosts($search_criteria, $order_by = 'posts.id DESC');
							if (!empty($postImageDetails) && count($postImageDetails) > 0) {
								foreach ($postImageDetails as $postImageDetails) {
									$profilename = 'uploads/post_images/' . $postImageDetails->post_image;
									if (file_exists($profilename) && !empty($postImageDetails->post_image) && isset($postImageDetails->post_image)) {
										$_image = $postImageDetails->post_image;
										unlink(realpath('uploads/post_images/' . $_image));
										unlink(realpath('uploads/post_images/large/' . $_image));
										unlink(realpath('uploads/post_images/medium/' . $_image));
										unlink(realpath('uploads/post_images/small/' . $_image));
									}
								}
							}
						} elseif ($type === 'Testimonials') {
							$postImageDetails = $this->admin_model->getTestimonials($search_criteria, $order_by = 'testimonials.id DESC');
							if (!empty($postImageDetails) && count($postImageDetails) > 0) {
								foreach ($postImageDetails as $postImageDetails) {
									$postfilename = 'uploads/testimonial_images/' . $postImageDetails->picture;
									if (file_exists($postfilename) && !empty($postImageDetails->picture) && isset($postImageDetails->picture)) {
										$_image = $postImageDetails->picture;
										unlink(realpath('uploads/testimonial_images/' . $_image));
										unlink(realpath('uploads/testimonial_images/large/' . $_image));
										unlink(realpath('uploads/testimonial_images/medium/' . $_image));
										unlink(realpath('uploads/testimonial_images/small/' . $_image));
									}
									$profilename = 'uploads/testimonial_images/' . $postImageDetails->organization_logo;
									if (file_exists($profilename) && !empty($postImageDetails->organization_logo) && isset($postImageDetails->organization_logo)) {
										$_image = $postImageDetails->organization_logo;
										unlink(realpath('uploads/testimonial_images/' . $_image));
										unlink(realpath('uploads/testimonial_images/large/' . $_image));
										unlink(realpath('uploads/testimonial_images/medium/' . $_image));
										unlink(realpath('uploads/testimonial_images/small/' . $_image));
									}
								}
							}
						}elseif ($type === 'Case Studies') {
							$postImageDetails = $this->admin_model->getCaseStudies($search_criteria, $order_by = 'case_studies.id DESC');
							if (!empty($postImageDetails) && count($postImageDetails) > 0) {
								foreach ($postImageDetails as $postImageDetails) {
									$postfilename = 'uploads/case_study_images/' . $postImageDetails->case_study_logo;
									if (file_exists($postfilename) && !empty($postImageDetails->case_study_logo) && isset($postImageDetails->case_study_logo)) {
										$_image = $postImageDetails->case_study_logo;
										unlink(realpath('uploads/case_study_images/' . $_image));
										unlink(realpath('uploads/case_study_images/large/' . $_image));
										unlink(realpath('uploads/case_study_images/medium/' . $_image));
										unlink(realpath('uploads/case_study_images/small/' . $_image));
									}
									$postfilename = 'uploads/case_study_images/' . $postImageDetails->clicks_impressions_seo_overview_image;
									if (file_exists($postfilename) && !empty($postImageDetails->clicks_impressions_seo_overview_image) && isset($postImageDetails->clicks_impressions_seo_overview_image)) {
										$_image = $postImageDetails->clicks_impressions_seo_overview_image;
										unlink(realpath('uploads/case_study_images/' . $_image));
										unlink(realpath('uploads/case_study_images/large/' . $_image));
										unlink(realpath('uploads/case_study_images/medium/' . $_image));
										unlink(realpath('uploads/case_study_images/small/' . $_image));
									}
									$postfilename = 'uploads/case_study_images/' . $postImageDetails->client_image;
									if (file_exists($postfilename) && !empty($postImageDetails->client_image) && isset($postImageDetails->client_image)) {
										$_image = $postImageDetails->client_image;
										unlink(realpath('uploads/case_study_images/' . $_image));
										unlink(realpath('uploads/case_study_images/large/' . $_image));
										unlink(realpath('uploads/case_study_images/medium/' . $_image));
										unlink(realpath('uploads/case_study_images/small/' . $_image));
									}
									$postfilename = 'uploads/case_study_images/' . $postImageDetails->case_study_image;
									if (file_exists($postfilename) && !empty($postImageDetails->case_study_image) && isset($postImageDetails->case_study_image)) {
										$_image = $postImageDetails->case_study_image;
										unlink(realpath('uploads/case_study_images/' . $_image));
										unlink(realpath('uploads/case_study_images/large/' . $_image));
										unlink(realpath('uploads/case_study_images/medium/' . $_image));
										unlink(realpath('uploads/case_study_images/small/' . $_image));
									}
								}
							}
						}elseif ($type === 'Teams') {
							$postImageDetails = $this->admin_model->getTeam($search_criteria, $order_by = 'team.id DESC');
							if (!empty($postImageDetails) && count($postImageDetails) > 0) {
								foreach ($postImageDetails as $postImageDetails) {
									$postfilename = 'uploads/team_images/' . $postImageDetails->picture;
									if (file_exists($postfilename) && !empty($postImageDetails->picture) && isset($postImageDetails->picture)) {
										$_image = $postImageDetails->picture;
										unlink(realpath('uploads/team_images/' . $_image));
										unlink(realpath('uploads/team_images/large/' . $_image));
										unlink(realpath('uploads/team_images/medium/' . $_image));
										unlink(realpath('uploads/team_images/small/' . $_image));
									}
								}
							}
						}elseif ($type === 'Careers') {
							$postImageDetails = $this->admin_model->getCareer($search_criteria, $order_by = 'career.id DESC');
						}elseif ($type === 'Clients') {
							$postImageDetails = $this->admin_model->getClient($search_criteria, $order_by = 'client.id DESC');
							if (!empty($postImageDetails) && count($postImageDetails) > 0) {
								foreach ($postImageDetails as $postImageDetails) {
									$postfilename = 'uploads/client_images/' . $postImageDetails->picture;
									if (file_exists($postfilename) && !empty($postImageDetails->picture) && isset($postImageDetails->picture)) {
										$_image = $postImageDetails->picture;
										unlink(realpath('uploads/client_images/' . $_image));
										unlink(realpath('uploads/client_images/large/' . $_image));
										unlink(realpath('uploads/client_images/medium/' . $_image));
										unlink(realpath('uploads/client_images/small/' . $_image));
									}
								}
							}
						} else {
							# code...
						}

						$where_conditions_b = array('categories_id' => $cat_id);
						$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_b);
						$where_conditions_d = array('id' => $cat_id);
						$deleted = $this->base_model->deleteWithWhereConditions('categories', $where_conditions_d);
						break;
					case "assign-to":
						$update_data = array(
							'categories_id' => $this->input->post('assign_to_cat', TRUE),
						);
						$where_conditions = array("categories_id" => $cat_id);
						$last_inserted_id = $this->base_model->update_entry('post_categories', $update_data, $where_conditions);
						$where_conditions_d = array('id' => $cat_id);
						$deleted = $this->base_model->deleteWithWhereConditions('categories', $where_conditions_d);
						break;
				}
			} else {
				$where_conditions_b = array('categories_id' => $cat_id);
				$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_b);
				$where_conditions_d = array('id' => $cat_id);
				$deleted = $this->base_model->deleteWithWhereConditions('categories', $where_conditions_d);
			}
			$this->session->set_flashdata('cat_success', 'Category has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/categories_list");
			}
		}
	}
	//Categories Coding End
	//Testimonials Coding Start
	public function testimonials_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('testimonials_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = $search_criteria_or = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);
		$cat_id = $this->input->get('cat_id', TRUE);
		$from = $this->input->get('date_from', TRUE);
		$to = $this->input->get('date_to', TRUE);

		if (!isset($serach_query) && !isset($status) && !isset($from) && !isset($to) && !isset($cat_id) && empty($status) && empty($serach_query) && empty($cat_id) && empty($from) && empty($to)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$cat_id = (isset($cat_id) && $cat_id != '') ? $cat_id : "";
		$from = (isset($from) && $from != '') ? $from : "";
		$to = (isset($to) && $to != '') ? $to : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(testimonials.date_added) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(testimonials.date_added) <='] = $to;
		}

		if (isset($status) && $status != '') {
			$search_criteria['testimonials.status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['author LIKE'] = '%' . $serach_query . '%';
		}
		//$search_criteria['parent_id =']= 0;
		if (isset($cat_id) && $cat_id != '') {
			$search_criteria['p_category.categories_id ='] = $cat_id;
		}
		$search_criteria['category.type ='] = 'T';
		$data['testimonialList'] = $this->admin_model->getTestimonials($search_criteria, $order_by = 'testimonials.id DESC');
		$search_criteria1 = array();
		$search_criteria1["parent_id ="] = 0;
		$search_criteria1["status ="] = 1;
		$search_criteria1['categories.type ='] = 'T';
		$data['AllCatDetails'] = $this->base_model->getAllRows('categories', 'name ASC', $search_criteria1, 'id,name', $search_criteria_or);

		$data['title'] = 'Testimonials  List';

		$data['searchcategoryKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['searchuserFromKeyword'] = $from;
		$data['searchuserToKeyword'] = $to;
		$data['catIdKeyword'] = $cat_id;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/testimonials-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_testimonial()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('testimonials_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$testimonialDetails = $this->base_model->getOneRecord("testimonials", "id", $this->input->post('id', TRUE), "id,picture,organization_logo");
				//Check whether user upload slider image
				if (!empty($_FILES['picture']['name'])) {
					$config['upload_path'] = 'uploads/testimonial_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['picture']['name'];
					$pagefilename = 'uploads/testimonial_images/' . $testimonialDetails->picture;
					if (file_exists($pagefilename) && !empty($testimonialDetails->picture) && isset($testimonialDetails->picture)) {
						$_image = $testimonialDetails->picture;
						unlink(realpath('uploads/testimonial_images/' . $_image));
						unlink(realpath('uploads/testimonial_images/large/' . $_image));
						unlink(realpath('uploads/testimonial_images/medium/' . $_image));
						unlink(realpath('uploads/testimonial_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('picture')) {
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];
						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($picture, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/testimonial_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/large/' . $picture);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/testimonial_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/medium/' . $picture);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/testimonial_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/small/' . $picture);
						}
						$this->image_lib->clear();
					} else {
						$picture = $testimonialDetails->picture;
					}
				} else {
					$picture = $testimonialDetails->picture;
				}
				if (!empty($_FILES['organization_logo']['name'])) {
					$config['upload_path'] = 'uploads/testimonial_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['organization_logo']['name'];
					$pagefilename = 'uploads/testimonial_images/' . $testimonialDetails->organization_logo;
					if (file_exists($pagefilename) && !empty($testimonialDetails->organization_logo) && isset($testimonialDetails->organization_logo)) {
						$_image = $testimonialDetails->organization_logo;
						unlink(realpath('uploads/testimonial_images/' . $_image));
						unlink(realpath('uploads/testimonial_images/large/' . $_image));
						unlink(realpath('uploads/testimonial_images/medium/' . $_image));
						unlink(realpath('uploads/testimonial_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('organization_logo')) {
						$uploadData = $this->upload->data();
						$organization_logo = $uploadData['file_name'];
						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($organization_logo, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/testimonial_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/large/' . $organization_logo);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/testimonial_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/medium/' . $organization_logo);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/testimonial_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/small/' . $organization_logo);
						}
						$this->image_lib->clear();
					} else {
						$organization_logo = $testimonialDetails->organization_logo;
					}
				} else {
					$organization_logo = $testimonialDetails->organization_logo;
				}

				$update_data = array(
					'author' => $this->input->post('author', TRUE),
					'picture' => $picture,
					'role' => $this->input->post('role', TRUE),
					'organization' => $this->input->post('organization', TRUE),
					'description' => $this->input->post('description'),
					'organization_logo' =>  $organization_logo,
					'type' => implode(',', $this->input->post('type', TRUE)),
					'ordering' => implode(',', $this->input->post('ordering', TRUE)),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('testimonials', $update_data, $where_conditions);
				try {
					$search_criteria1["posts_id ="] = $this->input->post('id', TRUE);
					$search_criteria1["p_c_type ="] = 'T';
					$AllPostCatDetails = $this->base_model->getAllRows('post_categories', 'id ASC', $search_criteria1, 'posts_id,categories_id');
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$checked = 0;
							if (!empty($AllPostCatDetails)) {
								foreach ($AllPostCatDetails as $AllPostCatDetailsV) {
									if ($categoryIdArrayV == $AllPostCatDetailsV->categories_id) {
										$checked = 1;
									}
								}
							}
							if ($checked === 1) {
								$where_conditions_d = array('posts_id' => $this->input->post('id', TRUE), 'p_c_type' => 'T');
								$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_d);
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'T');
							} else {
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'T');
							}
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			} else {
				if (!getUserCan('testimonials_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload category image
				if (!empty($_FILES['picture']['name'])) {
					$config['upload_path'] = 'uploads/testimonial_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['picture']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('picture')) {
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($picture, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/testimonial_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/large/' . $picture);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/testimonial_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/medium/' . $picture);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/testimonial_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/small/' . $picture);
						}
						$this->image_lib->clear();
					} else {
						$picture = '';
					}
				} else {
					$picture = '';
				}
				if (!empty($_FILES['organization_logo']['name'])) {
					$config['upload_path'] = 'uploads/testimonial_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['organization_logo']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('organization_logo')) {
						$uploadData = $this->upload->data();
						$organization_logo = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($organization_logo, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/testimonial_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/large/' . $organization_logo);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/testimonial_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/medium/' . $organization_logo);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/testimonial_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/testimonial_images/small/' . $organization_logo);
						}
						$this->image_lib->clear();
					} else {
						$organization_logo = '';
					}
				} else {
					$organization_logo = '';
				}
				$insert_data = array(
					'author' => $this->input->post('author', TRUE),
					'picture' => $picture,
					'role' => $this->input->post('role', TRUE),
					'organization' => $this->input->post('organization', TRUE),
					'description' => $this->input->post('description'),
					'organization_logo' =>  $organization_logo,
					'type' => implode(',', $this->input->post('type', TRUE)),
					'ordering' => implode(',', $this->input->post('ordering', TRUE)),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('testimonials', $insert_data);
				try {
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$postCategories[] = array('posts_id' => $last_inserted_id, 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'T');
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('testimonial_success', 'Testimonial has been updated successfully.');
				} else {
					$this->session->set_flashdata('testimonial_success', 'Testimonial has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/testimonials_list");
				}
			}
			$this->session->set_flashdata('testimonial_error', 'Testimonial does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/testimonials_list");
			}
		}
	}

	public function testimonial_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('testimonials_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$testimonial_id = trim($this->input->get('testimonial_id', TRUE));
			switch ($do) {
				case 'pending':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('testimonials', $update_data, $where_conditions);
					break;
				case 'publish':
					$update_data = array(
						'status' => 2
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('testimonials', $update_data, $where_conditions);
					break;
				case 'draft':
					$update_data = array(
						'status' => 3
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('testimonials', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('testimonial_success', 'Testimonial Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/testimonials_list");
			}
		}
	}

	public function delete_testimonial()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('testimonials_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$testimonial_id = $this->input->post('testimonial_id', TRUE);
			//print_r($_POST);
			//die;
			$testiDetails = $this->base_model->getOneRecord("testimonials", "id", $testimonial_id, "id,picture,organization_logo");
			$postfilename = 'uploads/testimonial_images/' . $testiDetails->picture;
			$postfilename1 = 'uploads/testimonial_images/' . $testiDetails->organization_logo;
			if (file_exists($postfilename) && !empty($testiDetails->picture) && isset($testiDetails->picture)) {
				$_image = $testiDetails->picture;
				unlink(realpath('uploads/testimonial_images/' . $_image));
				unlink(realpath('uploads/testimonial_images/large/' . $_image));
				unlink(realpath('uploads/testimonial_images/medium/' . $_image));
				unlink(realpath('uploads/testimonial_images/small/' . $_image));
			}
			if (file_exists($postfilename1) && !empty($testiDetails->organization_logo) && isset($testiDetails->organization_logo)) {
				$_image = $testiDetails->organization_logo;
				unlink(realpath('uploads/testimonial_images/' . $_image));
				unlink(realpath('uploads/testimonial_images/large/' . $_image));
				unlink(realpath('uploads/testimonial_images/medium/' . $_image));
				unlink(realpath('uploads/testimonial_images/small/' . $_image));
			}
			$where_conditions_b = array('posts_id' => $testimonial_id, 'p_c_type' => 'T');
			$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_b);
			$where_conditions_d = array('id' => $testimonial_id);
			$deleted = $this->base_model->deleteWithWhereConditions('testimonials', $where_conditions_d);

			$this->session->set_flashdata('testimonial_success', 'Testimonial has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/testimonials_list");
			}
		}
	}
	//Testimonials Coding End

	//Case Study Coding Start
	public function case_studies_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('case_studies_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = $search_criteria_or = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);
		$cat_id = $this->input->get('cat_id', TRUE);
		$from = $this->input->get('date_from', TRUE);
		$to = $this->input->get('date_to', TRUE);

		if (!isset($from) && !isset($to) && !isset($serach_query) && !isset($status) && empty($status) && empty($serach_query) && empty($from) && empty($to)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$cat_id = (isset($cat_id) && $cat_id != '') ? $cat_id : "";
		$from = (isset($from) && $from != '') ? $from : "";
		$to = (isset($to) && $to != '') ? $to : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(case_studies.date_added) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(case_studies.date_added) <='] = $to;
		}

		if (isset($status) && $status != '') {
			$search_criteria['case_studies.status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['case_studies.case_study_title LIKE'] = '%' . $serach_query . '%';
			//$search_criteria_or['posts.author_id']= '%'.$serach_query.'%';
		}
		if (isset($cat_id) && $cat_id != '') {
			$search_criteria['p_category.categories_id ='] = $cat_id;
		}
		$search_criteria['category.type ='] = 'CS';
		$data['caseStudiesList'] = $this->admin_model->getCaseStudies($search_criteria, $order_by = 'case_studies.id DESC');
		$search_criteria1 = array();
		$search_criteria1["parent_id ="] = 0;
		$search_criteria1["status ="] = 1;
		$search_criteria1['categories.type ='] = 'CS';
		$data['AllCatDetails'] = $this->base_model->getAllRows('categories', 'name ASC', $search_criteria1, 'id,name', $search_criteria_or);

		$data['title'] = 'Case Studies List';

		$data['searchpagesKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['catIdKeyword'] = $cat_id;
		$data['searchuserFromKeyword'] = $from;
		$data['searchuserToKeyword'] = $to;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/case-studies-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_case_study()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//echo "<pre>";print_r($_FILES);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('case_studies_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$postDetails = $this->base_model->getOneRecord("case_studies", "id", $this->input->post('id', TRUE), "id,case_study_image,case_study_logo,client_image,clicks_impressions_seo_overview_image");
				//Check whether user upload category image
				if (!empty($_FILES['case_study_image']['name'])) {
					$config['upload_path'] = 'uploads/case_study_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['case_study_image']['name'];
					$pagefilename = 'uploads/case_study_images/' . $postDetails->case_study_image;
					if (file_exists($pagefilename) && !empty($postDetails->case_study_image) && isset($postDetails->case_study_image)) {
						$_image = $postDetails->case_study_image;
						unlink(realpath('uploads/case_study_images/' . $_image));
						unlink(realpath('uploads/case_study_images/large/' . $_image));
						unlink(realpath('uploads/case_study_images/medium/' . $_image));
						unlink(realpath('uploads/case_study_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('case_study_image')) {
						$uploadData = $this->upload->data();
						$case_study_image = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($case_study_image, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/case_study_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/large/' . $case_study_image);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/case_study_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/medium/' . $case_study_image);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/case_study_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/small/' . $case_study_image);
						}
						$this->image_lib->clear();
					} else {
						$case_study_image = $postDetails->case_study_image;
					}
				} else {
					$case_study_image = $postDetails->case_study_image;
				}

				//Check whether user upload category image
				if (!empty($_FILES['client_image']['name'])) {
					$config['upload_path'] = 'uploads/case_study_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['client_image']['name'];
					$pagefilename = 'uploads/case_study_images/' . $postDetails->client_image;
					if (file_exists($pagefilename) && !empty($postDetails->client_image) && isset($postDetails->client_image)) {
						$_image = $postDetails->client_image;
						unlink(realpath('uploads/case_study_images/' . $_image));
						unlink(realpath('uploads/case_study_images/large/' . $_image));
						unlink(realpath('uploads/case_study_images/medium/' . $_image));
						unlink(realpath('uploads/case_study_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('client_image')) {
						$uploadData = $this->upload->data();
						$client_image = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($client_image, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/case_study_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/large/' . $client_image);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/case_study_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/medium/' . $client_image);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/case_study_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/small/' . $client_image);
						}
						$this->image_lib->clear();
					} else {
						$client_image = $postDetails->client_image;
					}
				} else {
					$client_image = $postDetails->client_image;
				}

				//Check whether user upload category image
				if (!empty($_FILES['clicks_impressions_seo_overview_image']['name'])) {
					$config['upload_path'] = 'uploads/case_study_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['clicks_impressions_seo_overview_image']['name'];
					$pagefilename = 'uploads/case_study_images/' . $postDetails->clicks_impressions_seo_overview_image;
					if (file_exists($pagefilename) && !empty($postDetails->clicks_impressions_seo_overview_image) && isset($postDetails->clicks_impressions_seo_overview_image)) {
						$_image = $postDetails->clicks_impressions_seo_overview_image;
						unlink(realpath('uploads/case_study_images/' . $_image));
						unlink(realpath('uploads/case_study_images/large/' . $_image));
						unlink(realpath('uploads/case_study_images/medium/' . $_image));
						unlink(realpath('uploads/case_study_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('clicks_impressions_seo_overview_image')) {
						$uploadData = $this->upload->data();
						$clicks_impressions_seo_overview_image = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($clicks_impressions_seo_overview_image, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/case_study_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/large/' . $clicks_impressions_seo_overview_image);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/case_study_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/medium/' . $clicks_impressions_seo_overview_image);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/case_study_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/small/' . $clicks_impressions_seo_overview_image);
						}
						$this->image_lib->clear();
					} else {
						$clicks_impressions_seo_overview_image = $postDetails->clicks_impressions_seo_overview_image;
					}
				} else {
					$clicks_impressions_seo_overview_image = $postDetails->clicks_impressions_seo_overview_image;
				}

				//Check whether user upload category image
				if (!empty($_FILES['case_study_logo']['name'])) {
					$config['upload_path'] = 'uploads/case_study_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['case_study_logo']['name'];
					$pagefilename = 'uploads/case_study_images/' . $postDetails->case_study_logo;
					if (file_exists($pagefilename) && !empty($postDetails->case_study_logo) && isset($postDetails->clicks_impressions_seo_overview_image)) {
						$_image = $postDetails->case_study_logo;
						unlink(realpath('uploads/case_study_images/' . $_image));
						unlink(realpath('uploads/case_study_images/large/' . $_image));
						unlink(realpath('uploads/case_study_images/medium/' . $_image));
						unlink(realpath('uploads/case_study_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('case_study_logo')) {
						$uploadData = $this->upload->data();
						$case_study_logo = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($case_study_logo, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/case_study_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/large/' . $case_study_logo);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/case_study_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/medium/' . $case_study_logo);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/case_study_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/small/' . $case_study_logo);
						}
						$this->image_lib->clear();
					} else {
						$case_study_logo = $postDetails->case_study_logo;
					}
				} else {
					$case_study_logo = $postDetails->case_study_logo;
				}
				$update_data = array(
					'case_study_title' => $this->input->post('case_study_title', TRUE),
					'case_study_slug' => $this->input->post('case_study_slug', TRUE),
					'meta_tag' => $this->input->post('meta_tag', TRUE),
					'meta_keyword' => $this->input->post('meta_keyword', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'case_study_sub_title' => $this->input->post('case_study_sub_title', TRUE),
					'case_study_long_content' => $this->input->post('case_study_long_content', TRUE),
					'website_visitor' => $this->input->post('website_visitor', TRUE),
					'organic_search_traffic' => $this->input->post('organic_search_traffic', TRUE),
					'conversation_rate' => $this->input->post('conversation_rate', TRUE),
					'backstory_content' => $this->input->post('backstory_content'),
					'problem_statement_content' => $this->input->post('problem_statement_content'),
					'the_challenge_content' => $this->input->post('the_challenge_content'),
					'case_study_title_1' => $this->input->post('case_study_title_1', TRUE),
					'case_study_sub_title_1' => $this->input->post('case_study_sub_title_1', TRUE),
					'client_name' => $this->input->post('client_name', TRUE),
					'client_designation' => $this->input->post('client_designation', TRUE),
					'client_company' => $this->input->post('client_company', TRUE),
					'client_content' => $this->input->post('client_content', TRUE),
					'case_study_image' =>  $case_study_image,
					'client_image' =>  $client_image,
					'clicks_impressions_seo_overview_image' =>  $clicks_impressions_seo_overview_image,
					'case_study_logo' =>  $case_study_logo,
					'case_study_type' => implode(',', $this->input->post('case_study_type', TRUE)),
					'case_study_display_order' => implode(',', $this->input->post('case_study_display_order', TRUE)),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('case_studies', $update_data, $where_conditions);
				try {
					$search_criteria1["posts_id ="] = $this->input->post('id', TRUE);
					$search_criteria1["p_c_type ="] = 'CS';
					$AllPostCatDetails = $this->base_model->getAllRows('post_categories', 'id ASC', $search_criteria1, 'posts_id,categories_id');
					//print_r($AllPostCatDetails);
					//die;
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$checked = 0;
							if (!empty($AllPostCatDetails)) {
								foreach ($AllPostCatDetails as $AllPostCatDetailsV) {
									if ($categoryIdArrayV == $AllPostCatDetailsV->categories_id) {
										$checked = 1;
									}
								}
							}
							if ($checked === 1) {
								$where_conditions_d = array('posts_id' => $this->input->post('id', TRUE), 'p_c_type' => 'CS');
								$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_d);
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'CS');
							} else {
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'CS');
							}
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			} else {
				if (!getUserCan('case_studies_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload category image
				if (!empty($_FILES['case_study_image']['name'])) {
					$config['upload_path'] = 'uploads/case_study_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['case_study_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('case_study_image')) {
						$uploadData = $this->upload->data();
						$case_study_image = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($case_study_image, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/case_study_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/large/' . $case_study_image);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/case_study_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/medium/' . $case_study_image);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/case_study_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/small/' . $case_study_image);
						}
						$this->image_lib->clear();
					} else {
						$case_study_image = '';
					}
				} else {
					$case_study_image = '';
				}

				//Check whether user upload category image
				if (!empty($_FILES['client_image']['name'])) {
					$config['upload_path'] = 'uploads/case_study_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['client_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('client_image')) {
						$uploadData = $this->upload->data();
						$client_image = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($client_image, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/case_study_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/large/' . $client_image);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/case_study_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/medium/' . $client_image);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/case_study_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/small/' . $client_image);
						}
						$this->image_lib->clear();
					} else {
						$client_image = '';
					}
				} else {
					$client_image = '';
				}

				//Check whether user upload category image
				if (!empty($_FILES['clicks_impressions_seo_overview_image']['name'])) {
					$config['upload_path'] = 'uploads/case_study_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['clicks_impressions_seo_overview_image']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('clicks_impressions_seo_overview_image')) {
						$uploadData = $this->upload->data();
						$clicks_impressions_seo_overview_image = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($clicks_impressions_seo_overview_image, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/case_study_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/large/' . $clicks_impressions_seo_overview_image);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/case_study_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/medium/' . $clicks_impressions_seo_overview_image);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/case_study_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/small/' . $clicks_impressions_seo_overview_image);
						}
						$this->image_lib->clear();
					} else {
						$clicks_impressions_seo_overview_image = '';
					}
				} else {
					$clicks_impressions_seo_overview_image = '';
				}

				//Check whether user upload category image
				if (!empty($_FILES['case_study_logo']['name'])) {
					$config['upload_path'] = 'uploads/case_study_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['case_study_logo']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('case_study_logo')) {
						$uploadData = $this->upload->data();
						$case_study_logo = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($case_study_logo, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/case_study_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/large/' . $case_study_logo);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/case_study_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/medium/' . $case_study_logo);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/case_study_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/case_study_images/small/' . $case_study_logo);
						}
						$this->image_lib->clear();
					} else {
						$case_study_logo = '';
					}
				} else {
					$case_study_logo = '';
				}
				$insert_data = array(
					'case_study_title' => $this->input->post('case_study_title', TRUE),
					'case_study_slug' => $this->input->post('case_study_slug', TRUE),
					'meta_tag' => $this->input->post('meta_tag', TRUE),
					'meta_keyword' => $this->input->post('meta_keyword', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'case_study_sub_title' => $this->input->post('case_study_sub_title', TRUE),
					'case_study_long_content' => $this->input->post('case_study_long_content', TRUE),
					'website_visitor' => $this->input->post('website_visitor', TRUE),
					'organic_search_traffic' => $this->input->post('organic_search_traffic', TRUE),
					'conversation_rate' => $this->input->post('conversation_rate', TRUE),
					'backstory_content' => $this->input->post('backstory_content'),
					'problem_statement_content' => $this->input->post('problem_statement_content'),
					'the_challenge_content' => $this->input->post('the_challenge_content'),
					'case_study_title_1' => $this->input->post('case_study_title_1', TRUE),
					'case_study_sub_title_1' => $this->input->post('case_study_sub_title_1', TRUE),
					'client_name' => $this->input->post('client_name', TRUE),
					'client_designation' => $this->input->post('client_designation', TRUE),
					'client_company' => $this->input->post('client_company', TRUE),
					'client_content' => $this->input->post('client_content', TRUE),
					'case_study_image' =>  $case_study_image,
					'client_image' =>  $client_image,
					'clicks_impressions_seo_overview_image' =>  $clicks_impressions_seo_overview_image,
					'case_study_logo' =>  $case_study_logo,
					'case_study_type' => implode(',', $this->input->post('case_study_type', TRUE)),
					'case_study_display_order' => implode(',', $this->input->post('case_study_display_order', TRUE)),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('case_studies', $insert_data);
				try {
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$postCategories[] = array('posts_id' => $last_inserted_id, 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'CS');
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('cs_success', 'Case Study has been updated successfully.');
				} else {
					$this->session->set_flashdata('cs_success', 'Case Study has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/case_studies_list");
				}
			}
			$this->session->set_flashdata('cs_error', 'Case Study does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/case_studies_list");
			}
		}
	}

	public function case_study_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('case_studies_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$case_study_id = trim($this->input->get('case_study_id', TRUE));
			switch ($do) {
				case 'pending':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $case_study_id);
					$last_inserted_id = $this->base_model->update_entry('case_studies', $update_data, $where_conditions);
					break;
				case 'publish':
					$update_data = array(
						'status' => 2
					);
					$where_conditions = array("id" => $case_study_id);
					$last_inserted_id = $this->base_model->update_entry('case_studies', $update_data, $where_conditions);
					break;
				case 'draft':
					$update_data = array(
						'status' => 3
					);
					$where_conditions = array("id" => $case_study_id);
					$last_inserted_id = $this->base_model->update_entry('case_studies', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('cs_success', 'Case Study Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/case_studies_list");
			}
		}
	}
	public function delete_case_study()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('case_studies_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$cs_id = $this->input->post('cs_id');
			$postDetails = $this->base_model->getOneRecord("case_studies", "id", $cs_id, "id,case_study_logo,clicks_impressions_seo_overview_image,client_image,case_study_image");
			$postfilename = 'uploads/case_study_images/' . $postDetails->case_study_logo;
			if (file_exists($postfilename) && !empty($postDetails->case_study_logo) && isset($postDetails->case_study_logo)) {
				$_image = $postDetails->case_study_logo;
				unlink(realpath('uploads/case_study_images/' . $_image));
				unlink(realpath('uploads/case_study_images/large/' . $_image));
				unlink(realpath('uploads/case_study_images/medium/' . $_image));
				unlink(realpath('uploads/case_study_images/small/' . $_image));
			}
			$postfilename = 'uploads/case_study_images/' . $postDetails->clicks_impressions_seo_overview_image;
			if (file_exists($postfilename) && !empty($postDetails->clicks_impressions_seo_overview_image) && isset($postDetails->clicks_impressions_seo_overview_image)) {
				$_image = $postDetails->clicks_impressions_seo_overview_image;
				unlink(realpath('uploads/case_study_images/' . $_image));
				unlink(realpath('uploads/case_study_images/large/' . $_image));
				unlink(realpath('uploads/case_study_images/medium/' . $_image));
				unlink(realpath('uploads/case_study_images/small/' . $_image));
			}
			$postfilename = 'uploads/case_study_images/' . $postDetails->client_image;
			if (file_exists($postfilename) && !empty($postDetails->client_image) && isset($postDetails->client_image)) {
				$_image = $postDetails->client_image;
				unlink(realpath('uploads/case_study_images/' . $_image));
				unlink(realpath('uploads/case_study_images/large/' . $_image));
				unlink(realpath('uploads/case_study_images/medium/' . $_image));
				unlink(realpath('uploads/case_study_images/small/' . $_image));
			}
			$postfilename = 'uploads/case_study_images/' . $postDetails->case_study_image;
			if (file_exists($postfilename) && !empty($postDetails->case_study_image) && isset($postDetails->case_study_image)) {
				$_image = $postDetails->case_study_image;
				unlink(realpath('uploads/case_study_images/' . $_image));
				unlink(realpath('uploads/case_study_images/large/' . $_image));
				unlink(realpath('uploads/case_study_images/medium/' . $_image));
				unlink(realpath('uploads/case_study_images/small/' . $_image));
			}
			$where_conditions_b = array('posts_id' => $cs_id, 'p_c_type' => 'CS');
			$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_b);
			$where_conditions_d = array('id' => $cs_id);
			$deleted = $this->base_model->deleteWithWhereConditions('case_studies', $where_conditions_d);

			$this->session->set_flashdata('cs_success', 'Case Study has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/case_studies_list");
			}
		}
	}
	//Case Study Coding End


	// careee coding start

	public function career_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('career_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = $search_criteria_or = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);
		$cat_id = $this->input->get('cat_id', TRUE);
		$from = $this->input->get('date_from', TRUE);
		$to = $this->input->get('date_to', TRUE);

		if (!isset($from) && !isset($to) && !isset($serach_query) && !isset($status) && empty($status) && empty($serach_query) && empty($from) && empty($to)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$cat_id = (isset($cat_id) && $cat_id != '') ? $cat_id : "";
		$from = (isset($from) && $from != '') ? $from : "";
		$to = (isset($to) && $to != '') ? $to : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(career.date_added) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(career.date_added) <='] = $to;
		}

		if (isset($status) && $status != '') {
			$search_criteria['career.status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['career.post_title LIKE'] = '%' . $serach_query . '%';
			//$search_criteria_or['posts.author_id']= '%'.$serach_query.'%';
		}
		if (isset($cat_id) && $cat_id != '') {
			$search_criteria['p_category.categories_id ='] = $cat_id;
		}
		$search_criteria['category.type ='] = 'CA';
		$data['postsList'] = $this->admin_model->getCareer($search_criteria, $order_by = 'career.id DESC');
		$search_criteria1 = array();
		$search_criteria1["parent_id ="] = 0;
		$search_criteria1["status ="] = 1;
		$search_criteria1['categories.type ='] = 'CA';
		$data['AllCatDetails'] = $this->base_model->getAllRows('categories', 'name ASC', $search_criteria1, 'id,name', $search_criteria_or);

		$data['title'] = 'Career List';

		$data['searchpagesKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['catIdKeyword'] = $cat_id;
		$data['searchuserFromKeyword'] = $from;
		$data['searchuserToKeyword'] = $to;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/career-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_career()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('career_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$postDetails = $this->base_model->getOneRecord("career", "id", $this->input->post('id', TRUE), "id");
				//Check whether user upload slider image
				$update_data = array(
					'post_title' => $this->input->post('post_title', TRUE),
					'post_slug' => $this->input->post('post_slug', TRUE),
					'meta_tag' => $this->input->post('meta_tag', TRUE),
					'meta_keyword' => $this->input->post('meta_keyword', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'post_short_content' =>  $this->input->post('post_short_content'),
					'post_long_content' =>  $this->input->post('post_long_content'),
					'linkedin' => $this->input->post('linkedin', TRUE),
					'post_quali_content' => $this->input->post('post_quali_content'),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('career', $update_data, $where_conditions);
				try {
					$search_criteria1["posts_id ="] = $this->input->post('id', TRUE);
					$search_criteria1["p_c_type ="] = 'CA';
					$AllPostCatDetails = $this->base_model->getAllRows('post_categories', 'id ASC', $search_criteria1, 'posts_id,categories_id');
					//print_r($AllPostCatDetails);
					//die;
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$checked = 0;
							if (!empty($AllPostCatDetails)) {
								foreach ($AllPostCatDetails as $AllPostCatDetailsV) {
									if ($categoryIdArrayV == $AllPostCatDetailsV->categories_id) {
										$checked = 1;
									}
								}
							}
							if ($checked === 1) {
								$where_conditions_d = array('posts_id' => $this->input->post('id', TRUE), 'p_c_type' => 'CA');
								$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_d);
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'CA');
							} else {
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'CA');
							}
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			} else {
				if (!getUserCan('career_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload category image
				$insert_data = array(
					'author_id' => $logged_in_id,
					'post_title' => $this->input->post('post_title', TRUE),
					'post_slug' => $this->input->post('post_slug', TRUE),
					'meta_tag' => $this->input->post('meta_tag', TRUE),
					'meta_keyword' => $this->input->post('meta_keyword', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'post_short_content' =>  $this->input->post('post_short_content'),
					'post_long_content' =>  $this->input->post('post_long_content'),
					'linkedin' => $this->input->post('linkedin', TRUE),
					'post_quali_content' => $this->input->post('post_quali_content'),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('career', $insert_data);
				try {
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$postCategories[] = array('posts_id' => $last_inserted_id, 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'CA');
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('post_success', 'Career has been updated successfully.');
				} else {
					$this->session->set_flashdata('post_success', 'Career has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/career_list");
				}
			}
			$this->session->set_flashdata('post_error', 'Career does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/career_list");
			}
		}
	}




	public function career_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
        if (!getUserCan('career_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$post_id = trim($this->input->get('post_id', TRUE));
			switch ($do) {
				case 'pending':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $post_id);
					$last_inserted_id = $this->base_model->update_entry('career', $update_data, $where_conditions);
					break;
				case 'publish':
					$update_data = array(
						'status' => 2
					);
					$where_conditions = array("id" => $post_id);
					$last_inserted_id = $this->base_model->update_entry('career', $update_data, $where_conditions);
					break;
				case 'draft':
					$update_data = array(
						'status' => 3
					);
					$where_conditions = array("id" => $post_id);
					$last_inserted_id = $this->base_model->update_entry('career', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('post_success', 'Career Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/career_list");
			}
		}
	}

	public function delete_career()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('career_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$post_id = $this->input->post('post_id');
			$postDetails = $this->base_model->getOneRecord("career", "id", $post_id, "id");

			$where_conditions_b = array('posts_id' => $post_id, 'p_c_type' => 'CA');
			$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_b);
			$where_conditions_d = array('id' => $post_id);
			$deleted = $this->base_model->deleteWithWhereConditions('career', $where_conditions_d);

			$this->session->set_flashdata('post_success', 'Career has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/career_list");
			}
		}
	}

	// Career coding end

	// Team coding start

	public function team_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('team_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = $search_criteria_or = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);
		$cat_id = $this->input->get('cat_id', TRUE);
		$from = $this->input->get('date_from', TRUE);
		$to = $this->input->get('date_to', TRUE);

		if (!isset($serach_query) && !isset($status) && !isset($from) && !isset($to) && !isset($cat_id) && empty($status) && empty($serach_query) && empty($cat_id) && empty($from) && empty($to)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$cat_id = (isset($cat_id) && $cat_id != '') ? $cat_id : "";
		$from = (isset($from) && $from != '') ? $from : "";
		$to = (isset($to) && $to != '') ? $to : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(team.date_added) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(team.date_added) <='] = $to;
		}

		if (isset($status) && $status != '') {
			$search_criteria['team.status ='] = $status;
		}

		if (isset($serach_query) && $serach_query != '') {
			$search_criteria['author LIKE'] = '%' . $serach_query . '%';
		}
		//$search_criteria['parent_id =']= 0;
		if (isset($cat_id) && $cat_id != '') {
			$search_criteria['p_category.categories_id ='] = $cat_id;
		}
		$search_criteria['category.type ='] = 'TM';
		$data['teamList'] = $this->admin_model->getTeam($search_criteria, $order_by = 'team.id DESC');
		$search_criteria1 = array();
		$search_criteria1["parent_id ="] = 0;
		$search_criteria1["status ="] = 1;
		$search_criteria1['categories.type ='] = 'TM';
		$data['AllCatDetails'] = $this->base_model->getAllRows('categories', 'name ASC', $search_criteria1, 'id,name', $search_criteria_or);

		$data['title'] = 'Team  List';

		$data['searchcategoryKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['searchuserFromKeyword'] = $from;
		$data['searchuserToKeyword'] = $to;
		$data['catIdKeyword'] = $cat_id;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/team-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_team()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('team_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$testimonialDetails = $this->base_model->getOneRecord("team", "id", $this->input->post('id', TRUE), "id,picture");
				//Check whether user upload slider image
				if (!empty($_FILES['picture']['name'])) {
					$config['upload_path'] = 'uploads/team_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['picture']['name'];
					$pagefilename = 'uploads/team_images/' . $testimonialDetails->picture;
					if (file_exists($pagefilename) && !empty($testimonialDetails->picture) && isset($testimonialDetails->picture)) {
						$_image = $testimonialDetails->picture;
						unlink(realpath('uploads/
						/' . $_image));
						unlink(realpath('uploads/team_images/large/' . $_image));
						unlink(realpath('uploads/team_images/medium/' . $_image));
						unlink(realpath('uploads/team_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('picture')) {
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];
						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($picture, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/team_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/team_images/large/' . $picture);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/team_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/team_images/medium/' . $picture);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/team_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/team_images/small/' . $picture);
						}
						$this->image_lib->clear();
					} else {
						$picture = $testimonialDetails->picture;
					}
				} else {
					$picture = $testimonialDetails->picture;
				}
				$update_data = array(
					'author' => $this->input->post('author', TRUE),
					'picture' => $picture,
					'organization' => $this->input->post('organization', TRUE),
					'description' => $this->input->post('description'),
					'linkedin' => $this->input->post('linkedin'),
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('team', $update_data, $where_conditions);
				try {
					$search_criteria1["posts_id ="] = $this->input->post('id', TRUE);
					$search_criteria1["p_c_type ="] = 'TM';
					$AllPostCatDetails = $this->base_model->getAllRows('post_categories', 'id ASC', $search_criteria1, 'posts_id,categories_id');
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$checked = 0;
							if (!empty($AllPostCatDetails)) {
								foreach ($AllPostCatDetails as $AllPostCatDetailsV) {
									if ($categoryIdArrayV == $AllPostCatDetailsV->categories_id) {
										$checked = 1;
									}
								}
							}
							if ($checked === 1) {
								$where_conditions_d = array('posts_id' => $this->input->post('id', TRUE), 'p_c_type' => 'TM');
								$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_d);
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'TM');
							} else {
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'TM');
							}
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			} else {
				if (!getUserCan('team_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload category image
				if (!empty($_FILES['picture']['name'])) {
					$config['upload_path'] = 'uploads/team_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['picture']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('picture')) {
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($picture, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/team_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/team_images/large/' . $picture);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/team_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/team_images/medium/' . $picture);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/team_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/team_images/small/' . $picture);
						}
						$this->image_lib->clear();
					} else {
						$picture = '';
					}
				} else {
					$picture = '';
				}
				$insert_data = array(
					'author' => $this->input->post('author', TRUE),
					'picture' => $picture,
					'organization' => $this->input->post('organization', TRUE),
					'description' => $this->input->post('description'),
					'linkedin' => $this->input->post('linkedin'),
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('team', $insert_data);
				try {
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$postCategories[] = array('posts_id' => $last_inserted_id, 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'TM');
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('team_success', 'Team has been updated successfully.');
				} else {
					$this->session->set_flashdata('team_success', 'Team has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/team_list");
				}
			}
			$this->session->set_flashdata('team_error', 'Team does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/team_list");
			}
		}
	}

	public function team_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('team_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}

		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$testimonial_id = trim($this->input->get('team_id', TRUE));
			switch ($do) {
				case 'pending':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('team', $update_data, $where_conditions);
					break;
				case 'publish':
					$update_data = array(
						'status' => 2
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('team', $update_data, $where_conditions);
					break;
				case 'draft':
					$update_data = array(
						'status' => 3
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('team', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('team_success', 'Team Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/team_list");
			}
		}
	}

	public function delete_team()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('team_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$testimonial_id = $this->input->post('team_id', TRUE);
			//print_r($_POST);
			//die;
			$testiDetails = $this->base_model->getOneRecord("team", "id", $testimonial_id, "id,picture");
			$postfilename = 'uploads/team_images/' . $testiDetails->picture;
			if (file_exists($postfilename) && !empty($testiDetails->picture) && isset($testiDetails->picture)) {
				$_image = $testiDetails->picture;
				unlink(realpath('uploads/team_images/' . $_image));
				unlink(realpath('uploads/team_images/large/' . $_image));
				unlink(realpath('uploads/team_images/medium/' . $_image));
				unlink(realpath('uploads/team_images/small/' . $_image));
			}

			$where_conditions_b = array('posts_id' => $testimonial_id, 'p_c_type' => 'TM');
			$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_b);
			$where_conditions_d = array('id' => $testimonial_id);
			$deleted = $this->base_model->deleteWithWhereConditions('team', $where_conditions_d);

			$this->session->set_flashdata('team_success', 'Team has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/team_list");
			}
		}
	}
	//Team Coding End
	//Free Seo Audit Page
	public function free_seo_audit_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'Free SEO Audit Page';
		$where_column['content_type ='] = 'TopMiddleContent';
		$where_column['page_id ='] = FREE_SEO_AUDIT_PAGE_ID;
		$TopMiddleContent = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['TopMiddleContent'] = $TopMiddleContent;

		$where_column['content_type ='] = 'LeftContent';
		$LeftContent = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['LeftContent'] = $LeftContent;

		$where_column['content_type ='] = 'MiddleContent';
		$MiddleContent = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['MiddleContent'] = $MiddleContent;

		$where_column['content_type ='] = 'RightContent';
		$RightContent = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['RightContent'] = $RightContent;

		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/free-seo-audit-page', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_free_seo_audit_content()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('content_type', TRUE) && !empty($this->input->post('content_type', TRUE))) {
			// 	echo "<pre>";print_r($_POST);
			// die;
				$where_column['content_type ='] = $this->input->post('content_type', TRUE);
				$where_column['page_id ='] = FREE_SEO_AUDIT_PAGE_ID;
				$bannerDetails = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
				$bannerdata = json_decode($bannerDetails->json_content);
				$img = $bannerdata->image;
				//Check whether user upload slider image
				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = 'uploads/free_seo_audit_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['image']['name'];
					$bannerfilename = 'uploads/free_seo_audit_images/' . $img;
					if (file_exists($bannerfilename) && !empty($img) && isset($img)) {
						$_image = $img;
						unlink(realpath('uploads/free_seo_audit_images/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('image')) {
						$uploadData = $this->upload->data();
						$banner_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/free_seo_audit_images';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$banner_image = $img;
					}
				} else {
					$banner_image = $img;
				}
				$existDataArray = array(
					'title' => $this->input->post('title', TRUE),
					'image' =>  $banner_image,
					'content' =>  $this->input->post('content', TRUE),
				);
				$update_data['json_content'] =  json_encode($existDataArray);
				$update_data['updated_at'] =  $date;
				$where_conditions = array("content_type" => $this->input->post('content_type', TRUE), "page_id" => FREE_SEO_AUDIT_PAGE_ID);
				$last_inserted_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
			}
			if ($last_inserted_id) {
				$this->session->set_flashdata('seo_success', 'Data has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/free_seo_audit_page");
				}
			}
			$this->session->set_flashdata('seo_error', 'Data does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/free_seo_audit_page");
			}
		}
	}
	//End free seo audit page
	//Start Content Writing
	public function content_writing_page()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$data = array();
		$data['title'] = 'Content Writing Page';
		$where_column['content_type ='] = 'CWTopMiddleContent';
		$where_column['page_id ='] = CONTENT_WRITING_PAGE_ID;
		$TopMiddleContent = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['CWTopMiddleContent'] = $TopMiddleContent;

		$where_column['content_type ='] = 'CWFirstContent';
		$LeftContent = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['CWFirstContent'] = $LeftContent;

		$where_column['content_type ='] = 'CWSecondContent';
		$MiddleContent = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['CWSecondContent'] = $MiddleContent;

		$where_column['content_type ='] = 'CWThirdContent';
		$RightContent = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
		$data['CWThirdContent'] = $RightContent;

		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/content-writing-page', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_content_writing_content()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('page_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('content_type', TRUE) && !empty($this->input->post('content_type', TRUE))) {
			// 	echo "<pre>";print_r($_POST);
			// die;
				$where_column['content_type ='] = $this->input->post('content_type', TRUE);
				$where_column['page_id ='] = CONTENT_WRITING_PAGE_ID;
				$bannerDetails = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');
				$bannerdata = json_decode($bannerDetails->json_content);
				$img = $bannerdata->image;
				//Check whether user upload slider image
				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = 'uploads/content_writing_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['image']['name'];
					$bannerfilename = 'uploads/content_writing_images/' . $img;
					if (file_exists($bannerfilename) && !empty($img) && isset($img)) {
						$_image = $img;
						unlink(realpath('uploads/content_writing_images/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('image')) {
						$uploadData = $this->upload->data();
						$banner_image = $uploadData['file_name'];
						$this->load->library('image_lib');
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/content_writing_images';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						$this->image_lib->clear();
					} else {
						$banner_image = $img;
					}
				} else {
					$banner_image = $img;
				}
				$existDataArray = array(
					'title' => $this->input->post('title', TRUE),
					'image' =>  $banner_image,
					'content' =>  $this->input->post('content', TRUE),
				);
				$update_data['json_content'] =  json_encode($existDataArray);
				$update_data['updated_at'] =  $date;
				$where_conditions = array("content_type" => $this->input->post('content_type', TRUE), "page_id" => CONTENT_WRITING_PAGE_ID);
				$last_inserted_id = $this->base_model->update_entry('inner_page_content', $update_data, $where_conditions);
			}
			if ($last_inserted_id) {
				$this->session->set_flashdata('seo_success', 'Data has been updated successfully.');
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/content_writing_page");
				}
			}
			$this->session->set_flashdata('seo_error', 'Data does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/content_writing_page");
			}
		}
	}
	//End content writing

	// Client coding start

	public function client_list()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('client_module', 'access_read')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		$user_role = $this->session->userdata('logged_in_brijwasi_data')['user_role'];
		$search_criteria = $search_criteria_or = array();

		$serach_query = trim($this->input->get('serach-query', TRUE));
		$status = $this->input->get('status', TRUE);
		$cat_id = $this->input->get('cat_id', TRUE);
		$from = $this->input->get('date_from', TRUE);
		$to = $this->input->get('date_to', TRUE);

		if (!isset($serach_query) && !isset($status) && !isset($from) && !isset($to) && !isset($cat_id) && empty($status) && empty($serach_query) && empty($cat_id) && empty($from) && empty($to)) {
		}
		$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
		$status = (isset($status) && $status != '') ? $status : "";
		$cat_id = (isset($cat_id) && $cat_id != '') ? $cat_id : "";
		$from = (isset($from) && $from != '') ? $from : "";
		$to = (isset($to) && $to != '') ? $to : "";
		if (isset($from) && !empty($from) && $from != '') {
			$from = date("Y-m-d", strtotime($from));
			$search_criteria['DATE(client.date_added) >='] = $from;
		}
		if (isset($to) && !empty($to) && $to != '') {
			$to = date("Y-m-d", strtotime($to));
			$search_criteria['DATE(client.date_added) <='] = $to;
		}

		if (isset($status) && $status != '') {
			$search_criteria['client.status ='] = $status;
		}

		
		//$search_criteria['parent_id =']= 0;
		if (isset($cat_id) && $cat_id != '') {
			$search_criteria['p_category.categories_id ='] = $cat_id;
		}
		$search_criteria['category.type ='] = 'CL';
		$data['teamList'] = $this->admin_model->getClient($search_criteria, $order_by = 'client.id DESC');
		$search_criteria1 = array();
		$search_criteria1["parent_id ="] = 0;
		$search_criteria1["status ="] = 1;
		$search_criteria1['categories.type ='] = 'CL';
		$data['AllCatDetails'] = $this->base_model->getAllRows('categories', 'name ASC', $search_criteria1, 'id,name', $search_criteria_or);

		$data['title'] = 'Client  List';

		$data['searchcategoryKeyword'] = $serach_query;
		$data['statusKeyword'] = $status;
		$data['searchuserFromKeyword'] = $from;
		$data['searchuserToKeyword'] = $to;
		$data['catIdKeyword'] = $cat_id;
		$this->load->view('Admin/include/header', $data);
		$this->load->view('Admin/include/left-menu', $data);
		$this->load->view('Admin/client-list', $data);
		$this->load->view('Admin/include/footer');
	}

	public function add_client()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if ($this->input->method() === 'post') {
			//echo "<pre>";print_r($_POST);
			//die;
			$logged_in_id = $this->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
			$date = date("Y-m-d H:i:s");
			if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
				if (!getUserCan('client_module', 'access_write')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				$testimonialDetails = $this->base_model->getOneRecord("client", "id", $this->input->post('id', TRUE), "id,picture");
				//Check whether user upload slider image
				if (!empty($_FILES['picture']['name'])) {
					$config['upload_path'] = 'uploads/client_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['picture']['name'];
					$pagefilename = 'uploads/client_images/' . $testimonialDetails->picture;
					if (file_exists($pagefilename) && !empty($testimonialDetails->picture) && isset($testimonialDetails->picture)) {
						$_image = $testimonialDetails->picture;
						unlink(realpath('uploads/
						/' . $_image));
						unlink(realpath('uploads/client_images/large/' . $_image));
						unlink(realpath('uploads/client_images/medium/' . $_image));
						unlink(realpath('uploads/client_images/small/' . $_image));
					}
					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('picture')) {
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];
						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($picture, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/client_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/client_images/large/' . $picture);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/client_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/client_images/medium/' . $picture);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/client_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/client_images/small/' . $picture);
						}
						$this->image_lib->clear();
					} else {
						$picture = $testimonialDetails->picture;
					}
				} else {
					$picture = $testimonialDetails->picture;
				}
				$update_data = array(
					'picture' => $picture,
					'status' =>  $this->input->post('status', TRUE),
					'date_updated' =>  $date,
				);
				$where_conditions = array("id" => $this->input->post('id', TRUE));
				$last_inserted_id = $this->base_model->update_entry('client', $update_data, $where_conditions);
				try {
					$search_criteria1["posts_id ="] = $this->input->post('id', TRUE);
					$search_criteria1["p_c_type ="] = 'CL';
					$AllPostCatDetails = $this->base_model->getAllRows('post_categories', 'id ASC', $search_criteria1, 'posts_id,categories_id');
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$checked = 0;
							if (!empty($AllPostCatDetails)) {
								foreach ($AllPostCatDetails as $AllPostCatDetailsV) {
									if ($categoryIdArrayV == $AllPostCatDetailsV->categories_id) {
										$checked = 1;
									}
								}
							}
							if ($checked === 1) {
								$where_conditions_d = array('posts_id' => $this->input->post('id', TRUE), 'p_c_type' => 'CL');
								$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_d);
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'CL');
							} else {
								$postCategories[] = array('posts_id' => $this->input->post('id', TRUE), 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'CL');
							}
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			} else {
				if (!getUserCan('client_module', 'access_create')) {
					$this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
					redirect("admin/dashboard");
				}
				//Check whether user upload category image
				if (!empty($_FILES['picture']['name'])) {
					$config['upload_path'] = 'uploads/client_images/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif|svg';
					$config['file_name'] = $_FILES['picture']['name'];

					//Load upload library and initialize configuration
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('picture')) {
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];

						$this->load->library('image_lib');
						$dom = new DOMDocument('1.0', 'utf-8');
						$ext = pathinfo($picture, PATHINFO_EXTENSION);
						/* First size */
						$configSize1['image_library']   = 'gd2';
						$configSize1['source_image']    = $uploadData['full_path'];
						$configSize1['create_thumb']    = FALSE;
						$configSize1['maintain_ratio']  = TRUE;
						$configSize1['width']           = 800;
						$configSize1['height']          = 600;
						$configSize1['new_image']   = ROOT_PATH . '/uploads/client_images/large';

						$this->image_lib->initialize($configSize1);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '800');
							$svg->setAttribute('height', '600');
							$dom->save(ROOT_PATH . '/uploads/client_images/large/' . $picture);
						}
						$this->image_lib->clear();
						/* Second size */
						$configSize2['image_library']   = 'gd2';
						$configSize2['source_image']    = $uploadData['full_path'];
						$configSize2['create_thumb']    = FALSE;
						$configSize2['maintain_ratio']  = TRUE;
						$configSize2['width']           = 300;
						$configSize2['height']          = 300;
						$configSize2['new_image']   = ROOT_PATH . '/uploads/client_images/medium';

						$this->image_lib->initialize($configSize2);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '300');
							$svg->setAttribute('height', '300');
							$dom->save(ROOT_PATH . '/uploads/client_images/medium/' . $picture);
						}
						$this->image_lib->clear();
						/* Third size */
						$configSize3['image_library']   = 'gd2';
						$configSize3['source_image']    = $uploadData['full_path'];
						$configSize3['create_thumb']    = FALSE;
						$configSize3['maintain_ratio']  = TRUE;
						$configSize3['width']           = 90;
						$configSize3['height']          = 90;
						$configSize3['new_image']   =  ROOT_PATH . '/uploads/client_images/small';

						$this->image_lib->initialize($configSize3);
						$this->image_lib->resize();
						if ($ext == 'svg') {
							$dom->load($uploadData['full_path']);
							$svg = $dom->documentElement;
							if (!$svg->hasAttribute('viewBox')) { // viewBox is needed to establish
								// userspace coordinates
								$pattern = '/^(\d*\.\d+|\d+)(px)?$/'; // positive number, px unit optional

								$interpretable =  preg_match($pattern, $svg->getAttribute('width'), $width) &&
									preg_match($pattern, $svg->getAttribute('height'), $height);

								if ($interpretable) {
									$view_box = implode(' ', [0, 0, $width[0], $height[0]]);
									$svg->setAttribute('viewBox', $view_box);
								} else { // this gets sticky
									throw new Exception("viewBox is dependent on environment");
								}
							}

							$svg->setAttribute('width', '90');
							$svg->setAttribute('height', '90');
							$dom->save(ROOT_PATH . '/uploads/client_images/small/' . $picture);
						}
						$this->image_lib->clear();
					} else {
						$picture = '';
					}
				} else {
					$picture = '';
				}
				$insert_data = array(
					'picture' => $picture,
					'status' =>  $this->input->post('status', TRUE),
					'date_added' =>  $date,
				);
				$last_inserted_id = $this->base_model->insert_entry('client', $insert_data);
				try {
					$categoryIdArray = $this->input->post('category_id', TRUE);
					$postCategories = array();
					if (!empty($categoryIdArray)) {
						foreach ($categoryIdArray as $categoryIdArrayV) {
							$postCategories[] = array('posts_id' => $last_inserted_id, 'categories_id' => $categoryIdArrayV, 'p_c_type' => 'cl');
						}
					}
					if (!empty($postCategories)) {
						// Insert files data into the database
						$insert = $this->base_model->insert_multiple_entry('post_categories', $postCategories);
					}
				} catch (Exception $e) {
					echo 'Message: ' . $e->getMessage();
				}
			}
			if ($last_inserted_id) {
				if ($this->input->post('id', TRUE) && !empty($this->input->post('id', TRUE))) {
					$this->session->set_flashdata('team_success', 'Client has been updated successfully.');
				} else {
					$this->session->set_flashdata('team_success', 'Client has been added successfully.');
				}
				if ($this->agent->referrer()) {
					//redirect to some function
					redirect($this->agent->referrer());
				} else {
					redirect("admin/client_list");
				}
			}
			$this->session->set_flashdata('team_error', 'Client does not saved. Please try again!');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/client_list");
			}
		}
	}

	public function client_status()
	{

		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('client_module', 'access_write')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}

		if ($this->input->method() === 'get') {
			$do = trim($this->input->get('do', TRUE));
			$testimonial_id = trim($this->input->get('team_id', TRUE));
			switch ($do) {
				case 'pending':
					$update_data = array(
						'status' => 1
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('client', $update_data, $where_conditions);
					break;
				case 'publish':
					$update_data = array(
						'status' => 2
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('client', $update_data, $where_conditions);
					break;
				case 'draft':
					$update_data = array(
						'status' => 3
					);
					$where_conditions = array("id" => $testimonial_id);
					$last_inserted_id = $this->base_model->update_entry('client', $update_data, $where_conditions);
					break;
			}

			$this->session->set_flashdata('team_success', 'Client Status has been changed successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/client_list");
			}
		}
	}

	public function delete_client()
	{
		if ($this->admin_model->check_logged() === false) {
			redirect(base_url() . 'admin');
		}
		if (!getUserCan('client_module', 'access_delete')) {
            $this->session->set_flashdata('unauthorize_error', 'Unauthorized access. You do not have sufficient rights to do this action.');
			redirect("admin/dashboard");
		}
		if ($this->input->method() === 'post') {
			$testimonial_id = $this->input->post('team_id', TRUE);
			//print_r($_POST);
			//die;
			$testiDetails = $this->base_model->getOneRecord("client", "id", $testimonial_id, "id,picture");
			$postfilename = 'uploads/client_images/' . $testiDetails->picture;
			if (file_exists($postfilename) && !empty($testiDetails->picture) && isset($testiDetails->picture)) {
				$_image = $testiDetails->picture;
				unlink(realpath('uploads/client_images/' . $_image));
				unlink(realpath('uploads/client_images/large/' . $_image));
				unlink(realpath('uploads/client_images/medium/' . $_image));
				unlink(realpath('uploads/client_images/small/' . $_image));
			}

			$where_conditions_b = array('posts_id' => $testimonial_id, 'p_c_type' => 'CL');
			$deleted = $this->base_model->deleteWithWhereConditions('post_categories', $where_conditions_b);
			$where_conditions_d = array('id' => $testimonial_id);
			$deleted = $this->base_model->deleteWithWhereConditions('client', $where_conditions_d);

			$this->session->set_flashdata('team_success', 'Client has been deleted successfully.');
			if ($this->agent->referrer()) {
				//redirect to some function
				redirect($this->agent->referrer());
			} else {
				redirect("admin/client_list");
			}
		}
	}
	//Client Coding End

	// Logout from admin page
	public function logout()
	{
		// Removing session data
		/*$sess_array = array(
			'logged_in_id' => '',
			'screen_name' => '',
			'user_mail' => '',
		);
		$this->session->unset_userdata('logged_in_brijwasi_data', $sess_array);*/
		$isCheck = $this->session->unset_userdata('logged_in_brijwasi_data');
		if (empty($isCheck)) {
			$this->session->set_flashdata('login_success', 'Successfully! Logged Out!');
		} else {
			$this->session->set_flashdata('login_error', 'Failed! to Logged Out!');
		}
		//$this->session->sess_destroy();
		//$data['message_display'] = 'Successfully Logout';
		//$this->load->view('login',$data);
		redirect("/admin", 'refresh');
	}
}
