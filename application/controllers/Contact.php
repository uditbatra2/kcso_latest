<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo extends CI_Controller {

	public function __construct(){
		parent::__construct();
		// Your own constructor code
		//$this->db->cache_delete_all();
	}

	//Method to generate a unique api key every time
	private function _generateApiKey(){
		return md5(uniqid(rand(), true));
	}
	public function index()
	{
		$banner_id = 3;
		$data=array();
		$data['bannerDetails'] = $this->base_model->getOneRecord('brij_banners', 'id', $banner_id, '*');
		$where_column['content_type ='] = 'Trusted';
		$where_column['page_id ='] = SEO_PAGE_ID;
			
		$data['trustedData'] = $this->base_model->getOneRecordWithWhere('inner_page_content', $where_column, 'id, json_content');	
		$this->load->view('include/header',$data);
        $this->load->view('seo',$data);
	    $this->load->view('include/footer');
	}
	
}