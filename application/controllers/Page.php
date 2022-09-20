<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
	
	
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
		$criteria_cat=array("status"=>1, "parent_id"=>0);
		$data['cateData']=$this->base_model->getAllRows('brij_product_categories','id DESC',$criteria_cat);
		
		$criteria_slider=array("status"=>1);
		$data['sliderData']=$this->base_model->getAllRows('brij_sliders','id DESC',$criteria_slider);
		
		$criteria_banner=array("status"=>1);
		$data['bannerData']=$this->base_model->getAllRows('brij_banners','id DESC',$criteria_banner);
		
		$criteria_p=array("status"=>1,"is_new"=>1);
		$data['newArrivalsData']=$this->base_model->getAllRowsWithLimit('brij_products',$criteria_p, $order_by='rand()', $limit=12);
		
		$data['bestSellerData']=$this->product_model->getBestSellerProduct();
		
		$criteria_f=array("status"=>1,"is_popular"=>1);
		$data['featuredData']=$this->base_model->getAllRowsWithLimit('brij_products',$criteria_f, $order_by='rand()', $limit=4);
		
		$criteria_l=array("status"=>1);
		$data['latestProductData']=$this->base_model->getAllRowsWithLimit('brij_products',$criteria_l, $order_by='id DESC', $limit=4);
		
		$criteria_v=array("products.status"=>1,"product_recently_views.ip_address"=> $_SERVER['REMOTE_ADDR']);
		$data['mostViewedProductData']=$this->product_model->getMostViewedProducts($criteria_v, $order_by='rand()','', $limit=12);
		
		//echo "<pre>";print_r($data['mostViewedProductData']);
		
		//Page meta data
        $data['title'] = getSiteSettingValue(1);
        $data['site_description'] = getSiteSettingValue(3);
        $data['site_keyword'] = getSiteSettingValue(4);		
	    $this->load->view('include/header',$data);
        $this->load->view('include/menu',$data);
	    $this->load->view('index',$data);
	    $this->load->view('include/footer',$data);
	}
}
