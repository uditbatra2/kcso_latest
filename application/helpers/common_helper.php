<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter Array Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/array_helper.html
 */
// ------------------------------------------------------------------------

if (!function_exists('get_data')) {
	/**
	 * Function Name: get_data()
	 * $latitude => latitude.
	 * $longitude => longitude.
	 * Return => Full address of the given Latitude and longitude .
	 **/
	function get_data($url)
	{
		$ch = curl_init();
		$timeout = 900; // 900 Seconds = 15 Minutes 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

if (!function_exists('dateFormat')) {
	function dateFormat($format = 'd-m-Y', $givenDate = null)
	{
		if (!empty($givenDate) && $givenDate != '0000-00-00 00:00:00') {
			return date($format, strtotime($givenDate));
		}
		return false;
	}
}


if (!function_exists('humanTiming')) {
	/**
	 * Function Name: humanTiming()
	 * $time => time.
	 * Return => time of the given number of units.
	 **/
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
}

if (!function_exists('truncate')) {
	function truncate($string, $length, $stopanywhere = false)
	{
		//truncates a string to a certain char length, stopping on a word if not specified otherwise.
		if (strlen($string) > $length) {
			//limit hit!
			$string = substr($string, 0, ($length - 3));
			if ($stopanywhere) {
				//stop anywhere
				$string .= '...';
			} else {
				//stop on a word.
				$string = substr($string, 0, strrpos($string, ' ')) . '...';
			}
		}
		return $string;
	}
}

if (!function_exists('getLatLong')) {
	/**
	 * Function Name: getLatLong()
	 * $address => Full address.
	 * Return => Latitude and longitude of the given address.
	 **/
	function getLatLong($address)
	{
		if (!empty($address)) {
			//Formatted address
			$formattedAddr = str_replace(' ', '+', $address);
			//Send request and receive json data by address
			$geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false');
			//To specify a Google API key in your request, include it as the value of a key parameter.
			//$geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key=GoogleAPIKey');
			$output = json_decode($geocodeFromAddr);
			//Get latitude and longitute from json data
			$data['latitude']  = $output->results[0]->geometry->location->lat;
			$data['longitude'] = $output->results[0]->geometry->location->lng;
			//Return latitude and longitude of the given address
			if (!empty($data)) {
				return $data;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}



if (!function_exists('getLocation')) {
	/**
	 * Function Name: getLatLong()
	 * $latitude => latitude.
	 * $longitude => longitude.
	 * Return => Full address of the given Latitude and longitude .
	 **/
	function getLocation($latitude, $longitude)
	{
		if (!empty($latitude) && !empty($longitude)) {
			$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($latitude) . ',' . trim($longitude) . '&sensor=false';
			$json = @file_get_contents($url);
			$data = json_decode($json);
			$status = $data->status;

			//if request status is successful
			if ($status == "OK") {
				//get address from json data
				$location = $data->results[0]->formatted_address;
			} else {
				$location =  '';
			}
			//return address to ajax 
			return $location;
		} else {
			return false;
		}
	}
}

//get product images by product id;
if (!function_exists('getProductImage')) {
	/**
	 * Function Name: getProductImage()
	 * $product_id => product id.
	 * $limit => limit of images.
	 * Return => All image of product given by id .
	 **/
	function getProductImage($product_id = null, $limit = '')
	{
		//get main CodeIgniter object
		$ci = &get_instance();
		//load databse library
		$ci->load->database();
		//get data from database
		$query = $ci->db->get_where('brij_product_images', array('product_id' => $product_id, "status" => 1), $limit);
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result;
		} else {
			return false;
		}
	}
}


//get all categories;
if (!function_exists('getAllCategories')) {
	/**
	 * Function Name: getAllCategories()
	 * $limit => limit of categories.
	 * Return => All categories.
	 **/
	function getAllCategories($parent_id = 0, $limit = '')
	{
		//get main CodeIgniter object
		$ci = &get_instance();
		//load databse library
		$ci->load->database();
		//get data from database
		$conditions = array('parent_id' => $parent_id, "status" => 1);
		/* if(isset($parent_id) && !empty($parent_id)){
		   $conditions=array('parent_id'=>$parent_id,"status"=>1)
	   } */
		$query = $ci->db->get_where('brij_product_categories', $conditions, $limit);
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result;
		} else {
			return false;
		}
	}
}

//get Category value by id;
if (!function_exists('getCategory')) {
	/**
	 * Function Name: getCategory()
	 * $cat_id => id.
	 * Return => cat deatils by id .
	 **/
	function getCategory($cat_id = null)
	{
		//get main CodeIgniter object
		$ci = &get_instance();
		//load databse library
		$ci->load->database();
		//get data from database
		$query = $ci->db->get_where('brij_product_categories', array('id' => $cat_id, "status" => 1));
		if ($query->num_rows() > 0) {
			$result = $query->row();
			return $result;
		} else {
			return false;
		}
	}
}


//get site configure value by id;
if (!function_exists('getSiteSettingValue')) {
	/**
	 * Function Name: getSiteSettingValue()
	 * $site_id => id.
	 * Return => All site of value given by id .
	 **/
	function getSiteSettingValue($site_id = null)
	{
		//get main CodeIgniter object
		$ci = &get_instance();
		//load databse library
		$ci->load->database();
		//get data from database
		$query = $ci->db->get_where('brij_configuration', array('id' => $site_id, "active" => 1));
		if ($query->num_rows() > 0) {
			$result = $query->row();
			return $result->conf_value;
		} else {
			return false;
		}
	}
}

//get total cart items by session id
if (!function_exists('getTotalCartItems')) {
	/**
	 * Function Name: getTotalCartItems()
	 * $session_id => session_id.
	 * Return => All total amount by session_id.
	 **/
	function getTotalCartItems($session_id = null)
	{
		//echo $session_id;
		//get main CodeIgniter object
		$ci = &get_instance();
		//load databse library
		$ci->load->database();
		//get data from database
		$cart_total = $cart_quantity = $cart_items = $cart_items_tax = 0;
		$conditions = ' OrderItems.order_session_id="' . $session_id . '"';
		if ($ci->session->userdata('logged_in_brijwasi_user_data')['ID']) {
			$conditions = ' OrderItems.user_id="' . $ci->session->userdata('logged_in_brijwasi_user_data')['ID'] . '"';
		}

		$stmt = $ci->db->query("SELECT Products.price,OrderItems.* FROM `brij_products` as Products inner join brij_order_items as OrderItems on Products.id=OrderItems.product_id where Products.status=1 and OrderItems.order_item_status=0 and" . $conditions . " order by OrderItems.id asc");
		$carts_details = $stmt->result();
		//pr($carts_details);
		//tax calculate
		$taxrate = 0; // % value
		foreach ($carts_details as $carts_detail) {
			if (!empty($carts_detail)) {
				$cart_total += $carts_detail->product_price;
				$cart_quantity += $carts_detail->product_quantity;
				$cart_items += count($carts_detail->id);
				$cart_total_price = $carts_detail->product_price;
				$item_sub = $cart_total_price;
				$item_total = $item_sub * ($taxrate / 100);
				$cart_items_tax += $item_total;
			}
		}

		$carts_array = array('cart_total' => $cart_total, 'cart_quantity' => $cart_quantity, 'cart_items' => $cart_items, 'cart_items_tax' => $cart_items_tax, 'grand_total_with_tax' => $cart_total + $cart_items_tax);
		return $carts_array;
	}
}

//get total product by critiriea
if (!function_exists('getProductCountPrice')) {
	/**
	 * Function Name: getProductCountPrice()
	 * $array => array.
	 * Return => All total price by array.
	 **/
	function getProductCountPrice($url_query, $price, $s_q)
	{
		//get main CodeIgniter object
		$ci = &get_instance();
		//load databse library
		$ci->load->database();
		//echo $url_query;
		$url_q = explode("=", $url_query);
		$id_type = trim($url_q[0]);
		if (isset($id_type) && !empty($id_type)) {
			$search_criteria = $search_criteria_or = array();
			$serach_query = trim($s_q);
			$price = trim($price);

			if (isset($id_type) && $id_type == 'cat_id') {
				$cat_id = $url_q[1];
			}
			if (isset($id_type) && $id_type == 'sub_cat_id') {
				$sub_cat_id = $url_q[1];
			}

			if (!isset($cat_id) && !empty($cat_id)) {
			}

			$serach_query = (isset($serach_query) && $serach_query != '') ? $serach_query : "";
			$price = (isset($price) && $price != '') ? $price : "";

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
			$search_criteria['brij_products.status ='] = 1;
			$productList = $ci->product_model->getProducts($search_criteria, $order_by, $search_criteria_or);
			return count($productList);
		}
	}
}

//get site configure value by id;
if (!function_exists('getUserCan')) {
	/**
	 * Function Name: getUserCan()
	 * $moduleName => module_name, $accessName => access_name.
	 * Return => All module_name of access_name .
	 **/
	function getUserCan($moduleName = NULL, $accessName = NULL)
	{
		//get main CodeIgniter object
		$ci = &get_instance();
		//load databse library
		$ci->load->database();
		//get data from database
		$logged_in_id = $ci->session->userdata('logged_in_brijwasi_data')['logged_in_id'];
		$user_role = $ci->session->userdata('logged_in_brijwasi_data')['user_role'];
		$query = $ci->db->get_where('brij_admin_permissions', array('user_id' => $logged_in_id, "role_id" => 2, 'module_name' => $moduleName, $accessName => 'Yes'));
		$permissionFlag = false;
		if ($query->num_rows() > 0) {
			$permissionFlag = true;
		} else if (isset($user_role) && $user_role == 1) {
			$permissionFlag = true;
		}
		return $permissionFlag;
	}
}
